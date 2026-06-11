<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Stock Inventory:: Utility Bar' parent='_frame_' icon='cog' clonable='0'  order='100' >
    
    <cms:mosaic name='ccs_utlbar_msc' label='Utility Information Bar' body_class='_pb'>
        
        <cms:tile name='ccs_ubr_ctct_tl' label='Contact Info Only' _pb_template='pg_frame/utlbar/theme/ubr_ctct' _pb_height='70'>
            <cms:embed 'pb_mods/pg_frame/utlbar/embed/ubr_ctct.htm' />
        </cms:tile>
        
        <cms:tile name='ccs_ubr_scal_tl' label='Social Links' _pb_template='pg_frame/utlbar/theme/ubr_scal' _pb_height='70'>
            <cms:embed 'pb_mods/pg_frame/utlbar/embed/ubr_scal.htm' />
        </cms:tile>
        
        <cms:tile name='ccs_ubr_menu_tl' label='Contact and Menu Items' _pb_template='pg_frame/utlbar/theme/ubr_menu' _pb_height='70'>
            <cms:embed 'pb_mods/pg_frame/utlbar/embed/ubr_menu.htm' />
        </cms:tile>
        
        <cms:tile name='ccs_ubr_shop_tl' label='Shop Bar with Cart' _pb_template='pg_frame/utlbar/theme/ubr_shop' _pb_height='70'>
            <cms:embed 'pb_mods/pg_frame/utlbar/embed/ubr_shop.htm' />
        </cms:tile>
        
        <cms:tile name='ccs_ubr_sntnc_tl' label='Text Phrase' _pb_template='pg_frame/utlbar/theme/ubr_sentnc' _pb_height='70'>
            <cms:embed 'pb_mods/pg_frame/utlbar/embed/ubr_sentnc.htm' />
        </cms:tile>
        
        <cms:tile name='ccs_ubr_booking_tl' label='Booking Engine' _pb_template='pg_frame/utlbar/theme/ubr_booking' _pb_height='70'>
            <cms:embed 'pb_mods/pg_frame/utlbar/embed/ubr_booking.htm' />
        </cms:tile>
        
        <cms:tile name='ccs_ubr_travel_tl' label='Travel Search' _pb_template='pg_frame/utlbar/theme/ubr_travel' _pb_height='70'>
            <cms:embed 'pb_mods/pg_frame/utlbar/embed/ubr_travel.htm' />
        </cms:tile>
        
		<cms:tile name='ccs_ubr_directory_tl' label='Directory Search' _pb_template='pg_frame/utlbar/theme/ubr_directory' _pb_height='70'>
            <cms:embed 'pb_mods/pg_frame/utlbar/embed/ubr_directory.htm' />
        </cms:tile>
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>