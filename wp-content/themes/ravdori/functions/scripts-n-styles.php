<?php
/**
 *  Scripts and styles registration and enqueue
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



/**
 * Registers the scripts and styles for the front-end
 */
function BH_register_scripts_n_styles() {

			BH_register_styles();
			BH_register_scripts();
}
add_action('init', 'BH_register_scripts_n_styles');



/**
 * Registers the scripts and styles for the back-end
 */
function BH_register_scripts_n_styles_admin() {

        BH_register_admin_styles();

        wp_enqueue_style('admin-backend');

        // ACF Styles for the repeater
        wp_enqueue_style(  'acf-global' , 'acf-field-group'  );

        BH_register_admin_scripts();


}
add_action('admin_enqueue_scripts', 'BH_register_scripts_n_styles_admin');



/**
 * Registers the styles for the back-end
 */
function BH_register_admin_styles() {

        wp_register_style( 'admin-backend',		CSS_DIR . '/backend.css' ,	array() , VERSION );
}


/**
 * This function registers the scripts for the back-end
 */
function BH_register_admin_scripts() {

        global $post_type;

        if (  ( ( isset($_GET['post_type']) AND  $_GET['post_type'] == 'story' ) ||
                ( $post_type == 'story' ) )  ||
                ( isset($_GET['page'])   AND $_GET['page']    == DICTIONARY_ADMIN_PAGE_MENU_SLUG ) ||
                ( isset($_GET['page'])   AND $_GET['page']    == QUOTES_ADMIN_PAGE_MENU_SLUG ) ||
              ( ( isset($_GET['action']) AND $_GET['action']  == 'edit' ) )
           )
        {

            wp_register_script('backend_repeater.js', JS_DIR . '/backend_repeater.js', array('jquery'), VERSION, true);
            wp_enqueue_script('backend_repeater.js');
        }
}


/**
 * This function registers the styles for the front-end
 */
function BH_register_styles() {
		
        wp_register_style( 'bootstrap'      ,	CSS_DIR . '/libs/bootstrap.min.css'        ,  array()             ,	VERSION );
		wp_register_style( 'bootstrap-rtl'  ,	CSS_DIR . '/libs/bootstrap-rtl.min.css'    ,  array('bootstrap')  ,	VERSION );
		wp_register_style( 'general'        ,	CSS_DIR . '/general.css'                   ,  array('bootstrap')  ,	VERSION );
        wp_register_style( 'wizard'         ,	CSS_DIR . '/wizard.css'                    ,  array('bootstrap')  ,	VERSION );
        wp_register_style( 'chosen'         ,	JS_DIR  . '/chosen/chosen.css'             ,  array('bootstrap') ,	VERSION );
        wp_register_style( 'toastr'         ,	JS_DIR  . '/toastr/toastr.min.css'         ,  array('bootstrap') ,	VERSION );


}

/** Load all the styles */
function BH_load_theme_styles() {

    wp_enqueue_style('bootstrap-rtl');
    wp_enqueue_style('general');
    wp_enqueue_style('wizard');
    wp_enqueue_style('chosen');
    wp_enqueue_style('toastr');

}

/**
 * This function registers the scripts for the front-end
 */
function BH_register_scripts() {

    //wp_register_script( 'bootstrap'      ,	JS_DIR . '/libs/bootstrap.min.js'             ,	array('jquery')       , VERSION  ,	true );
        wp_register_script( 'jquery'         ,	JS_DIR . '/jquery.js'                         ,	array()               , VERSION  ,	true );
        wp_register_script( 'JCycle2'        ,	JS_DIR . '/jquery.cycle2.min.js'              ,	array(/*'jquery' , 'bootstrap'*/) , VERSION  ,	true );
        wp_register_script( 'JRepeater'      ,	JS_DIR . '/jquery.repeater.min.js'            ,	array(/*'jquery' , 'bootstrap'*/) , VERSION  ,	true );
        wp_register_script( 'chosen'   ,	JS_DIR . '/chosen/chosen.jquery.js'  ,	array(/*'jquery' , 'bootstrap'*/) , VERSION  ,	true );
        wp_register_script( 'toastr'   ,	JS_DIR . '/toastr/toastr.min.js'  ,	array(/*'jquery' , 'bootstrap'*/) , VERSION  ,	true );
        wp_register_script( 'Wizard'         ,	JS_DIR . '/wizard.js'                         ,	array(/*'jquery' , 'bootstrap'*/) , VERSION  ,	true );
        wp_register_script( 'chained'         ,	JS_DIR . '/jquery.chained.min.js'                         ,	array(/*'jquery' , 'bootstrap'*/) , VERSION  ,	true );
        wp_register_script( 'alphanum'         ,	JS_DIR . '/jquery.alphanum.js'                         ,	array(/*'jquery' , 'bootstrap'*/) , VERSION  ,	true );
        wp_register_script( 'select-togglebutton'        ,	JS_DIR . '/select-togglebutton.js'              ,	array(/*'jquery' , 'bootstrap'*/) , VERSION  ,	true );
		wp_register_script( 'running-numbers'        ,	JS_DIR . '/jquery.running-numbers.js'              ,	array(/*'jquery' , 'bootstrap'*/) , VERSION  ,	true );
}

function BH_load_theme_scripts() {

    wp_enqueue_script('jquery');

    // JCycle2 script
    wp_enqueue_script('JCycle2');

    // JRepeater
    wp_enqueue_script('JRepeater');

    // Custom Select
    wp_enqueue_script('chosen');

    // The wizard
    wp_enqueue_script('Wizard');

    wp_enqueue_script('toastr');


    wp_enqueue_script('chained');

    wp_enqueue_script('alphanum');

    wp_enqueue_script('select-togglebutton');

}

// Add media upload support
add_action("admin_enqueue_scripts", "enqueue_media_uploader");
function enqueue_media_uploader() { wp_enqueue_media(); }

?>