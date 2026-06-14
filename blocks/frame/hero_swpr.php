<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Stock Inventory:: Page Heros' parent='_frame_' icon='cog' clonable='0' order='320' >
    <cms:mosaic name='ccs_hro_swpr_msc' label='Hero Carouselle Block' body_class='_pb'>
    
        
        <cms:tile name='ccs_hro_swpr_ful_til' label='Hero - Image and Video Carousel - X-Large Height' _pb_template='hero_swpr/theme/hero_swiper-slide' _pb_height='350'>
            <cms:embed 'pb_mods/hero_swpr/embed/hero_format_swpr.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide_2.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide_3.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide_4.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide_5.htm' />
        </cms:tile>
		<cms:tile name='ccs_hro_swpr_haf_til' label='Hero - Image and Video Carousel - Half Height' _pb_template='hero_swpr/theme/hero_swiper-slide' _pb_height='350'>
            <cms:embed 'pb_mods/hero_swpr/embed/hero_format_swpr.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide_2.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide_3.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide_4.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide_5.htm' />
        </cms:tile>
		<cms:tile name='ccs_hro_swpr_sem_til' label='Hero - Image and Video Carousel - Full Height' _pb_template='hero_swpr/theme/hero_swiper-slide' _pb_height='350'>
            <cms:embed 'pb_mods/hero_swpr/embed/hero_format_swpr.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide_2.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide_3.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide_4.htm' />
            <cms:embed 'pb_mods/hero_swpr/embed/hero_swiper-slide_5.htm' />
        </cms:tile>
        
    </cms:mosaic>
</cms:template>
    
<?php COUCH::invoke(); ?>