<cms:ignore>
    =========================================================================
    TRANSITION SIMPLE  -  router
    snippets/pb_mods/transns/router/trns_img_smp.inc

    Embedded as the first act of theme/trns_img_smp.html. Every dropdown token
    stored by the seven common/ embeds is turned into a finished class string or
    resolved value HERE, so the theme contains no styling logic.

    THE ONE RULE THAT MATTERS: one cms:if field per line inside every capture.
    The old theme wrote text-cms:show clr fs-cms:show sz unguarded, and
    every one of those dropdowns has an empty first option - so an untouched
    banner shipped literal "text- fs- fw- fst- ls- font-" in its class
    attribute. That is the defect this file exists to remove.
    =========================================================================
</cms:ignore>

<!-- ======================================================= -->
<!-- 1. THE THREE-LAYER BACKGROUND                           -->
<!-- ======================================================= -->
<cms:embed 'pb_mods/transns/router/trns_bg_engine.inc' />


<!-- ======================================================= -->
<!-- 2. INITIALISE EVERYTHING SET CONDITIONALLY              -->
<!-- __ROOT__ outlives a tile, so any var set only inside a  -->
<!-- cms:if would bleed into the next banner on the page.    -->
<!-- ======================================================= -->
<cms:set tis_txtbx_size = '' scope='global' />
<cms:set tis_txtm_classes = '' scope='global' />
<cms:ignore>
    TOP-LINE TAG. 'div' here is not a style default - it is the structural
    fallback meaning "no heading level was chosen", and it is exactly what the
    theme emitted before the dropdown existed. A heading is only minted when
    the user asks for one.
</cms:ignore>
<cms:set tis_txt1_tag = 'div' scope='global' />
<cms:set tis_txt1_classes = '' scope='global' />
<cms:set tis_txt2_classes = '' scope='global' />
<cms:set tis_fx_json = '' scope='global' />
<cms:set tis_has_fx = '0' scope='global' />
<cms:set tis_txtm_total = '0' scope='global' />
<cms:set tis_vid_src = '' scope='global' />
<cms:set tis_vid_attr = '' scope='global' />
<cms:set tis_c2a_grp_classes = '' scope='global' />
<cms:set tis_has_text = '0' scope='global' />
<cms:set tis_has_cta = '0' scope='global' />
<cms:set tis_has_vid = '0' scope='global' />


<!-- ======================================================= -->
<!-- 3. SECTION SPACING & TEXT BOX PLACEMENT                 -->
<!-- ======================================================= -->
<cms:capture into='tis_sec_pad' scope='global'><cms:if trns_py_ht>py-<cms:show trns_py_ht /></cms:if></cms:capture>

<cms:ignore>
    TEXT BOX WIDTH.
    trns_txt-box_size.htm ships complete column strings (col-12, col-lg-8, ...)
    plus a 'custom' escape that opens a repeatable of breakpoint+span pairs.

    The guard on the breakpoint token is the fix hero has and list_1 lacks:
    col-size.htm's first option is EMPTY (meaning "all devices"), so the
    unguarded form col-{sz}-{val} emits col--8 for a mobile-width row.
</cms:ignore>
<cms:if trns_txtbx_sz_opt = 'custom'>
    <cms:capture into='tis_txtbx_size' scope='global'><cms:show_repeatable 'trns_txtbx_cstm_sz_rpt'>col-<cms:if trns_txtbx_cstm_col_sz><cms:show trns_txtbx_cstm_col_sz />-</cms:if><cms:show trns_txtbx_cstm_col_val /> </cms:show_repeatable></cms:capture>
<cms:else />
    <cms:set tis_txtbx_size = trns_txtbx_sz_opt scope='global' />
</cms:if>
<cms:if tis_txtbx_size = ''><cms:set tis_txtbx_size = 'col-12' scope='global' /></cms:if>

<cms:ignore>
    h-align.htm and v-align.htm have no empty option, so these always resolve.
    Guarded anyway so a tile stored before the field existed cannot emit a bare
    prefix.

    NOTE the vertical one goes on the ROW, not the container. The old theme put
    align-items-{valg} on .container, which is neither a flex nor a grid box, so
    vertical placement silently did nothing. A .row IS a flex container.
