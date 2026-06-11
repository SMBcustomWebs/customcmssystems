<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Accordian Module' parent='_mod_gen_' icon='layers' clonable='0'  order='100' >
    <cms:mosaic name='accdng_msc' label='Module with Accordian Boxes' body_class='_pb'>
        <cms:tile name='cntct_map_add_tl' label='Accordian Boxes' _pb_template='bs_mods/theme/accordion' _pb_height='300'>
            <cms:embed 'pb_mods/bs_mods/embed/accordion.htm' /> 
        </cms:tile>
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>
