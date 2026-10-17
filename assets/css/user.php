<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:content_type 'text/css' />
<cms:template parent='_donottouch_' hidden='1' />


<cms:ignore>
    REMOVED 2026-09-04 - the hero gradient path, dead in both render modes.

    This file used to read ccs_hro_* off ?page=&post= and emit --ccs-bg-gradient,
    --ccs-ol-gradient and a .bg-holder.overlay-gradient:before rule. None of it
    reached anything:

      - no hero or hero_swpr theme references .bg-gradient or --ccs-bg-gradient;
        they paint via #<id> .hro-base / .hro-overlay from hero_custom_settings.html
      - --ccs-ol-gradient had no consumer anywhere in the codebase
      - on a live page the fields live on the TILE, not the host page, so the
        declarations emitted with empty color stops - linear-gradient(0deg,, ) -
        and the browser discarded them (verified: the rule's cssText was empty)
      - in a tile iframe it read the tile and emitted either the same empty form
        (hero_swpr, whose fields are ccs_hro_swpr_*) or the stored #000000 default

    Verified on the live home page, whose hero has BOTH a custom background
    gradient and a custom overlay gradient: exactly one rule paints each, and
    both come from the tile's own inline <style>. This file contributed nothing.

    Removing it also removed the last reader of ?page=&post= - see main-cap.htm.
</cms:ignore>

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
       <cms:ignore>
           LETTER SPACING WAS NEVER WIRED.

           ccs_gl_site_letter_space_cust has had a dropdown in Custom Typography
           the whole time, with seven real em values, and this file never read
           it - there was no letter-spacing declaration anywhere in the body
           rule. Picking "Wider" changed nothing on the site, and the settings
           preview on globals.php printed the chosen value as though it had.

           "normal" rather than 0em when unset: they render the same for most
           faces, but normal lets a font's own kerning table stand, and 0em
           overrides it.
       </cms:ignore>
       <cms:set bls="<cms:show ccs_gl_site_letter_space_cust />" "global" />

       <cms:ignore>
           THE FOUR SIZE/WEIGHT/HEIGHT DROPDOWNS ALL LEAD WITH "Base=-".

           Picked, that stores the literal string "-", and this file printed
           font-size: -rem. Invalid, so the browser dropped the declaration and
           body silently fell back to whatever it inherited. The Typography card
           on the dashboard already guarded for '-' when DISPLAYING the value;
           the CSS that actually renders never did.

           Same defaults as the other two branches, so "Base" now means what it
           says rather than nothing.
       </cms:ignore>
       <cms:if bfs eq '-' || bfs eq ''><cms:set bfs="1" "global" /></cms:if>
       <cms:if bfw eq '-' || bfw eq ''><cms:set bfw="400" "global" /></cms:if>
       <cms:if blh eq '-' || blh eq ''><cms:set blh="1.5" "global" /></cms:if>
       <cms:if bls eq '-' || bls eq ''><cms:set bls="normal" "global" /></cms:if>

       
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
            COLOR theme happened to be chosen. Nothing in the panel connects
            those two things.

            light, dark, scuro and notte are color themes and were never meant
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
        <cms:set bfs="1" "global" />
        <cms:set bfw="400" "global" />
        <cms:set blh="1.5" "global" />
        <cms:set bls="normal" "global" />

    <cms:else />
       <cms:set bff='"Montserrat", -apple-system, sans-serif' "global" />
       <cms:set fss='"Raleway", -apple-system, sans-serif' "global" />
       <cms:set fmn='"SFMono-Regular", Menlo, Monaco, Consolas, monospace' "global" />
       <cms:set fsf='"Playfair Display", Georgia, serif' "global" />
       <cms:set fcs='"Bradley Hand", "Brush Script MT", cursive' "global" />
       <cms:set fdc='"Sreda", Arvo, Candara' "global" />
       <cms:set bfs="1" "global" />
       <cms:set bfw="400" "global" />
       <cms:set blh="1.5" "global" />
       <cms:set bls="normal" "global" />
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
        Root text scale. Defaults to 100 so a site that has never saved the
        field renders exactly as it did before this existed.
    </cms:ignore>
    <cms:set ccs_site_text_scale="<cms:show ccs_gl_site_text_scale />" "global" />
    <cms:if ccs_site_text_scale eq ''>
        <cms:set ccs_site_text_scale='100' "global" />
    </cms:if>

    <cms:ignore>
        Footer and utility-bar link hover colors. Both fall back to the
        site-wide hover color when left on Default.
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
    <cms:ignore>
        NAVBAR TEXT COLOR AT DEFAULT.

        This was the literal "dark", so line 667 emitted

            .navbar-nav { --ccs-nav-link-color: var(--ccs-dark); }

        on every site, every theme, always. It is not a fallback - no field is
        read here at all. On Notte --ccs-dark is #050505 and on Scuro it is the
        same, so the top-level menu items were black text on a black bar. The
        per-navbar "Navbar Text Color" dropdown at Default emits nothing and
        falls through to exactly this value, which is why setting it to Default
        looked broken.

        --ccs-body-color is the color user.php already wrote for the active
        theme, and a navbar whose own Background Color is Default emits the
        class "bg-" - not a real class - so it is transparent and the site
        background is what sits behind the menu. Matching the body color is
        therefore the correct default on both light and dark themes.

        On light themes this moves the menu from #050505 to #292b2c, which is
        not a visible change. A navbar given an explicit background that fights
        the body color still has the Navbar Text Color dropdown to set it.
    </cms:ignore>
    <cms:set ccs_site_nav_mnu_ttl_clr="body-color" "global" />
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

    /* The select arrow is a data URI and an SVG in one cannot read
       currentColor, so the stroke has to be written into the file with the
       theme's own text colour already substituted. A URI needs the leading #
       as %23. Computed HERE, inside the one php block that already declares
       global $CTX, rather than inline in the CSS below - see the note on the
       .form-select rule for why that matters. */
    $CTX->set( 'body_clr_uri', str_replace('#', '%23', (string) $CTX->get('body_clr')), 'global' );
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
    --ccs-body-letter-spacing: <cms:show bls />;

    <cms:ignore>
        WHY EVERY ONE OF THESE IS WRAPPED IN A TEST.

        Each line builds a token NAME out of a stored value -
        var(--ccs- plus the value plus ). When the setting is on Default the
        value is empty, so the reference is to a property called --ccs-, which
        no stylesheet defines and which has no fallback. CSS calls that invalid
        at computed-value time: the custom property is dropped, and any property
        that reads it computes to unset. For an inherited property like color
        that means "inherit" and the damage is invisible. For background-color
        it means transparent, which is why a dropdown menu left on Default had
        no background at all.

        Emitting nothing when the field is empty is what Default should have
        meant all along. theme.css already carries a real value for every one of
        these, and every one of them is theme-aware:

            --ccs-navbar-active-color        rgba(emphasis, .9)   :5545
            --ccs-link-hover-color           #0062cc / #85bfff    :108 / :180
            --ccs-dropdown-color             var(--ccs-body-color)   :4927
            --ccs-dropdown-bg                var(--ccs-body-bg)      :4928
            --ccs-dropdown-link-color        var(--ccs-body-color)   :4936
            --ccs-dropdown-link-hover-color  var(--ccs-body-color)   :4937
            --ccs-dropdown-link-hover-bg     var(--ccs-tertiary-bg)  :4938

        So Default now inherits the theme instead of silently voiding the rule.
        Same reasoning as the navbar text color further up this file.
    </cms:ignore>
    <cms:if ccs_site_nav_actv_clr ne ''>
    --ccs-navbar-active-color: var(--ccs-<cms:show ccs_site_nav_actv_clr />);
    </cms:if>
    <cms:if ccs_site_nav_hvr_clr ne ''>
    --ccs-link-hover-color: var(--ccs-<cms:show ccs_site_nav_hvr_clr />);
    </cms:if>
    <cms:if ccs_site_nav_mnu_dd_txt_clr ne ''>
    --ccs-dropdown-color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_txt_clr />);
    </cms:if>
    /* theme.css:5169 - .dropdown-item reads THIS variable for its text color,
       not --ccs-dropdown-color above. --ccs-dropdown-color applies to the menu,
       and the item's own color declaration overrides what it inherits, so the
       line above alone never reached the text. Both are set now: the menu for
       anything that inherits, the link for the items themselves. */
    <cms:if ccs_site_nav_mnu_dd_txt_clr ne ''>
    --ccs-dropdown-link-color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_txt_clr />);
    </cms:if>
    <cms:if ccs_site_nav_mnu_dd_bg ne ''>
    --ccs-dropdown-bg: var(--ccs-<cms:show ccs_site_nav_mnu_dd_bg />);
    </cms:if>
    <cms:if ccs_site_nav_mnu_dd_hvr_clr ne ''>
    --ccs-dropdown-link-hover-color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_hvr_clr />);
    </cms:if>
    <cms:if ccs_site_nav_mnu_dd_hvr_bg ne ''>
    --ccs-dropdown-link-hover-bg: var(--ccs-<cms:show ccs_site_nav_mnu_dd_hvr_bg />);
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
   Bootstrap ships <hr> at opacity .25 so an uncolored divider reads as soft
   gray. That washes out an explicitly chosen color - text-danger at 25% is
   pink, not red - so any hr driven by a color dropdown gets full opacity and
   the chosen color renders true. */
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

