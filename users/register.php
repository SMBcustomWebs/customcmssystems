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

	<cms:ignore>
		THE MARKETING CONSENT WORDING. Shown beside the tickbox below and stored
		on the subscriber record as subscriber_consent_txt, from one string that
		profile.php embeds too. The reasoning is at the top of the snippet.
	</cms:ignore>
	<cms:embed 'utils/mkt_consent.htm' />

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

							<cms:ignore>
								=================================================
								THE SECOND HALF OF THE OPT-IN.

								Registration writes the subscriber row as
								'pending'. Clicking this link is what promotes it
								to 'subscribed', so an address only ever goes live
								once someone has proved they can receive mail at
								it. That is double opt-in, built out of the
								activation step the account system already has.

								A MISTYPED ADDRESS NEVER BECOMES A SUBSCRIBER. It
								gets one activation mail - which it would have got
								anyway - and then nothing, because the row stays
								pending and every send loop filters on
								subscriber_status of 'subscribed'.

								THE USER IS FOUND BY PAGE NAME, WHICH IS THE FIRST
								SEGMENT OF THE KEY. activation_link builds the hash
								as name|key|expiry|hash (extended-users.php:365),
								and register.php sets k_page_name to a
								cms:random_name, so name IS the user page's name.
								cms:process_activation exposes nothing but
								k_success, which is why it is read back off the
								query string here rather than handed over.

								show_unpublished, BECAUSE THE PAGE IS STILL
								UNPUBLISHED. _process_activation enables the USER
								row and never touches the page's publish date, so
								without this the lookup finds nothing and the
								promotion silently never happens. tags.php:2264
								has the admin-only guard commented out, so the
								parameter works for the visitor holding the link.

								CAPTURED OUT BEFORE THE SUBSCRIBER LOOKUP rather
								than nesting cms:pages inside cms:pages. The inner
								loop would shadow k_page_id, which is exactly the
								variable db_persist needs to edit the right row.
								=================================================
							</cms:ignore>
							<cms:gpc var='key' method='get' into='act_key' scope='global' />
							<cms:set act_user = '' scope='global' />
							<cms:each act_key sep='|'>
								<cms:if k_first_item>
									<cms:set act_user = item scope='global' />
								</cms:if>
							</cms:each>

							<cms:set act_email = '' scope='global' />
							<cms:set act_mkt = '' scope='global' />
							<cms:if act_user>
								<cms:pages masterpage=k_user_template page_name=act_user show_unpublished='1' limit='1'>
									<cms:set act_email = extended_user_email scope='global' />
									<cms:set act_mkt = user_marketing_opt scope='global' />
								</cms:pages>
							</cms:if>

							<cms:ignore>
								=========================================
								THE LIST ROW IS WRITTEN HERE, AND ONLY HERE.

								Clicking this link is the proof that the address
								receives mail, so it is the moment the row earns
								its place. Registration writes nothing to the
								list - it only records the tick on the account.

								FOUND-NESS AND STATE ARE SEPARATE QUESTIONS. A
								blank subscriber_status is a real value in this
								table and once answered the wrong one, so the
								flag is its own variable. CLAUDE.md sec.64.2.

								k_page_id IS CAPTURED OUT of the loop rather than
								used inside it, because db_persist needs the row
								id and a nested lookup would shadow it.

								REFUSALS, NOT GOOD STATES. Only a row already
								'subscribed' is left alone. Everything else,
								including 'unsubscribed' and values nobody planned
								for, means not yet confirmed, and activating an
								account with the box ticked confirms it - the
								click proves the mailbox, which is the same proof
								a first subscription rests on.

								An unsubscribed row DOES arrive here now.
								unsubscribe.php used to erase subscriber_email so
								this lookup could never match one; the address is
								kept as of 2026-09-20 because it is the key
								between the users table and this one. So the
								person gets their own row back, with today's
								consent date, rather than a duplicate.
								=========================================
							</cms:ignore>
							<cms:if act_mkt && act_email>
								<cms:set act_found = '' scope='global' />
								<cms:set act_state = '' scope='global' />
								<cms:set act_row   = '' scope='global' />
								<cms:pages masterpage='subscribers.php' custom_field="subscriber_email==<cms:show act_email />" limit='1'>
									<cms:set act_found = '1' scope='global' />
									<cms:set act_state = subscriber_status scope='global' />
									<cms:set act_row   = k_page_id scope='global' />
								</cms:pages>

								<cms:if act_found eq ''>
									<cms:capture into='act_token'><cms:php>echo bin2hex( random_bytes(16) );</cms:php></cms:capture>

									<cms:db_persist _masterpage='subscribers.php' _mode='create' _auto_title='1'
										k_page_title=act_email
										subscriber_email=act_email
										subscriber_token=act_token
										subscriber_status='subscribed'
										subscriber_consent_ts="<cms:date format='Y-m-d H:i:s' />"
										subscriber_consent_src='Account registration'
										subscriber_consent_txt=mkt_consent_txt />

								<cms:else_if act_state ne 'subscribed' />
									<cms:ignore>
										A row already exists - they signed up in the
										footer or the contact block first and it is
										still waiting on a confirmation click, or they
										were on the list once and left. Either way this
										activation is that click.

										consent_ts moves to now and unsub_ts is cleared,
										matching confirm-subscription.php. A subscribed
										row must never carry a leaving date.

										src and txt are left alone: they name where this
										person first agreed, and that is still true.
									</cms:ignore>
									<cms:db_persist _masterpage='subscribers.php' _mode='edit' _page_id=act_row
										subscriber_status='subscribed'
										subscriber_consent_ts="<cms:date format='Y-m-d H:i:s' />"
										subscriber_unsub_ts='' />
								</cms:if>
							</cms:if>

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
								<cms:ignore>
										method='post' IS NOT OPTIONAL AND WAS MISSING.

										cms:form DEFAULTS TO GET. The form tag in tags.php
										ends its attribute scan with
										"if( !$method ) $method = 'get';" - leave the
										attribute off and the whole registration submits
										in the query string.

										TWO THINGS THAT BROKE. The marketing tick is read
										with cms:gpc method='post', which looked in $_POST
										and found nothing, so reg_mkt was always empty:
										no subscriber row, no user_marketing_opt, and the
										box never re-ticked after a validation failure.
										Diagnosed 2026-09-20 after three wrong theories.

										And the PASSWORD was travelling in the URL - into
										browser history, server access logs and any
										Referer header the next page sent.

										Every other form in this install states its method.
										This was the only one that did not.
									</cms:ignore>
									<cms:form masterpage=k_user_template mode='create' method='post' anchor='0'>

									<cms:ignore>
										THE MARKETING TICK IS READ FROM POST, ONCE, HERE.

										A hand-written input rather than cms:input type='bound',
										because Couch renders a bound checkbox through the admin
										theme's own field templates and what is needed here is
										Bootstrap's form-check with a full sentence in the label.
										A raw input is not visible to cms:form, so cms:gpc is how
										the value is recovered - and db_persist_form takes it as
										an explicit override, the same way it already takes
										k_page_name and k_publish_date below.

										Read BEFORE the success branch so one call serves all
										three uses: storing it on the account, writing the
										pending subscriber row, and re-ticking the box if
										validation sends the form back.
									</cms:ignore>
									<cms:gpc var='frm_marketing' method='post' into='reg_mkt' scope='global' />

									<cms:if k_success>

										<!-- Save the user in the database as 'unpublished' (disabled) -->
										<cms:db_persist_form 
											_invalidate_cache='0'
											k_page_name="<cms:random_name />"
											k_publish_date='0000-00-00 00:00:00'
											user_marketing_opt=reg_mkt
										/>

										<!-- The Nested Success Check for DB validation -->
										<cms:if k_success>

											<!-- Send Activation Email -->
											<cms:ignore>
								THE SITE NAME COMES FROM GLOBALS, NOT FROM k_site_title.

								k_site_title DOES NOT EXIST. Not in core, not in any addon -
								grep the whole of ccs_dash and it appears nowhere. The
								only k_site_* Couch sets is k_site_link (page.php:1766).
								This mail used to sign off "Thanks," followed by a blank
								line, and nobody noticed, because a cms:show on an unset
								variable prints nothing and raises nothing. Same family as
								k_user_<field> in sec.28.

								Read it off globals.php explicitly, the way foot_3col.html
								does, and fall back to the domain so the subject line can
								never go out half-written.

								THE BODY STARTS AT COLUMN 0 AND HAS TO STAY THERE.
								cms:send_mail sends its children verbatim. On a plain-text
								mail it runs unhtmlentities() and strip_tags() over them
								(tags.php:5968-5971) - neither of which touches whitespace.
								Indented to match the markup around it, every line of this
								mail arrived behind twelve tabs. order_email.htm keeps its
								body hard against the left margin for the same reason.

								reply_to is set so a reply reaches a person. A transactional
								mail with no reply path is one of the things filters count
								against a sender.
							</cms:ignore>
							<cms:set reg_site = "<cms:get_field 'ccs_gl_site_name' masterpage='globals.php' />" />

							<cms:send_mail from="<cms:php>echo K_EMAIL_FROM;</cms:php>" to=frm_extended_user_email reply_to="<cms:php>echo K_EMAIL_TO;</cms:php>" subject="Activate your <cms:show reg_site /> account" debug='0'>
