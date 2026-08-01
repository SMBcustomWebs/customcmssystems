<?php require_once( '../ccs_dash/cms.php' ); ?>
<cms:template title="Register Account" hidden='1' />
	<cms:if k_logged_in >
        <!-- what is an already logged-in member doing on this page? Send back to homepage. -->
        <cms:redirect k_site_link />
    </cms:if>

	<cms:embed 'pb_mods/pg_frame/head.htm' />
	<cms:embed 'pb_mods/pg_frame/main-cap.htm' />   
	<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />

	<section class="pt-0">
		<div class="container">

			<div class="row pt-6 pb-4">
				<div class="col-12">
					<h1 class="mb-0">Create an Account</h1>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6">

					<!-- 1. Handle Account Activation (From Email Link) -->
					<cms:if action='activate' >
						<h4 class="mb-3">Activate Account</h4>
						<cms:process_activation />

						<cms:if k_success >
							<div class="alert alert-success">
								<i class="fas fa-check-circle me-2"></i> Your account has been activated successfully! You can now <a href="<cms:login_link />" class="alert-link">log in</a>.
							</div>
						<cms:else />
							<div class="alert alert-danger">
								<i class="fas fa-exclamation-triangle me-2"></i> <cms:show k_error />
							</div>
						</cms:if>

					<cms:else />

						<!-- 2. Display Success Message after Registration -->
						<cms:set success_msg="<cms:get_flash 'success_msg' />" />
						<cms:if success_msg >
							<div class="alert alert-success mb-4">
								<h5 class="alert-heading"><i class="fas fa-envelope me-2"></i> Almost there!</h5>
								<p class="mb-0">Your account has been created successfully. We've sent a verification link to your email address. Please click that link to activate your account.</p>
							</div>
						<cms:else />

							<!-- 3. The Registration Form -->
							<div class="p-4 bg-light border rounded">
								<cms:form masterpage=k_user_template mode='create' anchor='0'>

									<cms:if k_success>

										<!-- Save the user in the database as 'unpublished' (disabled) -->
										<cms:db_persist_form 
											_invalidate_cache='0'
											k_page_name="<cms:random_name />"
											k_publish_date='0000-00-00 00:00:00'
										/>

										<!-- The Nested Success Check for DB validation -->
										<cms:if k_success>

											<!-- Send Activation Email -->
											<cms:send_mail from="<cms:php>echo K_EMAIL_FROM;</cms:php>" to=frm_extended_user_email subject='Account Activation' debug='0'>
												Hi <cms:show frm_k_page_title />,

												Please click the following link to activate your account:
												<cms:activation_link frm_extended_user_email />

												Thanks,
												<cms:show k_site_title />
											</cms:send_mail>

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

									<div class="mb-3">
										<label class="form-label">Full Name</label>
										<cms:input type='bound' name='k_page_title' class='form-control' required='1' />
									</div>

									<div class="mb-3">
										<label class="form-label">Email Address</label>
										<cms:input type='bound' name='extended_user_email' class='form-control' required='1' />
									</div>

									<div class="mb-3">
										<label class="form-label">Password</label>
										<cms:input type='bound' name='extended_user_password' class='form-control' required='1' />
									</div>

									<div class="mb-4">
										<label class="form-label">Repeat Password</label>
										<cms:input type='bound' name='extended_user_password_repeat' class='form-control' required='1' />
									</div>

									<button type="submit" class="btn btn-primary w-100 py-2">Create Account</button>

									<div class="mt-3 text-center">
										<span class="text-muted">Already have an account?</span> <a href="<cms:login_link />" class="text-decoration-none">Log in here</a>
									</div>

								</cms:form>
							</div>
						</cms:if>
					</cms:if>

				</div>
			</div>

		</div>
	</section>

	<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
	<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>