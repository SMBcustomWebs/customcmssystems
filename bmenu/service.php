<?php require_once( '../ccs_dash/cms.php' ); ?>
    <cms:template title='Services Submenu Titles' parent='_donottouch_' clonable='1' icon='x' hidden='1' order='2300'  >

		
		<!-- SUBMENU PAGEBUILDER -->
		<cms:editable type="group" name="hro_sbm_pb_grp" label="'Service' Submenu - Hero" desc="Very top of page" order='1' >
		<cms:editable type='message' name='sbm_hro_pb_msg' group='hro_sbm_pb_grp' order='42'>
			<h2>Services Homepage View Hero Header</h2>
			<h3>IMPORTANT: Only the top Hero will show on page.</h3>
			<h4>Change order with the order arrows on the right of the tile.</h4>
			<p>Arrows show when floating pointer over the top right corner</p>
		</cms:editable>
		<cms:pagebuilder name='hro_sbm_pb' label='<h3>Submenu Page Hero</h3>'  group='hro_sbm_pb_grp' skip_custom_fields='1' order='45'>
			<cms:section label='SERVICE Submenu Hero - Single' name='hro_sbm_pb_sct'  masterpage='blocks/frame/hero.php' mosaic='ccs_hro_msc' />
			<cms:section label='SERVICE Submenu Hero - Carousel' name='hro_crs_sbm_pb_sct'  masterpage='blocks/frame/hero_swpr.php' mosaic='ccs_hro_swpr_msc' />
		</cms:pagebuilder>
		
		</cms:editable>
		
		
        <cms:editable type='message' name='sbm_cnt_msg' order='14'>
            <h2>Page Focus</h2>
            <h4>The main focus of this submenu item. These will usually link to seperate submenu items or direct contact pages.</h4>
            <p></p>
        </cms:editable>
        <cms:pagebuilder name='cnt_sbm_pb' label='<h3>Page Focus</h3>' skip_custom_fields='1' order='15'>


          <cms:section label='PRODUCT Navigation Swiper Block' name='prd_sbm_nswp_sld_blk_sct'  masterpage='blocks/sliders/nswp_sld_blk.php' mosaic='prd_nswp_sld_blk_msc' />
          <cms:section label='SERVICE Navigation Swiper Block' name='svc_sbm_nswp_sld_blk_sct'  masterpage='blocks/sliders/nswp_sld_blk.php' mosaic='svc_nswp_sld_blk_msc' />
			
          <cms:section label='ABOUT Category Layout Style 1' name='abt_sbm_pb_lst_sty1_sct'  masterpage='blocks/lists/list_st_1.php' mosaic='abt_list1_block_msc' />
          <cms:section label='PRODUCTS Category Layout Style 1' name='prd_sbm_pb_lst_sty1_sct'  masterpage='blocks/lists/list_st_1.php' mosaic='prd_list1_block_msc' />
          <cms:section label='SERVICES Category Layout Style 1' name='svc_sbm_pb_lst_sty1_sct'  masterpage='blocks/lists/list_st_1.php' mosaic='svc_list1_block_msc' />
			
			
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
            <cms:field 'k_page_title' label='Submenu Title' group='_custom_fields_' />
            <cms:field 'k_page_name' hide='1' />


        </cms:config_form_view>



</cms:template>
<!--<cms:ignore>
<cms:func 'delete-field-link' masterpage=k_template_name names='' types=''>
  <cms:php>
    // Validate masterpage
    global $DB, $CTX;
    $masterpage = trim( $CTX->get('masterpage') );
    $rs = $DB->select( K_TBL_TEMPLATES, array('id'), "name='". $DB->sanitize( $masterpage ) ."'");
    if( !isset($rs[0]) ) die( "ERROR: &lt;cms:call '". $CTX->get('k_func') ."' masterpage='".$masterpage."' &gt;: masterpage not found." );
  </cms:php>
  <cms:set links = '[]' is_json='1' />
  <cms:db_fields masterpage=masterpage names=names types=types skip_deleted='0'>
    <cms:test ignore='1'><cms:abort><cms:dump_all /></cms:abort></cms:test>
    <cms:set nonce = "<cms:create_nonce "delete_field_<cms:show id />" />" />
    <cms:php>
      global $CTX;
      $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
      $CTX->set( 'nonce', strtr(rawurlencode( $CTX->get('nonce') ), $revert), 'parent' );
    </cms:php>
    <cms:capture into="links.<cms:show name />" trim='1' scope='parent' is_json='1'>
    {
      "name": <cms:escape_json><cms:show name /></cms:escape_json>,
      "label": <cms:escape_json><cms:show label /></cms:escape_json>,
      "desc": <cms:escape_json><cms:show desc /></cms:escape_json>,
      "type": <cms:escape_json><cms:show type /></cms:escape_json>,
      "searchable": <cms:escape_json><cms:show searchable /></cms:escape_json>,
      "link": <cms:escape_json><cms:concat p0=k_admin_link p1='ajax.php?act=delete-field&fid=' p2=id p3='&nonce=' p4=nonce /></cms:escape_json>
    }
    </cms:capture>
    <cms:test ignore='1'><cms:abort><cms:show_json links /></cms:abort></cms:test>
  </cms:db_fields>
  <cms:each links as='field'>
    <cms:test ignore='1'><cms:abort><cms:dump_all /></cms:abort></cms:test>
    <cms:if k_first_item><table border='1'></cms:if>
    <tr style="<cms:if "<cms:mod k_count '2' />">background: #EEE;</cms:if>">
      <td><cms:show field.type /></td>
      <td><cms:show field.name /></td>
      <td><cms:show field.label /></td>
      <td><a href="<cms:show field.link />" target="_blank">del</a></td>
      <td><cms:show field.desc /></td>
    </tr>
    <cms:if k_last_item></table></cms:if>
  </cms:each>
</cms:func>
<cms:call 'delete-field-link' />
</cms:ignore>-->
<?php COUCH::invoke(); ?>