</cms:ignore>
<cms:capture into='tis_txt_halg' scope='global'><cms:if trns_txt_halg>text-<cms:show trns_txt_halg /></cms:if></cms:capture>
<cms:capture into='tis_box_halg' scope='global'><cms:if trns_txtbx_halg>justify-content-<cms:show trns_txtbx_halg /></cms:if></cms:capture>
<cms:capture into='tis_box_valg' scope='global'><cms:if trns_txtbx_valg>align-items-<cms:show trns_txtbx_valg /></cms:if></cms:capture>


<!-- ======================================================= -->
<!-- 4. TEXT                                                 -->
<!-- ======================================================= -->
<cms:ignore>
    trns_txt_opt: '' = none, '1' = up to two fixed lines, '2' = a repeatable of
    many lines. Resolved to a single flag plus the mode so the theme branches
    once instead of testing the raw field repeatedly.
</cms:ignore>
<cms:if trns_txt_opt><cms:set tis_has_text = '1' scope='global' /></cms:if>

<cms:capture into='tis_txtm_classes' scope='global'>
    <cms:if trns_txtm_clr>text-<cms:show trns_txtm_clr /> </cms:if>
    <cms:if trns_txtm_sz>fs-<cms:show trns_txtm_sz /> </cms:if>
    <cms:if trns_txtm_font>font-<cms:show trns_txtm_font /> </cms:if>
    <cms:if trns_txtm_fnt_wt>fw-<cms:show trns_txtm_fnt_wt /> </cms:if>
    <cms:if trns_txtm_fnt_st>fst-<cms:show trns_txtm_fnt_st /> </cms:if>
    <cms:if trns_txtm_ltr_spc>ls-<cms:show trns_txtm_ltr_spc /> </cms:if>
</cms:capture>

<cms:if trns_txt1_tag><cms:set tis_txt1_tag = trns_txt1_tag scope='global' /></cms:if>

<cms:capture into='tis_txt1_classes' scope='global'>
    <cms:if trns_txt1_clr>text-<cms:show trns_txt1_clr /> </cms:if>
    <cms:if trns_txt1_sz>fs-<cms:show trns_txt1_sz /> </cms:if>
    <cms:if trns_txt1_font>font-<cms:show trns_txt1_font /> </cms:if>
    <cms:if trns_txt1_fnt_wt>fw-<cms:show trns_txt1_fnt_wt /> </cms:if>
    <cms:if trns_txt1_fnt_st>fst-<cms:show trns_txt1_fnt_st /> </cms:if>
    <cms:if trns_txt1_ltr_spc>ls-<cms:show trns_txt1_ltr_spc /> </cms:if>
</cms:capture>

<cms:ignore>
    The old theme read trns_txt1_font for the SECOND line's font-family - a
    copy-paste slip that made line 2's font follow line 1. Corrected to
    trns_txt2_font.
</cms:ignore>
<cms:capture into='tis_txt2_classes' scope='global'>
    <cms:if trns_txt2_clr>text-<cms:show trns_txt2_clr /> </cms:if>
    <cms:if trns_txt2_sz>fs-<cms:show trns_txt2_sz /> </cms:if>
    <cms:if trns_txt2_font>font-<cms:show trns_txt2_font /> </cms:if>
    <cms:if trns_txt2_fnt_wt>fw-<cms:show trns_txt2_fnt_wt /> </cms:if>
    <cms:if trns_txt2_fnt_st>fst-<cms:show trns_txt2_fnt_st /> </cms:if>
    <cms:if trns_txt2_ltr_spc>ls-<cms:show trns_txt2_ltr_spc /> </cms:if>
</cms:capture>

<cms:ignore>
    TYPED-TEXT EFFECT.
    trns_txt_fx_opt is a single-option checkbox, so it is compared with plain
    '=' at runtime - cms:is is correct only inside a cms:func, where the code
    compiles to JavaScript.

    NO class set at all now. The effect owns no static lead-in line and no
    per-word overrides: the typed span prints INSIDE line 2's div and inherits
    line 2's typography whole. The animation is the emphasis - a second color
    and weight on top of it only fought with the sentence it belongs to.
    All that is left to resolve here is the word list itself.

    The JSON array is built here rather than in the theme. It is data, and the
    separator logic - comma before every row except the first, because
    k_paginated_bottom is not available inside show_repeatable - is exactly the
    kind of thing that should not be sitting in markup.
