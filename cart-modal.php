<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Cart Modal Fragment" parent='_donottouch_' hidden='1' />
<!-- SECURITY LOCKDOWN: Only allow POST requests, unless user is Super Admin.
     'k_method' is NOT a Couch variable - it is set nowhere in core or any
     addon, so the old test read as ('' ne 'POST'), which is ALWAYS true.
     That silently locked this fragment to access level 10 for every request
     method, which is why the cart sidebar returned the homepage for normal
     customers. $_SERVER['REQUEST_METHOD'] is what core itself uses
     (header.php:79). -->
<cms:php>
    global $CTX;
    $CTX->set( 'ccs_req_method', $_SERVER['REQUEST_METHOD'], 'global' );
</cms:php>
<cms:if ccs_req_method ne 'POST'>
    <cms:if k_user_access_level lt '10'>
        <cms:redirect k_site_link />
    </cms:if>
</cms:if>
<cms:no_cache />

<cms:if "<cms:pp_count_items />" >

    <!-- 1. Initialize Error Tracking Variables -->
    <cms:set cart_has_error='0' scope='global' />
    <cms:set cart_error_msg='' scope='global' />

    <!-- 2. First Pass: Safely group quantities by product ID using native PHP array -->
    <cms:php>
        global $my_tallies;
        $my_tallies = array();
    </cms:php>
    
    <cms:pp_cart_items>
        <cms:php>
            global $CTX, $my_tallies;
            $pid = $CTX->get('id'); // Using the exact 'id' from the cart loop
            $qty = $CTX->get('quantity');
            if(!isset($my_tallies[$pid])) { $my_tallies[$pid] = 0; }
            $my_tallies[$pid] += $qty;
        </cms:php>
    </cms:pp_cart_items>

    <!-- 3. Second Pass: Compare the grouped tallies against the database -->
    <cms:pp_cart_items>
        <!-- Reach back to the DB using the verified 'id' -->
        <cms:pages masterpage='product.php' id="<cms:show id />" limit='1'>
            <cms:if track_inventory && in_stock gt '0'>
                
                <!-- Retrieve the grouped math from the PHP array -->
                <cms:php>
                    global $CTX, $my_tallies;
                    $pid = $CTX->get('id');
                    $CTX->set('total_requested', $my_tallies[$pid], 'global');
                </cms:php>
                
                <cms:if total_requested gt in_stock>
                    <cms:set cart_has_error='1' scope='global' />
                    <!-- Slightly shorter text to fit the narrow sidebar -->
                    <cms:set cart_error_msg="You requested <cms:show total_requested /> of '<cms:show k_page_title />', but only <cms:show in_stock /> are available." scope='global' />
                </cms:if>
                
            </cms:if>
        </cms:pages>
    </cms:pp_cart_items>


    <cms:pp_cart_form class="d-flex flex-column h-100 m-0">
        <!-- Scrollable Items Area -->
        <div class="flex-grow-1 overflow-auto p-3">
            <cms:pp_cart_items>
                <div class="row border-bottom py-3 mx-0">
                    
                    <!-- Image -->
                    <div class="col-4 px-1"> 
                        <cms:if itm_thumb>
                            <img class="img-fluid rounded" src="<cms:show itm_thumb />" alt="<cms:show title />" />
                        <cms:else />
                            <img class="img-fluid rounded" src="<cms:show k_admin_link />uploads/image/noimg.png" alt="No Image" />
                        </cms:if>
                    </div>
                    
                    <!-- Details & Quantity -->
                    <div class="col-8 px-2">
                        <a href="<cms:show link />">
                            <p class="mb-1 fw-bold"><cms:show title /></p>
                        </a>
                        
                        <cms:pp_selected_options startcount='1'>
                            <div class="mb-0 text-muted"><cms:show option_name />: <cms:show option_value /></div>
                        </cms:pp_selected_options>
                        
                        <!-- Reach back to the database using verified 'id' -->
                        <cms:pages masterpage='product.php' id="<cms:show id />" limit='1'>
                            <cms:set cart_track_inv=track_inventory scope='parent' />
                            <cms:set cart_in_stock=in_stock scope='parent' />
                        </cms:pages>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div style="width: 60px;">
                                <!-- Inject the max attribute -->
                                <input type="number" class="form-control form-control-sm px-1 py-0" name="qty[<cms:show line_id />]" value="<cms:show quantity />" min="1" step="1" <cms:if cart_track_inv && cart_in_stock gt '0'>max="<cms:show cart_in_stock />"</cms:if>>
                            </div>
                            <div class="text-success fw-bold">US $<cms:number_format line_total /></div>
                        </div>

                        <!-- Optional UX: Show max available under the box -->
                        <cms:if cart_track_inv && cart_in_stock gt '0'>
                            <small class="mt-1 d-block text-warning"><cms:show cart_in_stock /> max available</small>
                        </cms:if>
                        
                        <div class="mt-1 text-end">
                            <a class="cart-remove text-danger text-decoration-none" href="<cms:pp_remove_item_link />">
                                <i class="fas fa-trash-alt"></i> Remove
                            </a>
                        </div>
                    </div>
                    
                </div>
            </cms:pp_cart_items>
        </div>
        
        <!-- Sticky Bottom Totals & Actions -->
        <div class="p-3 border-top mt-auto bg-light">
            <div class="d-flex justify-content-between mb-3">
                <span class="fw-bold">Subtotal:</span>
                <span class="text-success fw-bold">$<cms:number_format "<cms:pp_sub_total />" /></span>
            </div>
            
            <button type="submit" class="btn btn-outline-secondary btn-sm w-100 mb-2 cart-update">Update Quantities</button>
            <a href="<cms:link 'cart.php' />" class="btn btn-secondary btn-sm w-100 mb-2">View Full Cart</a>
            
            <cms:if cart_has_error>
                <!-- Show the combined error message and hide the checkout button -->
                <div class="alert alert-danger p-2 mb-0" role="alert">
                    <i class="fas fa-exclamation-triangle me-1"></i> <cms:show cart_error_msg />
                </div>
                <button type="button" class="btn btn-secondary w-100 mt-2" disabled>
                    <i class="fas fa-lock me-2"></i> Checkout Disabled
                </button>
            <cms:else />
                <!-- Normal Checkout Button -->
                <a href="<cms:pp_checkout_link />" class="btn btn-danger w-100 mt-3">
                    <i class="fas fa-lock me-2"></i> Purchase / Checkout
                </a>
            </cms:if>
            
        </div>
    </cms:pp_cart_form>

<cms:else />
    <!-- Empty State -->
    <div class="p-4 mt-5 text-center">
        <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
        <h5>Your cart is empty.</h5>
        <button type="button" class="btn btn-primary mt-3" data-bs-dismiss="offcanvas">Continue Shopping</button>
    </div>
</cms:if>
<span id="ajax-cart-count" class="d-none"><cms:pp_count_items /></span>
<?php COUCH::invoke(); ?>