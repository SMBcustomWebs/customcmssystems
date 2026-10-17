<cms:ignore>
    =========================================================================
    CONTACT BLOCK  -  ROUTER
    snippets/pb_mods/contact/router/cnt_frm.inc

    Every class string, id and fallback the themes print is built here. The
    themes stay markup.

    WHAT THIS FILE IS FOR, in one sentence: a hidden field still holds its
    value. conditional-fields.php compiles not_active into browser JavaScript
    - it hides a control in the admin and does nothing whatever to the stored
    row. So an author who ticks Customize Submit Button, picks a lurid green,
    then unticks the box has a green button on the page and no visible setting
    that explains it. Reading the CHECKBOX rather than the values is the only
    thing that makes unticking mean what it says.

    Three of the four gates work that way. The fourth deliberately does not:

        cnt_hdr_rmv_opt   whole header block, on or off      gate honored
        cnt_frm_cust_opt  field appearance                   gate honored
        cnt_btn_cust_opt  everything about the button        gate honored
        cnt_aft_cust_opt  thank-you wording and subject      READ STRAIGHT

    The after-send fields are wording with working defaults, not styling. An
    untouched form still has to send with a real subject line and show a
    sensible thank-you, so those three are read whatever the checkbox says -
    ticking it only makes them editable. They fall back on EMPTY instead,
    which is the other half of the same job: cnt_frm.htm cannot mark them
    required, because a required field inside a JavaScript-hidden group blocks
    the save and points at something the editor cannot see.

    TWO KINDS OF IDENTIFIER, and confusing them breaks the form:

      cnt_uid  is regenerated on every render and is used ONLY to pair a
               label's for= with its input's id=. That pairing lives and dies
               inside one response, so a fresh value each time is correct and
               guarantees two tiles on one page never collide.

      the FORM NAME is passed by each theme and must be STABLE, because the
      browser posts it back and Couch matches the response to it. A random
      name would mean no submission was ever recognized. Each tile template
      therefore hardcodes its own name.

    INITIALIZE EVERYTHING. scope='global' writes __ROOT__, which outlives a
    tile - a var set only inside a cms:if would bleed into the next contact
    section on the same page.
    =========================================================================
</cms:ignore>
<cms:set cnt_uid = "cnt_<cms:random_name />" scope='global' />

<cms:set cf_edit      = '' scope='global' />
<cms:set cf_sec_pad   = '' scope='global' />
<cms:set cf_min_h_css = '' scope='global' />
<cms:set cf_min_h     = '' scope='global' />
<cms:set cf_bg        = '' scope='global' />
<cms:set cf_box       = '' scope='global' />
<cms:set cf_box_halg  = '' scope='global' />
<cms:set cf_txt_halg  = '' scope='global' />

<cms:set cf_show_hdr  = '' scope='global' />
<cms:set cf_show_hdg  = '' scope='global' />
<cms:set cf_show_txt1 = '' scope='global' />
<cms:set cf_show_txt2 = '' scope='global' />
<cms:set cf_show_hr   = '' scope='global' />
<cms:set cf_hdg_cls   = '' scope='global' />
<cms:set cf_txt1_cls  = '' scope='global' />
<cms:set cf_txt2_cls  = '' scope='global' />
<cms:set cf_hr_cls    = '' scope='global' />

<cms:set cf_frm_cust  = '' scope='global' />
<cms:set cf_lbl_cls   = '' scope='global' />
<cms:set cf_fld_cls   = '' scope='global' />
<cms:set cf_gap_cls   = '' scope='global' />

<cms:set cf_btn_cls   = '' scope='global' />
<cms:set cf_btn_wrap  = '' scope='global' />
<cms:set cf_btn_box   = '' scope='global' />
<cms:set cf_btn_txt   = '' scope='global' />
<cms:set cf_btn_icon  = '' scope='global' />

<cms:set cf_ok_ttl    = '' scope='global' />
<cms:set cf_ok_txt    = '' scope='global' />
<cms:set cf_subj      = '' scope='global' />


