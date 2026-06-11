<?php require_once( '../couch/cms.php' ); ?>
    <cms:template title='User Profile' hidden='1' />
    
    <!-- this is secured page. login first to access it -->
    <cms:if k_logged_out >
        <cms:redirect "<cms:login_link />" />
    </cms:if>
    
    <!-- someone who manages to reach here is certainly a logged-in user -->
    <h2>Edit profile</h2>
    
    <!-- are there any success messages to show from previous save? -->
    <cms:set success_msg="<cms:get_flash 'success_msg' />" />
    <cms:if success_msg >
        <h4>Profile updated.</h4>
    </cms:if>

    <!-- show the edit form -->
    <style>
        form{ width:400px; }
    </style>
            
    <!-- this ia regular databound-form -->
    <cms:form 
        masterpage=k_user_template 
        mode='edit'
        page_id=k_user_id
        enctype="multipart/form-data"
        method='post'
        anchor='0'
        >
        
        <cms:if k_success >
            <cms:db_persist_form />

            <cms:if k_success >
                <cms:set_flash name='success_msg' value='1' />
                <cms:redirect k_page_link /> 
            </cms:if>
        </cms:if>  
        
        <cms:if k_error >
            <font color='red'><cms:each k_error ><cms:show item /><br /></cms:each></font>
        </cms:if>
        
        
        DisplayName:<br />
        <cms:input name='k_page_title' type='bound' /><br />

        E-mail:<br />
        <cms:input name='extended_user_email' type='bound' /><br />

        New Password: (If you would like to change the password type a new one. Otherwise leave this blank.)<br />
        <cms:input name='extended_user_password' type='bound' /><br />

        Repeat Password:<br />
        <cms:input name='extended_user_password_repeat' type='bound' /><br />  

        <input type="submit" name="submit" value="Save"/>   
        
    </cms:form>   

    <!-- give an option to logout -->
    <a href="<cms:logout_link />">logout</a>  

<?php COUCH::invoke(); ?>