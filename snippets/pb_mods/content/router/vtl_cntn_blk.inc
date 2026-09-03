<cms:ignore>
    =========================================================================
    VERTICAL TITLE CONTENT BLOCK  -  class compilation
    snippets/pb_mods/content/router/vtl_cntn_blk.inc

    Embedded as the first act of theme/vtl_cntn_blk.html.
    Turns stored dropdown tokens into finished class strings so the theme holds
    no styling logic. Follows nav_swpr form: every conditional var initialised
    up front, one cms:if per line inside each capture.
    =========================================================================
</cms:ignore>

<!-- ======================================================= -->
<!-- INITIALISE ALL CONDITIONALLY-SET VARS                   -->
<!-- __ROOT__ persists for the whole page render, so any var -->
<!-- set only inside a cms:if would otherwise carry over     -->
<!-- into the next tile of this same block type on the page. -->
<!-- ======================================================= -->
<cms:set vtl_sec_bg = '' scope='global' />
<cms:set vtl_sec_inline_bg = '' scope='global' />
<cms:set vtl_ttl_classes = '' scope='global' />
<cms:set vtl_ttl_classes_m = '' scope='global' />
<cms:set vtl_ttl_open_v = '' scope='global' />
<cms:set vtl_ttl_open_m = '' scope='global' />
<cms:set vtl_ttl_close = '' scope='global' />
<cms:set vtl_main_classes = '' scope='global' />
<cms:set vtl_side_classes = '' scope='global' />


<!-- ======================================================= -->
<!-- FRONT-END EDIT STATE                                    -->
<!-- ======================================================= -->
<cms:ignore>
    Resolved once so the theme tests a single value instead of repeating the
    two-part gate at every cluster and every empty-state check.

    This is what keeps a brand-new tile editable: the content gates below are
    OR'd against it, so an admin with editing on always sees the section shell
    and its buttons even though nothing has been filled in yet. Without it an
    empty tile would render nothing and there would be no way to reach the
    popups from the front end at all.
</cms:ignore>
<cms:set vtl_edit_on = '0' scope='global' />
<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />")>
    <cms:set vtl_edit_on = '1' scope='global' />
</cms:if>


<!-- ======================================================= -->
<!-- SECTION SPACING & BACKGROUND                            -->
<!-- ======================================================= -->
<cms:set vtl_sec_spacing = "<cms:show ccs_vtl_cntn_pad_top /> <cms:show ccs_vtl_cntn_pad_bot />" scope='global' />

<cms:ignore>
    all-colors.htm leads with 'Default Color=transparent', so an untouched tile
    emits bg-transparent - a real Bootstrap class and a correct no-op. The only
    value that is NOT a colour token is 'gradient', which routes to the custom
    group instead.
</cms:ignore>
<cms:if ccs_vtl_cntn_bg_clr_opt != 'gradient'>
    <cms:set vtl_sec_bg = "bg-<cms:show ccs_vtl_cntn_bg_clr_opt />" scope='global' />
</cms:if>

<cms:if ccs_vtl_cntn_bg_clr_opt = 'gradient'>
    <cms:if ccs_vtl_cntn_bg_clr_cstm_grdt_opt = '0'>
        <cms:set vtl_sec_inline_bg = "background-color: <cms:show ccs_vtl_cntn_bg_clr_cstm_one />;" scope='global' />
    <cms:else />
        <cms:if ccs_vtl_cntn_bg_clr_cstm_grad_rvrs_opt = '1'>
            <cms:set vtl_sec_inline_bg = "background-image: linear-gradient(<cms:show ccs_vtl_cntn_bg_clr_cust_grad_split_opt />deg, <cms:show ccs_vtl_cntn_bg_clr_cstm_two />, <cms:show ccs_vtl_cntn_bg_clr_cstm_one />);" scope='global' />
        <cms:else />
            <cms:set vtl_sec_inline_bg = "background-image: linear-gradient(<cms:show ccs_vtl_cntn_bg_clr_cust_grad_split_opt />deg, <cms:show ccs_vtl_cntn_bg_clr_cstm_one />, <cms:show ccs_vtl_cntn_bg_clr_cstm_two />);" scope='global' />
        </cms:if>
    </cms:if>
</cms:if>


<!-- ======================================================= -->
<!-- VERTICAL TITLE                                          -->
<!-- ======================================================= -->
<cms:ignore>
    Computed as a plain var rather than nested inside an attribute, so the
    theme's cms:if reads a single value.
