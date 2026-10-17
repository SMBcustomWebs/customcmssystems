<cms:ignore>
    =========================================================================
    CONTACT BLOCK  -  MAILING-LIST ROUTER
    snippets/pb_mods/contact/router/cnt_sub.inc

    Embedded by:  snippets/pb_mods/contact/theme/cnt_email_frm.html  ONLY
    Reads:        snippets/pb_mods/contact/embed/cnt_sub.htm

    Exports, both global:

        cs_notice   the sentence shown above the button AND stored on the record
        cs_src      what lands in subscriber_consent_src

    A SECOND ROUTER RATHER THAN TWO MORE LINES IN cnt_frm.inc, because
    cnt_frm.inc is embedded by both tiles and these fields exist on one. A
    shared router reading a field the basic tile never declares would not error
    - it would resolve empty forever - but it would put the mailing list into a
    file whose header says it serves both tiles.

    NO GATE. The email-only tile always collects; there is no state in which it
    does not, so there is nothing to switch on.

    ONE VARIABLE FOR THE NOTICE, DISPLAYED AND STORED. Not two fields saying
    similar things, and not a hardcoded copy in the theme. The notice is the
    whole of what the person was told, so the moment the shown text and the
    filed text come from different places they can disagree - and a record that
    might not match what was on the page is not worth keeping.
    =========================================================================
</cms:ignore>

<cms:ignore>
    THE FALLBACK IS NOT DECORATION. The field is editable and therefore
    erasable, and emptying it would mean addresses being filed with nothing
    shown to the person and nothing on the record - the one outcome this file
    exists to prevent. A blank puts the working sentence back.

    The stored default in cnt_sub.htm covers a NEW tile. This covers one an
    author has emptied.
</cms:ignore>
<cms:if cnt_sub_notice>
    <cms:set cs_notice = cnt_sub_notice scope='global' />
<cms:else />
    <cms:set cs_notice = 'Sending this form adds your email address to our mailing list once you confirm it by email. You can unsubscribe from any message we send.' scope='global' />
</cms:if>


<cms:ignore>
    THE SOURCE STRING IS FIXED, NOT AN AUTHOR FIELD.

    subscriber_consent_src answers "which form did this address come from" when
    a complaint arrives months later. An author-editable value would let the
    same form file two different answers over its life, which defeats the point.
    One tile type, one string.

    It does not name the page, because one tile type can sit on several pages
    and the subscriber row already carries its own creation date to place it.
</cms:ignore>
<cms:set cs_src = 'Contact block - email-only tile' scope='global' />

<cms:ignore>
    THE THANK-YOU WORDING, OVERRIDDEN FOR THIS TILE ONLY.

    cnt_frm.inc is embedded by both tiles and hands the themes a thank-you
    reading "Your message has been sent. We will get back to you shortly."
    That is true of the contact form and false here: this form sends no
    message to anybody and nobody gets back to them.

    THE TEST IS AGAINST THE DEFAULT TEXT, NOT AGAINST EMPTY. First attempt
    tested cnt_frm_ok_txt eq '' and changed nothing on screen, because that
    field is NOT empty on a registered tile - embed/common/cnt_frm.htm:317
    ships the sentence as the editable's stored default, so every tile owns a
    copy of it from the day it is created and cnt_frm.inc reads it as an
    author value.

    So the only honest question is whether the stored text is still the
    shipped default. If it is, nobody chose it and this tile gets wording that
    matches what it does. If an author typed anything else, theirs stands.

    Blank is covered too, since a field can be emptied after the fact.

    IT HAS TO SIT AFTER cnt_frm.inc in the theme, which it does - cnt_frm.inc
    on line 32, this file on line 38. Moving either embed breaks the override
    silently, because the last write to a global wins and there is nothing to
    error on.

    THE TEXT WORKS FOR EVERY OUTCOME, because it renders for all of them - a
    new address, one already on the list, a write that failed, and a bot that
    tripped the honeypot. It states what is true in all four, and the line the
    theme adds under it is true in only one.

    The heading is not overridden. "Thank You" fits both tiles.
</cms:ignore>
<cms:set cs_ok_shipped = 'Your message has been sent. We will get back to you shortly.' />

<cms:if cnt_frm_ok_txt eq cs_ok_shipped || cnt_frm_ok_txt eq ''>
    <cms:set cf_ok_txt = 'Your email address has been received.' scope='global' />
</cms:if>

<cms:ignore>
    THE SUBJECT LINE, OVERRIDDEN FOR THIS TILE ONLY.

    Same shape and same reason as the thank-you above: cnt_frm.inc serves both
    tiles and ships "Website Contact Form", which is right for the one that
    carries a name and a message and wrong for the one that only collects an
    address. In an inbox the two are indistinguishable.

    Tested against the shipped default, not against empty, because the field
    is NOT empty on a registered tile - embed/common/cnt_frm.htm:320 stores
    that sentence as the editable's default, so every tile owns a copy of it
    from the day it is created. An author who typed their own subject keeps it.
</cms:ignore>
<cms:set cs_subj_shipped = 'Website Contact Form' />

<cms:if cnt_frm_subj eq cs_subj_shipped || cnt_frm_subj eq ''>
    <cms:set cf_subj = 'Mailing list sign-up' scope='global' />
</cms:if>
