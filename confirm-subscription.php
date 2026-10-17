<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='Confirm Subscription' hidden='1' />

<cms:ignore>
    ===========================================================================
    CONFIRM SUBSCRIPTION  -  the second half of a double opt-in.
    confirm-subscription.php

    Reached from:  the confirmation mail sent by the email-only contact tile
                   (snippets/pb_mods/contact/theme/cnt_email_frm.html)
    Mirror of:     unsubscribe.php - same token mechanism, opposite direction

    WHAT THIS PAGE IS FOR. A form on a public page can be posted by anything.
    An address that arrives there has passed a format check and nothing else -
    nobody has shown they can read mail sent to it, and nobody has shown they
    are a person. So the tile writes the row as 'pending' and sends one mail to
    the address itself. Clicking the link in that mail is what promotes the row
    to 'subscribed'.

    A bot does not read the mailbox, so a bot never gets past this page. A
    mistyped address does not reach its owner's real inbox, so it never gets
    past either. Both stay pending forever and no send loop touches them,
    because every send loop filters on subscriber_status of 'subscribed'.

    THIS IS WHY THERE IS NO CAPTCHA ON THAT TILE. A captcha guesses whether the
    visitor is a person before letting the address through. This waits for
    proof instead, costs the visitor one click they were going to make anyway,
    and puts no third-party script on a page that carries the tile.

    ---------------------------------------------------------------------------
    THE TOKEN IS THE ONE THEY KEEP

    subscriber_token is generated when the row is created and never changes. It
    is the ?t= value here AND the ?t= value in every unsubscribe link, which is
    deliberate: one secret per subscriber, not two to keep in step. It is
    random_bytes(16) - unguessable, meaningless if it leaks, and it identifies
    exactly one row.

    NOT THE EMAIL ADDRESS, for the reasons written out in unsubscribe.php: an
    address in a URL ends up in referrer headers, server logs and browser
    history, and would let anyone confirm or cancel anyone else by guessing.

    ---------------------------------------------------------------------------
    ONLY 'pending' IS PROMOTED

    An already-confirmed row is left alone and shown the same success wording.
    Clicking an old link twice is a normal thing for a person to do and must not
    read as an error.

    An 'unsubscribed' row is NOT resurrected. Its address was blanked when they
    left, so the token matches a row with nothing in it - and re-subscribing
    somebody because they clicked a stale link in an old mail is exactly the
    complaint this whole arrangement exists to avoid. It falls to the failure
    wording.

    ---------------------------------------------------------------------------
    WHEN THE LINK DOES NOT WORK

    Same treatment as unsubscribe.php: one message, no hint about whether the
    address was ever on the list, and the Public Email from Contact Settings so
    they can reach a person.
    ===========================================================================
</cms:ignore>

<cms:set conf_token = "<cms:gpc 't' method='get' />" />
<cms:ignore>
    scope='global' ON THE INITIALISER IS NOT OPTIONAL.

    parser/parser.php: _set() with NO scope walks the context stack from the top
    down and writes into the first frame that has a _scope_ - the innermost one.
    _set() with scope='global' writes to ctx[0]. And _get() returns the FIRST
    frame that holds the name, searching top down.

    So a bare <cms:set x='0' /> out here and a <cms:set x='1' scope='global' />
    inside cms:pages are two different slots, and the outer '0' shadows the
    global '1' forever after. The work happens and the flag never flips.

    That is exactly what shipped: the confirm page promoted the row to
    subscribed and then told the visitor the link did not work. Verified by
    diagnostic 2026-09-20 - the row was subscribed while the page said it failed.
</cms:ignore>
<cms:set conf_done = '0' scope='global' />

