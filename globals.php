<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Global Site Settings" parent='_global_'  icon='globe' hidden="0" order="90" >

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
            <cms:editable type='image' name='ccs_gl_site_logo' label='Site Logo for Nav Bar' desc='Upload Site Logo '
                width='254'
                height='170'
                enforce_max='1'
                show_preview='1'
                preview_width='75'
                class='col-md-12'
                order='8'
            />
            <cms:editable type='image' name='ccs_gl_site_favicon' label='Favicon - may be ico, png, jpg' desc='The tiny logo that shows in browser tabs. Vector graphic recommended as favicon file'
                width='64'
                height='64'
                enforce_max='1'
                show_preview='1'
                preview_width='25'
                class='col-md-12'
                order='10'
            />
        </cms:editable>
        <cms:editable type='message' name='ccs_gl_site_biz_mta_msg' order='9' >
            <br><hr><br><hr><br>
            <h2>Settings for Main Website Business Identification</h2>
            <h4>Site Logo can either be an image or the website name</h4>
            <p>May choose size and color if using website name in place of logo image.</p>
        </cms:editable>
        <cms:editable type='row' name='ccs_gl_site_biz_mta_row'  order='10' >
            <cms:editable type='text' name='ccs_gl_site_name' label='Name of Website'  desc='May also be used for as site logo'
                class='col-md-7'
                order='2' 
            />
            <cms:func _into='ccs_gl_site_name_txt_lgo_chc' ccs_gl_site_name_txt_lgo_opt='' >
                <cms:if ccs_gl_site_name_txt_lgo_opt='1'>
                    show
                    <cms:else />
                    hide
                </cms:if>
            </cms:func>
            <cms:editable type='dropdown' name='ccs_gl_site_name_txt_sz' label='Displayed Text Size' desc=''
                opt_values='dropdowns/heading-size.htm'
                dynamic='opt_values'
                not_active=ccs_gl_site_name_txt_lgo_chc
                class='col-md-3'
                order='4'  
            />
            <cms:editable type='dropdown' name='ccs_gl_site_name_txt_clr' label='Displayed Text Color' desc=''
                opt_values='dropdowns/solid-colors.htm'
                opt_selected='danger'
                dynamic='opt_values'
                not_active=ccs_gl_site_name_txt_lgo_chc
                class='col-md-3'
                order='6'  
            />
         </cms:editable>
        <cms:editable type='message' name='ccs_gl_tgln_fmt_msg' order='16' >
            <br><hr>
            <h2>Format Text of Tagline</h2>
            <h4>Adjust font size and look.</h4>
            <p></p>
        </cms:editable>
        <cms:editable type='row' name='ccs_gl_site_tgl_row'  order='10' >
            <cms:editable type='text' name='ccs_gl_tgln' label='Business Tagline'  desc='optionally shown underneath logo'
                class='col-md-7'
                order='2' 
            />
            <cms:editable type='checkbox' name='ccs_gl_tgln_opt' label='Show Tagline under logo?' desc='uncheck to hide'
                opt_values='Show Tagline=1'
                opt_selected='1'
                class='col-md-3'
                order='3'
            />
            <cms:editable type='dropdown' name='ccs_gl_tgln_clr' label='Tagline Text Color'
                opt_values='dropdowns/solid-colors.htm'
                opt_selected='black'
                dynamic='opt_values'
                class='col-md-3'
                order='4'  
            />
            <cms:editable type='dropdown' name='ccs_gl_tgln_txt_sz' label='Tagline Text Size' 
                opt_values='dropdowns/text-size_sml.htm'
                dynamic='opt_values'
                class='col-md-3'
                order='6'  
            />
            <cms:editable type='message' name='nca_cntnt_2col_img_br' class='col-md-12'  order='7' >
                <br><hr>
            </cms:editable>            
            <cms:editable type='dropdown' name='ccs_gl_tgln_fnt_fm' label='Tagline Font Family' 
                opt_values='dropdowns/font-family.htm'
                dynamic='opt_values'
                class='col-md-3'
                order='8'  
            />
            <cms:editable type='dropdown' name='ccs_gl_tgln_fnt_wt' label='Tagline Font Weight' 
                opt_values='dropdowns/font-weight.htm'
                dynamic='opt_values'
                class='col-md-3'
                order='10'  
            />
            <cms:editable type='dropdown' name='ccs_gl_tgln_fnt_st' label='Tagline Font Style' 
                opt_values='dropdowns/font-style.htm'
                dynamic='opt_values'
                class='col-md-3'
                order='12'  
            />
            <cms:editable type='dropdown' name='ccs_gl_tgln_ltr_spc' label='Tagline Letter Spacing' 
                opt_values='dropdowns/hdr_ltr-spc.htm'
                dynamic='opt_values'
                class='col-md-3'
                order='14'  
            />
        </cms:editable>
        <cms:editable type='message' name='ccs_gl_site_desc_msg' order='19' >
            <br><hr>
            <h2>Website Metadata</h2>
            <h4>Paragraph that describes this website for search engines</h4>
            <p>Recommended to keep within 160 char</p>
        </cms:editable>
        <cms:editable type='textarea' name='ccs_gl_site_desc' label='Website Description' desc='SEO meta description. Recommended 160 char max'  
            class='col-md-12'
            order='20'
        />
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_nav_mnu_grp' label='Website Navigation Menu Settings' desc='navigation menu ative, dropdown, and hover colors' collapsed='0' order='12'>
        <cms:editable type='message' name='ccs_gl_site_nav_mnu_dd_msg' order='9' >
            <h2>Settings for Hovering and Dropdown Menus on Navigation Bar</h2>
            <h4>How to show user what the active (current) page is</h4>
            <p>Sets the color when mouse hovers over the menu item.</p>
        </cms:editable>
        <cms:editable type='row' name='ccs_gl_site_nav_mnu_dd_row'  order='10' >
            <cms:editable type='dropdown' name='ccs_gl_site_nav_actv_clr' label='Menu Item Color on Active Page' desc='Color of menu title when on the same page as menu title'
                opt_values='dropdowns/solid-colors.htm'
                dynamic='opt_values'
                order='4'  
            />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_hvr_clr' label='Menu Text Color on Mouse Hover' desc='Color of menu title when mouse hovers over the menu title'
                opt_values='dropdowns/solid-colors.htm'
                dynamic='opt_values'
                order='8'  
            />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_dd_txt_clr' label='Dropdown Menu Text Color' desc='Color of menu title in the dropdown menus'
                opt_values='dropdowns/solid-colors.htm'
                dynamic='opt_values'
                order='12'  
            />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_dd_bg' label='Dropdown Menu Background Color' desc='Color of backdrop in the dropdown menus'
                opt_values='dropdowns/solid-colors.htm'
                dynamic='opt_values'
                order='16'  
            />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_dd_hvr_clr' label='Dropdown Menu Text Color on Mouse Hover' desc='Color of menu title when in the dropdown menu when mouse hovers over menu title'
                opt_values='dropdowns/solid-colors.htm'
                dynamic='opt_values'
                order='20'  
            />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_dd_hvr_bg' label='Dropdown Menu Background Color on Mouse Hover' desc='Color of backdrop in the dropdown menu when mouse floats over menu title'
                opt_values='dropdowns/solid-colors.htm'
                dynamic='opt_values'
                order='24'  
            />
        </cms:editable>
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_cntc_grp' label='Website Contact Settings' order='15'>
        <cms:editable type='text' name='ccs_gl_hdqt_st_add' label='Headquarter Street Address'  desc='' order='2' />
        <cms:editable type='text' name='ccs_gl_hdqt_st_ad2' label='Headquarter Suite'  desc='optional' order='4' />
        <cms:editable type='text' name='ccs_gl_hdqt_cty' label='Headquarter City'  desc='' order='6' />
        <cms:editable type='text' name='ccs_gl_hdqt_sta' label='Headquarter State'  desc='' order='8' />
        <cms:editable type='text' name='ccs_gl_hdqt_zip' label='Headquarter Zip'  desc='' order='10' />
        <cms:editable type='text' name='ccs_gl_hdqt_ggl_lnk' label='Google Map Link'  desc='for address link to show Google Map' order='12' />
        <cms:editable type='text' name='ccs_gl_hdqt_email' label='Public Email'  desc='' order='14' />

        <cms:editable type='message' name='ccs_gl_cntct_pho_msg' order='16'>
            <h3>Add Phone Numbers</h3>
            <h4></h4>
            <p>Get phone icons <a href="https://fontawesome.com/v6/search" target="_bland"> here. https://fontawesome.com/v6/search</a></p>
        </cms:editable>
        <cms:repeatable name='ccs_gl_hdqt_pho_rpt' label='Public Phone Numbers' desc='one number per row' order='18' >    
            <cms:editable type='text' name='ccs_gl_hdqt_pho_fa_icon' label='Phone Icon' width='140' order='2' />
            <cms:editable type='text' name='ccs_gl_hdqt_pho' label='Phone Number' width='230' order='4' />
        </cms:repeatable>
        <cms:editable type='text' name='ccs_gl_hdqt_fax' label='Public Fax'  desc='' order='20' />
        
        <cms:editable type='message' name='ccs_gl_cntct_map_msg' order='24'>
            <h3>Add Map Embed or Link</h3>
            <h4></h4>
            <p>Get map icons <a href="https://fontawesome.com/v6/search" target="_bland"> here. https://fontawesome.com/v6/search</a></p>
        </cms:editable>
        <cms:repeatable name='ccs_gl_ggl_maps_rpt' label='Location Google Map Links' desc='one map per row' order='26' > 
            <cms:editable type='text' name='ccs_gl_hdqt_map_fa_icon' label='Map Icon' width='140' order='2' />
            <cms:editable type='textarea' name='ccs_gl_ggl_map_embed' label='Google Map Embed - Full url embed code' order='4' />
            <cms:editable type='text' name='ccs_gl_ggl_map_link' label='Google Map Link - Full url link'  desc='' order='6' />
        </cms:repeatable>
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_scl_grp'  label='Website Social Media Settings' order='18'>
        <cms:editable type='message' name='ccs_gl_social_msg' order='2'>
            <h3>Add Social links and Icons</h3>
            <h4>ex: Social Icon  fab fa-gitlab</h4>
            <p>see all options at here <a href="https://fontawesome.com/v6/search" target="_bland">https://fontawesome.com/v6/search</a></p>
        </cms:editable>   

        <cms:repeatable name='ccs_gl_social' label='Social Links' order='4' >
			<cms:editable name='ccs_gl_social_name' label='Name' type='text' order="2" />
			<cms:editable name='ccs_gl_social_icon' label='Icon' type='text' order="4" />
			<cms:editable name='ccs_gl_social_link' label='Social Link - use full link url' type='text'  order="6" />
			<cms:editable type='dropdown' name='ccs_gl_social_bg_clr' label='Button Background Color' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' order='8' />
			<cms:editable type='dropdown' name='ccs_gl_social_txt_clr' label='Button Icon/Text Color' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' order='10' />
		</cms:repeatable> 
            
        <cms:editable type='text' name='ccs_gl_andr_app' label='Android App Link'  desc='' order='6' />
        <cms:editable type='text' name='ccs_gl_appl_app' label='Apple App Link'  desc='' order='8' />
    </cms:editable>
    
    <cms:editable type='checkbox' name='ccs_gl_site_custom_color_opt' label='Customize Site Colors' desc='uncheck to revert to default colors'
        opt_values='Customize Colors=1'
        order='20'
    />
    <cms:editable type='checkbox' name='ccs_gl_site_custom_font_opt' label='Customize Site Fonts' desc='uncheck to revert to default fonts'
        opt_values='Customize Fonts=1'
        order='22'
    />

    <cms:func _into='ccs_gl_site_colors_custom' ccs_gl_site_custom_color_opt='' >
        <cms:if ("<cms:is '1' in=ccs_gl_site_custom_color_opt />") >
            show
            <cms:else />
            hide
        </cms:if>
    </cms:func>

    <cms:func _into='ccs_gl_site_fonts_custom' ccs_gl_site_custom_font_opt=''>
        <cms:if "<cms:is '1' in=ccs_gl_site_custom_font_opt />" >
            show
            <cms:else />
            hide
        </cms:if>
    </cms:func>
    <cms:func _into='ccs_gl_site_def_thm' ccs_gl_site_custom_color_opt=''>
        <cms:if "<cms:is '1' in=ccs_gl_site_custom_color_opt />" >
            hide
            <cms:else />
            show
        </cms:if>
    </cms:func>
    
    <cms:editable type='dropdown' name='ccs_gl_site_thm_opt' label='Set Website Color Theme'
        opt_values='Light=light|Dark=dark'
        not_active=ccs_gl_site_def_thm
        order='25'  
    />
    
    <cms:editable type='group' name='ccs_gl_site_site_cst_clr_grp' label='Custom Website Theme Colors' desc="sets HEX colors - advanced use only" not_active=ccs_gl_site_colors_custom  order='27' >
        <cms:editable type='color' name='ccs_gl_site_primary_cust'  label='Primary Color' desc='default HEX 007bff'
            color='#007bff' alpha='0' width='50%' height='100px' order='12' 
        />
        <cms:editable type='color' name='ccs_gl_site_secondary_cust'  label='Secondary Color' desc='default HEX 7968D9'
            color='#7968D9' alpha='0' width='50%' height='100px' order='14' 
        />
        <cms:editable type='color' name='ccs_gl_site_tertiary_cust'  label='Tertiary Color' desc='default HEX 687BD9'
            color='#687BD9' alpha='0' width='50%' height='100px' order='14' 
        />
        <cms:editable type='color' name='ccs_gl_site_quaternary_cust'  label='Quaternary Color' desc='default HEX 68C2D9'
            color='#68C2D9' alpha='0' width='50%' height='100px' order='14' 
        />
        <cms:editable type='color' name='ccs_gl_site_success_cust'  label='Success Color' desc='default HEX 28a745'
            color='#28a745' alpha='0' width='50%' height='100px' order='15' 
        />
        <cms:editable type='color' name='ccs_gl_site_info_cust'  label='Info Color' desc='default HEX 17a2b8'
            color='#17a2b8' alpha='0' width='50%' height='100px' order='16' 
        />
        <cms:editable type='color' name='ccs_gl_site_warning_cust'  label='Warning Color' desc='default HEX ffc107'
            color='#ffc107' alpha='0' width='50%' height='100px' order='17' 
        />
        <cms:editable type='color' name='ccs_gl_site_danger_cust'  label='Danger Color' desc='default HEX dc3545'
            color='#dc3545' alpha='0' width='50%' height='100px' order='18' 
        />
        <cms:editable type='color' name='ccs_gl_site_light_cust'  label='Light Color' desc='default HEX f5f5f5'
            color='#f5f5f5' alpha='0' width='50%' height='100px' order='19' 
        />
        <cms:editable type='color' name='ccs_gl_site_dark_cust'  label='Dark Color' desc='default HEX 17191b'
            color='#17191b' alpha='0' width='50%' height='100px' order='20' 
        />
        <cms:editable type='color' name='ccs_gl_site_white_cust'  label='White' desc='default HEX 007AFF'
            color='#007AFF' alpha='0' width='50%' height='100px' order='21' 
        />
        <cms:editable type='color' name='ccs_gl_site_black_cust'  label='Black' desc='default HEX 007AFF'
            color='#007AFF' alpha='0' width='50%' height='100px' order='22' 
        />
        <cms:editable type='color' name='ccs_gl_site_body_clr_cust'  label='Text Color' desc='main text color unless other specified. Default HEX 292b2c '
            color='#292b2c' alpha='0' width='50%' height='100px' order='23' 
        />
        <cms:editable type='color' name='ccs_gl_site_body_bg_cust'  label='Site Background' desc='main bakground color unless other specified. Default HEX fffffff'
            color='#ffffff' alpha='0' width='50%' height='100px' order='24' 
        />
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_site_cst_slct_grp' label='Colors when SELECTING / HIGHLIGHTING Text' desc="sets colors when selecting text on website" order='30' >
        <cms:editable type='message' name='ccs_gl_site_site_cst_slct_msg' order='2'>
            <h3>Choose Colors For Highlighting</h3>
            <h4>The color of the text and the color of the highlight</h4>
            <p></p>
        </cms:editable>   
        <cms:editable type='row' name='ccs_gl_site_hglt_row' order='5' >
            <cms:editable type='dropdown' name='ccs_gl_site_hglt_clr' label='Selected Text Highlighted Text Color'
                opt_values='dropdowns/theme-colors.htm'
                opt_selected='light'
                dynamic='opt_values'
                class='col-md-4'
                order='2'  
            />
            <cms:editable type='dropdown' name='ccs_gl_site_hglt_bg' label='Selected Text Hightlight Background Color'
                opt_values='dropdowns/theme-colors.htm'
                opt_selected='dark'
                dynamic='opt_values'
                class='col-md-4'
                order='4'  
            />
        </cms:editable>
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_site_fonts' label='Website Custom Type Fonts' desc="advanced use only" not_active=ccs_gl_site_fonts_custom  order='33' >
        <cms:editable type='message' name='ccs_gl_site_font_cust_dfl_msg' order='13'>
        <br><hr> 
        <h2>Default Font Style</h2>
            <h4>Sets the Custom Default Font-Family</h4>
            <p>Styling may also be changed at the element as desired.<br>See here for more information on web fonts: <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/font-family" target="_blank">https://developer.mozilla.org/en-US/docs/Web/CSS/font-family</a></p><p><b>IMPORTANT: To set fields back to original settings, clear the field and save the page.</b></p>
        </cms:editable>   

        <cms:editable type='textarea' name='ccs_gl_site_font_body_cust' label="Enter custom default font-family here:" desc="separate names with commas, and wrap multiple word titles in double-quotes" width='' height='50' no_xss_check='1' order='14' >"Montserrat", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"</cms:editable>
        
        <cms:editable type='message' name='ccs_gl_site_font_cust_xtr_msg' order='17'>
        <br><hr> 
        <h2>Extra Font Styles</h2>
            <h4>These fields set the website special font and text styles.</h4>
             <p>Styling may also be changed at the element as desired.<br>See here for more information on web fonts: <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/font-family" target="_blank">https://developer.mozilla.org/en-US/docs/Web/CSS/font-family</a></p>
        </cms:editable>   
        <cms:editable type='textarea' name='ccs_gl_site_font_base_cust' label="Enter Font-Family names for custom 'Base' setting:" desc="separate names with commas, and wrap multiple word titles in double-quotes" width='' height='50' no_xss_check='1' order='18' >"Helvetica Neue", Arial, sans-serif, "Segoe UI Symbol"</cms:editable>
        
        <cms:editable type='textarea' name='ccs_gl_site_font_sans_cust' label="Custom Sans Serif Font" desc="add your own custom font for sans serif type, or clear field and save page to return to site defaults" width='' height='50' no_xss_check='1' order='22' >"Droid Sans", "Open Sans", sans-serif</cms:editable>

        <cms:editable type='textarea' name='ccs_gl_site_font_serf_cust' label="Custom serif Font" desc="add your own custom font base for serif type, or clear field and save page to return to site defaults" width='' height='50' no_xss_check='1' order='26'  >"Playfair Display","Libre Baskerville", Georgia, serif</cms:editable>
        
        <cms:editable type='textarea' name='ccs_gl_site_font_mono_cust' label="Custom monospace Font" desc="add your own custom font base for monospace type, or clear field and save page to return to site defaults" width='' height='50' no_xss_check='1' order='30'  >"SFMono-Regular", Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace</cms:editable>
        
        <cms:editable type='textarea' name='ccs_gl_site_font_crsv_cust' label="Custom cursive Font" desc="add your own custom font base for cursive type, or clear field and save page to return to site defaults" width='' height='50' no_xss_check='1' order='34'  >"Bradley Hand", "Lucida Handwriting", "Brush Script MT", "Comic Sans MS"</cms:editable>

        <cms:editable type='textarea' name='ccs_gl_site_font_decr_cust' label="Custom decorative Font" desc="add your own custom font base for decorative type, or clear field and save page to return to site defaults" width='' height='50' no_xss_check='1' order='38'  >Sreda, Arvo, Candara</cms:editable>

        <cms:editable type='text' name='ccs_gl_site_font_size_cust' label="Custom Font Size"  desc='Measured in rem. Clear field and save page to return to site defaults' maxlength="4" width="125" order="42" >1</cms:editable>
        
        <cms:editable type='text' name='ccs_gl_site_font_weight_cust' label="Custom Font Weight"  desc='Clear field and save page to return to site defaults' maxlength="4" width="75" order="46" >400</cms:editable>
        
        <cms:editable type='text' name='ccs_gl_site_line_height_cust' label="Custom Line Height"  desc='Space between lines of text. Clear field and save page to return to site defaults' maxlength="4" width="75" order="50" >1.45</cms:editable>
    </cms:editable>
    