</cms:ignore>
<cms:set vtl_show_title = '1' scope='global' />
<cms:if ccs_vtl_cntn_ttl_rmv_opt = '1'>
    <cms:set vtl_show_title = '0' scope='global' />
</cms:if>

<cms:ignore>
    COLUMN ARITHMETIC.
    The source row is 2 + 6 + 4 = 12. Drop the title rail and 6 + 4 leaves two
    columns of dead space on the left, which reads as a broken layout rather
    than as a removed title. So the main column absorbs the rail when the title
    is off.
</cms:ignore>
<cms:set vtl_main_col = 'col-lg-6' scope='global' />
<cms:if vtl_show_title = '0'>
    <cms:set vtl_main_col = 'col-lg-8' scope='global' />
</cms:if>

<cms:if vtl_show_title = '1'>

    <cms:ignore>
        THE HEADING ELEMENT.

        heading-size.htm emits TAG names - h1|h2|h3|h4|h5|h6|p - not fs- numbers,
        which is why it is read into a tag here rather than prefixed with fs-.
        (hero makes exactly that mistake: it feeds this same file into
        'fs-<value>' and emits fs-h1, which is not defined in theme.css.)

        Fallback is h1, matching the file's own first option. Note the SOURCE
        markup used h2 - if this block sits beneath one that already owns the
        page h1, the editor should set h2 here.
    </cms:ignore>
    <cms:set vtl_ttl_tag = ccs_vtl_cntn_ttl_tag scope='global' />
    <cms:if vtl_ttl_tag = ''><cms:set vtl_ttl_tag = 'h1' scope='global' /></cms:if>

    <cms:ignore>
        Two class strings because the source styled the two renderings
        differently: the rotated rail carried text-300 fs-3, the horizontal
        version carried none and took the theme default.

        Ticking the override replaces BOTH with the chosen classes, so the two
        renderings of the same title stop diverging - which is what someone
        setting a colour would expect.

        Single-option checkbox: plain '=' compare on the raw stored string.
        cms:is is wrong at runtime and right only inside a cms:func, where the
        code compiles to JavaScript and the value is genuinely an array.
    </cms:ignore>
    <cms:if ccs_vtl_cntn_ttl_ovr_opt = '1'>
        <cms:capture into='vtl_ttl_classes' scope='global'>
            <cms:if ccs_vtl_cntn_ttl_clr>text-<cms:show ccs_vtl_cntn_ttl_clr /> </cms:if>
            <cms:if ccs_vtl_cntn_ttl_sz>fs-<cms:show ccs_vtl_cntn_ttl_sz /> </cms:if>
            <cms:if ccs_vtl_cntn_ttl_wt>fw-<cms:show ccs_vtl_cntn_ttl_wt /> </cms:if>
            <cms:if ccs_vtl_cntn_ttl_fst>fst-<cms:show ccs_vtl_cntn_ttl_fst /> </cms:if>
            <cms:if ccs_vtl_cntn_ttl_font>font-<cms:show ccs_vtl_cntn_ttl_font /> </cms:if>
            <cms:if ccs_vtl_cntn_ttl_ls>ls-<cms:show ccs_vtl_cntn_ttl_ls /> </cms:if>
        </cms:capture>
        <cms:set vtl_ttl_classes_m = vtl_ttl_classes scope='global' />
    <cms:else />
        <cms:ignore>Source defaults, per rendering.</cms:ignore>
        <cms:set vtl_ttl_classes = 'text-300 fs-3' scope='global' />
        <cms:set vtl_ttl_classes_m = '' scope='global' />
    </cms:if>

    <cms:ignore>
        Tag and classes assembled here rather than in the theme, the same idiom
        list_1 uses for its dynamic heading tag.

        Two deliberate differences from list_1's version. The concat runs inside
        a cms:capture BODY rather than as a cms:set attribute value, so there is
        no outer attribute delimiter to collide with. And the emitted class
        attribute is single-quoted, so every string literal passed to cms:concat
        can be double-quoted without escaping.

        Two open tags, one close: the rotated rail and the horizontal version
        carry different structural classes. text-uppercase is kept on the rail
        because it is part of the perpendicular look rather than a style choice;
        the source's lh-0 is dropped as redundant - .perpendicular already sets
        line-height: 0 itself.
    </cms:ignore>
    <cms:capture into='vtl_ttl_cls_v'>text-uppercase <cms:show vtl_ttl_classes /></cms:capture>
    <cms:capture into='vtl_ttl_cls_m'>mb-5 <cms:show vtl_ttl_classes_m /></cms:capture>

    <cms:capture into='vtl_ttl_open_v' scope='global'><cms:concat '<' vtl_ttl_tag " class='" vtl_ttl_cls_v "' style='transform: translateY(36px);'>" /></cms:capture>
    <cms:capture into='vtl_ttl_open_m' scope='global'><cms:concat '<' vtl_ttl_tag " class='" vtl_ttl_cls_m "'>" /></cms:capture>
    <cms:capture into='vtl_ttl_close' scope='global'><cms:concat '</' vtl_ttl_tag '>' /></cms:capture>

