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
						<div class="row mb-4 p-3 border rounded">
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

						<h4 class="mb-3 mt-4">Your Name</h4>
						<div class="row mb-4">
							<div class="col-md-6 mb-3">
								<label class="form-label">First Name</label>
								<cms:input type='bound' name='user_first_name' class='form-control' />
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">Last Name</label>
								<cms:input type='bound' name='user_last_name' class='form-control' />
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
								<cms:input type='bound' name='billing_address' class='form-control' />
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

					<cms:ignore>
						Order history lives on its own page rather than inside this form.
						Keeping it outside means a half-filled profile form can never be
						lost by navigating to it, and my-orders.php stays the single
						renderer of a customer's orders.
					</cms:ignore>
					<div class="card border shadow-sm mt-4">
						<div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
							<div>
								<h5 class="fw-bold mb-1">Order history</h5>
								<p class="mb-0 small text-body-secondary">
									Everything you have ordered while signed in, with a receipt for each.
								</p>
							</div>
							<a href="<cms:link 'my-orders.php' />" class="btn btn-primary">
								<i class="fas fa-receipt me-1" aria-hidden="true"></i> View orders
							</a>
						</div>
					</div>

					<div class="card border shadow-sm mt-3">
						<div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
							<div>
								<h5 class="fw-bold mb-1">Saved items</h5>
								<p class="mb-0 small text-body-secondary">
									Products you have saved for later.
								</p>
							</div>
							<a href="<cms:link 'saved-items.php' />" class="btn btn-outline-primary">
								<i class="fas fa-heart me-1" aria-hidden="true"></i> View saved items
							</a>
						</div>
					</div>

				</div>
			</div>

		</div>
	</section>

	<cms:ignore>
		Show/hide toggle on every password field on this page. Pure JS over
		the rendered inputs, so nothing above needs changing and a field
		added later is covered automatically.
	</cms:ignore>
	<cms:embed 'utils/password_reveal.htm' />

	<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
	<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>