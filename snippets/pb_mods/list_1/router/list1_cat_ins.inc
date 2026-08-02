<!-- ======================================================= -->
<!-- 1. DATA SOURCE PARSING -->
<!-- ======================================================= -->
<cms:set ccs_lst1_target_template='' scope='global' />
<cms:set ccs_lst1_target_folder='' scope='global' />

<cms:each ccs_lst1_src_opt sep=','>
    <cms:if k_count='1'><cms:set ccs_lst1_target_template=item scope='global' /></cms:if>
    <cms:if k_count='2'><cms:set ccs_lst1_target_folder=item scope='global' /></cms:if>
</cms:each>

<!-- ======================================================= -->
<!-- 2. HEADER TEXT BOX SIZING -->
<!-- ======================================================= -->
<cms:set ccs_lst1_txtbx_size='' scope='global' />
<cms:if ccs_lst1_hdg_txtbx_sz_opt='custom'>
    <cms:capture into='ccs_lst1_txtbx_size' scope='global'>
        <cms:show_repeatable 'ccs_lst1_hdg_txtbx_cstm_sz_rpt'>
            col-<cms:show ccs_lst1_hdg_txtbx_cstm_col_sz />-<cms:show ccs_lst1_hdg_txtbx_cstm_col_val /><cms:if "<cms:not k_last_item />"> </cms:if>
        </cms:show_repeatable>
    </cms:capture>
<cms:else />
    <cms:if ccs_lst1_hdg_txtbx_sz_opt != ''>
        <cms:set ccs_lst1_txtbx_size="col-<cms:show ccs_lst1_hdg_txtbx_sz_opt />" scope="global" />
    </cms:if>
</cms:if>

<!-- ======================================================= -->
<!-- 3. TYPOGRAPHY COMPILATION -->
<!-- ======================================================= -->
<cms:set ccs_lst1_hdg_classes='' scope='global' />
<cms:set ccs_lst1_sub_classes='' scope='global' />
<cms:set ccs_lst1_hdg_tag='h2' scope='global' />
<cms:set ccs_lst1_sub_tag='p' scope='global' />

<cms:if ccs_lst1_hdr_ovr_opt='1'>
    <!-- Override Tags -->
    <cms:if ccs_lst1_hdg_txt_hdg_sz != ''><cms:set ccs_lst1_hdg_tag=ccs_lst1_hdg_txt_hdg_sz scope='global' /></cms:if>
    <cms:if ccs_lst1_sub_txt_hdg_sz != ''><cms:set ccs_lst1_sub_tag=ccs_lst1_sub_txt_hdg_sz scope='global' /></cms:if>

    <!-- Build Main Headline Classes -->
    <cms:capture into='ccs_lst1_hdg_classes' scope='global'>
        <cms:if ccs_lst1_hdg_txt_clr != ''>text-<cms:show ccs_lst1_hdg_txt_clr /> </cms:if>
        <cms:if ccs_lst1_hdg_txt_sz != ''>fs-<cms:show ccs_lst1_hdg_txt_sz /> </cms:if>
        <cms:if ccs_lst1_hdg_txt_font != ''>font-<cms:show ccs_lst1_hdg_txt_font /> </cms:if>
        <cms:if ccs_lst1_hdg_txt_fnt_wt != ''>fw-<cms:show ccs_lst1_hdg_txt_fnt_wt /> </cms:if>
        <cms:if ccs_lst1_hdg_txt_fnt_st != ''>fst-<cms:show ccs_lst1_hdg_txt_fnt_st /> </cms:if>
        <cms:if ccs_lst1_hdg_txt_ltr_spc != ''>ls-<cms:show ccs_lst1_hdg_txt_ltr_spc /> </cms:if>
        <cms:if ccs_lst1_hdg_txt_ln_ht != ''>lh-<cms:show ccs_lst1_hdg_txt_ln_ht /></cms:if>
    </cms:capture>

    <!-- Build Sub-Headline Classes -->
    <cms:capture into='ccs_lst1_sub_classes' scope='global'>
        <cms:if ccs_lst1_sub_txt_clr != ''>text-<cms:show ccs_lst1_sub_txt_clr /> </cms:if>
        <cms:if ccs_lst1_sub_txt_sz != ''>fs-<cms:show ccs_lst1_sub_txt_sz /> </cms:if>
        <cms:if ccs_lst1_sub_txt_font != ''>font-<cms:show ccs_lst1_sub_txt_font /> </cms:if>
        <cms:if ccs_lst1_sub_txt_fnt_wt != ''>fw-<cms:show ccs_lst1_sub_txt_fnt_wt /> </cms:if>
        <cms:if ccs_lst1_sub_txt_fnt_st != ''>fst-<cms:show ccs_lst1_sub_txt_fnt_st /> </cms:if>
        <cms:if ccs_lst1_sub_txt_ltr_spc != ''>ls-<cms:show ccs_lst1_sub_txt_ltr_spc /> </cms:if>
        <cms:if ccs_lst1_sub_txt_ln_ht != ''>lh-<cms:show ccs_lst1_sub_txt_ln_ht /></cms:if>
    </cms:capture>
</cms:if>

<!-- ======================================================= -->
<!-- 4. BACKGROUND CLASS COMPILATION -->
<!-- ======================================================= -->
<cms:if ccs_lst1_bg_clr_opt='gradient'>
    <cms:set ccs_lst1_bg_class="bg-gradient" scope='global' />
<cms:else />
    <cms:set ccs_lst1_bg_class="bg-<cms:show ccs_lst1_bg_clr_opt />" scope='global' />
</cms:if>