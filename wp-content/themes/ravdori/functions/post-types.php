<?php
/**
 * Post types definitions and related functions
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


define ( 'STORY_POST_TYPE'       , 'story' );
define ( 'TEAM_MEMBER_POST_TYPE' , 'team-member' );
define ( 'PRESS_POST_TYPE'       , 'press' );


/**
 * Registers the theme's post types
 */
function BH_register_posttypes() {

    BH_register_post_type_story();
    BH_register_post_type_team_member();
    BH_register_post_type_press();

}
add_action('init', 'BH_register_posttypes');



/**
 * Register the story post type
 */
function BH_register_post_type_story() {

    $labels = array(
                        "name"               => __("סיפורים"  , 'BH' ) ,
                        "singular_name"      => __( "סיפור" , 'BH' )   ,
                        "menu_name"          => __("סיפורים"  , 'BH' ) ,
                        "add_new"            => __( "הוסף חדש" , 'BH' ),
                        "add_new_item"       => __( "הוסף סיפור חדש", 'BH' ) ,
                        "edit"               => __( "ערוך", 'BH' ) ,
                        "edit_item"          => __( "ערוך סיפור", 'BH' ),
                        "new_item"           => __( "סיפור חדש", 'BH' ),
                        "view"               => __( "הצג סיפור", 'BH') ,
                        "view_item"          => __( "הצג סיפור", 'BH' ),
                        "search_items"       => __( "חיפוש סיפורים", 'BH' ),
                        "not_found"          => __( "לא נמצאו סיפורים", 'BH' ) ,
                        "not_found_in_trash" => __( "לא נמצאו סיפורים בפח המיחזור",'BH'),
                        "parent"             => __( "סיפור אב", 'BH'),
    );


    $args = array(
        "labels"       => $labels ,
        "description"  => "" ,
        "public"       => true ,
        "show_ui"      => true ,
        "has_archive"  => true ,
        "show_in_menu" => true ,
        "exclude_from_search" => false  ,
        "capability_type"     => "post" ,
        "map_meta_cap"        => true   ,
        "hierarchical"        => false  ,
        "rewrite"             => array( "slug"  => 'stories' , "with_front" => true ),
        "query_var"           => true ,
        "taxonomies"           => array('category') ,
		"register_meta_box_cb" => 'add_story_metaboxes' ,
        "supports"             => array(
                                        "title"  ,
                                        "editor" ,
                                        "excerpt",
                                        "trackbacks" ,
                                        "comments"  ,
                                        "revisions" ,
                                        "thumbnail" ,
                                        "author"    ,
                                        "page-attributes" ,
                                        "post-formats"
                                       ) ,
    );

    register_post_type( STORY_POST_TYPE , $args );

}


/**
 * Register the team member post type
 */
function BH_register_post_type_team_member() {


    $labels = array(
        "name"                => __( "אנשי צוות", 'BH' ) ,
        "singular_name"       => __( "איש צוות", 'BH' )  ,
        "menu_name"           => __("אנשי צוות", 'BH' )  ,
        "add_new"             => __( "הוסף איש צוות", 'BH' ) ,
        "add_new_item"        => __( "הוסף איש צוות חדש", 'BH' ) ,
        "edit"                => __( "ערוך", 'BH' ) ,
        "edit_item"           => __( "ערוך איש צוות", 'BH ') ,
        "new_item"            => __( "איש צוות חדש", 'BH' )  ,
        "view"                => __( "הצג איש צוות", 'BH' )  ,
        "view_item"           => __( "הצג איש צוות", 'BH' )  ,
        "search_items"        => __( "חיפוש אנשי צוות", 'BH' ) ,
        "not_found"           => __( "לא נמצאו אנשי צוות", 'BH' ) ,
        "not_found_in_trash"  => __( "לא נמצאו אנשי צוות בפח המיחזור", 'BH' ) ,
        "parent"              => __( "איש צוות אב", 'BH' ),
    );


    $args = array (
                    "labels"       => $labels,
                    "description"  => "",
                    "public"       => true,
                    "show_ui"      => true,
                    "has_archive"  => false,
                    "show_in_menu" => true,
                    "exclude_from_search" => false,
                    'capabilities'        => array(
													'publish_posts'       => 'update_core',
													'edit_others_posts'   => 'update_core',
													'delete_posts'        => 'update_core',
													'delete_others_posts' => 'update_core',
													'read_private_posts'  => 'update_core',
													'edit_post'           => 'update_core',
													'delete_post'         => 'update_core',
													'read_post'           => 'update_core',
													),
                    "hierarchical"        => false,
                    "rewrite"             => array(
                                                    "slug"       => "team-member" ,
                                                    "with_front" => true
                                                  ),
                    "query_var" => true ,
                    "supports"  => array(
                                            "title"  ,
                                            "editor" ,
                                            "excerpt",
                                            "trackbacks" ,
                                            "custom-fields" ,
                                            "comments"  ,
                                            "revisions" ,
                                            "thumbnail" ,
                                            "author" ,
                                            "page-attributes" ,
                                            "post-formats" ,
                                        ),
    );

    register_post_type( TEAM_MEMBER_POST_TYPE , $args );

}


