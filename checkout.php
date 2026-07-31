<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Checkout" parent='_donottouch_' icon='cart' order="81" >
    <!-- We will define backend settings for Stripe/PayPal here later -->
</cms:template>
<script src="https://js.stripe.com/v3/"></script>
<cms:no_cache />
<cms:embed 'pb_mods/pg_frame/head.htm' />
<cms:set my_redirect_link = k_page_link />
<cms:embed 'pb_mods/pg_frame/main-cap.htm' />   
<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />

<!-- ============================================-->
<!-- <section> begin ============================-->
<section class="pt-0">
    <div class="container">
        
        <div class="row">
            <div class="col-12">
                <h1 class="pt-6 pb-4">Secure Checkout</h1>
            </div>
        </div>
        
        <div class="row">
            
            <!-- CHECKOUT FORM COLUMN -->
            <div class="col-lg-7 pe-lg-5">
                <h3 class="mb-4">Billing & Shipping Information</h3>
                
                <cms:form method="post" anchor='0'>
    
                    <cms:if k_success>
    
                    <!-- 1. Capture the selected payment gateway from the radio buttons -->
                    <cms:set selected_gateway = "<cms:gpc 'gateway' method='post' />" />

                    <cms:if selected_gateway == 'paypal'>

                        <!-- 2. Generate CouchCart's native PayPal form invisibly -->
                        <div class="d-none">
                            <cms:pp_payment_gateway use_paypal='1' empty_cart='0' />
                        </div>

                        <!-- 3. Show a clean loading message -->
                        <div class="alert alert-info text-center mt-4">
                            <i class="fas fa-spinner fa-spin me-2"></i> Redirecting securely to PayPal...
                        </div>

                        <!-- 4. Auto-submit the hidden PayPal form -->
                        <script>
                            setTimeout(function(){
                                document.querySelector('form[action*="paypal.com"]').submit();
                            }, 800);
                        </script>

                    <cms:else />

                        <!-- Process Stripe Payment -->
                        <cms:if frm_gateway = 'stripe' >
                            <cms:php>
                                // 1. Retrieve the token and the cart total
                                global $CTX;
                                $token = $_POST['stripeToken'];
                                $cart_total = $CTX->get('k_cart_total'); // CouchCart's native total variable

                                // Stripe requires the amount in cents (e.g., $10.50 must be 1050)
                                $amount_in_cents = round($cart_total * 100);

                                // 2. Stripe Test Secret Key 
                                // (This pairs with your pk_test... key. Swap it for the live key later!)
                                $stripe_secret_key = 'sk_test_dummy_key_replace_me'; 

                                // 3. Build the secure server-to-server request via cURL
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/charges');
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_USERPWD, $stripe_secret_key . ':'); // Notice the colon!
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                                    'amount' => $amount_in_cents,
                                    'currency' => 'usd',
                                    'source' => $token,
                                    'description' => 'Website Order Prototype'
                                ]));

                                // 4. Execute the charge and read Stripe's response
                                $response = curl_exec($ch);
                                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                curl_close($ch);

                                $result = json_decode($response, true);

                                // 5. Check if it succeeded and pass the result back to CouchCMS
                                if ($http_code == 200 && isset($result['status']) && $result['status'] == 'succeeded') {
                                    $CTX->set('stripe_payment_status', 'success');
                                } else {
                                    $CTX->set('stripe_payment_status', 'failed');
                                    // Grab the specific error message from Stripe (e.g., "Insufficient Funds")
                                    $error_message = isset($result['error']['message']) ? $result['error']['message'] : 'Payment failed.';
                                    $CTX->set('stripe_error_message', $error_message);
                                }
                            </cms:php>

                            <!-- 6. Handle the UI based on the response -->
                            <cms:if stripe_payment_status = 'success'>

                                <div class="alert alert-success p-4 mb-4 text-center">
                                    <h4 class="mb-2 fw-bold"><i class="fas fa-check-circle me-2"></i> Payment Successful!</h4>
                                    <p class="mb-0">Your card has been charged successfully.</p>
                                </div>

                                <!-- 1. Loop through all items in the purchased cart -->
                                <cms:pp_cart_items>

                                    <!-- 2. Access the specific cloned page for this product -->
                                    <cms:pages id="<cms:show id />" limit='1'>

                                        <!-- 3. Check if inventory tracking is turned on for this item -->
                                        <cms:if "<cms:is '1' in=track_inventory />" >

                                            <!-- 4. Calculate the new inventory number -->
                                            <cms:set current_stock = in_stock />
                                            <cms:set new_stock = "<cms:sub current_stock quantity />" />

                                            <!-- Safety check: Prevent inventory from dropping below zero -->
                                            <cms:if new_stock lt '0'>
                                                <cms:set new_stock = '0' />
                                            </cms:if>

                                            <!-- 5. Persist the new inventory number to the database -->
                                            <cms:db_persist
                                                _masterpage=k_template_name
                                                _page_id=k_page_id
                                                _mode='edit'
                                                in_stock=new_stock
                                            />

                                        </cms:if>

                                    </cms:pages>

                                </cms:pp_cart_items>

                                <!-- 6. Empty the cart now that the transaction is complete -->
                                <cms:pp_empty_cart />

                            <cms:else />

                                <div class="alert alert-danger p-4 mb-4 text-center">
                                    <h4 class="mb-2 fw-bold"><i class="fas fa-times-circle me-2"></i> Payment Failed</h4>
                                    <p class="mb-0"><cms:show stripe_error_message /></p>
                                </div>

                            </cms:if>

                        </cms:if>

                    </cms:if>

                </cms:if>

                <!-- Contact Info -->
                <h5 class="mb-3 fw-bold">Contact Information</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="order_first_name" class="form-label fw-bold">First Name *</label>
                        <cms:input type="text" class="form-control" id="order_first_name" name="first_name" required="1" />
                        <cms:if k_error_first_name><div class="mt-1 text-danger fw-bold">First name is required</div></cms:if>
                    </div>
                    <div class="col-md-6">
                        <label for="order_last_name" class="form-label fw-bold">Last Name *</label>
                        <cms:input type="text" class="form-control" id="order_last_name" name="last_name" required="1" />
                        <cms:if k_error_last_name><div class="mt-1 text-danger fw-bold">Last name is required</div></cms:if>
                    </div>
                    <div class="col-12">
                        <label for="order_email" class="form-label fw-bold">Email Address *</label>
                        <cms:input type="text" validator="email" class="form-control" id="order_email" name="email" required="1" />
                        <cms:if k_error_email><div class="mt-1 text-danger fw-bold">Valid email is required</div></cms:if>
                        <div class="form-text text-muted">We'll send your receipt and tracking info here.</div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <h5 class="mb-3 fw-bold">Shipping Address</h5>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label for="order_address" class="form-label fw-bold">Street Address *</label>
                        <cms:input type="text" class="form-control" id="order_address" name="address" placeholder="123 Main St" required="1" />
                        <cms:if k_error_address><div class="mt-1 text-danger fw-bold">Address is required</div></cms:if>
                        <div class="form-text text-danger">Please use a physical address (No P.O. Boxes allowed).</div>
                    </div>
                    <div class="col-md-6">
                        <label for="order_city" class="form-label fw-bold">City *</label>
                        <cms:input type="text" class="form-control" id="order_city" name="city" required="1" />
                        <cms:if k_error_city><div class="mt-1 text-danger fw-bold">City is required</div></cms:if>
                    </div>
                    <div class="col-md-3">
                        <label for="order_state" class="form-label fw-bold">State *</label>
                        <cms:input type="text" class="form-control" id="order_state" name="state" required="1" />
                        <cms:if k_error_state><div class="mt-1 text-danger fw-bold">State is required</div></cms:if>
                    </div>
                    <div class="col-md-3">
                        <label for="order_zip" class="form-label fw-bold">Zip Code *</label>
                        <cms:input type="text" class="form-control" id="order_zip" name="zip" required="1" />
                        <cms:if k_error_zip><div class="mt-1 text-danger fw-bold">Zip is required</div></cms:if>
                    </div>
                </div>

                    
                    
                <!-- The Billing Toggle -->
                <div class="form-check mt-4 mb-4">
                    <input class="form-check-input" type="checkbox" id="same_as_shipping" name="same_as_shipping" value="1" checked>
                    <label class="form-check-label fw-bold" for="same_as_shipping">
                        Billing address is the same as Shipping address
                    </label>
                </div>

                <!-- Billing Address Container (Hidden by default) -->
                <div id="billing_address_container" style="display: none;" class="p-3 border rounded mb-4 bg-light">
                    <h5 class="mb-3 fw-bold">Billing Address</h5>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Street Address *</label>
                        <input type="text" class="form-control" name="billing_street" id="billing_street">
                    </div>

                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label class="form-label fw-bold">City *</label>
                            <input type="text" class="form-control" name="billing_city" id="billing_city">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">State *</label>
                            <input type="text" class="form-control" name="billing_state" id="billing_state">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Zip Code *</label>
                            <input type="text" class="form-control" name="billing_zip" id="billing_zip">
                        </div>
                    </div>
                </div>

                    
                    
                    
                    
                <!-- Payment Selection -->
                <h5 class="mb-3 mt-4 fw-bold">Payment Method</h5>
                <div class="p-3 border rounded mb-4 bg-light">

                    <!-- Stripe (Credit Card) Option -->
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="gateway" id="gateway_stripe" value="stripe" checked>
                        <label class="form-check-label d-flex align-items-center fw-bold" for="gateway_stripe">
                            <i class="fas fa-credit-card me-2 text-primary"></i> Credit / Debit Card
                        </label>

                        <!-- NEW: Stripe Elements Secure Container -->
                        <div id="stripe-card-container" class="mt-3 p-3 border rounded bg-white">
                            <div id="card-element">
                                <!-- Stripe's Javascript will securely inject the card inputs here -->
                            </div>
                            <!-- Container for Stripe validation errors -->
                            <div id="card-errors" class="mt-2 text-danger fw-bold" role="alert"></div>
                        </div>
                    </div>

                    <!-- PayPal Option -->
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="radio" name="gateway" id="gateway_paypal" value="paypal">
                        <label class="form-check-label d-flex align-items-center fw-bold" for="gateway_paypal">
                            <i class="fab fa-paypal me-2 text-primary"></i> PayPal
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                    <i class="fas fa-lock me-2"></i> Proceed to Payment
                </button>

            </cms:form>
                
            </div>
            
            <!-- ORDER SUMMARY COLUMN -->
            <div class="col-lg-5">
                <div class="border p-4 sticky-top bg-light" style="top: 100px;">
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

                    <!-- KK'S CUSTOM TAX BREAKDOWN -->
                    <cms:if "<cms:pp_taxes />">
                        <hr class="my-2">
                        <cms:each pp_custom_taxes>
                            <!-- Shows individual category e.g., "Tax (Ammo)" -->
                            <div class="d-flex justify-content-between mb-1 text-muted">
                                <span>Tax (<cms:show key />)</span>
                                <span>$<cms:number_format "<cms:show item />" /></span>
                            </div>
                        </cms:each>

                        <!-- Shows combined tax total -->
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">Taxes Total</span>
                            <span class="fw-bold">$<cms:number_format "<cms:pp_taxes />" /></span>
                        </div>
                    </cms:if>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-success fw-bold">Total</span>
                        <span class="text-success fw-bold">$<cms:number_format "<cms:pp_total />" /></span>
                    </div>

                </div>
            </div>
            
        </div>
        
    </div>