<cms:ignore>
    =========================================================================
    IS INLINE EDITING BEING DRAWN RIGHT NOW.

    Worked out once and stored, because both themes and all four inline files
    ask the same question and the answer cannot change inside one render. It
    used to be written out nine times; nine copies of a condition is nine
    chances for one of them to drift.

    BOTH HALVES ARE REQUIRED. The access level alone would draw controls for a
    logged-in author who has switched editing off; the session flag alone
    would draw them for anyone who ever had it set.
    =========================================================================
</cms:ignore>
<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />")>
    <cms:set cf_edit = '1' scope='global' />
</cms:if>


<cms:ignore>
    =========================================================================
    SECTION. Padding on the section, the height floor on the inner box - see
    the note in _val/min-height.htm for why they must be different elements.
    The != '' guard matters: PHP reads the string "0" as false, so a plain
    cms:if would swallow a deliberate "remove all spacing".
    =========================================================================
</cms:ignore>
<cms:if cnt_py_ht != ''><cms:capture into='cf_sec_pad' scope='global'>py-<cms:show cnt_py_ht /></cms:capture></cms:if>

<cms:ignore>
    THE EDITING FLOOR. The section cluster is a vertical stack of four buttons
    pinned inside the top-left corner, and it has to fit inside the section or
    it spills over whatever comes next on the page.

    The arithmetic rather than a guessed number: four btn-sm circles at 31px,
    three 8px gaps between them, and the 16px the stack is inset from the top,
    comes to 164px. 12rem is 192px, which clears it with room left over.

    RECOMPUTE THIS IF A BUTTON IS EVER ADDED OR REMOVED. It went to 14rem
    while the stack briefly held five, and came back when Remove Section
    Header was folded into the height window instead of holding its own.

    Only the choices below the floor are raised - unset, 6rem and 9rem. 12rem
    and up already clear the stack, so an author who deliberately asked for a
    tall section keeps exactly what they asked for. And the floor applies only
    while inline editing is switched on, so a visitor never sees a section
    stretched to fit controls that are not being drawn for them.
</cms:ignore>
<cms:if cnt_min_h><cms:set cf_min_h = cnt_min_h scope='global' /></cms:if>
<cms:if cf_edit>
    <cms:if cf_min_h = '' || cf_min_h = '6rem' || cf_min_h = '9rem'>
        <cms:set cf_min_h = '12rem' scope='global' />
    </cms:if>
</cms:if>
<cms:if cf_min_h>
    <cms:capture into='cf_min_h_css' scope='global'>#<cms:show cnt_uid /> .cnt-body { min-height: <cms:show cf_min_h />; }</cms:capture>
</cms:if>

<cms:ignore>
    No 'custom' sentinel to dodge any more - cnt_bg_clr points at
    _tok/color-bg_solid.htm, whose every option is a real token.
</cms:ignore>
<cms:if cnt_bg_clr><cms:capture into='cf_bg' scope='global'>bg-<cms:show cnt_bg_clr /></cms:capture></cms:if>

<cms:ignore>
    ===========================================================================
    cf_cap_thm - WHICH reCAPTCHA SKIN TO ASK GOOGLE FOR.

    Read by cnt_basic_frm.html only. The email-only tile carries no captcha, so
    it simply never uses this - same arrangement as cf_min_h, which exists only
    to build cf_min_h_css.

    WHY THIS IS DERIVED RATHER THAN TYPED. It shipped as a hardcoded
    theme='light', which is the one thing this block is not supposed to do: an
    appearance value that ignores what the author chose. It now follows
    cnt_bg_clr, the background they already picked, so the widget goes dark when
    the band does without anyone configuring a second thing.

    AND WHY IT IS NOT A NEW FIELD. Google's widget takes light or dark and
    nothing else - it is an iframe, so it cannot take the site's colors, fonts
    or corner radius whatever we do. A dropdown offering two values that could
    be inferred is a choice that earns nothing, and this block already deleted
    one of those (the 'custom' background escape) for the same reason.

    THE LIST IS EXPLICIT ON PURPOSE. The dark tokens are 'dark', 'black' and the
    numeric shades from 600 up - _tok/color-bg_solid.htm calls 600 "Mid Shade",
    which is the point the widget stops reading on a white card. A numeric
    comparison would be shorter and would also silently swallow a token that is
    not a number.

    EVERYTHING ELSE FALLS TO light, INCLUDING THE FOUR THEME TOKENS. primary,
    secondary, tertiary, quaternary and body resolve out of user.php per theme,
    so whether they are dark is not knowable here - on Scuro the primary is dark
    and on a pale theme it is not. light is the safe miss: a light widget on a
    dark band reads as a deliberate white card, where a dark widget on a light
    band reads as broken.
    ===========================================================================