/**
 * Register the press post type
 */
function BH_register_post_type_press() {


    $labels = array(
                        "name"               => __( "כתבות", 'BH' ) ,
                        "singular_name"      => __( "כתבה",'BH' ),
                        "menu_name"          => __( "כתבות", 'BH' ),
                        "add_new"            => __( "הוסף כתבה", 'BH' ) ,
                        "add_new_item"       => __( "הוסף כתבה חדשה", 'BH' ) ,
                        "edit"               => __( "עריכה", 'BH' ) ,
                        "edit_item"          => __( "עריכת כתבה", 'BH' ) ,
                        "new_item"           => __( "כתבה חדשה", 'BH' )  ,
                        "view"               => __( "הצגת כתבה", 'BH' )  ,
                        "view_item"          => __( "הצגת כתבה", 'BH' )  ,
                        "search_items"       => __( "חיפוש כתבה", 'BH' ) ,
                        "not_found"          => __( "לא נמצאו כתבות", 'BH' ) ,
                        "not_found_in_trash" => __( "לא נמצאו כתבות בסל המיחזור", 'BH' ) ,
                        "parent"             => __( "כתבה אה", 'BH' ) ,
                    );

    $args = array(
        "labels"        => $labels ,
        "description"   => "" ,
        "public"        => true,
        "show_ui"       => true,
        "has_archive"   => false,
        "show_in_menu"  => true,
        "exclude_from_search" => false,
        "capability_type"     => "post",
        "map_meta_cap"        => true,
        "hierarchical"        => false,
        "rewrite"             => array (
                                        "slug"       => "press" ,
                                        "with_front" => true ,
                                       ),
        "query_var" => true,
        "supports"  => array(
                                "title"  ,
                                "editor" ,
                                "excerpt",
                                "trackbacks" ,
                                "custom-fields" ,
                                "comments"  ,
                                "revisions" ,
                                "thumbnail" ,
                                "author" ,
                                "page-attributes" ,
                                "post-formats"    ,
                            ),
    );

    register_post_type( PRESS_POST_TYPE , $args );

}



/**
 * Fix pagination display for the Story custom post type archive
 *
 * Wordpress has a known bug with the posts_per_page value and overriding it using
 * query_posts. The result is that although the number of allowed posts_per_page
 * is abided by on the first page, subsequent pages give a 404 error and act as if
 * there are no more custom post type posts to show and thus gives a 404 error.
 *
 * This fix is a nicer alternative to setting the blog pages show at most value in the
 * WP Admin reading options screen to a low value like 1.
 *
 */
function story_archive_display( $query ) {

    if ( is_post_type_archive( STORY_POST_TYPE ) && !is_admin() ) {
        $query->set( 'posts_per_page' , MAX_ROWS_PER_PAGE );
        return;
    }
}
add_action('pre_get_posts', 'story_archive_display');


/** Let's you select any user to be credited as author **/

/**
 * Adds a meta box to the post editing screen
 */

function add_story_metaboxes() {
    add_meta_box( 'authordiv', __('Author'), 'ca_meta_callback', STORY_POST_TYPE , 'side' );
}
//add_action( 'add_meta_boxes', 'add_story_metaboxes' );

/**
 * Outputs the content of the meta box
 */
function ca_meta_callback( $post ) {
	global $user_ID;
?>
<label class="screen-reader-text" for="post_author_override"><?php _e('Author'); ?></label>
<?php
	wp_dropdown_users( array(
		'name' => 'post_author_override',
		'selected' => empty($post->ID) ? $user_ID : $post->post_author,
		'include_selected' => true
	) );
}





/*********************************************************/
/****** Adding custom columns to CPT At the backend ******/
/*********************************************************/

