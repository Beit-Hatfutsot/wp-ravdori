<?php
/**
 * Adjusting the SCHOOLS taxonomy in the backend 
 *
 *
 * @package    functions/backend/backend.php
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function my_enqueue($hook) {
	
    if ( !('edit-tags.php' == $hook OR 'term.php' == $hook OR $_GET['taxonomy'] == 'schools') ) {
        return;
    }

	//wp_enqueue_style('chosen');
	wp_enqueue_style( 'chosen_admin_css'    ,	JS_DIR  . '/chosen/chosen.css');
    wp_enqueue_script('chosen_admin_js'     ,	JS_DIR  . '/chosen/chosen.jquery.js');
	wp_enqueue_script('chosen_admin_loader' ,	JS_DIR  . '/chosen/schools.backend.jquery.js' , array('chosen_admin_js'));
    
}
add_action( 'admin_enqueue_scripts', 'my_enqueue' );





?>