<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Cart" parent='_site_' icon='home' order="80" >
	
</cms:template>

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
                <h1 class="pt-6 pb-4">Your Shopping Cart</h1>
            </div>
        </div>
        
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
                            <cms:set cart_error_msg="You requested <cms:show total_requested /> units of '<cms:show k_page_title />', but we only have <cms:show in_stock /> available. Please reduce your quantities." scope='global' />
                        </cms:if>
                        
                    </cms:if>
                </cms:pages>
            </cms:pp_cart_items>

            <cms:pp_cart_form>
                <div class="row">
                    
                    <!-- CART ITEMS COLUMN -->
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-md-6 col-lg-12">
                                
                                <cms:pp_cart_items>
                                    <div class="row border py-3 mb-3 mx-0">                                   
                                        
                                        <!-- Dynamic Image with Fallback -->
                                        <div class="col-lg-3"> 
                                            <cms:if itm_thumb>
                                                <img class="img-fluid mx-auto d-block mb-3 mb-lg-0" style="max-width: 160px;" src="<cms:show itm_thumb />" alt="<cms:show title />" />
                                            <cms:else />
                                                <img class="img-fluid mx-auto d-block mb-3 mb-lg-0" style="max-width: 160px;" src="<cms:show k_admin_link />uploads/image/noimg.png" alt="Image Not Available" />
                                            </cms:if>
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <h5>Details</h5>
                                            <hr />
                                            <a href="<cms:show link />">
                                                <p class="fw-bold"><cms:show title /></p>
                                            </a>
                                            
                                            <!-- Dynamic Variants Loop -->
                                            <cms:pp_selected_options startcount='1'>
                                                <div class="row text-muted">
                                                    <div class="col-3"> <span class="me-3 fw-bold"><cms:show option_name /> : </span></div>
                                                    <div class="col"><cms:show option_value /></div>
                                                </div>
                                            </cms:pp_selected_options>
                                            
                                            <!-- Reach back to the database using verified 'id' -->
                                            <cms:pages masterpage='product.php' id="<cms:show id />" limit='1'>
                                                <cms:set cart_track_inv=track_inventory scope='parent' />
                                                <cms:set cart_in_stock=in_stock scope='parent' />
                                            </cms:pages>

                                            <div class="row align-items-center mt-3">
                                                <label class="col-3 mb-0 fw-bold" for="qty<cms:show line_id />"> Quantity :</label>
                                                
                                                <div class="col-3">
                                                    <!-- Inject the max attribute -->
                                                    <input type="number" class="form-control form-control-sm" id="qty<cms:show line_id />" name="qty[<cms:show line_id />]" value="<cms:show quantity />" min="1" step="1" <cms:if cart_track_inv && cart_in_stock gt '0'>max="<cms:show cart_in_stock />"</cms:if> >
                                                    
                                                    <!-- Optional UX: Show max available under the box -->
                                                    <cms:if cart_track_inv && cart_in_stock gt '0'>
                                                        <small class="mt-1 d-block text-warning"><cms:show cart_in_stock /> max available</small>
                                                    </cms:if>
                                                </div>
                                                
                                                <div class="col">
                                                    <p class="mb-0 text-muted">US $<cms:number_format price /><span> / base price</span></p>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="col-lg-3">
                                            <h5>Line Total </h5>
                                            <hr />
                                            <p class="mb-0 text-success fw-bold">US $<cms:number_format line_total /></p>
                                            <a class="mt-2 d-inline-block text-danger text-decoration-none" href="<cms:pp_remove_item_link />"> <i class="fas fa-trash-alt me-1"></i>Remove</a>
                                        </div>
                                    </div>
                                </cms:pp_cart_items>
                                
                            </div>
                        </div>
                    </div>
                    
                    <!-- CART TOTALS & CHECKOUT COLUMN -->
                    <div class="col">
                        <div class="row">
                            <div class="col-12 border align-self-start p-3 bg-light">
                                <p class="my-3 fw-bold">You have <cms:pp_count_items /> item(s) in cart</p>
                                <hr />
                                
                                <div class="row">
                                    <div class="col-8">
                                        <p class="mb-0">Sub Total: </p>
                                    </div>
                                    <div class="col-4 text-end">
                                        <p class="mb-0">$<cms:number_format "<cms:pp_sub_total />" /> </p>
                                    </div>
                                    
                                    <cms:if "<cms:pp_shipping />">
                                        <div class="col-8 mt-2">
                                            <p class="mb-0">Shipping: </p>
                                        </div>
                                        <div class="col-4 text-end mt-2">
                                            <p class="mb-0">$<cms:number_format "<cms:pp_shipping />" /> </p>
                                        </div>
                                    </cms:if>

                                    <cms:if "<cms:pp_taxes />">
                                        <div class="col-8 mt-2">
                                            <p class="mb-0">Taxes: </p>
                                        </div>
                                        <div class="col-4 text-end mt-2">
                                            <p class="mb-0">$<cms:number_format "<cms:pp_taxes />" /> </p>
                                        </div>
                                    </cms:if>
                                    
                                    <div class="col-12">
                                        <hr class="my-2" />
                                    </div>
                                    
                                    <div class="col-7">
                                        <p class="mb-0 fw-bold text-success">Total: </p>
                                    </div>
                                    <div class="col-5 text-end">
                                        <p class="mb-0 fw-bold text-success">$<cms:number_format "<cms:pp_total />" /></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 px-0 mt-3"> 
                                <button type="submit" class="btn btn-secondary w-100 d-block"> <i class="fas fa-sync-alt me-2"></i>Update Quantities </button>
                            </div>
                            
                            <cms:if cart_has_error>
                                <!-- Show the combined error message and hide the checkout button -->
                                <div class="alert alert-danger text-center mb-0 mt-3" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i> <cms:show cart_error_msg />
                                </div>
                                <button type="button" class="btn btn-secondary w-100 mt-2 mb-2 mb-lg-4" disabled>
                                    <i class="fas fa-lock me-2"></i> Checkout Disabled
                                </button>
                            <cms:else />
                                <!-- Normal Checkout Button -->
                                <a href="<cms:pp_checkout_link />" class="btn btn-danger w-100 mt-3 mb-2 mb-lg-4">
                                    <i class="fas fa-lock me-2"></i> Purchase / Checkout
                                </a>
                            </cms:if>

                            <!-- Return to Shopping Button.
                                 Was 'products.php', which does not exist in this
                                 install - the products template is 'product.php'. -->
                            <a href="<cms:link 'product.php' />" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-arrow-left me-2"></i> Return to Shopping
                            </a>
                        </div>
                    </div>
                    
                </div>
            </cms:pp_cart_form>
        <cms:else />
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-secondary py-5 text-center">
                        <h4 class="mb-3"><i class="fas fa-shopping-cart fa-2x mb-3 text-muted"></i><br>Your cart is currently empty!</h4>
                        <a href="<cms:show k_site_link />" class="btn btn-primary">Return to Shop</a>
                    </div>
                </div>
            </div>
        </cms:if>
        
    </div>
</section>
<!-- <section> close ============================-->
<!-- ============================================-->

<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>