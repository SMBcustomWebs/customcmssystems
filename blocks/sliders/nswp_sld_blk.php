<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Navigation Slider Blocks' parent='_stock_' icon='excerpt' clonable='0' order='280' >
    
    <!-- ==================================================== -->
    <!-- PRODUCTS AND SERVICES MOSAIC                                  -->
    <!-- ==================================================== -->
		
    <cms:mosaic name='prd_nswp_sld_blk_msc' label='Products:: Navigation Slider' body_class='_pb'>
        
        <cms:tile name='prd_nswp_sld_blk_til' label='Products Navigation Slider Block' _pb_template='nav_swpr/theme/nswp_sld_blk' _pb_height='350'>
             <cms:embed 'pb_mods/nav_swpr/embed/prd_nswp_sld_blk.htm' />
        </cms:tile>
        
    </cms:mosaic>
	
	<cms:mosaic name='svc_nswp_sld_blk_msc' label='Services:: Navigation Slider' body_class='_pb'>
        
        <cms:tile name='svc_nswp_sld_blk_til' label='Services Navigation Slider Block' _pb_template='nav_swpr/theme/nswp_sld_blk' _pb_height='350'>
             <cms:embed 'pb_mods/nav_swpr/embed/svc_nswp_sld_blk.htm' />
        </cms:tile>
        
    </cms:mosaic>
	
	

</cms:template>
<?php COUCH::invoke(); ?>