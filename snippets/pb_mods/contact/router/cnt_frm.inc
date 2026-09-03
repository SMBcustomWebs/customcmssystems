<cms:ignore>
    =========================================================================
    CONTACT BLOCK  -  ROUTER
    snippets/pb_mods/contact/router/cnt_frm.inc

    Every class string and id the themes print is built here. The themes stay
    markup.

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

<cms:set cf_sec_pad   = '' scope='global' />
<cms:set cf_min_h_css = '' scope='global' />
<cms:set cf_min_h     = '' scope='global' />
<cms:set cf_bg        = '' scope='global' />
<cms:set cf_box       = '' scope='global' />
<cms:set cf_hdg_cls   = '' scope='global' />
<cms:set cf_txt1_cls  = '' scope='global' />
<cms:set cf_txt2_cls  = '' scope='global' />
<cms:set cf_lbl_cls   = '' scope='global' />
<cms:set cf_fld_cls   = '' scope='global' />
<cms:set cf_gap_cls   = '' scope='global' />
<cms:set cf_btn_cls   = '' scope='global' />
<cms:set cf_btn_wrap  = '' scope='global' />

<cms:ignore>
    SECTION. Padding on the section, the height floor on the inner box - see
    the note in _val/min-height.htm for why they must be different elements.
    The != '' guard matters: PHP reads the string "0" as false, so a plain
    cms:if would swallow a deliberate "remove all spacing".
</cms:ignore>
<cms:if cnt_py_ht != ''><cms:capture into='cf_sec_pad' scope='global'>py-<cms:show cnt_py_ht /></cms:capture></cms:if>
<cms:ignore>
    THE EDITING FLOOR. The section cluster is a vertical stack of four buttons
    pinned to the top-left, and it has to fit inside the section or it spills
    over whatever comes next on the page.

    The arithmetic rather than a guessed number: four btn-sm circles at 31px,
    three 8px gaps between them, and the 16px the stack is inset from the top,
    comes to 164px. 12rem is 192px, which clears it with room left over.

    Only the three shortest choices are raised - unset, 6rem and 9rem. Anything
    from 12rem up already clears the stack, so an author who deliberately asked
    for a tall section keeps exactly what they asked for. And the floor applies
    only while inline editing is switched on, so a visitor never sees a section
    stretched to fit controls that are not being drawn for them.
</cms:ignore>
<cms:if cnt_min_h><cms:set cf_min_h = cnt_min_h scope='global' /></cms:if>
<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />")>
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
<cms:capture into='cf_box' scope='global'><cms:show cnt_box_sz /></cms:capture>

<cms:ignore>
    HEADING LEVEL is not routed at all. cnt_hdg_tag starts on h2 and its list
    carries no empty option, so it always holds a real tag name and the theme
    prints it directly. There was a cf_hdg_tag here that started at 'h2' and
    was only overwritten when the field held something - a second copy of the
    default, invisible to whoever set the first one. One value, one place.
</cms:ignore>
<cms:capture into='cf_hdg_cls' scope='global'><cms:if cnt_hdg_clr>text-<cms:show cnt_hdg_clr /> </cms:if><cms:if cnt_hdg_sz>fs-<cms:show cnt_hdg_sz /> </cms:if><cms:if cnt_hdg_font>font-<cms:show cnt_hdg_font /> </cms:if><cms:if cnt_hdg_fnt_wt>fw-<cms:show cnt_hdg_fnt_wt /> </cms:if><cms:if cnt_hdg_ltr_spc != ''>ls-<cms:show cnt_hdg_ltr_spc /> </cms:if></cms:capture>
<cms:capture into='cf_txt1_cls' scope='global'><cms:if cnt_txt1_clr>text-<cms:show cnt_txt1_clr /> </cms:if><cms:if cnt_txt1_sz>fs-<cms:show cnt_txt1_sz /> </cms:if><cms:if cnt_txt1_font>font-<cms:show cnt_txt1_font /> </cms:if><cms:if cnt_txt1_fnt_wt>fw-<cms:show cnt_txt1_fnt_wt /> </cms:if><cms:if cnt_txt1_ltr_spc != ''>ls-<cms:show cnt_txt1_ltr_spc /> </cms:if></cms:capture>
<cms:capture into='cf_txt2_cls' scope='global'><cms:if cnt_txt2_clr>text-<cms:show cnt_txt2_clr /> </cms:if><cms:if cnt_txt2_sz>fs-<cms:show cnt_txt2_sz /> </cms:if><cms:if cnt_txt2_font>font-<cms:show cnt_txt2_font /> </cms:if><cms:if cnt_txt2_fnt_wt>fw-<cms:show cnt_txt2_fnt_wt /> </cms:if><cms:if cnt_txt2_ltr_spc != ''>ls-<cms:show cnt_txt2_ltr_spc /> </cms:if></cms:capture>

<cms:ignore>
    FORM. 'form-label' is always present so the label element exists even when
    the author hides it visually - visually-hidden keeps it in the
    accessibility tree, display:none would not.
</cms:ignore>
<cms:capture into='cf_lbl_cls' scope='global'>form-label<cms:if cnt_frm_lbl_vis> <cms:show cnt_frm_lbl_vis /></cms:if><cms:if cnt_frm_lbl_clr> text-<cms:show cnt_frm_lbl_clr /></cms:if></cms:capture>
<cms:capture into='cf_fld_cls' scope='global'>form-control<cms:if cnt_frm_fld_sz> <cms:show cnt_frm_fld_sz /></cms:if></cms:capture>
<cms:capture into='cf_gap_cls' scope='global'><cms:if cnt_frm_fld_gap != ''>mb-<cms:show cnt_frm_fld_gap /></cms:if></cms:capture>

<cms:ignore>
    BUTTON. 'btn' base first, then {fill}-{color}. Bootstrap splits a button
    across two classes and needs both - .btn carries the padding, radius and
    sizing, .btn-{color} only sets colours. The variant alone renders as
    coloured text with no button shape at all.
</cms:ignore>
<cms:capture into='cf_btn_cls' scope='global'>btn <cms:show cnt_frm_btn_fill />-<cms:show cnt_frm_btn_clr /> <cms:if cnt_frm_btn_st><cms:show cnt_frm_btn_st /> </cms:if><cms:if cnt_frm_btn_sz><cms:show cnt_frm_btn_sz /> </cms:if><cms:if cnt_frm_btn_w><cms:show cnt_frm_btn_w /> </cms:if></cms:capture>
<cms:capture into='cf_btn_wrap' scope='global'><cms:if cnt_frm_btn_halg>text-<cms:show cnt_frm_btn_halg /></cms:if></cms:capture>
