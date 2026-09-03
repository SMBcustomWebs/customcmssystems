<?php
if ( !defined('K_COUCH_DIR') ) die(); // cannot be loaded directly

require_once( K_COUCH_DIR.'addons/cart/cart.php' );
require_once( K_COUCH_DIR.'addons/csv/csv.php' );
require_once( K_COUCH_DIR.'addons/inline/inline.php' );
require_once( K_COUCH_DIR.'addons/extended/extended-folders.php' );
//require_once( K_COUCH_DIR.'addons/extended/extended-comments.php' );
require_once( K_COUCH_DIR.'addons/extended/extended-users.php' );
require_once( K_COUCH_DIR.'addons/routes/routes.php' );
require_once( K_COUCH_DIR.'addons/jcropthumb/jcropthumb.php' );
require_once( K_COUCH_DIR.'addons/page-builder/page-builder.php' );
require_once( K_COUCH_DIR.'addons/color-picker/color-picker.php' );
require_once( K_COUCH_DIR.'addons/bootstrap-grid/bootstrap-grid.php' );
require_once( K_COUCH_DIR.'addons/recaptcha/recaptcha.php' );
//require_once( K_COUCH_DIR.'addons/sub-templates/sub-templates.php' );



// Grouping Admin Sidebar Elements
if( defined('K_ADMIN') ){
    $FUNCS->add_event_listener( 'register_admin_menuitems', 'my_register_admin_menuitems' );

    function my_register_admin_menuitems(){
        global $FUNCS;      
        $FUNCS->register_admin_menuitem( array('name'=>'_site_', 'title'=>'Site Pages', 'is_header'=>'1', 'weight'=>'100') );
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_bnr_', 'title'=>'Banner Modules', 'is_header'=>'1', 'weight'=>'150') );
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_lst_', 'title'=>'List Mods', 'is_header'=>'1', 'weight'=>'200') );
		$FUNCS->register_admin_menuitem( array('name'=>'_mod_prt_', 'title'=>'Portfolio Mods', 'is_header'=>'1', 'weight'=>'225') );
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_mnl_', 'title'=>'Manual Entry Mods', 'is_header'=>'1', 'weight'=>'250') );
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_glr_', 'title'=>'Photo Gallery Mods', 'is_header'=>'1', 'weight'=>'300') );
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_cmp_', 'title'=>'Mixed Sections Mod', 'is_header'=>'1', 'weight'=>'400') );
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_frm_', 'title'=>'Build Form Mods', 'is_header'=>'1', 'weight'=>'550') );
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_adv_', 'title'=>'Advanced Mod', 'is_header'=>'1', 'weight'=>'600') );
        $FUNCS->register_admin_menuitem( array('name'=>'_frame_', 'title'=>'Headers &amp; Footers', 'is_header'=>'1', 'weight'=>'700') );
        $FUNCS->register_admin_menuitem( array('name'=>'_stock_', 'title'=>'Stock Inventory', 'is_header'=>'1', 'weight'=>'750') );
        $FUNCS->register_admin_menuitem( array('name'=>'_global_', 'title'=>'Global Site Settings', 'is_header'=>'1', 'weight'=>'800') );
		
        $FUNCS->register_admin_menuitem( array('name'=>'_donottouch_', 'title'=>'DO NOT TOUCH', 'is_header'=>'1', 'weight'=>'900') );

    }
}
// end Grouping Admin Sidebar Element



