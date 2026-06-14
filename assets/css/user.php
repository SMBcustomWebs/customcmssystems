<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:content_type 'text/css' />
<cms:template parent='_donottouch_' hidden='1' />

/* -------------------------------------------------------------------------- */
/* Posh Button                                                                */
/* -------------------------------------------------------------------------- */
/* ========================================================================== */
/* 1. PRIMAVERA (SPRING) - Fresh greens, soft pinks, energetic and light      */
/* ========================================================================== */
[data-bs-theme="primavera"] {
    --ccs-primary: #FF8E9C;      /* Soft Coral Pink */
    --ccs-secondary: #85D3A9;    /* Spring Leaf Green */
    --ccs-tertiary: #A3D5FF;     /* Morning Sky Blue */
    --ccs-quaternary: #FFE8A1;   /* Pale Sunshine */
    --ccs-body-bg: #FCFCFA;      /* Ultra-light warm gray */
    --ccs-body-color: #2C3E50;   /* Deep slate for readability */
    --ccs-heading-color: #1A252F;
}

/* ========================================================================== */
/* 2. ESTATE (SUMMER) - High contrast, vibrant, oceanic, and warm             */
/* ========================================================================== */
[data-bs-theme="estate"] {
    --ccs-primary: #FF9F1C;      /* Vibrant Sun Orange */
    --ccs-secondary: #2EC4B6;    /* Tropical Ocean Teal */
    --ccs-tertiary: #E71D36;     /* Bright Watermelon Red */
    --ccs-quaternary: #FFBF69;   /* Warm Sand */
    --ccs-body-bg: #FFFFFF;      /* Stark white for high contrast */
    --ccs-body-color: #011627;   /* Deepest Navy (near black) */
    --ccs-heading-color: #011627;
}

/* ========================================================================== */
/* 3. AUTUNNO (AUTUMN) - Deep, rich, earthy, and sophisticated                */
/* ========================================================================== */
[data-bs-theme="autunno"] {
    --ccs-primary: #D95D39;      /* Burnt Maple Red */
    --ccs-secondary: #F0A202;    /* Golden Harvest Yellow */
    --ccs-tertiary: #826251;     /* Warm Bark Brown */
    --ccs-quaternary: #A89C94;   /* Foggy Gray */
    --ccs-body-bg: #F4F1ED;      /* Parchment off-white */
    --ccs-body-color: #3A1700;   /* Deep Espresso */
    --ccs-heading-color: #260F00;
}

/* ========================================================================== */
/* 4. INVERNO (WINTER) - Crisp, icy, stark, and highly corporate              */
/* ========================================================================== */
[data-bs-theme="inverno"] {
    --ccs-primary: #3A86FF;      /* Frost Blue */
    --ccs-secondary: #8ECAE6;    /* Glacier Ice */
    --ccs-tertiary: #4A4E69;     /* Cold Steel Gray */
    --ccs-quaternary: #C1D3FE;   /* Pale Frost */
    --ccs-body-bg: #F8F9FA;      /* Cool Snow White */
    --ccs-body-color: #0B132B;   /* Deep Midnight Blue */
    --ccs-heading-color: #050A17;
}

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
        <cms:set primary="#007AFF" "global" />
        <cms:set secondary="#292b2c" "global" />
        <cms:set tertiary="#687BD9" "global" />
        <cms:set quaternary="#68C2D9" "global" />
        <cms:set success="#4CD964" "global" />
        <cms:set info="#2EB7F5" "global" />
        <cms:set warning="#FF9500" "global" />
        <cms:set danger="#FF3B30" "global" />
        <cms:set light="#fafafa" "global" />
        <cms:set dark="#0c151a" "global" />
        <cms:set white="#fff" "global" />
        <cms:set black="#000" "global" />
        <cms:if ccs_gl_site_thm_opt='light'>
            <cms:set body_clr="#292b2c" "global" />
            <cms:set body_bg="#fff" "global" />
        <cms:else_if ccs_gl_site_thm_opt='dark' />
            <cms:set body_clr="#e1e1e1" "global" />
            <cms:set body_bg="#404040" "global" />
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
       <cms:set bff="<cms:show ccs_gl_site_font_body_cust />" "global" />
       <cms:set fbs="<cms:show ccs_gl_site_font_base_cust />" "global" />
       <cms:set fss="<cms:show ccs_gl_site_font_sans_cust />" "global" />
       <cms:set fmn="<cms:show ccs_gl_site_font_mono_cust />" "global" />
       <cms:set fsf="<cms:show ccs_gl_site_font_serf_cust />" "global" />
       <cms:set fcs="<cms:show ccs_gl_site_font_crsv_cust />" "global" />
       <cms:set fdc="<cms:show ccs_gl_site_font_decr_cust />" "global" />
       <cms:set bfs="<cms:show ccs_gl_site_font_size_cust />" "global" />
       <cms:set bfw="<cms:show ccs_gl_site_font_weight_cust />" "global" />
       <cms:set blh="<cms:show ccs_gl_site_line_height_cust />" "global" />
    <cms:else />
       <cms:set bff='"Montserrat", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"' "global" />
       <cms:set fbs='"Montserrat", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"' "global" />
       <cms:set fss=' Raleway, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"' "global" />
       <cms:set fmn='"SFMono-Regular", Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace' "global" />
       <cms:set fsf='"Playfair Display","Libre Baskerville", Georgia, serif ' "global" />
       <cms:set fcs='"Bradley Hand", "Lucida Handwriting", "Brush Script MT", "Comic Sans MS"' "global" />
       <cms:set fdc='Sreda, Arvo, Candara' "global" />
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

/*-----------------------------------------------
|   Font family
-----------------------------------------------*/
.font-sans-serif {
  font-family: <cms:show fss /> !important;
}
.font-base {
  font-family:<cms:show fbs /> !important ;
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
  --ccs-navbar-active-color: var(--ccs-<cms:show ccs_site_nav_hvr_clr />);
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

.dropdown-item {
  color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_txt_clr />) !important;
}
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


<?php COUCH::invoke(); ?>