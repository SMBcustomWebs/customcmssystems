<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Global Site Settings" parent='_global_' icon='globe' hidden="0" order="90" >

    <cms:editable type='message' name='ccs_gl_site_gen_msg' order='2' >
        <h2>Global Website Settings</h2>
        <h3>Logos, site colors, site fonts, and set meta data for Search Engine Optimization</h3>
         <br><hr><br><hr><br>
    </cms:editable>
    
    <cms:editable type='message' name='ccs_gl_site_nav_msg' order='3' >
        <h3>Select Prebuilt Navbar and Footer for website. IMPORTANT: ONLY ONE OF EACH</h3>
        <h4>Delete or edit by using the trashcan or pencil that appear in the top-right corner.</h4>
    </cms:editable>
	
	<cms:pagebuilder name='ccs_gl_tpb_pb' label='Optional Top Bar' skip_custom_fields='1' order='4'>
        <cms:section label='Optional Top Bar' name='ccs_gl_tpb_sect'  masterpage='blocks/frame/utlbar.php' mosaic='ccs_utlbar_msc' />
    </cms:pagebuilder>
    
    <cms:pagebuilder name='ccs_gl_nav_pb' label='Site Navigation' skip_custom_fields='1' order='5'>
        <cms:section label='Navigation Menu' name='ccs_gl_nav_sect'  masterpage='blocks/frame/navbar.php' mosaic='ccs_nav_msc' />
    </cms:pagebuilder>
    
    <cms:pagebuilder name='ccs_gl_ftr_pb' label='Site Footer' skip_custom_fields='1' order='6'>
        <cms:section label='Footer' name='ccs_gl_ftr_sect'  masterpage='blocks/frame/footer.php' mosaic='ccs_site_ftr_msc' />
    </cms:pagebuilder>
	
    <cms:editable type='message' name='ccs_gl_site_biz_msg' order='8' >
        <h2>Expand this section to set the site's global options. All Settings here can also be accessed from the front-end.</h2>
        <h4>Below choose logos, site colors, site fonts, and set meta data for Search Engine Optimization</h4>
        <p>Click the bars to expand (+) or collapse (-)</p>
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_biz_grp' label='Website Business Settings' desc='logo, site name, tagline, color theme, favicon' collapsed='1' order='9'>
        <cms:editable type='message' name='ccs_gl_site_biz_lgo_msg' order='4' >
            <h2>Upload Logos, Icons.</h2>
            <h4>Site Logo can either be an image or the website name</h4>
            <p>May choose size and color if using website name in place of logo image.</p>
        </cms:editable>
        <cms:editable type='row' name='ccs_gl_site_biz_lgo_row'  order='5' >
            <cms:editable type='dropdown' name='ccs_gl_site_name_txt_lgo_opt' label='Websie Logo as image or use website name' desc='options below'
                opt_values='Image=0|Site Name=1'
                class='col-md-4'
                order='6'  
            />
            <cms:editable type='image' name='ccs_gl_site_logo_hoz' label='Primary Navbar Logo (Horizontal)' desc='Required: 400px wide by 100px tall. (4:1 ratio)'
                width='400' height='100' crop='0' enforce_max='1' show_preview='1' preview_width='150' class='col-md-6' order='8'
            />
            <cms:editable type='image' name='ccs_gl_site_logo_sqr' label='Square / Mobile Logo' desc='Required: 500px by 500px. (1:1 ratio)'
                width='500' height='500' crop='0' enforce_max='1' show_preview='1' preview_width='75' class='col-md-6' order='9'
            />
            <cms:editable type='image' name='ccs_gl_site_favicon' label='Favicon - may be ico, png, jpg' desc='The tiny logo that shows in browser tabs. Vector graphic recommended as favicon file'
                width='64' height='64' enforce_max='1' show_preview='1' preview_width='25' class='col-md-12' order='10'
            />
        </cms:editable>
        <cms:editable type='message' name='ccs_gl_site_biz_mta_msg' order='9' >
            <br><hr><br><hr><br>
            <h2>Settings for Main Website Business Identification</h2>
        </cms:editable>
        <cms:editable type='row' name='ccs_gl_site_biz_mta_row'  order='10' >
            <cms:editable type='text' name='ccs_gl_site_name' label='Name of Website' class='col-md-7' order='2' />
            <cms:func _into='ccs_gl_site_name_txt_lgo_chc' ccs_gl_site_name_txt_lgo_opt='' >
                <cms:if ccs_gl_site_name_txt_lgo_opt='1'>show<cms:else />hide</cms:if>
            </cms:func>
            <cms:editable type='dropdown' name='ccs_gl_site_name_txt_sz' label='Displayed Text Size' opt_values='dropdowns/heading-size.htm' dynamic='opt_values' not_active=ccs_gl_site_name_txt_lgo_chc class='col-md-3' order='4' />
            <cms:editable type='dropdown' name='ccs_gl_site_name_txt_clr' label='Displayed Text Color' opt_values='dropdowns/solid-colors.htm' opt_selected='danger' dynamic='opt_values' not_active=ccs_gl_site_name_txt_lgo_chc class='col-md-3' order='6' />
         </cms:editable>
        <cms:editable type='message' name='ccs_gl_tgln_fmt_msg' order='16' ><br><hr><h2>Format Text of Tagline</h2></cms:editable>
        <cms:editable type='row' name='ccs_gl_site_tgl_row'  order='10' >
            <cms:editable type='text' name='ccs_gl_tgln' label='Business Tagline' class='col-md-7' order='2' />
            <cms:editable type='checkbox' name='ccs_gl_tgln_opt' label='Show Tagline under logo?' opt_values='Show Tagline=1' opt_selected='1' class='col-md-3' order='3' />
            <cms:editable type='dropdown' name='ccs_gl_tgln_clr' label='Tagline Text Color' opt_values='dropdowns/solid-colors.htm' opt_selected='black' dynamic='opt_values' class='col-md-3' order='4' />
            <cms:editable type='dropdown' name='ccs_gl_tgln_txt_sz' label='Tagline Text Size' opt_values='dropdowns/text-size_sml.htm' dynamic='opt_values' class='col-md-3' order='6' />
            <cms:editable type='message' name='nca_cntnt_2col_img_br' class='col-md-12'  order='7' ><br><hr></cms:editable>            
            <cms:editable type='dropdown' name='ccs_gl_tgln_fnt_fm' label='Tagline Font Family' opt_values='dropdowns/font-family.htm' dynamic='opt_values' class='col-md-3' order='8' />
            <cms:editable type='dropdown' name='ccs_gl_tgln_fnt_wt' label='Tagline Font Weight' opt_values='dropdowns/font-weight.htm' dynamic='opt_values' class='col-md-3' order='10' />
            <cms:editable type='dropdown' name='ccs_gl_tgln_fnt_st' label='Tagline Font Style' opt_values='dropdowns/font-style.htm' dynamic='opt_values' class='col-md-3' order='12' />
            <cms:editable type='dropdown' name='ccs_gl_tgln_ltr_spc' label='Tagline Letter Spacing' opt_values='dropdowns/hdr_ltr-spc.htm' dynamic='opt_values' class='col-md-3' order='14' />
        </cms:editable>
        <cms:editable type='message' name='ccs_gl_site_desc_msg' order='19' ><br><hr><h2>Website Metadata</h2></cms:editable>
        <cms:editable type='textarea' name='ccs_gl_site_desc' label='Website Description' desc='SEO meta description.' class='col-md-12' order='20' />
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_nav_mnu_grp' label='Website Navigation Menu Settings' collapsed='0' order='12'>
        <cms:editable type='message' name='ccs_gl_site_nav_mnu_dd_msg' order='9' ><h2>Settings for Hovering and Dropdown Menus</h2></cms:editable>
        <cms:editable type='row' name='ccs_gl_site_nav_mnu_dd_row'  order='10' >
            <cms:editable type='dropdown' name='ccs_gl_site_nav_actv_clr' label='Menu Item Color on Active Page' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' order='4' />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_hvr_clr' label='Menu Text Color on Mouse Hover' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' order='8' />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_dd_txt_clr' label='Dropdown Menu Text Color' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' order='12' />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_dd_bg' label='Dropdown Menu Background Color' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' order='16' />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_dd_hvr_clr' label='Dropdown Menu Text Color on Mouse Hover' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' order='20' />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_dd_hvr_bg' label='Dropdown Menu Background Color on Mouse Hover' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' order='24' />
        </cms:editable>
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_cntc_grp' label='Website Contact Settings' order='15'>
        <cms:editable type='text' name='ccs_gl_hdqt_st_add' label='Headquarter Street Address' order='2' />
        <cms:editable type='text' name='ccs_gl_hdqt_st_ad2' label='Headquarter Suite' order='4' />
        <cms:editable type='text' name='ccs_gl_hdqt_cty' label='Headquarter City' order='6' />
        <cms:editable type='text' name='ccs_gl_hdqt_sta' label='Headquarter State' order='8' />
        <cms:editable type='text' name='ccs_gl_hdqt_zip' label='Headquarter Zip' order='10' />
        <cms:editable type='text' name='ccs_gl_hdqt_ggl_lnk' label='Google Map Link' order='12' />
        <cms:editable type='text' name='ccs_gl_hdqt_email' label='Public Email' order='14' />

        <cms:editable type='message' name='ccs_gl_cntct_pho_msg' order='16'><h3>Add Phone Numbers</h3></cms:editable>
        <cms:repeatable name='ccs_gl_hdqt_pho_rpt' label='Public Phone Numbers' order='18' >    
            <cms:editable type='text' name='ccs_gl_hdqt_pho_fa_icon' label='Phone Icon' width='140' order='2' />
            <cms:editable type='text' name='ccs_gl_hdqt_pho' label='Phone Number' width='230' order='4' />
        </cms:repeatable>
        <cms:editable type='text' name='ccs_gl_hdqt_fax' label='Public Fax' order='20' />
        
        <cms:editable type='message' name='ccs_gl_cntct_map_msg' order='24'><h3>Add Map Embed or Link</h3></cms:editable>
        <cms:repeatable name='ccs_gl_ggl_maps_rpt' label='Location Google Map Links' order='26' > 
            <cms:editable type='text' name='ccs_gl_hdqt_map_fa_icon' label='Map Icon' width='140' order='2' />
            <cms:editable type='textarea' name='ccs_gl_ggl_map_embed' label='Google Map Embed' order='4' />
            <cms:editable type='text' name='ccs_gl_ggl_map_link' label='Google Map Link' order='6' />
        </cms:repeatable>
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_scl_grp' label='Website Social Media Settings' order='18'>
        <cms:editable type='message' name='ccs_gl_social_msg' order='2'><h3>Add Social links and Icons</h3></cms:editable>   
        <cms:repeatable name='ccs_gl_social' label='Social Links' order='4' >
			<cms:editable name='ccs_gl_social_name' label='Name' type='text' order="2" />
			<cms:editable name='ccs_gl_social_icon' label='Icon' type='text' order="4" />
			<cms:editable name='ccs_gl_social_link' label='Social Link' type='text'  order="6" />
			<cms:editable type='dropdown' name='ccs_gl_social_bg_clr' label='Button Background Color' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' order='8' />
			<cms:editable type='dropdown' name='ccs_gl_social_txt_clr' label='Button Icon/Text Color' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' order='10' />
		</cms:repeatable> 
        <cms:editable type='text' name='ccs_gl_andr_app' label='Android App Link' order='6' />
        <cms:editable type='text' name='ccs_gl_appl_app' label='Apple App Link' order='8' />
    </cms:editable>
    
    <cms:editable type='checkbox' name='ccs_gl_site_custom_color_opt' label='Customize Site Colors' opt_values='Customize Colors=1' order='20' />
    <cms:editable type='checkbox' name='ccs_gl_site_custom_font_opt' label='Customize Site Fonts' opt_values='Customize Fonts=1' order='22' />

    <cms:func _into='ccs_gl_site_colors_custom' ccs_gl_site_custom_color_opt='' ><cms:if ("<cms:is '1' in=ccs_gl_site_custom_color_opt />") >show<cms:else />hide</cms:if></cms:func>
    <cms:func _into='ccs_gl_site_fonts_custom' ccs_gl_site_custom_font_opt=''><cms:if "<cms:is '1' in=ccs_gl_site_custom_font_opt />" >show<cms:else />hide</cms:if></cms:func>
    <cms:func _into='ccs_gl_site_def_thm' ccs_gl_site_custom_color_opt=''><cms:if "<cms:is '1' in=ccs_gl_site_custom_color_opt />" >hide<cms:else />show</cms:if></cms:func>
    
    <cms:editable type='dropdown' name='ccs_gl_site_thm_opt' label='Set Website Color Theme' opt_values='Light=light|Dark=dark|Primavera=primavera|Estate=estate|Autunno=autunno|Inverno=inverno' not_active=ccs_gl_site_def_thm order='25' />
    
    <cms:editable type='group' name='ccs_gl_site_site_cst_clr_grp' label='Custom Website Theme Colors' not_active=ccs_gl_site_colors_custom order='27' >
        <cms:editable type='color' name='ccs_gl_site_primary_cust' label='Primary Color' color='#007bff' alpha='0' width='50%' height='100px' order='12' />
        <cms:editable type='color' name='ccs_gl_site_secondary_cust' label='Secondary Color' color='#7968D9' alpha='0' width='50%' height='100px' order='14' />
        <cms:editable type='color' name='ccs_gl_site_tertiary_cust' label='Tertiary Color' color='#687BD9' alpha='0' width='50%' height='100px' order='14' />
        <cms:editable type='color' name='ccs_gl_site_quaternary_cust' label='Quaternary Color' color='#68C2D9' alpha='0' width='50%' height='100px' order='14' />
        <cms:editable type='color' name='ccs_gl_site_success_cust' label='Success Color' color='#28a745' alpha='0' width='50%' height='100px' order='15' />
        <cms:editable type='color' name='ccs_gl_site_info_cust' label='Info Color' color='#17a2b8' alpha='0' width='50%' height='100px' order='16' />
        <cms:editable type='color' name='ccs_gl_site_warning_cust' label='Warning Color' color='#ffc107' alpha='0' width='50%' height='100px' order='17' />
        <cms:editable type='color' name='ccs_gl_site_danger_cust' label='Danger Color' color='#dc3545' alpha='0' width='50%' height='100px' order='18' />
        <cms:editable type='color' name='ccs_gl_site_light_cust' label='Light Color' color='#f5f5f5' alpha='0' width='50%' height='100px' order='19' />
        <cms:editable type='color' name='ccs_gl_site_dark_cust' label='Dark Color' color='#17191b' alpha='0' width='50%' height='100px' order='20' />
        <cms:editable type='color' name='ccs_gl_site_white_cust' label='White' color='#007AFF' alpha='0' width='50%' height='100px' order='21' />
        <cms:editable type='color' name='ccs_gl_site_black_cust' label='Black' color='#007AFF' alpha='0' width='50%' height='100px' order='22' />
        <cms:editable type='color' name='ccs_gl_site_body_clr_cust' label='Text Color' color='#292b2c' alpha='0' width='50%' height='100px' order='23' />
        <cms:editable type='color' name='ccs_gl_site_body_bg_cust' label='Site Background' color='#ffffff' alpha='0' width='50%' height='100px' order='24' />
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_site_cst_slct_grp' label='Colors when SELECTING Text' order='30' >
        <cms:editable type='message' name='ccs_gl_site_site_cst_slct_msg' order='2'><h3>Choose Colors For Highlighting</h3></cms:editable>   
        <cms:editable type='row' name='ccs_gl_site_hglt_row' order='5' >
            <cms:editable type='dropdown' name='ccs_gl_site_hglt_clr' label='Highlighted Text Color' opt_values='dropdowns/theme-colors.htm' opt_selected='light' dynamic='opt_values' class='col-md-4' order='2' />
            <cms:editable type='dropdown' name='ccs_gl_site_hglt_bg' label='Hightlight Background Color' opt_values='dropdowns/theme-colors.htm' opt_selected='dark' dynamic='opt_values' class='col-md-4' order='4' />
        </cms:editable>
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_site_fonts' label='Website Custom Type Fonts' not_active=ccs_gl_site_fonts_custom order='33' >
        <cms:editable type='message' name='ccs_gl_site_font_cust_dfl_msg' order='13'><br><hr><h2>Default Font Style</h2></cms:editable>   
        <cms:editable type='textarea' name='ccs_gl_site_font_body_cust' label="Custom default font-family" no_xss_check='1' order='14' >"Montserrat", sans-serif</cms:editable>
        <cms:editable type='message' name='ccs_gl_site_font_cust_xtr_msg' order='17'><br><hr><h2>Extra Font Styles</h2></cms:editable>   
        <cms:editable type='textarea' name='ccs_gl_site_font_base_cust' label="Custom 'Base' font" no_xss_check='1' order='18' >"Helvetica Neue", Arial, sans-serif</cms:editable>
        <cms:editable type='textarea' name='ccs_gl_site_font_sans_cust' label="Custom Sans Serif Font" no_xss_check='1' order='22' >"Droid Sans", sans-serif</cms:editable>
        <cms:editable type='textarea' name='ccs_gl_site_font_serf_cust' label="Custom serif Font" no_xss_check='1' order='26' >"Playfair Display", serif</cms:editable>
        <cms:editable type='textarea' name='ccs_gl_site_font_mono_cust' label="Custom monospace Font" no_xss_check='1' order='30' >monospace</cms:editable>
        <cms:editable type='textarea' name='ccs_gl_site_font_crsv_cust' label="Custom cursive Font" no_xss_check='1' order='34' >"Comic Sans MS"</cms:editable>
        <cms:editable type='textarea' name='ccs_gl_site_font_decr_cust' label="Custom decorative Font" no_xss_check='1' order='38' >Sreda</cms:editable>
        <cms:editable type='text' name='ccs_gl_site_font_size_cust' label="Custom Font Size" maxlength="4" width="125" order="42" >1</cms:editable>
        <cms:editable type='text' name='ccs_gl_site_font_weight_cust' label="Custom Font Weight" maxlength="4" width="75" order="46" >400</cms:editable>
        <cms:editable type='text' name='ccs_gl_site_line_height_cust' label="Custom Line Height" maxlength="4" width="75" order="50" >1.45</cms:editable>
    </cms:editable>

    <cms:editable type='group' name='ccs_gl_maintenance_grp' label='Maintenance Mode' order='40'>
        <cms:editable type='checkbox' name='ccs_gl_maintenance_mode' label='Soft Maintenance Mode' desc='Check to redirect public traffic to a coming soon page' opt_values='Site Offline=1' order='1' />
    </cms:editable>

    <cms:editable type='group' name='ccs_gl_integrations_grp' label='Third-Party Scripts & Routing' order='45'>
        <cms:editable type='text' name='ccs_gl_form_email' label='Primary Form Routing Email' desc='Where should contact forms send notifications?' order='1' />
        <cms:editable type='textarea' name='ccs_gl_header_scripts' label='Header Scripts (Google Analytics, Pixel)' desc='DO NOT include <script> tags, just the code inside.' no_xss_check='1' order='2' />
        <cms:editable type='textarea' name='ccs_gl_footer_scripts' label='Footer Scripts (Live Chat, HubSpot)' no_xss_check='1' order='3' />
    </cms:editable>

    <cms:editable type='group' name='ccs_gl_legal_grp' label='Privacy & Cookies' order='50'>
        <cms:editable type='checkbox' name='ccs_gl_cookie_consent' label='Enable Cookie Consent Banner?' opt_values='Yes=1' order='1' />
        <cms:editable type='text' name='ccs_gl_privacy_link' label='Privacy Policy Link' order='2' />
        <cms:editable type='text' name='ccs_gl_terms_link' label='Terms of Service Link' order='3' />
    </cms:editable>

