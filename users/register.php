<?php require_once( '../couch/cms.php' ); ?>
    <cms:template title='Registration' hidden='1' />

    <cms:if k_logged_in >
        <!-- what is an already logged-in member doing on this page? Send back to homepage. -->
        <cms:redirect k_site_link />
    </cms:if>
    
    <!-- are there any success messages to show from previous actions? -->
    <cms:set success_msg="<cms:get_flash 'success_msg' />" />
    <cms:if success_msg >
        <div class="notice">
            <cms:if success_msg='1' >
                Your account has been created successfully and we have sent you an email.<br />
                Please click the verification link within that mail to activate your account.
            <cms:else />
                Activation was successful! You can now log in!<br />
                <a href="<cms:login_link redirect=k_site_link />">Login</a>
            </cms:if>
        </div>
    <cms:else />
        
        <!-- now the real work -->
        <cms:set action="<cms:gpc 'act' method='get'/>" />
        
        <!-- is the visitor here by clicking the account-activation link we emailed? -->
        <cms:if action='activate' >
            <h1>Activate account</h1>
        
            <cms:process_activation />
            
            <cms:if k_success >
                 <cms:set_flash name='success_msg' value='2' />
                 <cms:redirect k_page_link />          
            <cms:else />
                <cms:show k_error />
            </cms:if>
        
        <cms:else />
        
            <!-- show the registration form -->
            <style>
                form{ width:200px; }
            </style>
            
            <h1>Create an account</h1>

            <cms:form 
                masterpage=k_user_template 
                mode='create'
                enctype='multipart/form-data'
                method='post'
                anchor='0'
                >

                <cms:if k_success >        

                    <cms:check_spam email=frm_extended_user_email />            

                    <cms:db_persist_form 
                        _invalidate_cache='0'
                        k_page_name = "<cms:random_name />"
                        k_publish_date = '0000-00-00 00:00:00'
                    />                    

                    <cms:if k_success >
                        
                        <cms:send_mail from="<cms:php>echo K_EMAIL_FROM;</cms:php>" to=frm_extended_user_email subject='New Account Confirmation' debug='1'>
                            Please click the following link to activate your account:
                            <cms:activation_link   frm_extended_user_email   />

                            Thanks,
                            Website Name
                        </cms:send_mail>                        
                                                
                        <cms:set_flash name='success_msg' value='1' />
                        <cms:redirect k_page_link />
                    </cms:if> 
                </cms:if>

                <cms:if k_error >
                    <font color='red'><cms:each k_error ><cms:show item /><br /></cms:each></font>
                </cms:if>        

                DisplayName:<br />
                <cms:input name='k_page_title' type='bound' /><br />
                
                Email Address:<br />
                <cms:input name='extended_user_email' type='bound' /><br />
                    
                Password:<br />
                <cms:input name='extended_user_password' type='bound' /><br />
                
                Repeat Password:<br />
                <cms:input name='extended_user_password_repeat' type='bound' /><br />       
               
                <input type="submit" name="submit" value="Create account"/>       

            </cms:form>
            
        </cms:if>
    </cms:if>    
    
    
<?php COUCH::invoke(); ?>