</cms:ignore>
<cms:set cf_cap_thm = 'light' scope='global' />
<cms:if cnt_bg_clr eq 'dark'  || cnt_bg_clr eq 'black'
     || cnt_bg_clr eq '600'   || cnt_bg_clr eq '700'  || cnt_bg_clr eq '800'
     || cnt_bg_clr eq '900'   || cnt_bg_clr eq '1000' || cnt_bg_clr eq '1100'
     || cnt_bg_clr eq '1200'>
    <cms:set cf_cap_thm = 'dark' scope='global' />
</cms:if>

<cms:ignore>
    THE THREE LAYOUT DROPDOWNS ARE ROUTED RATHER THAN PRINTED RAW. All three
    carry real defaults and no empty option, so the themes could show them
    directly - and did. Routing them costs three lines and means a theme never
    has to know that cnt_box_halg is a bare token that needs justify-content-
    glued to the front while cnt_box_sz is already a complete class.
</cms:ignore>
<cms:set cf_box      = cnt_box_sz   scope='global' />
<cms:set cf_box_halg = cnt_box_halg scope='global' />
<cms:set cf_txt_halg = cnt_txt_halg scope='global' />


<cms:ignore>
    =========================================================================
    WHY THESE TEST THE FIELD DIRECTLY AND NOT WITH cms:is

    cms:is is an alias for arr_val_exists (tags.php:7836) and its first line is
    is_array($in). It answers "is this value one of the items in that ARRAY".

    Inside a cms:func that is exactly right: functions.php:4726 hands a
    checkbox to the func as get_checkbox_data(), a PHP array, so the four funcs
    in cnt_opts.htm that gate the admin groups use it correctly.

    OUT HERE IT IS ALWAYS FALSE. On the front end $CTX holds a checkbox as a
    pipe-separated STRING, is_array('1') is false, and cms:is returns '0' no
    matter what is saved. Every gate below silently took its else branch: the
    labels never hid, the button never took its color, and Remove Section
    Header looked like it worked only because "show" is ITS else branch.

    A BARE FIELD TEST IS CORRECT HERE because each of these checkboxes has
    exactly one option - opt_values='Customize=1'. Unchecked stores '', checked
    stores '1'. IF A SECOND OPTION IS EVER ADDED to one of them, this stops
    being safe and the test has to split the string on its separator; cms:is
    still will not do it.
    =========================================================================
</cms:ignore>
<cms:ignore>
    =========================================================================
    THE SECTION HEADER. One gate for the lot of it.

    cnt_hdr_rmv_opt is INVERTED - checking it removes. Every other gate in this
    block shows on checked, so the func in cnt_opts.htm reads hide-on-checked
    for this one alone and the same inversion has to be repeated here, because
    the func drives the admin and this drives the page.

    THE PAGE NEEDS ITS OWN TEST AT ALL, and that is the whole point of this
    file. not_active hides the group in the admin; the heading text is still in
    the database and the theme would still print it. Before this test existed,
    checking Remove Section Header changed nothing a visitor could see.

    THE EMPTY IF-BRANCH FOLLOWED BY cms:else IS THE INVERSION. Checking the
    box removes the header, so "show" is what belongs in the else. See the note
    above the gates for why this reads the field directly.
    =========================================================================
