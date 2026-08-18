<?php require_once( '../ccs_dash/cms.php' ); ?>
<cms:template title="Login" hidden='1' />

<!-- 1. Catch the 'act=logout' parameter from the URL -->
<cms:set action="<cms:gpc 'act' method='get' />" />

<!-- 2. Listen for the Logout action -->
<cms:if action='logout' >
    <cms:ignore>
        process_logout normally redirects on its own: its default is
        redirect='2', meaning "use the querystring param named redirect",
        which users/logout.php supplies. The line below is only reached if
        that redirect does not happen.

        It used to point at "<site root>/login.php", which does not exist -
        this install's login template is users/login.php. That sent anyone
        who did reach it to a dead URL. Site root is the safe fallback.
    </cms:ignore>
    <cms:process_logout />
    <cms:redirect url="<cms:show k_site_link />" />
</cms:if>

<!-- 3. If already logged in, honour where they were headed, then fall back -->
<cms:if k_logged_in >
    <cms:ignore>
        alter_login_link (extended-users.php:734-742) already appends
        redirect=REQUEST_URI when it builds a login link for an anonymous
        visitor, so arriving here with ?redirect= set is the normal case -
        it is how "return me to the page I was on" works. Honour it before
        falling back to a role-based destination.
    </cms:ignore>
    <cms:set want="<cms:gpc 'redirect' method='get' />" />

    <cms:if want>
        <cms:redirect url=want />
    <cms:else_if k_user_access_level ge '7' />
        <!-- Admins go to the dashboard -->
        <cms:redirect url="<cms:show k_admin_link />" />
    <cms:else />
        <!-- Regular users go to the site root -->
        <cms:redirect url="<cms:show k_site_link />" />
    </cms:if>
</cms:if>

<cms:embed 'pb_mods/pg_frame/head.htm' />
<cms:embed 'pb_mods/pg_frame/main-cap.htm' />   
<cms:embed 'pb_mods/pg_frame/nav/nav_emb.htm' />

<section class="pt-0">
    <div class="container pt-6 pb-6">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                
                <h2 class="text-center mb-4">Log In</h2>
                
                <div class="p-4 bg-light border rounded">
                    
                    <!-- 3. The Login Form -->
                    <cms:form method="post" anchor='0'>
                        
                        <cms:if k_success >
                            <cms:process_login />
                        </cms:if>
                        
                        <cms:if k_error >
                            <div class="alert alert-danger">
                                <cms:each k_error >
                                    <cms:show item /><br />
                                </cms:each>
                            </div>
                        </cms:if>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <!-- Note: Extended users log in with email, but Couch still expects the input name to be k_user_name -->
                            <cms:input type='text' name='k_user_name' class='form-control' required='1' />
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <cms:input type='password' name='k_user_pwd' class='form-control' required='1' />
                        </div>
                        
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <!-- KK's recommended 'Remember Me' field -->
                                <cms:input type='checkbox' name='k_user_remember' opt_values='Remember me=1' />
                            </div>
                            <a href="<cms:show k_site_link />users/lost-password.php" class="text-decoration-none">Forgot Password?</a>
                        </div>
                        
                        <!-- KK's recommended Cookie Test hidden field -->
                        <input type="hidden" name="k_cookie_test" value="1" />
                        
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Log In</button>
                        
                    </cms:form>
                    
                    <div class="mt-4 text-center">
                        <span class="text-muted">Don't have an account?</span> 
                        <a href="<cms:show k_site_link />users/register.php" class="text-decoration-none fw-bold">Register here</a>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
</section>

<cms:embed 'pb_mods/pg_frame/footer/ftr_emb.htm' />
<cms:embed 'pb_mods/pg_frame/tail.htm' />
<?php COUCH::invoke(); ?>