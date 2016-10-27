<?php
/**
 * This file handles all the database functions needed to work
 * with the dictionary table
 *
 * @package    functions/database/dictionary.php
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */


/**
 * Gets the dictionary terms starting in the letter $letter
 *
 * @param string $letter  - The first letter of the terms to obtain
 * @param int    $page_number  - if the function is being used with pagination,
 *                               this param's number will get the current "page"
 * @return Row object if the query was successful, NULL otherwise
 */
function BH_dictionary_get_letter_terms( $letter , $page_number = NULL ) {

    global $wpdb;

    // If the input is NOT only one character or it is NOT a letter
    if ( !( preg_match('/(*UTF8)^([א-ת])$/' , $letter ) === 1 ) )
    {
        $letter = 'א';
    }

    // check if not final letters
    if ( !( preg_match('/(*UTF8)^([^ץךףםן])$/' , $letter ) === 1 ) )
    {
        $letter = 'א';
    }


    $query_string = " SELECT * FROM " . DB_DICTIONARY   . " DICTIONARY_TABLE " .
                    " WHERE EXISTS ( SELECT * FROM " . DB_POSTS . " POSTS_TABLE " .
                    " WHERE DICTIONARY_TABLE.post_id = POSTS_TABLE.ID AND POSTS_TABLE.post_status = 'publish') AND SUBSTR( DICTIONARY_TABLE.dictionary_term , 1 , 1 ) = %s" .
                    " ORDER BY dictionary_term ";



    if ( $page_number != NULL )
    {
        $query_string .= " LIMIT %d , %d ";

        $offset = ( $page_number - 1 ) * MAX_TERMS_PER_PAGE;

        $prepared_query_string = $wpdb->prepare( $query_string  , $letter , $offset , MAX_TERMS_PER_PAGE );
    }
    else
    {
        $prepared_query_string = $wpdb->prepare( $query_string  , $letter );
    }

    $query_result = $wpdb->get_results( $prepared_query_string );


    // Get the number of the total posts (without pagination) in the query, and
    // add the result in the returning array
    $total_post_query_string = " SELECT COUNT('dictionary_term_id') FROM " . DB_DICTIONARY   . " DICTIONARY_TABLE " .
                               " WHERE EXISTS ( SELECT * FROM " . DB_POSTS . " POSTS_TABLE " .
                               " WHERE DICTIONARY_TABLE.post_id = POSTS_TABLE.ID AND POSTS_TABLE.post_status = 'publish') AND SUBSTR( DICTIONARY_TABLE.dictionary_term , 1 , 1 ) = %s
                                 ORDER BY dictionary_term ";


    $post_count = $wpdb->get_var( $wpdb->prepare( $total_post_query_string  , $letter ) );

    $query_result['post_count'] = $post_count;

    return ( $query_result );

} // BH_dictionary_get_letter_terms






/**
 * Gets the dictionary terms and values from the database by the post's id.
 *
 * @param BIGINT $post_id  - The post's id with the dictionary terms we wish to obtain
 *
 * @return OBJECT - Row object if the query was successful, NULL otherwise
 */
function BH_dictionary_get_post_terms( $post_id ) {

    global $wpdb;

    $query_string = " SELECT * FROM " . DB_DICTIONARY   .
                    " WHERE post_id = %d";


    $query_result = $wpdb->get_results( $wpdb->prepare( $query_string  , $post_id ) );

    return ( $query_result );

} // BH_dictionary_get_post_terms



/**
 * Gets all the dictionary terms and values from the database.
 * Can return only limited number by using the $return_count param.
 *
 * @param int  $return_count  - The number of results to return:
 *                              -1 returns all the results
 *
 * @param bool $random - Should the returned  a random rows
 *
 * @return OBJECT - Row object if the query was successful, NULL otherwise
 */
function BH_dictionary_get_all_terms( $return_count = -1 , $random = false ) {

    global $wpdb;


    $query_string = " SELECT * FROM " . DB_DICTIONARY   . " DICTIONARY_TABLE " .
                    " WHERE EXISTS ( SELECT * FROM " . DB_POSTS . " POSTS_TABLE " .
                    " WHERE DICTIONARY_TABLE.post_id = POSTS_TABLE.ID AND POSTS_TABLE.post_status = 'publish') ";


    if ( $random == true )
    {
        $query_string .= ' ORDER BY RAND() ';
    }


    if ( $return_count  > 0 )
    {

        $query_string .= " LIMIT %d ";

        $query_string = $wpdb->prepare( $query_string  , $return_count );
    }


    $query_result = $wpdb->get_results( $query_string );

    return ( $query_result );

} // BH_dictionary_get_all_terms



/**
 * Insert's a single dictionary term and it's value to the database.
 *
 * The $term and $value params are being sanitize, and are being
 * checked if valid;
 *
 * @param string $term    - The dictionary term to insert
 * @param string $value   - The dictionary value to insert
 * @param BIGINT $post_id - The post id the term is associate with
 *
 * @return returns 1) false if the row could not be inserted. Otherwise,
 *                 it returns the number of affected rows (which will always be 1).
 *
 *                  2) false if the $term and $values params are empty, or the post_id
 *                     is not numeric
 *
 */
function BH_add_dictionary_term( $term , $value , $post_id ) {

    global $wpdb;

    sanitize( $term );
    sanitize( $value );

    if ( (empty( $term ) || empty( $value ) || empty( $post_id )) OR (!is_numeric( $post_id ))  )
    {
        return ( false );
    }

    $data_to_insert = array(
        'dictionary_term'  => $term  ,
        'dictionary_value' => $value ,
        'post_id'          => $post_id
    );

    $data_format = 	array( '%s' , '%s' ,  '%d' );

    $query_result = $wpdb->insert( DB_DICTIONARY , $data_to_insert , $data_format );

    return ( $query_result );

} // BH_add_dictionary_term