<cms:ignore>
  ROOT SCALE. Everything in rem measures against THIS, not against body -
  which is why the old size control never scaled the site: it was applied to
  body. A percentage keeps the visitor's own browser default in play.
</cms:ignore>
html {
  font-size: <cms:show ccs_site_text_scale />%;
}

<cms:ignore>
  FORM FIELDS AND BUTTONS AT 1rem.

  theme.css ships these at 0.7501875469rem - 12px. iOS Safari zooms the page
  whenever a focused input is under 16px and does not zoom back out, so on
  checkout the viewport jumped on every field. Buttons are raised with them so
  a control next to a field does not read a size smaller.

  Here rather than in theme.css: head.htm loads theme.css first and this file
  after it, so these win on load order, and a theme update cannot wipe them.

  .form-control-sm / -lg are left alone - they size with calc(1.5em + ...) and
  follow automatically. textarea is font-size:inherit and follows too.
</cms:ignore>
.form-control,
.form-select,
.form-label,
.input-group-text {
  font-size: 1rem;
}

<cms:ignore>
    =========================================================================
    FORM FIELDS - THE MODERN BOX.

    WHAT theme.css SHIPS, AND WHY IT LOOKS WRONG:

        .form-control { padding: 0.8rem 2.5rem; }
        .form-select  { padding: 0.8rem 7.5rem 0.8rem 2.5rem; }

    2.5rem is 40px of horizontal padding on an input - the button's padding,
    copied onto a field. The caret starts 40px from the left edge, which reads
    as broken rather than styled. Bootstrap's own is 0.75rem. The select is
    worse: 7.5rem is 120px of right padding, so its text is crushed into the
    left third with a canyon before the arrow.

    THREE COLOURS IN THERE IGNORE THE THEME ENTIRELY:

        color            #464a4c                 a fixed grey
        background       --ccs-quaternary-bg     #fff, no dark value anywhere
        focus ring       rgba(0,122,255,.25)     stays blue on every theme

    So on Scuro or Notte a field is a white box with grey text and a blue halo,
    sitting on a near-black page. Same family as the list-group and navbar
    faults - a literal where a token belongs.

    EVERYTHING BELOW DERIVES FROM TOKENS THIS FILE ALREADY WRITES, so it
    follows all eight themes and any added later. Nothing is hardcoded.

    THE SIZE MODIFIERS ARE PROTECTED. .form-control-sm and -lg set their own
    padding in theme.css and appear EARLIER in the cascade, so a bare
    .form-control padding rule here would silently beat them. The :not() pair
    keeps this to the base case and leaves both modifiers intact.

    FONT SIZE IS NOT SET HERE. It is 1rem from the rule directly above - the
    iOS Safari zoom threshold. Do not lower it.
    =========================================================================
