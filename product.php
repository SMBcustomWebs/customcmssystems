<?php require_once( 'ccs_dash/cms.php' ); ?>
    <cms:template title="Products" clonable='1' dynamic_folders='1' folder_masterpage='bmenu/product.php' icon='tags' order="1400">
        
        <!-- ============================================== -->
        <!-- PRESERVED GLOBALS & PAGEBUILDERS               -->
        <!-- ============================================== -->
        <cms:globals>
            <cms:editable type='message' name='tpl_msg' order='1' >
                <h2>Product Menu Page:: Build Homeview and Single-Content Pages</h2>
                <h4></h4>
                <hr><br><hr><br>
            </cms:editable>

            <cms:editable type='message' name='glb_hmv_msg' order='4' >
                <h2>Build Layout For Product Homepage View</h2>
                <h4>This is the landing page when users click on "About" in the menu</h4>
                <p></p>
                <hr><br>
            </cms:editable>

            <cms:pagebuilder name='hro_hmv_pb' label='Home View Page Header (Hero)' skip_custom_fields='1' order='5'>
                <cms:section label='PRODUCT Homepage Hero - Single' name='hro_hmv_pb_sct'  masterpage='blocks/frame/hero.php' mosaic='ccs_hro_msc' />
				<cms:section label='PRODUCT Homepage Hero - Carousel' name='hro_crs_hmv_pb_sct'  masterpage='blocks/frame/hero_swpr.php' mosaic='ccs_hro_swpr_msc' />
            </cms:pagebuilder>
            
            <cms:pagebuilder name='cnt_hmv_pb' label='Home view Main Content Builder' skip_custom_fields='1' order='10'>
                <cms:section label='Banner Transition' name='trans_sect'  masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />
                <cms:section label='Segmented with Scrolling Images' name='seg_img_scr_sect'  masterpage='blocks/mods/seg_img_scrol.php' mosaic='seg_img_scrl_msc' />
				
				
                <cms:section label='PRODUCT Navigation Swiper Block' name='prd_hmv_nswp_sld_blk_sct'  masterpage='blocks/sliders/nswp_sld_blk.php' mosaic='prd_nswp_sld_blk_msc' />
                <cms:section label='SERVICE Navigation Swiper Block' name='svc_hmv_nswp_sld_blk_sct'  masterpage='blocks/sliders/nswp_sld_blk.php' mosaic='svc_nswp_sld_blk_msc' />
				
				<cms:section label='ABOUT Category Layout Style 1' name='abt_hmv_pb_lst_sty1_sct'  masterpage='blocks/lists/list_st_1.php' mosaic='abt_list1_block_msc' />
                <cms:section label='PRODUCTS Category Layout Style 1' name='prd_hmv_pb_lst_sty1_sct'  masterpage='blocks/lists/list_st_1.php' mosaic='prd_list1_block_msc' />
                <cms:section label='SERVICES Category Layout Style 1' name='svc_hmv_pb_lst_sty1_sct'  masterpage='blocks/lists/list_st_1.php' mosaic='svc_list1_block_msc' />
				
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

            <cms:pagebuilder name='glb_sng_hro_pb' label='Single Page Header (Hero)' skip_custom_fields='1' order='105'>
                <cms:section label='Single View Page Hero' name='glb_sng_hro_sct'  masterpage='blocks/frame/hero.php' mosaic='ccs_hro_block_msc' />
            </cms:pagebuilder>

            <cms:pagebuilder name='glb_sng_cnt_pb' label='Single Page Main Content Builder' skip_custom_fields='1' order='110'>
                <cms:section label='Content Page' name='glb_sng_cnt_sct'  masterpage='blocks/content.php' mosaic='cntnt_pg_msc' />
                <cms:section label='Segmented with Scrolling Images' name='seg_img_scr_sect'  masterpage='blocks/mods/seg_img_scrol.php' mosaic='seg_img_scrl_msc' />
            </cms:pagebuilder>

            <cms:pagebuilder name='glb_sng_xtr_pb' label='Single Page Extra Content Builder' skip_custom_fields='1' order='115'>
                <cms:section label='Banner Transition' name='glb_sng_xtr_sct'  masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />
            </cms:pagebuilder>
        </cms:globals>  

        <!-- Force category to be chosen -->
        <cms:editable type='hidden' name='dummy' validator='menu_check' order='1' >1</cms:editable>  


        <!-- ============================================== -->
        <!-- ZONE 1: CORE IDENTITY                          -->
        <!-- ============================================== -->
		
		<!-- 1. The Controlling Field (Checkbox) -->
		<cms:editable type='checkbox' name='track_inventory' label='Track Inventory' desc='Check this box to strictly enforce stock limits on this item' opt_values='Yes=1' opt_selected='0' order='6' />

		<!-- 2. The Conditional Function -->
		<cms:func _into='show_inventory_cond' track_inventory=''>
			<cms:if "<cms:is '1' in=track_inventory />">
				show
			<cms:else />
				hide
			</cms:if>
		</cms:func>

		<!-- 3. The Target Field -->
		<cms:editable type='text' name='in_stock' label='Inventory Count' desc='Total number of physical items in stock (Numbers only)' search_type='integer' validator='non_negative_integer' width='150' order='7' not_active=show_inventory_cond />
		
        <cms:editable type='message' name='msg_core' order='10'>
            <div style="background: #fff3cd; border: 1px solid #ffe69c; border-left: 4px solid #f6c23e; padding: 15px; border-radius: 4px; margin-top: 20px; margin-bottom: 10px;">
                <h4 style="margin: 0 0 5px 0; color: #1cc88a; font-family: sans-serif;">1. General Product Details</h4>
                <p style="margin: 0; color: #5a5c69; font-size: 13px; font-family: sans-serif;">Set the primary product identity. Enter the unique SKU, general text description, and upload the main hero image below.</p>
            </div>
        </cms:editable>

        <cms:editable name='itm_sku' label='Product SKU' desc='Leave blank to use a system-generated ID (Letters and numbers only)' type='text' search_type='text' validator='regex=/^[A-Za-z0-9-]+$/' order='12' />
		
        <cms:editable type='textarea' name='itm_desc' label='General Description' desc='Describe this product. (Plain text only to preserve template styling)' order='15' />

        <!-- Main Image & Thumbnails Pulled to Root Level -->
        <cms:editable name='itm_img_mn' label='Main Hero Image' type='image' desc='Main image max dimension 1000px' width='1000' height='1000' crop='0' show_preview='1' preview_width='75' order='20' />
        <cms:editable name='pp_itm_thumb' label='Thumbnail (Auto)' type='thumbnail' width='200' height='200' show_preview='1' assoc_field='itm_img_mn' order='21' />
        <cms:editable name='itm_list_thumb' label='600x600 List Image (Auto)' type='jcropthumb' width='600' height='600' show_preview='1' preview_height='75' assoc_field='itm_img_mn' order='22' />


        <!-- ============================================== -->
        <!-- ZONE 2: MEDIA & SPECIFICATIONS                 -->
        <!-- ============================================== -->
        <cms:editable type='message' name='msg_media' order='30'>
            <div style="background: #fff3cd; border: 1px solid #ffe69c; border-left: 4px solid #f6c23e; padding: 15px; border-radius: 4px; margin-top: 20px; margin-bottom: 10px;">
                <h4 style="margin: 0 0 5px 0; color: #1cc88a; font-family: sans-serif;">2. Product Media & Highlights</h4>
                <p style="margin: 0; color: #5a5c69; font-size: 13px; font-family: sans-serif;">Manage the image gallery slider and build categorized specification accordions.</p>
            </div>
        </cms:editable>

        <cms:mosaic name='itm_sldrs_msc' label='Item Slide Show (Gallery)' order='35'>
            <cms:tile name='gallery_image' label='Gallery Image'>
                <cms:editable name='itm_slider_img' label='Gallery Image' type='image' width='1000' height='1000' crop='0' show_preview='1' preview_width='75' order='1' />
                <cms:editable name='itm_slider_img_alt' label='Alt Text' type='text' order='2' />
            </cms:tile>
        </cms:mosaic>

        <cms:mosaic name='itm_specs_msc' label='Specifications & Highlights' desc='Create categorized accordion sections' order='40'>
            <cms:tile name='spec_section' label='Spec Category'>
                <cms:editable type='text' name='spec_title' label='Category Title (Accordion Header)' desc='e.g., Dimensions, Materials, Safety Data' required='1' order='5' />
                <cms:editable 
                    type='richtext' 
                    name='spec_content' 
                    label='Category Content' 
                    desc='Add text, lists, charts, or images' 
                    toolbar='custom' 
                    custom_toolbar='Bold, Italic, -, RemoveFormat | NumberedList, BulletedList | Link, Unlink | Image, Table | Source'
                    order='10' 
                />
            </cms:tile>
        </cms:mosaic>


        <!-- ============================================== -->
        <!-- ZONE 3: E-COMMERCE & CART ENGINE               -->
        <!-- ============================================== -->
        <cms:editable type='message' name='msg_ecommerce' order='50'>
            <div style="background: #fff3cd; border: 1px solid #ffe69c; border-left: 4px solid #f6c23e; padding: 15px; border-radius: 4px; margin-top: 20px; margin-bottom: 10px;">
                <h4 style="margin: 0 0 5px 0; color: #1cc88a; font-family: sans-serif;">3. Cart Rules & Logistics (Advanced)</h4>
                <p style="margin: 0; color: #856404; font-size: 13px; font-family: sans-serif;">Configure pricing logic, available product variants, and shipping data.<i>Clicking on title bars will expand or collapse the group</i></p>
            </div>
        </cms:editable>

        <cms:editable type='group' name='group_price' label='Price Points' desc="current, reduced, added, etc" order='55' >   
            <cms:editable type='text' name='pp_price' label='Base Price' desc='Amount in USD (correct upto 2 decimal points without the $ sign)' maxlength='10' required='1' search_type='decimal' validator='non_zero_decimal' width='150' order='5' />
            <cms:editable 
				name='pp_tax_class' 
				label='Tax Class' 
				desc="If not selected, the default global tax percent will be used" 
				type='relation' 
				has='one' 
				searchable='0' 
				order='10' 
				orderby='page_name' 
				order_dir='asc' 
				masterpage='tax-class.php' 
			/>
            <cms:editable type='message' name='explain_discount_scale' order='10' >
                <b>Quantity based pricing:</b> <i>(Tiered pricing)</i><br/>
                <font color='#777'>If the base price of this product varies based on the quantity purchased (useful for bulk purchases),<br>
                for example, if the base price is $10 but you want the price to be reduced by $2 (i.e. made $8) for purchase of more than 5 units, and by $3 (i.e. made $7) for purchase of more than 10 units, set it to:</font> <br/>
                <font color='blue'><pre>[ 5=2 | 10=3 ]</pre></font>
                <font color='#777'>where the string above stands for '<i>reduce price by 2 for more than 5, reduce by 3 for more than 10</i>'<br>
                If you want the reduction to be a percentage of the base price (instead of a fixed value as done above), add a '%' sign e.g.<br></font>
                <font color='blue'><pre>[ 5=2 | 10=3 ]%</pre></font>        
                <font color='#777'>where the string above now stands for '<i>reduce price by 2% for more than 5, reduce by 3% for more than 10</i>'</font>
            </cms:editable>   
            <cms:editable type='text' name='pp_discount_scale' label='Add Qty Pricing Here:' desc="follow format as explained above" validator='regex=/\[\[?([^\]]*)\](\]?)\s*(%?)/' order='15' />
            
            <cms:editable type='text' name='old_price' label='OldPrice' desc='gets crossed out on page (optional)' maxlength='10' search_type='decimal' width='150' order='20' />
            <cms:editable type='relation' name='pp_tax_class' label='Tax Class' desc="if not selected, default tax applies" has='one' searchable='0' orderby='page_name' order_dir='asc' masterpage='tax-class.php' order='25' />
        </cms:editable>

        <cms:editable type='group' name='group_variants' label='Variants' desc="colors, sizes, etc" collapsed='1' order='60' >   
            <cms:editable type='message' name='explain_options' order='2'>
                <hr><br><hr>
                <b>Product Variants:</b>
                <br/>
                <font color='#777'>
                    If this product has variants (e.g. Size, Color or a Custom message) 
                    add each to the box below using the following format:
                </font>
                <br/>
                <font color='blue'>
                    <pre>
                        Color[Red | Black=+3  | Green=-2]        
                        Size[Large | Medium | Small]*
                        Your Message[*TEXT*]
                        Your Message[*TEXT*=5]
                    </pre>
                </font>
                <font color='#777'>Note that<br/>
                    1. Each variant is on a separate line.<br/>
                    2. If an option has a different price than the base price, you can specify the price difference too.<br/> 
                    For example, the 'Black' option of 'Color' above will add $3 to the base price while the 'Green' will deduct $2. <br>
                    3. To create radio buttons instead of a dropdown add a '*' at the end as with 'Size' in the example above. <br/>
                    4. To create a textbox (if the variant consists of custom text e.g. message to be printed on T-Shirts), use '*TEXT*' as shown in the third variant above. You can also specify any price difference as shown in the last variant.
                </font>
            </cms:editable>   
            <cms:editable type='textarea' name='pp_options' label='Describe Variants Here As Explained Above' height='130' order='4' />
        </cms:editable>
            
        <cms:editable type='group' name='group_shipping' label='Shipping Information' desc='click to expand' collapsed='1' order='65' > 
            <cms:editable type='radio' name='pp_requires_shipping' label='Requires shipping' desc='Select No if this is not a physical product that requires shipping' opt_values='Yes=1 | No=0' opt_selected='1' order='5' />
            <cms:editable type='message' name='explain_shipping_scale' order='8' >
                <b>Shipping Charges:</b><br/>
                <font color='#777'>Set the option below if you want to set up a sliding scale of shipping charges based on the number of this item ordered.<br>
                For example, if you charge $3 to deliver one to five units, $7 to ship six to 15 units, and $10 to ship more than 15 units, set it to:</font> <br/>
                <font color='blue'><pre>[ 0=3 | 5=7 | 15=10 ]</pre></font>
                <font color='#777'>where the string above stands for '<i>3 for more than 0, 7 for more than 5, 10 for more than 15</i>'</font>
            </cms:editable>   
            <cms:editable type='text' name='pp_shipping_scale' label='Set Shipping Charge:' desc='examples above' validator='regex=/\[\[?([^\]]*)\](\]?)\s*(%?)/' order='10' />
        </cms:editable>   


        <!-- ============================================== -->
        <!-- ZONE 4: VISIBILITY & BADGES                    -->
        <!-- ============================================== -->
        <cms:editable type='group' name='group_visibility' label='Visibility & Placement' order='75'>
            <cms:editable type='checkbox' name="feature" label="On Feature Listing" desc="check to add to features list" opt_values='Set As Feature Item=1' opt_selected='0' order='1' />
            <cms:editable type='checkbox' name="value" label="On Value Listing" desc="check to add to values list" opt_values='Set As Value Item=1' opt_selected='0' order='2' />
            <cms:editable type='checkbox' name="noshow" label="Do Not Show On Menu" desc="default - Will be shown on Site menu" opt_values='Do Not Show on Menu=1' opt_selected='0' order='3' />
        </cms:editable>


        <!-- ============================================== -->
        <!-- ADMIN VIEWS (LIST & FORM)                      -->
        <!-- ============================================== -->
        <cms:config_list_view exclude='default-page' searchable='1' orderby='weight' order='asc'>
            <!-- FIXED CSS BUG HERE -->
            <cms:style>
                .col-k_page_title { width: 20% !important; }
                .col-k_up_down { width: 10% !important; }
            </cms:style>
            
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
            <cms:field 'k_page_title' label='Product Name' group='_custom_fields_' />
            <cms:field 'k_page_name' hide='1' />
            <cms:field 'k_page_folder_id' label='Menu To Place Under' desc='may not choose crossed-out names' group='_custom_fields_' />
            <cms:jit_fields>
                <cms:if k_page_id ne '-1'>
                </cms:if> 
            </cms:jit_fields>
        </cms:config_form_view>

    </cms:template>
    
    <cms:embed 'tl_if_pb_emb.html' />
<?php COUCH::invoke(); ?>