<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Stock Inventory:: Navigation' parent='_frame_' icon='cog' clonable='0'  order='200' >
    <cms:mosaic name='ccs_nav_msc' label='Site Navigation Menu' body_class='_pb'>
        
        <cms:tile name='ccs_nav_mdd_std_tl' label='Navigation Bar Standard Multiple Dropdown' _pb_template='pg_frame/nav/theme/nav_mdd' _pb_height='50'>
            <cms:embed 'pb_mods/pg_frame/nav/embed/nav_mdd.htm' />
        </cms:tile>
        
        <cms:tile name='ccs_nav_sdd_std_tl' label='Navigation Bar Standard Single Dropdown' _pb_template='pg_frame/nav/theme/nav_sdd' _pb_height='50'>
            <cms:embed 'pb_mods/pg_frame/nav/embed/nav_sdd.htm' />
        </cms:tile>
        
        <cms:tile name='ccs_nav_dd_xtr_tl' label='Navigation Bar With Image / Video' _pb_template='pg_frame/nav/theme/nav_xtr' _pb_height='50'>
            <cms:embed 'pb_mods/pg_frame/nav/embed/nav_xtr.htm' />
        </cms:tile>
        
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>