/**
 * Insert's a multiple dictionary terms and values to the database.
 *
 * Each element in the $terms and $values array params are being sanitize, and are being
 * checked if valid.
 *
 * Both the $terms and $values must be in the same size.
 *
 * Each term in index $i of the $terms array has its value in the same index $i
 * in the $values array.
 *
 * @param Array $terms    - Array of dictionary terms to insert
 * @param Array $values   - Array of dictionary values to insert
 * @param BIGINT $post_id - The post id the term is associate with
 *
 * @return returns 1) false if the row could not be inserted. Otherwise,
 *                 it returns the number of affected rows (which will always be 1).
 *
 *                 2) false if the $term and $values params are empty, or the post_id
 *                     is not numeric
 *
 *                 3) false \ NULL on error
 */
function BH_add_dictionary_terms( $terms , $values , $post_id ) {

    global $wpdb;

    // Check if the array are of the same size
    if ( count ( $terms ) != count( $values ) ) {
        return ( false );
    }

    // Check the validity of the post's id
    if ( ( empty( $post_id )) OR (!is_numeric( $post_id ) )  )
    {
        return ( false );
    }


    // The main query - multiple insert SQL statement, because of lack of support in the current Wordpress WPDB object
    $dictionary_terms_insert_query = "INSERT INTO " . DB_DICTIONARY  . " (dictionary_term , dictionary_value , post_id) VALUES ";

    // Holds the MySql SQL INSERT INTO statement which is in the format of ( value 1 , value 2 , ... )
    $query_values = array();

    foreach( $terms as $index => $term )
    {
        sanitize( $terms[$index] );
        sanitize( $values[$index] );

        if ( (empty( $terms[$index] ) || empty( $values[$index] ))  )
        {
            return ( false );
        }

        $query_values[] = $wpdb->prepare( "(%s,%s,%d)", $terms[$index], $values[$index] , $post_id );
    }


    $dictionary_terms_insert_query .= implode( ",\n", $query_values );

    $query_result = $wpdb->query( $dictionary_terms_insert_query );

    return ( $query_result );

} // BH_add_dictionary_terms


/**
 * updates a dictionary term and it's value to the database.
 *
 * @param string $term  - The dictionary term to insert
 * @param string $value - The dictionary value to insert
 *
 * @return  returns the number of rows updated, or false if there is an error
 */
function BH_update_dictionary_term( $post_id , $term_id , $term  = NULL , $value = NULL  ) {

    global $wpdb;

    sanitize( $term );
    sanitize( $value );

    if ( (empty( $term ) || empty( $value ) || empty( $post_id ) || empty( $term_id ) ) OR
        (!is_numeric( $post_id ))  OR (!is_numeric( $term_id ))  )
    {
        return ( false );
    }


    $data_to_update = array();

    $data_to_update['dictionary_term']  = $term;
    $data_to_update['dictionary_value'] = $value;


    $what_to_update =  array(
        'post_id'            => $post_id,
        'dictionary_term_id' => $term_id
    );


    $query_result = $wpdb->update( DB_DICTIONARY , $data_to_update , $what_to_update );

    return ( $query_result );

} // BH_update_dictionary_term


/**
 * Delete's a dictionary term from the database.
 *
 * @param BIGINT $term_id - The dictionary term id to delete
 *
 * @return
 */
function BH_delete_dictionary_term( $term_id ) {

    global $wpdb;

    $data_to_delete = array(
        'dictionary_term_id'  => $term_id
    );

    $data_format = 	array( '%d' );

    $query_result = $wpdb->delete( DB_DICTIONARY , $data_to_delete , $data_format);

    return ( $query_result );

} // BH_delete_dictionary_term


/**
 * Delete's  multiple dictionary terms from the database.
 *
 * Each element in the $terms and $values array params are being sanitize, and are being
 * checked if valid.
 *
 * Both the $terms and $values must be in the same size.
 *
 * Each term in index $i of the $terms array has its value in the same index $i
 * in the $values array.
 *
 * @param Array $terms_pk - Array of dictionary terms Primary Keys to delete
 *
 * @return returns  false \ NULL on error
 *
 */
function BH_delete_dictionary_terms( $terms_pk ) {

    global $wpdb;


    // Check if we have values
    if ( ( empty( $terms_pk ) )  )
    {
        return ( false );
    }


    // The main query - multiple DELETE SQL statement, because of lack of support in the current Wordpress WPDB object
    $dictionary_terms_delete_query = "DELETE FROM " . DB_DICTIONARY  . "  WHERE dictionary_term_id IN (";

    // Sanitize every element from the primary keys array
    foreach( $terms_pk as &$term_pk )
    {
        sanitize( $term_pk );

        if ( ( empty( $term_pk  ) )  )
        {
            return ( false );
        }

        $term_pk = $wpdb->prepare( "%d", $term_pk  );
    }


    // Add the values of the primary keys array as a list of numbers
    $dictionary_terms_delete_query .= implode( ",", $terms_pk ) . ")";

    $query_result = $wpdb->query( $dictionary_terms_delete_query );

    return ( $query_result );

} // BH_delete_dictionary_terms



/**
 * Delete's all the  dictionary terms of specific post from the database.
 *
 * @param int $post_id - The post id to delete
 *
 * @return returns  false \ NULL on error
 *
 */
function BH_delete_all_post_dictionary_terms( $post_id )
{
    global $wpdb;

    $data_to_delete = array(
        'post_id'  => $post_id
    );

    $data_format = 	array( '%d' );

    $query_result = $wpdb->delete( DB_DICTIONARY , $data_to_delete , $data_format);

    return ( $query_result );
}