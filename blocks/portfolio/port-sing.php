<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Portfolio:: Single Details 1' parent='_mod_prt_' icon='cog' clonable='0' order='160'>
    
    <cms:mosaic name='ccs_prt_sng_1_msc' label='Portfolio Single Layout 1' body_class='_pb'>
        
        <cms:tile name='ccs_prt_dtl_1_tl' label='Portfolio Details (Split Grid)' _pb_template='portfolio/single/theme/details_1' _pb_height='350'>
            
            <cms:embed 'pb_mods/portfolio/single/embed/port-sing_1_module.htm' />
            <cms:embed 'pb_mods/portfolio/single/embed/port-sing_1_header.htm' />
            <cms:embed 'pb_mods/portfolio/single/embed/port-sing_module_1_highlights.htm' />
            <cms:embed 'pb_mods/portfolio/single/embed/port-sing_module_1_content.htm' />
            
        </cms:tile>
        
    </cms:mosaic>

</cms:template>
<?php COUCH::invoke(); ?>