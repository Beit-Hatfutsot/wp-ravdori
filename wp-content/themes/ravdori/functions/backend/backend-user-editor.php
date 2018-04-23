<?php
/**
 *  Manange editor viewing and editing privileges based
 *  thier associated districits and viewing capabilities (2 ACF fields)
 * 
 *
 * @package    functions/backend/backend-story-cpt.php
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



 /**
 * Filter the backend stories to show only districits belong to 
 * the editor's selected districits.
 *
 * @param object $query The WP_Query object.
 *
 * @return object The WP_Query object
 */
function posts_for_current_author($query) {
    global $pagenow;
	global $typenow;
	$q_vars = &$query->query_vars;

	
	// If we are in the backend and the current user is editor , and we are on edit.php
    if( $query->is_admin AND current_user_can('editor') 
						 AND $pagenow == 'edit.php' 
						 AND $typenow == STORY_POST_TYPE 
						 AND isset($q_vars['post_type']) 
						 AND $q_vars['post_type'] == STORY_POST_TYPE) 
	{
		
		$user_districts = get_field( 'acf-user-editor-districts' , 'user_' . get_current_user_id() );
		
		if ( $user_districts ) 
		{
			$tax_query = array (
									array(
												'taxonomy' => SCHOOLS_TAXONOMY,
												'field'    => 'term_id',
												'terms'    => $user_districts,
											),
							   );
				$query->set( 'tax_query', $tax_query );	
		}
		else 
		{	
			 // If the user has no associated districits,
			 // return an empty query			 
			 $query->set( 'post__in' , array(0) );	
		}
      
    }
	
	
	
	
    return $query;
}
add_filter( 'pre_get_posts', 'posts_for_current_author' );


/**
 * Registers the filter to handle a public preview.
 *
 * Filter will be set if it's the main query, a preview, a singular page
 * and the current user is editor
 *
 *
 * @param object $query The WP_Query object.
 * @return object The WP_Query object, unchanged.
 */
function show_public_preview( $query ) {
	
	
	if (
		$query->is_admin == false	AND
		$query->is_main_query() 	AND
		$query->is_preview()    	AND
		$query->is_singular()   	AND 
		current_user_can('editor')
	) 
	{
				
		if ( ! headers_sent() ) { nocache_headers(); }

		add_filter( 'posts_results', 'set_post_to_publish' , 10, 2 );
	}

	return $query;
}
add_filter('pre_get_posts', 'show_public_preview');




/**
 * Sets the post status of the first post to publish, so we don't have to do anything
 * *too* hacky to get it to load the preview.
 *
 *
 * @param  array $posts The post to preview.
 * @return array The post that is being previewed.
 */
function set_post_to_publish( $posts ) {
	
	// Remove the filter again, otherwise it will be applied to other queries too.
	remove_filter( 'posts_results', 'set_post_to_publish', 10 );

	if ( empty( $posts ) ) {
		return;
	}

	$post_id = $posts[0]->ID;

	
	// Set post status to publish so that it's visible.
	$posts[0]->post_status = 'publish';

	// Disable comments and pings for this post.
	add_filter( 'comments_open' , '__return_false' );
	add_filter( 'pings_open'    , '__return_false' );


	return $posts;
}



/**
 * Filter on the current_user_can() function.
 * This function is used to explicitly allow editors to edit contributors and other
 * authors posts if they are published or pending.
 *
 * @param array $actions An array of row action links. 
 *				Defaults are 'Edit', 
 *							 'Quick Edit', 
 *							 'Restore', 
 *							 'Trash', 
 *							 'Delete Permanently', 
 *							 'Preview',
 *							 'View'.
 *
 * @return array $actions - the actions array modified
 */