/** Add columns with their titles to the backend (No content)*/
function add_story_backend_columns( $columns ) {

  
	
    $columns = array(
        'cb'            => '<input type="checkbox" />',
        'title'         => __('כותרת הסיפור'),
        'author_adult'  => __('שם המבוגר'),
        'student'       => __( 'מנחה מתעד' ),
        'city'          => __('יישוב'),
        'district'      => __('מחוז'),
        'school'        => __('בית ספר'),
        /*'date_creation' => __('תאריך יצירה'),*/
        'date'          => __( 'Date' ),
        'subjects'      => __('נושאים'),
        /*'categories'  => __('קטגוריות'),*/
        'languages'     => __('שפות'),
        'post-status'   => __('מצב פרסום'),
    );

    return $columns;
}
add_filter( 'manage_edit-' . STORY_POST_TYPE . '_columns', 'add_story_backend_columns' ) ;





/** Attach the actual data to each column */
function add_story_backend_columns_data( $column, $post_id ) {
    global $post;


    list($student, $city, $school, $district, $subjects, $languages) = get_backend_story_data($post);
	
    switch( $column ) {
		
		case 'author_adult':
			$author_id = get_post_field ('post_author', $post_id);
			
			printf('<a href="%s" target="_blank">%s </a> |  <a href="%s" target="_blank">(%s %s)</a> ',
														admin_url(sprintf( 'user-edit.php?user_id=%d', $author_id )),
														get_the_author_meta( 'nickname', $author_id ),
														admin_url(sprintf( 'edit.php?post_type=story&author=%s', $author_id )),
														BH_get_author_post_type_counts_by_ID( $author_id ),
														'סיפורים מפורסמים'
														);
		break;
		
        case 'student' :
                echo $student;
        break;

        case 'district' :
            echo $district;
        break;


        case 'city' :
            echo $city;
        break;


        case 'school' :
            echo $school;
        break;


        case 'subjects' :
            echo $subjects;
        break;

        case 'languages' :
            echo $languages;
        break;

        case 'post-status' :
          echo get_post_status ( $post_id );
        break;

        /* Just break out of the switch statement for everything else. */
        default :
            break;
    }
}
add_action( 'manage_' . STORY_POST_TYPE .'_posts_custom_column', 'add_story_backend_columns_data', 10, 2 );





/**
 * @param $post
 * @return array
 */
function get_backend_story_data( $post )
{
    $story_data = get_story_meta_data(null, true);

    $student = (!empty($story_data[STORY_META_ARRAY_STUDENT_NAME]['meta_data']) ? $story_data[STORY_META_ARRAY_STUDENT_NAME]['meta_data'] : '');
    $location = (!empty($story_data[STORY_META_ARRAY_STUDENT_LOCATION]['meta_data']) ? $story_data[STORY_META_ARRAY_STUDENT_LOCATION]['meta_data'] : '');

    $city = '';
    $school = '';

    if ($location) {

        $matches = null;
        $regex = '#<\s*?a\b[^>]*>(.*?)</a\b[^>]*>#s';
        preg_match_all($regex, $location, $matches);
        $district = strip_tags($matches[0][1]);
        $city = strip_tags($matches[0][2]);
        $school = strip_tags($matches[0][3]);

    }

    $subjects = (!empty($story_data[STORY_META_ARRAY_SUBJECTS]['meta_data']) ? $story_data[STORY_META_ARRAY_SUBJECTS]['meta_data'] : '');


    if ($subjects) {

        $matches = null;
        $regex = '#<\s*?a\b[^>]*>(.*?)</a\b[^>]*>#s';
        preg_match_all($regex, $subjects, $matches);

        $subjects = implode(', ', $matches[1]);

    }


    #region Get the story languagess

    $terms = wp_get_object_terms($post->ID, LANGUAGES_TAXONOMY);
    $languages = [];

    if (!empty($terms) && !is_wp_error($terms)) {
        // $subjects_search_url = get_field('acf-options-search-subject', 'options');

        foreach ($terms as $term) {
            //$term_link = $subjects_search_url . '?subjects[]=' . $term->term_id;

            $languages[] =/* '<a href="' . $term_link . '" target="_blank" class="meta-term-link">' .*/
                $term->name /* . '</a>'*/
            ;
        }

        $languages = implode(', ', $languages);
        return array($student, $city, $school, $district, $subjects, $languages);
    }
    return array($student, $city, $school, $district, $subjects, $languages);

    #endregion
}






