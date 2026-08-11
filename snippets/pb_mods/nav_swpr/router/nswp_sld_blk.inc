<!-- ======================================================= -->
<!-- INITIALISE ALL CONDITIONALLY-SET VARS                   -->
<!-- __ROOT__ persists for the whole page render, so any var -->
<!-- set only inside a cms:if will otherwise carry over into -->
<!-- the next tile of this same block type on the page.      -->
<!-- ======================================================= -->
<cms:set nswp_sec_inline_bg = '' scope='global' />
<cms:set nswp_hdr_wrapper_class = '' scope='global' />
<cms:set nswp_hr_classes = '' scope='global' />
<cms:set nswp_main_hdg_classes = '' scope='global' />
<cms:set nswp_sub_hdg_classes = '' scope='global' />
<cms:set nswp_tile_hdg_classes = '' scope='global' />
<cms:set nswp_tile_sub_classes = '' scope='global' />
<cms:set nswp_nav_panel_class = '' scope='global' />
<cms:set nswp_hdg_size = '' scope='global' />
<cms:set nswp_tile_oldprc_classes = '' scope='global' />
<cms:set nswp_nav_ttl_classes = '' scope='global' />
<cms:set nswp_nav_link_clr = '' scope='global' />
<cms:set nswp_tile_cat_classes = '' scope='global' />
<cms:set nswp_ld_items = '' scope='global' />

<!-- ======================================================= -->
<!-- SECTION SPACING & BACKGROUND                            -->
<!-- ======================================================= -->
<cms:set nswp_sec_spacing = "<cms:show ccs_nswp_pad_top /> <cms:show ccs_nswp_pad_bot />" scope='global' />
<cms:set nswp_sec_bg = '' scope='global' />

<cms:if ccs_nswp_bg_clr_opt!='gradient'>
    <cms:set nswp_sec_bg = "bg-<cms:show ccs_nswp_bg_clr_opt />" scope='global' />
</cms:if>

<!-- Custom Background Color/Gradient Logic -->
<cms:if ccs_nswp_bg_clr_opt='gradient'>
    <cms:if ccs_nswp_bg_clr_cstm_grdt_opt='0'>
        <cms:set nswp_sec_inline_bg = "background-color: <cms:show ccs_nswp_bg_clr_cstm_one />;" scope='global' />
    <cms:else />
        <cms:if ccs_nswp_bg_clr_cstm_grad_rvrs_opt='1'>
            <cms:set nswp_sec_inline_bg = "background-image: linear-gradient(<cms:show ccs_nswp_bg_clr_cust_grad_split_opt />deg, <cms:show ccs_nswp_bg_clr_cstm_two />, <cms:show ccs_nswp_bg_clr_cstm_one />);" scope='global' />
        <cms:else />
            <cms:set nswp_sec_inline_bg = "background-image: linear-gradient(<cms:show ccs_nswp_bg_clr_cust_grad_split_opt />deg, <cms:show ccs_nswp_bg_clr_cstm_one />, <cms:show ccs_nswp_bg_clr_cstm_two />);" scope='global' />
        </cms:if>
    </cms:if>
</cms:if>


<!-- ======================================================= -->
<!-- HEADER CLASSES (IF ENABLED)                             -->
<!-- ======================================================= -->
<!-- Compute header visibility safely (avoid nesting tags in attributes) -->
<cms:set nswp_show_header = '1' scope='global' />
<cms:if ccs_nswp_hdr_rmv_opt='1'>
    <cms:set nswp_show_header = '0' scope='global' />
</cms:if>

