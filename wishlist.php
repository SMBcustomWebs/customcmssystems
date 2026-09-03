<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='Wishlist' parent="_donottouch_" clonable='1' hidden='1' access_level='7'>
    <cms:globals>
		<cms:editable type="checkbox" name="visible" opt_value="hide section" />
	</cms:globals>
    <!-- The ID of the user who saved the item.
         Written from ccs_auth_uid ($AUTH->user->id), NOT from k_user_id -
         k_user_id is overwritten by the extended-users addon and means
         two different things depending on install state. See
         snippets/utils/auth_uid.htm. -->
    <cms:editable name='wish_user_id' label='User ID' type='text' search_type='integer' />

    <!-- WHICH TEMPLATE the saved item belongs to, e.g. product.php or
         service.php. Without this the display views had to guess, and
         both hardcoded product.php - so a saved service counted toward
         the badge but rendered nothing, making the list look empty.
         Keeps the wishlist template-agnostic the same way
         tl_if_pb_emb.html keeps page rendering template-agnostic. -->
    <cms:editable name='wish_masterpage' label='Source Template' type='text' search_type='text' />

    <!-- The ID of the base item they saved (page id within wish_masterpage) -->
    <cms:editable name='wish_product_id' label='Item Page ID' type='text' search_type='integer' />

    <!-- RAW cart option string (os0: 0 | on0: Size | ...).
         Load-bearing: assets/js/ccs_js.js:261-269 splits this back into
         cart field names when moving the item to the cart. Never reformat. -->
    <cms:editable name='wish_variants' label='Selected Variants (raw)' type='textarea' />

    <!-- Human-readable copy of the same selection, built by the browser
         from the visible <option> text at save time. Display this to
         users; never parse it. -->
    <cms:editable name='wish_variants_display' label='Selected Variants (readable)' type='textarea' />

     <cms:config_list_view exclude='default-page' searchable='1' >
     
     </cms:config_list_view>


</cms:template>

<cms:embed 'tl_if_pb_emb.html' />
<?php COUCH::invoke(); ?>