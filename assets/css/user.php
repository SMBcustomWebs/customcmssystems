<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:content_type 'text/css' />
<cms:template parent='_donottouch_' hidden='1' />


<cms:if "<cms:gpc 'page' />" && "<cms:gpc 'post' />">
    <cms:pages masterpage="<cms:gpc 'page' />" id="<cms:gpc 'post' />" > 
		<cms:set target_template_order=k_template_order scope='global' />
        <cms:set target_template_name=k_template_name scope='global' />
		
        <cms:set bg_clr1="<cms:show ccs_hro_bg_clr_cstm_one />" "global" />
        <cms:set bg_clr2="<cms:show ccs_hro_bg_clr_cstm_two />" "global" />
        <cms:set bg_splt="<cms:show ccs_hro_bg_clr_cust_grad_split_opt />" "global" />
        
        <cms:if ccs_hro_bg_clr_cstm_grdt_opt='1' >
            <cms:set bg_grd="1"  "global" />
        </cms:if>
        
        <cms:if "<cms:is '1' in=ccs_hro_bg_clr_cstm_grad_rvrs_opt />">
            <cms:set swap_bg_clr="1" "global" />
        </cms:if>

        <cms:if ccs_hro_ovrly_clr_opt>
            <cms:set ol_clr1="<cms:show ccs_hro_ovrly_clr_cstm_one />" "global" />
            <cms:set ol_clr2="<cms:show ccs_hro_ovrly_clr_cstm_two />" "global" />
            <cms:set ol_splt="<cms:show ccs_hro_ovrly_cust_grad_split_opt />" "global" />
            
            <cms:if ccs_hro_ovrly_clr_grdt_opt='1' >
                <cms:set ol_grd="1"  "global" />
            </cms:if>
            
            <cms:if "<cms:is '1' in=ccs_hro_ovrly_clr_cstm_grdt_rvrs_opt />">
                <cms:set swap_ol_clr="1" "global" />
            </cms:if>
        </cms:if>
        
    </cms:pages>
</cms:if>