// JIT fields
{
    // Tag <cms:jit_fields>
    $FUNCS->register_tag( 'jit_fields', function($params, $node){
        global $CTX, $FUNCS, $AUTH;

        if( $AUTH->user->access_level < K_ACCESS_LEVEL_SUPER_ADMIN ){ return; }

        // get the 'config' object supplied by 'cms:config_form_view' tag
        $arr_config = &$CTX->get_object( '__config', 'config_form_view' );
        if( !is_array($arr_config) ){ return; }

        if( count($node->children) ){
            $content = $node->children;
        }
        $arr_config['jit_fields'] = $content;
    });

    if( defined('K_ADMIN') ){
        $FUNCS->add_event_listener( 'alter_pages_form_default_fields', function(&$arr_default_fields, &$obj){
            global $PAGE, $FUNCS, $CTX, $DB;

            if( !(is_array($obj->arr_config) && array_key_exists('jit_fields', $obj->arr_config) && is_array($obj->arr_config['jit_fields'])) ){ return; }

            // replace cms:config_form_view tag
            $arr_config = array( 'arr_fields'=>array(), 'js'=>'', 'css'=>'', 'html'=>'', 'params'=>'' );
            $listener_config_form_view = function($tag_name, &$params, &$node, &$html) use(&$arr_config){
                global $FUNCS, $CTX;

                $CTX->set_object( '__config', $arr_config );

                // invoke child tags
                foreach( $node->children as $child ){
                    $child->get_HTML();
                }
                return 1; // skip original tag code
            };
            $FUNCS->add_event_listener( 'alter_tag_config_form_view_execute', $listener_config_form_view );

            $html = '<cms:config_form_view></cms:config_form_view>';
            $parser = new KParser( $html );
            $dom = &$parser->get_DOM();
            $dom->children[1]->children = $obj->arr_config['jit_fields'];
            foreach( $dom->children as $child ){
                $child->get_HTML();
            }

            $FUNCS->remove_event_listener( 'alter_tag_config_form_view_execute', $listener_config_form_view );

            // set fields if any
            if( is_array($arr_config['arr_fields']) && count($arr_config['arr_fields']) ){
                if( !is_array($obj->arr_config) ){ $obj->arr_config = array(); }
                if( !is_array($obj->arr_config['arr_fields']) ){ $obj->arr_config['arr_fields'] = array(); }

                foreach( $arr_config['arr_fields'] as $k=>$v ){
                    $obj->arr_config['arr_fields'][$k] = $v;
                }
            }
        });
    }
}// end JIT fields


// validate  dropdown chosen (set on hidden dummy field) https://www.couchcms.com/forum/viewtopic.php?f=8&t=11558
function menu_check( $field, $args ){
    $f = $field->page->_fields['k_page_folder_id'];
    $fid = $f->get_data();
    if( $fid=='-1' ){
        $f->err_msg='Please select a menu category (folder) for page'; // set error on folder field
        return KFuncs::raise_error( '' ); // returning an error to fail page save 
    }
}


// validator for core folders dropdown (set on a dummy hidden field)
function bmenu_check( $field, $args ){
	if( !isset($field->page->_fields['ccs_abt_ctnt_sbm_opt']) ) { return; }
    $f = $field->page->_fields['ccs_abt_ctnt_sbm_opt'];
    $fid = $f->get_data();
    if( $fid=='1' ){
        $f->err_msg='Please Select a Menu Category'; // set error on folder field
        return KFuncs::raise_error( '' ); // returning an error to fail page save 
    }
}



//For Page Builder paths

$FUNCS->add_event_listener( 'override_renderables', function(){
    global $FUNCS;

    $FUNCS->override_render( 'pb_wrapper', array('template_path'=>K_SITE_DIR . K_SNIPPETS_DIR .'/pb_mods/misc/theme/') );
    $FUNCS->override_render( 'pb_tile', array('template_path'=>K_SITE_DIR . K_SNIPPETS_DIR .'/pb_mods/', 'template_ctx_setter'=>array('MyPB', '_render_pb_tile')) );
});

class MyPB{
    static function _render_pb_tile(){
        global $FUNCS, $CTX;

        $tpl_type = $CTX->get( 'k_template_type' );
        if( $tpl_type == 'tile' ){

            $tpl = $CTX->get( 'k_template__pb_template' ); // the template to render

            $tpl = trim( $tpl );
            if( $tpl!='' ){
                return array( $tpl );
            }
        }
    }
} // end class MyPB



