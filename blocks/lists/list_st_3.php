<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Catalog List Style 3' parent='_mod_lst_' icon='excerpt' clonable='0'  order='290' >
    <cms:mosaic name='list3_block_msc' label='Listing Page Style 3 - Titled Cards with  Sidebar' body_class='_pb'>
        <cms:tile name='list3_card_sdbr_rt_tile' label='Titled Cards with Sidebar on Right' _pb_template='list_3/theme/list3_card_sdbr_rt' _pb_height='350'>
             <cms:embed 'pb_mods/list_3/embed/list3_card_sdbr_rt.htm' />
        </cms:tile>
  
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>