<cms:pages masterpage="globals.php" > 
    <cms:if ccs_gl_site_custom_color_opt >
        <cms:set primary="<cms:show ccs_gl_site_primary_cust />" "global" />
        <cms:set secondary="<cms:show ccs_gl_site_secondary_cust />" "global" />
        <cms:set tertiary="<cms:show ccs_gl_site_tertiary_cust />" "global" />
        <cms:set quaternary="<cms:show ccs_gl_site_quaternary_cust />" "global" />
        <cms:set success="<cms:show ccs_gl_site_success_cust />" "global" />
        <cms:set info="<cms:show ccs_gl_site_info_cust />" "global" />
        <cms:set warning="<cms:show ccs_gl_site_warning_cust />" "global" />
        <cms:set danger="<cms:show ccs_gl_site_danger_cust />" "global" />
        <cms:set light="<cms:show ccs_gl_site_light_cust />" "global" />
        <cms:set dark="<cms:show ccs_gl_site_dark_cust />" "global" />
        <cms:set white="<cms:show ccs_gl_site_white_cust />" "global" />
        <cms:set black="<cms:show ccs_gl_site_black_cust />" "global" />
        <cms:set body_clr="<cms:show ccs_gl_site_body_clr_cust />" "global" />
        <cms:set body_bg="<cms:show ccs_gl_site_body_bg_cust />" "global" /> 
        
    <cms:else />
        <cms:set success="#4CD964" "global" />
        <cms:set info="#2EB7F5" "global" />
        <cms:set warning="#FF9500" "global" />
        <cms:set danger="#FF3B30" "global" />
        <cms:set light="#fafafa" "global" />
        <cms:set dark="#0c151a" "global" />
        <cms:set white="#fff" "global" />
        <cms:set black="#000" "global" />

        <cms:set body_clr="#292b2c" "global" />
        <cms:set body_bg="#fff" "global" />

        <cms:if ccs_gl_site_thm_opt == 'primavera'>
            <cms:set primary="#FF8E9C" "global" />
            <cms:set secondary="#85D3A9" "global" />
            <cms:set tertiary="#A3D5FF" "global" />
            <cms:set quaternary="#FFE8A1" "global" />
            <cms:if ccs_gl_site_thm_clr_opt != '1'>
                <cms:set body_bg="#FFF0F5" "global" />
                <cms:set body_clr="#2C3E50" "global" />
            </cms:if>
            
        <cms:else_if ccs_gl_site_thm_opt == 'estate' />
            <cms:set primary="#FF9F1C" "global" />
            <cms:set secondary="#2EC4B6" "global" />
            <cms:set tertiary="#E71D36" "global" />
            <cms:set quaternary="#FFBF69" "global" />
            <cms:if ccs_gl_site_thm_clr_opt != '1'>
                <cms:set body_bg="#F0F8FF" "global" />
                <cms:set body_clr="#011627" "global" />
            </cms:if>
            
        <cms:else_if ccs_gl_site_thm_opt == 'autunno' />
            <cms:set primary="#D95D39" "global" />
            <cms:set secondary="#F0A202" "global" />
            <cms:set tertiary="#826251" "global" />
            <cms:set quaternary="#A89C94" "global" />
            <cms:if ccs_gl_site_thm_clr_opt != '1'>
                <cms:set body_bg="#EFE8E0" "global" />
                <cms:set body_clr="#3A2318" "global" />
            </cms:if>
            
        <cms:else_if ccs_gl_site_thm_opt == 'inverno' />
            <cms:set primary="#3A86FF" "global" />
            <cms:set secondary="#8ECAE6" "global" />
            <cms:set tertiary="#4A4E69" "global" />
            <cms:set quaternary="#C1D3FE" "global" />
            <cms:if ccs_gl_site_thm_clr_opt != '1'>
                <cms:set body_bg="#E2EAF2" "global" />
                <cms:set body_clr="#1A252C" "global" />
            </cms:if>
		<cms:else_if ccs_gl_site_thm_opt == 'scuro' />
            <cms:set primary="#00ADB5" "global" />
            <cms:set secondary="#393E46" "global" />
            <cms:set tertiary="#5C6B73" "global" />
            <cms:set quaternary="#9DB2BF" "global" />
            <cms:set success="#2ECC71" "global" />
            <cms:set info="#3498DB" "global" />
            <cms:set warning="#F1C40F" "global" />
            <cms:set danger="#E74C3C" "global" />
            <cms:set light="#EAEAEA" "global" />
            <cms:set dark="#15181C" "global" />
            
            <cms:if ccs_gl_site_thm_clr_opt != '1'>
                <cms:set body_bg="#222831" "global" />
                <cms:set body_clr="#EEEEEE" "global" />
            </cms:if>
            
        <cms:else_if ccs_gl_site_thm_opt == 'notte' />
            <cms:set primary="#66FCF1" "global" />
            <cms:set secondary="#45A29E" "global" />
            <cms:set tertiary="#7B2CBF" "global" />
            <cms:set quaternary="#E0AAFF" "global" />
            <cms:set success="#00FF7F" "global" />
            <cms:set info="#00BFFF" "global" />
            <cms:set warning="#FFD700" "global" />
            <cms:set danger="#FF003F" "global" />
            <cms:set light="#F0F0F0" "global" />
            <cms:set dark="#050505" "global" />
            
            <cms:if ccs_gl_site_thm_clr_opt != '1'>
                <cms:set body_bg="#0B0C10" "global" />
                <cms:set body_clr="#C5C6C7" "global" />
            </cms:if>
            
        <cms:else />
            <cms:set primary="#007AFF" "global" />
            <cms:set secondary="#292b2c" "global" />
            <cms:set tertiary="#687BD9" "global" />
            <cms:set quaternary="#68C2D9" "global" />
            
            <cms:if ccs_gl_site_thm_opt == 'dark'>
                <cms:set body_clr="#e1e1e1" "global" />
                <cms:set body_bg="#404040" "global" />
            </cms:if>
        </cms:if>
    </cms:if>

    <cms:set gray-100="#fafafa" "global" />
    <cms:set gray-200="#f2f2f2" "global" />
    <cms:set gray-300="#e1e1e1" "global" />
    <cms:set gray-400="#bebebe" "global" />
    <cms:set gray-500="#949494" "global" />
    <cms:set gray-600="#7F7F7F" "global" />
    <cms:set gray-700="#6a6a6a" "global" />
    <cms:set gray-800="#555555" "global" />
    <cms:set gray-900="#404040" "global" />
    <cms:set gray-1000="#2b2b2b" "global" />
    <cms:set gray-1100="#0c151a" "global" />
    <cms:set gray-1200="#292b2c" "global" />

	<cms:if ccs_gl_site_custom_font_opt >
       <cms:set bff="'<cms:show ccs_gl_site_font_body_cust />', sans-serif" "global" />
       <cms:set fss="'<cms:show ccs_gl_site_font_sans_cust />', sans-serif" "global" />
       <cms:set fmn="'<cms:show ccs_gl_site_font_mono_cust />', monospace" "global" />
       <cms:set fsf="'<cms:show ccs_gl_site_font_serf_cust />', serif" "global" />
       <cms:set fcs="'<cms:show ccs_gl_site_font_crsv_cust />', cursive" "global" />
       <cms:set fdc="'<cms:show ccs_gl_site_font_decr_cust />', sans-serif" "global" />
       <cms:set bfs="<cms:show ccs_gl_site_font_size_cust />" "global" />
       <cms:set bfw="<cms:show ccs_gl_site_font_weight_cust />" "global" />
       <cms:set blh="<cms:show ccs_gl_site_line_height_cust />" "global" />
       
    <cms:else_if ccs_gl_site_thm_typo_opt />
        <cms:if ccs_gl_site_thm_opt == 'primavera'>
            <cms:set bff="'Lato', sans-serif" "global" />
            <cms:set fss="'Lato', sans-serif" "global" />
            <cms:set fsf="'Playfair Display', serif" "global" />
        <cms:else_if ccs_gl_site_thm_opt == 'estate' />
            <cms:set bff="'Montserrat', sans-serif" "global" />
            <cms:set fss="'Montserrat', sans-serif" "global" />
            <cms:set fsf="'Cinzel', serif" "global" />
        <cms:else_if ccs_gl_site_thm_opt == 'autunno' />
            <cms:set bff="'Open Sans', sans-serif" "global" />
            <cms:set fss="'Open Sans', sans-serif" "global" />
            <cms:set fsf="'Merriweather', serif" "global" />
        <cms:else_if ccs_gl_site_thm_opt == 'inverno' />
            <cms:set bff="'Roboto', sans-serif" "global" />
            <cms:set fss="'Roboto', sans-serif" "global" />
            <cms:set fsf="'Lora', serif" "global" />

        <cms:ignore>
            THE else IS NOT OPTIONAL, AND ITS ABSENCE WAS A REAL BUG.

            The theme dropdown offers EIGHT values - light, dark, primavera,
            estate, autunno, inverno, scuro, notte - and this chain handled
            four. With theme typography switched on and any of the other four
            selected, bff, fss and fsf were never assigned at all.

            That does not fail loudly. It produces

                .font-sans-serif { font-family:  !important; }

            an empty font-family, which is invalid, so the browser drops the
            declaration and the element inherits. Meanwhile fmn, fcs and fdc
            below ARE set, because they sit outside this chain - so three font
            roles kept working and three silently stopped, decided by which
            COLOUR theme happened to be chosen. Nothing in the panel connects
            those two things.

            light, dark, scuro and notte are colour themes and were never meant
            to carry typography, so they fall back to the site default stack -
            the same one the no-theme branch uses further down.
        </cms:ignore>
        <cms:else />
            <cms:set bff='"Montserrat", -apple-system, sans-serif' "global" />
            <cms:set fss='"Raleway", -apple-system, sans-serif' "global" />
            <cms:set fsf='"Playfair Display", Georgia, serif' "global" />
        </cms:if>
        
        <cms:set fmn='"SFMono-Regular", Menlo, Monaco, Consolas, monospace' "global" />
        <cms:set fcs='"Dancing Script", cursive' "global" />
        <cms:set fdc='"Oswald", sans-serif' "global" />
        <cms:set bfs="0.875" "global" />
        <cms:set bfw="400" "global" />
        <cms:set blh="1.5" "global" />

    <cms:else />
       <cms:set bff='"Montserrat", -apple-system, sans-serif' "global" />
       <cms:set fss='"Raleway", -apple-system, sans-serif' "global" />
       <cms:set fmn='"SFMono-Regular", Menlo, Monaco, Consolas, monospace' "global" />
       <cms:set fsf='"Playfair Display", Georgia, serif' "global" />
       <cms:set fcs='"Bradley Hand", "Brush Script MT", cursive' "global" />
       <cms:set fdc='"Sreda", Arvo, Candara' "global" />
       <cms:set bfs="0.875" "global" />
       <cms:set bfw="400" "global" />
       <cms:set blh="1.5" "global" />
    </cms:if>
    
    <cms:set ccs_gl_site_nav_logo_txt_clr="<cms:show ccs_gl_site_name_txt_clr />" "global" />
    <cms:set ccs_gl_site_nav_logo_txt_sz="<cms:show ccs_gl_site_name_txt_sz />" "global" />

    <cms:set ccs_site_nav_mnu_dd_txt_clr="<cms:show ccs_gl_site_nav_dd_txt_clr />" "global" />
    <cms:set ccs_site_nav_mnu_dd_bg="<cms:show ccs_gl_site_nav_dd_bg />" "global" />
    <cms:set ccs_site_nav_mnu_dd_hvr_clr="<cms:show ccs_gl_site_nav_dd_hvr_clr />" "global" />
    <cms:set ccs_site_nav_mnu_dd_hvr_bg="<cms:show ccs_gl_site_nav_dd_hvr_bg />" "global" />

    <cms:set ccs_site_nav_actv_clr="<cms:show ccs_gl_site_nav_actv_clr />"  "global" />
    <cms:set ccs_site_nav_hvr_clr="<cms:show ccs_gl_site_nav_hvr_clr />"  "global" />

    <cms:ignore>
        Footer and utility-bar link hover colours. Both fall back to the
        site-wide hover colour when left on Default.
    </cms:ignore>
    <cms:set ccs_site_ftr_hvr_clr="<cms:show ccs_gl_site_ftr_lnk_hvr_clr />"  "global" />
    <cms:if ccs_site_ftr_hvr_clr eq ''>
        <cms:set ccs_site_ftr_hvr_clr="<cms:show ccs_gl_site_nav_hvr_clr />"  "global" />
    </cms:if>

    <cms:set ccs_site_ubr_hvr_clr="<cms:show ccs_gl_site_ubr_lnk_hvr_clr />"  "global" />
    <cms:if ccs_site_ubr_hvr_clr eq ''>
        <cms:set ccs_site_ubr_hvr_clr="<cms:show ccs_gl_site_nav_hvr_clr />"  "global" />
    </cms:if>

    <cms:set ccs_site_slct_clr="<cms:show ccs_gl_site_hglt_clr />" "global" />
    <cms:set ccs_site_slct_bg="<cms:show ccs_gl_site_hglt_bg />" "global" />

    <cms:set ccs_site_nav_mnu_ttl_sz="1rem" "global" />
    <cms:set ccs_site_nav_mnu_ttl_wt="400" "global" />
    <cms:set ccs_site_nav_mnu_ttl_clr="dark" "global" />
