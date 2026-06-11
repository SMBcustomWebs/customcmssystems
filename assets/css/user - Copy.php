<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:content_type 'text/css' />
<cms:template parent='_donottouch_' hidden='1' />

/* -------------------------------------------------------------------------- */
/* Posh Button                               */
/* -------------------------------------------------------------------------- */

<cms:pages masterpage="<cms:gpc 'page' />" id="<cms:gpc 'post' />" > 
    <cms:set bg_clr1="<cms:show bg_clr_cstm_one />" "global" />
    <cms:set bg_clr2="<cms:show bg_clr_cstm_two />" "global" />
    <cms:set bg_splt="<cms:show bg_clr_cust_grad_split_opt />" "global" />
    <cms:if bg_clr_cstm_grdt_opt >
        <cms:set bg_grd="1"  "global" />
    </cms:if>
    <cms:if bg_clr_cstm_grad_rvrs_opt >
        <cms:set swap_bg_clr="1" "global" />
    </cms:if>

    <cms:if ovrly_clr_opt>
        <cms:set ol_clr1="<cms:show ovrly_clr_cstm_one />" "global" />
        <cms:set ol_clr2="<cms:show ovrly_clr_cstm_two />" "global" />
        <cms:set ol_splt="<cms:show ovrly_cust_grad_split_opt />" "global" />
        <cms:if ovrly_clr_grdt_opt >
            <cms:set ol_grd="1"  "global" />
        </cms:if>
        <cms:if ovrly_clr_cstm_grdt_rvrs_opt >
            <cms:set swap_ol_clr="1" "global" />
        </cms:if>
    </cms:if>
</cms:pages>

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
    
   
    
    <!-- <cms:set hdr_size="<cms:show hdr_text_weight />" "global" /> -->

/* -------------------------------------------------------------------------- */
/*                                Nav Bar and Logo                               */
/* -------------------------------------------------------------------------- */


    /* -------------------------------------------------------------------------- */
    /* Nav Bar and Logo variables mapped from globals                             */
    /* -------------------------------------------------------------------------- */

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
        // Handle 3-digit and 6-digit hex codes safely
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } elseif (strlen($hex) >= 6) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        } else {
            return '0, 0, 0'; // Fallback
        }
        return $r . ', ' . $g . ', ' . $b;
    }

    global $CTX;
    // Array of all color variables fetched from Couch
    $colors = ['primary', 'secondary', 'tertiary', 'quaternary', 'success', 'info', 'warning', 'danger', 'light', 'dark', 'white', 'black', 'body_clr', 'body_bg'];

    foreach ($colors as $color) {
        $hex = $CTX->get($color);
        if ($hex) {
            // Set the new _rgb variable globally
            $CTX->set($color . '_rgb', hexToRgbStr($hex), 'global');
        }
    }
</cms:php>

