<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Page Transitions' parent='_mod_bnr_' icon='puzzle-piece' clonable='0'  order='600' >
    <cms:mosaic name='trns_msc' label='Page Transitions' body_class='_pb'>
        <cms:tile name='trns_img_smp_tl' label='Transition Simple- Color, Image, Text, Link' _pb_template='transns/theme/trns_img_smp' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/common/trns_opts.htm' />
            <cms:embed 'pb_mods/transns/embed/common/trns_bg-clr.htm' /> 
            <cms:embed 'pb_mods/transns/embed/common/trns_bg-img.htm' />
            <cms:embed 'pb_mods/transns/embed/common/trns_ovrly.htm' />
            <cms:embed 'pb_mods/transns/embed/common/trns_txt.htm' /> 
            <cms:embed 'pb_mods/transns/embed/common/trns_btn.htm' />
            <cms:embed 'pb_mods/transns/embed/common/trns_vid.htm' />
        </cms:tile>
        <cms:tile name='trnsadv_lnks' label='Transition with Multiple Links' _pb_template='transns/theme/trnsadv_lnks' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_img.htm' /> 
            <cms:embed 'pb_mods/transns/embed/trns_img_smp.htm' />
            <cms:embed 'pb_mods/transns/embed/trnsmp_c2a.htm' />
            <cms:embed 'pb_mods/transns/embed/trnsadv_lnks.htm' />
        </cms:tile>
        <cms:tile name='trns_img_adv_tl' label='Transition - Advanced - Forms columns more' _pb_template='transns/theme/trns_img_adv' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_img_adv.htm' />
        </cms:tile>
        <cms:tile name='trns_img_txt_2col_menu_tl' label='Transition - Text with Stacked Menu links' _pb_template='transns/theme/trns_img_txt_2col_menu' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_img_txt_2col_menu.htm' />
        </cms:tile>
        <cms:tile name='trns_img_txt_colg_tl' label='Transition - Image Text Collage' _pb_template='transns/theme/trns_img_txt_colg' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_img_txt_colg.htm' />
        </cms:tile>
        <cms:tile name='trns_img_txt_quot_tl' label='Transition - Single Paragraph - Quote' _pb_template='transns/theme/trns_txt_quot' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_txt_quot.htm' />
        </cms:tile>
        <cms:tile name='trns_txt_hdr_3hglt_tl' label='Transition - Header - 3 Highlights' _pb_template='transns/theme/trns_txt_hdr_3hglt' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_txt_hdr_3hglt.htm' />
        </cms:tile>
        <cms:tile name='trns_lnks_form_tl' label='Transition - Links with Single-Line Form' _pb_template='transns/theme/trns_lnks_form' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_lnks_form.htm' />
        </cms:tile>
        <cms:tile name='trns_lnks_smp_wicon_tl' label='Transition - Icon Leading Simple Link' _pb_template='transns/theme/trns_lnks_smp_wicon' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_lnks_smp_wicon.htm' />
        </cms:tile>
        <cms:tile name='trns_lnks_smp_ttl_tl' label='Transition - Heading - Simple line - link' _pb_template='transns/theme/trns_lnks_smp_ttl' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_lnks_smp_ttl.htm' />
        </cms:tile>
        <cms:tile name='trns_txt_imgs_tl' label='Transition - 50-50 split - Text and Image Collage' _pb_template='transns/theme/trns_txt_imgs' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_txt_imgs.htm' />
        </cms:tile>
        <cms:tile name='trns_txt_imgs_tl' label='Transition - 3 Columns, mid column 2 rows, multi link buttons' _pb_template='transns/theme/trns_3col_mid_2rw_mlt_lnk' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_3col_mid_2rw_mlt_lnk.htm' />
        </cms:tile>
        <cms:tile name='trns_5050_ckbd_img_mnu_tl' label='Transition - 50-50 Checkerboard Menu with Image' _pb_template='transns/theme/trns_5050_ckbd_img_mnu' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_5050_ckbd_img_mnu.htm' />
        </cms:tile>
        <cms:tile name='trns_sng_img_para_tl' label='Transition - Single Image with Paragraph' _pb_template='transns/theme/trns_sng_img_para' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_sng_img_para.htm' />
        </cms:tile>
        <cms:tile name='trns_lr_lists_mid_imgswp_tl' label='Transition - Image Swiper Flanked by Lists' _pb_template='transns/theme/trns_lr_lists_mid_imgswp' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_lr_lists_mid_imgswp.htm' />
        </cms:tile>
        <cms:tile name='trns_hdg_lnks_img_tl' label='Transition - Title, Buttons, Image - Stacked' _pb_template='transns/theme/trns_hdg_lnks_img' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_hdg_lnks_img.htm' />
        </cms:tile>
        <cms:tile name='trns_50splt_chkbd_txt_img_tl' label='Transition - 2 Columns - Image Top, Text - Text Top, Image' _pb_template='transns/theme/trns_50splt_chkbd_txt_img' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_50splt_chkbd_txt_img.htm' />
        </cms:tile>
        <cms:tile name='trns_3col_mid_blnk_tl' label='Transition - 3 col, Mid Empty, Sides Aligned Outward' _pb_template='transns/theme/trns_3col_mid_blnk_img' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_3col_mid_blnk_img.htm' />
        </cms:tile>
        <cms:tile name='trns_4_hglts_icnmnd_tl' label='Transition - 4 Highlights With Iconsmind Icons' _pb_template='transns/theme/trns_4_hglts_icnmnd' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_4_hglts_icnmnd.htm' />
        </cms:tile>
    </cms:mosaic>
    <cms:mosaic name='trns_cpx' label='Page Transitions Multi-Function' body_class='_pb'>
        <cms:tile name='trns_img_hvr_grid_tl' label='Transition - Image Grid Hover to Change' _pb_template='transns/theme/trns_img_hvr_grid' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_img_hvr_grid.htm' />
        </cms:tile>
        <cms:tile name='trns_7525_ckbd_img_txt_tl' label='Transition - 75-25 Checkerboard Image-Text' _pb_template='transns/theme/trns_7525_ckbd_img_txt' _pb_height='300'>
            <cms:embed 'pb_mods/transns/embed/trns_7525_ckbd_img_txt.htm' />
        </cms:tile>

    </cms:mosaic>

</cms:template>
<?php COUCH::invoke(); ?>