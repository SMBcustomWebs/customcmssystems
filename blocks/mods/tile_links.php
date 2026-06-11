<?php require_once( '../../ccs_dash/cms.php' ); ?>
<cms:template title='Tiles with Links' parent='_mod_lnk_' icon='cog' clonable='0'  order='740' >
    <cms:mosaic name='___pg_tile_link_msc' label='Tiles With Links' body_class='_pb'>
        <cms:tile name='___pg_tile_link_3_tl' label='3 Page Tiles with Link Buttons' _pb_template='tile_link/theme/tile_ttl_out_3' _pb_height='300'>
            <cms:embed 'pb_mods/tile_link/embed/tile_ttl_out_3.htm' /> 
        </cms:tile>
        <cms:tile name='___pg_tile_link_imgs_nogtr_ins_tl' label='Images with links, no gutter Title inside' _pb_template='tile_link/theme/tile_ttl_in_imgs_nogtr' _pb_height='300'>
            <cms:embed 'pb_mods/tile_link/embed/tile_ttl_in_imgs_nogtr.htm' /> 
        </cms:tile>
        <cms:tile name='___pg_tile_link_img_wmain_tl' label='Tiles floatover Title, with Main Image' _pb_template='tile_link/theme/tiles_img_w_main' _pb_height='300' >
            <cms:embed 'pb_mods/tile_link/embed/tiles_img_w_main.htm' /> 
        </cms:tile>
        <cms:tile name='___pg_tile_link_art_wauth_tl' label='Image, Text  Link to Authored Article' _pb_template='tile_link/theme/pg_tile_link_art_wauth' _pb_height='300' >
            <cms:embed 'pb_mods/tile_link/embed/pg_tile_link_art_wauth.htm' /> 
        </cms:tile>
        <cms:tile name='___pg_tile_3crd_sng_lnk_tl' label='3 Cards Image, Text 1 Link' _pb_template='tile_link/theme/pg_tile_3crd_sng_lnk' _pb_height='300' >
            <cms:embed 'pb_mods/tile_link/embed/pg_tile_3crd_sng_lnk.htm' /> 
        </cms:tile>
    </cms:mosaic>
</cms:template>
<?php COUCH::invoke(); ?>