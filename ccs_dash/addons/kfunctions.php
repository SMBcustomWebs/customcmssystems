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
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_bnr_', 'title'=>'Banner Modules', 'is_header'=>'1', 'weight'=>'100') );
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_lst_', 'title'=>'List Mods', 'is_header'=>'1', 'weight'=>'200') );
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_mnl_', 'title'=>'Manual Entry Mods', 'is_header'=>'1', 'weight'=>'250') );
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_glr_', 'title'=>'Photo Gallery Mods', 'is_header'=>'1', 'weight'=>'300') );
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_cmp_', 'title'=>'Mixed Sections Mod', 'is_header'=>'1', 'weight'=>'400') );
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_frm_', 'title'=>'Build Form Mods', 'is_header'=>'1', 'weight'=>'550') );
        $FUNCS->register_admin_menuitem( array('name'=>'_mod_adv_', 'title'=>'Advanced Mod', 'is_header'=>'1', 'weight'=>'600') );
        $FUNCS->register_admin_menuitem( array('name'=>'_frame_', 'title'=>'Headers &amp; Footers', 'is_header'=>'1', 'weight'=>'700') );
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