<?php require_once( '../ccs_dash/cms.php' ); ?>
<cms:template title="Close Account" hidden='1' />

<cms:ignore>
    ===========================================================================
    CLOSING AN ACCOUNT - THE HALF THAT ACTS
    users/delete-account.php

    Reached only from the link in the mail that users/profile.php sends:

        <site>/users/delete-account.php?t=<their 32-character token>

    The token is written when they press the button on their profile and
    cleared the moment this page finishes, so a link works exactly once.

    ---------------------------------------------------------------------------
    WHAT THIS DOES, AND WHAT IT DELIBERATELY DOES NOT

    The account is deleted outright - the Couch page AND the K_TBL_USERS row
    behind it. Nothing of the person is left, and their email address is free
    again the moment it runs.

    THE ORDERS ARE NOT TOUCHED AND DO NOT NEED TO BE. An order carries its own
    copy of everything it needs: order_first_name, order_last_name, order_email
    and both addresses are fields on orders.php, written at checkout. An order
    is a complete record on its own, so deleting the account cannot orphan one.
    order_user_id is left pointing at an id that no longer exists, and the only
    thing that reads it is my-orders.php.

    An earlier version of this page emptied the account and disabled it instead,
    to protect order history. That protection was never needed.

    THE SUBSCRIBER ROW IS DELETED OUTRIGHT. That is the difference between
    unsubscribing and closing an account. Unsubscribing keeps the address,
    because the address is the key between the two tables and the row is the
    evidence of what was agreed. Closing the account says stop holding my
    address at all, so the row goes.

    ---------------------------------------------------------------------------
    THE ELEVATION, AND WHY IT IS HERE

    auth/user.php:184 refuses to save a user row when

        the row is not the logged-in user  AND  its level >= the saver's level

    The person opening this link is signed out, at level 0, and the account is
    level 2. So the write dies on "Cheating?!" unless the level is lifted for
    the duration.

    THIS IS COPIED FROM COUCH, NOT INVENTED. extended-users.php does exactly
    the same thing in process_activation:

        $access_level = $AUTH->user->access_level;
        $AUTH->user->access_level = K_ACCESS_LEVEL_AUTHENTICATED + 1;
        $errors = $user->save();
        ...
        $AUTH->user->access_level = $access_level;

    Same constant, and the smallest value that clears the guard - not admin,
    not super admin. It is raised immediately before the one write and put
    back immediately after, inside a branch that only runs when an unguessable
    token matched exactly one account and the request is under a day old.
    ===========================================================================
</cms:ignore>

<cms:gpc var='t' method='get' into='del_token' scope='global' />

<cms:set del_done = '0' scope='global' />
<cms:set del_uid  = ''  scope='global' />
<cms:set del_mail = ''  scope='global' />
<cms:set del_when = ''  scope='global' />

<cms:ignore>
    A SEPARATE found FLAG. user_del_req_ts can be blank on a row that exists,
    and a blank field has answered the wrong question here before -
    CLAUDE.md sec.64.2. k_page_id is captured out of the loop because
    db_persist needs it and a nested lookup would shadow it.
</cms:ignore>
<cms:if del_token != ''>
    <cms:pages masterpage=k_user_template custom_field="user_del_token==<cms:show del_token />" show_unpublished='1' limit='1'>
        <cms:set del_uid  = k_page_id scope='global' />
        <cms:set del_mail = extended_user_email scope='global' />
        <cms:set del_when = user_del_req_ts scope='global' />
    </cms:pages>
</cms:if>

<cms:ignore>
    TWENTY-FOUR HOURS, MEASURED IN SECONDS. cms:date with format='U' gives a
    unix timestamp for both sides, so the comparison is arithmetic and no
    timezone or string ordering is involved.

    A request with no timestamp is treated as expired rather than as valid
    forever. A missing date is not a young date.
</cms:ignore>
<cms:set del_fresh = '0' scope='global' />
<cms:if del_uid && del_when>
    <cms:set del_age = "<cms:sub "<cms:date format='U' />" "<cms:date del_when format='U' />" />" scope='global' />
    <cms:if del_age lt '86400'>
        <cms:set del_fresh = '1' scope='global' />
    </cms:if>
</cms:if>

