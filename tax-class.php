<?php require_once("ccs_dash/cms.php"); ?>
<cms:template title='Tax Classes' clonable='1' hidden='0' parent='_global_' >
    <cms:editable
        name='tax_rate'
        label='Tax Rate'
        desc='in percentage (correct upto 2 decimal points)'
        maxlength='10'
        required='1'
        search_type='decimal'
        validator='non_negative_decimal'
        width='90'
        type='text'
    />
    
    <cms:config_form_view>
        <cms:field 'k_page_title' label='Tax Class Name' group='_custom_fields_' />
        <cms:field 'k_page_name' hide='1' />
    </cms:config_form_view>
    <cms:config_list_view exclude='default-page' searchable='1' >
       
    </cms:config_list_view>
</cms:template>
<?php COUCH::invoke(); ?>