function remove_row_actions( $actions ){
	
	$user_can_edit = get_field( 'acf-user-editor-can-edit' , 'user_' . get_current_user_id() );
	
	// If the user is editor, and cannot edit posts,
	// remove the options from the post row in the backend
	if ( current_user_can('editor') AND $user_can_edit == false ) 
	{
		

		// If we are in the story post type,
		// remove editing options links.
		// If the post status is draft or pending,
		// add a preview link.
		if( get_post_type() === STORY_POST_TYPE ) 
		{
			global $post;
			
			unset( $actions['edit'] );
			unset( $actions['delete'] );
			unset( $actions['trash'] );
			unset( $actions['inline hide-if-no-js'] );
			
			// Important: Adding the preview link, wont automatcilly grant a draft/pending preview capbility,
			// it's done through a "hack" with the show_public_preview() function, and then
			// set_post_to_publish()
			if ( $post->post_status == 'pending' OR $post->post_status == 'draft' ) 
			{
				$title = _draft_or_post_title();
				$actions['view'] = '<a href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) . '" title="' . esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'Preview' ) . '</a>';
			}

		}
	}
    return $actions;
}
add_filter( 'post_row_actions', 'remove_row_actions', 10, 1 );





/**
 * Filter on the current_user_can() function.
 * This function is used to explicitly allow editors with view only
 * privileges to view published,pending and draft stories of their
 * allowed districits.
 *
 * @param array $allcaps All the capabilities of the user
 *
 * @param array $cap     [0] Required capability
 *
 * @param array $args    [0] Requested capability
 *                       [1] User ID
 *                       [2] Associated object ID
 *
 * @return array The post that is being previewed.
 */
function restrict_backend_stories_editing( $allcaps, $cap, $args ) {

// Bail out if we're not asking to edit or delete a post
if( ( 'edit_post' != $args[0] && 'delete_post' != $args[0] )
  // ... or user is admin 
  || ! empty( $allcaps['manage_options'] )
  // ... or user already cannot edit the post
  || empty( $allcaps['edit_posts'] ) )
    return $allcaps;

	
	if ( current_user_can('adult') ) {
		return $allcaps;
	}
		
	// Get the user districit
	$user_districts = get_field( 'acf-user-editor-districts' , 'user_' . get_current_user_id() );
	
	global $post;
	
	if ( $post != null ) {
		$postId = $post->ID;
	}
	else {
		$postId = $_GET['p'];
	}
	
	if(  has_term( $user_districts, SCHOOLS_TAXONOMY, $postId ) == false ) {
		wp_die( __('איו לך הרשאה לעריכת סיפור ממחוז זה' , 'BH') );
	}
	
	// Get if the current user can edit a post or jsut view it
    $user 			= wp_get_current_user();
    $user_can_edit  = get_field( 'acf-user-editor-can-edit' , 'user_' . $user->ID );

	// If current user is not an editor with view privileges only, 
	// disable his editing capbilities
	if(  empty( $user ) OR 
		(in_array( "editor", (array) $user->roles) AND $user_can_edit == false) 
	) 
	{ 
	   	$allcaps[$cap[0]] = FALSE;
	   	$allcaps['preview_others_posts'] = TRUE;
	}


return $allcaps;
}
add_filter( 'user_has_cap', 'restrict_backend_stories_editing', 10, 3 );





function acf_load_user_editor_districts_field_choices( $field ) {

	
    // reset choices
    $field['choices'] = array();
    
	
    // Get all the locations
	$locations = get_taxonomy_hierarchy( SCHOOLS_TAXONOMY );
	
	// Get the children (all the districits) of Israel (term ID = 3640)
	$locations = $locations[3640]->children;
	
	
	if ( !empty( $locations ) AND !is_wp_error( $locations ) ) 
	{
		foreach( $locations as $district ) 
		{  
			$field['choices'][ $district->term_id ] = $district->name;
		}
	}


    return $field;
    
}
add_filter('acf/load_field/name=acf-user-editor-districts', 'acf_load_user_editor_districts_field_choices');




function remove_admin_menu_pages() 
{
 
	global $user_ID;
 
	if ( current_user_can( 'editor' ) ) 
	{
		// Remove team members menu from admin
		remove_menu_page( 'edit.php?post_type=team-member' );
		
		// Remove team members press from admin
		remove_menu_page( 'edit.php?post_type=press' );
		
		// Remove CF7 from admin
		remove_menu_page( 'wpcf7' );
		
		// Remove posts from admin
		remove_menu_page( 'edit.php' );   		
		
		// Remove pages from admin
		remove_menu_page( 'edit.php?post_type=page' );                   
		
		// Remove tools from admin
		remove_menu_page( 'tools.php' );                   
		
	}
}
add_action( 'admin_init', 'remove_admin_menu_pages' );
