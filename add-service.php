<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />") >
    <!-- Security Passed: User is Admin and Inline Edit is ON -->
<cms:else />
    <!-- SECURITY LOCKDOWN: Silent redirect -->
    <cms:redirect link=k_site_link />
</cms:if>
<cms:set edit_id="<cms:gpc 'pid' method='get' />" />
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><cms:if edit_id>Edit Service<cms:else />Add New Service</cms:if></title>
    
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
                
                <div class="d-flex justify-content-between align-items-center mb-4">
					<h2 class="mb-0"><cms:if edit_id>Edit Employee<cms:else />Add New Employee</cms:if></h2>

					<div>
						<!-- Return to Site Button (Always visible) -->
						<a href="<cms:show k_site_link />" class="btn btn-outline-secondary shadow-sm <cms:if edit_id>me-2</cms:if>">
							<i class="fas fa-arrow-left me-1"></i> Return To Site
						</a>

						<!-- Add Another Employee Button (Only visible in edit mode) -->
						<cms:if edit_id>
							<a href="<cms:link 'add-about.php' />" class="btn btn-outline-primary fw-bold shadow-sm">
								<i class="fas fa-plus me-1"></i> Add Another Employee
							</a>
						</cms:if>
					</div>
				</div>


                <!-- THE MAIN DATABOUND FORM -->
                <cms:form 
                    name='service_frm'
                    masterpage='service.php' 
                    mode="<cms:if edit_id>edit<cms:else/>create</cms:if>"
                    page_id="<cms:if edit_id><cms:show edit_id /></cms:if>"
                    enctype='multipart/form-data'
                    method='post'
                    anchor='0'
                    class="<cms:get_flash 'form_display'/>"
                >
                    
                    <cms:if k_success>
                        <cms:db_persist_form 
                            _invalidate_cache='0' 
                            _auto_title='0'
                        />
                        <cms:if k_success>
                            <cms:if edit_id>
                                <!-- We are already editing, just show the success message -->
                                <div class="alert alert-success fw-bold">Service saved successfully!</div>
                            <cms:else />
                                <!-- We just created a new service. Catch the ID and redirect to Edit Mode -->
                                <cms:redirect url="<cms:link 'add-service.php' />?pid=<cms:show k_last_insert_id />&created=1" />
                            </cms:if>
                        </cms:if>
                    </cms:if>

                    <!-- Catch the redirect parameter to show a special welcome message -->
                    <cms:if "<cms:gpc 'created' method='get' />">
                        <div class="alert alert-success fw-bold border-success">
                            <i class="fas fa-check-circle me-2"></i> Service created successfully! Edit Service as needed. Add more images and Service specifications below. To add a new service, click button above. 
                        </div>
                    </cms:if>

                    <cms:if k_error>
                        <div class="alert alert-danger">
                            <b>Failed to save:</b>
                            <cms:each k_error>
                                <br>&bull; <cms:show item />
                            </cms:each>
                        </div>
                    </cms:if>


                    <!-- SYSTEM IDENTITY -->
                    <div class="form-zone">
                        <div class="zone-header">
                            <h4>System Identity</h4>
                            <p>Set the system name and category folder.</p>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Service Name (Title) *</label>
                            <div class="form-text mb-2">The public-facing name of the service.</div>
                            <cms:input type="bound" name="k_page_title" class="form-control" required='1' />
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Service Category (Folder)</label>
                            <div class="form-text mb-2">Select which section of the catalog this item belongs in.</div>
                            <cms:input type="bound" name="k_page_folder_id" class="form-select" />
                        </div>
                    </div>


                    <!-- ZONE 1: CORE IDENTITY -->
                    <div class="form-zone">
                        <div class="zone-header">
                            <h4>1. General Service Details</h4>
                            <p>Set the primary service identity. Enter the unique ID, general text description, and upload the main hero image below.</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Service SKU / ID</label>
                                <div class="form-text mb-2">Leave blank to use a system-generated ID (Letters and numbers only).</div>
                                <cms:input type="bound" name="itm_sku" class="form-control" />
                            </div>
                            
                            <!-- CONTROLLER: Track Inventory -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold d-block">Track Availability</label>
                                <div class="form-text mb-2">Check this box to strictly enforce booking/stock limits on this service.</div>
                                <div class="mt-2"><cms:input type="bound" name="track_inventory" /></div>
                            </div>
                            
                            <!-- TARGET CONDITIONAL FIELD (Hidden by default) -->
                            <div class="col-md-6 mb-4" id="in_stock_wrapper" style="display: none;">
                                <label class="form-label fw-bold">Availability Count</label>
                                <div class="form-text mb-2">Total number of available slots/items (Numbers only).</div>
                                <cms:input type="bound" name="in_stock" class="form-control" />
                            </div>
                        </div>

                        <div class="mb-4 mt-2">
                            <label class="form-label fw-bold">General Description</label>
                            <div class="form-text mb-2">Describe this service. (Plain text only to preserve template styling).</div>
                            <cms:input type="bound" name="itm_desc" class="form-control" style="height: 100px;" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Main Hero Image</label>
                            <div class="form-text mb-2">Main image max dimension 1000px. Thumbnails will auto-generate.</div>
                            <cms:input type="bound" name="itm_img_mn" />
                        </div>
                    </div>

                    
                    <!-- ZONE 3: E-COMMERCE & CART ENGINE -->
                    <div class="form-zone">
                        <div class="zone-header">
                            <h4>3. Cart Rules & Logistics (Advanced)</h4>
                            <p>Configure pricing logic, available service variants, and logistics data.</p>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Base Price (USD) *</label>
                                <div class="form-text mb-2">Amount in USD (correct up to 2 decimal points without the $ sign).</div>
                                <cms:input type="bound" name="pp_price" class="form-control" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Old Price</label>
                                <div class="form-text mb-2">Gets crossed out on page (optional).</div>
                                <cms:input type="bound" name="old_price" class="form-control" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Tax Class</label>
                                <div class="form-text mb-2">If not selected, the default global tax percent will be used.</div>
                                <cms:input type="bound" name="pp_tax_class" class="form-select" />
                            </div>
                        </div>

                        <!-- QTY TYPE DROPDOWN (New Controller) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Quantity Label (Cart Option)</label>
                            <div class="form-text mb-2">Select what the quantity box represents. Select "none" to hide the quantity box entirely.</div>
                            <cms:input type="bound" name="svc_qty_type" class="form-select" />
                        </div>
                        
                        <!-- QTY BASED PRICING (Target Field - Wrapped for JS toggle) -->
                        <div id="qty_pricing_wrapper" class="mb-4 bg-light p-3 border rounded">
                            <label class="form-label fw-bold text-primary">Quantity Based Pricing (Tiered Pricing)</label>
                            <div class="form-text mb-3">
                                If the base price varies based on the quantity purchased (useful for bulk hours/retainers), configure it here.<br>
                                &bull; <strong>Fixed:</strong> <code>[ 5=2 | 10=3 ]</code> reduces price by $2 for >5 units, and by $3 for >10 units.<br>
                                &bull; <strong>Percentage:</strong> <code>[ 5=2 | 10=3 ]%</code> reduces price by 2% for >5 units, and 3% for >10 units.
                            </div>
                            <cms:input type="bound" name="pp_discount_scale" class="form-control font-monospace" placeholder="e.g. [ 5=2 | 10=3 ]" />
                        </div>

                        <div class="mb-4 bg-light p-3 border rounded">
                            <label class="form-label fw-bold text-primary">Variants (Tiers, Options, Custom Requests)</label>
                            <div class="form-text mb-3">
                                Add each variant on a separate line. Use standard syntax or add price differences:<br>
                                &bull; <strong>Dropdown:</strong> <code>Tier[Pro | Basic=+3 | Elite=-2]</code><br>
                                &bull; <strong>Radio Buttons:</strong> Append an asterisk: <code>Speed[Standard | Expedited | Rush]*</code><br>
                                &bull; <strong>Text Input:</strong> <code>Your Request[*TEXT*=5]</code>
                            </div>
                            <cms:input type="bound" name="pp_options" class="form-control font-monospace" style="height: 120px;" placeholder="Tier[Pro | Basic=+3 | Elite=-2]" />
                        </div>

                        <div class="row bg-light p-3 border rounded mx-0">
                            <div class="col-md-5 mb-3 mb-md-0">
                                <label class="form-label fw-bold text-primary d-block">Requires Shipping</label>
                                <div class="form-text mb-2">Select 'No' if this is a digital or in-person service.</div>
                                <div class="mt-2"><cms:input type="bound" name="pp_requires_shipping" /></div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label fw-bold text-primary">Shipping / Travel Charge Scale</label>
                                <div class="form-text mb-2">
                                    Sliding scale based on order quantity. <br>
                                    Example: <code>[ 0=3 | 5=7 | 15=10 ]</code> = $3 for >0, $7 for >5, $10 for >15.
                                </div>
                                <cms:input type="bound" name="pp_shipping_scale" class="form-control font-monospace" />
                            </div>
                        </div>
                    </div>


                    <!-- ZONE 4: VISIBILITY & BADGES -->
                    <div class="form-zone">
                        <div class="zone-header">
                            <h4>4. Visibility & Placement</h4>
                            <p>Control where this service appears in site navigation and lists.</p>
                        </div>
                        <div class="d-flex flex-column gap-3 mt-3">
                            <div class="d-flex align-items-center">
                                <cms:input type="bound" name="feature" />
                                <span class="form-text ms-2 mt-0">Check to add to features list</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <cms:input type="bound" name="value" />
                                <span class="form-text ms-2 mt-0">Check to add to values list</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <cms:input type="bound" name="noshow" />
                                <span class="form-text ms-2 mt-0">Default: Will be shown on site menu</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm fw-bold">
                            <cms:if edit_id>Save Changes<cms:else />Save Service</cms:if>
                        </button>
                    </div>

                </cms:form>
                
                <cms:embed 'kcfinder_button_fix.htm' />
                
                <!-- ZONE 2: MEDIA & SPECS (Detached Popups for Mosaics) -->
                <cms:if edit_id>
                    <cms:pages masterpage='service.php' id=edit_id limit='1'>
                        <div class="form-zone border border-primary shadow-sm" style="border-width: 2px !important;">
                            <div class="zone-header" style="background-color: #cfe2ff; border-color: #9ec5fe; border-left-color: #0d6efd;">
                                <h4 style="color: #0a58ca;">2. Advanced Media & Specifications</h4>
                                <p style="color: #495057;">Manage the image gallery slider and build categorized specification accordions. (These save independently of the main form below).</p>
                            </div>
                            
                            <div class="d-flex flex-column gap-3 mt-4">
                                <div class="p-3 bg-white border rounded d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="d-block mb-1">Service Image Gallery</strong>
                                        <small class="text-muted">Upload additional images for the service gallery slider.</small>
                                    </div>
                                    <cms:popup_edit 'itm_sldrs_msc' link_text='Edit Gallery Images' />
                                </div>

                                <div class="p-3 bg-white border rounded d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="d-block mb-1">Specifications & Highlights</strong>
                                        <small class="text-muted">Create categorized accordion sections (e.g., Deliverables, Scope of Work, Requirements).</small>
                                    </div>
                                    <cms:popup_edit 'itm_specs_msc' link_text='Edit Specifications' />
                                </div>
                            </div>
                        </div>
                    </cms:pages>
                </cms:if>
            </div>
        </div>
    </div>


    <!-- RULE 3 & CONDITIONAL FIELDS VANILLA JS OVERRIDES -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            /* --- LOGIC 1: INVENTORY TOGGLE --- */
            const trackInvCheckboxes = document.querySelectorAll('input[name="f_track_inventory[]"]');
            const inStockWrapper = document.getElementById('in_stock_wrapper');

            function toggleInStock() {
                let isChecked = false;
                trackInvCheckboxes.forEach(function(cb) {
                    if (cb.value === '1' && cb.checked) {
                        isChecked = true;
                    }
                });
                if (isChecked) {
                    inStockWrapper.style.display = 'block';
                } else {
                    inStockWrapper.style.display = 'none';
                }
            }
            trackInvCheckboxes.forEach(function(cb) {
                cb.addEventListener('change', toggleInStock);
            });
            toggleInStock(); // init

            /* --- LOGIC 2: QUANTITY PRICING TOGGLE --- */
            // Target Couch's generated dropdown field name
            const qtyTypeSelect = document.querySelector('select[name="f_svc_qty_type"]');
            const qtyPricingWrapper = document.getElementById('qty_pricing_wrapper');

            function toggleQtyPricing() {
                if (qtyTypeSelect && qtyPricingWrapper) {
                    // If the dropdown value is 'none', hide the tiered pricing block entirely
                    if (qtyTypeSelect.value === 'none') {
                        qtyPricingWrapper.style.display = 'none';
                    } else {
                        qtyPricingWrapper.style.display = 'block';
                    }
                }
            }
            
            if (qtyTypeSelect) {
                qtyTypeSelect.addEventListener('change', toggleQtyPricing);
                toggleQtyPricing(); // init
            }
            
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