<?php require_once( '../ccs_dash/cms.php' ); ?>
<cms:template title='Logout Processor' hidden='1'>
    
    <cms:if k_logged_in>
        
        <!--
            1. Clear the CouchCart session data.

            This is load-bearing, not belt-and-braces. The cart lives in
            $_SESSION['kcart'] (addons/cart/cart.php:194, last line of
            serialize()), and AUTH->logout() only calls delete_cookie() - it
            never touches the session. Without this line the previous user's
            cart survives logout and the next person on the machine inherits it.
        -->
        <cms:pp_empty_cart />

        <!--
            2. Hand off to Couch's real logout.

            Must be logout_link, not login_link. login_link_handler
            (addons/extended/extended-users.php:703) accepts only 'redirect' -
            there is no 'action' parameter, so action='logout' was silently
            ignored and this returned the plain login URL. The user arrived
            still authenticated, login.php saw k_logged_in and bounced them to
            the dashboard, and the logout never happened.

            get_logout_link() (functions.php:1783) builds
                login.php?act=logout&nonce=...&redirect=...
            The nonce matters: AUTH->logout() calls validate_nonce() before
            deleting the cookie, so a link without one is refused outright.
        -->
        <cms:ignore>
            Destination is built into a variable first. Writing it inline would
            nest a double-quoted attribute inside another double-quoted
            attribute (url="...redirect="..."...") and the inner quote closes
            the outer one. Passing the variable unquoted sidesteps it.
        </cms:ignore>
        <cms:set nswp_logout_dest="<cms:show k_site_link />" />
        <cms:redirect url="<cms:logout_link redirect=nswp_logout_dest />" />
        
    <cms:else />
        
        <!-- Fallback: If they access this page but are already logged out, send them home -->
        <cms:redirect url="<cms:show k_site_link />" />
        
    </cms:if>

</cms:template>
<?php COUCH::invoke(); ?>