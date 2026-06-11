<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Travel Search Engine" icon='globe' clonable='0' order="910" >
    
    <cms:globals>
        <cms:editable type='message' name='tpl_msg' order='1' >
            <h2>Travel Search Engine :: Dedicated Receiver Page</h2>
            <h4>This non-clonable page catches data submitted from the Travel Search Utility Bar.</h4>
            <hr><br>
        </cms:editable>

        <cms:pagebuilder name='glb_sng_hro_pb' label='Page Header (Hero)' skip_custom_fields='1' order='5'>
            <cms:section label='Single View Page Hero' name='glb_sng_hro_sct' masterpage='blocks/frame/hero.php' mosaic='ccs_hro_block_msc' />
        </cms:pagebuilder>

        <cms:pagebuilder name='glb_sng_cnt_pb' label='Main Content Builder' skip_custom_fields='1' order='10'>
            <cms:section label='Travel Results Display' name='pb_travel_results' masterpage='blocks/travel_results.php' mosaic='travel_msc' />
        </cms:pagebuilder>

        <cms:pagebuilder name='glb_sng_xtr_pb' label='Page Extra Content' skip_custom_fields='1' order='15'>
            <cms:section label='Banner Transition' name='glb_sng_xtr_sct' masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />
        </cms:pagebuilder>
    </cms:globals>

</cms:template>

<cms:embed 'tl_if_pb_emb.html' />

<?php COUCH::invoke(); ?>