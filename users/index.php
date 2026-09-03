<?php require_once( '../ccs_dash/cms.php' ); ?>

	<cms:template clonable='1' title='Users' hidden='1'>

		<cms:ignore>
			Name is two fields, not one. Card networks and address
			verification match on first and last separately, so the split
			has to be real - deriving it from the display name guesses
			wrong on any name with more than two words.
		</cms:ignore>
		<cms:editable type='row' name='user_name_row' order='0'>
			<cms:editable type='text' name='user_first_name' label='First Name' class='col-md-6' />
			<cms:editable type='text' name='user_last_name'  label='Last Name'  class='col-md-6' />
		</cms:editable>

		<!-- Shipping Address Fields -->
		<cms:editable type='row' name='shipping_row_1' order='1'>
			<cms:editable type='text' name='shipping_address' label='Shipping Street Address' class='col-md-12' />
		</cms:editable>

		<cms:editable type='row' name='shipping_row_2' order='2'>
			<cms:editable type='text' name='shipping_city' label='Shipping City' class='col-md-4' />
			<cms:editable type='text' name='shipping_state' label='Shipping State' class='col-md-4' />
			<cms:editable type='text' name='shipping_zip' label='Shipping Zip Code' class='col-md-4' />
		</cms:editable>

		<!-- Billing Address Fields -->
		<cms:editable type='row' name='billing_row_1' order='3'>
			<cms:editable type='text' name='billing_address' label='Billing Street Address' class='col-md-12' />
		</cms:editable>

		<cms:editable type='row' name='billing_row_2' order='4'>
			<cms:editable type='text' name='billing_city' label='Billing City' class='col-md-4' />
			<cms:editable type='text' name='billing_state' label='Billing State' class='col-md-4' />
			<cms:editable type='text' name='billing_zip' label='Billing Zip Code' class='col-md-4' />
		</cms:editable>

	</cms:template>    


<?php COUCH::invoke(); ?>