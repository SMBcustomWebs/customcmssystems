<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Segmented with Scrolling Images' parent='_mod_adv_' icon='puzzle-piece' clonable='0'  order='805' >
    <cms:mosaic name='seg_img_scrl_msc' label='Segemented Section with Scolling Images' body_class='_pb' >
        <cms:tile name='seg_img_scrl_tl' label='Segmented with scrolling images' _pb_template='seg_img_scrl/theme/seg_img_scrl' _pb_height='300' >
            <cms:embed 'pb_mods/seg_img_scrl/embed/seg_img_scrl.htm' /> 
        </cms:tile>
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>