if( defined('K_ADMIN') ){ // if admin-panel being displayed ..

    // 1. Add a 'Save and back' button to form view
    $my_target_action = 'page'; // available targets on the form are - toolbar, filter, page and extended

    $FUNCS->add_event_listener( 'alter_pages_form_'.$my_target_action.'_actions', 'my_add_button' );
    function my_add_button( &$arr_actions, &$obj ){
        global $FUNCS, $PAGE;

        $route = $FUNCS->current_route;
        if( is_object($route) && $route->module=='pages' ){

            if( $PAGE->tpl_is_clonable ){ // if template is clonable, add the new button to form

                $arr_actions['btn_save_and_back'] =
                    array(
                        'title'=>'Save and go back',
                        'onclick'=>array(
                            "$('#btn_submit').trigger('my_submit');",
                            "var form = $('#".$obj->form_name."');",
                            "form.find('#k_custom_action').val('save_and_back');",
                            "form.submit();",
                            "return false;",
                        ),
                        'icon'=>'collapse-left',
                        'weight'=>15,
                    );
            }
        }
    }

    // 2. When the button above submits the form, take custom action (go back to list-view in this case)
    $FUNCS->add_event_listener( 'pages_form_custom_action', 'my_add_button_action' );
    function my_add_button_action( $custom_action, &$redirect_dest, &$pg, $_mode ){
        global $FUNCS, $PAGE;

        $route = $FUNCS->current_route;
        if( is_object($route) && $route->module=='pages' ){

            if( $custom_action === 'save_and_back' ){
                // set the new redirect destination (the list view with all querystring parameters) ..

                if( $PAGE->tpl_is_clonable ){
                    $link = $FUNCS->generate_route( $PAGE->tpl_name, 'list_view' );
                    $link = $FUNCS->get_qs_link( $link );

                    $redirect_dest = $link;
                }
            }
        }
    }
}



//To check Session vars
//$FUNCS->add_event_listener( 'add_render_vars', function () {
 //   global $CTX;
  //  $CTX->set( 'k__session', $_SESSION, 'global' );
//});
//add this to page
//<cms:php>
 //   echo '<div class="alert alert-warning mt-3"><pre>';
 //   print_r($_SESSION);
 //   echo '</pre></div>';
//</cms:php>


// ---------------------------------------------------------------------
// Outgoing mail - RFC-compliant headers and a real envelope sender.
//
// KFuncs::send_mail() joins headers with a bare "\n" on Linux and calls
// mail() with four arguments. On this host that produces two faults,
// both confirmed against Exim's log and the received headers of a
// message that actually arrived:
//
//   1. Exim folds bare-LF headers into the preceding one, so "From:"
//      swallows MIME-Version and Content-Type and the message ships
//      with no valid content type. Exim logs
//      "Content-Type: may not follow <address>" on every send.
//
//   2. With no -f, the envelope sender falls back to the cPanel user
//      (jznrjgte@sh00198.hostgator.com). Exim then DKIM-signs with the
//      account's primary domain rather than the sending domain, and
//      nothing aligns with the From: header.
//
// Gmail discarded messages carrying either fault, silently, with no
// bounce. Corrected, Gmail reports dkim=pass and dmarc=pass and delivers.
// Verified 2026-08-30. HostGator case I-26601376.
//
// Returning 1 tells KFuncs::send_mail() the send was handled, so it
// returns our result without calling mail() itself.
//
// NOTE: the phpmailer addon registers on this same event. If it is ever
// enabled, remove this block or phpmailer will never run.
// ---------------------------------------------------------------------
$FUNCS->add_event_listener( 'alter_send_mail',
    function( &$from, &$to, &$subject, &$text, &$headers, &$result, &$arr_config, $debug ){
        global $FUNCS;

        $sep = "\r\n";

        $h = '';
        if( is_array($headers) ){
            foreach( $headers as $k=>$v ){
                if( $k=='Sender' ) continue;                // core skips this too
                $h .= $FUNCS->_rsc($k) . ': ' . $FUNCS->_rsc($v) . $sep;
            }
            if( $h != '' ){
                $h = $sep . substr( $h, 0, -strlen($sep) );  // lead with one, drop the trailing one
            }
        }

        $c_from    = $FUNCS->_rsc( $from );
        $c_to      = $FUNCS->_rsc( $to );
        $c_subject = $FUNCS->_rsc( $subject );

        // Envelope sender, passed only when From is a bare address, so a
        // display-name or malformed From can never reach the shell.
        $params = preg_match( '/^[^@\s<>"\';|&$]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/', $c_from )
                ? '-f' . $c_from
                : '';

        $result = ( $params !== '' )
                ? @mail( $c_to, $c_subject, $text, 'From: '.$c_from.$h, $params )
                : @mail( $c_to, $c_subject, $text, 'From: '.$c_from.$h );

        return 1;
    }
);


