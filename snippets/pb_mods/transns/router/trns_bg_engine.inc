<cms:ignore>
    =========================================================================
    TRANSITION BANNER  -  THREE-LAYER BACKGROUND ENGINE
    snippets/pb_mods/transns/router/trns_bg_engine.inc

    Embed this near the top of any transition theme, then emit the three layer
    divs and cms:show trns_local_css inside the section element.

    Modelled on hero/theme/hero_custom_settings.html, with three corrections
    noted inline: per-tile CSS scoping is applied to EVERY rule, the parallax
    guard tests the dropdown's value rather than its label, and no bg-* class is
    ever put on the section element itself.

    LAYER ORDER, bottom to top:
        .trns-base      background color / gradient
        .bg-holder      the image, at its own opacity so the color bleeds up
        .trns-overlay   color tint over everything

    All three are .bg-holder, which theme.css defines as position:absolute with
    z-index:-1. That negative z-index is why the section element must NOT carry a
    bg-* color class: a background painted on the section itself sits ABOVE a
    z-index:-1 child and hides all three layers. CLAUDE.md 6a records this trap;
    the old build hit it, which is why its own edit tooltip read "may need to
    remove color to see". Giving the color its own layer fixes it.
    =========================================================================
</cms:ignore>

<!-- ======================================================= -->
<!-- INITIALISE EVERY CONDITIONALLY-SET VAR                  -->
<!-- __ROOT__ outlives a tile, so a var set only inside a    -->
<!-- cms:if would carry into the NEXT banner on the page.    -->
<!-- This is the real "several banners on one page" bug -    -->
<!-- it is a scope problem, not a naming one.                -->
<!-- ======================================================= -->
<cms:set trns_base_css = '' scope='global' />
<cms:set trns_ovrly_css = '' scope='global' />
<cms:set trns_prlx_css = '' scope='global' />
<cms:set trns_local_css = '' scope='global' />
<cms:set trns_img_style = '' scope='global' />
<cms:set trns_show_base = '0' scope='global' />
<cms:set trns_show_img = '0' scope='global' />
<cms:set trns_show_ovrly = '0' scope='global' />

<cms:ignore>
    PER-TILE SCOPE ID.
    Every rule below is prefixed with this, so two banners on one page cannot
    overwrite each other's colors. This is the fix for the old
    "Page can only have one image overlay set" warning.
    cms:random_name is regenerated per render, which is fine - the id only has
    to be unique within one response, and the style block travels with it.
</cms:ignore>
<cms:set trns_local_id = "trns_<cms:random_name />" scope='global' />


<!-- ======================================================= -->
<!-- LAYER 1  -  BACKGROUND COLOR                           -->
<!-- ======================================================= -->
<cms:ignore>
    all-colors.htm leads with 'Default Color=transparent'. Both 'transparent'
    and an empty value mean "no color layer", so the base div is skipped
    entirely rather than emitted as an invisible extra element.
</cms:ignore>
<cms:if trns_bg_clr_opt && trns_bg_clr_opt != 'transparent'>
    <cms:set trns_show_base = '1' scope='global' />

    <cms:if trns_bg_clr_opt = 'gradient'>
        <cms:if trns_bg_clr_cstm_grdt_opt = '0'>
            <cms:capture into='trns_base_css' scope='global'>#<cms:show trns_local_id /> .trns-base { background-color: <cms:show trns_bg_clr_cstm_one />; }</cms:capture>
        <cms:else />
            <cms:if trns_bg_clr_grad_rvrs_opt = '1'>
                <cms:capture into='trns_base_css' scope='global'>#<cms:show trns_local_id /> .trns-base { background-image: linear-gradient(<cms:show trns_bg_clr_grad_split_opt />deg, <cms:show trns_bg_clr_cstm_two />, <cms:show trns_bg_clr_cstm_one />); }</cms:capture>
            <cms:else />
                <cms:capture into='trns_base_css' scope='global'>#<cms:show trns_local_id /> .trns-base { background-image: linear-gradient(<cms:show trns_bg_clr_grad_split_opt />deg, <cms:show trns_bg_clr_cstm_one />, <cms:show trns_bg_clr_cstm_two />); }</cms:capture>
            </cms:if>
        </cms:if>
    <cms:else />
        <cms:ignore>
            A theme token resolves through the CSS variable rather than a bg-*
            utility class, because the utility would have to go on the section -
            the exact thing that breaks the z-index:-1 stack.
            trim is applied because several values in all-colors.htm carry
            trailing spaces ('primary  '), which would break var(--ccs-primary  ).
        </cms:ignore>
        <cms:capture into='trns_base_css' scope='global'>#<cms:show trns_local_id /> .trns-base { background-color: var(--ccs-<cms:trim trns_bg_clr_opt />); }</cms:capture>
    </cms:if>
</cms:if>


<!-- ======================================================= -->
<!-- LAYER 2  -  BACKGROUND IMAGE                            -->
<!-- ======================================================= -->
<cms:ignore>
    Inline style rather than a CSS rule: the image URL is per-tile data, and
    putting it inline keeps the style block to the parts that genuinely need
    a selector. opacity is what lets layer 1 bleed through - that was the
    stated goal of the whole "both at once" design.
</cms:ignore>
<cms:if trns_bg_img_opt = '1' && trns_bg_img>
    <cms:set trns_show_img = '1' scope='global' />
    <cms:capture into='trns_img_style' scope='global'>background-image: url('<cms:show trns_bg_img />');<cms:if trns_bg_img_pos> background-position: <cms:show trns_bg_img_pos />;</cms:if><cms:if trns_bg_img_opc> opacity: <cms:show trns_bg_img_opc />;</cms:if></cms:capture>
</cms:if>


