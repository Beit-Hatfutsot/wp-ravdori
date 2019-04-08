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
function remove_admin_bar() {
	
	// Does the current user can edit posts ( if editor )
	$user_can_edit = get_field( 'acf-user-editor-can-edit' , 'user_' . get_current_user_id() );
	
	
    //if (  (!current_user_can('administrator') && !is_admin())  OR ( !current_user_can('editor') &&  $user_can_edit == true )  ) {
	
	//if ( !current_user_can('editor') &&  $user_can_edit == false  ) 

	
	
	/*Show admin bar for admins and editors*/
	if (  (current_user_can('manage_options')) OR (current_user_can('editor') AND $user_can_edit == true)  ) 
	{
		show_admin_bar( true );
	}
	else 
	{
	    show_admin_bar( false );
	}	
	
	
	
}
add_action('after_setup_theme', 'remove_admin_bar');


// Remove add New in the admin bar
function wpse_260669_remove_new_content(){
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'new-content' );
}
add_action( 'wp_before_admin_bar_render', 'wpse_260669_remove_new_content' );


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






function disable_save( $maybe_empty, $postarr ) {
    
$disable_post_save = $maybe_empty;		
	
		
	// If we are on the front	
if ( !is_admin() ):

		global $wizardSessionManager;

		// Get the mail and user ID
		$email = null;
			
        if ( $wizardSessionManager )
			$email = $wizardSessionManager->getStepData( IWizardStep1Fields::ID );
		
		if ( isset( $email[ IWizardStep1Fields::EMAIL ] ) )
			$email = $email[ IWizardStep1Fields::EMAIL ];
		
		$user_exists = email_exists( $email );
		
		if ( !$user_exists )
			$disable_post_save = true;
		
		
		$args = array(
						'post_type'   => STORY_POST_TYPE,
						'author'      => $user_exists,
						'post_status' => array( 'draft' ),
						'fields'          => 'ids', // Only get post IDs
						'posts_per_page'  => -1
					 );

		$draft_stories 		 = get_posts( $args );  
		$draft_stories_count = count( $draft_stories );
		
		
		$step4Data = $wizardSessionManager->getStepData( IWizardStep4Fields::ID );
		
		$post_id = $postarr['ID'];	
		
			error_log("ST : ");
		error_log( print_r( $step4Data,true ) );
		
        // If there is an ID (it's an existing story) , update the post
        if ( isset( $step4Data[IWizardStep4Fields::POST_ID] ) )
        {
            $post_id =  $step4Data[ IWizardStep4Fields::POST_ID ];
			
			error_log("postis in if : ");
		error_log( print_r( $post_id ,true ) );
        }

		
		
		
		/*
		error_log("post: ");
		error_log( print_r( $draft_stories ,true ) );
		
		error_log("count: ");
		error_log( print_r( $draft_stories_count ,true ) );
	
		
		error_log("arr: ");
		error_log( print_r( $postarr ,true ) );
		
		error_log("arr id: ");
		error_log( print_r( $postarr['ID'] ,true ) );
		*/
		
		if ( $draft_stories_count == 1 AND in_array( $post_id ,$draft_stories ) ):
			$disable_post_save = false;
		else:
			$disable_post_save = true;
		endif;
		

endif;


    return $disable_post_save;
}
//add_filter( 'wp_insert_post_empty_content', 'disable_save', 999999, 2 );