<cms:if nswp_show_header='1'>

    <!-- Wrapper: padding + horizontal alignment -->
    <cms:capture into='nswp_hdr_wrapper_class' scope='global'>
        <cms:if ccs_nswp_hdg_txtbx_pd>py-<cms:show ccs_nswp_hdg_txtbx_pd /> </cms:if>
        <cms:if ccs_nswp_hdg_txtbx_halg>text-<cms:show ccs_nswp_hdg_txtbx_halg /> </cms:if>
    </cms:capture>

    <!-- Header Typography -->
    <!-- Single-option checkbox: plain '=' compare on the raw string, same -->
    <!-- as list_1. Multi-option checkboxes hold a pipe-delimited string -->
    <!-- instead, so walk those with cms:each - see ccs_nswp_filter_opt. -->
    <cms:if ccs_nswp_hdr_ovr_opt='1'>
        <cms:capture into='nswp_main_hdg_classes' scope='global'>
            <cms:if ccs_nswp_hdg_txt_clr>text-<cms:show ccs_nswp_hdg_txt_clr /> </cms:if>
            <cms:if ccs_nswp_hdg_txt_font>font-<cms:show ccs_nswp_hdg_txt_font /> </cms:if>
            <cms:if ccs_nswp_hdg_txt_fnt_wt>fw-<cms:show ccs_nswp_hdg_txt_fnt_wt /> </cms:if>
            <cms:if ccs_nswp_hdg_txt_fnt_st>fst-<cms:show ccs_nswp_hdg_txt_fnt_st /> </cms:if>
            <cms:if ccs_nswp_hdg_txt_ltr_spc>ls-<cms:show ccs_nswp_hdg_txt_ltr_spc /> </cms:if>
            <cms:if ccs_nswp_hdg_txt_ln_ht>lh-<cms:show ccs_nswp_hdg_txt_ln_ht /> </cms:if>
        </cms:capture>

        <cms:ignore>
            The heading is ALWAYS an h2 and that is deliberate.
            A section block can appear several times on one page, so it must never
            mint an h1 - that belongs to the hero. And h3+ would leave a gap in the
            outline. The user controls how big it LOOKS via a fs- class; the level
            stays correct without them having to understand why.
            Size now sits INSIDE the typography override with the rest of the
            typography. Unticked, no fs- class is emitted and the heading falls back
            to the theme's own size - which is the point of leaving it unticked.
            The tag is written literally in the theme - no cms:concat needed now
            that it is fixed, which also avoids quote-escaping inside an attribute.
        </cms:ignore>
        <cms:capture into='nswp_hdg_size' scope='global'><cms:if ccs_nswp_hdg_txt_sz>fs-<cms:show ccs_nswp_hdg_txt_sz /></cms:if></cms:capture>

        <cms:capture into='nswp_sub_hdg_classes' scope='global'>
            <cms:if ccs_nswp_sub_txt_clr>text-<cms:show ccs_nswp_sub_txt_clr /> </cms:if>
            <cms:if ccs_nswp_sub_txt_sz>fs-<cms:show ccs_nswp_sub_txt_sz /> </cms:if>
            <cms:if ccs_nswp_sub_txt_font>font-<cms:show ccs_nswp_sub_txt_font /> </cms:if>
            <cms:if ccs_nswp_sub_txt_fnt_wt>fw-<cms:show ccs_nswp_sub_txt_fnt_wt /> </cms:if>
            <cms:if ccs_nswp_sub_txt_fnt_st>fst-<cms:show ccs_nswp_sub_txt_fnt_st /> </cms:if>
            <cms:if ccs_nswp_sub_txt_ltr_spc>ls-<cms:show ccs_nswp_sub_txt_ltr_spc /> </cms:if>
            <cms:if ccs_nswp_sub_txt_ln_ht>lh-<cms:show ccs_nswp_sub_txt_ln_ht /> </cms:if>
        </cms:capture>
    </cms:if>


    <!-- HR Styles - 'my-' not 'py-': an <hr> is a border with no content box -->
    <cms:capture into='nswp_hr_classes' scope='global'>
        nswp-hr <cms:show ccs_nswp_hdg_hr /> <cms:show ccs_nswp_hdg_hr_wt /> <cms:if ccs_nswp_hdg_hr_clr>text-<cms:show ccs_nswp_hdg_hr_clr /> </cms:if><cms:if ccs_nswp_hdg_hr_pd>my-<cms:show ccs_nswp_hdg_hr_pd /> </cms:if>mx-auto
    </cms:capture>

</cms:if>


