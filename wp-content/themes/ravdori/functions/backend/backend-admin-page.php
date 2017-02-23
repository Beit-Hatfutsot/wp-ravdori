<?php
/**
 * Adding support in the backend enabling it to interact with the database
 *
 *
 * @package    functions/backend/backend.php
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */


// The WP main menu dictionary slug
define ( 'DICTIONARY_ADMIN_PAGE_MENU_SLUG' , 'dictionary/dictionary-admin-page.php' );

// The WP main menu quotes slug
define ( 'QUOTES_ADMIN_PAGE_MENU_SLUG' , 'quotes/quotes-admin-page.php' );



function BH_add_admin_menu() {

    add_menu_page( __( 'מילון' , 'BH' )    , __( 'מילון' , 'BH' )   , 'manage_options' , DICTIONARY_ADMIN_PAGE_MENU_SLUG     , 'BH_dictionary_admin_page' , 'dashicons-translation' , '5.666' );
    add_menu_page( __( 'ציטוטים' , 'BH' )  , __( 'ציטוטים' , 'BH' ) , 'manage_options' , QUOTES_ADMIN_PAGE_MENU_SLUG         , 'BH_quotes_admin_page'     , 'dashicons-format-quote'    , '6.666' );

}
add_action( 'admin_menu', 'BH_add_admin_menu' );





function BH_dictionary_admin_page() {

    BH_admin_page_save_to_db();

    // Get the calling post dictionary term
    $post_terms = BH_dictionary_get_all_terms();

    $titles = array ( __( 'ערך' , 'BH' ) , __( 'תרגום' , 'BH' ) );
?>
<?php 

$search_term = null;
$matches     = array();

if ( isset( $_POST[ 'term' ] ) OR isset( $_GET[ 'term' ] ) ) 
{ 

	if ( isset( $_POST[ 'term' ] ) ):
		$search_term = strip_tags( trim( $_POST[ 'term' ] ) );
	else:
		$search_term = strip_tags( trim( $_GET[ 'term' ] ) );
	endif;

	if( !empty($search_term ) ) 
	{
		
			foreach ( $post_terms as $term ):
			
				if ( (mb_strpos( $term->dictionary_term  , $search_term )  !== false) OR 
					 (mb_strpos( $term->dictionary_value , $search_term )  !== false) 
				   ) 
				{
					$matches[] = $term;
				}
			
			endforeach;

			$post_terms = $matches;	
	}
}

 ?>
    <div class="wrap">

        <h2><span class="dashicons dashicons-translation"></span> <?php _e('מילון' , 'BH' ) ?></h2>
		
		<form method="post" id="searchform">
			<input type="text" name="term">
			<input type="submit" value="חיפוש">
		</form>
	
        <form method="post">

            <?php submit_button(); ?>

            <?php  BH_build_repeater( "dictionary", $titles, $post_terms , false ,  true ,true ); ?>

            <div id="pk-container" style="display: none">
                <div id="pk-update" style="display: none"></div>
            </div>

            <?php submit_button(); ?>

        </form>
    </div>

<?php
}


function BH_quotes_admin_page() {

    BH_admin_page_save_to_db();

    // Get the calling post quotes
    $post_terms = BH_quotes_get_all_quotes();

    $titles = array ( __( 'ציטוט' , 'BH' ) );

    ?>

<?php 

$search_term = null;
$matches     = array();

if ( isset( $_POST[ 'term' ] ) OR isset( $_GET[ 'term' ] ) ) 
{ 

	if ( isset( $_POST[ 'term' ] ) ):
		$search_term = strip_tags( trim( $_POST[ 'term' ] ) );
	else:
		$search_term = strip_tags( trim( $_GET[ 'term' ] ) );
	endif;

	if( !empty($search_term ) ) 
	{
			foreach ( $post_terms as $term ):
			
				if ( $term == NULL OR is_object ( $term ) == false ) continue;
				
				if ( (mb_strpos( $term->quote_value  , $search_term )  !== false) ) 
				{
					$matches[] = $term;
				}
			
			endforeach;

			$post_terms = $matches;	
	}
}

?>
 
    <div class="wrap">

        <h2><span class="dashicons dashicons-format-quote"></span> <?php _e( 'ציטוטים' , 'BH' ) ?></h2>
		
		<form method="post" id="searchform">
			<input type="text" name="term">
			<input type="submit" value="חיפוש">
		</form>
		
        <form method="post">

            <?php submit_button(); ?>

            <?php  BH_build_repeater( "quotes", $titles, $post_terms , false ,  true ,true ); ?>

            <div id="pk-container" style="display: none">
                <div id="pk-update" style="display: none"></div>
            </div>

            <?php submit_button(); ?>

        </form>
    </div>

<?php
}




