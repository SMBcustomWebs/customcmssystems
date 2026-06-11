<?php require_once( 'ccs_dash/cms.php' ); ?>
    <cms:template title="About"  icon='people' clonable='1' dynamic_folders='1' folder_masterpage='bmenu/about.php' order="1200" >
        <cms:globals>
            <cms:editable type='message' name='tpl_msg' order='1' >
                <h2>About Menu Page:: Build Homeview and Single-Content Pages</h2>
                <h4></h4>
                <hr><br><hr><br>
            </cms:editable>

            <cms:editable type='message' name='glb_hmv_msg' order='4' >
                <h2>Build Layout For About Homepage View</h2>
                <h4>This is the landing page when users click on "About" in the menu</h4>
                <p></p>
                <hr><br>

            </cms:editable>
            <cms:pagebuilder name='glb_hmv_hro_pb' label='Home View Page Header (Hero)' skip_custom_fields='1' order='5'>
                <cms:section label='Home View Page Hero' name='glb_hmv_hro_sct'  masterpage='blocks/frame/hero.php' mosaic='ccs_hro_msc' />
            </cms:pagebuilder>
            
            <cms:pagebuilder name='glb_hmv_cnt_pb' label='Home view Main Content Builder' skip_custom_fields='1' order='10'>
                <cms:section label='Banner Transition' name='trans_sect'  masterpage='blocks/banner/transitions.php' mosaic='trns_msc' />
                <cms:section label='Segmented with Scrolling Images' name='seg_img_scr_sect'  masterpage='blocks/mods/seg_img_scrol.php' mosaic='seg_img_scrl_msc' />
                <cms:section label='Layout Style 1' name='lst_sty1_sect'  masterpage='blocks/lists/list_st_1.php' mosaic='list1_block_msc' />
                <cms:section label='Layout Style 2' name='lst_sty2_sect'  masterpage='blocks/lists/list_st_2.php' mosaic='list2_block_msc' />
                <cms:section label='Layout Style 3' name='lst_sty3_sect'  masterpage='blocks/lists/list_st_3.php' mosaic='list3_block_msc' />
                <cms:section label='Additional Page Items' name='add_trns_sect'  masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />
                <cms:section label='Additional Items' name='add_prc_sect'  masterpage='blocks/mods/pricing.php' mosaic='trns_pcng_msc' />
            </cms:pagebuilder>

            <cms:pagebuilder name='glb_hmv_xtr_pb' label='Home view Extra Content Builder' skip_custom_fields='1' order='15'>
                <cms:section label='Banner Transition' name='glb_sng_xtr_sct'  masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />

            </cms:pagebuilder>

            <cms:editable type='message' name='glb_sng_msg' order='104' >
                <hr><br><hr><br>
                <h2>Building the Page Layout for the single content page</h2>
                <h4>This is the landing page when users click on a single "About" (employee) link</h4>
                <p></p>
            </cms:editable>
            <!-- Build page layout for single-content view with Pagebuilder Blocks -->
            <cms:pagebuilder name='glb_sng_hro_pb' label='Single Page Header (Hero)' skip_custom_fields='1' order='105'>
                <cms:section label='Single View Page Hero' name='glb_sng_hro_sct'  masterpage='blocks/frame/hero.php' mosaic='ccs_hro_msc' />
            </cms:pagebuilder>

            <cms:pagebuilder name='glb_sng_cnt_pb' label='Single Page Main Content Builder' skip_custom_fields='1' order='110'>
                <cms:section label='Content Page' name='glb_sng_cnt_sct'  masterpage='blocks/content.php' mosaic='cntnt_pg_msc' />
                <cms:section label='Segmented with Scrolling Images' name='seg_img_scr_sect'  masterpage='blocks/mods/seg_img_scrol.php' mosaic='seg_img_scrl_msc' />
            </cms:pagebuilder>

            <cms:pagebuilder name='glb_sng_xtr_pb' label='Single Page Extra Content Builder' skip_custom_fields='1' order='115'>
                <cms:section label='Banner Transition' name='glb_sng_xtr_sct'  masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />

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
                    function test(){
                        alert( 'Hello<cms:show k_count />' );
                    }
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
            <cms:field 'k_folder_title' label='Submenu Title'  />
    		<cms:field 'k_folder_name' hide='1' />
			
            <cms:field 'k_page_folder_id' label='Menu To Place Under' desc='may not choose crossed-out names'  group='_custom_fields_' />
            <cms:jit_fields>
                <cms:if k_page_id ne '-1'>
                
                </cms:if> 
            </cms:jit_fields>
        </cms:config_form_view>
    </cms:template>
    <!--<cms:ignore><cms:embed 'pg_frame/head.htm' /></cms:ignore>-->
    <cms:embed 'tl_if_pb_emb.html' />
<!-- <cms:ignore>
<h1>Folders:</h1>
  <cms:folders hierarchical='1' childof=k_folder_name limit='1'  >
    <h2><cms:show k_folder_title /></h2><cms:dump />HERE
      <cms:folders hierarchical='1' include_custom_fields='1' childof=k_folder_name  >
          -<a href="<cms:show k_folder_link />"><cms:show k_folder_title /></a><br />
      </cms:folders>
  </cms:folders><br>
  <h1>Pages:</h1>
  <cms:pages masterpage=k_template_name limit='1'>
    <cms:dump />
  </cms:pages>
<cms:show k_template_folder_masterpage />
is k page?: <cms:show k_is_page />
<cms:if k_is_page>
    <h1>Page-view: <cms:show k_page_title /></h1>
   
    < !-- if page is contained within a folder -- >
    <cms:if k_page_foldername >
        <h3>Page in folder: <cms:show k_page_foldertitle /></h3>
       
        < !-- bring the containing folder in context using cms:folders like this -- >
        <cms:folders hierarchical='1' include_custom_fields='1' root=k_page_foldername depth='2'>
       DUMP FROM FOLDER INSIDE PAGE INSIDE PAGE VIEW:<br>
            <cms:dump />
           
           
        </cms:folders>
       
    </cms:if>

</cms:if></cms:ignore> -->
      






<!--
<h2>About Company</h2>
  <section class="p-0 text-white">

    <div class="bg-holder overlay overlay-2" style="background-image:url(<cms:show k_site_link />assets/images/about-company.jpg);">
    </div>
    <!--/.bg-holder-- >

    <div class="container">
      <div class="row min-vh-50 align-items-center py-5">
        <div class="col-md-8 col-lg-5">
          <h1 class="fs-6 fs-md-5 mb-3 text-white">About Posh </h1>
          <p class="fw-light fs-8">Responsive in design | Elegant in Style One Perfect Template for Anything</p>
        </div>
      </div>
    </div>
    <!-- end of .container-- >

  </section>
  <!-- <section> close ============================-->
  <!-- ============================================-->


<?php COUCH::invoke(); ?>