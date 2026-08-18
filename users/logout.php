<?php require_once( '../ccs_dash/cms.php' ); ?>
<cms:template title='Logout Processor' hidden='1' />

<cms:ignore>
    ============================================================
    WHY THIS FILE LOOKS DIFFERENT NOW
    ------------------------------------------------------------
    Previously the whole cms:if / redirect block lived INSIDE the
    opening and closing cms:template tags. That block is a
    declaration only - Couch reads it to define editable regions and
    template config, it is not page output. So none of the logic ran,
    no redirect fired, and the browser just sat on users/logout.php
    showing a blank page.

    The template tag is now self-closed and all logic lives after
    it, matching cart.php / checkout.php / cart-modal.php.
    ============================================================
</cms:ignore>

<cms:no_cache />

<cms:php>
    /* ------------------------------------------------------------
       Work out a SAFE place to send them after logout.

       Order of preference:
         1. an explicit ?ret= on the logout link
         2. the page they came from (HTTP_REFERER)
         3. the site root

       Two hard rules:
         - same-origin only. Never bounce to an off-site URL that
           happened to be in the referer.
         - never return to a page that is meaningless once logged
           out. The cart is emptied below, and checkout / account
           pages would just bounce or error, so those go to the
           site root instead.
       ------------------------------------------------------------ */
    global $CTX;

    $site = K_SITE_URL;
    $dest = '';

    $cand = isset($_GET['ret']) ? trim($_GET['ret']) : '';
    if( !strlen($cand) && isset($_SERVER['HTTP_REFERER']) ){
        $cand = trim( $_SERVER['HTTP_REFERER'] );
    }

    if( strlen($cand) && strpos($cand, $site) === 0 ){
        $tail = strtolower( substr($cand, strlen($site)) );

        $blocked = array(
            'cart.php',
            'checkout.php',
            'cart-modal.php',
            'wishlist.php',
            'wishlist-modal.php',
            'users/'
        );

        $ok = true;
        foreach( $blocked as $b ){
            if( strpos($tail, $b) === 0 ){ $ok = false; break; }
        }
        if( $ok ){ $dest = $cand; }
    }

    if( !strlen($dest) ){ $dest = $site; }

    $CTX->set( 'ccs_logout_dest', $dest, 'global' );
</cms:php>

<cms:if k_logged_in>

    <cms:ignore>
        Clear the CouchCart session data.

        Load-bearing, not belt-and-braces. The cart lives in
        $_SESSION (addons/cart/session.php:97), and AUTH->logout()
        only calls delete_cookie() (auth/auth.php:205-210) - it never
        touches the session. Without this the previous user's cart
        survives logout and the next person on the machine inherits
        it. Matches the agreed design: wishlist persists to the user,
        cart does not survive logout.
    </cms:ignore>
    <cms:pp_empty_cart />

    <cms:ignore>
        Hand off to Couch's real logout.

        cms:logout_link (registered extended-users.php:777) builds a
        URL carrying a valid nonce. AUTH->logout() runs
        validate_nonce() before deleting the cookie, and
        validate_nonce (functions.php:2170) falls back to
        $_REQUEST['nonce'], which is why users/login.php can call a
        bare cms:process_logout and still validate.

        The redirect= value rides through to that page, where
        process_logout's default redirect='2' means "use the
        querystring param named redirect".

        Destination is built into a variable first: writing it inline
        would nest a double-quoted attribute inside another
        double-quoted attribute and the inner quote would close the
        outer one.
    </cms:ignore>
    <cms:redirect url="<cms:logout_link redirect=ccs_logout_dest />" />

<cms:else />

    <!-- Already logged out - just send them somewhere sensible -->
    <cms:redirect url=ccs_logout_dest />

</cms:if>

<?php COUCH::invoke(); ?>