</cms:pages>

<cms:php>
    function hexToRgbStr($hex) {
        $hex = ltrim($hex, '#');
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } elseif (strlen($hex) >= 6) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        } else {
            return '0, 0, 0';
        }
        return $r . ', ' . $g . ', ' . $b;
    }

    global $CTX;
    $colors = ['primary', 'secondary', 'tertiary', 'quaternary', 'success', 'info', 'warning', 'danger', 'light', 'dark', 'white', 'black', 'body_clr', 'body_bg'];

    foreach ($colors as $color) {
        $hex = $CTX->get($color);
        if ($hex) {
            $CTX->set($color . '_rgb', hexToRgbStr($hex), 'global');
        }
    }
</cms:php>

:root {
    --ccs-primary: <cms:show primary />;
    --ccs-primary-rgb: <cms:show primary_rgb />;
    
    --ccs-secondary: <cms:show secondary />;
    --ccs-secondary-rgb: <cms:show secondary_rgb />;
    
    --ccs-tertiary: <cms:show tertiary />;
    --ccs-tertiary-rgb: <cms:show tertiary_rgb />;
    
    --ccs-quaternary: <cms:show quaternary />;
    --ccs-quaternary-rgb: <cms:show quaternary_rgb />;
    
    --ccs-success: <cms:show success />;
    --ccs-success-rgb: <cms:show success_rgb />;
    
    --ccs-info: <cms:show info />;
    --ccs-info-rgb: <cms:show info_rgb />;
    
    --ccs-warning: <cms:show warning />;
    --ccs-warning-rgb: <cms:show warning_rgb />;
    
    --ccs-danger: <cms:show danger />;
    --ccs-danger-rgb: <cms:show danger_rgb />;
    
    --ccs-light: <cms:show light />;
    --ccs-light-rgb: <cms:show light_rgb />;
    
    --ccs-dark: <cms:show dark />;
    --ccs-dark-rgb: <cms:show dark_rgb />;
    
    --ccs-white: <cms:show white />;
    --ccs-white-rgb: <cms:show white_rgb />;
    
    --ccs-black: <cms:show black />;
    --ccs-black-rgb: <cms:show black_rgb />;
    
	--ccs-body-color: <cms:show body_clr />;
    --ccs-body-bg: <cms:show body_bg />;

  	--bs-body-color: var(--ccs-body-color);
    --bs-heading-color: var(--ccs-body-color);
    --bs-body-bg: var(--ccs-body-bg);

    --ccs-body-color-rgb: <cms:show body_clr_rgb />;
    --ccs-body-bg-rgb: <cms:show body_bg_rgb />;

    --ccs-100: <cms:show gray-100 />;
    --ccs-200: <cms:show gray-200 />;
    --ccs-300: <cms:show gray-300 />;
    --ccs-400: <cms:show gray-400 />;
    --ccs-500: <cms:show gray-500 />;
    --ccs-600: <cms:show gray-600 />;
    --ccs-700: <cms:show gray-700 />;
    --ccs-800: <cms:show gray-800 />;
    --ccs-900: <cms:show gray-900 />;
    --ccs-1000: <cms:show gray-1000 />;
    --ccs-1100: <cms:show gray-1100 />;
    --ccs-1200: <cms:show gray-1200 />;
    
    --ccs-body-font-family: <cms:show bff />;
    --ccs-font-sans-serif: <cms:show fss />;
    --ccs-font-monospace: <cms:show fmn />;
    
    --ccs-font-serif: <cms:show fsf />;
    --ccs-font-cursive: <cms:show fcs />;
    --ccs-font-decorate: <cms:show fdc />;

    --ccs-body-font-size: <cms:show bfs />rem;
    --ccs-body-font-weight: <cms:show bfw />;
    --ccs-body-line-height: <cms:show blh />;

    --ccs-navbar-active-color: var(--ccs-<cms:show ccs_site_nav_actv_clr />);
    --ccs-link-hover-color: var(--ccs-<cms:show ccs_site_nav_hvr_clr />);
    --ccs-dropdown-color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_txt_clr />);
    /* theme.css:5169 - .dropdown-item reads THIS variable for its text colour,
       not --ccs-dropdown-color above. --ccs-dropdown-color applies to the menu,
       and the item's own colour declaration overrides what it inherits, so the
       line above alone never reached the text. Both are set now: the menu for
       anything that inherits, the link for the items themselves. */
    --ccs-dropdown-link-color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_txt_clr />);
    --ccs-dropdown-bg: var(--ccs-<cms:show ccs_site_nav_mnu_dd_bg />);
    --ccs-dropdown-link-hover-color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_hvr_clr />);
    --ccs-dropdown-link-hover-bg: var(--ccs-<cms:show ccs_site_nav_mnu_dd_hvr_bg />);

    <cms:if bg_grd="1" >
        <cms:if swap_bg_clr >
            --ccs-bg-gradient: linear-gradient(<cms:show bg_splt />deg, <cms:show bg_clr2 />, <cms:show bg_clr1 />) !important;
        <cms:else />
            --ccs-bg-gradient: linear-gradient(<cms:show bg_splt />deg, <cms:show bg_clr1 />, <cms:show bg_clr2 />) !important;
        </cms:if>
    <cms:else />
        --ccs-bg-gradient: linear-gradient(0deg,<cms:show bg_clr1 />, <cms:show bg_clr1 />) !important;
    </cms:if>

    <cms:if ol_grd="1" >
        <cms:if swap_ol_clr >
            --ccs-ol-gradient:linear-gradient(<cms:show ol_splt />deg, <cms:show ol_clr2 />, <cms:show ol_clr1 />) !important;
        <cms:else />
            --ccs-ol-gradient:linear-gradient(<cms:show ol_splt />deg, <cms:show ol_clr1 />, <cms:show ol_clr2 />) !important;
        </cms:if>
    <cms:else />
        --ccs-ol-gradient:linear-gradient(0deg, <cms:show ol_clr1 />, <cms:show ol_clr1 />) !important;
    </cms:if>
}

