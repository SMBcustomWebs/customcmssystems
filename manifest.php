<?php require_once( 'ccs_dash/cms.php' ); ?>
<cms:template title='App Manifest' hidden='1' parent='_donottouch_' />
<cms:content_type 'application/json' />
<cms:set site_favicon="<cms:get_field 'ccs_gl_site_favicon' masterpage='globals.php' />" />
{
    "name": "<cms:get_field 'ccs_gl_site_name' masterpage='globals.php' />",
    "icons": [
        <cms:if site_favicon>
        {
            "src": "<cms:thumbnail site_favicon width='192' height='192' crop='1' />",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "<cms:thumbnail site_favicon width='512' height='512' crop='1' />",
            "sizes": "512x512",
            "type": "image/png"
        }
        </cms:if>
    ],
    "theme_color": "#ffffff",
    "background_color": "#ffffff",
    "display": "standalone"
}
<?php COUCH::invoke(); ?>