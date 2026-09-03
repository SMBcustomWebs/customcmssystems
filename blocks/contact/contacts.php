<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Contact Sections' parent='_mod_frm_' icon='phone' clonable='0'  order='640' >
    <cms:mosaic name='cntct_msc' label='Contact Section' body_class='_pb'>

        <cms:tile name='cnt_basic_frm_tl' label='Contact Form - Name, Email, Message'
            _pb_template='contact/theme/cnt_basic_frm' _pb_height='300'>
            <cms:embed 'pb_mods/contact/embed/common/cnt_opts.htm' />
            <cms:embed 'pb_mods/contact/embed/common/cnt_txt.htm' />
            <cms:embed 'pb_mods/contact/embed/common/cnt_frm.htm' />
        </cms:tile>

        <cms:tile name='cnt_email_frm_tl' label='Contact Form - Email Only'
            _pb_template='contact/theme/cnt_email_frm' _pb_height='300'>
            <cms:embed 'pb_mods/contact/embed/common/cnt_opts.htm' />
            <cms:embed 'pb_mods/contact/embed/common/cnt_txt.htm' />
            <cms:embed 'pb_mods/contact/embed/common/cnt_frm.htm' />
        </cms:tile>

    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>
