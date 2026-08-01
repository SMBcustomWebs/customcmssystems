<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Wishlist Action Processor" parent='_donottouch_' hidden='1' />
<!-- SECURITY LOCKDOWN: Only allow POST requests, unless user is Super Admin -->
<cms:if k_method ne 'POST'>
    <cms:if k_user_access_level lt '10'>
        <cms:redirect k_site_link />
    </cms:if>
</cms:if>
<cms:no_cache />

<cms:set my_action="<cms:gpc 'wishlist_action' method='post' />" />

<cms:if my_action eq 'delete_item'>
    <cms:set my_del_id="<cms:gpc 'entry_id' method='post' />" />
    
    <cms:if my_del_id>
        <!-- Elevate script to Super Admin (Level 10) so it has permission to edit -->
        <cms:php>
            global $AUTH, $CTX;
            $CTX->set('old_access_level', $AUTH->user->access_level, 'global');
            $AUTH->user->access_level = 10;
        </cms:php>
        
        <!-- THE FIX: We edit the page, unpublish it, and detach the User ID! -->
        <cms:db_persist _masterpage='wishlist.php' _mode='edit' _page_id=my_del_id k_publish_date='0000-00-00 00:00:00' wish_user_id='' />
        
        <!-- Immediately restore normal permissions -->
        <cms:php>
            global $AUTH, $CTX;
            $AUTH->user->access_level = $CTX->get('old_access_level');
        </cms:php>

        <cms:if k_error>
            <cms:abort>ERROR: <cms:show k_error /></cms:abort>
        <cms:else />
            <cms:abort>WISHLIST_DELETED</cms:abort>
        </cms:if>
    <cms:else />
        <cms:abort>ERROR: Missing ID</cms:abort>
    </cms:if>
</cms:if>

<?php COUCH::invoke(); ?>