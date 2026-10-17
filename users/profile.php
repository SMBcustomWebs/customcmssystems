<?php require_once( '../ccs_dash/cms.php' ); ?>
    <cms:template title='User Profile' hidden='1' />
    
    <!-- Security Check: Kick out guests -->
	<cms:if k_logged_out >
		<cms:redirect "<cms:login_link />" />
	</cms:if>

	<cms:ignore>
		THE MARKETING CONSENT WORDING. The same string register.php shows, from
		one snippet, because the same tick is offered in two places and a record
		filed under wording nobody can produce is no record at all.
	</cms:ignore>
	<cms:embed 'utils/mkt_consent.htm' />

	<cms:ignore>
		=====================================================================
		THE ACCOUNT AS IT STANDS, READ BEFORE THE FORM TOUCHES IT.

		Two things are needed and both have to be the PRE-SAVE values:

		  prf_mkt_cur    what the tickbox should show on a fresh page load
		  prf_old_email  the address any existing subscriber row is filed under

		THE OLD ADDRESS IS THE INTERESTING ONE. Someone can change their email
		and their marketing preference in the same save. Looking the subscriber
		row up by the NEW address would find nothing and quietly leave the old
		address on the list forever, still receiving mail, with no account
		pointing at it. Looked up by the old one, the row is found and either
		moved to the new address or closed.

		show_unpublished, BECAUSE USER PAGES ARE NEVER PUBLISHED. register.php
		creates them with k_publish_date='0000-00-00 00:00:00' and activation
		enables the USER row without touching the page, so a plain cms:pages
		finds nothing here - for an account that demonstrably works. The form
		below is unaffected: it addresses the page by id, not by a query.
		=====================================================================
	</cms:ignore>
	<cms:set prf_mkt_cur = '' scope='global' />
	<cms:set prf_old_email = '' scope='global' />
	<cms:pages masterpage=k_user_template id=k_user_id show_unpublished='1' limit='1'>
		<cms:set prf_mkt_cur = user_marketing_opt scope='global' />
		<cms:set prf_old_email = extended_user_email scope='global' />
	</cms:pages>

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

						<cms:ignore>
							THE MARKETING TICK, READ FROM POST.

							prf_mkt_sent IS A HIDDEN INPUT THAT IS ALWAYS SENT, and it
							exists to tell the two cases apart. An unticked checkbox
							posts NOTHING, so an empty frm_marketing means either "they
							unticked it" or "this is a fresh page load" - and guessing
							wrong either resubscribes people who opted out or wipes the
							box every time the form comes back with an error.

							Sent means trust frm_marketing. Not sent means show what is
							on the account.
						</cms:ignore>
						<cms:gpc var='frm_marketing' method='post' into='prf_mkt' scope='global' />
						<cms:gpc var='frm_mkt_sent' method='post' into='prf_mkt_sent' scope='global' />
						<cms:if prf_mkt_sent><cms:else />
							<cms:set prf_mkt = prf_mkt_cur scope='global' />
						</cms:if>

						<cms:if k_success>
							<cms:db_persist_form user_marketing_opt=prf_mkt />

							<cms:if k_success >

								<cms:ignore>
									=====================================================
									KEEPING THE LIST IN STEP WITH THE ACCOUNT.

									The account field is the person's answer; the
									subscribers.php row is what actually receives mail.
									This is the only place the two are reconciled after
									sign-up, so it has to cover every transition rather
									than the happy one.

									LOOKED UP BY THE OLD ADDRESS, for the reason at the
									top of this file: the email may have just changed in
									this same save.

									TICKED, ROW FOUND    -> move it to the current address
									                        and mark it subscribed. This
									                        also promotes a row left
									                        'pending' by someone who
									                        registered, never activated,
									                        and got here another way.
									TICKED, NO ROW       -> create one, subscribed
									                        outright. No pending step:
									                        they are signed in, which is
									                        stronger proof of the address
									                        than clicking a link.
									UNTICKED, ROW FOUND  -> the unsubscribe.php treatment.
									                        The address is blanked rather
									                        than the row deleted, because
									                        db_persist cannot delete and
									                        because an anonymous stub is
									                        the better privacy outcome.
									UNTICKED, NO ROW     -> nothing to do.

									AN UNSUBSCRIBED ROW IS NEVER FOUND AGAIN, by design -
									its address was blanked. Someone who opts back in gets
									a NEW row with a new token and a fresh consent date,
									which is the right record: they agreed again, today.
									=====================================================
								</cms:ignore>
								<cms:set prf_lookup = prf_old_email scope='global' />
								<cms:if prf_lookup eq ''>
									<cms:set prf_lookup = frm_extended_user_email scope='global' />
								</cms:if>

								<cms:set prf_row = '0' scope='global' />
								<cms:if prf_lookup>
									<cms:pages masterpage='subscribers.php' custom_field="subscriber_email==<cms:show prf_lookup />" limit='1'>
										<cms:set prf_row = '1' scope='global' />
										<cms:if prf_mkt>
											<cms:ignore>
												RE-SUBSCRIBING AN EXISTING ROW, INCLUDING ONE
												THEY HAD LEFT.

												No confirmation mail and no new row. They are
												signed in as the owner of this address, which
												is stronger proof than a link click, so the
												tick is the consent and it takes effect now.

												THE CONSENT FIELDS ARE REWRITTEN, not kept.
												They agreed again, today, to today's wording -
												and a record still showing the sentence from a
												sign-up they later cancelled is not proof of
												anything. subscriber_unsub_ts is cleared for
												the same reason: it means "currently gone".

												The token is NOT regenerated. It is the
												identifier in every unsubscribe link ever sent
												to this person, and older mail in their inbox
												has to keep working.
											</cms:ignore>
											<cms:db_persist _masterpage='subscribers.php' _mode='edit' _page_id=k_page_id
												k_page_title=frm_extended_user_email
												subscriber_email=frm_extended_user_email
												subscriber_status='subscribed'
												subscriber_consent_ts="<cms:date format='Y-m-d H:i:s' />"
												subscriber_consent_src='Account profile'
												subscriber_consent_txt=mkt_consent_txt
												subscriber_unsub_ts='' />
										<cms:else />
											<cms:ignore>
												THE ADDRESS AND TITLE STAY. This used to blank
												both. The email is the only key between the
												users table and this one - erase it and the
												profile page can never find the row again, and
												the person comes back as a stranger. What stops
												mail going out is the status, which every send
												loop filters on.

												The address is removed for good only when the
												account is deleted.

												Consent fields are left exactly as they were.
												They record what was agreed and when, which is
												the evidence that the sign-up was legitimate -
												leaving is not a reason to destroy it.
											</cms:ignore>
											<cms:db_persist _masterpage='subscribers.php' _mode='edit' _page_id=k_page_id
												subscriber_status='unsubscribed'
												subscriber_unsub_ts="<cms:date format='Y-m-d H:i:s' />" />
										</cms:if>
									</cms:pages>
								</cms:if>

								<cms:if prf_mkt && prf_row eq '0'>
									<cms:capture into='prf_token'><cms:php>echo bin2hex( random_bytes(16) );</cms:php></cms:capture>

									<cms:db_persist _masterpage='subscribers.php' _mode='create' _auto_title='1'
										k_page_title=frm_extended_user_email
										subscriber_email=frm_extended_user_email
										subscriber_token=prf_token
										subscriber_status='subscribed'
										subscriber_consent_ts="<cms:date format='Y-m-d H:i:s' />"
										subscriber_consent_src='Account profile'
										subscriber_consent_txt=mkt_consent_txt />
								</cms:if>

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

						<cms:ignore>
							THE SAME TICK REGISTRATION OFFERS, AND THE ONLY PLACE TO CHANGE
							IT WITHOUT AN UNSUBSCRIBE LINK. Unticking here does a real
							unsubscribe - it does not merely stop the account agreeing -
							because a preference that leaves the address on the sending
							list is not a preference.

							Order mail, password resets and account notices are not
							affected. They are the service, not marketing.

							THE HIDDEN INPUT IS NOT OPTIONAL. See the note on prf_mkt_sent
							above: without it an unticked box and a fresh page load are
							indistinguishable.
						</cms:ignore>
						<hr class="my-4">
						<h5 class="fw-bold mb-3">Email preferences</h5>
						<input type="hidden" name="frm_mkt_sent" value="1">
						<div class="form-check mb-4">
							<input class="form-check-input" type="checkbox" value="1" name="frm_marketing" id="prf_marketing"<cms:if prf_mkt> checked</cms:if>>
							<label class="form-check-label" for="prf_marketing"><cms:show mkt_consent_txt /></label>
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

					<cms:ignore>
						=========================================================
						CLOSING THE ACCOUNT - THE REQUEST HALF.

						A FORM OF ITS OWN, NOT A CONTROL ON THE PROFILE FORM.
						Saving a shipping address and closing an account must
						never be the same submit, and a second cms:form here
						cannot be confused with the first: del_frm_sent is a
						hidden input that exists only inside this one, and the
						gate tests it alongside k_success. k_success is not
						private to a form - see CLAUDE.md sec.64.5 and the
						blank-email rows it cost us.

						NOTHING IS DESTROYED BY THIS BUTTON. It writes a token
						and a timestamp and mails a link. The link is what acts.
						That is the same shape as the activation mail and the
						subscription confirmation: an action that cannot be
						undone waits for proof that the person holding the
						mailbox asked for it.

						THE TOKEN IS REGENERATED EVERY TIME. Asking twice
						invalidates the first mail, so an old message found in
						an inbox months later cannot close an account.
						=========================================================
					</cms:ignore>

					<div class="card border border-danger-subtle shadow-sm mt-4">
						<div class="card-body">
							<h5 class="fw-bold mb-1 text-danger">Close this account</h5>

							<cms:form name='del_req_frm' method='post' anchor='0'>

								<cms:if frm_del_go && k_success>

									<cms:capture into='del_token'><cms:php>echo bin2hex( random_bytes(16) );</cms:php></cms:capture>

									<cms:ignore>
										Written to their own account while they are signed
										in as its owner, which is the one case Couch allows
										without elevation: auth/user.php:184 skips its guard
										when the row being saved IS the logged-in user.
									</cms:ignore>
									<cms:db_persist _masterpage=k_user_template _mode='edit' _page_id=k_user_id
										user_del_token=del_token
										user_del_req_ts="<cms:date format='Y-m-d H:i:s' />" />

									<cms:if k_error>
										<p class="mb-0 text-danger">That did not go through. Please try again.</p>
									<cms:else />
										<cms:set del_site = "<cms:get_field 'ccs_gl_site_name' masterpage='globals.php' />" />

										<cms:ignore>
											THE BODY STARTS AT COLUMN 0 AND HAS TO STAY THERE.
											cms:send_mail sends its children verbatim.
										</cms:ignore>
										<cms:send_mail from="<cms:php>echo K_EMAIL_FROM;</cms:php>"
											to=k_user_email reply_to="<cms:php>echo K_EMAIL_TO;</cms:php>"
											subject="Confirm you want to close your <cms:show del_site /> account">