:root {
    --posh-primary: <cms:show primary /> !important;
    --posh-primary-rgb: <cms:show primary_rgb /> !important;
    
    --posh-secondary: <cms:show secondary /> !important;
    --posh-secondary-rgb: <cms:show secondary_rgb /> !important;
    
    --posh-tertiary: <cms:show tertiary /> !important;
    --posh-tertiary-rgb: <cms:show tertiary_rgb /> !important;
    
    --posh-quaternary: <cms:show quaternary /> !important;
    --posh-quaternary-rgb: <cms:show quaternary_rgb /> !important;
    
    --posh-success: <cms:show success /> !important;
    --posh-success-rgb: <cms:show success_rgb /> !important;
    
    --posh-info: <cms:show info /> !important;
    --posh-info-rgb: <cms:show info_rgb /> !important;
    
    --posh-warning: <cms:show warning /> !important;
    --posh-warning-rgb: <cms:show warning_rgb /> !important;
    
    --posh-danger: <cms:show danger /> !important;
    --posh-danger-rgb: <cms:show danger_rgb /> !important;
    
    --posh-light: <cms:show light /> !important;
    --posh-light-rgb: <cms:show light_rgb /> !important;
    
    --posh-dark: <cms:show dark /> !important;
    --posh-dark-rgb: <cms:show dark_rgb /> !important;
    
    --posh-white: <cms:show white /> !important;
    --posh-white-rgb: <cms:show white_rgb /> !important;
    
    --posh-black: <cms:show black /> !important;
    --posh-black-rgb: <cms:show black_rgb /> !important;
    
    --posh-body-color-rgb: <cms:show body_clr_rgb /> !important;
    --posh-body-bg-rgb: <cms:show body_bg_rgb /> !important;

    --posh-100: <cms:show gray-100 /> !important;
    --posh-200: <cms:show gray-200 /> !important;
    --posh-300: <cms:show gray-300 /> !important;
    --posh-400: <cms:show gray-400 /> !important;
    --posh-500: <cms:show gray-500 /> !important;
    --posh-600: <cms:show gray-600 /> !important;
    --posh-700: <cms:show gray-700 /> !important;
    --posh-800: <cms:show gray-800 /> !important;
    --posh-900: <cms:show gray-900 /> !important;
    --posh-1000: <cms:show gray-1000 /> !important;
    --posh-1100: <cms:show gray-1100 /> !important;
    --posh-1200: <cms:show gray-1200 /> !important;
    
    --posh-body-font-family: <cms:show bff /> !important;
    --posh-font-sans-serif: <cms:show fss /> !important;
    --posh-font-monospace: <cms:show fmn /> !important;
    
    --posh-font-serif: <cms:show fsf /> !important;
    --posh-font-cursive: <cms:show fcs /> !important;
    --posh-font-decorate: <cms:show fdc /> !important;

    --posh-body-font-size: <cms:show bfs />rem !important;
    --posh-body-font-weight: <cms:show bfw /> !important;
    --posh-body-line-height: <cms:show blh />  !important;

    /* Correctly mapped to output exact var(--posh-[color]) syntax */
    --posh-navbar-active-color: var(--posh-<cms:show ccs_site_nav_actv_clr />) !important;
    --posh-link-hover-color: var(--posh-<cms:show ccs_site_nav_hvr_clr />) !important;
    --posh-dropdown-color: var(--posh-<cms:show ccs_site_nav_mnu_dd_txt_clr />) !important;
    --posh-dropdown-bg: var(--posh-<cms:show ccs_site_nav_mnu_dd_bg />) !important;
    --posh-dropdown-link-hover-color: var(--posh-<cms:show ccs_site_nav_mnu_dd_hvr_clr />) !important;
    --posh-dropdown-link-hover-bg: var(--posh-<cms:show ccs_site_nav_mnu_dd_hvr_bg />) !important;

    <cms:if bg_grd="1" >
        <cms:if swap_bg_clr >
            --posh-bg-gradient: linear-gradient(<cms:show bg_splt />deg, <cms:show bg_clr2 />, <cms:show bg_clr1 />)!important;
        <cms:else />
            --posh-bg-gradient: linear-gradient(<cms:show bg_splt />deg, <cms:show bg_clr1 />, <cms:show bg_clr2 />)!important;
        </cms:if>
    <cms:else />
        --posh-bg-gradient: linear-gradient(0deg,<cms:show bg_clr1 />, <cms:show bg_clr1 />)!important;
    </cms:if>

    <cms:if ol_grd="1" >
        <cms:if swap_ol_clr >
            --posh-ol-gradient:linear-gradient(<cms:show ol_splt />deg, <cms:show ol_clr2 />, <cms:show ol_clr1 />) !important;
        <cms:else />
            --posh-ol-gradient:linear-gradient(<cms:show ol_splt />deg, <cms:show ol_clr1 />, <cms:show ol_clr2 />) !important;
        </cms:if>
    <cms:else />
        --posh-ol-gradient:linear-gradient(0deg, <cms:show ol_clr1 />, <cms:show ol_clr1 />) !important;
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
  /*font-weight: <cms:show hdr_size /> ;*/
}

/*-----------------------------------------------
|   Link Colors
-----------------------------------------------*/

a{
  color: <cms:show body_clr />;
}

.link-white {
  color: RGBA(var(--posh-white-rgb), var(--posh-link-opacity, 1)) !important;
  -webkit-text-decoration-color: RGBA(var(--posh-white-rgb), var(--posh-link-underline-opacity, 1)) !important;
  text-decoration-color: RGBA(var(--posh-white-rgb), var(--posh-link-underline-opacity, 1)) !important;
}
.link-light:hover, .link-white:focus {
  color: RGBA(255, 255, 255, var(--posh-link-opacity, 1)) !important;
  -webkit-text-decoration-color: RGBA(255, 255, 255, var(--posh-link-underline-opacity, 1)) !important;
  text-decoration-color: RGBA(255, 255, 255, var(--posh-link-underline-opacity, 1)) !important;
}

