<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Stock Inventory:: Site Footers' parent='_frame_' icon='cog' clonable='0'  order='400' >
    <cms:mosaic name='ccs_site_ftr_msc' label='Site Footer' body_class='_pb'>
        <cms:tile name='st_ftr_bsc_tl' label='Site Footer Basic' _pb_template='pg_frame/footer/theme/foot_bsc' _pb_height='90'>
            <cms:embed 'pb_mods/pg_frame/footer/embed/foot_bsc.htm' />
        </cms:tile>
        <cms:tile name='st_ftr_2col_tl' label='Site Footer 2-column Logo | Text Links' _pb_template='pg_frame/footer/theme/foot_links' _pb_height='75'>
            <cms:embed 'pb_mods/pg_frame/footer/embed/foot_links.htm' />
        </cms:tile>
        <cms:tile name='st_ftr_3col_tl' label='Site Footer 3 column Logo | Links | Social' _pb_template='pg_frame/footer/theme/foot_3col' _pb_height='350'>
            <cms:embed 'pb_mods/pg_frame/footer/embed/foot_3col.htm' />
        </cms:tile>
        <cms:tile name='st_ftr_scal_frm_tl' label='Site Footer 2 column with Form, social, app | 3-column Links' _pb_template='pg_frame/footer/theme/foot_2col_form-scal' _pb_height='350'>
            <cms:embed 'pb_mods/pg_frame/footer/embed/foot_2col_form-scal.htm' />
        </cms:tile>
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>