</cms:template>

<cms:if k_template_name="globals.php"><cms:set ccs_gl_edt_ok='1' /></cms:if>

<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />") > 
    <cms:embed 'pb_mods/pg_frame/head.htm' />
    <cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />
    
    <body class="bg-light">
        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Global Dashboard</h2>
                <cms:if (k_user_access_level ge '10')>
                    <a target="_blank" href="<cms:admin_link />" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-tools me-2"></i>Advanced Admin
                    </a>
                </cms:if>
            </div>

            <h4 class="text-muted fs-6 text-uppercase fw-bold mb-3 mt-5">Zone 1: Site Pulse</h4>
            <div class="row g-4">
                
                <div class="col-lg-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Site Status</h5>
                            <cms:popup_edit 'ccs_gl_maintenance_grp|ccs_gl_maintenance_mode' link_text="<button class='btn btn-sm btn-light rounded-circle shadow-sm' style='width:32px; height:32px;' title='Edit Maintenance Mode'><i class='fas fa-cog text-muted'></i></button>" />
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <cms:if ccs_gl_maintenance_mode == '1'>
                                    <div class="spinner-grow text-warning spinner-grow-sm me-2" role="status"></div>
                                    <span class="fw-bold text-warning">Under Maintenance</span>
                                <cms:else />
                                    <div class="spinner-grow text-success spinner-grow-sm me-2" role="status"></div>
                                    <span class="fw-bold text-success">Site is Live</span>
                                </cms:if>
                            </div>
                            <p class="text-muted small mb-0">Toggle maintenance mode to safely restrict public access during massive updates.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card shadow-sm h-100 border-0 bg-dark text-white">
                        <div class="card-body p-4 d-flex flex-column justify-content-center">
                            <div class="row text-center">
                                <div class="col border-end border-secondary">
                                    <h3 class="mb-1 fw-bold">--</h3>
                                    <span class="text-uppercase small text-muted">Page Views</span>
                                </div>
                                <div class="col border-end border-secondary">
                                    <h3 class="mb-1 fw-bold">--</h3>
                                    <span class="text-uppercase small text-muted">Form Leads</span>
                                </div>
                                <div class="col">
                                    <h3 class="mb-1 fw-bold text-success">99.9%</h3>
                                    <span class="text-uppercase small text-muted">Uptime</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="text-muted fs-6 text-uppercase fw-bold mb-3 mt-5">Zone 2: Visual Identity</h4>
            <div class="row g-4">
                
                <div class="col-lg-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Brand Assets</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                                <div>
                                    <span class="d-block text-muted small fw-bold text-uppercase">Site Name & Tagline</span>
                                    <span class="d-block fw-bold"><cms:show ccs_gl_site_name /></span>
                                </div>
                                <cms:popup_edit 'ccs_gl_site_biz_grp|ccs_gl_site_biz_mta_msg|ccs_gl_site_biz_mta_row|ccs_gl_site_name_txt_lgo_opt|ccs_gl_site_name|ccs_gl_site_name_txt_sz|ccs_gl_site_name_txt_clr|ccs_gl_tgln_fmt_msg|ccs_gl_site_tgl_row|ccs_gl_tgln|ccs_gl_tgln_opt|ccs_gl_tgln_clr|ccs_gl_tgln_txt_sz|nca_cntnt_2col_img_br|ccs_gl_tgln_fnt_fm|ccs_gl_tgln_fnt_wt|ccs_gl_tgln_fnt_st|ccs_gl_tgln_ltr_spc' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                                <div>
                                    <span class="d-block text-muted small fw-bold text-uppercase">Site Logos</span>
                                    <span class="d-block text-muted small">Nav & Mobile Images</span>
                                </div>
                                <cms:popup_edit 'ccs_gl_site_biz_grp|ccs_gl_site_biz_lgo_msg|ccs_gl_site_biz_lgo_row|ccs_gl_site_name_txt_lgo_opt|ccs_gl_site_logo_hoz|ccs_gl_site_logo_sqr|ccs_gl_site_favicon' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
    <div class="card shadow-sm h-100 border-0">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Theme & Colors</h5>
            <cms:popup_edit 'ccs_gl_site_custom_color_opt|ccs_gl_site_thm_opt|ccs_gl_site_site_cst_clr_grp|ccs_gl_site_primary_cust|ccs_gl_site_secondary_cust|ccs_gl_site_tertiary_cust|ccs_gl_site_success_cust|ccs_gl_site_warning_cust|ccs_gl_site_danger_cust' link_text="<button class='btn btn-sm btn-light rounded-circle shadow-sm' style='width:32px; height:32px;' title='Edit Theme & Colors'><i class='fas fa-palette text-primary'></i></button>" />
        </div>
        
        <div class="card-body" data-bs-theme="<cms:show ccs_gl_site_thm_opt />">
            
            <div class="mb-3">
                <span class="badge bg-dark text-uppercase px-2 py-1"><cms:show ccs_gl_site_thm_opt /> Theme</span>
                <cms:if ccs_gl_site_custom_color_opt><span class="badge bg-primary text-uppercase px-2 py-1 ms-1">Custom Overrides Active</span></cms:if>
            </div>
            
            <div class="mt-3">
    <span class="d-block text-muted small fw-bold text-uppercase mb-2">Active Theme Palette</span>
    <div class="d-flex flex-wrap gap-2">
        <div title="Primary" class="rounded-circle shadow-sm border border-secondary border-opacity-25" style="width: 32px; height: 32px; background-color: <cms:get_custom_field 'ccs_gl_site_primary_cust' masterpage='globals.php' />;"></div>
        <div title="Secondary" class="rounded-circle shadow-sm border border-secondary border-opacity-25" style="width: 32px; height: 32px; background-color: <cms:get_custom_field 'ccs_gl_site_secondary_cust' masterpage='globals.php' />;"></div>
        <div title="Tertiary" class="rounded-circle shadow-sm border border-secondary border-opacity-25" style="width: 32px; height: 32px; background-color: <cms:get_custom_field 'ccs_gl_site_tertiary_cust' masterpage='globals.php' />;"></div>
        <div title="Quaternary" class="rounded-circle shadow-sm border border-secondary border-opacity-25" style="width: 32px; height: 32px; background-color: <cms:get_custom_field 'ccs_gl_site_quaternary_cust' masterpage='globals.php' />;"></div>
    </div>
