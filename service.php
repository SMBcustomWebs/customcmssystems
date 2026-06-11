<?php require_once( 'ccs_dash/cms.php' ); ?>
    <cms:template title="Services"  clonable='1' dynamic_folders='1' folder_masterpage='bmenu/service.php' icon='wrench' order='1300' >
        <cms:globals>
            <cms:editable type='message' name='tpl_msg' order='1' >
                <h2>Service Menu Page:: Build Homeview and Single-Content Pages</h2>
                <h4></h4>
                <hr><br><hr><br>
            </cms:editable>

            <cms:editable type='message' name='glb_hmv_msg' order='4' >
                <h2>Build Layout For Service Homepage View</h2>
                <h4>This is the landing page when users click on "About" in the menu</h4>
                <p></p>
                <hr><br>

            </cms:editable>
            <cms:pagebuilder name='glb_hmv_hro_pb' label='Home View Page Header (Hero)' skip_custom_fields='1' order='5'>
                <cms:section label='Home View Page Hero' name='glb_hmv_hro_sct'  masterpage='blocks/frame/hero.php' mosaic='ccs_hro_block_msc' />
            </cms:pagebuilder>
            
            <cms:pagebuilder name='glb_hmv_cnt_pb' label='Home view Main Content Builder' skip_custom_fields='1' order='10'>
                <cms:section label='Banner Transition' name='trans_sect'  masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />
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
        <!-- force category to be chosen - set in addon/k_functions -->
        <cms:editable type='hidden' name='dummy' validator='menu_check' order='1' >1</cms:editable>  
        <!-- Item description -->
        <cms:editable type='textarea' name='itm_desc' label='General Description'  desc='Describe Service'  order='15' />
        <cms:editable type='group' name='itm_img_grp' label='Images' collapsed='1' order='20' >
            <cms:editable name='itm_img_mn' type='image' desc='main image max dimension 800px'         
                width='1000'
                height='1000'
                enforce_max='1'
                show_preview='1' 
                preview_width='75'
                order='5'
            />
            <cms:editable type='thumbnail' name='pp_itm_thumb' label='Thumbnail'
                width='200' 
                height='200'
                show_preview='1'
                assoc_field='itm_img_mn'
                order='10'
            />
            <cms:editable type='jcropthumb' name='itm_list_thumb' label='600x600 List Image'
                width='600'
                height='600'
                show_preview='1'
                preview_height='75'
                assoc_field='itm_img_mn'
                order='15'
            />
        </cms:editable>




        <!-- service pricing -->
        <cms:editable type='group' name='group_price' label='Price Points' desc="current, reduced, added, etc"  order='30' >    
            <cms:editable type='text' name='pp_price' label='Base Price' desc='Amount in USD (correct upto 2 decimal points without the $ sign)'
                maxlength='10'
                required='0'
                search_type='decimal'
                validator='non_zero_decimal'
                width='150'
                order='5'
            />
            <cms:editable
                name='explain_discount_scale' 
                type='message'
                order='10'
                >
                <b>Quantity based pricing:</b> <i>(Tiered pricing)</i><br/>
                <font color='#777'>If the base price of this service varies based on the quantity purchased (useful for bulk purchases),<br>
                for example, if the base price is $10 but you want the price to be reduced by $2 (i.e. made $8) for purchase of more than 5 units, and by $3 (i.e. made $7) for purchase of more than 10 units, set it to:</font> <br/>
                <font color='blue'><pre>[ 5=2 | 10=3 ]</pre></font>
                <font color='#777'>where the string above stands for '<i>reduce price by 2 for more than 5, reduce by 3 for more than 10</i>'<br>
                If you want the reduction to be a percentage of the base price (instead of a fixed value as done above), add a '%' sign e.g.<br></font>
                <font color='blue'><pre>[ 5=2 | 10=3 ]%</pre></font>        
                <font color='#777'>where the string above now stands for '<i>reduce price by 2% for more than 5, reduce by 3% for more than 10</i>'</font>
            </cms:editable>   
            <cms:editable 
                name='pp_discount_scale' 
                label=':'
                type='text' 
                validator='regex=/\[\[?([^\]]*)\](\]?)\s*(%?)/'
                order='15'
            />
            <cms:editable 
                name='old_price' 
                label='OldPrice' 
                desc='gets crossed out on page (optional)'
                maxlength='10'
                search_type='decimal'
                width='150'
                type='text' 
                order='20'
            />
            <cms:editable type='relation' name='pp_tax_class' label='Tax Class' desc="if not selected, default tax applies"  
                has='one' 
                searchable='0'  
                orderby='page_name' 
                order_dir='asc'
                masterpage='tax-class.php' 
                order='25' 
            />
        </cms:editable>
        
        <cms:editable type='group' name='group_variants' label='Variants' desc="colors, sizes, etc" order='40' >    
            <cms:editable type='message' name='explain_options' order='2'>
                <hr><br><hr>
                <b>Service Variants:</b>
                <br/>
                <font color='#777'>
                    If this service has variants (e.g. Size, Color or a Custom message) 
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

            <cms:editable type='textarea' name='pp_options' label='Describe Varients Here As Explained Above'
                height='130'
                order='4'
            />
        </cms:editable>
        <cms:repeatable name='itm_ad_nfo' label="Specifications / Highlights" desc="each its own entry"  order='47'>
            <cms:editable type='text' name='prod_spec'label='Additional Info:' order='45' />
        </cms:repeatable>
            
        <cms:editable type='group' name='group_shipping' label='Shipping Information' desc='click to expand'  order='50' > 
            <cms:editable type='radio' name='pp_requires_shipping' label='Requires Product shipping' desc='Select Yes a physical product requires shipping for service work'
                opt_values='Yes=1 | No=0'
                opt_selected = '0'
                order='5'
            />

            <cms:editable type='message' name='explain_shipping_scale'  order='8' >
                <b>Shipping Charges:</b><br/>
                <font color='#777'>Set the option below if you want to set up a sliding scale of shipping charges based on the number of this item ordered.<br>
                For example, if you charge $3 to deliver one to five units, $7 to ship six to 15 units, and $10 to ship more than 15 units, set it to:</font> <br/>
                <font color='blue'><pre>[ 0=3 | 5=7 | 15=10 ]</pre></font>
                <font color='#777'>where the string above stands for '<i>3 for more than 0, 7 for more than 5, 10 for more than 15</i>'</font>
            </cms:editable>   

            <cms:editable type='text' name='pp_shipping_scale' label='Set Shipping Charge:' desc='examples above' 
                validator='regex=/\[\[?([^\]]*)\](\]?)\s*(%?)/'
                order='10'
            />
        </cms:editable>    

        <cms:ignore>
            <cms:editable type='relation' name='itm_bnd_rl' label="Brand Relation" masterpage='brands.php' has='one' order='60' />
        </cms:ignore>
        <cms:repeatable name='itm_sldrs' label="Item Slide Show"  order='70'>
            <cms:editable name='itm_slider_img' label='Image 1000x1000' type='image'  
                width='1000'
                height='1000'
                crop='1'
                show_preview='1'
                preview_width='75'
                order='3'              
            />
            <cms:editable name='itm_slider_img_alt' label='Alt Text' type='text' order='4' />
        </cms:repeatable>
            
        <cms:editable type='checkbox' name="feature" label="On Feature Listing" desc="check to add to features list"
            opt_values='Set As Feature Item=1'
            opt_selected = '0'
            order='75'
        />
        <cms:editable type='checkbox' name="value" label="On Value Listing" desc="check to add to values list"
            opt_values='Set As Value Item=1'
            opt_selected = '0'
            order='77'
        />
        <cms:editable type='checkbox' name="noshow" label="Do Not Show On Menu" desc="default - Will be shown on Site menu"
            opt_values='Do Not Show on Menu=1'
            opt_selected = '0'
            order='79'
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
            <cms:field 'k_page_title' label='Service Name' group='_custom_fields_' />
            <cms:field 'k_page_name' hide='1' />
            <cms:field 'k_page_folder_id' label='Menu To Place Under' desc='may not choose crossed-out names'  group='_custom_fields_' />
                    

            <cms:jit_fields>
                <cms:if k_page_id ne '-1'>
            
                </cms:if> 
            </cms:jit_fields>
        </cms:config_form_view>
    </cms:template>
    <cms:embed 'tl_if_pb_emb.html' />
<?php COUCH::invoke(); ?>