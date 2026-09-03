<cms:ignore>
    =========================================================================
    IMAGE + CONTENT BLOCK  -  class compilation
    snippets/pb_mods/content/router/img_2col_cntn_blk.inc

    Embedded as the first act of theme/img_2col_cntn_blk.html.
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
<cms:set i2c_sec_bg = '' scope='global' />
<cms:set i2c_sec_inline_bg = '' scope='global' />
<cms:set i2c_hdr_wrapper_class = '' scope='global' />
<cms:set i2c_hdg_classes = '' scope='global' />
<cms:set i2c_hdg_size = '' scope='global' />
<cms:set i2c_sub_classes = '' scope='global' />
<cms:set i2c_hr_classes = '' scope='global' />
<cms:set i2c_body_classes = '' scope='global' />
<cms:set i2c_hdg_open = '' scope='global' />
<cms:set i2c_hdg_close = '' scope='global' />


<!-- ======================================================= -->
<!-- FRONT-END EDIT STATE                                    -->
<!-- ======================================================= -->
<cms:ignore>
    Resolved once here so the theme can test a single value instead of
    repeating the two-part gate at every cluster and every empty-state check.

    This is what keeps a brand-new tile editable. All the content gates below
    are OR'd against it, so an admin with editing switched on always sees the
    section shell and its buttons even though nothing has been filled in yet -
    without it, an empty tile would render nothing and there would be no way to
    reach the popups from the front end at all.
</cms:ignore>
<cms:set i2c_edit_on = '0' scope='global' />
<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />")>
    <cms:set i2c_edit_on = '1' scope='global' />
</cms:if>


<!-- ======================================================= -->
<!-- SECTION SPACING & BACKGROUND                            -->
<!-- ======================================================= -->
<cms:set i2c_sec_spacing = "<cms:show ccs_img_2col_cntn_pad_top /> <cms:show ccs_img_2col_cntn_pad_bot />"
    scope='global' />

<cms:ignore>
    all-colors.htm leads with 'Default Color=transparent', so an untouched tile
    emits bg-transparent - a real Bootstrap class and a correct no-op. The only
    value that is NOT a colour token is 'gradient', which routes to the custom
    group instead.
</cms:ignore>
<cms:if ccs_img_2col_cntn_bg_clr_opt != 'gradient'>
    <cms:set i2c_sec_bg = "bg-<cms:show ccs_img_2col_cntn_bg_clr_opt />" scope='global' />
</cms:if>

<cms:if ccs_img_2col_cntn_bg_clr_opt = 'gradient'>
    <cms:if ccs_img_2col_cntn_bg_clr_cstm_grdt_opt = '0'>
        <cms:set i2c_sec_inline_bg = "background-color: <cms:show ccs_img_2col_cntn_bg_clr_cstm_one />;"
            scope='global' />
    <cms:else />
        <cms:if ccs_img_2col_cntn_bg_clr_cstm_grad_rvrs_opt = '1'>
            <cms:set i2c_sec_inline_bg = "background-image: linear-gradient(<cms:show ccs_img_2col_cntn_bg_clr_cust_grad_split_opt />deg, <cms:show ccs_img_2col_cntn_bg_clr_cstm_two />, <cms:show ccs_img_2col_cntn_bg_clr_cstm_one />);" scope='global' />
        <cms:else />
            <cms:set i2c_sec_inline_bg = "background-image: linear-gradient(<cms:show ccs_img_2col_cntn_bg_clr_cust_grad_split_opt />deg, <cms:show ccs_img_2col_cntn_bg_clr_cstm_one />, <cms:show ccs_img_2col_cntn_bg_clr_cstm_two />);" scope='global' />
        </cms:if>
    </cms:if>
</cms:if>


<!-- ======================================================= -->
<!-- IMAGE ROW                                               -->
<!-- ======================================================= -->
<cms:set i2c_img_rnd = ccs_img_2col_cntn_img_rnd scope='global' />

<cms:ignore>
    Column width follows how many images are actually filled, so one image
    alone spans the row instead of leaving a gap. Both filled is the designed
    case: col-lg-6 each, stacking to full width below lg.
</cms:ignore>
<cms:set i2c_img_count = '0' scope='global' />
<cms:if ccs_img_2col_cntn_img_1><cms:set i2c_img_count = "<cms:add i2c_img_count '1' />" scope='global' /></cms:if>
<cms:if ccs_img_2col_cntn_img_2><cms:set i2c_img_count = "<cms:add i2c_img_count '1' />" scope='global' /></cms:if>