body {
    background-color: var(--ccs-body-bg);
    color: var(--ccs-body-color);
}

h1, h2, h3, h4, h5, h6, p {
    color: var(--ccs-body-color);
}

.hr-sm {
  width: 14rem;
  margin: 1.25rem auto;
}
.hr-med {
  width: 28rem;
  margin: 1.25rem auto;
}
.hr-long {
  width: 48rem;
  margin: 1.25rem auto;
}

/* Divider rules used by the pagebuilder blocks.
   Bootstrap ships <hr> at opacity .25 so an uncoloured divider reads as soft
   grey. That washes out an explicitly chosen colour - text-danger at 25% is
   pink, not red - so any hr driven by a colour dropdown gets full opacity and
   the chosen colour renders true. */
.nswp-hr {
  opacity: 1 !important;
}

/* hr THICKNESS - independent of the length classes above.
   An <hr> draws as a border-top, so weight is border-top-width, not padding. */
/* Full width - needed a real class because an empty value collided with the
   "no line" option, and a select silently picks the LAST match. */
.hr-full {
  width: 100%;
  margin-left: auto;
  margin-right: auto;
}

.hr-w1 {
  border-top-width: 1px !important;
}
.hr-w3 {
  border-top-width: 3px !important;
}
.hr-w5 {
  border-top-width: 5px !important;
}

/*-----------------------------------------------
|   Font family
-----------------------------------------------*/
.font-sans-serif {
  font-family: <cms:show fss /> !important;
}

.font-monospace {
  font-family: <cms:show fmn /> !important;
}
.font-serif {
  font-family: <cms:show fsf /> !important;
}
.font-curs {
  font-family:<cms:show fcs /> !important ;
}
.font-decor {
  font-family: <cms:show fdc /> !important;
}

body {
  font-family: <cms:show bff />;
  font-size: <cms:show bfs />rem;
  font-weight: <cms:show bfw />;
  line-height: <cms:show blh />;
  color: <cms:show body_clr />;
  background-color: <cms:show body_bg />;
  -webkit-text-size-adjust: 100%;
}

h6, .h6, h5, .h5, h4, .h4, h3, .h3, h2, .h2, h1, .h1 {
  color: <cms:show body_clr />;
  font-family: <cms:show bff /> ;
}

/*-----------------------------------------------
|   Link Colors
-----------------------------------------------*/
a {
  color: <cms:show body_clr />;
}

.link-white {
  color: RGBA(var(--ccs-white-rgb), var(--ccs-link-opacity, 1)) !important;
  -webkit-text-decoration-color: RGBA(var(--ccs-white-rgb), var(--ccs-link-underline-opacity, 1)) !important;
  text-decoration-color: RGBA(var(--ccs-white-rgb), var(--ccs-link-underline-opacity, 1)) !important;
}
.link-light:hover, .link-white:focus {
  color: RGBA(255, 255, 255, var(--ccs-link-opacity, 1)) !important;
  -webkit-text-decoration-color: RGBA(255, 255, 255, var(--ccs-link-underline-opacity, 1)) !important;
  text-decoration-color: RGBA(255, 255, 255, var(--ccs-link-underline-opacity, 1)) !important;
}

.link-black {
  color: RGBA(var(--ccs-black-rgb), var(--ccs-link-opacity, 1)) !important;
  -webkit-text-decoration-color: RGBA(var(--ccs-black-rgb), var(--ccs-link-underline-opacity, 1)) !important;
  text-decoration-color: RGBA(var(--ccs-black-rgb), var(--ccs-link-underline-opacity, 1)) !important;
}
.link-black:hover, .link-black:focus {
  color: RGBA(0, 0, 0, var(--ccs-link-opacity, 1)) !important;
  -webkit-text-decoration-color: RGBA(0, 0, 0, var(--ccs-link-underline-opacity, 1)) !important;
  text-decoration-color: RGBA(0, 0, 0, var(--ccs-link-underline-opacity, 1)) !important;
}