#region Make the columns sortable ( Not In Use )


    /** Make the columns sortable*/
   /* function make_story_backend_sortable_columns( $columns ) {

        $columns['city'] = 'city';

        return $columns;
    }
    add_filter( 'manage_edit-' . STORY_POST_TYPE . '_sortable_columns', 'make_story_backend_sortable_columns' );*/




    /** Only run our customization on the 'edit.php' page in the admin. */
   /* function edit_story_load() {
        add_filter( 'request', 'backend_sort_stories' );
    }
    add_action( 'load-edit.php', 'edit_story_load' );*/




    /** Sorts the stories. */
//    function backend_sort_stories ( $vars ) {
//
//        /* Check if we're viewing the 'story' post type. */
//        if ( isset( $vars['post_type'] ) && $vars['post_type'] == STORY_POST_TYPE ) {
//
//            /* Check if 'orderby' is set to 'city'. */
//            if ( isset( $vars['orderby'] ) && 'city' == $vars['orderby'] ) {
//
//                /* Merge the query vars with our custom variables. */
//                $vars = array_merge(
//                    $vars,
//                    array(
//                        'meta_key' => 'city',
//                        'orderby' => 'meta_value_value'
//                    )
//                );
//            }
//        }
//
//        return $vars;
//    }
#endregion



/****** Adding CPT columns sorting capability At the backend ******/


function backend_create_filters() {
    global $typenow;
	global $pagenow;


    if( $typenow == STORY_POST_TYPE AND current_user_can('edit_others_pages') )
    {
        $filters = get_object_taxonomies( $typenow );

        foreach ($filters as $tax_slug)
        {
            if ( $tax_slug == 'category' )
                continue;

            $tax_obj = get_taxonomy( $tax_slug );
			


			
			$args  = array(
                                                'show_option_all' => __('הצג את כל ה'.$tax_obj->label ) ,
                                                'taxonomy'        => $tax_slug      ,
                                                'name'            => $tax_obj->name ,
                                                'orderby'         => 'term_order'   ,
                                               /* 'selected'        => $_GET[$tax_obj->query_var] ,*/
                                                'hierarchical'    => $tax_obj->hierarchical     ,
                                                'show_count'      => true ,
                                                'hide_empty'      => false ,
                                          );
			
			if ( $tax_obj->name == 'schools' )	{
				
				$user_districts = get_field( 'acf-user-editor-districts' , 'user_' . get_current_user_id() );

				if ( $user_districts )
					$args['child_of'] = $user_districts[0];			
			}						  
										  
            wp_dropdown_categories( $args );
        }


        dropdown_post_status();

        dropdown_author();

        //dropdown_acf( 'field_556c77ef78b9a' , 'students' , 'כל המנחים' );

    } // End if
}
add_action('restrict_manage_posts', 'backend_create_filters' );



function my_convert_restrict($query) {

    global $pagenow;
    global $typenow;

    if ( $query->is_admin && $pagenow == 'edit.php' )
    {
        $filters = get_object_taxonomies( $typenow );

        foreach ( $filters as $tax_slug )
        {
            $var = &$query->query_vars[$tax_slug];

            if ( isset($var) )
            {
                $term = get_term_by( 'id',$var,$tax_slug );

                if ( !empty( $term ) )
                    $var = $term->slug;
            }
        }

        $var = &$query->query_vars;

        if ( isset($_GET["postStatus"]) )
        {
            $var['post_status'] = $_GET["postStatus"];
        }

    } // End if
}

add_filter('parse_query','my_convert_restrict');



function dropdown_post_status()
{

    $posts_status = array ( 'publish' , 'pending' , 'draft' );


    echo "<select id='postStatus' name='postStatus'>";
    echo '<option value="any" ' . ( isset($_GET["postStatus"]) AND ($_GET["postStatus"] == 'any') ? 'selected' : '') . '>' . 'כל הפרסומים' . '</option>';

    foreach ( $posts_status as $status )
    {
        echo '<option value="' . $status . '" ' . ( isset($_GET["postStatus"]) AND ($_GET["postStatus"] == $status) ? 'selected' : '') . '>' . $status . '</option>';
    }

    echo "</select>";

}





