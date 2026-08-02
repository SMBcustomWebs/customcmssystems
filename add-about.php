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
    <title><cms:if edit_id>Edit Employee<cms:else />Add New Employee</cms:if></title>
    
    <!-- Assuming standard Bootstrap 5 for layout formatting -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- COUCH NATIVE ASSETS FOR FRONTEND FORMS -->
    <link href="<cms:show k_system_theme_link />includes/admin/main.css?v=<cms:show k_cms_build />" rel="stylesheet"/>
    <link href="<cms:show k_system_theme_link />includes/magnific-popup/magnific-popup.css?v=<cms:show k_cms_build />" rel="stylesheet"/>

    <script src="<cms:show k_admin_link />includes/jquery-3.x.min.js?v=<cms:show k_cms_build />"></script>
    <script src="<cms:show k_system_theme_link />includes/admin/main.js?v=<cms:show k_cms_build />"></script>
    <script src="<cms:show k_system_theme_link />includes/bootstrap.min.js?v=<cms:show k_cms_build />"></script>
    <script src="<cms:show k_system_theme_link />includes/magnific-popup/magnific-popup.min.js?v=<cms:show k_cms_build />"></script>
    <script src="<cms:show k_admin_link />includes/ckeditor/ckeditor.js"></script>
    <style>
        /* MOBILE IMAGE UPLOAD OVERFLOW FIX */
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
                    name='about_frm'
                    masterpage='about.php' 
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
                                <div class="alert alert-success fw-bold">Employee saved successfully!</div>
                            <cms:else />
                                <!-- We just created a new profile. Catch the ID and redirect to Edit Mode -->
                                <cms:redirect url="<cms:link 'add-about.php' />?pid=<cms:show k_last_insert_id />&created=1" />
                            </cms:if>
                        </cms:if>
                    </cms:if>

                    <!-- Catch the redirect parameter to show a special welcome message -->
                    <cms:if "<cms:gpc 'created' method='get' />">
                        <div class="alert alert-success fw-bold border-success">
                            <i class="fas fa-check-circle me-2"></i> Employee profile created successfully!
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

                    <!-- ZONE 1: EMPLOYEE IDENTITY -->
                    <div class="form-zone">
                        <div class="zone-header">
                            <h4>1. Employee Identity</h4>
                            <p>Set the name, department (folder), and positional titles.</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Employee Name (Title) *</label>
                                <div class="form-text mb-2">The public-facing name of the team member.</div>
                                <cms:input type="bound" name="k_page_title" class="form-control" required='1' />
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Department (Folder)</label>
                                <div class="form-text mb-2">Select which department this employee belongs in.</div>
                                <cms:input type="bound" name="k_page_folder_id" class="form-select" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">Position Full Title</label>
                                <div class="form-text mb-2">e.g., Chief Executive Officer</div>
                                <cms:input type="bound" name="pos" class="form-control" />
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">Position Abbreviation</label>
                                <div class="form-text mb-2">e.g., CEO</div>
                                <cms:input type="bound" name="pab" class="form-control" />
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">Start Date</label>
                                <div class="form-text mb-2">e.g., June 2025</div>
                                <cms:input type="bound" name="psd" class="form-control" />
                            </div>
                        </div>
                    </div>

                    <!-- ZONE 2: BIOGRAPHY & MEDIA -->
                    <div class="form-zone">
                        <div class="zone-header">
                            <h4>2. Biography & Media</h4>
                            <p>Provide the team member's bio and profile picture.</p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Biography</label>
                            <div class="form-text mb-2">Format the bio as necessary.</div>
                            <cms:input type="bound" name="desc" />
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Profile Image</label>
                                <div class="form-text mb-2">1000x1000 or similar 1:1 (square) ratio.</div>
                                <cms:input type="bound" name="img" />
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Image Alt Text</label>
                                <div class="form-text mb-2">For screen readers and SEO (e.g., "Portrait of John Doe").</div>
                                <cms:input type="bound" name="img_alt" class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm fw-bold">
                            <cms:if edit_id>Save Changes<cms:else />Save Employee</cms:if>
                        </button>
                    </div>

                </cms:form>
                
                <cms:embed 'kcfinder_button_fix.htm' />
                
            </div>
        </div>
    </div>

    <!-- MANUAL KCFINDER OVERRIDE SCRIPT -->
    <script type="text/javascript">
        //<![CDATA[
        <cms:admin_js />
        //]]>
    </script>

</body>
</html>
<?php COUCH::invoke(); ?>