<!-- ======================================================= -->
<!-- NAVIGATION LINK STYLES                                  -->
<!-- ======================================================= -->
<!-- Hover and active states come from the site theme (.btn-link sets -->
<!-- --ccs-btn-hover-color from --ccs-link-hover-color), so there are no -->
<!-- hover/active colour controls here by design. -->
<cms:if ccs_nswp_nav_ovr_opt='1'>
    <cms:capture into='nswp_nav_ttl_classes' scope='global'>
        <cms:if ccs_nswp_nav_ttl_clr>text-<cms:show ccs_nswp_nav_ttl_clr /> </cms:if>
        <cms:if ccs_nswp_nav_ttl_sz>fs-<cms:show ccs_nswp_nav_ttl_sz /> </cms:if>
        <cms:if ccs_nswp_nav_ttl_font>font-<cms:show ccs_nswp_nav_ttl_font /> </cms:if>
        <cms:if ccs_nswp_nav_ttl_fnt_wt>fw-<cms:show ccs_nswp_nav_ttl_fnt_wt /> </cms:if>
        <cms:if ccs_nswp_nav_ttl_fnt_st>fst-<cms:show ccs_nswp_nav_ttl_fnt_st /> </cms:if>
        <cms:if ccs_nswp_nav_ttl_ltr_spc>ls-<cms:show ccs_nswp_nav_ttl_ltr_spc /> </cms:if>
        <cms:if ccs_nswp_nav_ttl_ln_ht>lh-<cms:show ccs_nswp_nav_ttl_ln_ht /> </cms:if>
    </cms:capture>

    <cms:capture into='nswp_nav_link_clr' scope='global'>
        <cms:if ccs_nswp_nav_clr>text-<cms:show ccs_nswp_nav_clr /> </cms:if>
        <cms:if ccs_nswp_nav_lnk_sz>fs-<cms:show ccs_nswp_nav_lnk_sz /> </cms:if>
        <cms:if ccs_nswp_nav_lnk_font>font-<cms:show ccs_nswp_nav_lnk_font /> </cms:if>
        <cms:if ccs_nswp_nav_lnk_fnt_wt>fw-<cms:show ccs_nswp_nav_lnk_fnt_wt /> </cms:if>
        <cms:if ccs_nswp_nav_lnk_fnt_st>fst-<cms:show ccs_nswp_nav_lnk_fnt_st /> </cms:if>
        <cms:if ccs_nswp_nav_lnk_ltr_spc>ls-<cms:show ccs_nswp_nav_lnk_ltr_spc /> </cms:if>
        <cms:if ccs_nswp_nav_lnk_ln_ht>lh-<cms:show ccs_nswp_nav_lnk_ln_ht /> </cms:if>
    </cms:capture>
</cms:if>


<!-- ======================================================= -->
<!-- TILE / CARD STYLES                                      -->
<!-- ======================================================= -->
<!-- Image & Backing -->
<cms:set nswp_img_rnd = ccs_nswp_img_rnd scope='global' />
<cms:ignore>
    Tiles are a fixed 1:1 box (Bootstrap .ratio), so every slide is the same
    height whatever the source image measures. object-fit decides how the image
    behaves inside that box - 'cover' fills and crops, 'contain' fits whole.
    Bare token from the dropdown; the 'object-fit-' prefix goes on here.
</cms:ignore>
<cms:capture into='nswp_img_fit' scope='global'>object-fit-<cms:if ccs_nswp_img_fit_opt><cms:show ccs_nswp_img_fit_opt /><cms:else />cover</cms:if></cms:capture>
<cms:set nswp_txt_align = ccs_nswp_itm_txt_halg scope='global' />
<cms:ignore>
    A tile saved before this field existed has no stored value, which would
    emit a bare 'text-' class. h-align.htm leads with Left, so 'start' is the
    documented default and is what an unset field resolves to.
</cms:ignore>
<cms:if nswp_txt_align = ''><cms:set nswp_txt_align = 'start' scope='global' /></cms:if>
<cms:ignore>
    light-dark.htm emits a bare token so it can be reused anywhere; the 'bg-'
    is added at each use site. The cms:if keeps 'None' from emitting an orphan
    'bg-' with nothing after it.
</cms:ignore>
<cms:capture into='nswp_txt_backing' scope='global'><cms:if ccs_nswp_txt_bg_opt>bg-<cms:show ccs_nswp_txt_bg_opt /> <cms:show ccs_nswp_txt_bg_opc /></cms:if></cms:capture>
<cms:capture into='nswp_nav_panel_class' scope='global'><cms:if ccs_nswp_nav_pnl_opt>bg-<cms:show ccs_nswp_nav_pnl_opt /> <cms:show ccs_nswp_nav_pnl_opc /></cms:if></cms:capture>