function dropdown_author()
{

    //execute only on the 'post' content type
    global $post_type;

    if( $post_type == STORY_POST_TYPE ){

        //get a listing of all users that are 'author' or above
        $user_args = array(
            'show_option_all'   => 'כל המבוגרים',
            'orderby'           => 'display_name',
            'order'             => 'ASC',
            'name'              => 'author_admin_filter',
            'include_selected'  => true
        );

        //determine if we have selected a user to be filtered by already
        if(isset($_GET['author_admin_filter'])) {
            //set the selected value to the value of the author
            $user_args['selected'] = (int)sanitize_text_field($_GET['author_admin_filter']);
        }

        //display the users as a drop down
        wp_dropdown_users($user_args);
    }

}


function add_author_filter_to_posts_query($query){

    global $post_type, $pagenow;

    //if we are currently on the edit screen of the post type listings
    if( $pagenow == 'edit.php' && $post_type == STORY_POST_TYPE ){

        if(isset($_GET['author_admin_filter'])){

            //set the query variable for 'author' to the desired value
            $author_id = sanitize_text_field($_GET['author_admin_filter']);

            //if the author is not 0 (meaning all)
            if($author_id != 0){
                $query->query_vars['author'] = $author_id;
            }

        }
    }
}

add_action('pre_get_posts','add_author_filter_to_posts_query');




function dropdown_acf( $field_key , $filter_name , $all_fields_string )
{

    global $typenow;

    if ( $typenow == STORY_POST_TYPE )
    {

        $field = get_field_object($field_key)['value'];

        var_dump($field);

        ?>
        <select name="<?php echo $filter_name . '_filter'; ?>">
            <option value=""><?php _e( $all_fields_string , $filter_name ); ?></option>
            <?php
            $current_v = isset($_GET[$filter_name . '_filter']) ? $_GET[$filter_name . '_filter'] : '';
            foreach ($field as $label => $value) {
                printf (
                    '<option value="%s"%s>%s</option>',
                    $value,
                    $value == $current_v? ' selected="selected"':'',
                    $label
                );
            }
            ?>
        </select>
        <?php
    }

}



function request_acf_filter( $request  ) {
    if ( is_admin() && isset($request['post_type']) && $request['post_type']== STORY_POST_TYPE ) {
        $request['meta_key'] = 'status';
        $request['meta_value'] = $_GET[ 'students' . '_filter'];
    }
    return $request;
}
//add_action( 'request', 'request_acf_filter' );


/*********************************************************/
/**** Adding Export columns to Excel At the backend ****/
/*********************************************************/


// Step 1: add the custom Bulk Action to the select menus
function custom_bulk_admin_footer() {
    global $post_type;

    if( ($post_type == STORY_POST_TYPE) OR  get_current_screen()->id == 'users' ) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('<option>').val('export').text('<?php _e('ייצוא')?>').appendTo("select[name='action']");
                jQuery('<option>').val('export').text('<?php _e('ייצוא')?>').appendTo("select[name='action2']");
            });
        </script>
        <?php
    }
}
add_action('admin_footer-edit.php'  , 'custom_bulk_admin_footer' );
add_action('admin_footer-users.php' , 'custom_bulk_admin_footer' );


/**
 * Step 2: handle the custom Bulk Action
 *
 * Based on the post http://wordpress.stackexchange.com/questions/29822/custom-bulk-action
 */
