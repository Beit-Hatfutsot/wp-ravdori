<?php
/**
 * Adding CRUD support in the backend for the story custom post type.
 *
 * The file contains the creation of meta boxes and their markup.
 *
 * @package    functions/backend/backend-story-cpt.php
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */



/**
 * Creating meta boxes in the story custom post type
 */
function BH_add_story_cpt_meta_boxes()
{
    add_meta_box( "story-cpt-dictionary-meta-box" , __( 'מילון' , 'BH' )   , "BH_callback_story_cpt_dictionary_meta_box_markup" , "story" , "advanced" , "high" );
    add_meta_box( "story-cpt-quotes-meta-box"     , __( 'ציטוטים' , 'BH' ) , "BH_callback_story_cpt_quotes_meta_box_markup"     , "story" , "advanced" , "high" );
}
add_action( "add_meta_boxes" , "BH_add_story_cpt_meta_boxes" );



/**
 * The dictionary's meta box HTML output in the backend
 *
 * @param Object $post - The calling post object
 */
function BH_callback_story_cpt_dictionary_meta_box_markup( $post )
{
    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'dictionary_meta_box' , 'dictionary_meta_box_meta_box_nonce' );

    // Get the calling post dictionary term
    $post_dictionary = BH_dictionary_get_post_terms( $post->ID );

    $titles = array ( __( 'ערך' , 'BH' ) , __( 'תרגום' , 'BH' ) );

    BH_build_repeater( "dictionary" , $titles , $post_dictionary );
    ?>


    <div id="pk-container" style="display: none">
        <div id="pk-update" style="display: none"></div>
    </div>


<?php
}



/**
 * The quotes's meta box HTML output in the backend
 *
 * @param Object $post - The calling post object
 */
function BH_callback_story_cpt_quotes_meta_box_markup( $post )
{
    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'quotes_meta_box' , 'quotes_meta_box_meta_box_nonce' );

    // Get the calling post quotes
    $post_quotes = BH_quotes_get_post_quotes( $post->ID );

    $titles = array ( __( 'ציטוט' , 'BH' ) );

    BH_build_repeater( "quotes" , $titles , $post_quotes );
}



/**
 * Save the meta boxes input to the database
 *
 * @param int $post_id - The calling post's id
 */
