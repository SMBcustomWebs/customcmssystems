<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Content Pages 3 col w left verticle' parent='_mod_adv_' icon='script' clonable='0'  order='540' >
    <cms:mosaic name='cntnt_3col_l_vert_msc' label='Content Pages 3 col left text vert' body_class='_pb'>
        <cms:tile name='cntnt_3col_l_vert_tl' label='Content Layout 3col_l_vert' _pb_template='content/3col_l_vert/theme/3col_l_vert' _pb_height='300'>
            <cms:embed 'pb_mods/content/3col_l_vert/embed/3col_l_vert.htm' /> 
        </cms:tile>
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>