function custom_bulk_action() {
    global $typenow;
    $post_type = $typenow;

    // Stories Screen
    if( $post_type == STORY_POST_TYPE ) {

        // get the action
        $wp_list_table = _get_list_table('WP_Posts_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
        $action = $wp_list_table->current_action();

        $allowed_actions = array("export");
        if(!in_array($action, $allowed_actions)) return;

        // security check
        check_admin_referer('bulk-posts');

        // make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
        if(isset($_REQUEST['post'])) {
            $post_ids = array_map('intval', $_REQUEST['post']);
        }

        if(empty($post_ids)) return;

        // this is based on wp-admin/edit.php
        $sendback = remove_query_arg( array('exported', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
        if ( ! $sendback )
            $sendback = admin_url( "edit.php?post_type=$post_type" );

        $pagenum = $wp_list_table->get_pagenum();
        $sendback = add_query_arg( 'paged', $pagenum, $sendback );

        switch($action) {
            case 'export':

                // if we set up user permissions/capabilities, the code might look like:
                //if ( !current_user_can($post_type_object->cap->export_post, $post_id) )
                //	wp_die( __('You are not allowed to export this post.') );


                    if ( !perform_export_stories($post_ids) )
                        wp_die( __('Error exporting post.') );


                $sendback = add_query_arg( array('exported' => count($post_ids), 'ids' => join(',', $post_ids) ), $sendback );
                break;

            default: return;
        }

        $sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );

        wp_redirect($sendback);
        exit();
    }


    // Users Screen
    if(  get_current_screen()->id == 'users'  ) {

        // get the action
        $wp_list_table = _get_list_table('WP_Users_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
        $action = $wp_list_table->current_action();

        $allowed_actions = array("export");
        if(!in_array($action, $allowed_actions)) return;

        // security check
        check_admin_referer('bulk-users');

        // make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
        if(isset($_REQUEST['users'])) {
            $post_ids = array_map('intval', $_REQUEST['users']);
        }

        error_log(print_r( $post_ids , true));
        if(empty($post_ids)) return;

        // this is based on wp-admin/edit.php
        $sendback = remove_query_arg( array('exported', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
        if ( ! $sendback )
            $sendback = admin_url( "edit.php?post_type=$post_type" );

        $pagenum = $wp_list_table->get_pagenum();
        $sendback = add_query_arg( 'paged', $pagenum, $sendback );

        switch($action) {
            case 'export':

                // if we set up user permissions/capabilities, the code might look like:
                //if ( !current_user_can($post_type_object->cap->export_post, $post_id) )
                //	wp_die( __('You are not allowed to export this post.') );


                if ( !perform_export_users($post_ids) )
                    wp_die( __('Error exporting post.') );


                $sendback = add_query_arg( array('exported' => count($post_ids), 'ids' => join(',', $post_ids) ), $sendback );
                break;

            default: return;
        }

        $sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );

        wp_redirect($sendback);
        exit();
    }
}
add_action('load-edit.php', 'custom_bulk_action');
add_action('load-users.php', 'custom_bulk_action');

function custom_bulk_admin_notices() {
    global $post_type, $pagenow;

    if($pagenow == 'edit.php' && $post_type == STORY_POST_TYPE && isset($_REQUEST['exported']) && (int) $_REQUEST['exported']) {
        $message = sprintf( _n( 'הסיפורים הוצאו בהצלחה', '%s סיפורים הוצאו', $_REQUEST['exported'] ), number_format_i18n( $_REQUEST['exported'] ) );
        echo "<div class=\"updated\"><p>{$message}</p></div>";
    }
}
add_action('admin_notices', 'custom_bulk_admin_notices');


// callback: the STORIES export action
function perform_export_stories( $post_ids ) {

    // Include the csv exporter
    require_once(FUNCTIONS_DIR . 'story_uploader/' . 'php-export-data.class.php');

    $columns_names = array(
        'post_title'   =>  'שם הסיפור',
        'author'       => 'שם המבוגר' ,
        'student'      => 'מנחה מתעד' ,
        'city'         => 'יישוב' ,
        'district'     => 'מחוז' ,
        'school'       => 'בית ספר' ,
        'date'         => 'תאריך' ,
        'subjects'     => 'נושאים' ,
        'languages'    => 'שפות' ,
        'post-status'  => 'מצב פרסום' ,
    );


    #region Get the columns we wish to export, based on the visibility options in the back end

    // Get current user & his meta data
    $current_user = wp_get_current_user();
    $user_id 	  =   $current_user->ID;
    $user_meta 	  =  get_user_meta ($user_id);

    //  Get a STRING of the hidden columns in the backend, defined by the user
    // The string is:  "a:9:{i:0;s:6:"author";i:1;s:7:"student";i:2;s:4:"city";i:3;s:8:"district";i:4;s:6:"school";i:5;s:4:"date";i:6;s:8:"subjects";i:7;s:9:"languages";i:8;s:11:"post-status";}"
    $hidden_columns =  $user_meta['manageedit-storycolumnshidden'][0];

    // Convert the hidden columns string to an array,
    // by getting all of the strings between ""
    preg_match_all( '`"([^"]*)"`' , $hidden_columns, $results );

    // Flip he return array values with it's keys,
    // so now we have Array['"FIELD_NAME"']
    $results = array_flip($results[0]);

    // Remove the quotes from the keys
    foreach ( $results as $key => $value )
    {
        unset ($results[$key]);
        $key           = trim( $key ,'"' );
        $results[$key] = $value;
    }


    // Remove the columns we do not wish to export
    $columns_names  = array_diff_key($columns_names, $results);

    //$CSV_columns_headers[] = array_values ($columns_names);

    #endregion



    #region Get the actual data of each story

    global $post;

    $story_data_array = array();


    $exporter = new ExportDataExcel('browser', 'stories.xls');

    // starts streaming data to web browser
    $exporter->initialize();

    $first_line = true;

    foreach ( $post_ids as $post_id ) {
        $post = get_post($post_id);
        setup_postdata($post);

        list($student, $city, $school, $district, $subjects, $languages) = get_backend_story_data($post);

        $story_data_array['שם הסיפור'] = get_the_title();

        $key = "author";
        if (array_key_exists($key, $columns_names)) {
            $story_data_array[$columns_names[$key]] = get_the_author();
        }

        $key = "student";
        if (array_key_exists($key, $columns_names)) {
            $story_data_array[$columns_names[$key]] = $student;
        }

        $key = "city";
        if (array_key_exists($key, $columns_names)) {
            $story_data_array[$columns_names[$key]] = $city;
        }

        $key = "district";
        if (array_key_exists($key, $columns_names)) {
            $story_data_array[$columns_names[$key]] = $district;
        }

        $key = "school";
        if (array_key_exists($key, $columns_names)) {
            $story_data_array[$columns_names[$key]] = $school;
        }

        $key = "date";
        if (array_key_exists($key, $columns_names)) {
            $story_data_array[$columns_names[$key]] = get_the_date();
        }

        $key = "subjects";
        if (array_key_exists($key, $columns_names)) {
            $story_data_array[$columns_names[$key]] = $subjects;
        }

        $key = "languages";
        if (array_key_exists($key, $columns_names)) {
            $story_data_array[$columns_names[$key]] = $languages;
        }

        $key = "post-status";
        if (array_key_exists($key, $columns_names)) {
            $story_data_array[$columns_names[$key]] = get_post_status();
        }

        if ($first_line) {
            $exporter->addRow(array_keys($story_data_array));
            $first_line = false;
        }


        $exporter->addRow( array_values( $story_data_array ) );
        //error_log(  print_r( get_backend_story_data($post) , true ) );
    }

    wp_reset_postdata();

    #endregion



    // writes the footer, flushes remaining data to browser.
    $exporter->finalize();

    exit(); // all done



    return true;
}


// callback: the USERS export action
function perform_export_users( $post_ids ) {
error_log( print_r( $post_ids , true) );
    // Include the csv exporter
    require_once(FUNCTIONS_DIR . 'story_uploader/' . 'php-export-data.class.php');

    $exporter = new ExportDataExcel('browser', 'users.xls');

    // starts streaming data to web browser
    $exporter->initialize();

    $exporter->addRow( array('שם' , 'אימייל' ) );

    foreach ( $post_ids as $post_id ) {

        $user_info = get_userdata( $post_id );

        $exporter->addRow( array( $user_info->display_name , $user_info->user_email) );
    }

    // writes the footer, flushes remaining data to browser.
    $exporter->finalize();

    exit(); // all done

}




function add_school_id(){
    global $current_screen;

    $schoolCodes =  array();
    $max_code    = -1;

    switch ( $current_screen->id )
    {

        case 'edit-schools':
            // WE ARE AT /wp-admin/edit-tags.php?taxonomy=schools (The schools category page)



            $terms = get_terms( SCHOOLS_TAXONOMY, array ( 'hide_empty' => false ) );

            $lastChilds = array();

            foreach ($terms as $term) {
                $children = get_term_children( $term->term_id, SCHOOLS_TAXONOMY );

                if(sizeof($children) > 0) {
                    $lastChilds[] = array_pop( $children );
                }
            }

            foreach( $lastChilds as $school_id ) {
                $school_code = get_field( 'field_55bf5f841d1b0' , SCHOOLS_TAXONOMY . '_' . $school_id ); // field_55bf5f841d1b0 = acf-school-code

                if ( $school_code )
                    $schoolCodes[] = $school_code;
            }


            $max_code = max($schoolCodes);

        ?>
            <script type="text/javascript">
                jQuery(function(){
					/* The "ready" state used to be "DOMNodeInserted" in ACF v4 version */
					jQuery(document).ready(function(e) {
                        jQuery('#acf-field_55bf5f841d1b0').attr('placeholder', 'קוד אחרון: ' + '<?php echo $max_code; ?>'  ); // field_55bf5f841d1b0 = acf-school-code
                        jQuery('#acf-field_55bf5f841d1b0').val('<?php echo ($max_code + 1); ?>');
                    });
                });

            </script>

        <?php

        break;

    }

}
add_action( 'admin_footer-edit-tags.php', 'add_school_id' );



/***********************************************************************************/
/****** Showing number of posts of the story CPT At the backend for each user ******/
/***********************************************************************************/


function BH_manage_users_columns($column_headers) {
    unset($column_headers['posts']);
    $column_headers['custom_posts'] = __('מספר הסיפורים שהמבוגר פרסם');
    return $column_headers;
}
add_action('manage_users_columns','BH_manage_users_columns');


function BH_manage_users_custom_column($custom_column,$column_name,$user_id) {
    if ($column_name=='custom_posts') {
        $counts = BH_get_author_post_type_counts( $user_id );
        $custom_column = array();
        if (isset($counts[$user_id]) && is_array($counts[$user_id]))
            foreach($counts[$user_id] as $count) {
                $link = admin_url() . "edit.php?post_type=" . $count['type']. "&author=".$user_id;
                // admin_url() . "edit.php?author=" . $user->ID;
                $custom_column[] = "\t<tr><th>{$count['count']}</th><td><a href={$link}>{$count['label']}</a></td></tr>";
            }
        $custom_column = implode("\n",$custom_column);
        if (empty($custom_column))
            $custom_column = "<th>[none]</th>";
        $custom_column = "<table>\n{$custom_column}\n</table>";
    }
    return $custom_column;
}
add_action('manage_users_custom_column','BH_manage_users_custom_column',10,3);


function BH_get_author_post_type_counts( $userid, $post_type = STORY_POST_TYPE ) {
		
    static $counts;
    if (!isset($counts)) {
        global $wpdb;
        global $wp_post_types;
        $sql = <<<SQL
        SELECT
        post_type,
        post_author,
        COUNT(*) AS post_count
        FROM
        {$wpdb->posts}
        WHERE 1=1
        AND post_type NOT IN ('revision','nav_menu_item')
        AND post_status IN ('publish','pending', 'draft')
        GROUP BY
        post_type,
        post_author
SQL;
        $posts = $wpdb->get_results($sql);
        foreach($posts as $post) {
            $post_type_object = $wp_post_types[$post_type = $post->post_type];
            if (!empty($post_type_object->label))
                $label = $post_type_object->label;
            else if (!empty($post_type_object->labels->name))
                $label = $post_type_object->labels->name;
            else
                $label = ucfirst(str_replace(array('-','_'),' ',$post_type));
            if (!isset($counts[$post_author = $post->post_author]))
                $counts[$post_author] = array();
            $counts[$post_author][] = array(
                'label' => $label,
                'count' => $post->post_count,
                'type' => $post->post_type,
                );
        }
    }
    return $counts;

}



function BH_get_author_post_type_counts_by_ID( $userid, $post_type = STORY_POST_TYPE ) {
	
global $wpdb;

	$where = get_posts_by_author_sql( $post_type, true, $userid );

	$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );

  	return apply_filters( 'get_usernumposts', $count, $userid );
	
}


////////////////


add_action( 'current_screen', function ( $current_screen ) 
{  
        if ($current_screen->id === 'edit-story' AND  !current_user_can( 'manage_options' ) )
            add_filter( "views_{$current_screen->id}", 'list_table_views_filter' );

		
}, 20);

function list_table_views_filter( array $view ) {
   // error_log(print_r($view, true));
   
   
   
   $user_districts = get_field( 'acf-user-editor-districts' , 'user_' . get_current_user_id() );

// All the published storyies in the district
$args_query = array(
	'post_type' => STORY_POST_TYPE,
	'post_status' => array('publish'),
	'posts_per_page' => -1,
	'tax_query' => array(
							array(
								'taxonomy' => SCHOOLS_TAXONOMY,
								'field'    => 'term_id',
								'terms'    => $user_districts,
							)
						)
);

// Get all published
$query = new WP_Query( $args_query );
$total_published = $query->found_posts;
wp_reset_postdata();


// Get all draft
$args_query['post_status'] = array('draft');
$query = new WP_Query( $args_query );

$total_drafts    = $query->found_posts;



// Get all pending
$args_query['post_status'] = array('pending');
$query = new WP_Query( $args_query );

$total_pending    = $query->found_posts;

 

$view['publish'] = preg_replace( '/\(.+\)/U', '('.$total_published.')', $view['publish'] ); 
$view['draft']   = preg_replace( '/\(.+\)/U', '('.$total_drafts.')', $view['draft'] ); 
$view['pending'] = preg_replace( '/\(.+\)/U', '('.$total_pending.')', $view['pending'] ); 

   
    return $view;
}