<cms:if del_fresh eq '1'>

    <cms:ignore>
        THE MAILING LIST ROW GOES FIRST, and on its own terms. subscribers.php
        carries no access_level any more, so this needs no elevation - see
        CLAUDE.md sec.64.7 for what that attribute cost when it was there.

        db_delete, not db_persist. db_persist has no delete mode.
    </cms:ignore>
    <cms:set del_sub_id = '' scope='global' />
    <cms:if del_mail>
        <cms:pages masterpage='subscribers.php' custom_field="subscriber_email==<cms:show del_mail />" limit='1'>
            <cms:set del_sub_id = k_page_id scope='global' />
        </cms:pages>
    </cms:if>
    <cms:if del_sub_id>
        <cms:db_delete masterpage='subscribers.php' page_id=del_sub_id />
    </cms:if>

    <cms:ignore>
        THE ACCOUNT ITSELF, DELETED.

        db_delete on the user's PAGE, not a KUser call. Deleting the page fires
        Couch's page_deleted event, which extended-users.php listens for
        (delete_user_account): it reads extended_user_id off the page and calls
        $user->delete() on the matching row in K_TBL_USERS, for any account at
        or below K_ACCESS_LEVEL_AUTHENTICATED. One call removes both halves.

        BOTH HALVES MATTER FOR THE EMAIL. The address lives on the KUser row and
        it has to be unique. Delete only the page and that row survives holding
        their address, and the person can never register again with it - which
        is the opposite of what closing an account is supposed to do.

        WHY DELETE AND NOT ANONYMIZE. An order carries its own copy of
        everything it needs - order_first_name, order_last_name, order_email and
        both addresses are fields on orders.php, written at checkout. Nothing in
        an order reads back through order_user_id except my-orders.php, which
        this person can no longer reach. So the account row is not holding the
        records up, and an emptied placeholder row per closure is just clutter
        that accumulates forever. Changed 2026-09-20.

        COMING BACK IS CLEAN. The address is free the moment this runs, so they
        can register with it again. They do NOT inherit their old orders:
        my-orders.php matches on order_user_id and a new account gets a new id,
        and order_claim.htm only ever attaches an order to somebody holding that
        order's own token from order-complete.php. Nothing is keyed on the email.
    </cms:ignore>
    <cms:php>
        global $AUTH;
        $GLOBALS['vsp_del_prev_level'] = $AUTH->user->access_level;
        $AUTH->user->access_level = K_ACCESS_LEVEL_AUTHENTICATED + 1;
    </cms:php>

    <cms:db_delete masterpage=k_user_template page_id=del_uid />

    <cms:php>
        global $AUTH;
        $AUTH->user->access_level = $GLOBALS['vsp_del_prev_level'];
    </cms:php>

    <cms:ignore>
        db_delete DIES ON FAILURE rather than reporting one - "if we are here,
        delete was successful (script would have died otherwise)",
        data-bound-form.php:381. So reaching this line IS the success test, and
        there is no k_error to read the way db_persist leaves one.
    </cms:ignore>
    <cms:set del_done = '1' scope='global' />

</cms:if>

<cms:embed 'pb_mods/pg_frame/head.htm' />
<cms:embed 'pb_mods/pg_frame/main-cap.htm' />
<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />

<section class="pt-0">
    <div class="container">

        <div class="row pt-6 pb-4">
            <div class="col-12">
                <h1 class="mb-0">Close Account</h1>
            </div>
        </div>

        <div class="row pb-6">
            <div class="col-lg-8">

                <cms:if del_done eq '1'>
                    <div class="alert alert-success shadow-sm" role="status">
                        <h2 class="alert-heading h5 mb-1">This account is closed</h2>
                        <p class="mb-2">
                            The account and everything on it &ndash; your name, your addresses
                            and your email address &ndash; has been deleted, and your email
                            address has been removed from our mailing list. Your password no
                            longer works and this account cannot be reopened.
                        </p>
                        <p class="mb-2 small">
                            Your past orders are kept, as we are required to keep records of
                            completed purchases. Each one still holds the name and address it
                            was shipped to, and only we can look them up now.
                        </p>
                        <p class="mb-0 small">
                            You are welcome back any time. The same email address can be used
                            to open a new account, and it will start empty.
                        </p>
                    </div>
                <cms:else />
                    <cms:ignore>
                        ONE MESSAGE FOR EVERY FAILURE. No token, a token nobody
                        recognises, one already used, one older than a day, or a write
                        that did not take - all land here. Telling a stranger which of
                        those it was tells them something about somebody else's account.
                    </cms:ignore>
                    <div class="alert alert-warning shadow-sm" role="alert">
                        <h2 class="alert-heading h5 mb-1">That link did not work</h2>
                        <p class="mb-0">
                            It may have expired, or already been used. Links to close an
                            account are good for 24 hours.
                        </p>
                        <p class="mb-0 mt-2">
                            Sign in and ask again from your profile to get a new one.
                        </p>
                    </div>
                </cms:if>

            </div>
        </div>

    </div>
</section>

<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
<cms:embed 'pb_mods/pg_frame/tail.htm' />

<?php COUCH::invoke(); ?>