</cms:ignore>
<cms:if trns_txt_fx_opt = '1'>
    <cms:capture into='tis_fx_json' scope='global'><cms:show_repeatable 'trns_txt_fx_rpt'><cms:if trns_txt_fx_txt_ln><cms:if k_count gt '1'>,</cms:if>"<cms:show trns_txt_fx_txt_ln />"</cms:if></cms:show_repeatable></cms:capture>
    <cms:if tis_fx_json><cms:set tis_has_fx = '1' scope='global' /></cms:if>
</cms:if>

<cms:ignore>
    Mode 2 prints an unknown number of lines, and the typed span has to land on
    the LAST one. show_repeatable exposes k_count but no total, so count the
    rows first - after the loop, tis_txtm_total holds the final k_count - and
    the render loop then tests k_count against it.
</cms:ignore>
<cms:if trns_txt_opt = '2'>
    <cms:show_repeatable 'trns_txtm_rpt'><cms:set tis_txtm_total = k_count scope='global' /></cms:show_repeatable>
</cms:if>


<!-- ======================================================= -->
<!-- 5. POPUP VIDEO                                          -->
<!-- ======================================================= -->
<cms:ignore>
    THE SOURCE WAS BEING PICKED TWICE, WITH DIFFERENT NUMBERING.

    trns_opts.htm's trns_vid_opt used 1=Uploaded, 2=YouTube, 3=Vimeo, while
    trns_vid.htm's trns_mdl_vid_src_opt uses 0=Upload, 1=YouTube, 2=Vimeo. The
    old theme mixed them: it tested trns_mdl_vid_src_opt='3' for Vimeo - a value
    that field can never hold, so that branch was dead - then fell back to
    trns_vid_opt for the other two.

    Resolved by giving each field one job: trns_vid_opt is now purely on/off
    (the gate for the whole group), and trns_mdl_vid_src_opt is the single
    source of truth for WHICH source. BigPicture's attribute name differs per
    source, so it is resolved here too.
</cms:ignore>
<cms:if trns_vid_opt = '1'>
    <cms:if trns_mdl_vid_src_opt = '1' && trns_mdl_ytb_id>
        <cms:set tis_has_vid = '1' scope='global' />
        <cms:capture into='tis_vid_attr' scope='global'>{"ytSrc":"<cms:show trns_mdl_ytb_id />"}</cms:capture>
    <cms:else_if trns_mdl_vid_src_opt = '2' && trns_mdl_vim_id />
        <cms:set tis_has_vid = '1' scope='global' />
        <cms:capture into='tis_vid_attr' scope='global'>{"vimeoSrc":"<cms:show trns_mdl_vim_id />"}</cms:capture>
    <cms:else_if trns_mdl_vid_src_opt = '0' && trns_mdl_vid_upl_vid_mp4 />
        <cms:set tis_has_vid = '1' scope='global' />
        <cms:capture into='tis_vid_attr' scope='global'>{"vidSrc":"<cms:show trns_mdl_vid_upl_vid_mp4 />"}</cms:capture>
    </cms:if>
</cms:if>

<cms:capture into='tis_vid_btn_mgn' scope='global'><cms:if trns_add_vid_btn_mgn>mt-<cms:show trns_add_vid_btn_mgn /></cms:if></cms:capture>


<!-- ======================================================= -->
<!-- 6. CALL-TO-ACTION LINKS                                 -->
<!-- ======================================================= -->
<cms:ignore>
    Rebuilt against hero/embed/hero_cta.htm. Four slots, each resolved
    identically and generated from one template so slot 3 cannot drift from
    slot 2.

    trns_c2a_ct_opt: '' = none, 1-4 = that many. The old theme tested != '0',
    but "No Link" stores an EMPTY string and never '0', so that test was always
    true and the CTA markup rendered even with nothing chosen.

    GONE FROM HERE: tis_dvd_classes. The divider between text links was gated
    on a row-level Button/Text-Only switch that no longer exists, and with
    button-or-link now decided per slot a row can mix the two.
</cms:ignore>
<cms:if trns_c2a_ct_opt><cms:set tis_has_cta = '1' scope='global' /></cms:if>

<cms:ignore>
    GROUP WRAPPER. Direction, gap and alignment come straight from the
    _cls / _tok files, so nothing is assembled by hand here.
