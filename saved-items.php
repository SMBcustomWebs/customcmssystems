<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Saved Items" parent='_site_' icon='heart' order="79" />

<cms:ignore>
    ============================================================
    WHY THIS TEMPLATE EXISTS
    ------------------------------------------------------------
    wishlist.php is a DATA template - one page per saved item. Its
    bare URL is therefore the template's "home" view, and the
    k_is_home branch of tl_if_pb_emb.html (line 47) renders only
    pagebuilders; it never embeds single_content. So the saved-items
    list could never appear at wishlist.php no matter what was
    registered. That is structural, not a registration problem.

    This is a plain non-clonable page built the same way cart.php is:
    frame embeds around session-derived content. No route and no
    query string are needed - the logged-in user's identity already
    travels with every request via the auth cookie, and
    snippets/utils/auth_uid.htm turns that into ccs_auth_uid, which
    is what the list filters on.

    The routes addon is active but used nowhere in this site; routes
    exist for URL patterns and parameters, and this page has neither.
    ============================================================
</cms:ignore>

<cms:no_cache />

<cms:embed 'pb_mods/pg_frame/head.htm' />
<cms:set my_redirect_link = k_page_link />
<cms:embed 'pb_mods/pg_frame/main-cap.htm' />
<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />

<cms:ignore>
    The list markup is shared with the offcanvas panel's sibling
    file. Kept as a snippet so the page and the panel cannot drift
    apart, exactly as cart.php / cart-modal.php do.
</cms:ignore>
<cms:embed 'single/wishlist.html' />

<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>
