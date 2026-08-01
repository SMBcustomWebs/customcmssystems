<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='Wishlist' parent="_donottouch_" clonable='1' hidden='1'>
    <cms:globals>
		<cms:editable type="checkbox" name="visible" opt_value="hide section" />
	</cms:globals>
    <!-- The ID of the user who saved the item -->
    <cms:editable name='wish_user_id' label='User ID' type='text' search_type='integer' />
    
    <!-- The ID of the base product they saved -->
    <cms:editable name='wish_product_id' label='Product ID' type='text' search_type='integer' />
    
    <!-- The exact variants they selected (e.g., Size: Large | Color: Red) -->
    <cms:editable name='wish_variants' label='Selected Variants' type='textarea' />

</cms:template>

<cms:embed 'tl_if_pb_emb.html' />
<?php COUCH::invoke(); ?>