</cms:ignore>
.form-control:not(.form-control-sm):not(.form-control-lg) {
  padding: 0.75rem 1rem;
}
.form-select:not(.form-select-sm):not(.form-select-lg) {
  padding: 0.75rem 2.75rem 0.75rem 1rem;
}

.form-control,
.form-select {
  color: var(--ccs-body-color);
  background-color: var(--ccs-body-bg);
  border: 1px solid rgba(var(--ccs-body-color-rgb), 0.22);
  border-radius: var(--ccs-border-radius);
  box-shadow: none;
  transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}

<cms:ignore>
    opacity:1 is required. Firefox ships placeholders at a reduced opacity of
    its own, which would compound with the alpha here and take the hint below
    a readable contrast.
</cms:ignore>
.form-control::-webkit-input-placeholder { color: rgba(var(--ccs-body-color-rgb), 0.5); opacity: 1; }
.form-control::-moz-placeholder { color: rgba(var(--ccs-body-color-rgb), 0.5); opacity: 1; }
.form-control:-ms-input-placeholder { color: rgba(var(--ccs-body-color-rgb), 0.5); opacity: 1; }
.form-control::placeholder { color: rgba(var(--ccs-body-color-rgb), 0.5); opacity: 1; }

.form-control:hover:not(:focus):not(:disabled),
.form-select:hover:not(:focus):not(:disabled) {
  border-color: rgba(var(--ccs-body-color-rgb), 0.40);
}

