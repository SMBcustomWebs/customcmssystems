<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Tabs Section' parent='_mod_adv_' icon='cog' clonable='0'  order='700' >
    <cms:mosaic name='tabs_msc' label='Page Tabs' body_class='_pb'>
        <cms:tile name='___pg_tbs_sched_tl' label='Tabs-Open Text' _pb_template='tabs/theme/pg_tbs_open' _pb_height='300'>
            <cms:embed 'pb_mods/tabs/embed/pg_tbs_open.htm' /> 
        </cms:tile>
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>