function BH_admin_page_save_to_db() {

    /*********************/
    /*  Dictionary data */
    /*********************/

    // ------- Delete data

    if ( isset ( $_POST['stories_dictionary_pk_del'] ) AND !empty( $_POST['stories_dictionary_pk_del'] ) ) {

        $dictionary_terms_ids_to_delete = array_filter($_POST['stories_dictionary_pk_del']);

        // If there are terms to delete
        if (count( $dictionary_terms_ids_to_delete ) > 0) {

            BH_delete_dictionary_terms( $dictionary_terms_ids_to_delete );
        }
    }


    // ------- Update data

    if (     !empty( $_POST['old_fields_dictionary_terms'] )
        AND  !empty( $_POST['old_fields_dictionary_values'] )
        AND  !empty( $_POST['stories_dictionary_pk_update'] )
        AND  isset( $_POST['old_fields_dictionary_terms'] )
        AND  isset( $_POST['old_fields_dictionary_values'] )
        AND  isset( $_POST['stories_dictionary_pk_update'] ) ) {

        // Save all the post's repeater data to a new array containing no empty values
        $dictionary_old_terms    = array_filter($_POST['old_fields_dictionary_terms']);
        $dictionary_old_values   = array_filter($_POST['old_fields_dictionary_values']);
        $dictionary_pk_to_update = array_filter($_POST['stories_dictionary_pk_update']);


        foreach ( $dictionary_pk_to_update as $pk_id ) {

            $pk_id   = explode("," , $pk_id);
            $post_id = $pk_id[0];
            $pk      = $pk_id[1];

            if ( !empty( $_POST['stories_dictionary_pk_del'] ) ) {
                if ( in_array( $pk , $dictionary_terms_ids_to_delete ) )
                    continue;
            }

            BH_update_dictionary_term($post_id, $pk, $dictionary_old_terms[$pk], $dictionary_old_values[$pk]);
        }
    }


    /*****************/
    /*  Quotes data */
    /****************/


    // ------- Delete data

    if ( isset ( $_POST['stories_quotes_pk_del'] ) AND !empty( $_POST['stories_quotes_pk_del'] ) ) {

        $quotes_terms_ids_to_delete = array_filter($_POST['stories_quotes_pk_del']);

        // If there are terms to delete
        if (count( $quotes_terms_ids_to_delete ) > 0) {

            BH_delete_quotes ( $quotes_terms_ids_to_delete );
        }
    }


    // ------- Update data

    if (     !empty( $_POST['old_fields_quotes_terms'] )
        AND  !empty( $_POST['stories_quotes_pk_update'] )
        AND  isset( $_POST['old_fields_quotes_terms'] )
        AND  isset( $_POST['stories_quotes_pk_update'] )) {


        $quotes_old_terms    = array_filter($_POST['old_fields_quotes_terms']);
        $quotes_pk_to_update = array_filter($_POST['stories_quotes_pk_update']);

        if ( !empty( $_POST['stories_quotes_pk_del'] ) ) {

            $quotes_pk_to_update = array_diff( $quotes_pk_to_update, $quotes_terms_ids_to_delete );
        }

        foreach ( $quotes_pk_to_update as $pk_id ) {

            $pk_id   = explode("," , $pk_id);
            $post_id = $pk_id[0];
            $pk      = $pk_id[1];

            if ( !empty( $_POST['stories_quotes_pk_del'] ) ) {
                if ( in_array( $pk , $quotes_terms_ids_to_delete ) )
                    continue;
            }
            BH_update_quote( $post_id, $pk ,$quotes_old_terms[$pk] );

        }


    }

}