/* ==========================================================================
   THEME PALETTES, AND SEEDING THE CUSTOM COLOUR FIELDS FROM THEM

   ccs_theme_palette() is the palette table in PHP. It mirrors the colour
   chain in assets/css/user.php, which is what actually paints the site when
   "Customize Site Colors" is unchecked.

   WHY THE SEEDING HAS TO HAPPEN HERE RATHER THAN IN THE FIELD DEFAULTS.
   The color= attribute on a type='color' editable is a static literal, and
   addons/color-picker/color-picker.php line 67 consults it only while the
   field has never been saved:

       $data = strlen( $this->data ) ? $this->data : $this->color;

   The first save of globals.php writes a value into all fourteen colour
   fields, so from then on the default is dead. Seeding from a theme has to
   WRITE the fields, which is what the listener below does.

   THE FIELDS TRACK THE THEME WHILE CUSTOM COLOURS ARE OFF. Every save of
   globals.php with the box unticked writes the current theme's palette into
   the fourteen fields. They are hidden at that point, so nothing is lost - and
   the moment the box IS ticked the group appears already showing the theme's
   colours, because they were stored on the last theme save.

   That is the whole point: the owner picks a theme, saves, and later ticks
   Customize Site Colors to find the theme's palette waiting to be adjusted.
   One save, no seeding step.

   THE GROUP APPEARS WITHOUT A SAVE. not_active on the group is a cms:func with
   ccs_gl_site_custom_color_opt as a declared dependency, and
   conditional-fields.php compiles that into client-side JS - so ticking the box
   reveals the group immediately, showing the values the server rendered into
   those inputs on page load.

   ONCE CUSTOM COLOURS ARE ON, NOTHING TOUCHES THEM. Not a re-save, not a change
   of base theme. "Custom" means custom. To go back to a theme's palette, untick
   the box and save.

   THE OFF->ON SAVE IS ALSO SEEDED, which only matters in one case: changing the
   theme and ticking the box in the same sitting, where the revealed swatches
   still show the previous theme's colours. Seeding on that save corrects them.
   Any swatch touched in that same save is left alone.

   VALUES ARE SIX-DIGIT LOWERCASE HEX. That is what the picker stores at 100%
   opacity (color-picker.php lines 76-91 drop the alpha pair at 255) and what
   its validate() accepts.

   KEEP IN STEP WITH assets/css/user.php. Two copies of one table is how the
   font chain drifted. _tools/check-theme-palette.py diffs them - run it after
   touching either.
   ========================================================================== */

function ccs_theme_palette( $theme ){

    // The head of the chain in user.php: set first, then overridden per theme.
    $p = array(
        'primary'    => '#007aff',
        'secondary'  => '#292b2c',
        'tertiary'   => '#687bd9',
        'quaternary' => '#68c2d9',
        'success'    => '#4cd964',
        'info'       => '#2eb7f5',
        'warning'    => '#ff9500',
        'danger'     => '#ff3b30',
        'light'      => '#fafafa',
        'dark'       => '#0c151a',
        'white'      => '#ffffff',
        'black'      => '#000000',
        'body_clr'   => '#292b2c',
        'body_bg'    => '#ffffff',
    );

    switch( strtolower( trim( (string) $theme ) ) ){

        case 'primavera':
            $p['primary']='#ff8e9c'; $p['secondary']='#85d3a9';
            $p['tertiary']='#a3d5ff'; $p['quaternary']='#ffe8a1';
            $p['body_bg']='#fff0f5';  $p['body_clr']='#2c3e50';
            break;

        case 'estate':
            $p['primary']='#ff9f1c'; $p['secondary']='#2ec4b6';
            $p['tertiary']='#e71d36'; $p['quaternary']='#ffbf69';
            $p['body_bg']='#f0f8ff';  $p['body_clr']='#011627';
            break;

        case 'autunno':
            $p['primary']='#d95d39'; $p['secondary']='#f0a202';
            $p['tertiary']='#826251'; $p['quaternary']='#a89c94';
            $p['body_bg']='#efe8e0';  $p['body_clr']='#3a2318';
            break;

        case 'inverno':
            $p['primary']='#3a86ff'; $p['secondary']='#8ecae6';
            $p['tertiary']='#4a4e69'; $p['quaternary']='#c1d3fe';
            $p['body_bg']='#e2eaf2';  $p['body_clr']='#1a252c';
            break;

        case 'scuro':
            $p['primary']='#00adb5'; $p['secondary']='#393e46';
            $p['tertiary']='#5c6b73'; $p['quaternary']='#9db2bf';
            $p['success']='#2ecc71'; $p['info']='#3498db';
            $p['warning']='#f1c40f'; $p['danger']='#e74c3c';
            $p['light']='#eaeaea';   $p['dark']='#15181c';
            $p['body_bg']='#222831'; $p['body_clr']='#eeeeee';
            break;

        case 'notte':
            $p['primary']='#66fcf1'; $p['secondary']='#45a29e';
            $p['tertiary']='#7b2cbf'; $p['quaternary']='#e0aaff';
            $p['success']='#00ff7f'; $p['info']='#00bfff';
            $p['warning']='#ffd700'; $p['danger']='#ff003f';
            $p['light']='#f0f0f0';   $p['dark']='#050505';
            $p['body_bg']='#0b0c10'; $p['body_clr']='#c5c6c7';
            break;

        case 'dark':
            // A colour theme with no palette of its own beyond the page itself.
            $p['body_clr']='#e1e1e1'; $p['body_bg']='#404040';
            break;

        // 'light' and anything unrecognised keep the base set above.
    }

    return $p;
}

