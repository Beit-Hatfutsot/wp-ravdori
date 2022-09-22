<?php
/**
 * Security related functions such as input sanitation
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



function sanitize($input) {

    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        $input  = cleanInput($input);
        $output = $input; // esc_sql($input);

    }
    return $output;
}


function cleanInput($input) {

    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );

    $output = preg_replace($search, '', $input);
    return $output;
}


/**
 * Verify if a given nonce is ok, if not exit the process
 *
 * @param String     $nonce      - Nonce to verify
 * @param String/int $action     - Action name. Should give the context to what is taking place and be the same when the nonce was created
 * @param String     $dieMessage - The message to show in the die() message, default: null
 */
function checkNonce( $nonce , $action  , $dieMessage = null )
{
    if ( !wp_verify_nonce( $nonce ,$action ) )
    {
        die( $dieMessage );
    }
}


/**
 * Make the current logged in user to see in the media only his files
 */
function user_restrict_media_library( $query ) {
	
   $user = wp_get_current_user();
	
    if ( $query['post_type'] !== 'attachment'  ):
	
		if( empty( $user ) OR !in_array( "administrator", (array) $user->roles ) ):
			$query['author'] = $user->ID;
		endif;
		
	else:
error_log("-----------IN SECIONBD----------**");
error_log( print_r(current_user_can( 'add_users' ),true) );
		if( empty( $user ) OR ! current_user_can( 'add_users' ) ):
		error_log("-----------IN third----------**");
			$query['author'] = $user->ID;
		endif;
		
		
	endif;

    return $query;
}
add_filter( 'ajax_query_attachments_args', "user_restrict_media_library" );


function namesSanitization( $username )
{

    $username = wp_strip_all_tags( $username );
    $username = remove_accents( $username );
    // Kill octets
    $username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
    $username = preg_replace( '/[^A-Za-zא-ת ]/', '', $username );
    $username = preg_replace( '/&.+?;/', '', $username ); // Kill entities

    $username = trim( $username );

    sanitize_text_field($username);
    esc_attr($username);
    esc_textarea( stripslashes( $username ) );



    return ($username );
}


function showBackendErrors ( $errors , $fieldID )
{
    global $wizardSessionManager;

    if ( !isset($errors) AND $wizardSessionManager->getStepStatus( $wizardSessionManager->getCurrentStep() ) == IWizardSessionFieldsStatus::NO_ERRORS   )
        return;

    if ( isset($errors[ $fieldID ]) AND isset( $errors[$fieldID][IWizardErrorsFields::IS_VALID]  ) ):
        if ( $errors[ $fieldID ][IWizardErrorsFields::IS_VALID]  == false ):
            echo '<span class="error" for="' . $fieldID . '">';
            echo     $errors[ $fieldID ][ IWizardErrorsFields::MESSAGE ];
            echo '</span>';
        endif;
    endif;

}



// Disable access to the dashboard for non admin users
function allow_admin_area_to_admins_only() {

    if( defined('DOING_AJAX') && DOING_AJAX )
    {
        //Allow ajax calls
        return;
    }

    $user = wp_get_current_user();
	
	$allowed_roles = array('editor', 'administrator');
	
	
    if( empty( $user ) OR  count( array_intersect($allowed_roles, $user->roles ) ) == 0 )
    {
        //Redirect to main page if no user or if the user has no "administrator" or "editor" role assigned
        wp_redirect( get_site_url( ) );
        exit();
    }

}
add_action( 'admin_init', 'allow_admin_area_to_admins_only');
