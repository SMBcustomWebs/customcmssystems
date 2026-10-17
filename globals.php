<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Global Site Settings" parent='_global_' icon='globe' hidden="0" order="90" >

    <cms:editable type='message' name='ccs_gl_site_gen_msg' order='10' >
        <h2>Global Website Settings</h2>
        <h3>Manage site structure, global themes, branding, and metadata.</h3>
        <br><hr><br>
    </cms:editable>
    
    <cms:editable type='message' name='ccs_gl_site_nav_msg' order='20' >
        <h3>Site Architecture</h3>
        <h4>IMPORTANT: ONLY THE TOP LISTED NAV BAR AND FOOTER WILL SHOW ON THE LIVE SITE. TOP 2 UTILITY BARS</h4>
		<p>Use sorting arrows on the right of the tiles to rearrange order</p>
    </cms:editable>
    
    <cms:pagebuilder name='ccs_gl_tpb_pb' label='Optional Top Bar' skip_custom_fields='1' order='30'>
        <cms:section label='Optional Top Bar' name='ccs_gl_tpb_sect'  masterpage='blocks/frame/utlbar.php' mosaic='ccs_utlbar_msc' />
    </cms:pagebuilder>
    
    <cms:pagebuilder name='ccs_gl_nav_pb' label='Site Navigation' skip_custom_fields='1' order='40'>
        <cms:section label='Navigation Menu' name='ccs_gl_nav_sect'  masterpage='blocks/frame/navbar.php' mosaic='ccs_nav_msc' />
    </cms:pagebuilder>
    
    <cms:pagebuilder name='ccs_gl_ftr_pb' label='Site Footer' skip_custom_fields='1' order='50'>
        <cms:section label='Footer' name='ccs_gl_ftr_sect'  masterpage='blocks/frame/footer.php' mosaic='ccs_site_ftr_msc' />
    </cms:pagebuilder>


    <cms:editable type='group' name='ccs_gl_site_biz_grp' label='Branding & Identity' desc='Logo, site name, tagline, and SEO metadata' collapsed='1' order='60'>
        
        <cms:editable type='message' name='ccs_gl_site_biz_lgo_msg' order='1' >
            <h2>Upload Logos & Icons</h2>
            <p>Site Logo can either be an image or the website name.</p>
        </cms:editable>

        <cms:editable type='row' name='ccs_gl_site_biz_lgo_hoz_row' order='2' >
            <cms:editable type='image' name='ccs_gl_site_logo_hoz_drk' label='Horizontal Logo (Dark/Color)' desc='For light backgrounds. Upload at 1200x300 or larger, 4:1 ratio. Stored at up to 1200x300 so it stays sharp on high-density screens.' width='1200' height='300' crop='0' enforce_max='1' show_preview='1' preview_width='150' class='col-md-6 mb-3' order='1' />
            <cms:editable type='image' name='ccs_gl_site_logo_hoz_lgt' label='Horizontal Logo (Light/White)' desc='For dark backgrounds. Upload at 1200x300 or larger, 4:1 ratio. Stored at up to 1200x300 so it stays sharp on high-density screens.' width='1200' height='300' crop='0' enforce_max='1' show_preview='1' preview_width='150' class='col-md-6 mb-3' order='2' />
        </cms:editable>

        <cms:editable type='row' name='ccs_gl_site_biz_lgo_sqr_row' order='3' >
            <cms:editable type='image' name='ccs_gl_site_logo_sqr_drk' label='Square Logo (Dark/Color)' desc='For light backgrounds. Upload at 1000x1000 or larger, square. Stored at up to 1000x1000 so it stays sharp on high-density screens.' width='1000' height='1000' crop='0' enforce_max='1' show_preview='1' preview_width='75' class='col-md-6 mb-3' order='1' />
            <cms:editable type='image' name='ccs_gl_site_logo_sqr_lgt' label='Square Logo (Light/White)' desc='For dark backgrounds. Upload at 1000x1000 or larger, square. Stored at up to 1000x1000 so it stays sharp on high-density screens.' width='1000' height='1000' crop='0' enforce_max='1' show_preview='1' preview_width='75' class='col-md-6 mb-3' order='2' />
        </cms:editable>

        <cms:editable type='row' name='ccs_gl_site_biz_lgo_fav_row' order='4' >
            <cms:editable type='image' name='ccs_gl_site_favicon' label='Favicon - may be ico, png, jpg' desc='The tiny logo that shows in browser tabs.' width='64' height='64' enforce_max='1' show_preview='1' preview_width='25' class='col-md-12' order='1' />
        </cms:editable>

        <cms:editable type='message' name='ccs_gl_site_biz_mta_msg' order='5' >
            <br><hr><br><h2>Business Name Identification</h2>
        </cms:editable>

        <cms:editable type='row' name='ccs_gl_site_biz_mta_row'  order='6' >
            <cms:editable type='dropdown' name='ccs_gl_site_name_txt_lgo_opt' label='Default Website Brand Display' desc='Choose global default' opt_values='Image=0|Site Name=1' class='col-md-12 mb-3' order='1' />
            <cms:editable type='text' name='ccs_gl_site_name' label='Name of Website' class='col-md-6' order='2' />
            
            <cms:func _into='ccs_gl_site_name_txt_lgo_chc' ccs_gl_site_name_txt_lgo_opt='' >
                <cms:if ccs_gl_site_name_txt_lgo_opt='1'>show<cms:else />hide</cms:if>
            </cms:func>
            
            <cms:ignore>
                not_active REMOVED from these two, and it could not have been
                made correct by rewriting the condition.

                It read ccs_gl_site_name_txt_lgo_chc, which shows these only when
                the GLOBAL Default Website Brand Display is Site Name. But every
                navbar carries its own Brand Display Style and can choose Site
                Name independently of this setting. The ordinary case - global
                left on Image, one navbar switched to Site Name - hid both
                fields, so the company name rendered with an empty size and an
                orphan "text-" prefix, and there was no way in the panel to reach
                them and fix it.

                A better condition is not available. not_active compiles to
                JavaScript bound to fields on the SAME template, and the field
                that would have to drive it lives on a navbar tile in another
                template entirely. So these are always visible now. They are the
                site-wide fallback and any navbar may be relying on them.

                A NOTE FOR ANYONE TEMPTED BY opt_selected HERE. It will not do
                what it looks like it does. field.php:997 consults get_data()
                first and falls back to opt_selected only to decide which option
                to mark selected while DRAWING the select - it is admin-panel
                only, never stored, and the front end still reads the field
                itself, which is still empty. The panel shows a color chosen and
                the page renders without one. To give a field a real starting
                value, put it in the BODY of the editable: get_data() returns
                default_data whenever data is empty, on the front end and in the
                panel alike.

                PER-NAVBAR OVERRIDES now live on each nav tile as
                ccs_nav_{mdd,sdd,xtr}_name_sz / _name_clr and win when set.

                ccs_gl_site_name_txt_lgo_chc, declared just above, no longer has
                a consumer. Left in place rather than deleted - it is inert, and
                removing a func is a schema change for no gain.
            </cms:ignore>
            <cms:ignore>
                heading-size_sec.htm, NOT heading-size.htm, and this is the fix
                for the color bug rather than a tidy-up.

                heading-size.htm stores h1 through h6. Those are not size classes
                in this theme - user.php:452 gives .h1 through .h6 a color and a
                font-family as well - so choosing a SIZE here silently overrode
                the COLOR field sitting immediately beside it. Only .text-tertiary
                and .text-quaternary survived it, because theme.css:25774 is the
                only place the color utilities carry !important.

                heading-size_sec.htm stores 5 through 9, consumed as fs-N, and
                .fs-N sets font-size and nothing else. It is the same list the
                per-navbar override already uses, so both ends of the fallback now
                speak one convention.

                A STORED h3 WILL NOT MATCH this list, so the field reads blank
                after the change and the size wants picking once. That is the cost
                of the fix and it is one click.
            </cms:ignore>
            <cms:editable type='dropdown' name='ccs_gl_site_name_txt_sz' label='Displayed Text Size' desc='Site-wide default. Each navbar can override it.' opt_values='dropdowns/heading-size_sec.htm' dynamic='opt_values' class='col-md-3' order='3' />
            <cms:editable type='dropdown' name='ccs_gl_site_name_txt_clr' label='Displayed Text Color' desc='Site-wide default. Each navbar can override it.' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' class='col-md-3' order='4' />
        </cms:editable>

        <cms:editable type='message' name='ccs_gl_tgln_fmt_msg' order='7' ><br><hr><h2>Tagline Configuration</h2></cms:editable>

        <cms:ignore>
            COLUMN WIDTHS ON THE FIRST FOUR FIELDS: 6 + 2 + 2 + 2 = 12.

            They were 7 + 3 + 3 + 3 = 16. Bootstrap wraps at 12, so the tagline
            and its checkbox took the first line at 10 of 12, and Color and
            Size were pushed onto a second line that then sat two thirds empty -
            which is the ragged gap you could see in the panel.

            The four typography fields below are 3 + 3 + 3 + 3 and were always
            correct; only this first group was over.

            Any row's children must total 12 or a multiple of it. The
            col-md-12 message in the middle is the deliberate exception: it
            forces the line break between the two halves.
        </cms:ignore>
        <cms:editable type='row' name='ccs_gl_site_tgl_row'  order='8' >
            <cms:editable type='text' name='ccs_gl_tgln' label='Business Tagline' class='col-md-6' order='1' />
            <cms:editable type='checkbox' name='ccs_gl_tgln_opt' label='Show Tagline under logo?' opt_values='Show Tagline=1'  class='col-md-2' order='2' />
            <cms:editable type='dropdown' name='ccs_gl_tgln_clr' label='Tagline Text Color' opt_values='dropdowns/solid-colors.htm'  dynamic='opt_values' class='col-md-2' order='3' />
            <cms:editable type='dropdown' name='ccs_gl_tgln_txt_sz' label='Tagline Text Size' opt_values='dropdowns/text-size_sml.htm' dynamic='opt_values' class='col-md-2' order='4' />
            <cms:editable type='message' name='nca_cntnt_2col_img_br' class='col-md-12'  order='5' ><hr></cms:editable>            
            <cms:editable type='dropdown' name='ccs_gl_tgln_fnt_fm' label='Tagline Font Family' opt_values='dropdowns/font-family.htm' dynamic='opt_values' class='col-md-3' order='6' />
            <cms:editable type='dropdown' name='ccs_gl_tgln_fnt_wt' label='Tagline Font Weight' opt_values='dropdowns/font-weight.htm' dynamic='opt_values' class='col-md-3' order='7' />
            <cms:editable type='dropdown' name='ccs_gl_tgln_fnt_st' label='Tagline Font Style' opt_values='dropdowns/font-style.htm' dynamic='opt_values' class='col-md-3' order='8' />
            <cms:editable type='dropdown' name='ccs_gl_tgln_ltr_spc' label='Tagline Letter Spacing' opt_values='dropdowns/hdr_ltr-spc.htm' dynamic='opt_values' class='col-md-3' order='9' />
        </cms:editable>

        <cms:editable type='message' name='ccs_gl_site_desc_msg' order='9' ><br><hr><h2>Website Metadata</h2></cms:editable>
        <cms:ignore>
            class REMOVED. This carried col-md-12 while its parent is
            ccs_gl_site_biz_grp - a GROUP, not a row. It was the only col-* in
            this file whose parent was not a row.

            A Bootstrap column outside a flex row still takes the column's
            padding and float behavior but has no row to lay out inside, and
            this field sits at the very boundary where the next group's header
            begins - which is where the Master Theme Controls banner was
            overlapping the field above it.

            A group child with no class already renders full width, which is
            what col-md-12 was asking for, so nothing is lost by dropping it.
            The message directly above has no class either, so the two now
            match.
        </cms:ignore>
        <cms:editable type='textarea' name='ccs_gl_site_desc' label='Website Description' desc='SEO meta description.' order='10' />
    </cms:editable>


    <cms:editable type='group' name='ccs_gl_master_theme_grp' label='Master Theme Controls' desc='Toggle active themes, custom overrides, and global typography' order='70'>
        
        <cms:editable type='message' name='ccs_gl_theme_custom_msg' order='1'>
            <h2>Theme & Custom Overrides</h2>
            <p>Check the boxes below to unlock total manual control over colors and fonts. Leave unchecked to select a curated base theme.</p>
        </cms:editable>

        <cms:editable type='checkbox' name='ccs_gl_site_custom_color_opt' label='Customize Site Colors' opt_values='Customize Colors=1' order='2' />
        <cms:editable type='checkbox' name='ccs_gl_site_custom_font_opt' label='Customize Site Fonts' opt_values='Customize Fonts=1' order='3' />

        <cms:func _into='ccs_gl_hide_theme_dropdown' ccs_gl_site_custom_color_opt=''>
            <cms:if "<cms:is '1' in=ccs_gl_site_custom_color_opt />">hide<cms:else />show</cms:if>
        </cms:func>

        <cms:func _into='ccs_gl_site_colors_custom' ccs_gl_site_custom_color_opt='' ><cms:if "<cms:is '1' in=ccs_gl_site_custom_color_opt />" >show<cms:else />hide</cms:if></cms:func>
        <cms:func _into='ccs_gl_site_fonts_custom' ccs_gl_site_custom_font_opt=''><cms:if "<cms:is '1' in=ccs_gl_site_custom_font_opt />" >show<cms:else />hide</cms:if></cms:func>

        <cms:editable type='message' name='ccs_gl_theme_master_msg' order='4' not_active=ccs_gl_hide_theme_dropdown>
            <br><hr><h2>Curated Theme Engine</h2>
            <p>Select your base theme, then toggle typography or background settings below.</p>
        </cms:editable>

        <cms:editable type='dropdown' name='ccs_gl_site_thm_opt' label='Set Website Theme Base' opt_values='Light=light|Dark=dark|Primavera=primavera|Estate=estate|Autunno=autunno|Inverno=inverno|Scuro (Dark)=scuro|Notte (Dark)=notte' not_active=ccs_gl_hide_theme_dropdown order='5' />

        <cms:func _into='ccs_gl_site_thm_clr_cond' ccs_gl_site_custom_color_opt='' ccs_gl_site_thm_opt=''>
            <cms:if ("<cms:is '1' in=ccs_gl_site_custom_color_opt />") || (ccs_gl_site_thm_opt == 'light') || (ccs_gl_site_thm_opt == 'dark') >hide<cms:else />show</cms:if>
        </cms:func>
        
        <cms:func _into='ccs_gl_site_thm_typo_cond' ccs_gl_site_custom_font_opt='' ccs_gl_site_thm_opt=''>
            <cms:if ("<cms:is '1' in=ccs_gl_site_custom_font_opt />") || (ccs_gl_site_thm_opt == 'light') || (ccs_gl_site_thm_opt == 'dark') >hide<cms:else />show</cms:if>
        </cms:func>

        <cms:editable type='checkbox' name='ccs_gl_site_thm_clr_opt' label='Disable Theme Background/Text Colors' desc='Check this box to revert to basic white/dark.' opt_values='Do Not Apply Theme Bg/Text=1' not_active=ccs_gl_site_thm_clr_cond order='6' />
        <cms:ignore>
            SITE TEXT SIZE SCALES THE WHOLE SYSTEM, NOT JUST TYPE.

            This is emitted by user.php as a percentage on the ROOT element:

                html { font-size: 100%; }

            Nearly everything in theme.css is rem - 2477 declarations, and only
            104 of them are font-size. The rest is padding, margin and gap. So a
            step up here makes the site roomier as well as larger-lettered,
            which is what a text-size control should do.

            A PERCENTAGE, NOT PIXELS, DELIBERATELY. 100% means "whatever this
            visitor set as their browser default". Someone who raised theirs to
            20px for eyesight reasons still gets 20px here, and Comfortable
            gives them 22.5px. A pixel value on html would override that and
            take the choice away from them.

            IT STOPS AT 112.5% ON PURPOSE. All 81 media queries in theme.css are
            in px, and the container max-widths are hardcoded (540/720/960/1140).
            Neither follows the root size. At 125% the navbar - navbar-expand-lg,
            so horizontal down to 992px - would be a quarter wider inside a
            container that had not grown, and would run out of room before the
            hamburger takes over. 112.5% fits in the slack the layout already
            has. Adding larger steps means converting those breakpoints to em
            and the containers to rem first.

            Composes with the body size in Custom Typography: this sets the
            root, that sets body relative to the root.
        </cms:ignore>
        <cms:editable type='dropdown' name='ccs_gl_site_text_scale' label='Site Text Size'
            desc='Scales all text and spacing together. Respects the visitor&apos;s own browser font setting.'
            opt_values='dropdowns/text-scale.htm' dynamic='opt_values' order='8' >100</cms:editable>

        <cms:editable type='checkbox' name='ccs_gl_site_thm_typo_opt' label='Apply Theme Typography' desc='Overrides defaults with curated fonts matching the theme.' opt_values='Apply Theme Typography=1' not_active=ccs_gl_site_thm_typo_cond order='7' />
        
    </cms:editable>


    <cms:ignore>
        THESE color= VALUES ARE SEED DEFAULTS, NOT LIVE THEME COLORS.

        color= on a type='color' editable is a static literal. The picker addon
        uses it only while the field has never been saved -
        addons/color-picker/color-picker.php line 67:

            $data = strlen( $this->data ) ? $this->data : $this->color;

        So it cannot follow ccs_gl_site_thm_opt, and interpolating the theme
        color here would not help either: the first save of this page writes a
        value into every one of these fields, and from then on the default is
        never consulted again. Seeding from a theme needs an explicit action
        that WRITES the fields, not a smarter default.

        WHAT THEY WERE. Bootstrap 4's stock palette, which is not this site's.
        White and Black were both #007AFF - the primary color, pasted into the
        wrong two rows - so ticking Customize Colors turned white and black
        blue. They now match the default palette in assets/css/user.php, the
        one that renders with Customize Site Colors unchecked, so ticking the
        box starts from what was already on screen.

        KEEP IN STEP with the <cms:else /> branch of the color chain in
        assets/css/user.php (approx lines 55-68 and 143-155).
    </cms:ignore>
    <cms:editable type='group' name='ccs_gl_site_site_cst_clr_grp' label='Custom Website Theme Colors' not_active=ccs_gl_site_colors_custom order='80' >
        <cms:editable type='color' name='ccs_gl_site_primary_cust' label='Primary Color' color='#007AFF' alpha='0' width='50%' height='100px' order='1' />
        <cms:editable type='color' name='ccs_gl_site_secondary_cust' label='Secondary Color' color='#292b2c' alpha='0' width='50%' height='100px' order='2' />
        <cms:editable type='color' name='ccs_gl_site_tertiary_cust' label='Tertiary Color' color='#687BD9' alpha='0' width='50%' height='100px' order='3' />
        <cms:editable type='color' name='ccs_gl_site_quaternary_cust' label='Quaternary Color' color='#68C2D9' alpha='0' width='50%' height='100px' order='4' />
        <cms:editable type='color' name='ccs_gl_site_success_cust' label='Success Color' color='#4CD964' alpha='0' width='50%' height='100px' order='5' />
        <cms:editable type='color' name='ccs_gl_site_info_cust' label='Info Color' color='#2EB7F5' alpha='0' width='50%' height='100px' order='6' />
        <cms:editable type='color' name='ccs_gl_site_warning_cust' label='Warning Color' color='#FF9500' alpha='0' width='50%' height='100px' order='7' />
        <cms:editable type='color' name='ccs_gl_site_danger_cust' label='Danger Color' color='#FF3B30' alpha='0' width='50%' height='100px' order='8' />
        <cms:editable type='color' name='ccs_gl_site_light_cust' label='Light Color' color='#fafafa' alpha='0' width='50%' height='100px' order='9' />
        <cms:editable type='color' name='ccs_gl_site_dark_cust' label='Dark Color' color='#0c151a' alpha='0' width='50%' height='100px' order='10' />
        <cms:editable type='color' name='ccs_gl_site_white_cust' label='White' color='#ffffff' alpha='0' width='50%' height='100px' order='11' />
        <cms:editable type='color' name='ccs_gl_site_black_cust' label='Black' color='#000000' alpha='0' width='50%' height='100px' order='12' />
        <cms:editable type='color' name='ccs_gl_site_body_clr_cust' label='Text Color' color='#292b2c' alpha='0' width='50%' height='100px' order='13' />
        <cms:editable type='color' name='ccs_gl_site_body_bg_cust' label='Site Background' color='#ffffff' alpha='0' width='50%' height='100px' order='14' />
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_site_cst_slct_grp' label='Text Selection Colors' order='90' >
        <cms:editable type='message' name='ccs_gl_site_site_cst_slct_msg' order='1'><h3>Choose Colors For Highlighting</h3></cms:editable>   
        <cms:editable type='row' name='ccs_gl_site_hglt_row' order='2' >
            <cms:editable type='dropdown' name='ccs_gl_site_hglt_clr' label='Highlighted Text Color' opt_values='dropdowns/theme-colors.htm' dynamic='opt_values' class='col-md-4' order='1' />
            <cms:editable type='dropdown' name='ccs_gl_site_hglt_bg' label='Highlight Background Color' opt_values='dropdowns/theme-colors.htm' dynamic='opt_values' class='col-md-4' order='2' />
        </cms:editable>
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_site_fonts' label='Custom Typograpy' not_active=ccs_gl_site_fonts_custom order='100' >
        <cms:editable type='message' name='ccs_gl_site_font_cust_dfl_msg' order='1'><h2>Default Font Style</h2></cms:editable>   
        <cms:editable type='dropdown' name='ccs_gl_site_font_body_cust' label="Custom default font-family" opt_values='dropdowns/font-sans.htm' dynamic='opt_values' order='2' />
        
        <cms:editable type='message' name='ccs_gl_site_font_cust_xtr_msg' order='3'><br><hr><h2>Extra Font Styles</h2></cms:editable>   
        <cms:editable type='dropdown' name='ccs_gl_site_font_sans_cust' label="Custom Sans Serif Font" opt_values='dropdowns/font-sans.htm' dynamic='opt_values' order='4' />
        <cms:editable type='dropdown' name='ccs_gl_site_font_serf_cust' label="Custom Serif Font" opt_values='dropdowns/font-serif.htm' dynamic='opt_values' order='5' />
        <cms:editable type='dropdown' name='ccs_gl_site_font_mono_cust' label="Custom Monospace Font" opt_values='dropdowns/font-mono.htm' dynamic='opt_values' order='6' />
        <cms:editable type='dropdown' name='ccs_gl_site_font_crsv_cust' label="Custom Cursive Font" opt_values='dropdowns/font-script.htm' dynamic='opt_values' order='7' />
        <cms:editable type='dropdown' name='ccs_gl_site_font_decr_cust' label="Custom Decorative Font" opt_values='dropdowns/font-decor.htm' dynamic='opt_values' order='8' />
        
        <cms:editable type='message' name='ccs_gl_site_font_cust_spc_msg' order='9'><br><hr><h2>Spacing & Sizing</h2></cms:editable>  
        <cms:editable type='dropdown' name='ccs_gl_site_font_size_cust' label="Custom Font Size" opt_values='dropdowns/ccs-bdy-fnt-size.htm' dynamic='opt_values' order="10" />
        <cms:editable type='dropdown' name='ccs_gl_site_font_weight_cust' label="Custom Font Weight" opt_values='dropdowns/ccs-bdy-fnt-wght.htm' dynamic='opt_values' order="11" />
        <cms:editable type='dropdown' name='ccs_gl_site_line_height_cust' label="Custom Line Height" opt_values='dropdowns/ccs-bdy-fnt-line.htm' dynamic='opt_values' order="12" />
        <cms:editable type='dropdown' name='ccs_gl_site_letter_space_cust' label="Custom Letter Spacing" opt_values='dropdowns/ccs-bdy-fnt-spce.htm' dynamic='opt_values' order="13" />
    </cms:editable>


    <cms:editable type='group' name='ccs_gl_site_nav_mnu_grp' label='Navigation Menu Settings' collapsed='1' order='110'>
        <cms:editable type='message' name='ccs_gl_site_nav_mnu_dd_msg' order='1' ><h2>Settings for Hovering and Dropdown Menus</h2></cms:editable>
                <cms:ignore>
            col-md-4 ADDED to all six. They had no class at all, inside a row -
            the only row in this file whose children carried none - so six
            controls that were meant to sit three across had no width to lay out
            with. Six at col-md-4 is 24, which wraps cleanly into two lines of
            three.
        </cms:ignore>
