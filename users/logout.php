<?php require_once( '../ccs_dash/cms.php' ); ?>
<cms:template title='Logout Processor' hidden='1'>
    
    <cms:if k_logged_in>
        
        <!-- 1. Intercept and clear the CouchCart session data -->
        <cms:pp_empty_cart />
        
        <!-- 2. Redirect to Couch's native, secure logout URL to kill the auth session -->
        <cms:redirect url="<cms:login_link action='logout' />" />
        
    <cms:else />
        
        <!-- Fallback: If they access this page but are already logged out, send them home -->
        <cms:redirect url="<cms:show k_site_link />" />
        
    </cms:if>

</cms:template>
<?php COUCH::invoke(); ?>