Someone signed in to your <cms:show del_site /> account and asked to close it.

If that was you, open this link within 24 hours:

<cms:link 'users/delete-account.php' />?t=<cms:show del_token />

HERE IS EXACTLY WHAT HAPPENS WHEN YOU OPEN IT

  The account is deleted. Your name, your shipping and billing addresses and
  your email address go with it.
  Your email address is removed from our mailing list.
  Your password stops working. You will not be able to sign in again.

  This cannot be undone and a closed account cannot be reopened. You are
  welcome back any time - the same email address can be used to open a new
  account, and it will start empty.

  Your past orders are kept. We are required by law to keep records of
  completed purchases, so each order keeps the name and address it was
  shipped to. What they no longer have is an account you can sign in to and
  view them from - after this, only we can look them up.

IF YOU DID NOT ASK FOR THIS

Do nothing and nothing happens. The account stays exactly as it is unless that
link is opened. If you are not sure who asked, sign in and change your password.

Thanks,
<cms:show del_site />
<cms:show k_site_link />
</cms:send_mail>

										<p class="mb-0 fw-semibold">
											Check your email &ndash; open the link we just sent
											to finish closing this account. It expires in 24 hours.
										</p>
									</cms:if>

								<cms:else />

									<p class="small mb-2">
										Closing your account erases your name, your shipping address and
										your billing address, removes your email address from our mailing
										list, and stops your password working. <strong>You will not be able
										to sign in again, and a closed account cannot be reopened.</strong>
									</p>
									<p class="small text-body-secondary mb-3">
										Your past orders are kept &ndash; we are required to keep records of
										completed purchases &ndash; but you will no longer be able to view
										them. Nothing happens yet: we will email you a link, and the account
										stays exactly as it is unless you open it.
									</p>

									<cms:ignore>
										A cms:input, NOT A RAW ONE, AND THE FORM DEPENDS ON IT.

										tags.php:5559 only processes a submission
										"if( isset($PAGE->forms[$name]) )", and that entry is
										created by the first Couch field the form registers.
										A form built from raw HTML alone registers nothing,
										so it is never in that array, k_success is never set,
										and the submit silently does nothing at all - no
										write, no mail, no error. That is exactly what this
										form did on its first live test, 2026-09-20.

										type='hidden' renders an input and carries no label,
										so it costs nothing on screen. It also posts as
										frm_del_go, which doubles as the marker proving THIS
										form was the one sent - k_success alone cannot say,
										see CLAUDE.md sec.64.5.
									</cms:ignore>
									<cms:input type='hidden' name='del_go' value='1' />
									<button type="submit" class="btn btn-outline-danger">
										<i class="fas fa-triangle-exclamation me-1" aria-hidden="true"></i>
										Email me a link to close my account
									</button>

								</cms:if>

							</cms:form>
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