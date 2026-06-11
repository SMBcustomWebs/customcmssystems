<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Catalog List Style 2' parent='_mod_lst_' icon='excerpt' clonable='0'  order='260' >
    <cms:mosaic name='list2_block_msc' label='Listing Page Style 1 - Medium Image Squares' body_class='_pb'>
        <cms:tile name='list2_cat_sm_crc_tile' label='Catalog Small Circles' _pb_template='list_2/theme/list2_cat_sm-crc' _pb_height='350'>
             <cms:embed 'pb_mods/list_2/embed/list2_cat_sm-crc.htm' />
        </cms:tile>
        <cms:tile name='list2_cat_sm_sqr_tile' label='Catalog Small Squares' _pb_template='list_2/theme/list2_cat_sm-sqr' _pb_height='350'>
             <cms:embed 'pb_mods/list_2/embed/list2_cat_sm-sqr.htm' />
        </cms:tile>
        <cms:tile name='list2_cat_sq_txt_tile' label='Catalog Med Squares - Text' _pb_template='list_2/theme/list2_cat_md-sq-txt' _pb_height='350'>
             <cms:embed 'pb_mods/list_2/embed/list2_cat_md-sq-txt.htm' />
        </cms:tile>
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>