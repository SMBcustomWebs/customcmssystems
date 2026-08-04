<!-- 1. TEXT BOX SIZE CALCULATION -->
<cms:if ccs_lst1_hdg_txtbx_sz_opt='custom'>
    <cms:capture into='txtbx_size_cap' scope="global">
        <cms:show_repeatable 'ccs_lst1_hdg_txtbx_cstm_sz_rpt'>
            col-<cms:show ccs_lst1_hdg_txtbx_cstm_col_sz />-<cms:show ccs_lst1_hdg_txtbx_cstm_col_val />
        </cms:show_repeatable>
    </cms:capture>
    <cms:set txtbx_size="<cms:show txtbx_size_cap />" scope="global"/>
<cms:else />
    <cms:set txtbx_size="col <cms:show ccs_lst1_hdg_txtbx_sz_opt />" scope="global" />
</cms:if>

<!-- 2. DATA SOURCE CALCULATION -->
<cms:if ccs_lst1_src_opt='all' || ccs_lst1_src_opt='-'>
    <cms:set target_folder='' scope='global' />
<cms:else />
    <cms:set target_folder=ccs_lst1_src_opt scope='global' />
</cms:if>

<!-- 3. TYPOGRAPHY & SPACING CLASS COMPILATION -->
<!-- Main Heading -->
<cms:capture into='main_hdg_classes' scope='global'>
    <cms:if ccs_lst1_hdg_txt_clr>text-<cms:show ccs_lst1_hdg_txt_clr /> </cms:if>
    <cms:if ccs_lst1_hdg_txt_font>font-<cms:show ccs_lst1_hdg_txt_font /> </cms:if>
    <cms:if ccs_lst1_hdg_txt_fnt_wt>fw-<cms:show ccs_lst1_hdg_txt_fnt_wt /> </cms:if>
    <cms:if ccs_lst1_hdg_txt_fnt_st>fst-<cms:show ccs_lst1_hdg_txt_fnt_st /> </cms:if>
    <cms:if ccs_lst1_hdg_txt_ltr_spc>ls-<cms:show ccs_lst1_hdg_txt_ltr_spc /> </cms:if>
    <cms:if ccs_lst1_hdg_txt_ln_ht>lh-<cms:show ccs_lst1_hdg_txt_ln_ht /> </cms:if>
</cms:capture>

<!-- Sub Heading -->
<cms:capture into='sub_hdg_classes' scope='global'>
    <cms:if ccs_lst1_sub_txt_clr>text-<cms:show ccs_lst1_sub_txt_clr /> </cms:if>
    <cms:if ccs_lst1_sub_txt_sz>fs-<cms:show ccs_lst1_sub_txt_sz /> </cms:if>
    <cms:if ccs_lst1_sub_txt_font>font-<cms:show ccs_lst1_sub_txt_font /> </cms:if>
    <cms:if ccs_lst1_sub_txt_fnt_wt>fw-<cms:show ccs_lst1_sub_txt_fnt_wt /> </cms:if>
    <cms:if ccs_lst1_sub_txt_fnt_st>fst-<cms:show ccs_lst1_sub_txt_fnt_st /> </cms:if>
    <cms:if ccs_lst1_sub_txt_ltr_spc>ls-<cms:show ccs_lst1_sub_txt_ltr_spc /> </cms:if>
    <cms:if ccs_lst1_sub_txt_ln_ht>lh-<cms:show ccs_lst1_sub_txt_ln_ht /> </cms:if>
</cms:capture>

<!-- Dividing Line -->
<cms:capture into='hr_classes' scope='global'>
    <cms:show ccs_lst1_hdg_hr /> <cms:if ccs_lst1_hdg_hr_clr>text-<cms:show ccs_lst1_hdg_hr_clr /> </cms:if><cms:if ccs_lst1_hdg_hr_pd>my-<cms:show ccs_lst1_hdg_hr_pd /> </cms:if>mx-auto
</cms:capture>

<!-- Grid Item Headings (Compiled ONLY if the override box is checked) -->
<cms:set item_hdg_classes='' scope='global' />
<cms:set item_sub_classes='' scope='global' />

