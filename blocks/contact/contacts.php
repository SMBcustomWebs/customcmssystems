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

            <cms:ignore>
                NOT IN common/, AND ONLY ON THIS TILE. The mailing-list sign-up
                is the one thing in this block that writes to the database, and
                it belongs to the form that asks for an address and nothing
                else. The basic tile asks for a name and a message, which makes
                it a contact form - and a contact form that adds people to a
                marketing list is what this arrangement exists to prevent.

                Scoping it by embed rather than by a hide list means the basic
                tile cannot offer the option even by mistake: the fields are
                not on it.
            </cms:ignore>
            <cms:embed 'pb_mods/contact/embed/cnt_sub.htm' />
        </cms:tile>

    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>