</cms:if>


<!-- ======================================================= -->
<!-- MAIN COLUMN                                             -->
<!-- ======================================================= -->
<cms:ignore>
    h-align.htm has no empty option - it leads with 'Left=start' - so this
    always resolves to a real token. Guarded anyway so a tile stored before the
    field existed cannot emit a bare 'text-'.
</cms:ignore>
<cms:set vtl_main_align = 'text-start' scope='global' />
<cms:if ccs_vtl_cntn_main_halg>
    <cms:set vtl_main_align = "text-<cms:show ccs_vtl_cntn_main_halg />" scope='global' />
</cms:if>

<cms:ignore>
    These land on the wrapper div, not on the editor's own paragraphs. Colour,
    font-size, weight, family and letter-spacing are inherited properties and
    theme.css sets none of them on a bare p, so the richtext content picks them
    up with no inherit helper. Anything styled inside the editor is inline and
    still wins.
</cms:ignore>
<cms:if ccs_vtl_cntn_main_ovr_opt = '1'>
    <cms:capture into='vtl_main_classes' scope='global'>
        <cms:if ccs_vtl_cntn_main_clr>text-<cms:show ccs_vtl_cntn_main_clr /> </cms:if>
        <cms:if ccs_vtl_cntn_main_sz>fs-<cms:show ccs_vtl_cntn_main_sz /> </cms:if>
        <cms:if ccs_vtl_cntn_main_wt>fw-<cms:show ccs_vtl_cntn_main_wt /> </cms:if>
        <cms:if ccs_vtl_cntn_main_fst>fst-<cms:show ccs_vtl_cntn_main_fst /> </cms:if>
        <cms:if ccs_vtl_cntn_main_font>font-<cms:show ccs_vtl_cntn_main_font /> </cms:if>
        <cms:if ccs_vtl_cntn_main_ls>ls-<cms:show ccs_vtl_cntn_main_ls /> </cms:if>
    </cms:capture>
</cms:if>


<!-- ======================================================= -->
<!-- SIDE COLUMN                                             -->
<!-- ======================================================= -->
<cms:set vtl_side_align = 'text-start' scope='global' />
<cms:if ccs_vtl_cntn_side_halg>
    <cms:set vtl_side_align = "text-<cms:show ccs_vtl_cntn_side_halg />" scope='global' />
</cms:if>

<cms:if ccs_vtl_cntn_side_ovr_opt = '1'>
    <cms:capture into='vtl_side_classes' scope='global'>
        <cms:if ccs_vtl_cntn_side_clr>text-<cms:show ccs_vtl_cntn_side_clr /> </cms:if>
        <cms:if ccs_vtl_cntn_side_sz>fs-<cms:show ccs_vtl_cntn_side_sz /> </cms:if>
        <cms:if ccs_vtl_cntn_side_wt>fw-<cms:show ccs_vtl_cntn_side_wt /> </cms:if>
        <cms:if ccs_vtl_cntn_side_fst>fst-<cms:show ccs_vtl_cntn_side_fst /> </cms:if>
        <cms:if ccs_vtl_cntn_side_font>font-<cms:show ccs_vtl_cntn_side_font /> </cms:if>
        <cms:if ccs_vtl_cntn_side_ls>ls-<cms:show ccs_vtl_cntn_side_ls /> </cms:if>
    </cms:capture>
</cms:if>


<!-- ======================================================= -->
<!-- EMPTY STATE                                             -->
<!-- ======================================================= -->
<cms:ignore>
    A brand-new tile has no title and no copy. Rendering the section anyway
    would put an empty coloured band on the page, which reads as a layout bug
    rather than as unfilled content. The theme gates on this, OR'd against
    vtl_edit_on so an admin can still get at the popups.
</cms:ignore>
<cms:set vtl_has_content = '0' scope='global' />
<cms:if ccs_vtl_cntn_ttl_txt || ccs_vtl_cntn_main || ccs_vtl_cntn_side>
    <cms:set vtl_has_content = '1' scope='global' />
</cms:if>
