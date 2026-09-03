<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='Order Complete' hidden='1' executable='1' parent='_donottouch_' order='84'>
    <cms:editable type='message' name='ocp_msg' order='1'>
        <p>Where the customer lands after paying, by either gateway.
        Addressed by the order's random page_name, never its id.</p>
    </cms:editable>
</cms:template>
<cms:no_cache />
<cms:ignore>
    ================================================================
    ORDER COMPLETE

    Reached as order-complete.php?t=<order page_name>. That token is the
    random md5 db_persist generated when the order was created, NOT the
    sequential page id - so nobody can count upwards through ?t= and read
    other people's names, addresses and order contents.

    This page is a SAFETY NET, not the authority. Stripe's webhook and
    PayPal's IPN are what really confirm payment. But a webhook can be
    seconds late, and a customer staring at "processing" wonders whether
    they have been charged. So for Stripe we ask the API directly what
    happened to the intent, and fulfil here if it already succeeded.
    order_stock_deducted means whichever path arrives second does nothing.

    PayPal is not polled - Payments Standard gives us no read API here -
    so a PayPal order that the IPN has not yet confirmed honestly says so.

    THE CART IS EMPTIED HERE, and only once an order is actually paid.
    It cannot be emptied server-side by a webhook: that runs in PayPal's
    or Stripe's request, with no access to this visitor's session.
    ================================================================
</cms:ignore>

<cms:set ocp_token = "<cms:gpc 't' method='get' />" />
<cms:set ocp_found = '' scope='global' />

<cms:ignore>
    Declared up front rather than only inside the branches that set them.
    An undefined variable reads as empty and would work by accident here,
    which is exactly the kind of thing that stops working by accident later.
</cms:ignore>
<cms:set ocp_owner   = '' scope='global' />
<cms:set ocp_claimed = '' scope='global' />
<cms:set ocl_result  = '' scope='global' />

<cms:if ocp_token>
    <cms:pages masterpage='orders.php' page_name=ocp_token limit='1'>
        <cms:set ocp_found       = '1'            scope='global' />
        <cms:set ocp_id          = k_page_id      scope='global' />
        <cms:set ocp_number      = order_number   scope='global' />
        <cms:set ocp_status      = order_status   scope='global' />
        <cms:set ocp_gateway     = order_gateway  scope='global' />
        <cms:set ocp_txn         = order_txn_id   scope='global' />
        <cms:set ocp_total       = order_total    scope='global' />
        <cms:set ocp_email       = order_email    scope='global' />
        <cms:set ocp_first       = order_first_name scope='global' />
        <cms:set ocp_sub         = order_subtotal  scope='global' />
        <cms:set ocp_ship        = order_shipping  scope='global' />
        <cms:set ocp_tax         = order_tax       scope='global' />
        <cms:set ocp_owner       = order_user_id   scope='global' />
    </cms:pages>
</cms:if>

<cms:ignore>
    GUEST ORDER CLAIM
    -----------------
    An order placed without signing in has no order_user_id, so it can never
    appear in my-orders.php. This is the one moment the visitor is holding
    proof of it - the token in the URL - so it is the one moment the order can
    be filed against an account without anyone having to prove anything else.

    Runs before the frame so a claim redirects cleanly rather than writing
    into a half-rendered page. See snippets/utils/order_claim.htm for why
    possession of the token is sufficient authority and why an order that
    already has an owner is never reassigned.
</cms:ignore>
<cms:ignore>
    Assign, then compare. A quoted tag used directly as an operand is a
    complete expression to Couch's parser, which then accepts only && or ||
    after it - a comparison operator there fails the whole file with

        ERROR! LOGIC_OP: Invalid char "e"

    the "e" being the start of eq.