/*-----------------------------------------------
|   Button Colors
-----------------------------------------------*/
.btn-black {
  color: var(--ccs-gray-200);
  background-color: #000;
  border-color: #000;
}
.btn-black:hover, .btn-black:focus {
  color: var(--ccs-gray-200);
  background-color: #060606;
  border-color: #0e0e0e;
}

.btn-outline-black {
  color: #000;
  background-image: none;
  background-color: transparent;
  border-color: #000;
}
.btn-outline-black.btn-icon span {
  border-color: #000;
  background-color: transparent;
  -webkit-transition: background-color 0.2s ease-in-out;
  transition: background-color 0.2s ease-in-out;
}
.btn-outline-black.btn-icon:hover span, .btn-outline-black.btn-icon:focus span, .btn-outline-black.btn-icon:active span, .btn-outline-black.btn-icon:active:focus span {
  background-color: rgba(12, 21, 26, 0.1);
  border-color: transparent;
}
.btn-outline-black:hover {
  color: #fff;
  background-color: #000;
  border-color: #000;
}
.btn-outline-black:focus, .btn-outline-black.focus {
  color: #fff;
  background-color: #000;
  border-color: #000;
}
.btn-outline-black:active, .btn-outline-black.active, .open > .btn-outline-black.dropdown-toggle {
  color: #fff;
  background-color: #000;
  border-color: #000;
}
.btn-outline-black:active:hover, .btn-outline-black:active:focus, .btn-outline-black:active.focus, .btn-outline-black.active:hover, .btn-outline-black.active:focus, .btn-outline-black.active.focus, .open > .btn-outline-black.dropdown-toggle:hover, .open > .btn-outline-black.dropdown-toggle:focus, .open > .btn-outline-black.dropdown-toggle.focus {
  color: #fff;
  background-color: black;
  border-color: black;
}
.btn-outline-black.active {
  background-color: black;
  border-color: black;
}
.btn-outline-black.disabled:focus, .btn-outline-black.disabled.focus, .btn-outline-black:disabled:focus, .btn-outline-black:disabled.focus {
  border-color: #2c4d60;
}
.btn-outline-black.disabled:hover, .btn-outline-black:disabled:hover {
  border-color: #2c4d60;
}

/*-----------------------------------------------
|   Navigation Menu
-----------------------------------------------*/
.nav-link:hover, .nav-link:focus {
  color: var(--ccs-nav-link-hover-color);
}

.navbar {
  /* Was ccs_site_nav_hvr_clr - the HOVER color - which silently overrode the
     ACTIVE color set on this same variable at :root above. Two settings, one
     variable, and the later one won, so "Menu Item Color on Active Page" could
     never take effect even once the active class started rendering. */
  --ccs-navbar-active-color: var(--ccs-<cms:show ccs_site_nav_actv_clr />);
}

.navbar-nav {
  --ccs-nav-link-font-size: <cms:show ccs_site_nav_mnu_ttl_sz />;
  --ccs-nav-link-font-weight:  <cms:show ccs_site_nav_mnu_ttl_wt />;
  --ccs-nav-link-color: var(--ccs-<cms:show ccs_site_nav_mnu_ttl_clr />);
  --ccs-nav-link-hover-color: var(--ccs-link-hover-color);
}

.navbar-nav .nav-link.active, .navbar-nav .nav-link.show {
  color: var(--ccs-navbar-active-color);
}

/* The .dropdown-item colour rule that used to sit here has been removed, and
   the reason it existed at all is fixed at its source in the :root block above.

   theme.css:5169 reads --ccs-dropdown-link-color for the item's text colour.
   :root here was setting --ccs-dropdown-color, which theme.css applies to the
   MENU, not the item - and .dropdown-item's own colour declaration overrides
   what it would have inherited. So the global setting never reached the item,
   and this rule was added to force it, with !important.

   That !important then beat every .text-* utility, because theme.css compiles
   its colour utilities WITHOUT !important (theme.css:11617). Result: the
   per-navbar text colour and the per-item text-<...> class were both in the
   HTML and both lost, on every dropdown item in every navbar.

   With the correct variable set above, the global setting is the DEFAULT and a
   text-* utility overrides it - .text-primary at theme.css:11617 comes after
   .dropdown-item at :5163 in the same file, so it wins on order at equal
   specificity, with no !important needed on either side. */
.dropdown-menu {
  background-color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_bg />) !important;
}
.dropdown-item-wrapper:hover, .dropdown-item-wrapper:focus {
  color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_hvr_clr />) !important;
}
.dropdown-item:hover {
  color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_hvr_clr />) !important;
  background-color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_hvr_bg />) !important;
}
.nav-link.active {
   color: var(--ccs-<cms:show ccs_site_nav_actv_clr />) !important;
}
.nav-item:hover,
.nav-link:hover {
  color: var(--ccs-<cms:show ccs_site_nav_hvr_clr />) !important;
}

<cms:if ccs_site_ftr_hvr_clr ne ''>
/* Footer links. Beats the hardcoded .link-COLOUR:hover ramp in theme.css
   on specificity (0,2,1 vs 0,2,0), so it wins regardless of load order. */
.ccs-footer a:hover,
.ccs-footer a:focus,
.ccs-footer .btn-link:hover,
.ccs-footer .btn-link:focus {
  color: var(--ccs-<cms:show ccs_site_ftr_hvr_clr />) !important;
  text-decoration-color: var(--ccs-<cms:show ccs_site_ftr_hvr_clr />) !important;
}
</cms:if>

<cms:if ccs_site_ubr_hvr_clr ne ''>
/* Utility bar links. */
.ccs-utlbar a:hover,
.ccs-utlbar a:focus,
.ccs-utlbar .btn-link:hover,
.ccs-utlbar .btn-link:focus {
  color: var(--ccs-<cms:show ccs_site_ubr_hvr_clr />) !important;
  text-decoration-color: var(--ccs-<cms:show ccs_site_ubr_hvr_clr />) !important;
}
</cms:if>

::selection {
  background-color: var(--ccs-<cms:show ccs_site_slct_bg />) !important;
  color: var(--ccs-<cms:show ccs_site_slct_clr />) !important;
}

<cms:if ol_grd="1" >
    <cms:if swap_ol_clr >
    .bg-holder.overlay-gradient:before {
      background: linear-gradient(<cms:show ol_splt />deg, <cms:show ol_clr2 />, <cms:show ol_clr1 />) !important;
    }
    <cms:else />
    .bg-holder.overlay-gradient:before {
      background: linear-gradient(<cms:show ol_splt />deg, <cms:show ol_clr1 />, <cms:show ol_clr2 />) !important;
    }
    </cms:if>
