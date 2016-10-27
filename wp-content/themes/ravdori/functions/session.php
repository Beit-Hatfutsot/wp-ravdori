<?php
/**
 * Session handlers
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

require_once WIZARD_DIRECTORY   . 'BH_SessionManager.php';

// The session manager
$wizardSessionManager = null;

add_action('init', 'onStartSession', 1);
function onStartSession() {

    global $wizardSessionManager;

    $wizardSessionManager = BH_SessionManager::getInstance();

}



add_action('wp_logout' , 'onEndSession');
add_action('wp_login'  , 'onEndSession');
function onEndSession() {}


// Log in the user to the system
function logUserIn( $userId )
{
    global $current_user;

    // Set the global user object
    $current_user = get_user_by( 'id', $userId );

    // set the WP login cookie
    $secure_cookie = is_ssl() ? true : false;
    wp_set_auth_cookie( $userId , true, $secure_cookie );

}


// Remove the admin bar for Adult user
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}


// Disable an adult user from accessing the back office
//add_action( 'admin_init', 'admin_area_for_manage_options_only');
function admin_area_for_manage_options_only() {

    if( defined('DOING_AJAX') && DOING_AJAX ) {
        //Allow ajax calls
        return;
    }


    if( ! current_user_can( "manage_options" ) ) {
        //Redirect to main page if the user has no "manage_options" capability
        wp_redirect( get_site_url( ) );
        exit();
    }

}


function clear_session_on_logout() {

     global $wizardSessionManager;
     $wizardSessionManager->destroy();
}
add_action('wp_logout', 'clear_session_on_logout');

