<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Content Blocks A' parent='_stock_' icon='excerpt' clonable='0' order='290' >
    
    <!-- ==================================================== -->
    <!-- PRODUCTS AND SERVICES MOSAIC                                  -->
    <!-- ==================================================== -->
		
    <cms:mosaic name='vtl_cntn_blk_msc' label='Vertical Title Content Block' body_class='_pb'>
        
        <cms:tile name='tl_cntn_blk_til' label='Vertical Title Content Block' _pb_template='content/theme/vtl_cntn_blk' _pb_height='350'>
             <cms:embed 'pb_mods/content/embed/vtl_cntn_blk.htm' />
        </cms:tile>
        
    </cms:mosaic>
	
	<cms:mosaic name='img_cntn_blk_msc' label='Image Content Block' body_class='_pb'>
        
        <cms:tile name='img_cntn_blk_til' label='Image Content Block' _pb_template='content/theme/img_cntn_blk' _pb_height='350'>
             <cms:embed 'pb_mods/content/embed/img_cntn_blk.htm' />
        </cms:tile>
        
    </cms:mosaic>
	
	<cms:mosaic name='img_2col_cntn_blk_msc' label='2 Image Top Content Block' body_class='_pb'>
        
        <cms:tile name='img_2col_cntn_blk_til' label='2 Image Top Content Block' _pb_template='content/theme/img_2col_cntn_blk' _pb_height='350'>
             <cms:embed 'pb_mods/content/embed/img_2col_cntn_blk.htm' />
        </cms:tile>
        
    </cms:mosaic>

</cms:template>
<?php COUCH::invoke(); ?>