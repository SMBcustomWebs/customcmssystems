<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Content Style 1 col Above 2 ' parent='_mod_adv_' icon='script' clonable='0'  order='520' >
    <cms:mosaic name='cntnt_pg_st_3_msc' label='Content Pages' body_class='_pb'>
        <cms:tile name='cntnt_pg_tl3' label='Content Layout 3' _pb_template='content/content3/theme/content3' _pb_height='300'>
            <cms:embed 'pb_mods/content/content3/embed/content3.htm' /> 
        </cms:tile>
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>