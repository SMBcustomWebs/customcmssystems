<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Maintenance Holding Page" hidden='1' order='9990' />

<cms:ignore>
    ===========================================================================
    maintenance.php

    Where public visitors land while ccs_gl_maintenance_mode is ticked.
    utils/maintenance_gate.htm does the redirecting; this file only renders.

    NO WORDING LIVES HERE. Heading, message and the two toggles are all in the
    Maintenance Mode group in globals.php, so the switch and the words it puts
    on screen are edited in the same popup. Nothing to re-register here when the
    text changes.

    NO NAV AND NO FOOTER, DELIBERATELY. Every link in either points at a page
    the gate is about to bounce the visitor away from, and a holding page whose
    menu leads back to itself reads as broken. head.htm and tail.htm are still
    embedded - they carry the theme, the fonts and user.php, which is what makes
    this look like the site rather than a default browser page.

    503, NOT 200. A holding page served as 200 tells a crawler "this is the
    content now" and pages start dropping out of the index. 503 with Retry-After
    says temporarily unavailable, come back. Set before any output, because
    head.htm opens with the doctype on line 1.
    ===========================================================================
</cms:ignore>

<cms:php>
    if( !headers_sent() ){
        header( 'HTTP/1.1 503 Service Unavailable', true, 503 );
        header( 'Retry-After: 3600' );
    }
</cms:php>

<cms:embed 'pb_mods/pg_frame/head.htm' />
<cms:embed 'pb_mods/pg_frame/main-cap.htm' />

<cms:pages masterpage='globals.php' limit='1'>

    <cms:ignore>
        The logo follows the resolved theme mode rather than a setting of its
        own, the same way nav_mdd.html picks it, so it cannot come out
        dark-on-dark here while being correct everywhere else. bs_theme_mode is
        already set by head.htm. Falls back to the square logo, then to the site
        name as text, so this page always has something at the top of it.
    </cms:ignore>
    <cms:if bs_theme_mode == 'dark'>
        <cms:set mnt_logo = ccs_gl_site_logo_hoz_lgt />
        <cms:if mnt_logo eq ''><cms:set mnt_logo = ccs_gl_site_logo_sqr_lgt /></cms:if>
    <cms:else />
        <cms:set mnt_logo = ccs_gl_site_logo_hoz_drk />
        <cms:if mnt_logo eq ''><cms:set mnt_logo = ccs_gl_site_logo_sqr_drk /></cms:if>
    </cms:if>

    <cms:set mnt_head  = ccs_gl_maint_head />
    <cms:set mnt_body  = ccs_gl_maint_body />
    <cms:set mnt_email = ccs_gl_hdqt_email />

    <section class="py-8">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7 text-center">

                    <cms:if mnt_logo ne ''>
                        <img src="<cms:show mnt_logo />"
                            alt="<cms:show ccs_gl_site_name /> logo"
                            class="mb-5" style="max-width: 260px; height: auto; max-height: 70px;" />
                    <cms:else />
                        <h2 class="fs-4 fw-bold mb-5"><cms:show ccs_gl_site_name /></h2>
                    </cms:if>

                    <h1 class="fs-2 fw-bold mb-3"><cms:show mnt_head /></h1>

                    <p class="fs-8 mb-0 opacity-75"><cms:show mnt_body /></p>

                    <cms:if ccs_gl_maint_show_email && (mnt_email ne '')>
                        <hr class="my-5 opacity-25" />
                        <p class="fs-9 mb-0 opacity-75">
                            Need something in the meantime?
                            <a class="text-reset text-decoration-underline"
                                href="mailto:<cms:show mnt_email />"><cms:show mnt_email /></a>
                        </p>
                    </cms:if>

                    <cms:if ccs_gl_maint_show_social>
                        <div class="mt-4">
                            <cms:show_repeatable 'ccs_gl_social'>
                                <a class="p-2 text-reset" href="<cms:show ccs_gl_social_link />"
                                    title="<cms:show ccs_gl_social_name />" target="_blank" rel="noopener">
                                    <span class="<cms:show ccs_gl_social_icon /> fs-7" aria-hidden="true"></span>
                                    <span class="visually-hidden"><cms:show ccs_gl_social_name /></span>
                                </a>
                            </cms:show_repeatable>
                        </div>
                    </cms:if>

                    <cms:ignore>
                        The way back in for an admin who is not signed in. Without
                        it the only route is typing the login URL from memory, and
                        the gate lets that page through precisely so this works.
                    </cms:ignore>
                    <cms:if k_logged_out>
                        <p class="fs-10 mt-6 mb-0 opacity-50">
                            <a class="text-reset text-decoration-none"
                                href="<cms:link 'users/login.php' />">Staff sign in</a>
                        </p>
                    </cms:if>

                </div>
            </div>
        </div>
    </section>

</cms:pages>

<cms:embed 'pb_mods/pg_frame/tail.htm' />

<?php COUCH::invoke(); ?>