</cms:ignore>
<cms:set ocp_claim_post = "<cms:gpc 'claim' method='post' />" />
<cms:if ocp_found && ocp_claim_post eq '1'>
    <cms:set ocl_order_id = ocp_id scope='global' />
    <cms:set ocl_owner    = ocp_owner scope='global' />
    <cms:embed 'utils/order_claim.htm' />

    <cms:if ocl_result eq 'claimed'>
        <cms:set ocp_owner = ccs_auth_uid scope='global' />
        <cms:set ocp_claimed = '1' scope='global' />
    </cms:if>
</cms:if>

<cms:ignore>
    Still pending on a card order? Ask Stripe what actually happened rather
    than guessing. A read-only retrieve; the webhook remains authoritative.
</cms:ignore>
<cms:if ocp_found && ocp_status == 'pending' && ocp_gateway == 'stripe' && ocp_txn>
    <cms:php>
        global $CTX;
        $test   = ( defined('K_STRIPE_TEST_MODE') && K_STRIPE_TEST_MODE ) ? 1 : 0;
        $secret = $test
            ? ( defined('K_STRIPE_TEST_SECRET_KEY') ? K_STRIPE_TEST_SECRET_KEY : '' )
            : ( defined('K_STRIPE_LIVE_SECRET_KEY') ? K_STRIPE_LIVE_SECRET_KEY : '' );

        $CTX->set( 'ocp_intent_ok', '', 'global' );

        if( $secret !== '' && function_exists('curl_init') ){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_intents/' . rawurlencode($CTX->get('ocp_txn')));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $secret . ':');
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            $resp = curl_exec($ch);
            curl_close($ch);

            $res = json_decode($resp, true);
            if( is_array($res) && isset($res['status']) && $res['status'] === 'succeeded' ){
                $CTX->set( 'ocp_intent_ok', '1', 'global' );
            }
        }
    </cms:php>

    <cms:if ocp_intent_ok>
        <cms:set ccs_fulfil_order_id = ocp_id />
        <cms:set ccs_fulfil_txn_id = ocp_txn />
        <cms:embed 'utils/order_fulfil.htm' />
        <cms:set ocp_status = 'paid' scope='global' />
    </cms:if>
</cms:if>

<cms:embed 'pb_mods/pg_frame/head.htm' />
<cms:embed 'pb_mods/pg_frame/main-cap.htm' />
<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />
<cms:embed 'utils/store_theme.htm' />

