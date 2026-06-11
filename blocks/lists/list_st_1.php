<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Catalog List Style 1' parent='_mod_lst_' icon='excerpt' clonable='0'  order='280' >
    <cms:mosaic name='list1_block_msc' label='Listing Page Style 1 - Medium Image Squares' body_class='_pb'>
        <cms:tile name='list1_cat_ins_tile' label='Portfolio Catalog - Title Inside' _pb_template='list_1/theme/list1_cat_ins' _pb_height='350'>
             <cms:embed 'pb_mods/list_1/embed/list1_cat_ins.htm' />
        </cms:tile>
        <cms:tile name='list1_cat_outs_tile' label='Portfolio Catalog - Title Outside' _pb_template='list_1/theme/list1_cat_outs' _pb_height='350'>
             <cms:embed 'pb_mods/list_1/embed/list1_cat_outs.htm' />
        </cms:tile>
        <cms:tile name='list1_cat_hov_tile' label='Portfolio Catalog - Title on Hover' _pb_template='list_1/theme/list1_cat_hov' _pb_height='350'>
             <cms:embed 'pb_mods/list_1/embed/list1_cat_hov.htm' />
        </cms:tile>
    </cms:mosaic>
</cms:template>
    
<?php COUCH::invoke(); ?>