</cms:ignore>
<cms:capture into='tis_c2a_grp_classes' scope='global'>d-flex flex-wrap w-100 <cms:if trns_c2a_mult_ord><cms:show trns_c2a_mult_ord /> </cms:if><cms:if trns_frmt_c2a_mg>gap-<cms:show trns_frmt_c2a_mg /> </cms:if><cms:if trns_c2a_halg>justify-content-<cms:show trns_c2a_halg /> align-items-<cms:show trns_c2a_halg /> </cms:if>mt-4</cms:capture>

<cms:ignore>--- SLOT 1 ---</cms:ignore>
<cms:set tis_c2a1_href = '' scope='global' />
<cms:set tis_c2a1_new  = '0' scope='global' />
<cms:set tis_c2a1_icon = '0' scope='global' />
<cms:set tis_c2a1_side = '' scope='global' />
<cms:set tis_c2a1_ext  = '0' scope='global' />
<cms:set tis_c2a1_classes = '' scope='global' />

<cms:if trns_c2a1_lnk = 'custom'>
    <cms:set tis_c2a1_href = trns_c2a1_cst_lnk scope='global' />
    <cms:set tis_c2a1_new  = '1' scope='global' />
<cms:else />
    <cms:set tis_c2a1_href = trns_c2a1_lnk scope='global' />
</cms:if>

<cms:ignore>
    An external URL supplies its own marker, and the embed hides the icon
    fields when one is chosen - so a custom destination can never carry a
    user icon. Testing it here too means a value saved BEFORE the destination
    was switched to Custom cannot leak through as a second icon.
</cms:ignore>
<cms:if trns_c2a1_lnk = 'custom'>
    <cms:set tis_c2a1_ext = '1' scope='global' />
<cms:else />
    <cms:if trns_c2a1_icon_fa && trns_c2a1_icon_pos>
        <cms:set tis_c2a1_icon = '1' scope='global' />
        <cms:if trns_c2a1_icon_pos = 'btn-icon btn-icon-right'>
            <cms:set tis_c2a1_side = 'right' scope='global' />
        <cms:else />
            <cms:set tis_c2a1_side = 'left' scope='global' />
        </cms:if>
    </cms:if>
</cms:if>

<cms:ignore>
    'link' in the color slot means plain text, no button chrome at all - so
    none of the button classes are assembled and the icon is a bare i tag
    rather than a btn-brand well, which is button-only in theme.css.
    Otherwise: {{fill}}-{{color}} assembles in ONE place, which is what stops
    btn--outline and btn-outlineprimary from ever appearing again.
</cms:ignore>
<cms:capture into='tis_c2a1_classes' scope='global'><cms:if trns_c2a1_clr = 'link'><cms:if trns_c2a1_lnk_clr>link-<cms:show trns_c2a1_lnk_clr /> </cms:if><cms:else />btn <cms:if trns_c2a1_clr><cms:if trns_c2a1_fill><cms:show trns_c2a1_fill /><cms:else />btn</cms:if>-<cms:show trns_c2a1_clr /> </cms:if><cms:if trns_c2a1_st><cms:show trns_c2a1_st /> </cms:if><cms:if trns_c2a1_sz><cms:show trns_c2a1_sz /> </cms:if><cms:if trns_c2a1_w><cms:show trns_c2a1_w /> </cms:if><cms:if tis_c2a1_icon = '1'><cms:show trns_c2a1_icon_pos /> </cms:if></cms:if></cms:capture>

<cms:ignore>--- SLOT 2 ---</cms:ignore>
<cms:set tis_c2a2_href = '' scope='global' />
<cms:set tis_c2a2_new  = '0' scope='global' />
<cms:set tis_c2a2_icon = '0' scope='global' />
<cms:set tis_c2a2_side = '' scope='global' />
<cms:set tis_c2a2_ext  = '0' scope='global' />
<cms:set tis_c2a2_classes = '' scope='global' />

<cms:if trns_c2a2_lnk = 'custom'>
    <cms:set tis_c2a2_href = trns_c2a2_cst_lnk scope='global' />
    <cms:set tis_c2a2_new  = '1' scope='global' />
<cms:else />
    <cms:set tis_c2a2_href = trns_c2a2_lnk scope='global' />
</cms:if>

