<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Checkout" parent='_donottouch_' icon='cart' order="81" >
    <cms:editable type='message' name='chk_msg' order='1'>
        <p>Checkout. Gateway credentials live in <code>ccs_dash/config.php</code>, never here -
        that file is gitignored, this template is not.</p>
    </cms:editable>
</cms:template>
<cms:no_cache />

<cms:ignore>
    ================================================================
    CHECKOUT

    Both gateways follow ONE shape:

        submit -> validate stock -> write PENDING order + line items
               -> gateway takes the money
               -> server-to-server confirmation (Stripe webhook /
                  PayPal IPN) marks paid and deducts stock ONCE

    Nothing on this page deducts inventory. order_fulfil.htm is the
    only thing that does, and it is reached from stripe-webhook.php,
    paypal-ipn.php and order-complete.php - all three guarded by the
    same order_stock_deducted flag, so whichever arrives first wins
    and the others are no-ops.

    STRIPE uses PaymentIntents, not the legacy Charges API. Charges
    cannot carry a bank's "prove it's really them" step, so any card
    requiring 3-D Secure - which is most European and many Mexican
    ones - simply fails on it. createPaymentMethod + handleNextAction
    are both current; neither was in Stripe's March 2026 removals.

    PAYPAL does NOT use cms:pp_payment_gateway. That tag builds its
    querystring with no notify_url and no custom field (see
    KCart::payment_gateway in the cart addon), so there is no way to
    tell PayPal where to send the IPN or which order it belongs to.
    The form is therefore built here, from the same cart data, with
    those two fields present. Nothing in the addon is modified.
    ================================================================
</cms:ignore>

<cms:php>
    global $CTX;

    // One switch picks the pair. Flip K_STRIPE_TEST_MODE in config.php to go live.
    $test = ( defined('K_STRIPE_TEST_MODE') && K_STRIPE_TEST_MODE ) ? 1 : 0;
    $pk = $test
        ? ( defined('K_STRIPE_TEST_PUBLISHABLE_KEY') ? K_STRIPE_TEST_PUBLISHABLE_KEY : '' )
        : ( defined('K_STRIPE_LIVE_PUBLISHABLE_KEY') ? K_STRIPE_LIVE_PUBLISHABLE_KEY : '' );

    $CTX->set( 'ccs_stripe_pk', $pk, 'global' );
    $CTX->set( 'ccs_stripe_ready', ($pk!=='') ? '1' : '', 'global' );
</cms:php>

<cms:embed 'pb_mods/pg_frame/head.htm' />
<cms:set my_redirect_link = k_page_link />
<cms:embed 'pb_mods/pg_frame/main-cap.htm' />
<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />
<cms:embed 'utils/store_theme.htm' />

