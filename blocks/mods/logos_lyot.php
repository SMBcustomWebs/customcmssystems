<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Logos Layout' parent='_mod_gen_' icon='cog' clonable='0'  order='720' >
    <cms:mosaic name='___pg_lgos_msc' label='Logos Layout' body_class='_pb'>
        <cms:tile name='___pg_lgos_hirc_tl' label='Logos with hierarchy' _pb_template='logos_lyot/theme/logos_lyot' _pb_height='300'>
            <cms:embed 'pb_mods/logos_lyot/embed/logos_lyot.htm' /> 
        </cms:tile>
        <cms:tile name='___pg_lgos_scrol_tl' label='Scrolling Logos' _pb_template='logos_lyot/theme/scrol_logos' _pb_height='100' >
            <cms:embed 'pb_mods/logos_lyot/embed/scrol_logos.htm' /> 
        </cms:tile>
        <cms:tile name='___pg_lr_lgos_mid_txt_tl' label='Text in Middle Flanked with Logos on Backgrounds' _pb_template='logos_lyot/theme/lr_lgos_mid_txt' _pb_height='100' >
            <cms:embed 'pb_mods/logos_lyot/embed/lr_lgos_mid_txt.htm' /> 
        </cms:tile>
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>