$FUNCS->add_event_listener( 'page_prevalidate',
    function( &$fields, &$pg ){

        if( $pg->tpl_name !== 'globals.php' ) return;

        // Index the posted fields by name.
        $by_name = array();
        for( $i = 0; $i < count($fields); $i++ ){
            $by_name[ $fields[$i]->name ] = $i;
        }

        foreach( array( 'ccs_gl_site_custom_color_opt', 'ccs_gl_site_thm_opt' ) as $n ){
            if( !isset($by_name[$n]) ) return;
        }

        // orig_data is the value as it stood before this save (field.php:317).
        // It is null on a field that was not posted - an inactive field never
        // reaches store_posted_changes (field.php:236) - so fall back to the
        // stored value rather than reading null as "was off".
        $prev = function( $f ){
            return is_null( $f->orig_data ) ? $f->get_data() : $f->orig_data;
        };

        $opt       = &$fields[ $by_name['ccs_gl_site_custom_color_opt'] ];
        $on_now    = ( trim( (string) $opt->data ) === '1' );
        $on_before = ( trim( (string) $prev($opt) ) === '1' );

        // Already customising and staying that way: hands off.
        if( $on_now && $on_before ) return;

        $p = ccs_theme_palette( $fields[ $by_name['ccs_gl_site_thm_opt'] ]->data );

        $map = array(
            'ccs_gl_site_primary_cust'    => 'primary',
            'ccs_gl_site_secondary_cust'  => 'secondary',
            'ccs_gl_site_tertiary_cust'   => 'tertiary',
            'ccs_gl_site_quaternary_cust' => 'quaternary',
            'ccs_gl_site_success_cust'    => 'success',
            'ccs_gl_site_info_cust'       => 'info',
            'ccs_gl_site_warning_cust'    => 'warning',
            'ccs_gl_site_danger_cust'     => 'danger',
            'ccs_gl_site_light_cust'      => 'light',
            'ccs_gl_site_dark_cust'       => 'dark',
            'ccs_gl_site_white_cust'      => 'white',
            'ccs_gl_site_black_cust'      => 'black',
            'ccs_gl_site_body_clr_cust'   => 'body_clr',
            'ccs_gl_site_body_bg_cust'    => 'body_bg',
        );

        foreach( $map as $field_name => $key ){
            if( !isset($by_name[$field_name]) ) continue;
            $f = &$fields[ $by_name[$field_name] ];

            // A swatch the owner moved in this same save wins over the theme.
            $before = (string) $prev($f);
            if( strcasecmp( $before, (string) $f->data ) !== 0 ){
                unset( $f );
                continue;
            }

            $f->data = $p[$key];

            // Persistence is driven by ->modified (page.php:1033), not by
            // whether the field was active, so an inactive field written here
            // still reaches the database.
            $f->modified = ( strcasecmp( $before, $f->data ) !== 0 );
            unset( $f );
        }
    }
);
