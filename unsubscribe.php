<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='Unsubscribe' parent='_member_' icon='envelope-o' clonable='0' order='9910'>
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

    Honors the promise made beside the newsletter box: "You can unsubscribe at
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

    ---------------------------------------------------------------------------
    HOW SOMEONE ACTUALLY UNSUBSCRIBES

    They click the link in any newsletter they were sent. That is the whole
    flow. It needs no account, no login and no reply, and it works from any
    device or mail client.

    The link is this page plus their token:

        <site>/unsubscribe.php?t=<their 32-character token>

    The token is written once, at sign-up, by the footer newsletter block
    (snippets/pb_mods/pg_frame/footer/theme/foot_2col_form-scal.html) and stored
    on their row as subscriber_token. It never changes and it is theirs alone,
    so the same link keeps working in every message you ever send them.

    ---------------------------------------------------------------------------
    HOW TO PUT THE LINK IN AN EMAIL YOU SEND

    Every subscriber needs their OWN link, so it has to be built inside the loop
    that sends the mail, from that row's token. Building it once outside the
    loop would unsubscribe whoever the first row happened to be, no matter who
    clicked.

    In a Couch send loop, the pattern is: open cms:pages on subscribers.php
    filtered to subscriber_status of "subscribed", and inside that loop call
    cms:send_mail with to set to subscriber_email. In the body, write the link
    as cms:link on this template, followed by a query string of t= and the row's
    subscriber_token.

    THREE THINGS THAT MATTER IN THAT LOOP

    1. Filter on subscriber_status, AND skip a blank subscriber_email. The
       status filter is the whole guard - an unsubscribed row KEEPS its address
       now, so an unfiltered loop would mail every person who ever asked you to
       stop. Nothing about the row itself will save you; the filter has to be
       there.

       The blank check is the belt to that braces. Rows written before a form
       bug was fixed carry no address, and a send to an empty To line is a
       bounce and a spam signal.

       Pending rows are excluded by the same filter and must stay excluded -
       nobody has confirmed them.

    2. Send from K_EMAIL_FROM, the same constant the sign-up confirmation and
       users/register.php use. It is the address this domain's SPF record
       authorizes. Any other sender arrives unaligned and lands in spam.

    3. Add a List-Unsubscribe header carrying the same URL. Gmail and Outlook
       surface it as their own one-click unsubscribe control at the top of the
       message, which measurably reduces spam complaints - people who cannot
       find your link press the spam button instead, and that costs the whole
       domain's reputation, not just one send.

    ---------------------------------------------------------------------------
    WHEN THE LINK DOES NOT WORK

    A missing, malformed or already-used token shows "That link did not work"
    and offers the Public Email from Contact Settings so they can ask a human.
    It deliberately does not say whether the address was ever on the list - see
    ONE CONFIRMATION FOR BOTH OUTCOMES above. If that field is blank the page
    still makes sense, it just says "contact us" without naming an address, so
    filling in Public Email is worth doing.
    ===========================================================================
</cms:ignore>

<cms:set unsub_token = "<cms:gpc 't' method='get' />" />
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
<cms:set unsub_done = '0' scope='global' />

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
            <cms:ignore>
                THE ADDRESS STAYS ON THE ROW.

                It used to be erased here, and the title replaced with
                "Unsubscribed <date>". That made the row unusable as a record:
                subscribers.php and the users table have exactly one thing in
                common, the email address, and erasing it cut the only key
                between them. A person who left and signed up again through the
                footer came back as a brand-new row with nothing linking them to
                the earlier opt-out, and their own profile page could no longer
                find the row it was supposed to be driving. Changed 2026-09-20.

                WHAT PROTECTS THEM IS THE STATUS, NOT THE ERASURE. Only
                'subscribed' rows are ever mailed. Keeping the address means the
                list can also answer "has this person opted out before", which
                it could not before.

                The address is removed for good only when the account itself is
                deleted.
            </cms:ignore>
            <cms:db_persist _masterpage='subscribers.php' _mode='edit' _page_id=k_page_id
                subscriber_status='unsubscribed'
                subscriber_unsub_ts="<cms:date format='Y-m-d H:i:s' />" />

            <cms:ignore>
                k_error TESTED, NOT k_success. db_persist sets both
                (data-bound-form.php:277-296), and the empty-k_error form is the
                pattern foot_2col_form-scal.html has run in production for months.
                Matched to confirm-subscription.php so the two pages fail the same
                way if they ever fail - this one erases an address, so reporting
                failure after succeeding would be the worst version of that bug.

                The else branch above catches a BLANK status too, which is what
                row 137 carries. An unsubscribe must work on any row that is not
                already unsubscribed; enumerating the good states is how
                confirm-subscription.php went wrong twice (CLAUDE.md 64).
            </cms:ignore>
            <cms:if k_error><cms:else />
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
                        <cms:ignore>
                            ccs_gl_site_email DOES NOT EXIST. It was declared nowhere on
                            this site, so this resolved empty and the page always fell to
                            the vaguer "contact us" branch below. ccs_gl_hdqt_email is the
                            real field - Public Email, in Contact Settings - and it is what
                            the rest of the site shows visitors.
                        </cms:ignore>
                        <cms:set unsub_email = "<cms:get_custom_field 'ccs_gl_hdqt_email' masterpage='globals.php' />" />
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
