<?php require_once( 'ccs_dash/cms.php' ); ?>
    <cms:template title="About"  icon='people' clonable='1' dynamic_folders='1' folder_masterpage='bmenu/about.php' order="1200" >
        <cms:globals>
            <cms:editable type='message' name='tpl_msg' order='9' >
                <h2>About Menu Page:: Build Homeview framework(Top section) and Single-Content Page framework(bottom section)</h2>
                <h4></h4>
                <hr><br><hr><br>
            </cms:editable>

			<cms:editable type="group" name="hro_hmv_pb_grp" label="'About' Homepage - Hero" desc="Very top of page" order='10' />

			<cms:editable type='message' name='hro_hmv_pb_msg' group='hro_hmv_pb_grp' order='11'>
				<h2>About Homepage View Hero Header</h2>
				<h3>IMPORTANT: Only the top Hero will show on page.</h3>
				<h4>Change order with the order arrows on the right of the tile.</h4>
				<p>Arrows show when floating pointer over the top right corner</p>
				<hr><br>
			</cms:editable>

			<cms:pagebuilder name='hro_hmv_pb' label='About Homepage Header (Hero)' group='hro_hmv_pb_grp' skip_custom_fields='1' order='12'>
				<cms:section label='About Homepage Hero - Single' name='hro_hmv_pb_sct'  masterpage='blocks/frame/hero.php' mosaic='ccs_hro_msc' />
				<cms:section label='About Homepage Hero - Carousel' name='hro_crs_hmv_pb_sct'  masterpage='blocks/frame/hero_swpr.php' mosaic='ccs_hro_swpr_msc' />
			</cms:pagebuilder>

			<cms:editable type="group" name="cnt_hmv_pb_grp" label="Homepage Content Selector" desc="" order='20' />

	
			<cms:editable type='message' name='cnt_hmv_pb_msg' group='cnt_hmv_pb_grp' order='21'>
				<h2>Build Layout For About Homepage View</h2>
				<h4></h4>
				<p></p>
				<hr><br>
			</cms:editable>

			<cms:pagebuilder name='cnt_hmv_pb' label='Home view Main Content Builder' group='cnt_hmv_pb_grp' skip_custom_fields='1' order='22'>
				<cms:section label='Banner Transition' name='trans_sect'  masterpage='blocks/banner/transitions.php' mosaic='trns_msc' />
				
				<cms:section label='Segmented with Scrolling Images' name='seg_img_scr_sect'  masterpage='blocks/mods/seg_img_scrol.php' mosaic='seg_img_scrl_msc' />
				
				<cms:section label='ABOUT Category Layout Style 1' name='abt_hmv_pb_lst_sty1_sct'  masterpage='blocks/lists/list_st_1.php' mosaic='abt_list1_block_msc' />
				<cms:section label='PRODUCTS Category Layout Style 1' name='prd_hmv_pb_lst_sty1_sct'  masterpage='blocks/lists/list_st_1.php' mosaic='prd_list1_block_msc' />
                <cms:section label='SERVICES Category Layout Style 1' name='svc_hmv_pb_lst_sty1_sct'  masterpage='blocks/lists/list_st_1.php' mosaic='svc_list1_block_msc' />

				<cms:section label='Layout Style 2' name='cnt_hmv_lst_sty2_sect'  masterpage='blocks/lists/list_st_2.php' mosaic='list2_block_msc' />
				
				<cms:section label='Layout Style 3' name='cnt_hmv_lst_sty3_sect'  masterpage='blocks/lists/list_st_3.php' mosaic='list3_block_msc' />
				
				<cms:section label='Additional Page Items' name='cnt_hmv_trn_sect'  masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />
				
				<cms:section label='Additional Items' name='add_prc_sect'  masterpage='blocks/mods/pricing.php' mosaic='trns_pcng_msc' />
			</cms:pagebuilder>
			
			
			<cms:editable type="group" name="xtr_hmv_pb_grp" label="Page Extras" desc="Photo Galleries, etc" order='30' />
			<cms:editable type='message' name='xtr_hmv_pb_msg' group='xtr_hmv_pb_grp' order='31'>
				<h2>Build Layout For About Homepage Page Extras</h2>
				<h4></h4>
				<p></p>
				<hr><br>
			</cms:editable>
            <cms:pagebuilder name='xtr_hmv_pb' label='Home view Extra Content Builder' group='xtr_hmv_pb_grp' skip_custom_fields='1' order='35'>
                <cms:section label='Banner Transition' name='xtr_hmv_bnr_trn_sct'  masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />

            </cms:pagebuilder>
			
			<!-- START SINGLE CONTENT PAGE FRAMEING -->
			<!-- Build Hero for single-content view with Pagebuilder Blocks -->
			<cms:editable type="group" name="hro_sng_pb_grp" label="Content Page Hero Selector" desc="" order='70' />

				<cms:editable type='message' name='hro_sng_pb_msg' group='hro_sng_pb_grp' order='71' >
					<hr><br><hr><br>
					<h2>Building the Page Hero for the single content page</h2>
					<h4>This is the landing page when users click on a single "About" (employee) link</h4>
					<p></p>
				</cms:editable>
				
				<cms:pagebuilder name='hro_sng_pb' label='Single Page Header (Hero)' group='hro_sng_pb_grp' skip_custom_fields='1' order='75'>
					<cms:section label='About Content Page Hero - Single' name='hro_sng_pb_sct'  masterpage='blocks/frame/hero.php' mosaic='ccs_hro_msc' />
					<cms:section label='About Content Page Hero - Carousel' name='hro_sng_hmv_pb_sct'  masterpage='blocks/frame/hero_swpr.php' mosaic='ccs_hro_swpr_msc' />
				</cms:pagebuilder>

			<!-- Build page modules for single-content view with Pagebuilder Blocks -->
			<cms:editable type="group" name="cnt_sng_pb_grp" label="Build Page Modules" desc="" order='80' />

				<cms:editable type='message' name='cnt_sng_pb_msg' group='cnt_sng_pb_grp' order='81' >
					<hr><br><hr><br>
					<h2>Building the Page Layout for the single content page</h2>
					<h4>This is the landing page when users click on a single "About" (employee) link</h4>
					<p></p>
				</cms:editable>
				<cms:pagebuilder name='cnt_sng_pb' label='Single Page Main Content Builder' group='cnt_sng_pb_grp' skip_custom_fields='1' order='85'>
					<cms:section label='Content Page' name='cnt_sng_pb_cnt_sct'  masterpage='blocks/content.php' mosaic='cntnt_pg_msc' />
					<cms:section label='Segmented with Scrolling Images' name='cnt_sng_pb_scr_sect'  masterpage='blocks/mods/seg_img_scrol.php' mosaic='seg_img_scrl_msc' />
				</cms:pagebuilder>
			
			
			<cms:editable type="group" name="xtr_sng_pb_grp" label="Page Content Selector" desc="" order='90' />

				<cms:editable type='message' name='xtr_sng_pb_msg' group='xtr_sng_pb_grp' order='91' >
					<hr><br><hr><br>
					<h2>Building the Page Layout for the single content page</h2>
					<h4>This is the landing page when users click on a single "About" (employee) link</h4>
					<p></p>
				</cms:editable>

				<cms:pagebuilder name='xtr_sng_pb' label='Single Page Extra Content Builder' group='xtr_sng_pb_grp' skip_custom_fields='1' order='95'>
					<cms:section label='Banner Transition' name='xtr_sng_pb_trn_sct'  masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />
				</cms:pagebuilder>
			

        </cms:globals>  

        <cms:editable type='hidden' name='dummy' validator='menu_check' order='1' >1</cms:editable>
        <cms:editable type='message' name='pg_msg' order='2' >
            <h2>Fill In Employee Info</h2>
            <h4></h4>
            <p>Save Page or unpublish to not be shown.</p>
        </cms:editable>
        <cms:editable type='image' name='img' label='Image' desc='1000x1000 or similar 1:1 (square) ratio'
            width='1000'
            height='1000'
            enforce_max='1'
            show_preview='1'
            preview_width='75'
            order='5'
        /> 
        <cms:editable type='text' name='img_alt' label='Item Image Alt' desc='for screen readers and SEO' 
            order='6'
        />
        <cms:editable type='text' name='pos' label='Position Full Title' 
            order='8'
        />
        <cms:editable type='text' name='pab' label='Position Abbriviation' 
            order='9'
        />
        <cms:editable type='text' name='psd' label='Position Start Date' 
            order='10'
        
        />
        <cms:editable type='richtext' name='desc' label='Bio' desc='format as necessary'
			toolbar='custom'
			custom_toolbar='Bold, Italic, Underline, -, RemoveFormat | NumberedList, BulletedList | Link, Unlink | Source'
			order='20'
		/>
				<cms:config_list_view exclude='default-page' searchable='1' orderby='weight' order='asc'>
            <cms:style>
                .col-k_page_title{
                    width: 20% ; important!
                }
                .col-k_up_down{
                    width: 10% ; important!
                }
            </cms:style>
            <cms:ignore>
                <cms:script>
                    
                </cms:script>
                <cms:html>
                    <cms:repeat '3' startcount='1'>
                        <h<cms:show k_count />>Hello</h><cms:show k_count />>
                    </cms:repeat>

                    <cms:show_warning heading='Important' >
                        Please do not delete any of these pages!
                    </cms:show_warning>
                    <cms:show_info heading='' >
                        These pages have been created automatically!
                    </cms:show_info>

                </cms:html>
            </cms:ignore>
            <cms:field 'k_up_down' header='Reorder Arrows' class='k_up_down' />
            <cms:field 'k_page_title' sortable='0' class='k_page_title' />
            <cms:field 'sb_menu_fld' header='Menu Path To Page' >
            <cms:folders exclude=k_page_foldername >
                <cms:if "<cms:is_ancestor parent=k_folder_name child=k_page_foldername />" >
                    <strong><cms:show k_folder_title /></strong><cms:show " >> " />
                </cms:if>
            </cms:folders>
            <cms:show k_page_foldertitle />
            </cms:field>
            <cms:field 'k_actions' />
            <cms:field 'k_selector_checkbox' />
        </cms:config_list_view>
        <cms:config_form_view>
			
            <cms:field 'k_page_title' label='Employee Name' group='_custom_fields_' />
            <cms:field 'k_page_name' hide='1' />
            
    		<cms:field 'k_folder_name' hide='1' />
			
            <cms:field 'k_page_folder_id' label='Menu Category' group='_custom_fields_' />
            <cms:jit_fields>
                <cms:if k_page_id ne '-1'>
                
                </cms:if> 
            </cms:jit_fields>
        </cms:config_form_view>
    </cms:template>

    <cms:embed 'tl_if_pb_emb.html' />

<?php COUCH::invoke(); ?>