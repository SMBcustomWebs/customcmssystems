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

		<cms:ignore>
			MARKETING CONSENT LIVES ON THE ACCOUNT, NOT ONLY ON THE LIST.

			subscribers.php is the sending list; this is the account holder's
			answer to "do you want marketing email". They are kept in step by
			register.php and profile.php - ticking here writes a subscribers.php
			row, unticking clears it - but they are deliberately two things.

			WHY NOT JUST READ THE LIST. A subscriber row is erased on
			unsubscribe (see unsubscribe.php - the address is blanked, which is
			the point), so it cannot answer "did this person decline" later. The
			account field can, which is what stops a profile page from showing a
			box ticked because nobody knows any better.

			A CHECKBOX, AND IT STARTS UNTICKED. Registering is not asking for
			marketing - the person came to buy something - so the tick is a
			separate act with its own wording next to it. That is the opposite
			of the email-only contact tile, where sending the form IS the
			sign-up and the notice says so.
		</cms:ignore>
		<cms:editable type='row' name='user_mkt_row' order='5'>
			<cms:editable type='checkbox' name='user_marketing_opt' label='Marketing Emails'
				desc='Set by the account holder at sign-up or in their profile. Ticking adds their address to Subscribers; unticking removes it. Changing it here does neither - use the list itself.'
				opt_values='Send promotional and marketing emails=1' class='col-md-12' />
		</cms:editable>


		<cms:ignore>
			=================================================================
			ACCOUNT DELETION - THE PENDING REQUEST.

			Both hidden, because neither is anybody's to type. The token is
			written when the account holder asks to close the account from
			their profile, and the link mailed to them carries it. Clicking
			that link anonymizes the account and clears both fields, so a
			used link cannot be used twice.

			LABELLED, BECAUSE hidden='1' DOES NOT HIDE. On a cms:editable that
			attribute is ignored - the field renders anyway, with its raw name
			where the label should be, which is how subscriber_email has been
			showing as "subscriber_email" all along. If it is going to be on
			the form it may as well say what it is and warn against typing in
			it. Corrected 2026-09-20.

			delete-account.php finds the account with custom_field on
			user_del_token, the same way subscribers.php is searched on
			subscriber_email.

			A row with a token and a timestamp is a request nobody completed.
			They expire after 24 hours; the link says so and the page
			enforces it.
			=================================================================
		</cms:ignore>
		<cms:editable type='row' name='user_del_row' order='6'>
			<cms:editable type='text' name='user_del_token' label='Account Deletion Token'
				desc='Written when the account holder asks to close the account, cleared the moment the emailed link is opened. A value here is a request nobody finished. Do not type in this field.'
				class='col-md-6' />
			<cms:editable type='text' name='user_del_req_ts' label='Deletion Requested'
				desc='When they asked. The link expires 24 hours after this.'
				class='col-md-6' />
		</cms:editable>

	</cms:template>    


<?php COUCH::invoke(); ?>