<cms:else />
    .bg-holder.overlay-gradient:before {
      background: linear-gradient(0deg, <cms:show ol_clr1 />, <cms:show ol_clr1 />) !important;
    }
</cms:if>

#tpb_mnu.sticky-top {
  z-index: 1021 !important ;
}

.navbar .dropdown-menu::before {
    content: "";
    position: absolute;
    top: -25px;
    left: 0;
    right: 0;
    height: 25px;
    background: transparent;
    z-index: -1;
}

.navbar .dropdown-menu {
    margin-top: -5px !important;
}

.navbar .dropdown-menu .dropdown-menu {
    margin-top: 0 !important;
    margin-left: -2px !important; 
}

@media (min-width: 992px) {
    .navbar .navbar-nav .nav-item.dropdown {
        padding-bottom: 25px !important;
        margin-bottom: -25px !important;
    }
    .navbar .navbar-nav .dropdown-menu {
        margin-top: -12px !important;
    }
    .navbar .dropdown-menu .dropdown {
        padding-right: 20px !important;
        margin-right: -20px !important;
    }
}

/* -------------------------------------------------------------------------- */
/* OUTLINE (FLOATING BORDER) EXPANSION PACK                                   */
/* -------------------------------------------------------------------------- */

/* 1. Outline Styles */
.outline-solid { outline-style: solid !important; }
.outline-dotted { outline-style: dotted !important; }
.outline-dashed { outline-style: dashed !important; }
.outline-double { outline-style: double !important; }
.outline-none { outline-style: none !important; }

/* 2. Outline Widths (Updated to match border-size.htm) */
.outline-medium { outline-width: medium !important; }
.outline-thin { outline-width: thin !important; }
.outline-thick { outline-width: thick !important; }
.outline-1px { outline-width: 1px !important; }
.outline-2px { outline-width: 2px !important; }
.outline-3px { outline-width: 3px !important; }
.outline-4px { outline-width: 4px !important; }
.outline-5px { outline-width: 5px !important; }
.outline-6px { outline-width: 6px !important; }
.outline-8px { outline-width: 8px !important; }
.outline-10px { outline-width: 10px !important; }
.outline-12px { outline-width: 12px !important; }

/* 3. Outline Offsets (Positive / Outward) */
.outline-offset-0 { outline-offset: 0 !important; }
.outline-offset-25 { outline-offset: 0.25rem !important; }
.outline-offset-50 { outline-offset: 0.50rem !important; }
.outline-offset-75 { outline-offset: 0.75rem !important; }

/* 4. Outline Offsets (Negative / Inward) */
.outline-offset--25 { outline-offset: -0.25rem !important; }
.outline-offset--50 { outline-offset: -0.50rem !important; }
.outline-offset--75 { outline-offset: -0.75rem !important; }

/* 5. Outline Colors */
.outline-primary { outline-color: var(--ccs-primary) !important; }
.outline-secondary { outline-color: var(--ccs-secondary) !important; }
.outline-tertiary { outline-color: var(--ccs-tertiary) !important; }
.outline-quaternary { outline-color: var(--ccs-quaternary) !important; }
.outline-success { outline-color: var(--ccs-success) !important; }
.outline-info { outline-color: var(--ccs-info) !important; }
.outline-warning { outline-color: var(--ccs-warning) !important; }
.outline-danger { outline-color: var(--ccs-danger) !important; }
.outline-light { outline-color: var(--ccs-light) !important; }
.outline-dark { outline-color: var(--ccs-dark) !important; }
.outline-white { outline-color: var(--ccs-white) !important; }
.outline-black { outline-color: var(--ccs-black) !important; }


/* -------------------------------------------------------------------------- */
/* BACKGROUND OPACITY EXPANSION PACK (DECIMAL ESCAPES)                        */
/* -------------------------------------------------------------------------- */

.bg-opacity-0\.0 { --ccs-bg-opacity: 0.0 !important; }
.bg-opacity-0\.1 { --ccs-bg-opacity: 0.1 !important; }
.bg-opacity-0\.2 { --ccs-bg-opacity: 0.2 !important; }
.bg-opacity-0\.25 { --ccs-bg-opacity: 0.25 !important; }
.bg-opacity-0\.3 { --ccs-bg-opacity: 0.3 !important; }
.bg-opacity-0\.4 { --ccs-bg-opacity: 0.4 !important; }
.bg-opacity-0\.5 { --ccs-bg-opacity: 0.5 !important; }
.bg-opacity-0\.6 { --ccs-bg-opacity: 0.6 !important; }
.bg-opacity-0\.7 { --ccs-bg-opacity: 0.7 !important; }
.bg-opacity-0\.75 { --ccs-bg-opacity: 0.75 !important; }
.bg-opacity-0\.8 { --ccs-bg-opacity: 0.8 !important; }
.bg-opacity-0\.9 { --ccs-bg-opacity: 0.9 !important; }
.bg-opacity-1\.0 { --ccs-bg-opacity: 1.0 !important; }
.bg-opacity-1 { --ccs-bg-opacity: 1 !important; }

/* -------------------------------------------------------------------------- */
/* CUSTOM Z-INDEX OVERRIDES                                                   */
/* -------------------------------------------------------------------------- */
.z-10 { z-index: 10 !important; }


/* -------------------------------------------------------------------------- */
/* STRUCTURAL OVERRIDES (Site Pages only)                                    */
/* -------------------------------------------------------------------------- */
<cms:if (target_template_order ge '1000' && target_template_order le '1999') || target_template_name == 'index.php'>
/* Removes the hardcoded 100vh quota so modular sections snap together */
.content {
    min-height: unset !important;
}
</cms:if>





/* -------------------------------------------------------------------------- */
/* SHOPPING CART OVERRIDES                                   */
/* -------------------------------------------------------------------------- */
.custom-variants table { margin: 0; }
.custom-variants table td { padding: 0 10px 0 0; }
.custom-variants table select, 
.custom-variants table input[type="text"] {
    display: block;
    width: 100%;
    padding: .375rem 2.25rem .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: .375rem;
}

/* Swiper Image Aspect Ratio Fix */
.product-slider .swiper-slide img {
    aspect-ratio: 1 / 1 !important;
    object-fit: contain !important;
    width: 100% !important;
    background-color: transparent !important;
}


/* Pulsating Loading Dot */
.ajax-nav-link {
    position: relative;
    transition: color 0.2s ease;
}