.link-black {
  color: RGBA(var(--posh-black-rgb), var(--posh-link-opacity, 1)) !important;
  -webkit-text-decoration-color: RGBA(var(--posh-black-rgb), var(--posh-link-underline-opacity, 1)) !important;
  text-decoration-color: RGBA(var(--posh-black-rgb), var(--posh-link-underline-opacity, 1)) !important;
}
.link-black:hover, .link-black:focus {
  color: RGBA(0, 0, 0, var(--posh-link-opacity, 1)) !important;
  -webkit-text-decoration-color: RGBA(0, 0, 0, var(--posh-link-underline-opacity, 1)) !important;
  text-decoration-color: RGBA(0, 0, 0, var(--posh-link-underline-opacity, 1)) !important;
}


/*-----------------------------------------------
|   Button Colors
-----------------------------------------------*/

.btn-black {
  color: var(--posh-gray-200);
  background-color: #000;
  border-color: #000;
}
.btn-black:hover, .btn-black:focus {
  color: var(--posh-gray-200);
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
  color: var(--posh-nav-link-hover-color);
}

.navbar {
  --posh-navbar-active-color: var(--posh-<cms:show ccs_site_nav_hvr_clr />);
}

.navbar-nav {
  --posh-nav-link-font-size: <cms:show ccs_site_nav_mnu_ttl_sz />;
  --posh-nav-link-font-weight:  <cms:show ccs_site_nav_mnu_ttl_wt />;
  --posh-nav-link-color: var(--posh-<cms:show ccs_site_nav_mnu_ttl_clr />);
  --posh-nav-link-hover-color: var(--posh-link-hover-color);
}

.navbar-nav .nav-link.active, .navbar-nav .nav-link.show {
  color: var(--posh-navbar-active-color);
}

.dropdown-item {
  color: var(--posh-<cms:show ccs_site_nav_mnu_dd_txt_clr />) !important;
}
.dropdown-menu {
  background-color: var(--posh-<cms:show ccs_site_nav_mnu_dd_bg />) !important;
}
.dropdown-item-wrapper:hover, .dropdown-item-wrapper:focus {
  color: var(--posh-<cms:show ccs_site_nav_mnu_dd_hvr_clr  />) !important;
}
.dropdown-item:hover {
  color: var(--posh-<cms:show ccs_site_nav_mnu_dd_hvr_clr  />) !important;
  background-color: var(--posh-<cms:show ccs_site_nav_mnu_dd_hvr_bg />) !important;
}
.nav-link.active {
   color: var(--posh-<cms:show ccs_site_nav_actv_clr />) !important;
}
.nav-item:hover,
.nav-link:hover {
  color: var(--posh-<cms:show ccs_site_nav_hvr_clr />) !important;
}

::selection {
  background-color: var(--posh-<cms:show ccs_site_slct_bg />) !important;
  color: var(--posh-<cms:show ccs_site_slct_clr />) !important;
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

/* Creates an invisible interaction bridge over the dropdown air gap */
.navbar .dropdown-menu::before {
    content: "";
    position: absolute;
    top: -25px;      /* Extends the hover zone upward into the gap */
    left: 0;
    right: 0;
    height: 25px;    /* Matches the size of the gap exactly */
    background: transparent;
    z-index: -1;     /* Sits safely behind content elements */
}

/* Eliminates the spatial offset and pulls the dropdown window upward */
.navbar .dropdown-menu {
    margin-top: -5px !important; /* Adjust this value until the gap is completely closed */
}

/* For nested level-2 flyout menus in nav_mdd, close the side gap too */
.navbar .dropdown-menu .dropdown-menu {
    margin-top: 0 !important;
    margin-left: -2px !important; 
}
@media (min-width: 992px) {
    /* 1. Force the parent hover wrapper to extend its boundary downward */
    .navbar .navbar-nav .nav-item.dropdown {
        padding-bottom: 25px !important;
        margin-bottom: -25px !important;
    }

    /* 2. Lock the menu to the bottom of the newly extended container seam */
    .navbar .navbar-nav .dropdown-menu {
        margin-top: -12px !important;
    }
    
    /* 3. For multi-dropdowns (nav_mdd), fix the side flight gapping */
    .navbar .dropdown-menu .dropdown {
        padding-right: 20px !important;
        margin-right: -20px !important;
    }
}
<?php COUCH::invoke(); ?>