</cms:ignore>
<cms:if cnt_hdr_rmv_opt><cms:else />
    <cms:set cf_show_hdr = '1' scope='global' />

    <cms:if cnt_hdg_ln><cms:set cf_show_hdg = '1' scope='global' /></cms:if>
    <cms:if cnt_txt_opt ge '1' && cnt_txt1_ln><cms:set cf_show_txt1 = '1' scope='global' /></cms:if>
    <cms:if cnt_txt_opt ge '2' && cnt_txt2_ln><cms:set cf_show_txt2 = '1' scope='global' /></cms:if>
    <cms:if cnt_hdg_hr><cms:set cf_show_hr = '1' scope='global' /></cms:if>

    <cms:ignore>
        HEADING LEVEL is not routed at all. cnt_hdg_tag starts on h2 and its
        list carries no empty option, so it always holds a real tag name and
        the theme prints it directly. There was a cf_hdg_tag here that started
        at 'h2' and was only overwritten when the field held something - a
        second copy of the default, invisible to whoever set the first one.
        One value, one place.
    </cms:ignore>
    <cms:capture into='cf_hdg_cls' scope='global'><cms:if cnt_hdg_clr>text-<cms:show cnt_hdg_clr /> </cms:if><cms:if cnt_hdg_sz>fs-<cms:show cnt_hdg_sz /> </cms:if><cms:if cnt_hdg_font>font-<cms:show cnt_hdg_font /> </cms:if><cms:if cnt_hdg_fnt_wt>fw-<cms:show cnt_hdg_fnt_wt /> </cms:if><cms:if cnt_hdg_ltr_spc != ''>ls-<cms:show cnt_hdg_ltr_spc /> </cms:if></cms:capture>
    <cms:capture into='cf_txt1_cls' scope='global'><cms:if cnt_txt1_clr>text-<cms:show cnt_txt1_clr /> </cms:if><cms:if cnt_txt1_sz>fs-<cms:show cnt_txt1_sz /> </cms:if><cms:if cnt_txt1_font>font-<cms:show cnt_txt1_font /> </cms:if><cms:if cnt_txt1_fnt_wt>fw-<cms:show cnt_txt1_fnt_wt /> </cms:if><cms:if cnt_txt1_ltr_spc != ''>ls-<cms:show cnt_txt1_ltr_spc /> </cms:if></cms:capture>
    <cms:capture into='cf_txt2_cls' scope='global'><cms:if cnt_txt2_clr>text-<cms:show cnt_txt2_clr /> </cms:if><cms:if cnt_txt2_sz>fs-<cms:show cnt_txt2_sz /> </cms:if><cms:if cnt_txt2_font>font-<cms:show cnt_txt2_font /> </cms:if><cms:if cnt_txt2_fnt_wt>fw-<cms:show cnt_txt2_fnt_wt /> </cms:if><cms:if cnt_txt2_ltr_spc != ''>ls-<cms:show cnt_txt2_ltr_spc /> </cms:if></cms:capture>

    <cms:ignore>
        THE DIVIDING RULE.

        'my-' not 'py-': an hr is a border with no content box, so padding
        would move nothing. hr-size.htm and hr-thickness.htm both emit
        COMPLETE classes, so they are printed as-is and never prefixed.

        THE SIDE MARGINS FOLLOW THE TEXT ALIGNMENT rather than being hardcoded
        to mx-auto the way the content block does it. A short rule centered
        under left-aligned words looks like a mistake. Bootstrap's ms-/me-
        utilities are all !important, which they have to be - .hr-sm sets
        'margin: 1.25rem auto' as a shorthand, so anything less specific loses
        to it. my- is !important too and takes the vertical half back.
    </cms:ignore>
    <cms:if cf_show_hr>
        <cms:capture into='cf_hr_cls' scope='global'><cms:show cnt_hdg_hr /> <cms:if cnt_hr_wt><cms:show cnt_hr_wt /> </cms:if><cms:if cnt_hr_clr>text-<cms:show cnt_hr_clr /> </cms:if><cms:if cnt_hr_pd != ''>my-<cms:show cnt_hr_pd /> </cms:if><cms:if cf_txt_halg == 'center'>mx-auto<cms:else_if cf_txt_halg == 'end' />ms-auto me-0<cms:else />me-auto ms-0</cms:if></cms:capture>
    </cms:if>