<!-- ======================================================= -->
<!-- LAYER 3  -  COLOR OVERLAY                              -->
<!-- ======================================================= -->
<cms:ignore>
    overlay-colors.htm leads with 'No Overlay=' (empty), so an untouched banner
    emits no overlay layer at all.

    For a theme token the opacity rides in the rgba() alpha via the -rgb
    variable, which is how theme.css expresses its colors. For a custom color
    the alpha is already in the picked value, so opacity is applied to the layer
    instead - applying both would compound them.
</cms:ignore>
<cms:if trns_ovrly_clr_opt>
    <cms:set trns_show_ovrly = '1' scope='global' />

    <cms:ignore>
        'custom' - not 'gradient'. The overlay dropdown now comes from
        _tok/color-overlay.htm, whose custom escape stores custom. Keep this
        test and that file in step; they are a matched pair.
    </cms:ignore>
    <cms:if trns_ovrly_clr_opt = 'custom'>
        <cms:if trns_ovrly_grdt_opt = '0'>
            <cms:capture into='trns_ovrly_css' scope='global'>#<cms:show trns_local_id /> .trns-overlay { background-color: <cms:show trns_ovrly_clr_cstm_one />;<cms:if trns_ovrly_opc> opacity: <cms:show trns_ovrly_opc />;</cms:if> }</cms:capture>
        <cms:else />
            <cms:if trns_ovrly_grad_rvrs_opt = '1'>
                <cms:capture into='trns_ovrly_css' scope='global'>#<cms:show trns_local_id /> .trns-overlay { background-image: linear-gradient(<cms:show trns_ovrly_grad_split_opt />deg, <cms:show trns_ovrly_clr_cstm_two />, <cms:show trns_ovrly_clr_cstm_one />);<cms:if trns_ovrly_opc> opacity: <cms:show trns_ovrly_opc />;</cms:if> }</cms:capture>
            <cms:else />
                <cms:capture into='trns_ovrly_css' scope='global'>#<cms:show trns_local_id /> .trns-overlay { background-image: linear-gradient(<cms:show trns_ovrly_grad_split_opt />deg, <cms:show trns_ovrly_clr_cstm_one />, <cms:show trns_ovrly_clr_cstm_two />);<cms:if trns_ovrly_opc> opacity: <cms:show trns_ovrly_opc />;</cms:if> }</cms:capture>
            </cms:if>
        </cms:if>
    <cms:else />
        <cms:ignore>
            THEME COLOR - and NO fabricated alpha value.

            The previous form read
                rgba(var(--ccs-X-rgb), cms:if opc / show opc / cms:else / 1 )
            which substituted a hardcoded 1 whenever the transparency field was
            empty. That is a default by the back door: it made "user never
            touched this" and "user deliberately chose fully opaque" compile to
            identical CSS, so the router was deciding something nobody chose.

            Simply deleting the 1 is not enough - it would emit
            rgba(var(--ccs-black-rgb), ) which is invalid and paints nothing.
            So the two cases are split, and each branch is complete CSS on its
            own: an alpha is used only when the user actually set one.
        </cms:ignore>
        <cms:if trns_ovrly_opc>
            <cms:capture into='trns_ovrly_css' scope='global'>#<cms:show trns_local_id /> .trns-overlay { background-color: rgba(var(--ccs-<cms:trim trns_ovrly_clr_opt />-rgb), <cms:show trns_ovrly_opc />); }</cms:capture>
        <cms:else />
            <cms:capture into='trns_ovrly_css' scope='global'>#<cms:show trns_local_id /> .trns-overlay { background-color: var(--ccs-<cms:trim trns_ovrly_clr_opt />); }</cms:capture>
        </cms:if>
    </cms:if>
</cms:if>


<!-- ======================================================= -->
<!-- PARALLAX ZOOM                                           -->
<!-- ======================================================= -->
<cms:ignore>
    A rellax layer is translated vertically as the page scrolls, so it must be
    taller than its section or the edges pull away and show a gap. The taller
    the travel, the bigger the buffer - hence the arithmetic rather than a
    fixed value.

    GUARD FIX: hero tests ccs_hro_bg_scrl_spd != 'No Scrolling Effect', but
    prlx-scrl.htm emits '0' for that option - the string it compares against is
    the LABEL, never the stored value, so hero's parallax maths runs
    unconditionally. Testing the real value is the correction.
</cms:ignore>
<cms:if trns_fx_opt && trns_fx_opt != '0'>
    <cms:set trns_prlx_abs = trns_fx_opt />
    <cms:if trns_prlx_abs lt '0'>
        <cms:set trns_prlx_abs = "<cms:mul trns_prlx_abs '-1' />" />
    </cms:if>
    <cms:set trns_prlx_top = "<cms:mul trns_prlx_abs '8' />" />
    <cms:set trns_prlx_ht = "<cms:add '100' "<cms:mul trns_prlx_top '2' />" />" />
    <cms:capture into='trns_prlx_css' scope='global'>#<cms:show trns_local_id /> .bg-holder[data-parallax] { height: <cms:show trns_prlx_ht />%; top: -<cms:show trns_prlx_top />%; }</cms:capture>
</cms:if>


<!-- ======================================================= -->
<!-- ASSEMBLE                                                -->
<!-- ======================================================= -->
<cms:ignore>
    One style block per tile, emitted only when it has something in it, so a
    banner with no color and no overlay adds no bytes at all.

    Note there is no !important anywhere. hero needs it because it is competing
    with utility classes on the same elements; here the layers are dedicated
    divs that nothing else targets, so specificity alone is enough. That keeps
    the rules overridable from user.php if you ever need to.
</cms:ignore>
<cms:capture into='trns_local_css' scope='global'><cms:show trns_base_css /><cms:show trns_ovrly_css /><cms:show trns_prlx_css /></cms:capture>