<cms:ignore>
    An external URL supplies its own marker, and the embed hides the icon
    fields when one is chosen - so a custom destination can never carry a
    user icon. Testing it here too means a value saved BEFORE the destination
    was switched to Custom cannot leak through as a second icon.
</cms:ignore>
<cms:if trns_c2a2_lnk = 'custom'>
    <cms:set tis_c2a2_ext = '1' scope='global' />
<cms:else />
    <cms:if trns_c2a2_icon_fa && trns_c2a2_icon_pos>
        <cms:set tis_c2a2_icon = '1' scope='global' />
        <cms:if trns_c2a2_icon_pos = 'btn-icon btn-icon-right'>
            <cms:set tis_c2a2_side = 'right' scope='global' />
        <cms:else />
            <cms:set tis_c2a2_side = 'left' scope='global' />
        </cms:if>
    </cms:if>
</cms:if>

<cms:ignore>
    'link' in the color slot means plain text, no button chrome at all - so
    none of the button classes are assembled and the icon is a bare i tag
    rather than a btn-brand well, which is button-only in theme.css.
    Otherwise: {{fill}}-{{color}} assembles in ONE place, which is what stops
    btn--outline and btn-outlineprimary from ever appearing again.
</cms:ignore>
<cms:capture into='tis_c2a2_classes' scope='global'><cms:if trns_c2a2_clr = 'link'><cms:if trns_c2a2_lnk_clr>link-<cms:show trns_c2a2_lnk_clr /> </cms:if><cms:else />btn <cms:if trns_c2a2_clr><cms:if trns_c2a2_fill><cms:show trns_c2a2_fill /><cms:else />btn</cms:if>-<cms:show trns_c2a2_clr /> </cms:if><cms:if trns_c2a2_st><cms:show trns_c2a2_st /> </cms:if><cms:if trns_c2a2_sz><cms:show trns_c2a2_sz /> </cms:if><cms:if trns_c2a2_w><cms:show trns_c2a2_w /> </cms:if><cms:if tis_c2a2_icon = '1'><cms:show trns_c2a2_icon_pos /> </cms:if></cms:if></cms:capture>

<cms:ignore>--- SLOT 3 ---</cms:ignore>
<cms:set tis_c2a3_href = '' scope='global' />
<cms:set tis_c2a3_new  = '0' scope='global' />
<cms:set tis_c2a3_icon = '0' scope='global' />
<cms:set tis_c2a3_side = '' scope='global' />
<cms:set tis_c2a3_ext  = '0' scope='global' />
<cms:set tis_c2a3_classes = '' scope='global' />

<cms:if trns_c2a3_lnk = 'custom'>
    <cms:set tis_c2a3_href = trns_c2a3_cst_lnk scope='global' />
    <cms:set tis_c2a3_new  = '1' scope='global' />
<cms:else />
    <cms:set tis_c2a3_href = trns_c2a3_lnk scope='global' />
</cms:if>

<cms:ignore>
    An external URL supplies its own marker, and the embed hides the icon
    fields when one is chosen - so a custom destination can never carry a
    user icon. Testing it here too means a value saved BEFORE the destination
    was switched to Custom cannot leak through as a second icon.
</cms:ignore>
<cms:if trns_c2a3_lnk = 'custom'>
    <cms:set tis_c2a3_ext = '1' scope='global' />
<cms:else />
    <cms:if trns_c2a3_icon_fa && trns_c2a3_icon_pos>
        <cms:set tis_c2a3_icon = '1' scope='global' />
        <cms:if trns_c2a3_icon_pos = 'btn-icon btn-icon-right'>
            <cms:set tis_c2a3_side = 'right' scope='global' />
        <cms:else />
            <cms:set tis_c2a3_side = 'left' scope='global' />
        </cms:if>
    </cms:if>
</cms:if>

<cms:ignore>
    'link' in the color slot means plain text, no button chrome at all - so
    none of the button classes are assembled and the icon is a bare i tag
    rather than a btn-brand well, which is button-only in theme.css.
    Otherwise: {{fill}}-{{color}} assembles in ONE place, which is what stops
    btn--outline and btn-outlineprimary from ever appearing again.
