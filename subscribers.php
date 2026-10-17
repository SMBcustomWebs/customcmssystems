<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:ignore>
    =========================================================================
    NO access_level ON THIS TEMPLATE, AND IT MUST STAY THAT WAY.

    It used to carry access_level='7'. page.php:891 kills any page save where
    the page's access level is higher than the SAVING USER'S level:

        if( $this->get_access_level($inherited) > $AUTH->user->access_level ){
            die( "Cheating?!" );
        }

    A visitor is level 0 and a customer is level 2, so every front-end write to
    this table died on a blank page reading "Cheating?!" - the registration
    form after the account was made and the mail sent, and the profile page on
    save. It looked like it worked during development only because the tester
    was signed in as an admin at level 10 in the same browser.

    THIS DOES NOT MAKE THE LIST PUBLIC. The admin panel is gated on its own:
    ccs_dash/index.php:9 calls $AUTH->check_access(K_ACCESS_LEVEL_ADMIN),
    so nobody below level 7 reaches the panel at all. access_level here added
    nothing to that, and it does not restrict cms:pages either - a diagnostic
    on 2026-09-20 proved reads are unaffected by it.

    The same attribute sits on orders.php, order-items.php and wishlist.php.
    Any of those written by a signed-in customer will die the same way.
    =========================================================================