.form-control:focus,
.form-select:focus {
  color: var(--ccs-body-color);
  background-color: var(--ccs-body-bg);
  border-color: var(--ccs-primary);
  box-shadow: 0 0 0 0.2rem rgba(var(--ccs-primary-rgb), 0.25);
  outline: 0;
}

<cms:ignore>
    THE SELECT ARROW IS A DATA URI WITH THE STROKE COLOUR BAKED IN, and
    theme.css bakes in %23555555 - a dark grey arrow on a dark field. An SVG in
    a data URI cannot read currentColor, so the only fix is to write the file
    again with the theme's own text colour substituted. str_replace turns the
    leading # into %23, which is what a URI needs.
</cms:ignore>
.form-select {
  --ccs-form-select-bg-img: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='<cms:show body_clr_uri />' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
}

.form-label {
  margin-bottom: 0.375rem;
  font-weight: 600;
}

.input-group-text {
  color: var(--ccs-body-color);
  background-color: rgba(var(--ccs-body-color-rgb), 0.05);
  border: 1px solid rgba(var(--ccs-body-color-rgb), 0.22);
}


.btn {
  --ccs-btn-font-size: 1rem;
}

<cms:ignore>
    ===========================================================================
    FLUID HEADINGS.

    theme.css sizes every heading as one fixed number with no media query - h1
    is 4.2087rem (67px) and .display-1 is 9.9688rem (160px) at EVERY viewport
    width. There is no @media anywhere in that file that steps a heading down,
    and no clamp() and no vw. On a 375px phone a display-1 headline fits about
    two characters on a line.

    THIS IS NOT AN EXOTIC FIX - IT IS THE FRAMEWORK'S OWN DEFAULT, SWITCHED OFF.
    Stock Bootstrap 5.3 ships RFS (Responsive Font Sizes) enabled:

        h1, .h1 { font-size: calc(1.375rem + 1.5vw); }
        @media (min-width: 1200px) { h1, .h1 { font-size: 2.5rem; } }

    theme.css was compiled with it disabled. clamp() is the newer single-line
    form of the same curve - one declaration instead of a rule plus a media
    query, and no breakpoint staircase. Baseline Widely Available since 2020.

    HOW THE NUMBERS WERE PICKED. Two anchor points: 375px (phone) and 1200px,
    which is Bootstrap's own RFS cap and this site's xl breakpoint, so the
    fluid part stops exactly where the 1140px container stops growing.

        clamp( size at 375px , the line between them , TODAY'S EXACT VALUE )

    Every maximum is the byte-for-byte value from theme.css, so nothing moves
    at all above 1200px. Only phones and tablets change.

    THE rem TERM IS DELIBERATELY POSITIVE ON ALL EIGHT. A middle term of pure
    vw ignores a visitor who has raised their browser's default font size
    without zooming - vw does not respond to that. Keeping a positive rem
    component means their setting still moves the heading. The minimums were
    chosen so no step needs a negative one, which is also why display-1 floors
    at 50px rather than 40.

    fs-9, fs-10 and h6 ARE NOT HERE ON PURPOSE. Those are body and caption,
    16px and 12px. Body text must not shrink on a phone - 16px is the floor
    everywhere and it is also the iOS Safari zoom threshold.

    Composes with Site Text Size: that sets the root, and every rem term here
    measures against the root, so the two multiply rather than fight.
    ===========================================================================
</cms:ignore>
/* Fluid headings - see the note in assets/css/user.php. Max = theme.css value. */

.fs-1 { font-size: clamp(3.1250rem, 13.273vw + 0.0142rem, 9.9687612606rem) !important; }
.fs-2 { font-size: clamp(2.3750rem, 9.898vw + 0.0553rem, 7.4784405556rem) !important; }
.fs-3 { font-size: clamp(2.0000rem, 7.002vw + 0.3590rem, 5.6102329749rem) !important; }
.fs-4 { font-size: clamp(1.8750rem, 4.526vw + 0.8142rem, 4.2087269129rem) !important; }
.fs-5 { font-size: clamp(1.6250rem, 2.972vw + 0.9285rem, 3.1573345183rem) !important; }
.fs-6 { font-size: clamp(1.4375rem, 1.806vw + 1.0143rem, 2.368593037rem) !important; }
.fs-7 { font-size: clamp(1.2500rem, 1.022vw + 1.0105rem, 1.776889rem) !important; }
.fs-8 { font-size: clamp(1.1250rem, 0.403vw + 1.0305rem, 1.333rem) !important; }

.display-1 { font-size: clamp(3.1250rem, 13.273vw + 0.0142rem, 9.9687612606rem); }
.display-2 { font-size: clamp(2.3750rem, 9.898vw + 0.0553rem, 7.4784405556rem); }
.display-3 { font-size: clamp(2.0000rem, 7.002vw + 0.3590rem, 5.6102329749rem); }
.display-4 { font-size: clamp(1.8750rem, 4.526vw + 0.8142rem, 4.2087269129rem); }
.display-5 { font-size: clamp(1.6250rem, 2.972vw + 0.9285rem, 3.1573345183rem); }
.display-6 { font-size: clamp(1.4375rem, 1.806vw + 1.0143rem, 2.368593037rem); }

h1, .h1 { font-size: clamp(1.8750rem, 4.526vw + 0.8142rem, 4.2087269129rem); }
h2, .h2 { font-size: clamp(1.6250rem, 2.972vw + 0.9285rem, 3.1573345183rem); }
h3, .h3 { font-size: clamp(1.4375rem, 1.806vw + 1.0143rem, 2.368593037rem); }
h4, .h4 { font-size: clamp(1.2500rem, 1.022vw + 1.0105rem, 1.776889rem); }
h5, .h5 { font-size: clamp(1.1250rem, 0.403vw + 1.0305rem, 1.333rem); }


body {
  font-family: <cms:show bff />;
  font-size: <cms:show bfs />rem;
  font-weight: <cms:show bfw />;
  line-height: <cms:show blh />;
  letter-spacing: <cms:show bls />;
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
  <cms:if ccs_site_nav_actv_clr ne ''>
  --ccs-navbar-active-color: var(--ccs-<cms:show ccs_site_nav_actv_clr />);
  </cms:if>
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

/* The .dropdown-item color rule that used to sit here has been removed, and
   the reason it existed at all is fixed at its source in the :root block above.

   theme.css:5169 reads --ccs-dropdown-link-color for the item's text color.
   :root here was setting --ccs-dropdown-color, which theme.css applies to the
   MENU, not the item - and .dropdown-item's own color declaration overrides
   what it would have inherited. So the global setting never reached the item,
   and this rule was added to force it, with !important.

   That !important then beat every .text-* utility, because theme.css compiles
   its color utilities WITHOUT !important (theme.css:11617). Result: the
   per-navbar text color and the per-item text-<...> class were both in the
   HTML and both lost, on every dropdown item in every navbar.

   With the correct variable set above, the global setting is the DEFAULT and a
   text-* utility overrides it - .text-primary at theme.css:11617 comes after
   .dropdown-item at :5163 in the same file, so it wins on order at equal
   specificity, with no !important needed on either side. */
<cms:ignore>
    THE WHOLE RULE GOES, NOT JUST THE VALUE.

    These five carry !important, so an empty setting did not merely fail to
    apply - it beat theme.css with a value that then computed to unset. The
    dropdown background was the visible one: on Default, menus were transparent.

    A rule with no declarations left in it is legal CSS and simply does nothing,
    but the declaration is what has to be conditional, so the test wraps the
    whole block and nothing is printed at all.
</cms:ignore>
<cms:if ccs_site_nav_mnu_dd_bg ne ''>
.dropdown-menu {
  background-color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_bg />) !important;
}
</cms:if>
<cms:if ccs_site_nav_mnu_dd_hvr_clr ne ''>
.dropdown-item-wrapper:hover, .dropdown-item-wrapper:focus {
  color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_hvr_clr />) !important;
}
</cms:if>
<cms:ignore>
    :focus as well as :hover - theme.css:22195 sets both to near-black with
    !important, so a rule that only answered :hover left a keyboard user
    tabbing through invisible items.
