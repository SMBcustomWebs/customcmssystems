<?php require_once( '../ccs_dash/cms.php' ); ?>

<cms:template title='About Submenu Titles' clonable='1' parent='_donottouch_' icon='x' hidden='1' order="2200"  >

    <cms:editable type='message' name='sbm_hro_msg' order='2'>
        <h2>Page Topper</h2>
        <h4>One Topper per page</h4>
        <p></p>
    </cms:editable>
    <cms:pagebuilder name='sbm_hro_pb' label='<h3>Page Topper</h3>' skip_custom_fields='1' order='10'>
        <cms:section label='Page Hero' name='sbm_hro_sct'  masterpage='blocks/frame/hero.php' mosaic='ccs_hro_block_msc' />
    </cms:pagebuilder>
    <cms:editable type='message' name='sbm_cnt_msg' order='14'>
        <h2>Page Focus</h2>
        <h4>The main focus of this submenu item. These will usually link to seperate submenu items or direct contact pages.</h4>
        <p></p>
    </cms:editable>
    <cms:pagebuilder name='sbm_cnt_pb' label='<h3>Page Focus</h3>' skip_custom_fields='1' order='15'>
        <cms:section label='List Page Layout Style 1' name='lst_sty1_sct'  masterpage='blocks/lists/list_st_1.php' mosaic='list1_block_msc' />
        <cms:section label='List Page Layout Style 2' name='lst_sty2_sct'  masterpage='blocks/lists/list_st_2.php' mosaic='list2_block_msc' />
        <cms:section label='List Page Layout Style 3' name='lst_sty3_sct'  masterpage='blocks/lists/list_st_3.php' mosaic='list3_block_msc' />
        
    </cms:pagebuilder>
    <cms:editable type='message' name='sbm_xtr_msg' order='19'>
        <h2>Additional Page Items</h2>
        <h4>Optionally add related items to the page for fill content</h4>
        <p></p>
    </cms:editable>

    <cms:pagebuilder name='sbm_xtr_pb' label='<h3>Page Added Items</h3>' skip_custom_fields='1' order='20'>
        <cms:section label='Additional Page Items' name='ccs_abt_cntn_pg_add_trns_sect'  masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />
        <cms:section label='Additional Items' name='ccs_abt_cntn_pg_add_prc_sect'  masterpage='blocks/mods/pricing.php' mosaic='trns_pcng_msc' />

    </cms:pagebuilder>


    <cms:config_list_view exclude='default-page' searchable='1' orderby='weight' order='asc'>



    </cms:config_list_view>
    
    <cms:config_form_view>
        <cms:field 'k_folder_title' label='Submenu Title'  />
        <cms:field 'k_folder_name' hide='1' />
        <cms:field 'k_page_image' hide='0' />
        <cms:jit_fields>
            <cms:if k_page_id ne '-1'>
            
            </cms:if> 
        </cms:jit_fields>
    </cms:config_form_view>


</cms:template>

<?php COUCH::invoke(); ?>