<!-- Tile Typography Override -->
<cms:if ccs_nswp_itm_typo_opt='1'>
    <cms:capture into='nswp_tile_hdg_classes' scope='global'>
        <cms:if ccs_nswp_itm_hdg_clr>text-<cms:show ccs_nswp_itm_hdg_clr /> </cms:if>
        <cms:if ccs_nswp_itm_hdg_sz>fs-<cms:show ccs_nswp_itm_hdg_sz /> </cms:if>
        <cms:if ccs_nswp_itm_hdg_font>font-<cms:show ccs_nswp_itm_hdg_font /> </cms:if>
        <cms:if ccs_nswp_itm_hdg_fnt_wt>fw-<cms:show ccs_nswp_itm_hdg_fnt_wt /> </cms:if>
        <cms:if ccs_nswp_itm_hdg_fnt_st>fst-<cms:show ccs_nswp_itm_hdg_fnt_st /> </cms:if>
        <cms:if ccs_nswp_itm_hdg_ltr_spc>ls-<cms:show ccs_nswp_itm_hdg_ltr_spc /> </cms:if>
        <cms:if ccs_nswp_itm_hdg_ln_ht>lh-<cms:show ccs_nswp_itm_hdg_ln_ht /> </cms:if>
    </cms:capture>

    <!-- One subheading set governs the whole subheading slot: the folder -->
    <!-- slide's description, and the price + markdown price on product -->
    <!-- slides. No separate price colour control. -->
    <cms:capture into='nswp_tile_sub_classes' scope='global'>
        <cms:if ccs_nswp_itm_sub_clr>text-<cms:show ccs_nswp_itm_sub_clr /> </cms:if>
        <cms:if ccs_nswp_itm_sub_sz>fs-<cms:show ccs_nswp_itm_sub_sz /> </cms:if>
        <cms:if ccs_nswp_itm_sub_font>font-<cms:show ccs_nswp_itm_sub_font /> </cms:if>
        <cms:if ccs_nswp_itm_sub_fnt_wt>fw-<cms:show ccs_nswp_itm_sub_fnt_wt /> </cms:if>
        <cms:if ccs_nswp_itm_sub_fnt_st>fst-<cms:show ccs_nswp_itm_sub_fnt_st /> </cms:if>
        <cms:if ccs_nswp_itm_sub_ltr_spc>ls-<cms:show ccs_nswp_itm_sub_ltr_spc /> </cms:if>
        <cms:if ccs_nswp_itm_sub_ln_ht>lh-<cms:show ccs_nswp_itm_sub_ln_ht /> </cms:if>
    </cms:capture>

    <!-- Category line above the tile title. Its defaults live in user.php -->
    <!-- under :where(), which has zero specificity, so anything chosen here -->
    <!-- overrides them without needing !important. -->
    <cms:ignore>
        Only emit category classes when its own override is ticked, so unticking
        it returns the line to the user.php default rather than leaving a stale
        stored value applied.
    </cms:ignore>
    <cms:if ccs_nswp_itm_cat_ovr_opt='1'>
    <cms:capture into='nswp_tile_cat_classes' scope='global'>
        <cms:if ccs_nswp_itm_cat_clr>text-<cms:show ccs_nswp_itm_cat_clr /> </cms:if>
        <cms:if ccs_nswp_itm_cat_sz>fs-<cms:show ccs_nswp_itm_cat_sz /> </cms:if>
        <cms:if ccs_nswp_itm_cat_font>font-<cms:show ccs_nswp_itm_cat_font /> </cms:if>
        <cms:if ccs_nswp_itm_cat_fnt_wt>fw-<cms:show ccs_nswp_itm_cat_fnt_wt /> </cms:if>
        <cms:if ccs_nswp_itm_cat_fnt_st>fst-<cms:show ccs_nswp_itm_cat_fnt_st /> </cms:if>
        <cms:if ccs_nswp_itm_cat_ltr_spc>ls-<cms:show ccs_nswp_itm_cat_ltr_spc /> </cms:if>
        <cms:if ccs_nswp_itm_cat_ln_ht>lh-<cms:show ccs_nswp_itm_cat_ln_ht /> </cms:if>
    </cms:capture>
    </cms:if>
</cms:if>

<!-- Old price is deliberately hardcoded, not user-styled. It sits one step -->
<!-- down the fs- scale from the subheading (each step is x0.75) and takes a -->
<!-- muted theme gray, so the strike-through reads as secondary rather than -->
<!-- competing with the live price. Computed outside the override gate so it -->
<!-- always resolves. fs-10 is the floor - there is no fs-11. -->
<cms:capture into='nswp_tile_oldprc_classes' scope='global'>text-600 <cms:if ccs_nswp_itm_sub_sz='7'>fs-8<cms:else_if ccs_nswp_itm_sub_sz='8' />fs-9<cms:else />fs-10</cms:if></cms:capture>