</cms:template>

<cms:if k_template_name="globals.php"><cms:set ccs_gl_edt_ok='1' /></cms:if>

<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />") > 
    <cms:embed 'pb_mods/pg_frame/head.htm' />
    <cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />
    <body>
        <div class="container">
            <div class="align-items-center">
                <br><hr class=''><br><hr><br>
                <h3>Global Settings</h3>
                <br><hr><br>
                
                <h4>Website Business Settings</h4>
                <cms:popup_edit 'ccs_gl_site_biz_grp|ccs_gl_site_biz_mta_msg|ccs_gl_site_biz_mta_row|ccs_gl_site_name_txt_lgo_opt|ccs_gl_site_name|ccs_gl_site_name_txt_sz|ccs_gl_site_name_txt_clr|ccs_gl_tgln_fmt_msg|ccs_gl_site_tgl_row|ccs_gl_tgln|ccs_gl_tgln_opt|ccs_gl_tgln_clr|ccs_gl_tgln_txt_sz|nca_cntnt_2col_img_br|ccs_gl_tgln_fnt_fm|ccs_gl_tgln_fnt_wt|ccs_gl_tgln_fnt_st|ccs_gl_tgln_ltr_spc' link_text="<button class='btn btn-xl btn-primary font-sans-serif' title='Color: Primary Font-Family: Default  Click to edit Website Name and Tagline'><p>Edit Site Name and Tagline</p></button>"  />
                
                <cms:popup_edit 'ccs_gl_site_biz_grp|ccs_gl_site_desc_msg|ccs_gl_site_desc' link_text="<button class='ms-2 btn btn-xl btn-success font-sans-serif' title='Color:Success Font-Family: Sans Serif  Click to edit SEO Meta data'><p>Edit SEO Site Description</p></button>"  />
				
				<cms:popup_edit 'ccs_gl_site_biz_grp|ccs_gl_site_biz_lgo_msg|ccs_gl_site_biz_lgo_row|ccs_gl_site_name_txt_lgo_opt|ccs_gl_site_logo_hoz|ccs_gl_site_logo_sqr|ccs_gl_site_favicon' link_text="<button class='ms-2 btn btn-xl btn-secondary font-base' title='Color: Secondary Font-Family: Base  Click to edit Logo'><p>Set Brand Logos</p></button>"  />
                
                <cms:editable type='row' name='ccs_gl_site_biz_lgo_row'  order='5' >
					<cms:editable type='dropdown' name='ccs_gl_site_name_txt_lgo_opt' label='Websie Logo as image or use website name' desc='options below'
						opt_values='Image=0|Site Name=1'
						class='col-md-4'
						order='6'  
					/>

					<cms:editable type='image' name='ccs_gl_site_logo_hoz' label='Primary Navbar Logo (Horizontal)' desc='Required: 400px wide by 100px tall. (4:1 ratio)'
						width='400'
						height='100'
						crop='0'
						enforce_max='1'
						show_preview='1'
						preview_width='150'
						class='col-md-6'
						order='8'
					/>

					<cms:editable type='image' name='ccs_gl_site_logo_sqr' label='Square / Mobile Logo' desc='Required: 500px by 500px. (1:1 ratio)'
						width='500'
						height='500'
						crop='0'
						enforce_max='1'
						show_preview='1'
						preview_width='75'
						class='col-md-6'
						order='9'
					/>

					<cms:editable type='image' name='ccs_gl_site_favicon' label='Favicon - may be ico, png, jpg' desc='The tiny logo that shows in browser tabs. Vector graphic recommended as favicon file'
						width='64'
						height='64'
						enforce_max='1'
						show_preview='1'
						preview_width='25'
						class='col-md-12'
						order='10'
					/>
				</cms:editable>
                
                <cms:popup_edit 'ccs_gl_site_cntc_grp|ccs_gl_hdqt_st_add|ccs_gl_hdqt_st_ad2|ccs_gl_hdqt_cty|ccs_gl_hdqt_sta|ccs_gl_hdqt_zip|ccs_gl_hdqt_ggl_lnk|ccs_gl_hdqt_email|ccs_gl_cntct_pho_msg|ccs_gl_hdqt_pho_rpt|ccs_gl_hdqt_fax|ccs_gl_cntct_map_msg|ccs_gl_ggl_maps_rpt' link_text="<hr class=''><button class='btn btn-xl btn-info font-serif' title='Color: Info  Font-Family: Serif  Click to edit Busines Main Contact Information'><p>Edit Site Contact Settings</p></button>"  />

                <cms:popup_edit 'ccs_gl_site_scl_grp|ccs_gl_social_msg|ccs_gl_social|ccs_gl_social_bg_clr|ccs_gl_social_txt_clr|ccs_gl_andr_app|ccs_gl_appl_app' link_text="<button class='ms-2 btn btn-xl btn-warning font-monospace' title='Color: Warning Font-Family: Monospace  Click to edit Website Social Links'><p>Edit Site Social Media</p></button>"  />

                <br><hr class=''><br><hr><br>
                
                <h4>Website Color and Font Settings</h4>
                <p class="">Use these sentences to see the current default settings for font-family and default text color.<b>*</b></p>
                <p>Select/highlight these words to display the SELECT colors.<b>**</b></p>
                <p>The buttons below display the current settings for the extra theme colors and font-families. Hover mouse over buttons to see more info.</p>

                <p>Current settings are <cms:if ccs_gl_site_custom_color_opt=''> site default colors (not custom) on the <cms:show ccs_gl_site_thm_opt /> theme<cms:else />custom colors</cms:if> and <cms:if ccs_gl_site_custom_font_opt=''>site default fonts<cms:else />custom fonts</cms:if>.</p> 
                
                <cms:popup_edit 'ccs_gl_site_custom_color_opt|ccs_gl_site_thm_opt|ccs_gl_site_site_cst_clr_grp|ccs_gl_site_body_clr_cust|ccs_gl_site_body_bg_cust' link_text="<button class='btn btn-xl btn-danger font-sans-serif' title='Color: Danger Font-Family: Sans-serif  Click to edit default font, text color and background color'><p><b>*</b> Site text and background color</p></button>"  />
                
                <cms:popup_edit 'ccs_gl_site_custom_font_opt|ccs_gl_site_site_fonts|ccs_gl_site_font_body_cust' link_text="<button class='ms-2 btn btn-xl btn-warning font-serif' title='Color: Warning Font-Family: Serif  Click to edit default font-family'><p><b>*</b> Site Font-Families</p></button>"  />
                <br><hr>
                
                <h5>Color Themes</h5>
                <cms:popup_edit 'ccs_gl_site_site_cst_slct_grp|ccs_gl_site_site_cst_slct_msg|ccs_gl_site_hglt_row|ccs_gl_site_hglt_clr|ccs_gl_site_hglt_bg' link_text="<button class='mx-3 btn btn-xl btn-light font-decor' title='Color: Light Font-Family: Decor  Click to edit what the highlight colors look like when selecting text'><p><b>**</b> Change SELECT / HIGHLIGHT colors</p></button>"  />
                
                <cms:popup_edit 'ccs_gl_site_custom_color_opt|ccs_gl_site_thm_opt|ccs_gl_site_site_cst_clr_grp|ccs_gl_site_primary_cust|ccs_gl_site_secondary_cust|ccs_gl_site_tertiary_cust|ccs_gl_site_quaternary_cust|ccs_gl_site_success_cust|ccs_gl_site_info_cust|ccs_gl_site_warning_cust|ccs_gl_site_danger_cust|ccs_gl_site_light_cust|ccs_gl_site_dark_cust|ccs_gl_site_white_cust|ccs_gl_site_black_cust' link_text="<button class='mx-3 btn btn-xl btn-danger font-curs' title='Color: Danger Font-Family: Cursive  Click to edit custom website theme colors'><p>Customize Theme Colors for Site</p></button>"  />
                <br><hr class=''><br><hr><br>
                
                <h4>Font Settings</h4>

                <cms:popup_edit 'ccs_gl_site_custom_font_opt|ccs_gl_site_site_fonts|ccs_gl_site_font_body_cust|ccs_gl_site_font_base_cust|ccs_gl_site_font_sans_cust|ccs_gl_site_font_mono_cust' link_text="<button class='btn btn-xl btn-black font-curs' title='Color: Black Font-Family: Cursive  Click to set extra font-family looks'><p>Customize Font-Family Theme Options </p></button>"  />
                
                <cms:popup_edit 'ccs_gl_site_custom_font_opt|ccs_gl_site_site_fonts|ccs_gl_site_font_size_cust|ccs_gl_site_font_weight_cust|ccs_gl_site_line_height_cust' link_text="<button class='my-3 mx-3 btn btn-xl btn-white font-sans-serif' title='Color: White Font-Family: Default  Click to set extra font-family looks'><p>Customize Default Font size, Weight, Line-Height Options </p></button><br><hr><br>"  />
                
                <br><hr class=''><br><hr><br>
                
                <h5>Advanced editing</h5>
                <cms:if (k_user_access_level ge '10')>
                    <a target="_blank" href="<cms:admin_link />" title="Edit using the Admin page - will open up in new tab">
                        <button class='btn btn-xs btn-outline-primary'>Edit All <cms:show k_template_title /> Settings in Admin Area</button>
                    </a>
                </cms:if>
            </div>
        </div>
        
        <cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />    
    <cms:embed 'pb_mods/pg_frame/tail.htm' />
    
<cms:else />
    <cms:redirect k_site_link />
</cms:if>
<?php COUCH::invoke(); ?>