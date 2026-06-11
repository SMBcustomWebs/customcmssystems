<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Booking Engine" icon='calendar' clonable='0' order="900" >
    
    <cms:globals>
        <cms:editable type='message' name='tpl_msg' order='1' >
            <h2>Booking Engine :: Dedicated Receiver Page</h2>
            <h4>This non-clonable page catches data submitted from the Booking Utility Bar.</h4>
            <hr><br>
        </cms:editable>

        <cms:pagebuilder name='glb_hmv_hro_pb' label='Page Header (Hero)' skip_custom_fields='1' order='5'>
            <cms:section label='Single View Page Hero' name='glb_hmv_hro_sct' masterpage='blocks/frame/hero.php' mosaic='ccs_hro_block_msc' />
        </cms:pagebuilder>

        <cms:pagebuilder name='glb_hmv_cnt_pb' label='Main Content Builder' skip_custom_fields='1' order='10'>
            <cms:section label='Booking Results Display' name='pb_booking_results' masterpage='blocks/booking_results.php' mosaic='booking_msc' />
        </cms:pagebuilder>

        <cms:pagebuilder name='glb_hmv_xtr_pb' label='Page Extra Content' skip_custom_fields='1' order='15'>
            <cms:section label='Banner Transition' name='glb_hmv_xtr_sct' masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />
        </cms:pagebuilder>
    </cms:globals>

</cms:template>

<cms:embed 'tl_if_pb_emb.html' />
<?php COUCH::invoke(); ?>