<cms:if ccs_lst1_itm_typo_opt='1'>
    <cms:capture into='item_hdg_classes' scope='global'>
        <cms:if ccs_lst1_itm_hdg_clr>text-<cms:show ccs_lst1_itm_hdg_clr /> </cms:if>
        <cms:if ccs_lst1_itm_hdg_sz>fs-<cms:show ccs_lst1_itm_hdg_sz /> </cms:if>
        <cms:if ccs_lst1_itm_hdg_font>font-<cms:show ccs_lst1_itm_hdg_font /> </cms:if>
        <cms:if ccs_lst1_itm_hdg_fnt_wt>fw-<cms:show ccs_lst1_itm_hdg_fnt_wt /> </cms:if>
        <cms:if ccs_lst1_itm_hdg_fnt_st>fst-<cms:show ccs_lst1_itm_hdg_fnt_st /> </cms:if>
        <cms:if ccs_lst1_itm_hdg_ltr_spc>ls-<cms:show ccs_lst1_itm_hdg_ltr_spc /> </cms:if>
        <cms:if ccs_lst1_itm_hdg_ln_ht>lh-<cms:show ccs_lst1_itm_hdg_ln_ht /> </cms:if>
    </cms:capture>

    <cms:capture into='item_sub_classes' scope='global'>
        <cms:if ccs_lst1_itm_sub_clr>text-<cms:show ccs_lst1_itm_sub_clr /> </cms:if>
        <cms:if ccs_lst1_itm_sub_sz>fs-<cms:show ccs_lst1_itm_sub_sz /> </cms:if>
        <cms:if ccs_lst1_itm_sub_font>font-<cms:show ccs_lst1_itm_sub_font /> </cms:if>
        <cms:if ccs_lst1_itm_sub_fnt_wt>fw-<cms:show ccs_lst1_itm_sub_fnt_wt /> </cms:if>
        <cms:if ccs_lst1_itm_sub_fnt_st>fst-<cms:show ccs_lst1_itm_sub_fnt_st /> </cms:if>
        <cms:if ccs_lst1_itm_sub_ltr_spc>ls-<cms:show ccs_lst1_itm_sub_ltr_spc /> </cms:if>
        <cms:if ccs_lst1_itm_sub_ln_ht>lh-<cms:show ccs_lst1_itm_sub_ln_ht /> </cms:if>
    </cms:capture>
</cms:if>

<!-- 4. DYNAMIC HTML TAG GENERATION (Avoids Parser Errors) -->

<!-- Set fallbacks so the HTML tag never breaks if the variable is empty -->
<cms:set main_tag=ccs_lst1_hdg_txt_hdg_sz />
<cms:if main_tag=''><cms:set main_tag='h2' /></cms:if>

<!-- Build the tags without the rogue backslashes -->
<cms:set main_hdg_open="<cms:concat '<' main_tag ' class="' main_hdg_classes '">' />" scope="global" />
<cms:set main_hdg_close="<cms:concat '</' main_tag '>' />" scope="global" />

<!-- Re-added Dynamic sub-tag logic to match the HTML display file -->
<cms:set sub_hdg_open="<cms:concat '<p class="' sub_hdg_classes '">' />" scope="global" />
<cms:set sub_hdg_close="</p>" scope="global" />

<!-- 5. CUSTOM BACKGROUND COLOR/GRADIENT LOGIC -->
<cms:capture into='custom_bg_style' scope='global'>
    <cms:if ccs_lst1_bg_clr_opt == 'gradient'>
        <cms:if ccs_lst1_bg_clr_cstm_grdt_opt == '0'>
            background-color: <cms:show ccs_lst1_bg_clr_cstm_one />;
        <cms:else />
            <cms:set grad_dir = ccs_lst1_bg_clr_cust_grad_split_opt />
            <cms:if ccs_lst1_bg_clr_cstm_grad_rvrs_opt >
                background: linear-gradient(<cms:show grad_dir />deg, <cms:show ccs_lst1_bg_clr_cstm_two />, <cms:show ccs_lst1_bg_clr_cstm_one />);
            <cms:else />
                background: linear-gradient(<cms:show grad_dir />deg, <cms:show ccs_lst1_bg_clr_cstm_one />, <cms:show ccs_lst1_bg_clr_cstm_two />);
            </cms:if>
        </cms:if>
    </cms:if>
</cms:capture>