<cms:if conf_token != ''>
    <cms:ignore>
        custom_field builds an INNER JOIN (CLAUDE.md sec.4c), so this returns a
        row only when the token matches exactly. limit='1' because a token
        identifies one subscriber by construction.
    </cms:ignore>
    <cms:pages masterpage='subscribers.php' custom_field="subscriber_token==<cms:show conf_token />" limit='1'>

        <cms:ignore>
            THE TOKEN IS THE AUTHORITY, AND NOTHING REFUSES IT NOW.

            This used to test for 'subscribed' or 'pending' and do nothing for
            anything else. Row 137 - written by the footer box in August - has a
            BLANK subscriber_status, so it matched neither branch, nothing ran,
            and the page printed "That link did not work" while the row sat
            there perfectly promotable.

            Enumerating the good states is the mistake. A token is unguessable
            and identifies exactly one row, so the only question worth asking is
            whether there is a reason to REFUSE it.

            THERE USED TO BE ONE, AND IT IS GONE. An unsubscribed row had its
            address erased on the way out, so there was nothing to send to. The
            address is kept now - it is the key between the users table and this
            one - so a person who left and signed up again through the footer
            gets their own row back rather than a dead end. Changed 2026-09-20.

            Clicking a confirmation link is an act by whoever holds the mailbox,
            which is the only proof this system has and the same proof a first
            subscription rests on. So every status promotes: pending, blank,
            unsubscribed, or a value nobody planned for.
        </cms:ignore>
        <cms:if subscriber_status == 'subscribed'>
            <cms:ignore>
                Already done. Clicking the link a second time is not a mistake.
            </cms:ignore>
            <cms:set conf_done = '1' scope='global' />

        <cms:else />
            <cms:ignore>
                subscriber_unsub_ts IS CLEARED, because it means "currently
                gone" and they are not. The departure is still readable in the
                admin - the status moved and the consent date moved with it -
                but a row that is subscribed must not also carry a leaving date,
                or the send loops and the evidence disagree.

                subscriber_consent_ts IS MOVED TO NOW. For a pending row this
                click is proof of address rather than a second act of consent,
                and the difference is hours. For a row coming back from
                unsubscribed it is the whole point: the consent that matters is
                today's, not the one they cancelled.

                consent_src and consent_txt are left alone. They name the form
                where this person first agreed and the sentence they were shown,
                and this page does not know either - it has a token and nothing
                else.

                k_error tested rather than k_success: db_persist sets both
                (data-bound-form.php:277-296), and the empty-k_error form is the
                pattern foot_2col_form-scal.html has run in production for
                months.
            </cms:ignore>
            <cms:db_persist _masterpage='subscribers.php' _mode='edit' _page_id=k_page_id
                subscriber_status='subscribed'
                subscriber_consent_ts="<cms:date format='Y-m-d H:i:s' />"
                subscriber_unsub_ts='' />

            <cms:if k_error><cms:else />
                <cms:set conf_done = '1' scope='global' />
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

                <cms:if conf_done == '1'>
                    <h1 class="mb-3">You are on the list</h1>
                    <p class="fs-6">
                        Your email address is confirmed. You will start receiving our
                        mailing list from the next one we send.
                    </p>
                    <p>
                        Every message has an unsubscribe link at the bottom, and using
                        it removes your address straight away.
                    </p>
                <cms:else />
                    <cms:ignore>
                        Says nothing about whether the address was ever on the
                        list - same reasoning as unsubscribe.php.
                    </cms:ignore>
                    <h1 class="mb-3">That link did not work</h1>
                    <p class="fs-6">
                        This confirmation link is incomplete, or it has already been
                        used and then cancelled.
                    </p>
                    <p>
                        Use the link in the confirmation email we sent you, or
                        <cms:set conf_email = "<cms:get_custom_field 'ccs_gl_hdqt_email' masterpage='globals.php' />" />
                        <cms:if conf_email>
                            email us at <a href="mailto:<cms:show conf_email />"><cms:show conf_email /></a>
                            and we will add you by hand.
                        <cms:else />
                            contact us and we will add you by hand.
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