</cms:ignore>
<cms:capture into='tis_c2a3_classes' scope='global'><cms:if trns_c2a3_clr = 'link'><cms:if trns_c2a3_lnk_clr>link-<cms:show trns_c2a3_lnk_clr /> </cms:if><cms:else />btn <cms:if trns_c2a3_clr><cms:if trns_c2a3_fill><cms:show trns_c2a3_fill /><cms:else />btn</cms:if>-<cms:show trns_c2a3_clr /> </cms:if><cms:if trns_c2a3_st><cms:show trns_c2a3_st /> </cms:if><cms:if trns_c2a3_sz><cms:show trns_c2a3_sz /> </cms:if><cms:if trns_c2a3_w><cms:show trns_c2a3_w /> </cms:if><cms:if tis_c2a3_icon = '1'><cms:show trns_c2a3_icon_pos /> </cms:if></cms:if></cms:capture>

<cms:ignore>--- SLOT 4 ---</cms:ignore>
<cms:set tis_c2a4_href = '' scope='global' />
<cms:set tis_c2a4_new  = '0' scope='global' />
<cms:set tis_c2a4_icon = '0' scope='global' />
<cms:set tis_c2a4_side = '' scope='global' />
<cms:set tis_c2a4_ext  = '0' scope='global' />
<cms:set tis_c2a4_classes = '' scope='global' />

<cms:if trns_c2a4_lnk = 'custom'>
    <cms:set tis_c2a4_href = trns_c2a4_cst_lnk scope='global' />
    <cms:set tis_c2a4_new  = '1' scope='global' />
<cms:else />
    <cms:set tis_c2a4_href = trns_c2a4_lnk scope='global' />
</cms:if>

<cms:ignore>
    An external URL supplies its own marker, and the embed hides the icon
    fields when one is chosen - so a custom destination can never carry a
    user icon. Testing it here too means a value saved BEFORE the destination
    was switched to Custom cannot leak through as a second icon.
</cms:ignore>
<cms:if trns_c2a4_lnk = 'custom'>
    <cms:set tis_c2a4_ext = '1' scope='global' />
<cms:else />
    <cms:if trns_c2a4_icon_fa && trns_c2a4_icon_pos>
        <cms:set tis_c2a4_icon = '1' scope='global' />
        <cms:if trns_c2a4_icon_pos = 'btn-icon btn-icon-right'>
            <cms:set tis_c2a4_side = 'right' scope='global' />
        <cms:else />
            <cms:set tis_c2a4_side = 'left' scope='global' />
        </cms:if>
    </cms:if>
</cms:if>

<cms:ignore>
    'link' in the color slot means plain text, no button chrome at all - so
    none of the button classes are assembled and the icon is a bare i tag
    rather than a btn-brand well, which is button-only in theme.css.
    Otherwise: {{fill}}-{{color}} assembles in ONE place, which is what stops
    btn--outline and btn-outlineprimary from ever appearing again.
</cms:ignore>
<cms:capture into='tis_c2a4_classes' scope='global'><cms:if trns_c2a4_clr = 'link'><cms:if trns_c2a4_lnk_clr>link-<cms:show trns_c2a4_lnk_clr /> </cms:if><cms:else />btn <cms:if trns_c2a4_clr><cms:if trns_c2a4_fill><cms:show trns_c2a4_fill /><cms:else />btn</cms:if>-<cms:show trns_c2a4_clr /> </cms:if><cms:if trns_c2a4_st><cms:show trns_c2a4_st /> </cms:if><cms:if trns_c2a4_sz><cms:show trns_c2a4_sz /> </cms:if><cms:if trns_c2a4_w><cms:show trns_c2a4_w /> </cms:if><cms:if tis_c2a4_icon = '1'><cms:show trns_c2a4_icon_pos /> </cms:if></cms:if></cms:capture>


<!-- ======================================================= -->
<!-- 7. EMPTY STATE                                          -->
<!-- ======================================================= -->
<cms:ignore>
    A brand-new tile has no text, no CTA, no video and no background. Rendering
    the section anyway leaves an empty band that reads as a layout bug. The
    theme gates on this.
</cms:ignore>
<cms:set tis_has_content = '0' scope='global' />
<cms:if tis_has_text='1' || tis_has_cta='1' || tis_has_vid='1' || trns_show_base='1' || trns_show_img='1'>
    <cms:set tis_has_content = '1' scope='global' />
</cms:if>