<section class="pt-0">
    <div class="container">

        <div class="row"><div class="col-12"><h1 class="pt-6 pb-4">Secure Checkout</h1></div></div>

        <cms:if "<cms:not "<cms:pp_count_items />" />">
            <div class="alert alert-info p-4">
                <h4 class="fw-bold mb-2">Your cart is empty</h4>
                <p class="mb-3">There is nothing to check out.</p>
                <a href="<cms:link 'product.php' />" class="btn btn-primary">Browse products</a>
            </div>
        <cms:else />

        <div class="row">

            <div class="col-lg-7 pe-lg-5">

                <cms:ignore>
                    Both doors, not one. Anyone reading this is signed out, and the
                    likeliest reason is that they have no account - so offering only
                    "Log in" speaks to the smaller half of the audience and reads as
                    a dead end to the rest.

                    Neither is required. Checkout works as a guest, and the order can
                    still be attached to an account afterwards from the receipt page
                    (snippets/utils/order_claim.htm), which is why nothing here
                    pressures anyone to stop and sign up mid-purchase.

                    login_link carries redirect='1' so signing in returns to
                    checkout with the cart intact rather than dumping them on the
                    home page.
                </cms:ignore>
                <cms:if k_logged_out>
                    <div class="alert alert-info d-flex align-items-center mb-4 p-3 border rounded">
                        <i class="fas fa-user-circle fa-2x me-3" aria-hidden="true"></i>
                        <div>
                            <h5 class="mb-1 fw-bold">Log in or create an account</h5>
                            <p class="mb-0 <cms:show ccs_st_secondary />">
                                <a href="<cms:login_link redirect='1' />" class="alert-link fw-bold">Log in</a>
                                or <a href="<cms:link 'users/register.php' />" class="alert-link fw-bold">join</a>
                                for faster checkout, saved addresses, and your order history in one place.
                                You can also carry on as a guest &mdash; you'll be able to save the order to
                                an account afterwards.
                            </p>
                        </div>
                    </div>
                </cms:if>

                <cms:ignore>
                    Prefer the real name fields on the account, fall back to
                    splitting the display name. Both live in
                    utils/user_name.htm now.

                    This block used to read k_user_user_first_name directly.
                    That variable does not exist - the addon exposes only five
                    k_user_* values and none of the user page's own fields - so
                    the split-the-title fallback ran every time and the account's
                    real first and last names were never used.
                </cms:ignore>
                <cms:embed 'utils/user_name.htm' />
                <cms:set prefill_first_name = ccs_user_first />
                <cms:set prefill_last_name  = ccs_user_last />

                <cms:form method="post" anchor='0' id='ccs-checkout-form'>

                    <cms:if k_success>

                        <cms:set chk_gateway = "<cms:gpc 'gateway' method='post' />" />
                        <cms:if chk_gateway ne 'paypal'><cms:set chk_gateway = 'stripe' /></cms:if>

                        <cms:ignore>
                            ----------------------------------------------------
                            STOCK GATE. cart.php checks this too, but a customer
                            can sit on the checkout page while the last unit
                            sells. Checked again here, immediately before money
                            moves, and quantities are tallied ACROSS lines - the
                            same product can appear on several lines with
                            different variants.
                            ----------------------------------------------------
                        </cms:ignore>
                        <cms:php>
                            global $my_tallies;
                            $my_tallies = array();
                        </cms:php>
                        <cms:pp_cart_items>
                            <cms:php>
                                global $CTX, $my_tallies;
                                $pid = $CTX->get('id');
                                $qty = $CTX->get('quantity');
                                if( !isset($my_tallies[$pid]) ){ $my_tallies[$pid] = 0; }
                                $my_tallies[$pid] += $qty;
                            </cms:php>
                        </cms:pp_cart_items>

                        <cms:set chk_stock_error = '' scope='global' />
                        <cms:pp_cart_items>
                            <cms:ignore>
                                Template resolved from the line's own page id.
                                This is the last oversell check before money
                                moves, and a hardcoded product.php would let a
                                service or any other sellable template through
                                it unexamined. See snippets/utils/item_tpl.htm
                            </cms:ignore>
                            <cms:set itm_tpl_id = id scope='global' />
                            <cms:embed 'utils/item_tpl.htm' />
                            <cms:pages masterpage=itm_tpl id="<cms:show id />" limit='1'>
                                <cms:if track_inventory = '1'>
                                    <cms:php>
                                        global $CTX, $my_tallies;
                                        $pid = $CTX->get('id');
                                        $CTX->set( 'chk_wanted', isset($my_tallies[$pid]) ? $my_tallies[$pid] : 0, 'global' );
                                    </cms:php>
                                    <cms:if chk_wanted gt in_stock>
                                        <cms:set chk_stock_error = "Only <cms:show in_stock /> of '<cms:show k_page_title />' remain, but your cart asks for <cms:show chk_wanted />. Please adjust your cart." scope='global' />
                                    </cms:if>
                                </cms:if>
                            </cms:pages>
                        </cms:pp_cart_items>

                        <cms:if chk_stock_error>
                            <div class="alert alert-warning p-4 mb-4">
                                <h5 class="fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Stock changed</h5>
                                <p class="mb-3"><cms:show chk_stock_error /></p>
                                <a href="<cms:link 'cart.php' />" class="btn btn-secondary">Back to cart</a>
                            </div>

                        <cms:else />

                            <cms:ignore>
                                Write the PENDING order. Sets ccs_new_order_id,
                                ccs_new_order_number, ccs_new_order_token and
                                ccs_order_error.
                            </cms:ignore>
                            <cms:embed 'utils/order_create.htm' />

                            <cms:if ccs_order_error || "<cms:not ccs_new_order_id />">
                                <div class="alert alert-danger p-4 mb-4">
                                    <h5 class="fw-bold mb-2">We could not start your order</h5>
                                    <p class="mb-0">No payment has been taken. <cms:show ccs_order_error /></p>
                                </div>

                            <cms:else_if chk_gateway = 'stripe' />

                                <cms:ignore>
                                    ================= STRIPE =================
                                    The browser already turned the card into a
                                    payment_method id. Create and confirm the
                                    intent server-side so the amount is decided
                                    here and never trusted from the client.
                                </cms:ignore>
                                <cms:php>
                                    global $CTX;

                                    $test   = ( defined('K_STRIPE_TEST_MODE') && K_STRIPE_TEST_MODE ) ? 1 : 0;
                                    $secret = $test
                                        ? ( defined('K_STRIPE_TEST_SECRET_KEY') ? K_STRIPE_TEST_SECRET_KEY : '' )
                                        : ( defined('K_STRIPE_LIVE_SECRET_KEY') ? K_STRIPE_LIVE_SECRET_KEY : '' );

                                    $pm       = isset($_POST['payment_method_id']) ? trim($_POST['payment_method_id']) : '';
                                    $order_id = $CTX->get('ccs_new_order_id');
                                    $order_no = $CTX->get('ccs_new_order_number');
                                    $token    = $CTX->get('ccs_new_order_token');
                                    $total    = $CTX->get('oc_total');

                                    $CTX->set('chk_stripe_state', 'failed', 'global');
                                    $CTX->set('chk_stripe_msg', '', 'global');
                                    $CTX->set('chk_client_secret', '', 'global');

                                    $return_url = rtrim(K_SITE_URL, '/') . '/order-complete.php?t=' . rawurlencode($token);
                                    $CTX->set('chk_return_url', $return_url, 'global');

                                    if( $secret==='' ){
                                        $CTX->set('chk_stripe_msg', 'Card payments are not configured on this site yet.', 'global');
                                    }
                                    elseif( $pm==='' ){
                                        $CTX->set('chk_stripe_msg', 'No card details were received. Please re-enter your card and try again.', 'global');
                                    }
                                    else{
                                        // Amount is derived from the stored order total, in cents.
                                        $cents = (int) round( ((float)$total) * 100 );

                                        $fields = array(
                                            'amount'                  => $cents,
                                            'currency'                => strtolower( defined('K_PAYPAL_CURRENCY') ? K_PAYPAL_CURRENCY : 'usd' ),
                                            'payment_method'          => $pm,
                                            'confirm'                 => 'true',
                                            'return_url'              => $return_url,
                                            'description'             => 'Order ' . $order_no,
                                            'payment_method_types'    => array('card'),
                                            'metadata'                => array(
                                                'order_id'     => $order_id,
                                                'order_number' => $order_no,
                                            ),
                                        );

                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_intents');
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_USERPWD, $secret . ':');
                                        curl_setopt($ch, CURLOPT_POST, true);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
                                        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                                        // Idempotency: a double-submit or a browser retry re-uses the
                                        // same intent instead of creating a second charge.
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Idempotency-Key: ccs-order-' . $order_id));

                                        $resp = curl_exec($ch);
                                        $cerr = curl_error($ch);
                                        curl_close($ch);

                                        $res = json_decode($resp, true);

                                        if( !is_array($res) ){
                                            $CTX->set('chk_stripe_msg', 'We could not reach the payment processor. ' . $cerr, 'global');
                                        }
                                        elseif( isset($res['error']) ){
                                            $CTX->set('chk_stripe_msg', isset($res['error']['message']) ? $res['error']['message'] : 'The payment was declined.', 'global');
                                        }
                                        else{
                                            $status = isset($res['status']) ? $res['status'] : '';
                                            $CTX->set('chk_intent_id', isset($res['id']) ? $res['id'] : '', 'global');

                                            if( $status=='succeeded' ){
                                                $CTX->set('chk_stripe_state', 'succeeded', 'global');
                                            }
                                            elseif( $status=='requires_action' || $status=='requires_source_action' ){
                                                // The bank wants the cardholder to authenticate.
                                                $CTX->set('chk_stripe_state', 'requires_action', 'global');
                                                $CTX->set('chk_client_secret', isset($res['client_secret']) ? $res['client_secret'] : '', 'global');
                                            }
                                            else{
                                                $CTX->set('chk_stripe_msg', 'The payment could not be completed (status: ' . $status . ').', 'global');
                                            }
                                        }
                                    }
                                </cms:php>

                                <cms:ignore>
                                    Record the intent id against the order straight
                                    away, whatever the outcome. If anything later
                                    goes wrong there is still a thread from the
                                    order record back to Stripe.
                                </cms:ignore>
                                <cms:if chk_intent_id>
                                    <cms:db_persist_ex
                                        _masterpage='orders.php' _mode='edit'
                                        _page_id=ccs_new_order_id _invalidate_cache='0'
                                        order_txn_id=chk_intent_id />
                                </cms:if>

                                <cms:if chk_stripe_state = 'succeeded'>
                                    <cms:ignore>
                                        Paid outright, no 3-D Secure needed. Hand off
                                        to order-complete.php, which fulfils, empties
                                        the cart and shows the receipt. The webhook
                                        will also fire; order_stock_deducted makes
                                        whichever loses the race a no-op.
                                    </cms:ignore>
                                    <div class="alert alert-success p-4 text-center">
                                        <h4 class="fw-bold mb-2"><i class="fas fa-check-circle me-2"></i>Payment approved</h4>
                                        <p class="mb-0">Finishing your order&hellip;</p>
                                    </div>
                                    <script>window.location.replace(<cms:php>global $CTX; echo json_encode($CTX->get('chk_return_url'));</cms:php>);</script>

                                <cms:else_if chk_stripe_state = 'requires_action' />
                                    <div class="alert alert-info p-4 text-center" id="ccs-3ds-box">
                                        <h4 class="fw-bold mb-2"><i class="fas fa-shield-halved me-2"></i>Your bank needs to verify this payment</h4>
                                        <p class="mb-0">A secure window from your bank will open. Do not close this page.</p>
                                    </div>
                                    <div class="alert alert-danger p-4 d-none" id="ccs-3ds-error"></div>
                                    <script src="https://js.stripe.com/v3/"></script>
                                    <script>
                                    (function(){
                                        var stripe = Stripe(<cms:php>global $CTX; echo json_encode($CTX->get('ccs_stripe_pk'));</cms:php>);
                                        var secret = <cms:php>global $CTX; echo json_encode($CTX->get('chk_client_secret'));</cms:php>;
                                        var done   = <cms:php>global $CTX; echo json_encode($CTX->get('chk_return_url'));</cms:php>;

                                        stripe.handleNextAction({ clientSecret: secret }).then(function(result){
                                            if( result.error ){
                                                document.getElementById('ccs-3ds-box').classList.add('d-none');
                                                var e = document.getElementById('ccs-3ds-error');
                                                e.classList.remove('d-none');
                                                e.innerHTML = '<h5 class="fw-bold mb-2">Verification failed</h5><p class="mb-0"></p>';
                                                e.querySelector('p').textContent = result.error.message || 'Your bank did not approve the payment.';
                                            } else {
                                                window.location.replace(done);
                                            }
                                        });
                                    })();
                                    </script>

                                <cms:else />
                                    <cms:ignore>
                                        Payment failed. Mark the order so it is not
                                        left sitting as 'pending' forever, and say so
                                        plainly. Stock was never touched.
                                    </cms:ignore>
                                    <cms:db_persist_ex
                                        _masterpage='orders.php' _mode='edit'
                                        _page_id=ccs_new_order_id _invalidate_cache='0'
                                        order_status='failed' />

                                    <div class="alert alert-danger p-4 mb-4">
                                        <h4 class="fw-bold mb-2"><i class="fas fa-times-circle me-2"></i>Payment failed</h4>
                                        <p class="mb-2"><cms:show chk_stripe_msg /></p>
                                        <p class="mb-0 small">Your card has not been charged and your cart is unchanged.
                                        Order reference <cms:show ccs_new_order_number /> if you need to contact us.</p>
                                    </div>
                                </cms:if>

                            <cms:else />

                                <cms:ignore>
                                    ================= PAYPAL =================
                                    Built by hand rather than via
                                    cms:pp_payment_gateway, because that tag emits
                                    no notify_url and no custom field - without
                                    those, PayPal has nowhere to send the IPN and
                                    no way to say which order it refers to, which
                                    is exactly why the old flow could never record
                                    a PayPal payment.

                                    rm=2 asks PayPal to POST the variables back to
                                    the return URL. custom carries the order id for
                                    the IPN; invoice carries the readable reference
                                    and makes PayPal reject a duplicate payment for
                                    the same order.

                                    Item amounts are per-unit and exclude tax and
                                    shipping, which travel as tax_cart and
                                    handling_cart so the totals reconcile.
                                </cms:ignore>
                                <cms:ignore>
                                    Values only here. The PayPal form itself CANNOT live inside
                                    cms:form - nested <form> elements are invalid HTML and the
                                    browser silently drops the inner one, so the auto-submit found
                                    nothing to submit and the visible button re-posted to checkout.
                                    Same trap as nested anchors in the theme notes. The real form is
                                    rendered after cms:form below, gated on chk_paypal_go.
                                </cms:ignore>
                                <cms:set chk_pp_url = "https://www.paypal.com/cgi-bin/webscr" scope='global' />
                                <cms:php>
                                    global $CTX, $CART;
                                    if( isset($CART) && $CART->get_config('paypal_use_sandbox') ){
                                        $CTX->set('chk_pp_url', 'https://www.sandbox.paypal.com/cgi-bin/webscr', 'global');
                                    }
                                    $CTX->set('chk_pp_business', isset($CART) ? $CART->get_config('paypal_email') : '', 'global');
                                    $CTX->set('chk_pp_currency', isset($CART) ? $CART->get_config('currency') : 'USD', 'global');
                                    $CTX->set('chk_ipn_url',  rtrim(K_SITE_URL,'/') . '/paypal-ipn.php', 'global');
                                    $CTX->set('chk_ret_url',  rtrim(K_SITE_URL,'/') . '/order-complete.php?t=' . rawurlencode($CTX->get('ccs_new_order_token')), 'global');
                                    $CTX->set('chk_cxl_url',  rtrim(K_SITE_URL,'/') . '/cart.php', 'global');
                                </cms:php>
                                <cms:set chk_pp_email = frm_email scope='global' />
                                <cms:set chk_paypal_go = '1' scope='global' />

                                <div class="alert alert-info text-center p-4">
                                    <i class="fas fa-spinner fa-spin me-2"></i> Sending you to PayPal&hellip;
                                </div>

                            </cms:if>

                        </cms:if>

                    <cms:else />

                    <h3 class="mb-4">Billing &amp; Shipping Information</h3>

                    <cms:if "<cms:not ccs_stripe_ready />">
                        <div class="alert alert-warning p-3 mb-4">
                            <strong>Card payments are unavailable.</strong> No Stripe publishable key is configured.
                            PayPal will still work.
                        </div>
                    </cms:if>

                    <h5 class="mb-3 fw-bold">Contact Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="order_first_name" class="form-label fw-bold">First Name *</label>
                            <cms:input type="text" class="form-control" id="order_first_name" name="first_name" label="First Name" required="1" value=prefill_first_name />
                            <cms:if k_error_first_name><div class="mt-1 text-danger fw-bold">First name is required</div></cms:if>
                        </div>
                        <div class="col-md-6">
                            <label for="order_last_name" class="form-label fw-bold">Last Name *</label>
                            <cms:input type="text" class="form-control" id="order_last_name" name="last_name" label="Last Name" required="1" value=prefill_last_name />
                            <cms:if k_error_last_name><div class="mt-1 text-danger fw-bold">Last name is required</div></cms:if>
                        </div>
                        <div class="col-12">
                            <label for="order_email" class="form-label fw-bold">Email Address *</label>
                            <cms:input type="text" validator="email" class="form-control" id="order_email" name="email" label="Email" required="1" value=k_user_email />
                            <cms:if k_error_email><div class="mt-1 text-danger fw-bold">Valid email is required</div></cms:if>
                            <div class="small mt-1 <cms:show ccs_st_secondary />">We'll send your receipt and tracking info here.</div>
                        </div>
                    </div>

                    <h5 class="mb-3 fw-bold">Shipping Address</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label for="order_address" class="form-label fw-bold">Street Address *</label>
                            <cms:input type="text" class="form-control" id="order_address" name="address" label="Address" placeholder="123 Main St" required="1" value=k_user_shipping_address />
                            <cms:if k_error_address><div class="mt-1 text-danger fw-bold">Address is required</div></cms:if>
                            <div class="form-text text-danger">Please use a physical address (No P.O. Boxes allowed).</div>
                        </div>
                        <div class="col-md-6">
                            <label for="order_city" class="form-label fw-bold">City *</label>
                            <cms:input type="text" class="form-control" id="order_city" name="city" label="City" required="1" value=k_user_shipping_city />
                            <cms:if k_error_city><div class="mt-1 text-danger fw-bold">City is required</div></cms:if>
                        </div>
                        <div class="col-md-3">
                            <label for="order_state" class="form-label fw-bold">State *</label>
                            <cms:input type="text" class="form-control" id="order_state" name="state" label="State" required="1" value=k_user_shipping_state />
                            <cms:if k_error_state><div class="mt-1 text-danger fw-bold">State is required</div></cms:if>
                        </div>
                        <div class="col-md-3">
                            <label for="order_zip" class="form-label fw-bold">Zip Code *</label>
                            <cms:input type="text" class="form-control" id="order_zip" name="zip" label="Zip" required="1" value=k_user_shipping_zip />
                            <cms:if k_error_zip><div class="mt-1 text-danger fw-bold">Zip is required</div></cms:if>
                        </div>
                    </div>

                    <div class="form-check mt-4 mb-4">
                        <input class="form-check-input" type="checkbox" id="same_as_shipping" name="same_as_shipping" value="1" checked>
                        <label class="form-check-label fw-bold" for="same_as_shipping">
                            Billing address is the same as Shipping address
                        </label>
                    </div>

                    <div id="billing_address_container" style="display: none;" class="p-3 border rounded mb-4 <cms:show ccs_st_panel />">
                        <h5 class="mb-3 fw-bold">Billing Address</h5>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Street Address *</label>
                            <input type="text" class="form-control" name="billing_address" id="billing_address" value="<cms:show k_user_billing_address />">
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label class="form-label fw-bold">City *</label>
                                <input type="text" class="form-control" name="billing_city" id="billing_city" value="<cms:show k_user_billing_city />">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">State *</label>
                                <input type="text" class="form-control" name="billing_state" id="billing_state" value="<cms:show k_user_billing_state />">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Zip Code *</label>
                                <input type="text" class="form-control" name="billing_zip" id="billing_zip" value="<cms:show k_user_billing_zip />">
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3 mt-4 fw-bold">Payment Method</h5>
                    <div class="p-3 border rounded mb-4 <cms:show ccs_st_panel />">

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="gateway" id="gateway_stripe" value="stripe" checked>
                            <label class="form-check-label d-flex align-items-center fw-bold" for="gateway_stripe">
                                <i class="fas fa-credit-card me-2 text-primary"></i> Credit / Debit Card
                            </label>
                            <div id="stripe-card-container" class="mt-3 p-3 border rounded bg-body text-body">
                                <div id="card-element"></div>
                                <div id="card-errors" class="mt-2 text-danger fw-bold" role="alert"></div>
                            </div>
                        </div>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="radio" name="gateway" id="gateway_paypal" value="paypal">
                            <label class="form-check-label d-flex align-items-center fw-bold" for="gateway_paypal">
                                <i class="fab fa-paypal me-2 text-primary"></i> PayPal
                            </label>
                        </div>
                    </div>

                    <button type="submit" id="ccs-checkout-submit" class="btn btn-primary w-100 py-3 fw-bold">
                        <i class="fas fa-lock me-2"></i> <span id="ccs-submit-label">Proceed to Payment</span>
                    </button>

                    </cms:if>

                </cms:form>

                <cms:ignore>
                    OUTSIDE cms:form on purpose - see the note in the PayPal branch
                    above. This is a sibling of the checkout form, never a child.
                </cms:ignore>
                <cms:if chk_paypal_go>
                    <form action="<cms:show chk_pp_url />" method="post" id="ccs-paypal-form">
                        <input type="hidden" name="cmd" value="_cart">
                        <input type="hidden" name="upload" value="1">
                        <input type="hidden" name="business" value="<cms:show chk_pp_business />">
                        <input type="hidden" name="currency_code" value="<cms:show chk_pp_currency />">
                        <input type="hidden" name="rm" value="2">
                        <input type="hidden" name="notify_url" value="<cms:show chk_ipn_url />">
                        <input type="hidden" name="return" value="<cms:show chk_ret_url />">
                        <input type="hidden" name="cancel_return" value="<cms:show chk_cxl_url />">
                        <input type="hidden" name="custom" value="<cms:show ccs_new_order_id />">
                        <input type="hidden" name="invoice" value="<cms:show ccs_new_order_number />">
                        <input type="hidden" name="email" value="<cms:show chk_pp_email />">

                        <cms:ignore>
                            PayPal numbers cart lines from 1: item_name_1, amount_1, and so on.
                            pp_cart_items does NOT expose k_count - cart_items_handler builds its
                            vars from the item attributes and never sets a counter - so the index
                            has to be kept by hand. Getting this wrong renders the fields as
                            "amount_" with no number, and PayPal answers AMOUNT_MISSING.
                            KCart::payment_gateway does the same thing with $item_count = 1.
                        </cms:ignore>
                        <cms:set pp_idx = "0" scope="global" />
                        <cms:pp_cart_items>
                            <cms:set pp_idx = "<cms:add pp_idx '1' />" scope="global" />
                            <input type="hidden" name="item_number_<cms:show pp_idx />" value="<cms:show id />">
                            <input type="hidden" name="item_name_<cms:show pp_idx />" value="<cms:show title />">
                            <input type="hidden" name="amount_<cms:show pp_idx />" value="<cms:show price />">
                            <input type="hidden" name="quantity_<cms:show pp_idx />" value="<cms:show quantity />">
                            <input type="hidden" name="shipping_<cms:show pp_idx />" value="0">
                        </cms:pp_cart_items>

                        <input type="hidden" name="tax_cart" value="<cms:show oc_tax />">
                        <input type="hidden" name="handling_cart" value="<cms:show oc_shipping />">

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">Continue to PayPal</button>
                    </form>
                    <script>
                        (function(){
                            var f = document.getElementById('ccs-paypal-form');
                            if( f ){ setTimeout(function(){ f.submit(); }, 500); }
                        })();
                    </script>
                </cms:if>


            </div>

            <div class="col-lg-5">
                <div class="border p-4 sticky-top <cms:show ccs_st_panel />" style="top: 100px;">
                    <h4 class="mb-3 fw-bold">Order Summary</h4>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Subtotal</span>
                        <span class="fw-bold">$<cms:number_format "<cms:pp_sub_total />" /></span>
                    </div>
                    <cms:if "<cms:pp_shipping />">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">Shipping</span>
                            <span class="fw-bold">$<cms:number_format "<cms:pp_shipping />" /></span>
                        </div>
                    </cms:if>
                    <cms:if "<cms:pp_taxes />">
                        <hr class="my-2">
                        <cms:each pp_custom_taxes>
                            <div class="d-flex justify-content-between mb-1 <cms:show ccs_st_secondary />">
                                <span>Tax (<cms:show key />)</span>
                                <span>$<cms:number_format "<cms:show item />" /></span>
                            </div>
                        </cms:each>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">Taxes Total</span>
                            <span class="fw-bold">$<cms:number_format "<cms:pp_taxes />" /></span>
                        </div>
                    </cms:if>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold fs-8">Total</span>
                        <span class="fw-bold fs-8">$<cms:number_format "<cms:pp_total />" /></span>
                    </div>
                </div>
            </div>

        </div>
        </cms:if>

    </div>
