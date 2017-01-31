<?php
/**
 * theme prefix => BH
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


	// theme version is used to register styles and scripts
	if ( function_exists('wp_get_theme') ) :

		$theme_data    = wp_get_theme();
		$theme_version = $theme_data->get('Version');

	else :

		$theme_data    = get_theme_data( trailingslashit(get_stylesheet_directory()).'style.css' );
		$theme_version = $theme_data['Version'];

	endif;
	
	define( 'VERSION', $theme_version );
	
	// other
	define( 'TEMPLATE'   ,	get_bloginfo('template_directory') );
	define( 'HOME'       ,	home_url( '/' ) );
	define( 'CSS_DIR'    ,	TEMPLATE . '/css' );
	define( 'JS_DIR'     ,	TEMPLATE . '/js' );
    define( 'IMAGES_DIR' ,	TEMPLATE . '/images' );
    define( 'FUNCTIONS_DIR' ,  $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/ravdori/functions/' );
	
	// Global variables consts
	define( 'GLBL_WP_NAV_TOP_BOTTOM' , 'wp_nav_top_bottom' ); // Indicates where to show the  1 of X... text in the pagination

    // Google Fonts
    $google_fonts = array (
                             'Asap'	=> 'https://fonts.googleapis.com/css?family=Alef&subset=latin,hebrew',
                          );


?>