<!-- ======================================================= -->
<!-- PRODUCT FILTER LOGIC (CHECKBOXES)                       -->
<!-- ======================================================= -->
<!-- Always exclude items marked 'Do Not Show on Menu' -->
<cms:set nswp_custom_field = "noshow!=1" scope='global' />

<!-- Multi-option checkbox: the raw value is a pipe-delimited string, so -->
<!-- walk it with cms:each. Its default sep is '|', which is also Couch's -->
<!-- default checkbox separator. Conditions are ANDed by cms:pages. -->
<cms:each ccs_nswp_filter_opt>
    <cms:if item='featured'>
        <cms:set nswp_custom_field = "<cms:show nswp_custom_field /> | feature=1" scope='global' />
    </cms:if>
    <cms:if item='value'>
        <cms:set nswp_custom_field = "<cms:show nswp_custom_field /> | value=1" scope='global' />
    </cms:if>
</cms:each>

<!-- ======================================================= -->
<!-- PRE-QUALIFY CATEGORIES & BUILD NAV HTML                 -->
<!-- ======================================================= -->
<cms:set my_target_folder = ccs_nswp_src_opt scope='global' />
<cms:set cat_override = "<cms:gpc 'cat_override' method='get' />" scope='global' />
<cms:set initial_load_cat = '' scope='global' />
<cms:set left_nav_html = '' scope='global' />

<!-- The four original branches differed only in three cms:folders -->
<!-- attributes, so those are resolved up front and the link builder is -->
<!-- written once. childof='' is skipped by the tag exactly as if omitted -->
<!-- (tags.php folders(): elseif( $childof!='' )). -->
<cms:set nav_childof = '' scope='global' />
<cms:if my_target_folder != 'all'>
    <cms:set nav_childof = my_target_folder scope='global' />
</cms:if>

<cms:set nav_depth = '0' scope='global' />
<cms:set nav_hier = '1' scope='global' />
<cms:if ccs_nswp_content_typ = 'folders'>
    <cms:set nav_depth = '1' scope='global' />
    <cms:set nav_hier = '0' scope='global' />
</cms:if>