Hi <cms:show frm_k_page_title />,

You asked to create an account at <cms:show reg_site /> (<cms:show k_site_link />).
Click the link below within 24 hours to activate it:

<cms:activation_link frm_extended_user_email />

If you did not sign up, ignore this message - the account stays disabled and
the link expires on its own.

Thanks,
<cms:show reg_site />
<cms:show k_site_link />
</cms:send_mail>

											<cms:ignore>
												=========================================
												NOTHING IS WRITTEN TO THE LIST HERE.

												This block used to write a 'pending'
												subscriber row at this point. It killed the
												request: the account was created and the
												activation mail went out, then the page died
												on "Cheating?!" and never reached the
												redirect below. No row, and a blank white
												page for the person who just signed up.
												Live test 2026-09-20.

												THE LIST ROW IS WRITTEN AT ACTIVATION, at
												the top of this file, which is where it
												belonged anyway. The tick is carried on the
												account as user_marketing_opt until then;
												the subscribers table is the record, and
												nothing goes into it until the address has
												proved it can receive mail.

												That also means one write instead of two and
												no pending row to promote.
												=========================================
											</cms:ignore>

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

									<cms:ignore>
										MARKETING IS A SEPARATE ASK, AND IT STARTS UNTICKED.

										Nobody opens an account in order to receive advertising -
										they came to buy something - so this cannot ride along
										with the rest of the form the way it does on the
										email-only contact tile, where the whole point of the
										form is joining the list and the notice says so.

										A PRE-TICKED BOX IS NOT CONSENT in any jurisdiction that
										has looked at the question, and it poisons the record for
										everyone on the list rather than only for the person who
										did not notice. There is no default here and there must
										never be one.

										THE LABEL IS mkt_consent_txt, the same variable stored on the
										subscriber row. What they read and what is on file are
										one string.

										NOTHING HAPPENS UNTIL THEY ACTIVATE. Ticking writes a
										pending row; the link in the activation mail is what
										makes it live.
									</cms:ignore>
									<hr class="my-4">
									<div class="form-check mb-4">
										<input class="form-check-input" type="checkbox" value="1" name="frm_marketing" id="reg_marketing"<cms:if reg_mkt> checked</cms:if>>
										<label class="form-check-label" for="reg_marketing"><cms:show mkt_consent_txt /></label>
									</div>

									<cms:ignore>
										THE PRIVACY LINE NAMES MARKETING UNCONDITIONALLY, because
										the tickbox is on the page whether or not it is ticked and
										the line describes what the form can do. Branching it on
										reg_mkt would make the disclosure appear only after the
										person had already agreed, which is the wrong way round.
									</cms:ignore>
									<cms:set pn_purpose = 'We use your email address to create and secure your account, to send order updates, and to let you reset your password. We send marketing email only if you tick the box above, and you can unsubscribe from any message. This form is protected by Google reCAPTCHA to stop automated sign-ups, so Google receives your IP address and some information about how you interact with this page.' />
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
										<cms:input type='recaptcha' name='reg_captcha' label='Verification' theme='dark' size='normal' />
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