.ajax-nav-link.loading::after {
    content: '';
    display: inline-block;
    width: 8px;
    height: 8px;
    margin-left: 8px;
    background-color: currentColor;
    border-radius: 50%;
    animation: pulse-dot 1s infinite ease-in-out;
    vertical-align: middle;
}

@keyframes pulse-dot {
    0% {
        transform: scale(0.6);
        opacity: 0.4;
    }
    50% {
        transform: scale(1.2);
        opacity: 1;
    }
    100% {
        transform: scale(0.6);
        opacity: 0.4;
    }
}

/* Scrollable category list in the nav panel.
   The 350px was previously an inline style with no relation to anything - it
   coincidentally matched _pb_height, which only sizes the ADMIN preview window.
   Kept as a cap so a long list cannot stretch the section, but expressed here
   so it is themeable, and bounded by viewport height on short screens. */

/* ------------------------------------------------------------------
   Nav height follows the slider.

   The earlier attempt used height:100% and failed: that resolves against
   the parent's SPECIFIED height, and .col-lg-3 gets its height from flex
   stretch, so its specified height stays auto and the chain never resolves.
   A flex-only chain fails the other way - in a flex row the tallest item
   sets the row height, so a long nav would push the section taller.

   Absolute-fill solves both at once. .col-lg-3 already carries
   position-relative, so inset:0 on its inner row (a) takes the nav out of
   flow, leaving the slider alone to set row height, and (b) gives the nav a
   definite height to resolve against. Flex the chain inside it, with
   min-height:0 at every level or overflow never engages.

   lg and up only: below that the columns stack, there is no slider beside
   the nav to match, and the max-height cap below applies instead.
   ------------------------------------------------------------------ */
/* ------------------------------------------------------------------
   Category image stacking.

   theme.css gives .bg-holder z-index:-1, which only works when no ancestor
   paints a background between it and the nearest stacking context. A tile
   with a section background (a colour or gradient on the <section>) paints
   straight over the image, so the same markup showed the image on one tile
   and not on another - it tracked whether a section background was set, not
   the listing mode.

   Fixed by making the nav column its own stacking context and keeping the
   image inside it at a non-negative level, with the nav content one above.
   Scoped to .nswp-nav-col so every other .bg-holder on the site is untouched.
   ------------------------------------------------------------------ */
.nswp-nav-col {
    position: relative;
    z-index: 0;
    isolation: isolate;
}
.nswp-nav-col .bg-holder {
    z-index: 0;
}
.nswp-nav-col > .row > .col-12 {
    position: relative;
    z-index: 1;
    /* Redundant gutter padding. .nswp-nav-inner's mx-3 already insets the panel
       from the image edge, so this second layer only narrowed the label. */
    padding-left: 0;
    padding-right: 0;
}

/* ------------------------------------------------------------------
   NAV LABEL WIDTH

   These are .btn-link only so a button can look like a link (they filter in
   place, they never navigate). That also drags in the theme's button metrics,
   where --ccs-btn-padding-x is 2.5rem - sized for wide call-to-action buttons,
   not for labels in a quarter-width column. At two sides that is 5rem of the
   column gone, which is what forced short category names onto two lines.

   Overriding the variable rather than the padding keeps the theme's own
   mechanism intact, and leaves the level-N indent rules (which set padding-left
   directly) working exactly as before.
   ------------------------------------------------------------------ */
.nswp-nav-col .ajax-nav-link {
    --ccs-btn-padding-x: 0.25rem;
}

@media (min-width: 992px) {
    .nswp-nav-col > .row {
        position: absolute;
        /* top right bottom left - the negative bottom lets the panel hang a
           little below the tiles as a deliberate tail, rather than ending
           dead flush. It is out of flow, so this cannot affect row height. */
        /* Inset the sides by half a gutter so the panel and its bg-holder sit
           inside the column's content box, restoring the space between the nav
           and the slider. inset:0 would fill the padding box edge to edge and
           the image would touch the neighbouring column. */
        inset: 0 calc(var(--ccs-gutter-x, 1.875rem) * 0.5) -1.5rem calc(var(--ccs-gutter-x, 1.875rem) * 0.5);
        /* .row carries negative side margins (-0.5 * gutter) to cancel the
           gutters of its columns. Absolutely positioning it means left:0 /
           right:0 anchor the margin box, so those negatives push the row - and
           the full-width .bg-holder inside it - about 15px past the column on
           BOTH sides, overlapping the slider. Nothing cancels them here, so
           they are zeroed. The .col-12 keeps its own gutter padding, so inner
           spacing is unchanged. */
        margin-left: 0;
        margin-right: 0;
        display: flex;
        flex-direction: column;
    }
    .nswp-nav-col > .row > .col-12,
    .nswp-nav-inner,
    .nswp-nav-panel {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
    }
    .nswp-nav-panel > .nswp-scroll-container {
        flex: 1 1 auto;
        min-height: 0;
        max-height: none;
    }
}