</section>
<!-- <section> close ============================-->
<!-- ============================================-->
<script>
    
    
    // UI Toggle: Billing vs Shipping Address
    var sameAsShippingCheckbox = document.getElementById('same_as_shipping');
    var billingContainer = document.getElementById('billing_address_container');

    sameAsShippingCheckbox.addEventListener('change', function() {
        if (this.checked) {
            billingContainer.style.display = 'none';
            // Optional: clear the billing fields if they re-check the box
            document.getElementById('billing_street').value = '';
            document.getElementById('billing_city').value = '';
            document.getElementById('billing_state').value = '';
            document.getElementById('billing_zip').value = '';
        } else {
            billingContainer.style.display = 'block';
        }
    });
    
    
    
    
    
    // 1. Initialize Stripe with the public test key
    var stripe = Stripe('pk_test_TYooMQauvdEDq54NiTphI7jx');
    var elements = stripe.elements();

    // 2. Custom styling to match your Bootstrap theme
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    // 3. Create the card Element and mount it
    var card = elements.create('card', {style: style});
    card.mount('#card-element');

    // 4. Handle real-time validation errors
    card.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // 5. UI Toggle: Show/Hide Stripe fields
    var stripeRadio = document.getElementById('gateway_stripe');
    var paypalRadio = document.getElementById('gateway_paypal');
    var stripeContainer = document.getElementById('stripe-card-container');

    function togglePaymentFields() {
        if (stripeRadio.checked) {
            stripeContainer.style.display = 'block';
        } else {
            stripeContainer.style.display = 'none';
        }
    }

    stripeRadio.addEventListener('change', togglePaymentFields);
    paypalRadio.addEventListener('change', togglePaymentFields);

    // 6. NEW: Intercept the form submission
    var form = document.querySelector('form');
    
    form.addEventListener('submit', function(event) {
        // Only intercept if Stripe is selected
        if (stripeRadio.checked) {
            event.preventDefault(); // Stop the form from submitting natively

            // Request a secure token from Stripe
            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Show error in the UI if card is invalid
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Success! Send the token to CouchCMS
                    stripeTokenHandler(result.token);
                }
            });
        }
    });

    // 7. NEW: Append the token to the form and submit
    function stripeTokenHandler(token) {
        // Create a hidden input to store the token ID
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form natively to CouchCMS
        form.submit();
    }
</script>
<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>