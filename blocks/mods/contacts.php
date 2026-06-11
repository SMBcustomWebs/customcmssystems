<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Contact Sections' parent='_mod_frm_' icon='phone' clonable='0'  order='640' >
    <cms:mosaic name='cntct_msc' label='Contact Section' body_class='_pb'>
        <cms:tile name='cntct_map_add_tl' label='2 Col with Basic Form' _pb_template='contact/theme/cntct_map_cntcfrm' _pb_height='300'>
            <cms:embed 'pb_mods/contact/embed/cntct_map_cntcfrm.htm' /> 
        </cms:tile>
        
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>