function BH_save_meta_box_story_cpt_to_db( $post_id )
{

    // Check if our nonce is set.
    if ( ! isset( $_POST['dictionary_meta_box_meta_box_nonce'] ) OR ! isset( $_POST['quotes_meta_box_meta_box_nonce'] )) {
        return;
    }


    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['dictionary_meta_box_meta_box_nonce'] , 'dictionary_meta_box' )  OR
         ! wp_verify_nonce( $_POST['quotes_meta_box_meta_box_nonce']     , 'quotes_meta_box' )) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) &&  $_POST['post_type'] == 'story' ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* From here, it's safe for us to save the data  */

    /*********************/
    /*  Dictionary data */
    /*********************/

    // ------- Add new data

    if (     !empty( $_POST['new_fields_dictionary_terms'] )
        AND  !empty( $_POST['new_fields_dictionary_values'] ) ) {

        // Save all the post's repeater data to a new array containing no empty values
        $dictionary_new_terms = array_filter( $_POST['new_fields_dictionary_terms'] );

        // Same as above, just for values
        $dictionary_new_values = array_filter( $_POST['new_fields_dictionary_values'] );

        // If there are new terms, insert them to the db
        if ( count( $dictionary_new_terms ) > 0  ) {

            // The adding function will take care for all the sensitization for us
            BH_add_dictionary_terms($dictionary_new_terms, $dictionary_new_values, $post_id);
        }
    }

    // ------- Delete data

    if ( !empty( $_POST['stories_dictionary_pk_del'] ) ) {

        $dictionary_terms_ids_to_delete = array_filter($_POST['stories_dictionary_pk_del']);

        // If there are terms to delete
        if (count( $dictionary_terms_ids_to_delete ) > 0) {

            BH_delete_dictionary_terms($dictionary_terms_ids_to_delete);
        }
    }


    // ------- Update data

    if (     !empty( $_POST['old_fields_dictionary_terms'] )
        AND  !empty( $_POST['old_fields_dictionary_values'] )
        AND  !empty( $_POST['stories_dictionary_pk_update'] )) {

        // Save all the post's repeater data to a new array containing no empty values
        $dictionary_old_terms    = array_filter($_POST['old_fields_dictionary_terms']);
        $dictionary_old_values   = array_filter($_POST['old_fields_dictionary_values']);
        $dictionary_pk_to_update = array_filter($_POST['stories_dictionary_pk_update']);

        if ( !empty( $_POST['stories_dictionary_pk_del'] ) ) {

            $dictionary_pk_to_update = array_diff($dictionary_pk_to_update, $dictionary_terms_ids_to_delete);
        }

        foreach ($dictionary_pk_to_update as $pk) {

            BH_update_dictionary_term($post_id, $pk, $dictionary_old_terms[$pk], $dictionary_old_values[$pk]);
        }
    }

    /*****************/
    /*  Quotes data */
    /****************/

    // ------- Add new data

    if ( !empty( $_POST['new_fields_quotes_terms'] )  ) {

        // Save all the post's repeater data to a new array containing no empty values
        $quotes_new_terms = array_filter( $_POST['new_fields_quotes_terms'] );

        // If there are new terms, insert them to the db
        if ( count( $quotes_new_terms ) > 0  ) {

            // The adding function will take care for all the sensitization for us
            BH_add_quotes( $quotes_new_terms , $post_id );
        }
    }


    // ------- Delete data

    if ( !empty( $_POST['stories_quotes_pk_del'] ) ) {

        $quotes_terms_ids_to_delete = array_filter($_POST['stories_quotes_pk_del']);

        // If there are terms to delete
        if (count( $quotes_terms_ids_to_delete ) > 0) {

            BH_delete_quotes ( $quotes_terms_ids_to_delete );
        }
    }


    // ------- Update data

    if (     !empty( $_POST['old_fields_quotes_terms'] )
        AND  !empty( $_POST['stories_quotes_pk_update'] )) {


        $quotes_old_terms    = array_filter($_POST['old_fields_quotes_terms']);
        $quotes_pk_to_update = array_filter($_POST['stories_quotes_pk_update']);

        if ( !empty( $_POST['stories_quotes_pk_del'] ) ) {

            $quotes_pk_to_update = array_diff( $quotes_pk_to_update, $quotes_terms_ids_to_delete );
        }

        foreach ($quotes_pk_to_update as $pk) {

            BH_update_quote( $post_id, $pk ,$quotes_old_terms[$pk] );

        }
    }

}
add_action( 'save_post', 'BH_save_meta_box_story_cpt_to_db' );



/**
* Adds a link to the author profile edit, in the story CPT's publish box
*/
function add_story_author_link()
{
    global $post;

    /* check if this is a story CPT , if not then we won't add the custom field */
    if (get_post_type($post) != STORY_POST_TYPE ) return false;
    ?>
    <div class="misc-pub-section">
      <a href="<?php echo admin_url( sprintf( 'user-edit.php?user_id=%d' , $post->post_author ) ); ?>#footer-thankyou" target="_blank">הצג/ערוך את פרטי המבוגר (<?php the_author_meta( 'nickname' , $post->post_author ); ?>)</a>
    </div>
    <?php
}
add_action( 'post_submitbox_misc_actions', 'add_story_author_link' );





/** Make the default wp editor to be inside an meta box */
function action_add_meta_boxes()
{
    global $_wp_post_type_features;
    foreach ($_wp_post_type_features as $type => &$features) {
        if (isset($features['editor']) && $features['editor']) {
            unset($features['editor']);
            add_meta_box(
                'description',
                __('תוכן הסיפור'),
                'content_metabox',
                $type, 'normal', 'high'
            );
        }
    }

}
add_action( 'add_meta_boxes', 'action_add_meta_boxes', 0 );


/** Creates an editor inside a metabox*/
function content_metabox( $post )
{
    echo '<div class="wp-editor-wrap">';

    wp_editor($post->post_content, 'content', array('dfw' => true, 'tabindex' => 1) );

    echo '</div>';
}




function story_change_author_admin_script() {
    global $post_type;

	if( $post_type == STORY_POST_TYPE )
	{
		   wp_enqueue_script('story.backend.jquery'     ,	JS_DIR  . '/story.backend.jquery.js');
	}
}
add_action( 'admin_print_scripts-post-story.php' , 'story_change_author_admin_script', 11 );
add_action( 'admin_print_scripts-post.php'       , 'story_change_author_admin_script', 11 );