</cms:ignore>
<cms:if ccs_site_nav_mnu_dd_hvr_clr ne '' || ccs_site_nav_mnu_dd_hvr_bg ne ''>
.dropdown-item:hover,
.dropdown-item:focus {
  <cms:if ccs_site_nav_mnu_dd_hvr_clr ne ''>
  color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_hvr_clr />) !important;
  </cms:if>
  <cms:if ccs_site_nav_mnu_dd_hvr_bg ne ''>
  background-color: var(--ccs-<cms:show ccs_site_nav_mnu_dd_hvr_bg />) !important;
  </cms:if>
}
</cms:if>
<cms:if ccs_site_nav_actv_clr ne ''>
.nav-link.active {
   color: var(--ccs-<cms:show ccs_site_nav_actv_clr />) !important;
}
</cms:if>
<cms:if ccs_site_nav_hvr_clr ne ''>
.nav-item:hover,
.nav-link:hover {
  color: var(--ccs-<cms:show ccs_site_nav_hvr_clr />) !important;
}
</cms:if>

<cms:ignore>
    =========================================================================
    THE DROPDOWN PANEL ON "DEFAULT".

    There is no curated per-theme default for the menu dropdown and there never
    was. ccs_theme_palette() and the chain at the top of this file curate
    fourteen values per theme - the four brand colours, the four utilities,
    light, dark, white, black, body background and body text. Nothing
    nav-dropdown-specific. So with these fields on Default this file emits
    nothing (sec.57) and theme.css's generic values stand:

        --ccs-dropdown-bg            var(--ccs-body-bg)      theme.css:4928
        --ccs-dropdown-link-color    var(--ccs-body-color)   theme.css:4936
        --ccs-dropdown-link-hover-bg var(--ccs-tertiary-bg)  theme.css:4938

    Those ARE theme-aware, which is why nothing looked broken enough to report.
    The failure is subtler: the panel background resolves to the SAME colour as
    the page, and theme.css gives .dropdown-menu no border, so on Notte a
    #0B0C10 panel sits on a #0B0C10 page and the menu reads as text floating in
    space. And --ccs-tertiary-bg is #4b4b4b on every dark theme - a mid grey
    that belongs to none of the curated palettes.

    DERIVED, NOT TABULATED. Eight more rows in the palette table is eight more
    chances to drift, and a ninth theme would arrive with none. These build the
    panel from tokens instead: keep the background opaque on --ccs-body-bg,
    because a dropdown overlays content and must not be see-through, then
    separate it from the page with a border and a shadow drawn from the body
    text colour. Hover comes from the primary at low alpha, so it belongs to
    whatever palette is active.

    GUARDED, so an explicit choice still wins. These only apply while the
    matching field is on Default - the moment someone picks a colour, the
    sec.57 rules above take over.
    =========================================================================
