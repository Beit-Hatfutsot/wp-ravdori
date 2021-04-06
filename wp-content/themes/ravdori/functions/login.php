<?php
/**
 * Login screen functions
 *
 * @author		Nir Goldberg
 * @package		functions
 * @version		1.3.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * bh_login_screen
 *
 * This function tweaks login screen
 *
 * @param	N/A
 * @return	N/A
 */
function bh_login_screen() {

	wp_enqueue_style( 'admin-login' );

}
add_action( 'login_head', 'bh_login_screen' );

/**
 * bh_login_logo_url
 *
 * This function tweaks login header URL
 *
 * @param	N/A
 * @return	(string)
 */
function bh_login_logo_url() {

	// return
	return HOME;

}
add_filter( 'login_headerurl', 'bh_login_logo_url' );

/**
 * bh_login_logo_url_title
 *
 * This function tweaks login header title
 *
 * @param	N/A
 * @return	(string)
 */
function bh_login_logo_url_title() {

	// return
	return get_bloginfo( 'name' );

}
add_filter( 'login_headertitle', 'bh_login_logo_url_title' );