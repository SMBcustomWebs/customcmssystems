<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='Unsubscribe' parent='_global_' icon='envelope-o' clonable='0' order='9910'>
    <cms:ignore>
        No editable fields. Every string on this page is fixed, because the two
        outcomes it can report - "you are unsubscribed" and "that link is not
        valid" - are statements of fact about what just happened, not wording an
        owner should be able to soften.
    </cms:ignore>
</cms:template>

<cms:ignore>
    ===========================================================================
    unsubscribe.php

    Honours the promise made beside the newsletter box: "You can unsubscribe at
    any time using the link in any message." Until this template existed that
    sentence was stored in every subscriber's consent record as the thing they
    had agreed to, with nothing behind it.

    HOW IT IDENTIFIES SOMEONE. ?t=<token>, a random 32-character value written
    at sign-up. Not the email address: an address in a URL ends up in referrer
    headers, server logs and browser history, and would let anyone unsubscribe
    anyone else by guessing. A token is unguessable and meaningless if it leaks.

    IT WORKS WITH NO ACCOUNT AND NO LOGIN, which is the entire point - most
    subscribers will never have either.

    IT DOES NOT DELETE THE ROW, BECAUSE IT CANNOT. Couch has no db_delete tag;
    db_persist (data-bound-form addon, :319) can create or edit and nothing
    else. Reaching past Couch into the database from a public template is not a
    trade worth making, so this clears the address and the page title and marks
    the row instead. The personal data is gone at the moment they ask - which is
    the part that matters - and what remains is an anonymous stub.

    ONE CONFIRMATION FOR BOTH OUTCOMES, DELIBERATELY. A valid token and an
    unrecognised one produce almost the same page. If a wrong token said "that
    address is not on our list", the page would answer the question "is this
    person a subscriber?" for anyone who tried - which is a disclosure in
    itself. Already-unsubscribed also reports success, because from the
    visitor's point of view it is true.

    NO CONFIRMATION STEP. Some senders make you click again to prove you meant
    it. Withdrawal has to be at least as easy as consent, and consent here was
    one click on a form, so leaving is one click on a link.

    GET, not POST, and that is a considered exception. A one-click link from an
    email cannot POST. The risk a prefetcher unsubscribes someone by following
    the link is real but small, and the alternative - a link that needs a second
    click - is worse both legally and for the person trying to leave.
    ===========================================================================
</cms:ignore>

<cms:set unsub_token = "<cms:gpc 't' method='get' />" />
<cms:set unsub_done = '0' />

<cms:if unsub_token != ''>
    <cms:ignore>
        custom_field builds an INNER JOIN (CLAUDE.md sec.4c), so this returns a
        row only when the token matches exactly. limit='1' because a token
        identifies one subscriber by construction.
    </cms:ignore>
    <cms:pages masterpage='subscribers.php' custom_field="subscriber_token==<cms:show unsub_token />" limit='1'>

        <cms:if subscriber_status == 'unsubscribed'>
            <cms:set unsub_done = '1' scope='global' />
        <cms:else />
            <cms:db_persist _masterpage='subscribers.php' _mode='edit' _page_id=k_page_id
                k_page_title="Unsubscribed <cms:date format='Y-m-d' />"
                subscriber_email=''
                subscriber_status='unsubscribed'
                subscriber_unsub_ts="<cms:date format='Y-m-d H:i:s' />" />

            <cms:if k_success>
                <cms:set unsub_done = '1' scope='global' />
            </cms:if>
        </cms:if>

    </cms:pages>
</cms:if>

<cms:embed 'pb_mods/pg_frame/head.htm' />
<cms:embed 'pb_mods/pg_frame/main-cap.htm' />
<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />

<section class="py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                <cms:if unsub_done == '1'>
                    <h1 class="mb-3">You have been unsubscribed</h1>
                    <p class="fs-6">
                        Your email address has been removed from our newsletter list.
                        You will not receive any further newsletters from us.
                    </p>
                    <p>
                        This does not affect any orders you have placed, or your account
                        if you have one. Those are separate, and nothing about them has
                        changed.
                    </p>
                <cms:else />
                    <cms:ignore>
                        Says nothing about whether the address was ever on the
                        list - see the header. It offers the one thing that is
                        actually useful instead.
                    </cms:ignore>
                    <h1 class="mb-3">That link did not work</h1>
                    <p class="fs-6">
                        This unsubscribe link is incomplete or has already been used.
                    </p>
                    <p>
                        Use the link in the most recent newsletter you received, or
                        <cms:set unsub_email = "<cms:get_custom_field 'ccs_gl_site_email' masterpage='globals.php' />" />
                        <cms:if unsub_email>
                            email us at <a href="mailto:<cms:show unsub_email />"><cms:show unsub_email /></a>
                            and we will remove you by hand.
                        <cms:else />
                            contact us and we will remove you by hand.
                        </cms:if>
                    </p>
                </cms:if>

                <p class="mt-4">
                    <a href="<cms:show k_site_link />">Back to the site</a>
                </p>

            </div>
        </div>
    </div>
</section>

<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>
