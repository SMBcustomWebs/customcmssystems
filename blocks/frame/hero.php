<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Stock Inventory:: Page Heros' parent='_frame_' icon='cog' clonable='0' order='300' >
    <cms:mosaic name='ccs_hro_msc' label='Hero Block' body_class='_pb'>
        
        <cms:tile name='ccs_hro_single_img_tile' label='Hero - Single Image Background' _pb_template='hero/theme/hero_single_img' _pb_height='350'>
			<cms:embed 'pb_mods/hero/embed/hero_format_single.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_bg-clr.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_bg-img.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_ovrly-clr.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_txtblk.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_txtblk_txt.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_txtblk_img.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_txtblk_form.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_txtblk_bg.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_cta.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_modal-add.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_btm_rail.htm' />
        </cms:tile>
        
        <cms:tile name='ccs_hro_single_vid_tile' label='Hero - Single Video Background' _pb_template='hero/theme/hero_single_vid' _pb_height='350'>
			<cms:embed 'pb_mods/hero/embed/hero_format_single.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_bg-vid.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_bg-clr.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_bg-img.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_ovrly-clr.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_txtblk.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_txtblk_txt.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_txtblk_img.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_txtblk_form.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_txtblk_bg.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_cta.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_modal-add.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_btm_rail.htm' />
             

        </cms:tile>
        
        <cms:tile name='ccs_hro_swiper_img_tile' label='Hero - Image and Video Carousel' _pb_template='hero/theme/hero_swiper-slide' _pb_height='350'>
            <cms:embed 'pb_mods/hero/embed/hero_format_swpr.htm' />
            <cms:embed 'pb_mods/hero/embed/hero_swiper-slide.htm' />
            <cms:embed 'pb_mods/hero/embed/hero_swiper-slide_2.htm' />
            <cms:embed 'pb_mods/hero/embed/hero_swiper-slide_3.htm' />
            <cms:embed 'pb_mods/hero/embed/hero_swiper-slide_4.htm' />
            <cms:embed 'pb_mods/hero/embed/hero_swiper-slide_5.htm' />
        </cms:tile>

        <cms:tile name='ccs_hro_split_tile' label='Hero - Split Sides' _pb_template='hero/theme/hero_split' _pb_height='350'>
			<cms:embed 'pb_mods/hero/embed/hero_format_split.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_split_col_1.htm' />
			<cms:embed 'pb_mods/hero/embed/hero_split_col_2.htm' />
			
			
            </cms:tile>
        
        <cms:tile name='ccs_hro_offset_tile' label='Hero - Single Image with Offset Element' _pb_template='' _pb_height='350'>
            </cms:tile>
        
        <cms:tile name='ccs_hro_inst_img' label='w/ Small Inset Image and Title' _pb_template='hero/theme/hero_inst_img' _pb_height='350'>
            <cms:embed 'pb_mods/hero/embed/hero_inst_img.htm' />
        </cms:tile>
        
    </cms:mosaic>
</cms:template>
    
<?php COUCH::invoke(); ?>