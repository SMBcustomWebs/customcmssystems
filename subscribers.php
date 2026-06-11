<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='Subscribers' clonable='1' executable='0' order='900'>
    
    <cms:editable name='subscriber_email' type='text' hidden='1' />

    <cms:config_list_view orderby='publish_date' order='desc'>
       
    </cms:config_list_view>

</cms:template>
<?php COUCH::invoke(); ?>