</cms:ignore>
<cms:if ccs_site_nav_mnu_dd_bg eq ''>
.dropdown-menu {
  background-color: var(--ccs-body-bg);
  border: 1px solid rgba(var(--ccs-body-color-rgb), 0.15);
  -webkit-box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.18);
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.18);
}

<cms:ignore>
    ON A DARK THEME THE PANEL HAS TO BE LIFTED, NOT JUST OUTLINED.

    The rule above sets the panel to --ccs-body-bg, which is the page's own
    background - so on Notte a #0B0C10 panel sat on a #0B0C10 page and the
    border and shadow were doing all the work. A 1px hairline and a black
    shadow on a near-black page are close to invisible, so the menu still read
    as text floating in space. The panel has to be a different colour.

    background-image, NOT a translucent background-color. A dropdown overlays
    page content and must stay opaque - anything behind it showing through is
    worse than no panel at all. A linear-gradient with two identical stops is a
    flat wash, and it paints ON TOP of the opaque body-bg underneath, so the
    result is a solid colour that is simply lighter.

    KEYED ON data-bs-theme, which head.htm already stamps on the html element
    for dark, scuro and notte. Light themes keep the clean body-bg panel above,
    which is what they should have - a white menu on a white page separated by
    a border and shadow is the conventional light-mode treatment, and tinting
    it grey would look dirty rather than elevated.

    13% lands each dark theme clearly clear of its page:
        notte   #0B0C10 -> about #232428
        scuro   #222831 -> about #3C424A
        dark    #404040 -> about #555555