<cms:editable type='row' name='ccs_gl_site_nav_mnu_dd_row'  order='2' >
            <cms:editable type='dropdown' name='ccs_gl_site_nav_actv_clr' label='Menu Item Color on Active Page' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' class='col-md-4' order='1' />
            <cms:ignore>
                RENAMED. This is not just the menu. assets/css/user.php feeds it
                into TWO places: the nav's own hover rule, and
                --ccs-link-hover-color, which is the hover color for every
                ordinary link on the site. Called "Menu Text Color" it looked
                like a nav-only setting, and someone changing it for the menu
                was quietly changing links everywhere.

                The footer has its own control now, below, because it needs to
                sit on its own background.
            </cms:ignore>
            <cms:editable type='dropdown' name='ccs_gl_site_nav_hvr_clr' label='Link Color on Mouse Hover (menu and site-wide)'
                desc='Applies to the navigation menu AND to ordinary links across the site. The footer is set separately.'
                opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' class='col-md-4' order='2' />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_dd_txt_clr' label='Dropdown Menu Text Color' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' class='col-md-4' order='3' />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_dd_bg' label='Dropdown Menu Background Color' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' class='col-md-4' order='4' />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_dd_hvr_clr' label='Dropdown Menu Text Color on Mouse Hover' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' class='col-md-4' order='5' />
            <cms:editable type='dropdown' name='ccs_gl_site_nav_dd_hvr_bg' label='Dropdown Menu Background Color on Mouse Hover' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' class='col-md-4' order='6' />
        </cms:editable>
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_cntc_grp' label='Contact Settings' collapsed='1' order='120'>
        <cms:editable type='text' name='ccs_gl_hdqt_st_add' label='Headquarter Street Address' order='1' />
        <cms:editable type='text' name='ccs_gl_hdqt_st_ad2' label='Headquarter Suite' order='2' />
        <cms:editable type='text' name='ccs_gl_hdqt_cty' label='Headquarter City' order='3' />
        <cms:editable type='text' name='ccs_gl_hdqt_sta' label='Headquarter State' order='4' />
        <cms:editable type='text' name='ccs_gl_hdqt_zip' label='Headquarter Zip' order='5' />
        <cms:editable type='text' name='ccs_gl_hdqt_ggl_lnk' label='Google Map Link' order='6' />
        <cms:editable type='text' name='ccs_gl_hdqt_email' label='Public Email' order='7' />

        <cms:editable type='message' name='ccs_gl_cntct_pho_msg' order='8'><h3>Add Phone Numbers</h3></cms:editable>
        <cms:repeatable name='ccs_gl_hdqt_pho_rpt' label='Public Phone Numbers' order='9' >   
            <cms:editable type='text' name='ccs_gl_hdqt_pho_fa_icon' label='Phone Icon' width='140' order='1' />
            <cms:editable type='text' name='ccs_gl_hdqt_pho' label='Phone Number' width='230' order='2' />
        </cms:repeatable>
        
        <cms:editable type='text' name='ccs_gl_hdqt_fax' label='Public Fax' order='10' />
        
        <cms:editable type='message' name='ccs_gl_cntct_map_msg' order='11'><h3>Add Map Embed or Link</h3></cms:editable>
        <cms:repeatable name='ccs_gl_ggl_maps_rpt' label='Location Google Map Links' order='12' > 
            <cms:editable type='text' name='ccs_gl_hdqt_map_fa_icon' label='Map Icon' width='140' order='1' />
            <cms:editable type='textarea' name='ccs_gl_ggl_map_embed' label='Google Map Embed' order='2' />
            <cms:editable type='text' name='ccs_gl_ggl_map_link' label='Google Map Link' order='3' />
        </cms:repeatable>
    </cms:editable>
    
    <cms:editable type='group' name='ccs_gl_site_scl_grp' label='Social Media Settings' collapsed='1' order='130'>
        <cms:editable type='message' name='ccs_gl_social_msg' order='1'><h3>Add Social links and Icons</h3></cms:editable>   
        <cms:repeatable name='ccs_gl_social' label='Social Links' order='2' >
            <cms:editable name='ccs_gl_social_name' label='Name' type='text' order="1" />
            <cms:editable name='ccs_gl_social_icon' label='Icon' type='text' order="2" />
            <cms:editable name='ccs_gl_social_link' label='Social Link' type='text'  order="3" />
            <cms:editable type='dropdown' name='ccs_gl_social_bg_clr' label='Button Background Color' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' order='4' />
            <cms:editable type='dropdown' name='ccs_gl_social_txt_clr' label='Button Icon/Text Color' opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' order='5' />
        </cms:repeatable> 
        <cms:editable type='text' name='ccs_gl_andr_app' label='Android App Link' order='3' />
        <cms:editable type='text' name='ccs_gl_appl_app' label='Apple App Link' order='4' />
    </cms:editable>
    

    <cms:editable type='group' name='ccs_gl_integrations_grp' label='Third-Party Scripts & Routing' collapsed='1' order='140'>
        <cms:editable type='text' name='ccs_gl_form_email' label='Primary Form Routing Email' desc='Where should contact forms send notifications?' order='1' />
        <cms:editable type='textarea' name='ccs_gl_header_scripts' label='Header Scripts (Google Analytics, Pixel)' desc='DO NOT include <script> tags, just the code inside.' no_xss_check='1' order='2' />
        <cms:editable type='textarea' name='ccs_gl_footer_scripts' label='Footer Scripts (Live Chat, HubSpot)' no_xss_check='1' order='3' />
    </cms:editable>

    <cms:editable type='group' name='ccs_gl_site_ftr_grp' label='Footer &amp; Utility Bar Links' desc='Hover colors for links in the footer and the top utility bar' collapsed='1' order='145'>

        <cms:editable type='message' name='ccs_gl_site_ftr_msg' order='1'>
            <h2>Link Hover Colors</h2>
            <h4>The footer and the utility bar sit on their own background colors,
                usually darker than the page, so they need their own hover color
                rather than inheriting the site-wide one.</h4>
            <h4><strong>Leave either blank</strong> to fall back to the site-wide
                setting under Navigation Menu Settings.</h4>
            <p>Pick something that contrasts with that bar's background. A hover
               color close to the background makes the words vanish under the
               pointer, which is what these settings exist to prevent.</p>
        </cms:editable>

        <cms:editable type='row' name='ccs_gl_site_ftr_row' order='2'>
            <cms:editable type='dropdown' name='ccs_gl_site_ftr_lnk_hvr_clr' label='Footer Link Color on Mouse Hover'
                desc='Applies to every link in the footer, including the copyright line.'
                opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' class='col-md-6' order='1' />
            <cms:editable type='dropdown' name='ccs_gl_site_ubr_lnk_hvr_clr' label='Utility Bar Link Color on Mouse Hover'
                desc='The bar above the header, with the cart and account icons.'
                opt_values='dropdowns/solid-colors.htm' dynamic='opt_values' class='col-md-6' order='2' />
        </cms:editable>
    </cms:editable>

    <cms:editable type='group' name='ccs_gl_legal_grp' label='Privacy & Cookies' collapsed='1' order='150'>

        <cms:editable type='message' name='ccs_gl_legal_msg' order='1'>
            <h2>Cookie Consent</h2>
            <h4>Everything the consent bar says is editable here. All of it arrives
                already filled in with working wording, so the bar is usable the
                moment you switch it on.</h4>
            <h4>Switching the bar OFF does not turn tracking on. With no bar there
                is no way to consent, so the gated script slots below stay empty.
                Off means ask nobody and load nothing.</h4>
        </cms:editable>

        <cms:editable type='row' name='ccs_gl_legal_ctl_row' order='5'>
            <cms:editable type='checkbox' name='ccs_gl_cookie_consent' label='Show the Cookie Consent Bar'
                opt_values='Yes=1' class='col-md-6' order='1' />
            <cms:ignore>
                THE VERSION IS THE RE-ASK SWITCH, and it is the one field here
                with a consequence beyond wording.

                Consent is given to a specific policy, not to the idea of
                cookies. utils/consent.htm stores this number in the visitor's
                cookie and compares it on every request. Change it - to 2, or to
                anything different - and every stored answer stops matching, so
                everyone is asked again.

                Raise it whenever the policy changes in a way someone might have
                answered differently about: a new processor, a new category, a
                new purpose. Leaving it alone after such a change means carrying
                on under consent that was given to something else.
            </cms:ignore>
            <cms:editable type='text' name='ccs_gl_consent_ver' label='Consent Policy Version'
                desc='Change this to ask every visitor again. See the note in the code before you do.'
                required='1' class='col-md-6' order='2'>1</cms:editable>
        </cms:editable>

        <cms:editable type='message' name='ccs_gl_legal_wording_msg' order='10'>
            <hr><h2>What the bar says</h2>
        </cms:editable>

        <cms:editable type='row' name='ccs_gl_legal_txt_row' order='15'>
            <cms:editable type='text' name='ccs_gl_consent_ttl' label='Heading'
                required='1' class='col-md-4' order='1'>Your privacy</cms:editable>
            <cms:editable type='textarea' name='ccs_gl_consent_txt' label='Explanation'
                desc='Plain language. Say what you actually set and why.'
                required='1' class='col-md-8' order='2'>We use cookies that are needed to run this site and keep your cart working. With your permission we would also like to use cookies that help us understand how the site is used and let us show embedded videos. Your cart and checkout work either way.</cms:editable>
        </cms:editable>

        <cms:ignore>
            THE TWO ANSWER BUTTONS ARE STYLED IDENTICALLY IN THE THEME and that
            is not a detail to undo here by wording one of them softly. Refusing
            has to be exactly as easy as accepting - same prominence, same number
            of clicks. A decline that is quieter or buried a level deeper is the
            specific pattern regulators have issued fines over.
        </cms:ignore>
        <cms:editable type='row' name='ccs_gl_legal_btn_row' order='20'>
            <cms:editable type='text' name='ccs_gl_consent_no' label='Decline Button'
                required='1' class='col-md-4' order='1'>Reject all</cms:editable>
            <cms:editable type='text' name='ccs_gl_consent_pref' label='Choose Button'
                required='1' class='col-md-4' order='2'>Choose</cms:editable>
            <cms:editable type='text' name='ccs_gl_consent_yes' label='Accept Button'
                required='1' class='col-md-4' order='3'>Accept all</cms:editable>
        </cms:editable>

        <cms:editable type='message' name='ccs_gl_legal_cat_msg' order='25'>
            <hr><h2>Category descriptions</h2>
            <h4>Shown when a visitor opens Choose. Strictly Necessary is described
                in the theme and is always on - sign-in, the cart, and remembering
                this choice.</h4>
        </cms:editable>

        <cms:editable type='row' name='ccs_gl_legal_cat_row' order='30'>
            <cms:editable type='text' name='ccs_gl_consent_a_txt' label='Analytics'
                required='1' class='col-md-4' order='1'>Helps us see which pages are used, in aggregate.</cms:editable>
            <cms:editable type='text' name='ccs_gl_consent_m_txt' label='Marketing'
                required='1' class='col-md-4' order='2'>Lets us measure advertising and show relevant offers.</cms:editable>
            <cms:editable type='text' name='ccs_gl_consent_e_txt' label='Embedded media'
                desc='Videos and similar content served by another company. Naming that company is what makes the choice informed - a generic description is the thing regulators object to.'
                required='1' class='col-md-4' order='3'>Lets us play videos hosted by YouTube and Vimeo, which set their own cookies.</cms:editable>
        </cms:editable>

        <cms:editable type='message' name='ccs_gl_legal_lnk_msg' order='35'>
            <hr><h2>Legal pages</h2>
            <h4>The bar links to whichever of these you fill in. An empty one is
                simply not linked, so nothing breaks while a page is still being
                written.</h4>
        </cms:editable>

        <cms:ignore>
            THESE THREE FILL THEMSELVES IN, and the mechanism is worth knowing
            because it is not obvious that it works.

            The body of a cms:editable is not stored as literal text. tags.php
            line 1514 walks the node's children and calls get_HTML() on each,
            accumulating the RESULT, and line 1559 stores that as default_data.
            So a cms:link in the body is resolved at registration time and the
            finished URL is what gets saved. get_data() then returns it whenever
            the field is empty (field.php:363), on the front end and in the panel
            alike - so the author sees a real, working link sitting in the box on
            their first visit, and can overwrite it with anything they like.

            ORDER OF REGISTRATION MATTERS, once. cms:link returns NOTHING for a
            template that has no row in the templates table (tags.php:1029) - the
            same trap that made the Logout button emit an empty href. So the
            three legal templates must be visited by a super admin BEFORE this
            file is re-registered, or these defaults bake in as empty.

            If that happens there is no damage and no mystery: fill them by hand,
            or visit the legal pages and re-register globals. Nothing downstream
            breaks on an empty link - the consent bar simply omits the link
            rather than printing a dead one.
        </cms:ignore>
        <cms:editable type='row' name='ccs_gl_legal_lnk_row' order='40'>
            <cms:editable type='text' name='ccs_gl_privacy_link' label='Privacy Policy Link'
                desc='Pre-filled from your Privacy Policy page. Overwrite it to point somewhere else.'
                class='col-md-4' order='1'><cms:link masterpage='legal/privacy.php' /></cms:editable>
            <cms:editable type='text' name='ccs_gl_cookie_link' label='Cookie Policy Link'
                desc='Pre-filled from your Cookie Policy page.'
                class='col-md-4' order='2'><cms:link masterpage='legal/cookies.php' /></cms:editable>
            <cms:editable type='text' name='ccs_gl_terms_link' label='Terms of Service Link'
                desc='Pre-filled from your Terms page.'
                class='col-md-4' order='3'><cms:link masterpage='legal/terms.php' /></cms:editable>
        </cms:editable>

    </cms:editable>

    <cms:editable type='group' name='ccs_gl_store_grp' label='Store Appearance' desc='Panels and text on the cart, checkout, product and order pages' collapsed='1' order='155'>

        <cms:ignore>
            THE MASTER SWITCH FOR THE SHOP FRONT-END.

            This framework is forked per site, and most forks never sell
            anything. Off, tail.htm does not load assets/js/ccs_shop.js at all -
            no cart, no wishlist, nothing downloaded or parsed. On, it does.

            It gates the SCRIPT only. Templates, snippets and the cart addon are
            unaffected, so switching it off does not break an existing store's
            data - it just stops the interactive layer being served. Turn it on
            for any site with a cart, a wishlist, or the shop utility bar.
        </cms:ignore>
        <cms:editable type='checkbox' name='ccs_gl_shop_on' label='This site sells things'
            desc='Loads the cart and wishlist JavaScript. Leave off on a brochure site so visitors never download it.'
            opt_values='Enable the shop front-end=1' order='0' />

        <cms:editable type='message' name='ccs_gl_store_msg' order='1'>
            <h2>Store Appearance</h2>
            <h4>Controls the panels on the cart, checkout, order summary and receipt
                pages. One setting covers all of them, so they cannot drift apart.</h4>
            <h4>There is deliberately no color picker here, and no list of theme
                colors. A store panel has to stay readable no matter which theme
                preset or custom palette the site is running, and the only surfaces
                that can promise that are a light one, a dark one, or none at all.
                Everything on the panel takes its text color from the panel itself,
                so the two can never disagree.</h4>
            <h4>Prices and totals are emphasised with weight, not color, for the
                same reason.</h4>
        </cms:editable>

        <cms:ignore>
            THESE TWO NEEDED A type='row' AROUND THEM.

            col-md-6 is a Bootstrap grid class and does nothing without a .row
            parent. Sitting loose in the group, the two controls had no grid to
            lay out in - they collapsed onto one line and the next group's header
            drew straight over them. Every other side-by-side pair in this file
            is wrapped; these two were not.

            The reverse mistake is a few hundred lines up in
            ccs_gl_site_nav_mnu_dd_row: fields inside a row with no col class at
            all. Both shapes break, in opposite directions.
        </cms:ignore>
        <cms:editable type='row' name='ccs_gl_store_row' order='5' >

            <cms:editable type='dropdown' name='ccs_gl_store_panel' label='Panel Surface'
                desc='Background for cart, checkout and order-summary panels. Text color comes with it.'
                opt_values='_tok/color-surface.htm' dynamic='opt_values'
                class='col-md-6 mb-3' order='1' />

            <cms:editable type='dropdown' name='ccs_gl_store_secondary' label='Secondary Text'
                desc='Helper lines such as "We will send your receipt here". Dims the panel text rather than replacing it, so it stays readable on either surface.'
                opt_values='Same as panel text - most readable=| Slightly dimmed=opacity-85 | Dimmed=opacity-75'
                class='col-md-6 mb-3' order='2' />

        </cms:editable>

    </cms:editable>

    <cms:editable type='group' name='ccs_gl_ordml_grp' label='Order Emails' desc='Addresses and wording for the receipt sent to customers' collapsed='1' order='157'>

        <cms:editable type='message' name='ccs_gl_ordml_msg' order='1'>
            <h2>Order Emails</h2>
            <h4>Every line the customer reads on their receipt is set here. Leave a
                field empty and it falls back to the wording shown as its default, so
                a blank field can never produce a blank receipt.</h4>
            <h4><strong>Both addresses below must exist as real mailboxes on this
                domain.</strong> An address on a different domain will fail
                authentication and receipts will stop being delivered. An address with
                no mailbox behind it damages deliverability for the whole site.</h4>
            <h4>Bounces for undeliverable receipts arrive at the <em>Send Receipts
                From</em> address. Forward that mailbox somewhere it will be read, or
                a receipt that starts failing will fail silently.</h4>
            <h4><strong>Placeholders</strong> you can use in any wording field below.
                They are replaced when the email is sent:<br>
                <code>{shop}</code> site name &nbsp;
                <code>{order}</code> order reference &nbsp;
                <code>{first_name}</code> &nbsp;
                <code>{last_name}</code> &nbsp;
                <code>{email}</code> customer address &nbsp;
                <code>{contact}</code> the contact address below &nbsp;
                <code>{total}</code> amount paid</h4>
        </cms:editable>

        <cms:editable type='row' name='ccs_gl_ordml_addr_row' order='5' >
            <cms:editable type='text' name='ccs_gl_ordml_from' label='Send Receipts From'
                desc='Unmonitored sending address. Must be a real mailbox on this domain. Bounces arrive here.'
                class='col-md-6 mb-3' order='1' >donotreply@valasports.com</cms:editable>
            <cms:editable type='text' name='ccs_gl_ordml_contact' label='Customer Contact Address'
                desc='Shown to customers for questions. Must be monitored by a person.'
                class='col-md-6 mb-3' order='2' >orders@valasports.com</cms:editable>
        </cms:editable>

        <cms:editable type='message' name='ccs_gl_ordml_wording_msg' order='10'>
            <h4>Receipt wording</h4>
        </cms:editable>

        <cms:editable type='row' name='ccs_gl_ordml_head_row' order='15' >
            <cms:editable type='text' name='ccs_gl_ordml_subject' label='Subject Line'
                desc='What the customer sees in their inbox list.'
                class='col-md-8 mb-3' order='1' >Your {shop} order {order}</cms:editable>
            <cms:editable type='text' name='ccs_gl_ordml_greeting' label='Greeting'
                desc='Opening line. If the name is missing the comma is removed automatically.'
                class='col-md-4 mb-3' order='2' >Thank you, {first_name}.</cms:editable>
        </cms:editable>

        <cms:editable type='row' name='ccs_gl_ordml_intro_row' order='20' >
            <cms:editable type='textarea' name='ccs_gl_ordml_confirm' label='Confirmation Line'
                desc='Confirms the order and the payment.' height='70'
                class='col-md-6 mb-3' order='1' >We have your order and your payment has gone through.</cms:editable>
            <cms:editable type='textarea' name='ccs_gl_ordml_reference' label='Reference Line'
                desc='Gives the customer their order reference.' height='70'
                class='col-md-6 mb-3' order='2' >Your reference is {order} — please quote it if you get in touch.</cms:editable>
        </cms:editable>

        <cms:editable type='row' name='ccs_gl_ordml_close_row' order='25' >
            <cms:editable type='textarea' name='ccs_gl_ordml_next' label='What Happens Next'
                desc='Do not promise anything the business cannot do. There is no order tracking or shipping notification.' height='70'
                class='col-md-6 mb-3' order='1' >We will be in touch when your order is on its way.</cms:editable>
            <cms:editable type='textarea' name='ccs_gl_ordml_help' label='Where To Get Help'
                desc='Must tell the customer not to reply, since the sending address is unmonitored.' height='70'
                class='col-md-6 mb-3' order='2' >Questions about your order, or something not right? Email {contact} and quote {order}. This message is sent from an unmonitored address, so please do not reply to it.</cms:editable>
        </cms:editable>

        <cms:editable type='row' name='ccs_gl_ordml_foot_row' order='30' >
            <cms:editable type='text' name='ccs_gl_ordml_btn' label='Receipt Button Label'
                desc='Links to the order page.'
                class='col-md-4 mb-3' order='1' >View your receipt</cms:editable>
            <cms:editable type='textarea' name='ccs_gl_ordml_footer' label='Footer Note'
                desc='Small print under the receipt. Explains why the email was sent.' height='70'
                class='col-md-8 mb-3' order='2' >This receipt was sent to {email} because an order was placed at {shop}.</cms:editable>
        </cms:editable>

    </cms:editable>

    <cms:editable type='group' name='ccs_gl_maintenance_grp' label='Maintenance Mode' collapsed='1' order='160'>

        <cms:editable type='message' name='ccs_gl_maint_msg_hdr' order='1'>
            <h2>Maintenance Mode</h2>
            <h4>Ticking the box sends public visitors to a holding page. Anyone at
                access level 7 or above keeps browsing the real site, so you can
                work while it is closed.</h4>
            <h4>Still reachable while it is on: the login and password pages, so you
                can sign in; and the Stripe and PayPal callbacks, so a payment already
                in flight still completes. Nothing else on the front end is.</h4>
            <h4>The holding page returns HTTP 503 with a Retry-After header, which
                tells search engines the site is temporarily down rather than gone.
                Leaving maintenance mode on for weeks will still cost you rankings.</h4>
        </cms:editable>

        <cms:editable type='checkbox' name='ccs_gl_maintenance_mode' label='Soft Maintenance Mode'
            desc='Check to send public traffic to the holding page' opt_values='Site Offline=1' order='5' />

        <cms:editable type='text' name='ccs_gl_maint_head' label='Heading'
            desc='Shown large at the top of the holding page.'
            order='10' >We will be back shortly</cms:editable>

        <cms:editable type='textarea' name='ccs_gl_maint_body' label='Message' height='90'
            desc='One or two sentences. Say what is happening and roughly when you expect to be back.'
            order='15' >We are making some improvements to the site. Everything will be back to normal soon - thanks for your patience.</cms:editable>

        <cms:editable type='row' name='ccs_gl_maint_opt_row' order='20' >
            <cms:ignore>
                BOTH OF THESE POINT AT FIELDS THAT LIVE IN OTHER GROUPS, so the
                labels name the field and the group. There are four email fields
                in this file and only one of them belongs on a public page:

                  ccs_gl_hdqt_email     Public Email          <- this one
                  ccs_gl_form_email     form routing inbox
                  ccs_gl_ordml_from     unmonitored sender
                  ccs_gl_ordml_contact  order questions

                Either box ticked with its source field empty renders nothing at
                all rather than an empty line, which is correct but silent - so
                the descs say so.
            </cms:ignore>
            <cms:editable type='checkbox' name='ccs_gl_maint_show_email' label='Show Public Email'
                desc='The Public Email set in Contact Settings. Leave that field blank and nothing is shown here.'
                opt_values='Show the public email=1' class='col-md-6 mb-3' order='1' />
            <cms:editable type='checkbox' name='ccs_gl_maint_show_social' label='Show Social Links'
                desc='The links set in Social Media Settings. No links there means nothing is shown here.'
                opt_values='Show social links=1' class='col-md-6 mb-3' order='2' >1</cms:editable>
        </cms:editable>

    </cms:editable>