</cms:if>


<cms:ignore>
    =========================================================================
    FORM FIELDS.

    'form-label' and 'form-control' are always present, gate or no gate - they
    are what makes a label a label and a box a box, not decoration. The gate
    only decides whether the four appearance dropdowns get to add to them.

    visually-hidden rather than display:none on a hidden label, because
    display:none takes the element out of the accessibility tree and the label
    stops existing for a screen reader. The whole reason for emitting a label
    element even when the design does not want to show one is that it keeps
    working for the people who cannot see it.

    THE UNGATED DEFAULTS ARE NOT GUESSES. mb-3 is the same value cnt_frm.htm
    ships as the default for cnt_frm_fld_gap, so ticking the box and touching
    nothing changes nothing on the page. The modern box shape - the padding,
    border, radius and focus ring - is not here at all; it is site-wide in
    assets/css/user.php, so every form on the site moves together.

    cf_frm_cust EXISTS SO THE INLINE FILE DOES NOT HAVE TO ASK AGAIN. The edit
    button dims when this gate is off, and one flag in the markup beats
    repeating the test inside a link_text attribute, where a nested tag would
    need a third level of quoting.
    =========================================================================
</cms:ignore>
<cms:if cnt_frm_cust_opt>
    <cms:set cf_frm_cust = '1' scope='global' />
    <cms:capture into='cf_lbl_cls' scope='global'>form-label<cms:if cnt_frm_lbl_vis> <cms:show cnt_frm_lbl_vis /></cms:if><cms:if cnt_frm_lbl_clr> text-<cms:show cnt_frm_lbl_clr /></cms:if></cms:capture>
    <cms:capture into='cf_fld_cls' scope='global'>form-control<cms:if cnt_frm_fld_sz> <cms:show cnt_frm_fld_sz /></cms:if></cms:capture>
    <cms:capture into='cf_gap_cls' scope='global'><cms:if cnt_frm_fld_gap != ''>mb-<cms:show cnt_frm_fld_gap /><cms:else />mb-3</cms:if></cms:capture>
<cms:else />
    <cms:set cf_lbl_cls = 'form-label'   scope='global' />
    <cms:set cf_fld_cls = 'form-control' scope='global' />
    <cms:set cf_gap_cls = 'mb-3'         scope='global' />
</cms:if>


<cms:ignore>
    =========================================================================
    THE SUBMIT BUTTON.

    'btn' base first, then {fill}-{color}. Bootstrap splits a button across two
    classes and needs both - .btn carries the padding, radius and sizing,
    .btn-{color} only sets colors. The variant alone renders as colored text
    with no button shape at all.

    UNTICKED IS A FIXED STRING, NOT A READ OF THE FIELDS. Every one of these
    dropdowns keeps whatever was last saved into it, so branching on "does the
    field hold something" would leave a customization in place after the box
    that offered it was switched off.

    THE ICON AND THE MARKUP IT DEMANDS. _cls/btn-icon-pos.htm emits a complete
    PAIR - btn-icon plus the side modifier - because every rule in theme.css is
    written .btn-icon.btn-icon-left, so the base is required for the modifier
    to match anything. It also requires the icon to be wrapped in a span
    carrying btn-brand INSIDE the button; theme.css positions that span
    absolutely to draw the tinted well, and a bare i tag gets no well and sits
    in the text flow, so the reserved padding shows as a gap. The themes print
    that span as the first child every time and let the class decide the side.

    BOTH HALVES OR NEITHER. A side with no Font Awesome class would reserve
    room for an icon that never arrives; a class with no side would style an
    icon that theme.css never positions. cf_btn_icon stays empty unless both
    are filled in, and the themes guard the span on it.
    =========================================================================
