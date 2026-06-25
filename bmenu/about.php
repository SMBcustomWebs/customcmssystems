<?php require_once( '../ccs_dash/cms.php' ); ?>

<cms:template title='About Submenu Titles' clonable='1' parent='_donottouch_' icon='x' hidden='1' order="2200"  >

		<cms:editable type='message' name='tpl_msg' order='39' >
			<hr><br><hr><br>
			<h2>About Submenu Page:: Build Submenu Homeview framework</h2>
			<h4></h4>
			
		</cms:editable>

		<!-- SUBMENU PAGEBUILDER -->
		<cms:editable type="group" name="hro_sbm_pb_grp" label="'About' Submenu - Hero" desc="Very top of page" order='40' />
		<cms:editable type='message' name='sbm_hro_pb_msg' group='hro_sbm_pb_grp' order='42'>
			<h2>About Homepage View Hero Header</h2>
			<h3>IMPORTANT: Only the top Hero will show on page.</h3>
			<h4>Change order with the order arrows on the right of the tile.</h4>
			<p>Arrows show when floating pointer over the top right corner</p>
		</cms:editable>
		<cms:pagebuilder name='hro_sbm_pb' label='<h3>Submenu Page Hero</h3>'  group='hro_sbm_pb_grp' skip_custom_fields='1' order='45'>
			<cms:section label='About Submenu Hero - Single' name='hro_sbm_pb_sct'  masterpage='blocks/frame/hero.php' mosaic='ccs_hro_msc' />
			<cms:section label='About Submenu Hero - Carousel' name='hro_crs_sbm_pb_sct'  masterpage='blocks/frame/hero_swpr.php' mosaic='ccs_hro_swpr_msc' />
		</cms:pagebuilder>

		<cms:editable type="group" name="cnt_sbm_pb_grp" label="'About' Submenu Content" desc="main page content" order='50' />
		<cms:editable type='message' name='cnt_sbm_pb_msg' group='cnt_sbm_pb_grp' order='52'>
			<h2>Submenu Landing Page Main Content</h2>
			<h4>The page layout of this submenu page.</h4>
			<p></p>
		</cms:editable>
		<cms:pagebuilder name='cnt_sbm_pb' label='<h3>Submenu Page Content</h3>' group='cnt_sbm_pb_grp' skip_custom_fields='1' order='55'>
			<cms:section label='List Page Layout Style 1' name='cnt_sbm_pb_lst_sty1_sct'  masterpage='blocks/lists/list_st_1.php' mosaic='list1_block_msc' />
			<cms:section label='List Page Layout Style 2' name='cnt_sbm_pb_lst_sty2_sct'  masterpage='blocks/lists/list_st_2.php' mosaic='list2_block_msc' />
			<cms:section label='List Page Layout Style 3' name='cnt_sbm_pb_lst_sty3_sct'  masterpage='blocks/lists/list_st_3.php' mosaic='list3_block_msc' />

		</cms:pagebuilder>

		<cms:editable type="group" name="xtr_sbm_pb_grp" label="'About' Submenu Extra" desc="galleries, forms, etc" order='60' />
		<cms:editable type='message' name='sbm_xtr_msg' group='xtr_sbm_pb_grp' order='61'>
			<h2>Additional Page Items</h2>
			<h4>Optionally add related items to the page for fill content</h4>
			<p></p>
		</cms:editable>

		<cms:pagebuilder name='sbm_xtr_pb' label='<h3>Page Added Items</h3>' group='xtr_sbm_pb_grp' skip_custom_fields='1' order='65'>
			<cms:section label='Additional Page Items' name='sbm_xtr_pb_add_trns_sect'  masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />
			<cms:section label='Additional Items' name='sbm_xtr_pb_add_prc_sect'  masterpage='blocks/mods/pricing.php' mosaic='trns_pcng_msc' />

		</cms:pagebuilder>


    <cms:config_list_view exclude='default-page' searchable='1' orderby='weight' order='asc'>



    </cms:config_list_view>
    
    <cms:config_form_view>
        <cms:field 'k_label_f_k_title' label='Subcategory Name' group='_custom_fields_' />
        <cms:field 'f_k_name' hide='1' />
        <cms:field 'k_page_image' hide='0' />
        <cms:jit_fields>
            <cms:if k_page_id ne '-1'>
            
            </cms:if> 
        </cms:jit_fields>
    </cms:config_form_view>


</cms:template>

<?php COUCH::invoke(); ?>