<?php require_once( '../ccs_dash/cms.php' ); ?>
    <cms:template title='User Profile' hidden='1' />
    
    <!-- Security Check: Kick out guests -->
	<cms:if k_logged_out >
		<cms:redirect "<cms:login_link />" />
	</cms:if>

	<cms:embed 'pb_mods/pg_frame/head.htm' />
	<cms:embed 'pb_mods/pg_frame/main-cap.htm' />   
	<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />

	<section class="pt-0">
		<div class="container">

			<div class="row pt-6 pb-4">
				<div class="col-12">
					<h1 class="mb-0">My Account Profile</h1>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-8">

					<!-- Success Message -->
					<cms:set success_msg="<cms:get_flash 'success_msg' />" />
					<cms:if success_msg >
						<div class="alert alert-success mb-4 text-center">
							<i class="fas fa-check-circle me-2"></i> Profile updated successfully.
						</div>
					</cms:if>

					<cms:form masterpage=k_user_template mode='edit' page_id=k_user_id enctype="multipart/form-data" method='post' anchor='0'>

						<cms:if k_success>
							<cms:db_persist_form />

							<cms:if k_success >
								<cms:set_flash name='success_msg' value='1' />
								<cms:redirect k_page_link /> 
							</cms:if>
						</cms:if>

						<cms:if k_error>
							<div class="alert alert-danger mb-4">
								<cms:each k_error>
									<cms:show item /><br>
								</cms:each>
							</div>
						</cms:if>

						<h4 class="mb-3">Account Details</h4>
						<div class="row mb-4">
							<div class="col-md-6 mb-3">
								<label class="form-label">Display Name</label>
								<cms:input type='bound' name='k_page_title' class='form-control' />
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">Email Address</label>
								<cms:input type='bound' name='extended_user_email' class='form-control' />
							</div>
						</div>

						<h4 class="mb-3">Security</h4>
						<div class="row mb-4 bg-light p-3 border rounded">
							<div class="col-md-6 mb-3 mb-md-0">
								<label class="form-label">New Password</label>
								<cms:input type='bound' name='extended_user_password' class='form-control' />
								<div class="form-text text-muted">If you would like to change your password, type a new one. Otherwise leave this blank.</div>
							</div>
							<div class="col-md-6">
								<label class="form-label">Repeat Password</label>
								<cms:input type='bound' name='extended_user_password_repeat' class='form-control' />
							</div>
						</div>

						<h4 class="mb-3 mt-4">Shipping Address</h4>
						<div class="row mb-4">
							<div class="col-12 mb-3">
								<label class="form-label">Street Address</label>
								<cms:input type='bound' name='shipping_address' class='form-control' />
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">City</label>
								<cms:input type='bound' name='shipping_city' class='form-control' />
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">State</label>
								<cms:input type='bound' name='shipping_state' class='form-control' />
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Zip Code</label>
								<cms:input type='bound' name='shipping_zip' class='form-control' />
							</div>
						</div>

						<h4 class="mb-3">Billing Address</h4>
						<div class="row mb-4">
							<div class="col-12 mb-3">
								<label class="form-label">Street Address</label>
								<cms:input type='bound' name='billing_street' class='form-control' />
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">City</label>
								<cms:input type='bound' name='billing_city' class='form-control' />
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">State</label>
								<cms:input type='bound' name='billing_state' class='form-control' />
							</div>
							<div class="col-md-4 mb-3">
								<label class="form-label">Zip Code</label>
								<cms:input type='bound' name='billing_zip' class='form-control' />
							</div>
						</div>

						<div class="d-flex align-items-center mt-4">
							<button type="submit" class="btn btn-primary py-2 px-4 me-3">Save Changes</button>

							<!-- THE LOGOUT LINK (Moved to the bottom where it belongs!) -->
							<a href="<cms:logout_link />" class="text-danger text-decoration-none">
								<i class="fas fa-sign-out-alt me-1"></i> Logout
							</a>
						</div>

					</cms:form>

				</div>
			</div>

		</div>
	</section>

	<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
	<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>