</cms:ignore>
<cms:if cnt_btn_cust_opt>

    <cms:if cnt_frm_btn_icon_pos && cnt_frm_btn_icon_fa>
        <cms:capture into='cf_btn_icon' scope='global'><cms:show cnt_frm_btn_icon_fa /><cms:if cnt_frm_btn_icon_clr> text-<cms:show cnt_frm_btn_icon_clr /></cms:if></cms:capture>
    </cms:if>

    <cms:capture into='cf_btn_cls' scope='global'>btn <cms:show cnt_frm_btn_fill />-<cms:show cnt_frm_btn_clr /><cms:if cnt_frm_btn_st> <cms:show cnt_frm_btn_st /></cms:if><cms:if cnt_frm_btn_sz> <cms:show cnt_frm_btn_sz /></cms:if><cms:if cnt_frm_btn_w> <cms:show cnt_frm_btn_w /></cms:if><cms:if cf_btn_icon> <cms:show cnt_frm_btn_icon_pos /></cms:if></cms:capture>

    <cms:if cnt_frm_btn_txt>
        <cms:set cf_btn_txt = cnt_frm_btn_txt scope='global' />
    <cms:else />
        <cms:set cf_btn_txt = 'Submit' scope='global' />
    </cms:if>

    <cms:capture into='cf_btn_wrap' scope='global'>text-<cms:show cnt_frm_btn_halg /></cms:capture>

    <cms:ignore>
        FULL WIDTH NEEDS A BLOCK WRAPPER OR IT IS NOT FULL WIDTH.

        The button sits inside a shrink-to-fit wrapper so its edit control can
        be pinned beside it rather than out at the column edge. w-100 is 100%
        OF THAT WRAPPER, and the wrapper is only as wide as its contents, so
        the two define each other in a circle - the browser settles it at the
        button's natural text width and Full Width silently does nothing.

        d-block breaks the circle: the wrapper takes the column, the button
        takes the wrapper. Every other width is a fraction of the column and
        wants the shrink-to-fit wrapper, so this swaps only for w-100.
    </cms:ignore>
    <cms:if cnt_frm_btn_w == 'w-100'>
        <cms:set cf_btn_box = 'd-block' scope='global' />
    <cms:else />
        <cms:set cf_btn_box = 'd-inline-block' scope='global' />
    </cms:if>

<cms:else />

    <cms:ignore>
        The plain button, spelled out. btn-primary is the site's own primary
        token, rounded-capsule is what _cls/btn-shape.htm calls Rounded, and
        the placement follows the section's Text Alignment so an untouched
        form reads as one column rather than a left-aligned form with a
        centered button under it.
    </cms:ignore>
    <cms:set cf_btn_cls = 'btn btn-primary rounded-capsule' scope='global' />
    <cms:set cf_btn_txt = 'Submit' scope='global' />
    <cms:set cf_btn_box = 'd-inline-block' scope='global' />
    <cms:capture into='cf_btn_wrap' scope='global'>text-<cms:show cf_txt_halg /></cms:capture>

</cms:if>


<cms:ignore>
    =========================================================================
    AFTER THE MESSAGE IS SENT. Read straight through the gate, on purpose -
    the reasoning is at the top of this file.

    THE SUBJECT LINE IS THE ONE THAT MATTERS BEYOND THIS PAGE. An empty
    Subject header is a strong spam signal, and mail from this domain has
    already been through a deliverability fight once. cnt_frm.htm cannot mark
    it required, so the substitution happens here and cms:send_mail can never
    be handed a blank one.
    =========================================================================
</cms:ignore>
<cms:if cnt_frm_ok_ttl>
    <cms:set cf_ok_ttl = cnt_frm_ok_ttl scope='global' />
<cms:else />
    <cms:set cf_ok_ttl = 'Thank You' scope='global' />
</cms:if>

<cms:if cnt_frm_ok_txt>
    <cms:set cf_ok_txt = cnt_frm_ok_txt scope='global' />
<cms:else />
    <cms:set cf_ok_txt = 'Your message has been sent. We will get back to you shortly.' scope='global' />
</cms:if>

<cms:if cnt_frm_subj>
    <cms:set cf_subj = cnt_frm_subj scope='global' />
<cms:else />
    <cms:set cf_subj = 'Website Contact Form' scope='global' />
</cms:if>
