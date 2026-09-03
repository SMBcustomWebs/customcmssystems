<?php require_once( '../ccs_dash/cms.php' ); ?>
<cms:template title="Register Account" hidden='1' />

	<cms:ignore>
		=============================================================
		THE ACTIVATION LINK ARRIVES AS ?act=activate

		activation_link_handler builds it that way
		(extended-users.php: "?act=activate&key=" . urlencode($hash)).

		This page used to branch on a variable called `action`, which
		NOTHING in Couch ever sets - not core, not the addon. The test
		was therefore always false and the activation branch was dead
		code: clicking the emailed link fell through to the
		registration form, cms:process_activation never ran, and the
		account stayed Disabled forever. Registration itself was fine
		the whole time; the account simply could never be switched on,
		so its owner could never sign in to see their own profile.

		Read the query string explicitly. `action` is accepted too, so
		any older link already sitting in someone's inbox still works.
		=============================================================
	</cms:ignore>
	<cms:set reg_act = "<cms:gpc 'act' method='get' />" />
	<cms:if reg_act eq ''>
		<cms:set reg_act = "<cms:gpc 'action' method='get' />" />
	</cms:if>

	<cms:ignore>
		The logged-in bounce must not swallow an activation. Someone
		signed in on the same browser - a shared machine, or an admin
		testing - would otherwise be redirected away and the account in
		the link would stay disabled with no clue why.
	</cms:ignore>
	<cms:if k_logged_in && reg_act ne 'activate'>
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
					<cms:if reg_act eq 'activate'>
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
							<div class="p-4 border rounded">
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

									<div class="row">
										<div class="col-md-6 mb-3">
											<label class="form-label">First Name</label>
											<cms:input type='bound' name='user_first_name' class='form-control' required='1' />
										</div>
										<div class="col-md-6 mb-3">
											<label class="form-label">Last Name</label>
											<cms:input type='bound' name='user_last_name' class='form-control' required='1' />
										</div>
									</div>

									<cms:ignore>
										k_page_title is the account's display name and Couch requires
										it. Kept, but no longer the source of the customer's name -
										checkout reads user_first_name / user_last_name.
									</cms:ignore>
									<div class="mb-3">
										<label class="form-label">Display Name</label>
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

									<cms:ignore>
										Optional at signup. Everything checkout asks for except card
										details, so a returning customer's form is already filled in.
										Nothing here is required - a blank address must never block
										someone from creating an account.
									</cms:ignore>
									<hr class="my-4">
									<h5 class="fw-bold mb-1">Shipping address</h5>
									<p class="small text-body-secondary mb-3">Optional. Saves you typing it at checkout.</p>

									<div class="mb-3">
										<label class="form-label">Street Address</label>
										<cms:input type='bound' name='shipping_address' class='form-control' />
									</div>
									<div class="row">
										<div class="col-md-5 mb-3">
											<label class="form-label">City</label>
											<cms:input type='bound' name='shipping_city' class='form-control' />
										</div>
										<div class="col-md-3 mb-3">
											<label class="form-label">State</label>
											<cms:input type='bound' name='shipping_state' class='form-control' />
										</div>
										<div class="col-md-4 mb-4">
											<label class="form-label">Zip</label>
											<cms:input type='bound' name='shipping_zip' class='form-control' />
										</div>
									</div>

									<cms:set pn_purpose = 'We use your email address to create and secure your account, to send order updates, and to let you reset your password. It is not used for advertising unless you ask for it. This form is protected by Google reCAPTCHA to stop automated sign-ups, so Google receives your IP address and some information about how you interact with this page.' />
									<cms:embed 'utils/privacy_notice.htm' />

									<cms:ignore>
										reCAPTCHA v2 checkbox, from the addon already loaded in kfunctions.php.
										Keys live in ccs_dash/config.php.
									
										NOT GATED ON COOKIE CONSENT, deliberately. Stopping automated sign-ups
										is strictly necessary to run the account system, and strictly-necessary
										processing does not require consent - but it does require disclosing,
										which is why the notice above now names Google and says what it gets.
									
										Gating it would also defeat the point: a bot declines the cookie banner
										and walks through an unprotected form.
									
										The Google script loads ONLY on a page carrying this widget. That is why
										v2 was chosen over v3, which expects to run sitewide and would put a
										third-party tracker on every page of a site built to avoid exactly that.
									
										size accepts only normal or compact - the addon rejects anything else,
										so invisible mode is not available.
									</cms:ignore>
									<div class="mb-3">
										<cms:input type='recaptcha' name='reg_captcha' theme='dark' size='normal' />
									</div>
									
									<button type="submit" class="btn btn-primary w-100 my-2">Create Account</button>

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

	<cms:ignore>
		Show/hide toggle on every password field on this page. Pure JS over
		the rendered inputs, so nothing above needs changing and a field
		added later is covered automatically.
	</cms:ignore>
	<cms:embed 'utils/password_reveal.htm' />

	<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
	<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>