<?php require_once( '../couch/cms.php' ); ?>
    <cms:template title='Login' hidden='1' />

    <!-- now the real work -->
    <cms:if k_logged_in >

        <!-- this 'login' template also handles 'logout' requests. Check if this is so -->
        <cms:set action="<cms:gpc 'act' method='get'/>" />
    
        <cms:if action='logout' >
            <cms:process_logout />
        <cms:else />  
            <!-- what is an already logged-in member doing on the login page? Send back to homepage. -->
            <cms:redirect k_site_link />
        </cms:if>
    
    <cms:else />
        
        <!-- show the login form -->
        <h1>Login</h1>
        
        <cms:form method='post' anchor='0'>
            <cms:if k_success >
                <!-- 
                    The 'process_login' tag below expects fields named 
                    'k_user_name', 'k_user_pwd' and (optionally) 'k_user_remember', 'k_cookie_test'
                    in the form
                -->
                <cms:process_login />
                
            </cms:if>
            
            <cms:if k_error >
                <h3><font color='red'><cms:show k_error /></font></h3>
            </cms:if>
            
            
            Username: <br/>
            <cms:input type='text' name='k_user_name' /> <br/>
            
            Password: <br />
            <cms:input type='password' name='k_user_pwd' /> <br/>
            
            <!-- if 'remember me' function is not required, the following checkbox can be omitted -->
            <cms:input type='checkbox' name='k_user_remember' opt_values='Remember me=1' /> <br/>
            
            <input type="hidden" name="k_cookie_test" value="1" />
            
            <input type="submit" value="Login" name="submit"/>
        </cms:form>
        
        <!-- optionally display links for 'Create account' and 'Lost password' -->
        <br />
        <cms:if k_user_registration_template >
            Not signed up yet? <a href="<cms:link k_user_registration_template />" />Create an account here.</a> <br>
        </cms:if>
        <cms:if k_user_lost_password_template >
            Forgot your password? <a href="<cms:link k_user_lost_password_template />" />Click here.</a> <br>
        </cms:if>
        
        
    </cms:if>
<?php COUCH::invoke(); ?>