</cms:ignore>
[data-bs-theme="dark"] .dropdown-menu {
  background-image: -webkit-gradient(linear, left top, left bottom,
                    from(rgba(var(--ccs-body-color-rgb), 0.13)),
                    to(rgba(var(--ccs-body-color-rgb), 0.13)));
  background-image: linear-gradient(rgba(var(--ccs-body-color-rgb), 0.13),
                                    rgba(var(--ccs-body-color-rgb), 0.13));
  border-color: rgba(var(--ccs-body-color-rgb), 0.22);
  -webkit-box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.5);
  box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.5);
}
</cms:if>
<cms:if ccs_site_nav_mnu_dd_hvr_bg eq ''>
.dropdown-item:hover,
.dropdown-item:focus {
  background-color: rgba(var(--ccs-primary-rgb), 0.12);
}
</cms:if>

<cms:ignore>
    =========================================================================
    DROPDOWN ITEM TEXT ON "DEFAULT" - TWO HARDCODED NEAR-BLACKS IN theme.css.

    Default is supposed to mirror the top-level menu, and it could not, because
    two rules in theme.css outrank everything this file writes:

      theme.css:24452
        .navbar-top .navbar-nav-top .dropdown .dropdown-item
            color: rgba(12, 21, 26, 0.6)
        Four classes - specificity (0,4,0). A literal near-black at 60%.

      theme.css:22195
        .navbar .dropdown-item:hover, .navbar .dropdown-item:focus
            color: var(--ccs-gray-1100) !important
        --ccs-gray-1100 is #0c151a. Near-black again, and !important.

    On a dark panel both are invisible, which is why the items read as empty
    while the top-level links were fine - the top level takes its colour from
    --ccs-nav-link-color, which nothing in theme.css overrides at that weight.

    theme.css DOES have a light answer for these - :24785 and :24821 - but both
    are scoped to .navbar-dark and .navbar-secondary, and this navbar carries
    neither. Its classes are navbar navbar-top navbar-expand-lg plus a bg-
    utility, so those rules never match on any theme.

    MIRRORING, NOT A NEW COLOUR. The base takes --ccs-nav-link-color, the exact
    variable the top-level links use, so the two can never drift. Hover takes
    --ccs-link-hover-color, which is what .navbar-nav feeds to
    --ccs-nav-link-hover-color for the top level. Default now means "the same as
    the menu above", which is what it always read as.

    NO !important ON THE BASE, DELIBERATELY. It only has to match (0,4,0) and
    load later, which it does. Leaving it off means a per-item text- utility
    still wins - so if the navbar's own Text Color is set, the dropdown follows
    it, which is the mirroring behaviour one level up.

    !important IS REQUIRED ON HOVER, because :22195 carries it.
    =========================================================================
