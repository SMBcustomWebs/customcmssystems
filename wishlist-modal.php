<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Wishlist Action Processor" parent='_donottouch_' hidden='1' />
<!-- SECURITY LOCKDOWN: Only allow POST requests, unless user is Super Admin.
     'k_method' is NOT a Couch variable - set nowhere in core or any addon -
     so the old test read as ('' ne 'POST'), always true. That accidentally
     blocked everyone below level 10 and broke wishlist delete for real
     customers. $_SERVER['REQUEST_METHOD'] is what core uses (header.php:79). -->
<cms:php>
    global $CTX;
    $CTX->set( 'ccs_req_method', $_SERVER['REQUEST_METHOD'], 'global' );
</cms:php>
<cms:if ccs_req_method ne 'POST'>
    <cms:if k_user_access_level lt '10'>
        <cms:redirect k_site_link />
    </cms:if>
</cms:if>
<cms:no_cache />

<!-- This fragment does not embed the page frame, so it must pull in the
     stable user id itself. -->
<cms:embed 'utils/auth_uid.htm' />

<!-- Must be logged in at all. The old code reached the elevation block
     without ever checking this. -->
<cms:if k_logged_out || (ccs_auth_uid eq '')>
    <cms:abort>ERROR: Not logged in</cms:abort>
</cms:if>

<cms:set my_action="<cms:gpc 'wishlist_action' method='post' />" />

<cms:ignore>
    RENDER MODE
    -----------
    Returns exactly the same fragment the offcanvas shell embeds on
    first load, so refreshWishlistModal() in assets/js/ccs_js.js can
    replace #wishlist-modal-body without a page reload.

    Without this the panel was server-rendered once and never updated:
    saving an item bumped the badge (client-side arithmetic in the
    product page's JS) while the list itself stayed stale until the
    user navigated away - which read as "the save didn't work".

    Same shape as cart-modal.php, which is why the cart never had
    this problem. cms:abort stops here so none of the delete logic
    below can run on a render request.
</cms:ignore>
<cms:if my_action eq 'render'>
    <cms:abort><cms:embed 'e_modals/wishlist_body.html' /></cms:abort>
</cms:if>

<cms:if my_action eq 'delete_item'>
    <cms:set my_del_id="<cms:gpc 'entry_id' method='post' />" />

    <cms:if my_del_id>

        <!-- OWNERSHIP CHECK - do not remove.
             This script elevates itself to access level 10 in order to write.
             Without proving the entry belongs to the caller first, any logged-in
             user could delete any other user's wishlist entry by passing its id
             (an IDOR). The old guard hid this only by accident, because it
             blocked everyone under level 10; correcting the guard above is
             exactly what would have exposed it. -->
        <cms:set my_owner_ok='0' scope='global' />
        <cms:pages masterpage='wishlist.php' id=my_del_id limit='1'>
            <cms:if wish_user_id && (wish_user_id eq ccs_auth_uid)>
                <cms:set my_owner_ok='1' scope='global' />
            </cms:if>
        </cms:pages>

        <cms:if my_owner_ok eq '1'>

            <!-- Elevate script to Super Admin (Level 10) so it has permission to edit -->
            <cms:php>
                global $AUTH, $CTX;
                $CTX->set('old_access_level', $AUTH->user->access_level, 'global');
                $AUTH->user->access_level = 10;
            </cms:php>

            <!-- Edit the page, unpublish it, and detach the User ID -->
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
            <cms:abort>ERROR: Not your entry</cms:abort>
        </cms:if>

    <cms:else />
        <cms:abort>ERROR: Missing ID</cms:abort>
    </cms:if>
</cms:if>

<?php COUCH::invoke(); ?>