.nswp-scroll-container {
    max-height: min(350px, 55vh);
    overflow-y: auto;
    /* long category names must wrap, not push the list sideways */
    overflow-x: hidden;
    /* stop the page scrolling once the list hits its end - matters on touch */
    overscroll-behavior: contain;
    scrollbar-width: thin;
    /* Fade the bottom edge so it reads as scrollable. A hard cut-off looks
       like a complete list and people do not scroll what looks finished. */
    -webkit-mask-image: linear-gradient(to bottom, #000 calc(100% - 1.5rem), transparent 100%);
    mask-image: linear-gradient(to bottom, #000 calc(100% - 1.5rem), transparent 100%);
}

/* Deliberately NOT flex. A bare text node in a flex container becomes an
   anonymous flex item, and whether it may shrink below its content width
   depends on min-width:auto resolution - the fragile corner of flexbox, and
   why long category names were clipping instead of wrapping. Normal block
   flow wraps reliably, so the marker is just an inline-block. */
.ajax-nav-link.nswp-folder-link {
    display: block;
    text-align: left;
    white-space: normal;
    /* overflow-wrap alone breaks a word only when it genuinely cannot fit.
       word-break: break-word was also set and is far more eager - it split
       "Equipment &" into "Equipme / nt &" even though a space was available. */
    overflow-wrap: break-word;
    line-height: 1.3;
}

/* Active ("you are here") category in the left nav.
   Deliberately no colour: .btn-link already supplies hover/active colour from
   the site theme, and the link's own colour comes from the block's Nav Link
   Text Color dropdown. Weight + underline are the only affordances added here,
   so this rule never fights a user-chosen colour.
   This is the single definition - the router emits .nswp-active on page load
   and the block's JS toggles the same class on click. */
.ajax-nav-link.nswp-folder-link.nswp-active {
    font-weight: 700;
    text-decoration: underline;
}

/* Top-level folder shown above a slide title when "Show Category On Tiles" is on.
   Uppercased here, never in the data - the folder title keeps its stored case so
   the admin sees normal text and the presentation stays changeable.
   Caps need extra tracking to stay readable at small sizes. */
/* Structural bits with no dropdown equivalent - these always apply. */
.nswp-tile-cat {
    text-transform: uppercase;
    opacity: 0.6;
    margin-bottom: 0.15rem;
}

/* Typographic defaults. :where() carries zero specificity, so the moment the
   user picks a size / weight / spacing / line-height the fs-*, fw-*, ls-* or
   lh-* utility wins outright. Without this the rule would tie with those
   single-class utilities and, loading later than theme.css, silently beat
   them - the control would appear to do nothing. */
:where(.nswp-tile-cat) {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    line-height: 1.2;
}

/* Depth cue for Pages (leaf-only) mode, where ancestor folders are not listed.
   One dot per level above. Decorative only - the real path is on the link's
   aria-label, so this is aria-hidden in the markup. */
.ajax-nav-link .nswp-depth-dots {
    display: inline-block;
    margin-right: 0.25rem;
    opacity: 0.45;
    letter-spacing: 0.12em;
    font-weight: 700;
    flex-shrink: 0;
}

.ajax-nav-link .nswp-folder-marker {
    display: inline-block;
    margin-right: 0.5rem;
    vertical-align: middle;
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    background-color: currentColor;
    opacity: 0.3;
    flex-shrink: 0;
}

.ajax-nav-link.nswp-folder-link.level-0 .nswp-folder-marker {
    opacity: 0;
}

/* Nested category indentation. The router emits level-N from k_level, so the
   depth is however deep the folder tree goes. Levels beyond the last rule here
   simply stop indenting further - they still render. */
.ajax-nav-link.nswp-folder-link.level-1 {
    padding-left: calc(0.75rem * 1) !important;
}

.ajax-nav-link.nswp-folder-link.level-2 {
    padding-left: calc(0.75rem * 2) !important;
}

.ajax-nav-link.nswp-folder-link.level-3 {
    padding-left: calc(0.75rem * 3) !important;
}

.ajax-nav-link.nswp-folder-link.level-4 {
    padding-left: calc(0.75rem * 4) !important;
}

.ajax-nav-link.nswp-folder-link.level-5 {
    padding-left: calc(0.75rem * 5) !important;
}

.ajax-nav-link.nswp-folder-link.level-6 {
    padding-left: calc(0.75rem * 6) !important;
}

.ajax-nav-link.nswp-folder-link.level-7 {
    padding-left: calc(0.75rem * 7) !important;
}

.ajax-nav-link.nswp-folder-link.level-8 {
    padding-left: calc(0.75rem * 8) !important;
}

.ajax-nav-link.nswp-folder-link.level-9 {
    padding-left: calc(0.75rem * 9) !important;
}

.ajax-nav-link.nswp-folder-link.level-10 {
    padding-left: calc(0.75rem * 10) !important;
}




<?php COUCH::invoke(); ?>

/* ------------------------------------------------------------------
   NAV MIRROR - DRIVEN BY BLOCK TEXT ALIGNMENT

   Bootstrap's text-start/center/end handles the alignment itself. What it
   cannot do is flip the depth indent and marker spacing, which are physical
   left/right values - so those follow the chosen alignment here.

   Left alignment uses the rules above unchanged and remains the default.
   Centre drops the indent entirely: an indent read from one edge is
   meaningless when the text is not anchored to that edge.

   Specificity is one class higher than the rules it overrides, so the
   !important indents are beaten without adding more !important than the
   originals already carry.
   ------------------------------------------------------------------ */
.nswp-align-end .ajax-nav-link.nswp-folder-link {
    text-align: right;
}

.nswp-align-end .ajax-nav-link .nswp-folder-marker {
    margin-right: 0;
    margin-left: 0.5rem;
}

.nswp-align-end .ajax-nav-link .nswp-depth-dots {
    margin-right: 0;
    margin-left: 0.25rem;
}

.nswp-align-end .ajax-nav-link.nswp-folder-link.level-1 {
    padding-left: 0 !important;
    padding-right: calc(0.75rem * 1) !important;
}

.nswp-align-end .ajax-nav-link.nswp-folder-link.level-2 {
    padding-left: 0 !important;
    padding-right: calc(0.75rem * 2) !important;
}

.nswp-align-end .ajax-nav-link.nswp-folder-link.level-3 {
    padding-left: 0 !important;
    padding-right: calc(0.75rem * 3) !important;
}

.nswp-align-end .ajax-nav-link.nswp-folder-link.level-4 {
    padding-left: 0 !important;
    padding-right: calc(0.75rem * 4) !important;
}

.nswp-align-end .ajax-nav-link.nswp-folder-link.level-5 {
    padding-left: 0 !important;
    padding-right: calc(0.75rem * 5) !important;
}

.nswp-align-end .ajax-nav-link.nswp-folder-link.level-6 {
    padding-left: 0 !important;
    padding-right: calc(0.75rem * 6) !important;
}

.nswp-align-end .ajax-nav-link.nswp-folder-link.level-7 {
    padding-left: 0 !important;
    padding-right: calc(0.75rem * 7) !important;
}

.nswp-align-end .ajax-nav-link.nswp-folder-link.level-8 {
    padding-left: 0 !important;
    padding-right: calc(0.75rem * 8) !important;
}

.nswp-align-end .ajax-nav-link.nswp-folder-link.level-9 {
    padding-left: 0 !important;
    padding-right: calc(0.75rem * 9) !important;
}

.nswp-align-end .ajax-nav-link.nswp-folder-link.level-10 {
    padding-left: 0 !important;
    padding-right: calc(0.75rem * 10) !important;
}

.nswp-align-center .ajax-nav-link.nswp-folder-link[class*="level-"] {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

.nswp-align-center .ajax-nav-link .nswp-depth-dots {
    margin-right: 0.25rem;
    margin-left: 0.25rem;
}