</div>
<div class="mt-2">
    <span class="d-block text-muted small fw-bold text-uppercase mb-2">Utility Colors</span>
    <div class="d-flex flex-wrap gap-2">
        <div title="Success" class="rounded-circle shadow-sm border border-secondary border-opacity-25" style="width: 28px; height: 28px; background-color: <cms:get_custom_field 'ccs_gl_site_success_cust' masterpage='globals.php' />;"></div>
        <div title="Info" class="rounded-circle shadow-sm border border-secondary border-opacity-25" style="width: 28px; height: 28px; background-color: <cms:get_custom_field 'ccs_gl_site_info_cust' masterpage='globals.php' />;"></div>
        <div title="Warning" class="rounded-circle shadow-sm border border-secondary border-opacity-25" style="width: 28px; height: 28px; background-color: <cms:get_custom_field 'ccs_gl_site_warning_cust' masterpage='globals.php' />;"></div>
        <div title="Danger" class="rounded-circle shadow-sm border border-secondary border-opacity-25" style="width: 28px; height: 28px; background-color: <cms:get_custom_field 'ccs_gl_site_danger_cust' masterpage='globals.php' />;"></div>
        <div title="Light" class="rounded-circle shadow-sm border border-secondary border-opacity-25" style="width: 28px; height: 28px; background-color: <cms:get_custom_field 'ccs_gl_site_light_cust' masterpage='globals.php' />;"></div>
        <div title="Dark" class="rounded-circle shadow-sm border border-secondary border-opacity-25" style="width: 28px; height: 28px; background-color: <cms:get_custom_field 'ccs_gl_site_dark_cust' masterpage='globals.php' />;"></div>
    </div>