</section>

<cms:if "<cms:pp_count_items />" && "<cms:not k_success />">
<script src="https://js.stripe.com/v3/"></script>
<script>
(function(){
    var sameAs  = document.getElementById('same_as_shipping');
    var billing = document.getElementById('billing_address_container');
    if( sameAs && billing ){
        sameAs.addEventListener('change', function(){
            billing.style.display = this.checked ? 'none' : 'block';
        });
    }

    var pk = <cms:php>global $CTX; echo json_encode($CTX->get('ccs_stripe_pk'));</cms:php>;

    var cardEl      = document.getElementById('card-element');
    var stripeRadio = document.getElementById('gateway_stripe');
    var paypalRadio = document.getElementById('gateway_paypal');
    var container   = document.getElementById('stripe-card-container');
    var submitBtn   = document.getElementById('ccs-checkout-submit');
    var label       = document.getElementById('ccs-submit-label');

    /* The form is found from the card element outward, never with
       querySelector('form') - the nav and the footer newsletter both put
       a form on this page ahead of the checkout one. */
    var form = cardEl ? cardEl.closest('form') : null;
    if( !form ) return;

    function toggle(){
        var useCard = stripeRadio && stripeRadio.checked;
        if( container ) container.style.display = useCard ? 'block' : 'none';
        if( label ) label.textContent = useCard ? 'Pay Now' : 'Continue to PayPal';
    }
    if( stripeRadio ) stripeRadio.addEventListener('change', toggle);
    if( paypalRadio ) paypalRadio.addEventListener('change', toggle);

    if( !pk ){
        if( container ) container.style.display = 'none';
        if( stripeRadio ) stripeRadio.disabled = true;
        if( paypalRadio ) paypalRadio.checked = true;
        toggle();
        return;
    }

    var stripe   = Stripe(pk);
    var elements = stripe.elements();
    /* Stripe draws the card field inside its own iframe, so no stylesheet of
       ours can reach it - its API takes literal values only. Rather than type
       hex codes here, which would go stale the moment the site theme or the
       panel setting changes, ask the browser what it has ALREADY resolved for
       this box and hand Stripe the same numbers. A throwaway probe span does
       the same for the theme's danger colour. */
    function ccsProbeColor( host, cls ){
        var probe = document.createElement('span');
        probe.className = cls;
        probe.style.display = 'none';
        host.appendChild(probe);
        var c = window.getComputedStyle(probe).color;
        host.removeChild(probe);
        return c;
    }

    var cardBox   = document.getElementById('stripe-card-container');
    var boxStyle  = window.getComputedStyle(cardBox);
    var cardColor = boxStyle.color;
    var errColor  = ccsProbeColor(cardBox, 'text-danger');

    /* The placeholder is the field's own colour at reduced alpha, so it can
       only move toward the background it sits on, never across it. */
    var hintColor = cardColor.replace(/^rgba?\(([^)]+)\)$/, function(m, inner){
        var p = inner.split(',').slice(0, 3).map(function(v){ return v.trim(); });
        return 'rgba(' + p.join(', ') + ', 0.55)';
    });

    var card = elements.create('card', { style: {
        base: {
            color: cardColor,
            fontFamily: boxStyle.fontFamily,
            fontSize: boxStyle.fontSize,
            fontSmoothing: 'antialiased',
            '::placeholder': { color: hintColor }
        },
        invalid: { color: errColor, iconColor: errColor }
    } });
    card.mount('#card-element');

    var errBox = document.getElementById('card-errors');
    card.on('change', function(ev){ errBox.textContent = ev.error ? ev.error.message : ''; });

    toggle();

    var submitting = false;
    form.addEventListener('submit', function(ev){
        if( !stripeRadio || !stripeRadio.checked ) return;   /* PayPal posts normally */
        if( submitting ) return;
        ev.preventDefault();
        submitting = true;
        submitBtn.disabled = true;
        if( label ) label.textContent = 'Checking your card…';

        /* createPaymentMethod, not createToken. The token/Charges pair cannot
           carry a 3-D Secure challenge, so cards that require one just fail. */
        stripe.createPaymentMethod({ type: 'card', card: card }).then(function(result){
            if( result.error ){
                errBox.textContent = result.error.message;
                submitting = false;
                submitBtn.disabled = false;
                if( label ) label.textContent = 'Pay Now';
                return;
            }
            var hidden = document.createElement('input');
            hidden.type  = 'hidden';
            hidden.name  = 'payment_method_id';
            hidden.value = result.paymentMethod.id;
            form.appendChild(hidden);
            form.submit();
        });
    });
})();
</script>
</cms:if>

<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>