<cms:folders masterpage=source_master childof=nav_childof depth=nav_depth hierarchical=nav_hier orderby='weight'>

    <!-- Protect these from being overwritten by the nested loops below -->
    <cms:set current_parent_name = k_folder_name />
    <cms:set current_parent_title = k_folder_title />

    <cms:ignore>
        Folders mode keeps the tree, so indentation is meaningful.
        Pages mode is LEAF-ONLY (see the guard below): parents are hidden, so
        indenting a survivor under an invisible parent would look like a bug.
        Those get a breadcrumb label instead, built further down.
    </cms:ignore>
    <cms:set indent_value = '0' />
    <cms:if ccs_nswp_content_typ = 'folders'>
        <cms:if k_level><cms:set indent_value = k_level /></cms:if>
    </cms:if>

    <cms:ignore>
        SCALABILITY. This guard runs once per folder in the nav, so anything
        expensive here multiplies by the size of the folder tree.

        cms:folders already provides both counts from the in-memory tree at ZERO
        query cost (folder.php:530-531):
            k_folder_pagecount      pages directly in this folder
            k_folder_totalpagecount pages in this folder AND all descendants

        Folders mode needs no query at all: the slider lists descendants having
        pages, and "some descendant has pages" is exactly
        totalpagecount > pagecount.

        Pages mode still needs the filter query (noshow / featured / value), but
        only for folders that actually hold pages - the count check skips the
        rest before any SQL runs.
    </cms:ignore>
    <cms:set cat_has_items = '0' />
    <cms:if ccs_nswp_content_typ = 'folders'>
        <cms:if k_folder_totalpagecount gt k_folder_pagecount>
            <cms:set cat_has_items = '1' />
        </cms:if>
    <cms:else />
        <cms:if k_folder_pagecount gt '0'>
            <cms:ignore>
                LEAF-ONLY. cms:pages defaults to include_subfolders='1'
                (tags.php:2044), which would qualify every ancestor of a folder
                that has pages - a three-deep chain would give three links all
                showing the same set. Forcing '0' means one link == one set.
                The slider query carries the same flag or the two disagree.
            </cms:ignore>
            <cms:pages masterpage=source_master folder=current_parent_name custom_field=nswp_custom_field include_subfolders='0' limit='1'>
                <cms:set cat_has_items = '1' scope='parent' />
            </cms:pages>
        </cms:if>
    </cms:if>

    <cms:if cat_has_items = '1'>

        <!-- First qualifying folder is what loads before any click -->
        <cms:if initial_load_cat = ''>
            <cms:set initial_load_cat = current_parent_name scope='global' />
        </cms:if>

        <!-- Active state is one stable class. Its appearance is defined once -->
        <!-- in user.php and toggled by the same name in the theme's JS, so no -->
        <!-- colour is named here or there. -->
        <cms:set nswp_nav_active_class = '' />
        <cms:if cat_override>
            <cms:if current_parent_name = cat_override>
                <cms:set nswp_nav_active_class = 'nswp-active' />
            </cms:if>
        <cms:else />
            <cms:if current_parent_name = initial_load_cat>
                <cms:set nswp_nav_active_class = 'nswp-active' />
            </cms:if>
        </cms:if>

        <cms:ignore>
            DEPTH CUE (pages / leaf-only mode only).
            Ancestors are hidden in that mode, so a bare title gives no sense of
            where the category sits. One dot per level above is a compact stand-in
            for a breadcrumb - it says "there is something before this" without
            eating the column width.

            The dots are decorative and aria-hidden. The real path goes on the
            link's aria-label, so screen readers get the full context that sighted
            users get from the dots. Deliberately NOT a title attribute: those
            never appear on touch devices and screen-reader support for them is
            inconsistent.

            cms:parentfolders reads the in-memory folder tree, so no extra queries.
        </cms:ignore>
        <cms:set ancestor_depth = '0' />
        <cms:if ccs_nswp_content_typ != 'folders'>
            <cms:if k_level><cms:set ancestor_depth = k_level /></cms:if>
        </cms:if>

        <cms:capture into='nav_aria'><cms:show current_parent_title /><cms:if ancestor_depth gt '0'>, in <cms:parentfolders folder=current_parent_name masterpage=source_master skip_child='1'><cms:if k_level gt '0'> / </cms:if><cms:show k_folder_title /></cms:parentfolders></cms:if></cms:capture>

        <cms:ignore>
            A button, not a link. These filter the slider in place - they never
            navigate - so href="#" made a screen reader announce "link" and put
            a nowhere-link in the link list. .btn-link exists precisely so a
            button can look like a link, and its hover colour comes from
            --ccs-btn-hover-color, a button variable, so styling is unchanged.
            Buttons also activate on Space as well as Enter.
            aria-pressed tells assistive tech which category is currently applied.
        </cms:ignore>
        <cms:capture into='temp_link'>
            <button type="button"
               class="btn btn-link py-2 d-block w-100 text-<cms:show nswp_txt_align /> ajax-nav-link nswp-folder-link level-<cms:show indent_value /> <cms:show nswp_nav_link_clr /> <cms:show nswp_nav_active_class />"
               aria-label="<cms:show nav_aria />"
               aria-pressed="<cms:if nswp_nav_active_class>true<cms:else />false</cms:if>"
               data-target-cat="<cms:show current_parent_name />">
                <span class="nswp-folder-marker" aria-hidden="true"></span>
                <cms:if ancestor_depth gt '0'><span class="nswp-depth-dots" aria-hidden="true"><cms:repeat count=ancestor_depth>&middot;</cms:repeat></span></cms:if>
                <cms:show current_parent_title />
            </button>
        </cms:capture>
        <cms:set left_nav_html = "<cms:show left_nav_html /><cms:show temp_link />" scope='global' />
    </cms:if>

</cms:folders>

<!-- Finalize target load cat for the slider -->
<cms:set target_load_cat = '' scope='global' />
<cms:if cat_override>
    <cms:set target_load_cat = cat_override scope='global' />
<cms:else />
    <cms:set target_load_cat = initial_load_cat scope='global' />
</cms:if>

<cms:ignore>
    EMPTY STATE.
    If no category qualified, target_load_cat is blank - and a blank folder is
    not a filter, it is "no filter", so the slider would silently list the whole
    source instead of nothing. That is the worst possible failure: it looks like
    data rather than an error. nswp_has_content lets the theme render nothing
    (or a message) instead.
    Reachable when the source dropdown is still on its unselected '-' value, or
    when every category is filtered out by the featured / value flags.
</cms:ignore>
<cms:set nswp_has_content = '1' scope='global' />
<cms:if target_load_cat = '' || my_target_folder = '-'>
    <cms:set nswp_has_content = '0' scope='global' />
</cms:if>