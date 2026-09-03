<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='Subscribers' clonable='1' parent='_global_' executable='0' order='9900'>
    
    <cms:editable name='subscriber_email' type='text' hidden='1' />

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
    <cms:editable name='subscriber_status' type='text' label='Status'
        desc='subscribed, or unsubscribed once they use their link.'>subscribed</cms:editable>

    <cms:editable name='subscriber_unsub_ts' type='text' label='Unsubscribed'
        desc='When they left. Blank while they are still subscribed.' />

    <cms:config_list_view orderby='publish_date' order='desc'>
       
    </cms:config_list_view>

</cms:template>
<?php COUCH::invoke(); ?>