<section class="pt-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <cms:if "<cms:not ocp_found />">
                    <h1 class="pt-6 pb-3">Order not found</h1>
                    <div class="alert alert-warning p-4">
                        <p class="mb-0">We could not find that order. If you have just paid, check your
                        email for a receipt before trying again &mdash; and please do not re-submit your payment.</p>
                    </div>
                    <a href="<cms:link 'product.php' />" class="btn btn-primary mt-3">Continue shopping</a>

                <cms:else_if ocp_status == 'paid' />
                    <cms:ignore>Paid and fulfilled - safe to clear the cart.</cms:ignore>
                    <cms:pp_empty_cart />

                    <h1 class="pt-6 pb-3">Thank you<cms:if ocp_first>, <cms:show ocp_first /></cms:if>.</h1>
                    <div class="alert alert-success p-4 mb-4">
                        <h4 class="fw-bold mb-2"><i class="fas fa-check-circle me-2"></i>Your order is confirmed</h4>
                        <p class="mb-0">A receipt is on its way to <strong><cms:show ocp_email /></strong>.</p>
                    </div>
                    <table class="table">
                        <tr><th style="width:220px">Order reference</th><td class="fw-bold"><cms:show ocp_number /></td></tr>
                        <tr><th>Paid with</th><td><cms:if ocp_gateway == 'paypal'>PayPal<cms:else />Card</cms:if></td></tr>
                    </table>

                    <cms:ignore>
                        The same line items as the emailed receipt, from the same
                        query, so the two can never disagree. Totals are emphasised
                        with weight and size rather than colour - see the Store
                        Appearance note: no fixed hue stays readable against a
                        background the theme is free to move.
                    </cms:ignore>
                    <h2 class="fs-8 fw-bold mt-5 mb-3">What you ordered</h2>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center" style="width:70px">Qty</th>
                                    <th class="text-end" style="width:120px">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <cms:pages masterpage='order-items.php' custom_field="item_order_id==<cms:show ocp_id />" limit='500'>
                                    <tr>
                                        <td>
                                            <cms:show item_title />
                                            <cms:if item_variants><br><span class="small <cms:show ccs_st_secondary />"><cms:show item_variants /></span></cms:if>
                                        </td>
                                        <td class="text-center"><cms:show item_qty /></td>
                                        <td class="text-end">$<cms:number_format item_line_total /></td>
                                    </tr>
                                </cms:pages>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end border-0 pb-0 <cms:show ccs_st_secondary />">Subtotal</td>
                                    <td class="text-end border-0 pb-0">$<cms:number_format ocp_sub /></td>
                                </tr>
                                <cms:if ocp_ship gt '0'>
                                    <tr>
                                        <td colspan="2" class="text-end border-0 py-0 <cms:show ccs_st_secondary />">Shipping</td>
                                        <td class="text-end border-0 py-0">$<cms:number_format ocp_ship /></td>
                                    </tr>
                                </cms:if>
                                <cms:if ocp_tax gt '0'>
                                    <tr>
                                        <td colspan="2" class="text-end border-0 py-0 <cms:show ccs_st_secondary />">Tax</td>
                                        <td class="text-end border-0 py-0">$<cms:number_format ocp_tax /></td>
                                    </tr>
                                </cms:if>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold fs-8 pt-3">Paid</td>
                                    <td class="text-end fw-bold fs-8 pt-3">$<cms:number_format ocp_total /></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <p class="small <cms:show ccs_st_secondary />">Quote your order reference in any correspondence.</p>

                    <cms:ignore>
                        KEEP THIS ORDER. Shown only when the order belongs to nobody -
                        once it has an owner the panel is gone for good, so there is
                        nothing here for a second person to press.

                        Colours are fixed pairs, not alert-*, for the same reason as
                        the status pills on my-orders.php.
                    </cms:ignore>
                    <cms:if ocp_claimed>
                        <div class="ocl-note ocl-ok p-4 mt-4">
                            <h4 class="fw-bold mb-2" style="font-size:16px">
                                <i class="fas fa-check-circle me-2" aria-hidden="true"></i>Saved to your account
                            </h4>
                            <p class="mb-3" style="font-size:14px">
                                Order <strong><cms:show ocp_number /></strong> is now in your order history,
                                with its receipt, for as long as you have an account.
                            </p>
                            <a href="<cms:link 'my-orders.php' />" class="btn btn-primary btn-sm">
                                <i class="fas fa-receipt me-1" aria-hidden="true"></i> View my orders
                            </a>
                        </div>

                    <cms:else_if ocp_owner eq '' />
                        <div class="ocl-note ocl-offer p-4 mt-4">
                            <h4 class="fw-bold mb-2" style="font-size:16px">Keep this order</h4>

                            <cms:if k_logged_in && ccs_auth_uid>
                                <p class="mb-3" style="font-size:14px">
                                    You checked out without signing in, so this order is not attached to
                                    your account yet. Save it and it joins your order history, receipt
                                    and all.
                                </p>
                                <form method="post" class="m-0">
                                    <input type="hidden" name="claim" value="1">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-bookmark me-1" aria-hidden="true"></i> Save this order to my account
                                    </button>
                                </form>

                            <cms:else />
                                <p class="mb-3" style="font-size:14px">
                                    You checked out as a guest, so this order lives only in the receipt
                                    emailed to <strong><cms:show ocp_email /></strong>. With an account
                                    it is kept in your order history, and your details are filled in
                                    next time.
                                </p>
                                <cms:ignore>
                                    Both routes come back to THIS url, token and all, where the
                                    save button is waiting. Registration needs email activation
                                    before a first sign-in, so nothing is carried through it -
                                    the token in the address bar is the whole state.
                                </cms:ignore>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="<cms:link 'users/register.php' />" class="btn btn-primary btn-sm">
                                        Create an account
                                    </a>
                                    <a href="<cms:login_link redirect='1' />" class="btn btn-outline-primary btn-sm">
                                        I already have one
                                    </a>
                                </div>
                                <p class="mb-0 mt-3" style="font-size:13px">
                                    Come back to this page once you are signed in &mdash; the link is in your
                                    receipt email &mdash; and press Save. Nothing is lost in the meantime.
                                </p>
                            </cms:if>
                        </div>

                    <cms:else_if k_logged_in && ccs_auth_uid && ocp_owner eq ccs_auth_uid />
                        <cms:ignore>
                            Already filed, including on a reload straight after saving.
                            Without this the success panel would simply vanish on
                            refresh, which reads as the save having come undone.
                        </cms:ignore>
                        <p class="mt-4 mb-0 <cms:show ccs_st_secondary />" style="font-size:14px">
                            <i class="fas fa-check me-1" aria-hidden="true"></i>
                            This order is saved to your account &mdash;
                            <a href="<cms:link 'my-orders.php' />">see all your orders</a>.
                        </p>

                    <cms:else_if ocl_result eq 'notyours' />
                        <div class="ocl-note ocl-warn p-4 mt-4">
                            <p class="mb-0" style="font-size:14px">
                                This order is already saved to a different account, so it has been left
                                alone. If that is not right, quote order
                                <strong><cms:show ocp_number /></strong> and we will sort it out.
                            </p>
                        </div>
                    </cms:if>

                    <a href="<cms:link 'product.php' />" class="btn btn-primary mt-4">Continue shopping</a>

                <cms:else_if ocp_status == 'failed' />
                    <h1 class="pt-6 pb-3">Payment did not go through</h1>
                    <div class="alert alert-danger p-4">
                        <p class="mb-0">Order <strong><cms:show ocp_number /></strong> was not paid, and
                        nothing has been charged. Your cart is untouched, so you can try again.</p>
                    </div>
                    <a href="<cms:link 'checkout.php' />" class="btn btn-primary mt-3">Return to checkout</a>

                <cms:else />
                    <cms:ignore>
                        Pending. Almost always a PayPal order whose IPN has not
                        landed yet, or an e-cheque, which can take days. Do not
                        claim success and do not empty the cart.
                    </cms:ignore>
                    <h1 class="pt-6 pb-3">We're confirming your payment</h1>
                    <div class="alert alert-info p-4">
                        <h4 class="fw-bold mb-2"><i class="fas fa-hourglass-half me-2"></i>Order <cms:show ocp_number /> is being processed</h4>
                        <p class="mb-2">Your payment has been submitted and we are waiting for the payment
                        provider to confirm it. This is usually seconds, but some payment types take longer.</p>
                        <p class="mb-0"><strong>Please do not pay again.</strong> We will email
                        <strong><cms:show ocp_email /></strong> as soon as it clears.</p>
                    </div>
                    <a href="" class="btn btn-secondary mt-3">Check again</a>
                </cms:if>

            </div>
        </div>
    </div>
</section>

<cms:ignore>
    Fixed colour pairs. text-bg-* and alert-* take one half of the pair from a
    theme variable and leave the other a build-time literal, so Site Colors can
    drift them apart. Same rule as my-orders.php and restock.php.
</cms:ignore>
<style>
 .ocl-note{border:1px solid;border-radius:4px}
 .ocl-offer{background:#f7f8f9;border-color:#d9dde1;color:#22252a}
 .ocl-ok{background:#e4f0ea;border-color:#1c6045;color:#144934}
 .ocl-warn{background:#f6efe1;border-color:#7a4f0c;color:#5c3b09}
</style>

<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>
