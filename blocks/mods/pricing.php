<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Pricing Sections' parent='_mod_prc_' icon='dollar' clonable='0'  order='620' >
    <cms:mosaic name='trns_pcng_msc' label='Pricing Tiles Section' body_class='_pb'>
        <cms:tile name='trns_prc_3_lnks_tl' label='3 Pricing Tiles with Links' _pb_template='pricing/theme/prc_3tl_lnk' _pb_height='300'>
            <cms:embed 'pb_mods/pricing/embed/prc_3tl_lnk.htm' /> 
        </cms:tile>
        
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>