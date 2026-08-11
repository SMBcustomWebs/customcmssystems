<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Category Lists Styles 1' parent='_stock_' icon='excerpt' clonable='0'  order='280' >
    
    <!-- ==================================================== -->
    <!-- THE ABOUT MOSAIC                                     -->
    <!-- ==================================================== -->
    <cms:mosaic name='abt_list1_block_msc' label='About List Style 1' body_class='_pb'>
        
        <cms:tile name='abt_list1_cat_ins_tile' label='About Catalog - Title Inside' _pb_template='list_1/theme/list1_cat_ins' _pb_height='350'>
             <cms:embed 'pb_mods/list_1/embed/abt_list1_cat_ins.htm' />
        </cms:tile>
        
        <cms:tile name='abt_list1_cat_outs_tile' label='About Catalog - Title Outside' _pb_template='list_1/theme/list1_cat_outs' _pb_height='350'>
             <cms:embed 'pb_mods/list_1/embed/abt_list1_cat_ins.htm' />
        </cms:tile>
       
        
    </cms:mosaic>

    <!-- ==================================================== -->
    <!-- THE PRODUCTS MOSAIC                                  -->
    <!-- ==================================================== -->
    <cms:mosaic name='prd_list1_block_msc' label='Products List Style 1' body_class='_pb'>
        
        <cms:tile name='prod_list1_cat_ins_tile' label='Products Catalog - Title Inside' _pb_template='list_1/theme/list1_cat_ins' _pb_height='350'>
             <cms:embed 'pb_mods/list_1/embed/prd_list1_cat_ins.htm' />
        </cms:tile>
        
        <cms:tile name='prod_list1_cat_outs_tile' label='Products Catalog - Title Outside' _pb_template='list_1/theme/list1_cat_outs' _pb_height='350'>
             <cms:embed 'pb_mods/list_1/embed/prd_list1_cat_ins.htm' />
        </cms:tile>
       
        
    </cms:mosaic>

    <!-- ==================================================== -->
    <!-- THE SERVICES MOSAIC                                  -->
    <!-- ==================================================== -->
    <cms:mosaic name='srv_list1_block_msc' label='Services List Style 1' body_class='_pb'>
        
        <cms:tile name='srv_list1_cat_ins_tile' label='Services Catalog - Title Inside' _pb_template='list_1/theme/list1_cat_ins' _pb_height='350'>
             <cms:embed 'pb_mods/list_1/embed/svc_list1_cat_ins.htm' />
        </cms:tile>
        
        <cms:tile name='srv_list1_cat_outs_tile' label='Services Catalog - Title Outside' _pb_template='list_1/theme/list1_cat_outs' _pb_height='350'>
             <cms:embed 'pb_mods/list_1/embed/svc_list1_cat_ins.htm' />
        </cms:tile>
        
      
        
    </cms:mosaic>

</cms:template>
    
<?php COUCH::invoke(); ?>