</cms:ignore>
<cms:template title='Subscribers' clonable='1' parent='_member_' executable='0' order='9900'>
    
    <cms:editable name='subscriber_email' type='text' hidden='1' />

    <cms:ignore>
        =======================================================================
        THE RECORD FIELDS ARE LOCKED IN THE PANEL. THE STATUS DROPDOWN IS NOT.

        A consent record that the owner can type into is not evidence of
        anything - "it says so on your own system, which you can edit" answers
        itself. So the four fields that exist to be PROOF, plus the token, are
        rendered read-only, and Status stays a live control because correcting a
        row by hand is the reason it is a dropdown.

        HOW, BECAUSE COUCH HAS NO READ-ONLY EDITABLE. There is no 'readonly'
        attribute on cms:editable and no 'extra' passthrough reaching
        k_field_extra for admin fields - that only exists for cms:input on the
        front end. What core DOES give is a stable wrapper id per field,
        k_element_<name> (edit-mosaic-ex.php:344), and a message field renders
        arbitrary HTML. addons/extended/extended-users.php already uses exactly
        this to hide extended_user_id and extended_user_email. Same door.

        BELT AND BRACES. The CSS greys them and blocks pointer focus; the script
        sets the real readonly attribute so a keyboard tab cannot type into them
        either. With JavaScript off the CSS alone still signals "not yours to
        edit", and nothing breaks.

        WHAT THIS IS AND IS NOT. It stops accidents - a stray click, a paste in
        the wrong box, a well-meant tidy-up. It cannot stop a determined owner,
        and no admin UI can: anyone with this panel has the database behind it.
        That is not the bar. These records are credible because they were
        written automatically, at the time, by the same code for every
        subscriber - a contemporaneous business record. Hand-editability is what
        would undermine that, which is the whole reason for locking them.

        IF ONE GENUINELY HAS TO BE CORRECTED - a bad import, a migration - take
        the field name out of the selector list below, re-register, fix it, put
        it back. The friction is the point.
        =======================================================================
    </cms:ignore>
    <cms:editable name='subscriber_lock' type='message' order='1'>
        <style type="text/css">
            #k_element_subscriber_consent_ts input,
            #k_element_subscriber_consent_src input,
            #k_element_subscriber_consent_txt textarea,
            #k_element_subscriber_unsub_ts input,
            #k_element_subscriber_token input {
                pointer-events: none;
                background: #f1f1f1;
                color: #555;
                border-style: dashed;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                ['subscriber_consent_ts','subscriber_consent_src','subscriber_consent_txt',
                 'subscriber_unsub_ts','subscriber_token'].forEach(function (n) {
                    var w = document.getElementById('k_element_' + n);
                    if (!w) return;
                    w.querySelectorAll('input, textarea').forEach(function (el) {
                        el.setAttribute('readonly', 'readonly');
                        el.setAttribute('tabindex', '-1');
                    });
                });
            });
        </script>
    </cms:editable>

    <cms:ignore>
        THE CONSENT RECORD. Three fields, added 2026-08-21.

        A marketing list needs more than an address on it - it needs to be able
        to show, for any given person, WHEN they signed up, WHERE they signed
        up, and WHAT they were told at the time. Before this, the template
        stored an email and nothing else, so a complaint could not be answered.

        subscriber_consent_txt is the important one and the least obvious. The
        wording next to a sign-up box changes over time; storing the sentence
        the person actually agreed to is what makes the record proof rather
        than an assertion. Footer sign-up writes the same string it displays,
        from one variable, so the two cannot drift.

        NOT hidden, unlike subscriber_email. A record nobody can look at is not
        a record - these have to be readable in the panel on demand.

        NO IP ADDRESS, DELIBERATELY. It is common practice and it would make the
        proof stronger, but an IP is itself personal data: collecting it adds a
        disclosure obligation and a retention question for a list where
        timestamp, source and wording already establish consent. Add
        subscriber_consent_ip here if you decide you want it, and say so in the
        privacy policy at the same time.
    </cms:ignore>
    <cms:editable name='subscriber_consent_ts' type='text' label='Consent given'
        desc='When this person subscribed. Written automatically at sign-up.' />

    <cms:editable name='subscriber_consent_src' type='text' label='Consent source'
        desc='Which form they used. Written automatically at sign-up.' />

    <cms:editable name='subscriber_consent_txt' type='textarea' label='Wording agreed to'
        desc='The exact sentence shown next to the sign-up box at the time. This is the part that makes the record provable.' />

    <cms:ignore>
        THE UNSUBSCRIBE TOKEN, AND WHY IT IS NOT THE EMAIL ADDRESS.

        An unsubscribe link has to work for someone who is not logged in and may
        never have had an account, so it carries an identifier in the URL. If
        that identifier were the email address, the address would sit in
        referrer headers, server logs and browser history - and anyone could
        unsubscribe anyone else by guessing. A random token is unguessable,
        meaningless if leaked, and identifies exactly one row.

        Generated at sign-up by the footer form with random_bytes(16).
    </cms:ignore>
    <cms:editable name='subscriber_token' type='text' label='Unsubscribe token'
        desc='Random, generated at sign-up. It is the ?t= value in this subscriber unsubscribe link.' />

    <cms:ignore>
        UNSUBSCRIBING BLANKS THE ADDRESS RATHER THAN DELETING THE ROW.

        Couch has no db_delete tag - db_persist (the data-bound-form addon) is
        the only write available from a front-end template, and it can create or
        edit, not remove. Rather than reach past Couch into the database, the
        unsubscribe clears subscriber_email and the page title and marks the row
        here.

        THE PRIVACY OUTCOME IS THE SAME. The personal data is gone at the moment
        they ask, which is the part that matters. What remains is an anonymous
        stub - signed up on this date, left on that one - with nothing in it that
        identifies a person, so there is nothing left to retain, breach or
        produce on request.

        The owner can bulk-delete unsubscribed rows in the panel whenever they
        like. It is tidying, not a retention obligation.
    </cms:ignore>
    <cms:ignore>
        THREE VALUES, NOT TWO. 'pending' was added when account registration
        started offering the list.

            pending       Ticked the box at sign-up, has not clicked the
                          activation link yet. NOT a subscriber. Nothing may be
                          sent to a pending row - the address has passed a
                          format check and nothing else, and an address nobody
                          has proved they can read is exactly the address a
                          spam complaint comes from.
            subscribed    Confirmed. The only status a send loop may select.
            unsubscribed  Left. The address is blanked; the row is an anonymous
                          stub kept only so the date is recoverable.

        WHO WRITES WHAT:

          users/register.php            pending, promoted by the activation link
          contact block, email tile     pending, promoted by a confirmation mail
                                        (confirm-subscription.php)
          footer newsletter box         pending, promoted by a confirmation mail
                                        (confirm-subscription.php)
          users/profile.php             subscribed outright - the ONLY path that
                                        skips confirmation, and it can: someone
                                        signed in to their own account has proved
                                        the address more strongly than clicking a
                                        link would.

        BOTH PUBLIC FORMS ALSO CARRY AN INVISIBLE HONEYPOT. A per-IP throttle was
        tried and removed - it counted every page creation from the address, so
        saving a tile or placing an order blocked the next sign-up. The reasoning,
        and why there is no captcha on either form, is at the CAPTCHA SLOT note in
        snippets/pb_mods/contact/theme/cnt_email_frm.html.

        A SEND LOOP THAT FORGETS THE FILTER MAILS EVERY PENDING ROW. The filter
        is subscriber_status=='subscribed' and it is not optional; the pattern
        is written out in full at the top of unsubscribe.php.
    </cms:ignore>
    <cms:ignore>
        A DROPDOWN, NOT A TEXT BOX, AND THAT IS NOT COSMETIC.

        Every send loop selects on subscriber_status=='subscribed' - an exact
        string match. A text box let an admin type "Subscribed", "pendng" or a
        trailing space, and any of those silently removes that person from every
        future send with nothing on screen to explain it. A dropdown makes the
        three states the only three states, and field.php:252 rejects a value
        that is not one of the listed options, so a bad status can no longer be
        stored by any route.

        It is also the only place an admin can correct a row by hand, which is
        worth having: someone writes in asking to be added, a confirmation mail
        goes astray, a complaint needs answering.

        NO opt_selected - house rule, see orders.php. The first option is the
        default, and Pending is first ON PURPOSE. It only applies to a row an
        admin creates by hand (all four write paths set the status explicitly),
        and a hand-typed address that nobody has confirmed should not start out
        being mailed. Pick Subscribed deliberately if you are vouching for them.

        THE ONE MOVE THAT BITES: unsubscribed -> subscribed.

        Unsubscribing BLANKS the address (unsubscribe.php) - that is the whole
        privacy outcome. The row that remains is an anonymous stub, so setting it
        back to Subscribed produces a row a send loop will select with nothing to
        send to. If someone rejoins, let them use a sign-up form; that writes a
        fresh row with a new token and a real consent date.

        pending -> subscribed is a different thing and is legitimate: it skips
        the confirmation click, so you are asserting the address is theirs.
    </cms:ignore>
    <cms:editable name='subscriber_status' type='dropdown' label='Status'
        desc='Only Subscribed rows are ever mailed. Pending means they have not clicked their confirmation link yet. Do not move an Unsubscribed row back by hand - that person asked to be removed, and the only thing that should put them back is their own tick or their own click.'
        opt_values='Pending - not yet confirmed=pending | Subscribed=subscribed | Unsubscribed=unsubscribed'
        order='10' />

    <cms:ignore>
        A DATE, NOT A STATE - AND THE OLD LABEL SAID OTHERWISE.

        This was labelled "Unsubscribed", which sat directly under a Status
        dropdown whose third option is also "Unsubscribed". Two controls
        appearing to say the same thing, one of them a text box. Renamed to
        "Unsubscribed on", because that is all it is: the moment they left.

        IT IS NOT REDUNDANT WITH STATUS. Status says WHETHER, this says WHEN,
        and when is the part that cannot be reconstructed afterwards - the
        address is erased on unsubscribe, so the row becomes an anonymous stub
        holding two dates and nothing else. If someone ever says "you kept
        emailing me after I opted out", the pair of timestamps is the entire
        answer. Without this one there is no answer.

        It is also what makes tidying possible: bulk-deleting unsubscribed rows
        older than some cutoff needs a date to sort on.

        WRITTEN AUTOMATICALLY, THREE PLACES, NEVER READ BY ANY CODE:
          unsubscribe.php    stamps it when they use their link
          profile.php        stamps it when a signed-in user unticks the box
          profile.php        CLEARS it if they tick the box again, so a rejoined
                             row does not carry a stale leaving date

        LOCKED IN THE PANEL, like the rest of the consent record - see the note
        at the top of this file. It is evidence, and evidence you can type into
        proves nothing.
    </cms:ignore>
    <cms:editable name='subscriber_unsub_ts' type='text' label='Unsubscribed on'
        desc='The date and time they left, written automatically and not editable here. Blank while they are still subscribed. This is a record of WHEN - the Status dropdown above is what decides whether they get mailed.' />

    <cms:config_list_view orderby='publish_date' exclude='default-page' order='desc'>
       
    </cms:config_list_view>

</cms:template>
<?php COUCH::invoke(); ?>
