<?php require_once( '../ccs_dash/cms.php' ); ?>
    <cms:template title='Lost Password' hidden='1' />

    <cms:if k_logged_in >
        <!-- what is an already logged-in member doing on this page? Send back to homepage. -->
        <cms:redirect k_site_link />
    </cms:if>

	<cms:embed 'pb_mods/pg_frame/head.htm' />
	<cms:embed 'pb_mods/pg_frame/main-cap.htm' />   
	<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />

	<section class="pt-0">
		<div class="container pt-6 pb-6">
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-5">

					<!-- 1. Handle Reset Password (User clicks the link in their email) -->
					<cms:if action='reset' >
						<h2 class="text-center mb-4">Reset Password</h2>

						<div class="p-4 bg-light border rounded">
							<cms:process_reset_password send_mail='0' />

							<cms:if k_success >

								<!-- Email the new password to the user -->
								<cms:send_mail from="<cms:php>echo K_EMAIL_FROM;</cms:php>" to=k_user_email subject='Your new password' debug='0'>
									Your password has been reset for the following site and username:
									<cms:show k_site_link />
									Username: <cms:show k_user_name />

									New Password: <cms:show k_user_new_password />

									Once logged in you can change your password.
								</cms:send_mail>

								<!-- Set success code 2 to indicate the password was successfully reset -->
								<cms:set_flash name='success_msg' value='2' />
								<cms:redirect k_page_link />      

							<cms:else />
								<div class="alert alert-danger">
									<i class="fas fa-exclamation-triangle me-2"></i> <cms:show k_error />
								</div>
							</cms:if>
						</div>

					<!-- 2. Handle Forgot Password Form (Default view) -->
					<cms:else />

						<h2 class="text-center mb-4">Forgot Password</h2>

						<div class="p-4 bg-light border rounded">

							<!-- Check for Success Messages -->
							<cms:set success_msg="<cms:get_flash 'success_msg' />" />

							<cms:if success_msg='1' >
								<div class="alert alert-success text-center">
									<i class="fas fa-paper-plane me-2 mb-2 fa-2x"></i><br>
									A password reset link has been sent to your email address. Please check your inbox.
								</div>
							<cms:else_if success_msg='2' />
								<div class="alert alert-success text-center">
									<i class="fas fa-check-circle me-2 mb-2 fa-2x"></i><br>
									Your password has been reset and emailed to you. <br>
									<a href="<cms:show k_site_link />users/login.php" class="alert-link fw-bold mt-2 d-inline-block">Log in here</a>
								</div>
							<cms:else />

								<!-- The Request Form -->
								<cms:form method="post" anchor='0'>

									<cms:if k_success>

										<!-- Process the request but suppress Couch's default email -->
										<cms:process_forgot_password send_mail='0' />

										<cms:if k_success>
											<!-- Send our custom verification email -->
											<cms:send_mail from="<cms:php>echo K_EMAIL_FROM;</cms:php>" to=k_user_email subject='Password reset requested' debug='0'>         
												A request was received to reset your password for the following site and username:
												<cms:show k_site_link />
												Username: <cms:show k_user_name />

												To confirm that the request was made by you please visit the following address, otherwise just ignore this email.
												<cms:show k_reset_password_link />
											</cms:send_mail>

											<!-- Set success code 1 to indicate the reset link was sent -->
											<cms:set_flash name='success_msg' value='1' />
											<cms:redirect k_page_link />
										</cms:if>   
									</cms:if>

									<cms:if k_error>
										<div class="alert alert-danger">
											<i class="fas fa-exclamation-triangle me-2"></i> <cms:show k_error />
										</div>
									</cms:if>

									<p class="text-muted mb-4">Enter your email address and we'll send you a link to reset your password.</p>

									<div class="mb-4">
										<label class="form-label fw-bold">Email Address</label>
										<!-- Expects k_user_name as the input field per KK's instructions -->
										<cms:input type='text' name='k_user_name' class='form-control' required='1' />
									</div>

									<button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Send Reset Link</button>

								</cms:form>

							</cms:if>

							<div class="mt-4 text-center">
								<a href="<cms:show k_site_link />users/login.php" class="text-decoration-none">Back to Login</a>
							</div>

						</div>
					</cms:if>

				</div>
			</div>
		</div>
	</section>

	<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
	<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>   