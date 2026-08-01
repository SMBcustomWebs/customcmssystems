<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />") >
    <!-- Security Passed: User is Admin and Inline Edit is ON -->
<cms:else />
    <!-- SECURITY LOCKDOWN: Silent redirect -->
    <cms:redirect link=k_site_link />
</cms:if>
<cms:set edit_id="<cms:gpc 'pid' method='get' />" />>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><cms:if edit_id>Edit Product<cms:else />Add New Product</cms:if></title>
    
    <!-- Assuming standard Bootstrap 5 for layout formatting -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- COUCH NATIVE ASSETS FOR FRONTEND FORMS (Required for Magnific Popup and styling) -->
	<link href="<cms:show k_system_theme_link />includes/admin/main.css?v=<cms:show k_cms_build />" rel="stylesheet"/>
	<link href="<cms:show k_system_theme_link />includes/magnific-popup/magnific-popup.css?v=<cms:show k_cms_build />" rel="stylesheet"/>

	<script src="<cms:show k_admin_link />includes/jquery-3.x.min.js?v=<cms:show k_cms_build />"></script>
	<script src="<cms:show k_system_theme_link />includes/admin/main.js?v=<cms:show k_cms_build />"></script>
	<script src="<cms:show k_system_theme_link />includes/bootstrap.min.js?v=<cms:show k_cms_build />"></script>
	<script src="<cms:show k_system_theme_link />includes/magnific-popup/magnific-popup.min.js?v=<cms:show k_cms_build />"></script>
	<!-- Required for Drag-and-Drop Sorting -->
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

	<!-- 1. TableGear Engine (Required to draw the repeatable grids) -->
	<link href="<cms:show k_admin_link />addons/repeatable/tablegear/tablegear.css" rel="stylesheet" type="text/css" />
	<script src="<cms:show k_admin_link />addons/repeatable/tablegear/tablegear.js"></script>

	<!-- 2. Mosaic Addon (Required for the gallery rows) -->
	<script src="<cms:show k_admin_link />addons/mosaic/theme/mosaic.js"></script>
	<!-- Required for Couch Popup Edit Buttons (TinyBox) -->
	<link href="<cms:show k_admin_link />addons/inline/tinybox2/style.css" rel="stylesheet" type="text/css" />
	<script src="<cms:show k_admin_link />addons/inline/tinybox2/tinybox.js"></script>
	
	
    <style>
        /* RULE 2: MOBILE IMAGE UPLOAD OVERFLOW FIX */
        @media (max-width: 767px) {
            .k_element, span[id^="k_element_"], .input-group {
                display: flex !important;
                flex-direction: column !important;
                width: 100% !important;
            }
            input.form-control[type="text"], .input-group > .form-control {
                display: block !important;
                width: 100% !important;
                position: static !important;
                margin-bottom: 10px !important;
                border-radius: 4px !important;
            }
            input[type="button"][value*="Browse"], input.k_button, button.popup-iframe, 
            .input-group-append, .input-group-btn, .input-group > .btn, .input-group > input[type="button"] {
                display: block !important;
                width: 100% !important;
                position: static !important;
                transform: none !important;
                margin: 0 !important;
                padding: 10px !important;
                float: none !important;
                box-sizing: border-box !important;
                border-radius: 4px !important;
                text-align: center;
            }
        }
        /* SVG ICON FIX: Shrink ALL CouchCMS generated SVGs and fix click targeting */
		.k_element svg, 
		.k_button svg, 
		button.popup-iframe svg, 
		.input-group-btn > .btn svg, 
		.input-group > .btn svg {
			width: 16px !important;
			height: 16px !important;
			pointer-events: none !important;
			vertical-align: middle !important;
		}
        /* Form styling to match your backend aesthetic */
        .form-zone { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .zone-header { background: #fff3cd; border: 1px solid #ffe69c; border-left: 4px solid #f6c23e; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .zone-header h4 { margin: 0 0 5px 0; color: #1cc88a; font-family: sans-serif; font-size: 1.1rem; font-weight: bold; }
        .zone-header p { margin: 0; color: #5a5c69; font-size: 13px; font-family: sans-serif; }
		
		/* Style Couch Popup Edit links as Bootstrap Buttons */
		a.k_popup_edit {
			display: inline-block !important;
			padding: 8px 16px !important;
			background-color: #0d6efd !important; /* Bootstrap Primary Blue */
			color: #ffffff !important;
			text-decoration: none !important;
			border-radius: 4px !important;
			font-weight: bold !important;
			font-size: 14px !important;
			border: none !important;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
		}

		a.k_popup_edit:hover {
			background-color: #0b5ed7 !important;
			color: #ffffff !important;
		}
		
		
    </style>
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <h2><cms:if edit_id>Edit Product<cms:else />Add New Product</cms:if></h2>
				<cms:if edit_id>
					<cms:pages masterpage='product.php' id=edit_id limit='1'>

						<div class="alert alert-info mb-4">
							<strong>Extra Product Images:</strong> Gallery
							<div class="mt-3 d-flex gap-3">
								<!-- Gallery Popup Button -->
								<cms:popup_edit 'itm_sldrs_msc' link_text='Edit Gallery Images' />

							</div>
						</div>

						<div class="alert alert-info mb-4">
							<strong>Advanced  Specs:</strong><p>Each Heading is it's own subject matter for description. A good place for a return policy, safety labels, product highlights, special information</p>
							<div class="mt-3 d-flex gap-3">


								<!-- Specs Popup Button -->
								<cms:popup_edit 'itm_specs_msc' link_text='Edit Specifications' />
							</div>
						</div>

					</cms:pages>
				</cms:if>
                <!-- THE DATABOUND FORM -->
                <cms:form 
					name='product_frm'
					masterpage='product.php' 
					mode="<cms:if edit_id>edit<cms:else/>create</cms:if>"
					page_id="<cms:if edit_id><cms:show edit_id /></cms:if>"
					enctype='multipart/form-data'
					method='post'
					anchor='0'
					class="<cms:get_flash 'form_display'/>"
				>
                    
                    <cms:if k_success>
                        <!-- Title is required by Couch, we auto-generate it from the SKU or ID if needed, but let's just prompt for standard k_page_title below -->
                        <cms:db_persist_form 
                            _invalidate_cache='0' 
                            _auto_title='0'
                        />
                        <cms:if k_success>
                            <div class="alert alert-success">Product created successfully!</div>
                            <!-- Optional redirect to the new page: <cms:redirect url=k_redirect_link /> -->
                        </cms:if>
                    </cms:if>

                    <cms:if k_error>
                        <div class="alert alert-danger">
                            <b>Failed to save:</b>
                            <cms:each k_error>
                                <br><cms:show item />
                            </cms:each>
                        </div>
                    </cms:if>

                    <!-- SYSTEM IDENTITY -->
                    <div class="form-zone">
                        <div class="zone-header">
                            <h4>System Identity</h4>
                            <p>Set the system name and category folder.</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Name (Title) *</label>
                            <cms:input type="bound" name="k_page_title" class="form-control" required='1' />
                        </div>
                        <!-- RULE 4: DYNAMIC FOLDER DROPDOWN -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Category (Folder)</label>
                            <cms:input type="bound" name="k_page_folder_id" class="form-select" />
                        </div>
                    </div>

                    <!-- ZONE 1: CORE IDENTITY -->
                    <div class="form-zone">
                        <div class="zone-header">
                            <h4>1. General Product Details</h4>
                            <p>Set the primary product identity. Enter the unique SKU, general text description, and upload the main hero image below.</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Product SKU</label>
                                <cms:input type="bound" name="itm_sku" class="form-control" />
                            </div>
                            
                            <!-- CONTROLLER: Track Inventory -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold d-block">Track Inventory</label>
                                <cms:input type="bound" name="track_inventory" />
                            </div>
                            
                            <!-- RULE 3: TARGET CONDITIONAL FIELD (Hidden by default) -->
                            <div class="col-md-6 mb-3" id="in_stock_wrapper" style="display: none;">
                                <label class="form-label fw-bold">Inventory Count</label>
                                <cms:input type="bound" name="in_stock" class="form-control" />
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label fw-bold">General Description</label>
                            <cms:input type="bound" name="itm_desc" class="form-control" style="height: 100px;" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Main Hero Image</label>
                            <cms:input type="bound" name="itm_img_mn" />
                        </div>
                    </div>

                    
                    <!-- ZONE 3: E-COMMERCE & CART ENGINE -->
                    <div class="form-zone">
                        <div class="zone-header">
                            <h4>3. Cart Rules & Logistics (Advanced)</h4>
                            <p>Configure pricing logic, available product variants, and shipping data.</p>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Base Price (USD) *</label>
                                <cms:input type="bound" name="pp_price" class="form-control" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Old Price</label>
                                <cms:input type="bound" name="old_price" class="form-control" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Tax Class</label>
                                <cms:input type="bound" name="pp_tax_class" class="form-select" />
                            </div>
                        </div>
						
                        <div class="mb-4">
                            <label class="form-label fw-bold">Add Qty Pricing Here</label>
                            <cms:input type="bound" name="pp_discount_scale" class="form-control" />
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Variants (Colors, Sizes, etc)</label>
                            <cms:input type="bound" name="pp_options" class="form-control" style="font-family: monospace;" />
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold d-block">Requires Shipping</label>
                                <cms:input type="bound" name="pp_requires_shipping" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Set Shipping Charge Scale</label>
                                <cms:input type="bound" name="pp_shipping_scale" class="form-control" />
                            </div>
                        </div>
                    </div>

                    <!-- ZONE 4: VISIBILITY & BADGES -->
                    <div class="form-zone">
                        <div class="zone-header">
                            <h4>4. Visibility & Placement</h4>
                        </div>
                        <div class="d-flex gap-4">
                            <div><cms:input type="bound" name="feature" /></div>
                            <div><cms:input type="bound" name="value" /></div>
                            <div><cms:input type="bound" name="noshow" /></div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg px-5 mb-5">Save Product</button>

                </cms:form>
				<cms:embed 'kcfinder_button_fix.htm' />
				<!-- uneditable mosaics -->
				
            </div>
        </div>
    </div>

    <!-- jQuery Required for KCFinder Fix and general Couch integrations -->
    

    <!-- RULE 3: CONDITIONAL FIELDS VANILLA JS OVERRIDE -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Target Couch's generated input names (prefixed with f_)
            // Couch renders checkboxes as arrays, so the name is 'f_track_inventory[]'
            const trackInvCheckboxes = document.querySelectorAll('input[name="f_track_inventory[]"]');
            const inStockWrapper = document.getElementById('in_stock_wrapper');

            function toggleInStock() {
                let isChecked = false;
                trackInvCheckboxes.forEach(function(cb) {
                    // Check if the specific '1' value is checked
                    if (cb.value === '1' && cb.checked) {
                        isChecked = true;
                    }
                });

                if (isChecked) {
                    inStockWrapper.style.display = 'block';
                } else {
                    inStockWrapper.style.display = 'none';
                    // Optional: Clear the input value when hidden so rogue data isn't saved
                    // document.querySelector('input[name="f_in_stock"]').value = '';
                }
            }

            // Attach event listener to all checkboxes in the array
            trackInvCheckboxes.forEach(function(cb) {
                cb.addEventListener('change', toggleInStock);
            });

            // Run once on load to set initial state
            toggleInStock();
        });
    </script>

    <!-- RULE 7: MANUAL KCFINDER OVERRIDE SCRIPT -->
    <script type="text/javascript">
        //<![CDATA[
        <cms:admin_js />
        //]]>
    </script>


</body>
</html>
<?php COUCH::invoke(); ?>