<cms:set i2c_img_col = 'col-12' scope='global' />
<cms:if i2c_img_count = '2'>
    <cms:set i2c_img_col = 'col-12 col-lg-6' scope='global' />
</cms:if>


<!-- ======================================================= -->
<!-- SECTION HEADER                                          -->
<!-- ======================================================= -->
<cms:ignore>
    Computed as a plain var rather than nested inside an attribute, so the
    theme's cms:if reads a single value.
</cms:ignore>
<cms:set i2c_show_header = '1' scope='global' />
<cms:if ccs_img_2col_cntn_hdr_rmv_opt = '1'>
    <cms:set i2c_show_header = '0' scope='global' />
</cms:if>

<cms:if i2c_show_header = '1'>

    <!-- Wrapper: padding + horizontal alignment -->
    <cms:capture into='i2c_hdr_wrapper_class' scope='global'>
        <cms:if ccs_img_2col_cntn_hdr_pd>py-<cms:show ccs_img_2col_cntn_hdr_pd /> </cms:if>
        <cms:if ccs_img_2col_cntn_hdr_halg>text-<cms:show ccs_img_2col_cntn_hdr_halg /> </cms:if>
    </cms:capture>

    <cms:ignore>
        THE HEADING ELEMENT.

        heading-size.htm emits TAG names - h1|h2|h3|h4|h5|h6|p - not fs- numbers,
        which is why it is read into a tag here rather than prefixed with fs-.
        (hero makes exactly that mistake: it feeds this same file into
        'fs-<value>' and emits fs-h1, which is not defined in theme.css.)

        Fallback is h1, matching the file's own first option, so a tile created
        before this field existed resolves the same way a new one does.
    </cms:ignore>
    <cms:set i2c_hdg_tag = ccs_img_2col_cntn_hdg_tag scope='global' />
    <cms:if i2c_hdg_tag = ''><cms:set i2c_hdg_tag = 'h1' scope='global' /></cms:if>

    <!-- Heading typography -->
    <cms:ignore>
        Single-option checkbox: plain '=' compare on the raw stored string.
        cms:is is wrong at runtime and right only inside a cms:func, where the
        code compiles to JavaScript and the value is genuinely an array.
    </cms:ignore>
    <cms:if ccs_img_2col_cntn_hdr_ovr_opt = '1'>
        <cms:capture into='i2c_hdg_size' scope='global'><cms:if ccs_img_2col_cntn_hdg_sz>fs-<cms:show ccs_img_2col_cntn_hdg_sz /></cms:if></cms:capture>

        <cms:capture into='i2c_hdg_classes' scope='global'>
            <cms:if ccs_img_2col_cntn_hdg_clr>text-<cms:show ccs_img_2col_cntn_hdg_clr /> </cms:if>
            <cms:if ccs_img_2col_cntn_hdg_wt>fw-<cms:show ccs_img_2col_cntn_hdg_wt /> </cms:if>
            <cms:if ccs_img_2col_cntn_hdg_fst>fst-<cms:show ccs_img_2col_cntn_hdg_fst /> </cms:if>
            <cms:if ccs_img_2col_cntn_hdg_font>font-<cms:show ccs_img_2col_cntn_hdg_font /> </cms:if>
            <cms:if ccs_img_2col_cntn_hdg_ls>ls-<cms:show ccs_img_2col_cntn_hdg_ls /> </cms:if>
        </cms:capture>

        <cms:capture into='i2c_sub_classes' scope='global'>
            <cms:if ccs_img_2col_cntn_sub_clr>text-<cms:show ccs_img_2col_cntn_sub_clr /> </cms:if>
            <cms:if ccs_img_2col_cntn_sub_sz>fs-<cms:show ccs_img_2col_cntn_sub_sz /> </cms:if>
            <cms:if ccs_img_2col_cntn_sub_wt>fw-<cms:show ccs_img_2col_cntn_sub_wt /> </cms:if>
            <cms:if ccs_img_2col_cntn_sub_fst>fst-<cms:show ccs_img_2col_cntn_sub_fst /> </cms:if>
            <cms:if ccs_img_2col_cntn_sub_font>font-<cms:show ccs_img_2col_cntn_sub_font /> </cms:if>
            <cms:if ccs_img_2col_cntn_sub_ls>ls-<cms:show ccs_img_2col_cntn_sub_ls /> </cms:if>
        </cms:capture>
    </cms:if>

    <cms:ignore>
        Tag and classes are assembled here rather than in the theme, the same
        idiom list_1 uses for its dynamic heading tag.

        Two deliberate differences from list_1's version. The concat runs inside
        a cms:capture BODY rather than as a cms:set attribute value, so there is
        no outer attribute delimiter to collide with. And the emitted class
        attribute is single-quoted, so every string literal passed to cms:concat
        can be double-quoted without escaping. list_1 nests a bare double quote
        inside a double-quoted attribute, which happens to parse but is one
        edit away from breaking.
    </cms:ignore>
    <cms:capture into='i2c_hdg_all_classes'><cms:show i2c_hdg_size /> <cms:show i2c_hdg_classes /></cms:capture>
    <cms:capture into='i2c_hdg_open' scope='global'><cms:concat '<' i2c_hdg_tag " class='" i2c_hdg_all_classes "'>" /></cms:capture>
    <cms:capture into='i2c_hdg_close' scope='global'><cms:concat '</' i2c_hdg_tag '>' /></cms:capture>

    <cms:ignore>
        'my-' not 'py-': an hr is a border with no content box, so padding
        would not separate it from anything.
        hr-size.htm and hr-thickness.htm both emit COMPLETE classes, so they
        take no prefix - unlike the colour and spacing tokens on the same line.
    </cms:ignore>
    <cms:capture into='i2c_hr_classes' scope='global'>
        <cms:show ccs_img_2col_cntn_hdg_hr /> <cms:show ccs_img_2col_cntn_hr_wt /> <cms:if ccs_img_2col_cntn_hr_clr>text-<cms:show ccs_img_2col_cntn_hr_clr /> </cms:if><cms:if ccs_img_2col_cntn_hr_pd>my-<cms:show ccs_img_2col_cntn_hr_pd /> </cms:if>mx-auto
    </cms:capture>