</cms:ignore>
<cms:if ccs_site_nav_mnu_dd_txt_clr eq ''>
.navbar-top .navbar-nav-top .dropdown .dropdown-item {
  color: var(--ccs-nav-link-color);
}
</cms:if>
<cms:if ccs_site_nav_mnu_dd_hvr_clr eq ''>
.navbar .dropdown-item:hover,
.navbar .dropdown-item:focus,
.navbar-top .navbar-nav-top .dropdown .dropdown-item:hover,
.navbar-top .navbar-nav-top .dropdown .dropdown-item:focus {
  color: var(--ccs-link-hover-color) !important;
}
</cms:if>


<cms:if ccs_site_ftr_hvr_clr ne ''>
/* Footer links. Beats the hardcoded .link-COLOR:hover ramp in theme.css
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

<cms:ignore>
    SAME TRAP, AND THIS ONE WAS THE WORST OF THEM.

    Both Text Selection Colors default to blank, and both were interpolated into
    a token name, so an untouched site emitted a transparent background and an
    unset color - with !important, so the browser's own blue selection could
    not come back. Dragging across text appeared to do nothing at all.

    Now nothing is printed unless at least one color is set, and each
    declaration is independent so setting only one of the two still leaves the
    native highlight doing the other half.
</cms:ignore>
<cms:if ccs_site_slct_bg ne '' || ccs_site_slct_clr ne ''>
::selection {
  <cms:if ccs_site_slct_bg ne ''>
  background-color: var(--ccs-<cms:show ccs_site_slct_bg />) !important;
  </cms:if>
  <cms:if ccs_site_slct_clr ne ''>
  color: var(--ccs-<cms:show ccs_site_slct_clr />) !important;
  </cms:if>
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
/* Removes the hardcoded 100vh quota so modular sections snap together.

   The template test that used to gate this rule lives in
   pb_mods/pg_frame/main-cap.htm now, which puts .content-flush on the wrapper
   for exactly the same templates. Moved on 2026-09-04 because it was the only
   thing left in this file that varied by page: with it gone the stylesheet is
   identical for every page, so it can be cached once for the site instead of
   once per page (and once per tile in the PageBuilder canvas).

   Measured before the move: user.php with no params and user.php as the home
   page requested it differed by these three lines and nothing else. */
.content-flush {
    min-height: unset !important;
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
   with a section background (a color or gradient on the <section>) paints
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
   Deliberately no color: .btn-link already supplies hover/active color from
   the site theme, and the link's own color comes from the block's Nav Link
   Text Color dropdown. Weight + underline are the only affordances added here,
   so this rule never fights a user-chosen color.
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
   Center drops the indent entirely: an indent read from one edge is
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