</div>
    </div>
</div></div>

                <div class="col-lg-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Typography</h5>
                            <cms:popup_edit 'ccs_gl_site_custom_font_opt|ccs_gl_site_site_fonts|ccs_gl_site_font_body_cust|ccs_gl_site_font_base_cust|ccs_gl_site_font_sans_cust|ccs_gl_site_font_mono_cust|ccs_gl_site_font_size_cust|ccs_gl_site_font_weight_cust|ccs_gl_site_line_height_cust' link_text="<button class='btn btn-sm btn-light rounded-circle shadow-sm' style='width:32px; height:32px;' title='Edit Typography'><i class='fas fa-font text-primary'></i></button>" />
                        </div>
                        <div class="card-body">
                            <div class="p-3 bg-light rounded mb-2 overflow-hidden">
                                <span class="d-block text-muted small fw-bold text-uppercase mb-1">Body Text (Sans-Serif)</span>
                                <span class="fs-5" style="font-family: <cms:show ccs_gl_site_font_sans_cust />;">The quick brown fox jumps.</span>
                            </div>
                            <div class="p-3 bg-light rounded overflow-hidden">
                                <span class="d-block text-muted small fw-bold text-uppercase mb-1">Headings (Serif)</span>
                                <span class="fs-5 fw-bold" style="font-family: <cms:show ccs_gl_site_font_serf_cust />;">The quick brown fox.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="text-muted fs-6 text-uppercase fw-bold mb-3 mt-5">Zone 3: Architecture & Integrations</h4>
            <div class="row g-4">
                
                <div class="col-lg-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                            <h5 class="mb-0 fw-bold">Site Architecture</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-bars text-muted me-2"></i> Navbar Block</span>
                                    <cms:popup_edit 'ccs_gl_nav_pb' link_text="<button class='btn btn-sm btn-outline-primary'>Assign</button>" />
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-arrow-up text-muted me-2"></i> Top Utility Bar</span>
                                    <cms:popup_edit 'ccs_gl_tpb_pb' link_text="<button class='btn btn-sm btn-outline-primary'>Assign</button>" />
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center border-bottom-0">
                                    <span><i class="fas fa-shoe-prints text-muted me-2"></i> Footer Block</span>
                                    <cms:popup_edit 'ccs_gl_ftr_pb' link_text="<button class='btn btn-sm btn-outline-primary'>Assign</button>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Architecture -->
				<div class="col-lg-4">
					<div class="card shadow-sm h-100 border-0">
						<div class="card-header bg-white border-bottom-0 pt-4 pb-0">
							<h5 class="mb-0 fw-bold">Site Architecture</h5>
						</div>
						<div class="card-body">

							<!-- Hidden Counters -->
							<cms:set nav_count='0' scope='global' />
							<cms:show_pagebuilder 'ccs_gl_nav_pb'><cms:set nav_count=k_count scope='global' /></cms:show_pagebuilder>

							<cms:set ftr_count='0' scope='global' />
							<cms:show_pagebuilder 'ccs_gl_ftr_pb'><cms:set ftr_count=k_count scope='global' /></cms:show_pagebuilder>

							<cms:set tpb_count='0' scope='global' />
							<cms:show_pagebuilder 'ccs_gl_tpb_pb'><cms:set tpb_count=k_count scope='global' /></cms:show_pagebuilder>

							<div class="list-group list-group-flush">

								<!-- Navbar Status -->
								<div class="list-group-item px-0 d-flex justify-content-between align-items-center">
									<div>
										<i class="fas fa-bars text-muted me-2"></i> Navbar 
										<cms:if nav_count == '1'>
											<span class="badge bg-success ms-1">1 Active</span>
										<cms:else_if nav_count == '0' />
											<span class="badge bg-danger ms-1">Missing</span>
										<cms:else />
											<span class="badge bg-danger ms-1"><cms:show nav_count /> Detected (Delete <cms:show "<cms:sub nav_count '1' />" />)</span>
										</cms:if>
									</div>
									<cms:popup_edit 'ccs_gl_nav_pb' link_text="<button class='btn btn-sm btn-outline-primary'>Manage</button>" />
								</div>

								<!-- Top Utility Bar Status -->
								<div class="list-group-item px-0 d-flex justify-content-between align-items-center">
									<div>
										<i class="fas fa-arrow-up text-muted me-2"></i> Utility Bar
										<cms:if tpb_count == '1' || tpb_count == '2'>
											<span class="badge bg-success ms-1"><cms:show tpb_count /> Active</span>
										<cms:else_if tpb_count == '0' />
											<span class="badge bg-secondary ms-1">None (Optional)</span>
										<cms:else />
											<span class="badge bg-danger ms-1"><cms:show tpb_count /> Detected (Max 2)</span>
										</cms:if>
									</div>
									<cms:popup_edit 'ccs_gl_tpb_pb' link_text="<button class='btn btn-sm btn-outline-primary'>Manage</button>" />
								</div>

								<!-- Footer Status -->
								<div class="list-group-item px-0 d-flex justify-content-between align-items-center border-bottom-0">
									<div>
										<i class="fas fa-shoe-prints text-muted me-2"></i> Footer
										<cms:if ftr_count == '1'>
											<span class="badge bg-success ms-1">1 Active</span>
										<cms:else_if ftr_count == '0' />
											<span class="badge bg-danger ms-1">Missing</span>
										<cms:else />
											<span class="badge bg-danger ms-1"><cms:show ftr_count /> Detected (Delete <cms:show "<cms:sub ftr_count '1' />" />)</span>
										</cms:if>
									</div>
									<cms:popup_edit 'ccs_gl_ftr_pb' link_text="<button class='btn btn-sm btn-outline-primary'>Manage</button>" />
								</div>

							</div>

							<cms:if nav_count gt '1' || ftr_count gt '1' || tpb_count gt '2'>
								<div class="alert alert-danger small py-2 mt-3 mb-0">
									<i class="fas fa-triangle-exclamation me-1"></i> <strong>Layout Error:</strong> You have too many blocks active. Only the first valid block will be displayed on your live site.
								</div>
							</cms:if>

						</div>
					</div>
				</div>

                <div class="col-lg-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                            <h5 class="mb-0 fw-bold">Integrations & Legal</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-envelope-open-text text-muted me-2"></i> Form Email Target</span>
                                    <cms:popup_edit 'ccs_gl_integrations_grp|ccs_gl_form_email' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-code text-muted me-2"></i> Tracking Scripts</span>
                                    <cms:popup_edit 'ccs_gl_integrations_grp|ccs_gl_header_scripts|ccs_gl_footer_scripts' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </div>
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center border-bottom-0">
                                    <span><i class="fas fa-shield-halved text-muted me-2"></i> Cookie & Privacy</span>
                                    <cms:popup_edit 'ccs_gl_legal_grp|ccs_gl_cookie_consent|ccs_gl_privacy_link|ccs_gl_terms_link' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />    
    <cms:embed 'pb_mods/pg_frame/tail.htm' />
    
<cms:else />
    <cms:redirect k_site_link />
</cms:if>
<?php COUCH::invoke(); ?>