</cms:template>
<cms:if k_template_name="globals.php"><cms:set ccs_gl_edt_ok='1' /></cms:if>

<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />") > 
    <cms:embed 'pb_mods/pg_frame/head.htm' />
    <cms:embed 'pb_mods/pg_frame/main-cap.htm' />   
    <cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />
	<cms:set my_redirect_link = k_page_link />

	
        <div class="container py-5 ccs-dash">
        <style>
            /* ---------------------------------------------------------------
               DASHBOARD SURFACES AND ROWS

               EVERY COLOR HERE IS DERIVED FROM --ccs-body-color-rgb, which
               user.php writes per theme. Nothing is hardcoded, so a card reads
               correctly on Notte, on Inverno, and on anything added later. A
               flat hex or a fixed gray is what caused the unreadable text this
               page had before.

               Cards previously carried border-0 on a transparent background,
               which on a dark theme meant no card was visible at all - the page
               read as floating fragments. They now have a 3.5% lift and a 14%
               border: enough to see the container, not enough to compete with
               the content.

               .ccs-dash-row is the one row primitive. Icon, label, current
               value, action - in that order, every time. min-width:0 on the
               middle column is what lets a long value ellipsis instead of
               shoving the button out of the card, and flex:0 0 auto on the
               action is what stops the button ever shrinking or wrapping.

               .list-group's color token is --ccs-gray-800 (#555555) in
               theme.css, declared once in :root and never redefined for dark.
               Nothing here uses .list-group any more, but the override stays:
               it costs one line and it protects anything added later.

               .badge and a second .btn rule (theme.css:6649 and :23055) are
               both white-space: nowrap, so a label wider than its box ran out
               through the border. Wrapping lets them grow downward. The round
               icon buttons keep 32px because that is an inline style.
               --------------------------------------------------------------- */
            .ccs-dash .list-group { --ccs-list-group-color: var(--ccs-body-color); }
            .ccs-dash .badge      { white-space: normal; }
            .ccs-dash .btn        { white-space: normal; overflow-wrap: break-word; height: auto; }

            .ccs-dash .card {
                background-color: rgba(var(--ccs-body-color-rgb), .035);
                border: 1px solid rgba(var(--ccs-body-color-rgb), .14) !important;
            }
            .ccs-dash .card-header {
                border-bottom: 1px solid rgba(var(--ccs-body-color-rgb), .10) !important;
            }

            .ccs-dash-sec {
                font-size: .75rem; letter-spacing: .08em; text-transform: uppercase;
                font-weight: 700; opacity: .55;
            }

            .ccs-dash-row {
                display: flex; align-items: center; gap: .75rem; padding: .7rem 0;
                border-bottom: 1px solid rgba(var(--ccs-body-color-rgb), .10);
            }
            .ccs-dash-row:last-child { border-bottom: 0; padding-bottom: 0; }
            .ccs-dash-row-ico   { flex: 0 0 1.25rem; text-align: center; opacity: .55; }
            .ccs-dash-row-main  { flex: 1 1 auto; min-width: 0; }
            .ccs-dash-row-label { display: block; font-weight: 600; line-height: 1.25; }
            .ccs-dash-row-val   { display: block; font-size: .8125rem; opacity: .65;
                                  line-height: 1.35; overflow: hidden;
                                  text-overflow: ellipsis; white-space: nowrap; }
            .ccs-dash-row-act   { flex: 0 0 auto; }

            .ccs-dash-sw    { width: 22px; height: 22px; border-radius: 50%;
                              border: 1px solid rgba(var(--ccs-body-color-rgb), .25); }
            .ccs-dash-spec-lbl { font-size: .7rem; letter-spacing: .05em; text-transform: uppercase;
                                 font-weight: 700; opacity: .6; white-space: nowrap; }
            .ccs-dash-spec-fnt { font-size: .7rem; opacity: .85; min-width: 0;
                                 overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .ccs-spec       { line-height: 1.15; }
            .ccs-spec-lg    { font-size: 1.75rem; }
            .ccs-spec-md    { font-size: 1.05rem; }
            .ccs-spec-sm    { font-size: .7rem; }
            .ccs-dash-strip { background-color: rgba(var(--ccs-body-color-rgb), .05);
                              border-radius: .375rem; padding: .75rem; }
        </style>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <h2 class="fw-bold mb-0">Global Settings</h2>
                <cms:if (k_user_access_level ge '10')>
                    <a target="_blank" href="<cms:admin_link />" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-tools me-2"></i>Advanced Admin
                    </a>
                </cms:if>
            </div>

            <cms:ignore>
                COUNTS ARE READ ONCE, HERE, NOT INSIDE THE CARD.

                Each of these opens a pagebuilder loop, and a loop reassigns the
                context while it runs. Reading them up front and stashing the
                result globally keeps the card markup free of loops and means the
                Layout card can show all three counts side by side.
            </cms:ignore>
            <cms:set nav_count='0' scope='global' />
            <cms:show_pagebuilder 'ccs_gl_nav_pb'><cms:set nav_count=k_count scope='global' /></cms:show_pagebuilder>
            <cms:set ftr_count='0' scope='global' />
            <cms:show_pagebuilder 'ccs_gl_ftr_pb'><cms:set ftr_count=k_count scope='global' /></cms:show_pagebuilder>
            <cms:set tpb_count='0' scope='global' />
            <cms:show_pagebuilder 'ccs_gl_tpb_pb'><cms:set tpb_count=k_count scope='global' /></cms:show_pagebuilder>


            <h3 class="ccs-dash-sec mb-3">Look and feel</h3>
            <div class="row g-4">

                <cms:ignore>================= BRAND =================</cms:ignore>
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-transparent pt-3 pb-2">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-copyright me-2 opacity-50"></i>Brand</h5>
                        </div>
                        <div class="card-body">

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-signature"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Name &amp; tagline</span>
                                    <span class="ccs-dash-row-val"><cms:if ccs_gl_site_name ne ''><cms:show ccs_gl_site_name /><cms:else />Not set</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_site_biz_grp|ccs_gl_site_biz_mta_msg|ccs_gl_site_biz_mta_row|ccs_gl_site_name_txt_lgo_opt|ccs_gl_site_name|ccs_gl_site_name_txt_sz|ccs_gl_site_name_txt_clr|ccs_gl_tgln_fmt_msg|ccs_gl_site_tgl_row|ccs_gl_tgln|ccs_gl_tgln_opt|ccs_gl_tgln_clr|ccs_gl_tgln_txt_sz|nca_cntnt_2col_img_br|ccs_gl_tgln_fnt_fm|ccs_gl_tgln_fnt_wt|ccs_gl_tgln_fnt_st|ccs_gl_tgln_ltr_spc' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-image"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Logos &amp; favicon</span>
                                    <span class="ccs-dash-row-val">Wide and square, light and dark<cms:if ccs_gl_site_favicon eq ''> &middot; no favicon set</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_site_biz_grp|ccs_gl_site_biz_lgo_msg|ccs_gl_site_biz_lgo_hoz_row|ccs_gl_site_logo_hoz_drk|ccs_gl_site_logo_hoz_lgt|ccs_gl_site_biz_lgo_sqr_row|ccs_gl_site_logo_sqr_drk|ccs_gl_site_logo_sqr_lgt|ccs_gl_site_biz_lgo_fav_row|ccs_gl_site_favicon' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                            <cms:ignore>
                                THE FAVICON HAS NO ROW OF ITS OWN.

                                It is one 64px image that is set once and never touched
                                again, and the Logos popup already carries it - the field
                                list ends ccs_gl_site_biz_lgo_fav_row|ccs_gl_site_favicon.
                                A second row pointing at the same field made the card look
                                like it held two jobs when it holds one. The Logos row now
                                says so, and flags it when the favicon is missing.

                                WHAT WENT WITH IT: ccs_gl_site_social_share. The pre-rebuild
                                dashboard paired that name with the favicon under a label
                                promising a share image, and the field is declared nowhere -
                                not in this template, not anywhere on either site. One
                                reference, zero definitions.

                                The feature behind it is unbuilt as well: the Open Graph tags
                                in snippets/pb_mods/pg_frame/head.htm lines 82-87 are still
                                boilerplate, so every page ships og:title empty and og:image
                                set to the literal string LINK TO THE IMAGE FILE. Any link to
                                this site shared anywhere shows no title and no picture.
                                Building it means an image editable plus real values in those
                                six meta tags. Still outstanding.
                            </cms:ignore>

                            <cms:ignore>
                                NEW ROW. ccs_gl_site_desc is the SEO meta description and it
                                existed in the template with no way to reach it from here -
                                the Brand popup listed the name and tagline fields but never
                                this one. Same story as the two groups added to the Layout
                                card below.
                            </cms:ignore>
                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-magnifying-glass"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">SEO Description</span>
                                    <span class="ccs-dash-row-val"><cms:if ccs_gl_site_desc ne ''><cms:show ccs_gl_site_desc /><cms:else />Not set</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_site_biz_grp|ccs_gl_site_desc_msg|ccs_gl_site_desc' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                        </div>
                    </div>
                </div>

                <cms:ignore>================= THEME &amp; COLOR =================</cms:ignore>
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-transparent pt-3 pb-2">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-palette me-2 opacity-50"></i>Theme &amp; color</h5>
                        </div>
                        <div class="card-body">

                            <cms:ignore>
                                ONE SOURCE FOR THE PALETTE.

                                The swatches used to be a sixty-line per-theme cms:if chain
                                copied out of assets/css/user.php - a second copy of the same
                                table, which is exactly how the font chain drifted and ended
                                up blank on two themes.

                                ccs_theme_palette() in the dash's kfunctions.php is that table
                                in PHP, and _tools/check-theme-palette.py already diffs it
                                against user.php. Calling it here means the swatches cannot
                                disagree with the stylesheet, and a new theme is added in two
                                places instead of three.

                                It returns the CURATED palette. The two things it does not
                                know about are applied after: Customize Colors, which replaces
                                every value with the saved custom fields, and Disable Theme
                                Bg/Text, which sends the background and text back to the base
                                white-on-dark pair rather than the theme's own.
                            </cms:ignore>
                            <cms:php>
                                global $CTX;
                                $p = ccs_theme_palette( $CTX->get('ccs_gl_site_thm_opt') );
                                foreach( $p as $k => $v ){ $CTX->set( 'sw_'.$k, $v, 'global' ); }
                            </cms:php>

                            <cms:if ccs_gl_site_custom_color_opt>
                                <cms:set sw_primary=ccs_gl_site_primary_cust "global" />
                                <cms:set sw_secondary=ccs_gl_site_secondary_cust "global" />
                                <cms:set sw_tertiary=ccs_gl_site_tertiary_cust "global" />
                                <cms:set sw_quaternary=ccs_gl_site_quaternary_cust "global" />
                                <cms:set sw_success=ccs_gl_site_success_cust "global" />
                                <cms:set sw_info=ccs_gl_site_info_cust "global" />
                                <cms:set sw_warning=ccs_gl_site_warning_cust "global" />
                                <cms:set sw_danger=ccs_gl_site_danger_cust "global" />
                                <cms:set sw_light=ccs_gl_site_light_cust "global" />
                                <cms:set sw_dark=ccs_gl_site_dark_cust "global" />
                                <cms:set sw_body_bg=ccs_gl_site_body_bg_cust "global" />
                                <cms:set sw_body_clr=ccs_gl_site_body_clr_cust "global" />
                            <cms:else_if ccs_gl_site_thm_clr_opt eq '1' />
                                <cms:set sw_body_bg='#ffffff' "global" />
                                <cms:set sw_body_clr='#292b2c' "global" />
                            </cms:if>

                            <div class="ccs-dash-strip mb-3">
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <span class="ccs-dash-sw" title="Primary"    style="background-color: <cms:show sw_primary />;"></span>
                                    <span class="ccs-dash-sw" title="Secondary"  style="background-color: <cms:show sw_secondary />;"></span>
                                    <span class="ccs-dash-sw" title="Tertiary"   style="background-color: <cms:show sw_tertiary />;"></span>
                                    <span class="ccs-dash-sw" title="Quaternary" style="background-color: <cms:show sw_quaternary />;"></span>
                                    <span class="opacity-25 mx-1">|</span>
                                    <span class="ccs-dash-sw" title="Success" style="background-color: <cms:show sw_success />;"></span>
                                    <span class="ccs-dash-sw" title="Info"    style="background-color: <cms:show sw_info />;"></span>
                                    <span class="ccs-dash-sw" title="Warning" style="background-color: <cms:show sw_warning />;"></span>
                                    <span class="ccs-dash-sw" title="Danger"  style="background-color: <cms:show sw_danger />;"></span>
                                    <span class="opacity-25 mx-1">|</span>
                                    <span class="ccs-dash-sw" title="Page background" style="background-color: <cms:show sw_body_bg />;"></span>
                                    <span class="ccs-dash-sw" title="Body text"       style="background-color: <cms:show sw_body_clr />;"></span>
                                </div>
                            </div>

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-swatchbook"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Theme</span>
                                    <span class="ccs-dash-row-val"><cms:if ccs_gl_site_custom_color_opt>Custom colors<cms:else /><cms:show ccs_gl_site_thm_opt /></cms:if><cms:if ccs_gl_site_thm_clr_opt eq '1'> &middot; plain background</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_master_theme_grp|ccs_gl_theme_custom_msg|ccs_gl_site_custom_color_opt|ccs_gl_theme_master_msg|ccs_gl_site_thm_opt|ccs_gl_site_thm_clr_opt|ccs_gl_site_thm_typo_opt|ccs_gl_site_site_cst_clr_grp|ccs_gl_site_primary_cust|ccs_gl_site_secondary_cust|ccs_gl_site_tertiary_cust|ccs_gl_site_quaternary_cust|ccs_gl_site_success_cust|ccs_gl_site_info_cust|ccs_gl_site_warning_cust|ccs_gl_site_danger_cust|ccs_gl_site_light_cust|ccs_gl_site_dark_cust|ccs_gl_site_white_cust|ccs_gl_site_black_cust|ccs_gl_site_body_clr_cust|ccs_gl_site_body_bg_cust' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-highlighter"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Highlight &amp; selection</span>
                                    <span class="ccs-dash-row-val"><cms:if ccs_gl_site_hglt_clr ne '' || ccs_gl_site_hglt_bg ne ''>Custom<cms:else />Browser default</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_site_site_cst_slct_grp|ccs_gl_site_site_cst_slct_msg|ccs_gl_site_hglt_row|ccs_gl_site_hglt_clr|ccs_gl_site_hglt_bg' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                        </div>
                    </div>
                </div>

                <cms:ignore>================= TYPE =================</cms:ignore>
                <div class="col-12">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-transparent pt-3 pb-2">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-font me-2 opacity-50"></i>Type</h5>
                        </div>
                        <div class="card-body">

                            <cms:ignore>
                                THE FONT CHAIN, RESOLVED ONCE, MIRRORING user.php EXACTLY.

                                Three branches, and the two that are easy to get wrong:

                                CURSIVE AND DECORATIVE ARE NOT THE SAME IN BOTH BRANCHES.
                                With Apply Theme Typography ON they are Dancing Script and
                                Oswald; with it OFF they are Bradley Hand and Sreda. Same
                                monospace either way. Verified against user.php lines 48-50
                                and its else branch - not assumed.

                                ONLY FOUR THEMES HAVE A CURATED PAIRING. primavera, estate,
                                autunno and inverno. dark, light, scuro and notte fall to the
                                stock Montserrat / Raleway / Playfair Display. Inventing
                                branches for them would name fonts the stylesheet never loads.

                                THE DEFAULTS ARE SET FIRST, THEN OVERRIDDEN. Same shape as
                                the chain in user.php, so a branch that sets only three of
                                the six leaves the rest correct instead of blank. The missing
                                else here is what left the specimen empty on scuro and notte.

                                SIZE DEFAULT IS 1, NOT 0.875. This card said 0.875 while
                                user.php has said 1 since the typography overhaul, so the
                                preview was printing a number the site had not used for weeks.
                            </cms:ignore>
                            <cms:set dash_font_body='Montserrat' "global" />
                            <cms:set dash_font_sans='Raleway' "global" />
                            <cms:set dash_font_serif='Playfair Display' "global" />
                            <cms:set dash_font_mono='SFMono-Regular' "global" />
                            <cms:set dash_font_cursive='Bradley Hand' "global" />
                            <cms:set dash_font_decor='Sreda' "global" />
                            <cms:set dash_font_size='1' "global" />
                            <cms:set dash_font_weight='400' "global" />
                            <cms:set dash_line_height='1.5' "global" />
                            <cms:set dash_letter_space='normal' "global" />

                            <cms:if ccs_gl_site_custom_font_opt>
                                <cms:set dash_font_body=ccs_gl_site_font_body_cust "global" />
                                <cms:set dash_font_sans=ccs_gl_site_font_sans_cust "global" />
                                <cms:set dash_font_serif=ccs_gl_site_font_serf_cust "global" />
                                <cms:set dash_font_mono=ccs_gl_site_font_mono_cust "global" />
                                <cms:set dash_font_cursive=ccs_gl_site_font_crsv_cust "global" />
                                <cms:set dash_font_decor=ccs_gl_site_font_decr_cust "global" />

                                <cms:set dash_font_size=ccs_gl_site_font_size_cust "global" />
                                <cms:set dash_font_weight=ccs_gl_site_font_weight_cust "global" />
                                <cms:set dash_line_height=ccs_gl_site_line_height_cust "global" />
                                <cms:set dash_letter_space=ccs_gl_site_letter_space_cust "global" />

                                <cms:if dash_font_size eq '-' || dash_font_size eq ''><cms:set dash_font_size='1' "global" /></cms:if>
                                <cms:if dash_font_weight eq '-' || dash_font_weight eq ''><cms:set dash_font_weight='400' "global" /></cms:if>
                                <cms:if dash_line_height eq '-' || dash_line_height eq ''><cms:set dash_line_height='1.5' "global" /></cms:if>
                                <cms:if dash_letter_space eq '-' || dash_letter_space eq ''><cms:set dash_letter_space='normal' "global" /></cms:if>

                            <cms:else_if ccs_gl_site_thm_typo_opt />
                                <cms:if ccs_gl_site_thm_opt == 'primavera'>
                                    <cms:set dash_font_body='Lato' "global" /><cms:set dash_font_sans='Lato' "global" /><cms:set dash_font_serif='Playfair Display' "global" />
                                <cms:else_if ccs_gl_site_thm_opt == 'estate' />
                                    <cms:set dash_font_body='Montserrat' "global" /><cms:set dash_font_sans='Montserrat' "global" /><cms:set dash_font_serif='Cinzel' "global" />
                                <cms:else_if ccs_gl_site_thm_opt == 'autunno' />
                                    <cms:set dash_font_body='Open Sans' "global" /><cms:set dash_font_sans='Open Sans' "global" /><cms:set dash_font_serif='Merriweather' "global" />
                                <cms:else_if ccs_gl_site_thm_opt == 'inverno' />
                                    <cms:set dash_font_body='Roboto' "global" /><cms:set dash_font_sans='Roboto' "global" /><cms:set dash_font_serif='Lora' "global" />
                                </cms:if>
                                <cms:set dash_font_cursive='Dancing Script' "global" />
                                <cms:set dash_font_decor='Oswald' "global" />
                            </cms:if>

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-a"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Fonts</span>
                                    <span class="ccs-dash-row-val"><cms:if ccs_gl_site_custom_font_opt>Custom<cms:else_if ccs_gl_site_thm_typo_opt />Theme pairing<cms:else />Site standard</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_master_theme_grp|ccs_gl_site_custom_font_opt|ccs_gl_site_site_fonts|ccs_gl_site_font_body_cust|ccs_gl_site_font_sans_cust|ccs_gl_site_font_serf_cust|ccs_gl_site_font_mono_cust|ccs_gl_site_font_crsv_cust|ccs_gl_site_font_decr_cust|ccs_gl_site_font_size_cust|ccs_gl_site_font_weight_cust|ccs_gl_site_line_height_cust|ccs_gl_site_letter_space_cust' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-text-height"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Text size</span>
                                    <span class="ccs-dash-row-val">Whole site at <cms:if ccs_gl_site_text_scale ne ''><cms:show ccs_gl_site_text_scale /><cms:else />100</cms:if>%</span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_master_theme_grp|ccs_gl_site_text_scale' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                            <cms:ignore>
                                WATERFALL, NOT ONE BIG TRUNCATED LINE.

                                The tiles originally showed one sentence at fs-6 with
                                text-truncate, so every one ended in an ellipsis. An ellipsis
                                reads as "click to see the rest" - it looked like a control
                                and was not one.

                                A waterfall is the standard way to present a typeface: the
                                same word at descending sizes, so both the letterforms and how
                                the face holds together as it shrinks are visible at once.
                                Nothing is cut off, so nothing implies there is more to open.

                                "Handgloves" is the traditional type-tester word. It carries
                                ascenders, descenders, round and straight forms and a
                                distinctive g - the letters that actually separate one face
                                from another - while still reading as a word.

                                The tiles size to their content. The fixed height:48% with
                                overflow-hidden is what forced the truncation originally.
                            </cms:ignore>
                            <div class="row g-3 mt-1">
                                <cms:ignore>Base / Body</cms:ignore>
                                <div class="col-12 col-sm-6 col-xl-4">
                                    <div class="ccs-dash-strip h-100">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                                            <span class="ccs-dash-spec-lbl">Base / Body</span>
                                            <span class="ccs-dash-spec-fnt"><cms:if dash_font_body eq '-' || dash_font_body eq ''>Base<cms:else /><cms:show dash_font_body /></cms:if></span>
                                        </div>
                                        <div class="ccs-spec" style="font-family: '<cms:show dash_font_body />', sans-serif;">
                                            <div class="ccs-spec-lg">Handgloves</div>
                                            <div class="ccs-spec-md">Handgloves</div>
                                            <div class="ccs-spec-sm">Handgloves</div>
                                        </div>
                                    </div>
                                </div>

                                <cms:ignore>Sans-Serif</cms:ignore>
                                <div class="col-12 col-sm-6 col-xl-4">
                                    <div class="ccs-dash-strip h-100">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                                            <span class="ccs-dash-spec-lbl">Sans-Serif</span>
                                            <span class="ccs-dash-spec-fnt"><cms:if dash_font_sans eq '-' || dash_font_sans eq ''>Base<cms:else /><cms:show dash_font_sans /></cms:if></span>
                                        </div>
                                        <div class="ccs-spec" style="font-family: '<cms:show dash_font_sans />', sans-serif;">
                                            <div class="ccs-spec-lg">Handgloves</div>
                                            <div class="ccs-spec-md">Handgloves</div>
                                            <div class="ccs-spec-sm">Handgloves</div>
                                        </div>
                                    </div>
                                </div>

                                <cms:ignore>Headings (Serif)</cms:ignore>
                                <div class="col-12 col-sm-6 col-xl-4">
                                    <div class="ccs-dash-strip h-100">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                                            <span class="ccs-dash-spec-lbl">Headings (Serif)</span>
                                            <span class="ccs-dash-spec-fnt"><cms:if dash_font_serif eq '-' || dash_font_serif eq ''>Base<cms:else /><cms:show dash_font_serif /></cms:if></span>
                                        </div>
                                        <div class="ccs-spec fw-bold" style="font-family: '<cms:show dash_font_serif />', serif;">
                                            <div class="ccs-spec-lg">Handgloves</div>
                                            <div class="ccs-spec-md">Handgloves</div>
                                            <div class="ccs-spec-sm">Handgloves</div>
                                        </div>
                                    </div>
                                </div>

                                <cms:ignore>Monospace</cms:ignore>
                                <div class="col-12 col-sm-6 col-xl-4">
                                    <div class="ccs-dash-strip h-100">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                                            <span class="ccs-dash-spec-lbl">Monospace</span>
                                            <span class="ccs-dash-spec-fnt"><cms:if dash_font_mono eq '-' || dash_font_mono eq ''>Base<cms:else /><cms:show dash_font_mono /></cms:if></span>
                                        </div>
                                        <div class="ccs-spec" style="font-family: '<cms:show dash_font_mono />', monospace;">
                                            <div class="ccs-spec-lg">Handgloves</div>
                                            <div class="ccs-spec-md">Handgloves</div>
                                            <div class="ccs-spec-sm">Handgloves</div>
                                        </div>
                                    </div>
                                </div>

                                <cms:ignore>Cursive / Script</cms:ignore>
                                <div class="col-12 col-sm-6 col-xl-4">
                                    <div class="ccs-dash-strip h-100">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                                            <span class="ccs-dash-spec-lbl">Cursive / Script</span>
                                            <span class="ccs-dash-spec-fnt"><cms:if dash_font_cursive eq '-' || dash_font_cursive eq ''>Base<cms:else /><cms:show dash_font_cursive /></cms:if></span>
                                        </div>
                                        <div class="ccs-spec" style="font-family: '<cms:show dash_font_cursive />', cursive;">
                                            <div class="ccs-spec-lg">Handgloves</div>
                                            <div class="ccs-spec-md">Handgloves</div>
                                            <div class="ccs-spec-sm">Handgloves</div>
                                        </div>
                                    </div>
                                </div>

                                <cms:ignore>Decorative</cms:ignore>
                                <div class="col-12 col-sm-6 col-xl-4">
                                    <div class="ccs-dash-strip h-100">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-1">
                                            <span class="ccs-dash-spec-lbl">Decorative</span>
                                            <span class="ccs-dash-spec-fnt"><cms:if dash_font_decor eq '-' || dash_font_decor eq ''>Base<cms:else /><cms:show dash_font_decor /></cms:if></span>
                                        </div>
                                        <div class="ccs-spec" style="font-family: '<cms:show dash_font_decor />', sans-serif;">
                                            <div class="ccs-spec-lg">Handgloves</div>
                                            <div class="ccs-spec-md">Handgloves</div>
                                            <div class="ccs-spec-sm">Handgloves</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <cms:ignore>
                                THE SETTINGS SENTENCE.

                                Real running text carrying the four base settings as inline
                                style, so what is described in the label is what is rendered
                                below it. A specimen tile shows you a letterform; only a
                                paragraph shows you what those numbers do to a block of text,
                                which is the thing being set.

                                The size is multiplied by the Site Text Size scale in the
                                browser, not here: font-size is in rem and rem measures
                                against the root, which user.php sets to that percentage. So
                                this paragraph is the true rendered size at the current scale
                                without any arithmetic in the template.
                            </cms:ignore>
                            <div class="ccs-dash-strip mt-3">
                                <div class="ccs-dash-spec-lbl mb-2">
                                    Base text &middot; size <cms:show dash_font_size />rem
                                    &middot; weight <cms:show dash_font_weight />
                                    &middot; line height <cms:show dash_line_height />
                                    &middot; letter spacing <cms:show dash_letter_space />
                                    <cms:if ccs_gl_site_text_scale ne '' && ccs_gl_site_text_scale ne '100'>&middot; scaled to <cms:show ccs_gl_site_text_scale />%</cms:if>
                                </div>
                                <p class="mb-0" style="font-family: '<cms:show dash_font_body />', sans-serif; font-size: <cms:show dash_font_size />rem; font-weight: <cms:show dash_font_weight />; line-height: <cms:show dash_line_height />; letter-spacing: <cms:show dash_letter_space />;">
                                    This paragraph is set in the site's own base font at the exact size, weight, line height and letter spacing chosen above. Read a few lines of it rather than a single word: spacing and rhythm only show themselves at length. The quick brown fox jumps over the lazy dog.
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

            </div>


            <h3 class="ccs-dash-sec mb-3 mt-5">Setup and data</h3>
            <div class="row g-4">

                <cms:ignore>================= LAYOUT =================</cms:ignore>
                <div class="col-12 col-md-6 col-xxl-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-transparent pt-3 pb-2">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-table-columns me-2 opacity-50"></i>Layout</h5>
                        </div>
                        <div class="card-body">

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-bars"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Navbar</span>
                                    <span class="ccs-dash-row-val"><cms:if nav_count == '1'>1 active<cms:else_if nav_count == '0' />None &mdash; the site has no menu<cms:else /><cms:show nav_count /> staged, only the first shows</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_nav_pb' link_text="<button class='btn btn-sm btn-outline-primary'>Manage</button>" />
                                </span>
                            </div>

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-arrow-up"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Utility bar</span>
                                    <span class="ccs-dash-row-val"><cms:if tpb_count == '0'>None staged<cms:else_if tpb_count == '1' />1 active<cms:else_if tpb_count == '2' />2 active<cms:else /><cms:show tpb_count /> staged, only the top two show</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_tpb_pb' link_text="<button class='btn btn-sm btn-outline-primary'>Manage</button>" />
                                </span>
                            </div>

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-shoe-prints"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Footer</span>
                                    <span class="ccs-dash-row-val"><cms:if ftr_count == '1'>1 active<cms:else_if ftr_count == '0' />None &mdash; the site has no footer<cms:else /><cms:show ftr_count /> staged, only the first shows</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_ftr_pb' link_text="<button class='btn btn-sm btn-outline-primary'>Manage</button>" />
                                </span>
                            </div>

                            <cms:ignore>
                                TWO ROWS THAT HAD NO WAY IN.

                                ccs_gl_site_nav_mnu_grp and ccs_gl_site_ftr_grp are both real
                                groups in this template with saved values driving live CSS,
                                and neither appeared anywhere on the old dashboard - the only
                                way to reach them was the back end. The footer one was added
                                earlier this month and never wired to a button at all.
                            </cms:ignore>
                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-square-caret-down"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Menu &amp; dropdown colors</span>
                                    <span class="ccs-dash-row-val">Hover, active page and dropdown panels</span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_site_nav_mnu_grp|ccs_gl_site_nav_mnu_dd_msg|ccs_gl_site_nav_actv_clr|ccs_gl_site_nav_hvr_clr|ccs_gl_site_nav_mnu_dd_row|ccs_gl_site_nav_dd_txt_clr|ccs_gl_site_nav_dd_bg|ccs_gl_site_nav_dd_hvr_clr|ccs_gl_site_nav_dd_hvr_bg' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-link"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Footer &amp; utility links</span>
                                    <span class="ccs-dash-row-val">Hover color for the two bars</span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_site_ftr_grp|ccs_gl_site_ftr_msg|ccs_gl_site_ftr_row|ccs_gl_site_ftr_lnk_hvr_clr|ccs_gl_site_ubr_lnk_hvr_clr' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                            <cms:if nav_count gt '1' || ftr_count gt '1' || tpb_count gt '2'>
                                <div class="alert alert-warning small py-2 mt-3 mb-0">
                                    <i class="fas fa-triangle-exclamation me-1"></i>More blocks are staged than the site will show. Only the top of each list goes live.
                                </div>
                            </cms:if>
                            <cms:if nav_count == '0' || ftr_count == '0'>
                                <div class="alert alert-danger small py-2 mt-3 mb-0">
                                    <i class="fas fa-circle-exclamation me-1"></i>A navbar or footer is missing. Visitors will land on a page with no way to move around it.
                                </div>
                            </cms:if>

                        </div>
                    </div>
                </div>

                <cms:ignore>================= CONTACT &amp; SOCIAL =================</cms:ignore>
                <div class="col-12 col-md-6 col-xxl-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-transparent pt-3 pb-2">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-address-book me-2 opacity-50"></i>Contact &amp; social</h5>
                        </div>
                        <div class="card-body">

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-location-dot"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Contact details</span>
                                    <span class="ccs-dash-row-val"><cms:if ccs_gl_hdqt_cty ne ''><cms:show ccs_gl_hdqt_cty /><cms:if ccs_gl_hdqt_sta ne ''>, <cms:show ccs_gl_hdqt_sta /></cms:if><cms:else_if ccs_gl_hdqt_email ne '' /><cms:show ccs_gl_hdqt_email /><cms:else />Not set</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_site_cntc_grp|ccs_gl_hdqt_st_add|ccs_gl_hdqt_st_ad2|ccs_gl_hdqt_cty|ccs_gl_hdqt_sta|ccs_gl_hdqt_zip|ccs_gl_hdqt_ggl_lnk|ccs_gl_hdqt_email|ccs_gl_cntct_pho_msg|ccs_gl_hdqt_pho_rpt|ccs_gl_hdqt_fax|ccs_gl_cntct_map_msg|ccs_gl_ggl_maps_rpt' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-hashtag"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Social links</span>
                                    <span class="ccs-dash-row-val">Profiles and app store links</span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_site_scl_grp|ccs_gl_social_msg|ccs_gl_social|ccs_gl_andr_app|ccs_gl_appl_app' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                        </div>
                    </div>
                </div>

                <cms:ignore>================= STORE =================</cms:ignore>
                <div class="col-12 col-md-6 col-xxl-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-transparent pt-3 pb-2">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-store me-2 opacity-50"></i>Store</h5>
                        </div>
                        <div class="card-body">

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-cart-shopping"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Store appearance</span>
                                    <span class="ccs-dash-row-val">Cart, checkout and receipt panels</span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_store_grp|ccs_gl_store_msg|ccs_gl_store_panel|ccs_gl_store_secondary' height='620' width='940' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-receipt"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Order emails</span>
                                    <span class="ccs-dash-row-val"><cms:if ccs_gl_ordml_from ne ''>From <cms:show ccs_gl_ordml_from /><cms:else />Sender address not set</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_ordml_grp|ccs_gl_ordml_msg|ccs_gl_ordml_addr_row|ccs_gl_ordml_from|ccs_gl_ordml_contact|ccs_gl_ordml_wording_msg|ccs_gl_ordml_head_row|ccs_gl_ordml_subject|ccs_gl_ordml_greeting|ccs_gl_ordml_intro_row|ccs_gl_ordml_confirm|ccs_gl_ordml_reference|ccs_gl_ordml_close_row|ccs_gl_ordml_next|ccs_gl_ordml_help|ccs_gl_ordml_foot_row|ccs_gl_ordml_btn|ccs_gl_ordml_footer' height='860' width='1040' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                        </div>
                    </div>
                </div>

                <cms:ignore>================= INTEGRATIONS &amp; LEGAL =================</cms:ignore>
                <div class="col-12 col-md-6 col-xxl-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-transparent pt-3 pb-2">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-shield-halved me-2 opacity-50"></i>Integrations &amp; legal</h5>
                        </div>
                        <div class="card-body">

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-envelope-open-text"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Form email target</span>
                                    <span class="ccs-dash-row-val"><cms:if ccs_gl_form_email ne ''><cms:show ccs_gl_form_email /><cms:else />Not set &mdash; form messages go nowhere</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_integrations_grp|ccs_gl_form_email' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-code"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Tracking scripts</span>
                                    <span class="ccs-dash-row-val"><cms:if ccs_gl_header_scripts ne '' || ccs_gl_footer_scripts ne ''>Active<cms:else />None</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_integrations_grp|ccs_gl_header_scripts|ccs_gl_footer_scripts' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-cookie-bite"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Cookies &amp; privacy</span>
                                    <span class="ccs-dash-row-val"><cms:if ccs_gl_cookie_consent>Consent bar on<cms:else />Consent bar off</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_legal_grp|ccs_gl_cookie_consent|ccs_gl_privacy_link|ccs_gl_terms_link' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                        </div>
                    </div>
                </div>

                <cms:ignore>================= SITE STATUS =================</cms:ignore>
                <div class="col-12 col-md-6 col-xxl-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-transparent pt-3 pb-2">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-tower-broadcast me-2 opacity-50"></i>Site status</h5>
                        </div>
                        <div class="card-body">

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico">
                                    <cms:if ccs_gl_maintenance_mode><i class="fas fa-circle text-warning"></i><cms:else /><i class="fas fa-circle text-success"></i></cms:if>
                                </span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label"><cms:if ccs_gl_maintenance_mode>Closed for maintenance<cms:else />Open to the public</cms:if></span>
                                    <span class="ccs-dash-row-val"><cms:if ccs_gl_maintenance_mode>Everyone below level 7 sees the holding page<cms:else />Visitors see the live site</cms:if></span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <cms:popup_edit 'ccs_gl_maintenance_grp|ccs_gl_maint_msg_hdr|ccs_gl_maintenance_mode|ccs_gl_maint_head|ccs_gl_maint_body|ccs_gl_maint_opt_row|ccs_gl_maint_show_email|ccs_gl_maint_show_social' link_text="<button class='btn btn-sm btn-outline-primary'>Edit</button>" />
                                </span>
                            </div>

                            <div class="ccs-dash-row">
                                <span class="ccs-dash-row-ico"><i class="fas fa-eye"></i></span>
                                <span class="ccs-dash-row-main">
                                    <span class="ccs-dash-row-label">Holding page</span>
                                    <span class="ccs-dash-row-val">Preview it without switching the site off</span>
                                </span>
                                <span class="ccs-dash-row-act">
                                    <a class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener" href="<cms:show k_site_link />maintenance.php">View</a>
                                </span>
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