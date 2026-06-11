<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title="Directory Search Engine" icon='search' clonable='0' order="920" >
    
    <!-- Wrapping in globals to satisfy the tl_if_pb_emb.html renderer -->
    <cms:globals>
        <cms:editable type='message' name='tpl_msg' order='1' >
            <h2>Directory Search Engine :: Dedicated Receiver Page</h2>
            <h4>This non-clonable page catches data submitted from the Directory Utility Bar.</h4>
            <hr><br>
        </cms:editable>

        <!-- 1. Required Hero Region (HMV mapping for non-clonable template) -->
        <cms:pagebuilder name='glb_hmv_hro_pb' label='Page Header (Hero)' skip_custom_fields='1' order='5'>
            <cms:section label='Single View Page Hero' name='glb_hmv_hro_sct' masterpage='blocks/frame/hero.php' mosaic='ccs_hro_block_msc' />
        </cms:pagebuilder>

        <!-- 2. Required Main Content Region -->
        <cms:pagebuilder name='glb_hmv_cnt_pb' label='Main Content Builder' skip_custom_fields='1' order='10'>
            <!-- The specific Directory Results block -->
            <cms:section label='Directory Results Display' name='pb_directory_results' masterpage='blocks/directory_results.php' mosaic='directory_msc' />
        </cms:pagebuilder>

        <!-- 3. Required Extra Content Region -->
        <cms:pagebuilder name='glb_hmv_xtr_pb' label='Page Extra Content' skip_custom_fields='1' order='15'>
            <cms:section label='Banner Transition' name='glb_hmv_xtr_sct' masterpage='blocks/mods/transitions.php' mosaic='trns_msc' />
        </cms:pagebuilder>
    </cms:globals>

</cms:template>

<!-- The universal renderer -->
<cms:embed 'tl_if_pb_emb.html' />

<?php COUCH::invoke(); ?>