</cms:if>


<!-- ======================================================= -->
<!-- BODY CONTENT                                            -->
<!-- ======================================================= -->
<cms:ignore>
    h-align.htm has no empty option - it leads with 'Left=start' - so this
    always resolves to a real token. Guarded anyway so a tile stored before the
    field existed cannot emit a bare 'text-'.
</cms:ignore>
<cms:set i2c_body_align = 'text-start' scope='global' />
<cms:if ccs_img_2col_cntn_body_halg>
    <cms:set i2c_body_align = "text-<cms:show ccs_img_2col_cntn_body_halg />" scope='global' />
</cms:if>

<cms:ignore>
    These land on the wrapper div, not on the editor's own paragraphs. Colour,
    font-size, weight, family and letter-spacing are inherited properties and
    theme.css sets none of them on a bare p, so the richtext content picks them
    up without an inherit helper. Anything styled inside the editor is inline
    and still wins.
</cms:ignore>
<cms:if ccs_img_2col_cntn_body_ovr_opt = '1'>
    <cms:capture into='i2c_body_classes' scope='global'>
        <cms:if ccs_img_2col_cntn_body_clr>text-<cms:show ccs_img_2col_cntn_body_clr /> </cms:if>
        <cms:if ccs_img_2col_cntn_body_sz>fs-<cms:show ccs_img_2col_cntn_body_sz /> </cms:if>
        <cms:if ccs_img_2col_cntn_body_wt>fw-<cms:show ccs_img_2col_cntn_body_wt /> </cms:if>
        <cms:if ccs_img_2col_cntn_body_fst>fst-<cms:show ccs_img_2col_cntn_body_fst /> </cms:if>
        <cms:if ccs_img_2col_cntn_body_font>font-<cms:show ccs_img_2col_cntn_body_font /> </cms:if>
        <cms:if ccs_img_2col_cntn_body_ls>ls-<cms:show ccs_img_2col_cntn_body_ls /> </cms:if>
    </cms:capture>
</cms:if>


<!-- ======================================================= -->
<!-- EMPTY STATE                                             -->
<!-- ======================================================= -->
<cms:ignore>
    A brand-new tile has no images, no heading and no body. Rendering the
    section anyway would put an empty coloured band on the page, which reads as
    a layout bug rather than as unfilled content. The theme gates on this.
</cms:ignore>
<cms:set i2c_has_content = '0' scope='global' />
<cms:if i2c_img_count gt '0' || ccs_img_2col_cntn_hdg_txt || ccs_img_2col_cntn_sub_txt || ccs_img_2col_cntn_body>
    <cms:set i2c_has_content = '1' scope='global' />
</cms:if>
