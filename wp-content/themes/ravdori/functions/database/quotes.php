<?php
/**
 * This file handles all the database functions needed to work
 * with the quotes table
 *
 * @package    functions/database/quotes.php
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */



/**
 * Gets all the existing quotes from the database.
 *
 * @param int $page_number - The number of the page that should be returned
 *                           if pagination is used
 *
 * @return OBJECT - Row object if the query was successful, NULL otherwise
 */
function BH_quotes_get_all_quotes( $page_number = NULL  ) {

    global $wpdb;

    $query_string = " SELECT * FROM " . DB_QUOTES . " QUOTES_TABLE" .
                    " WHERE EXISTS ( SELECT * FROM " . DB_POSTS . " POSTS_TABLE " .
                    " WHERE QUOTES_TABLE.post_id = POSTS_TABLE.ID AND POSTS_TABLE.post_status = 'publish') ";


    if ( $page_number != NULL )
    {
        $query_string .= " LIMIT %d , %d ";

        $offset = ( $page_number - 1 ) * MAX_QUOTES_PER_PAGE;

        $query_string = $wpdb->prepare( $query_string  , $offset , MAX_QUOTES_PER_PAGE );
    }


    $query_result = $wpdb->get_results( $query_string );

    // Get the number of the total posts (without pagination) in the query, and
    // add the result in the returning array

    $total_post_query_string = " SELECT COUNT('quote_id') FROM " . DB_QUOTES . " QUOTES_TABLE" .
                               " WHERE EXISTS ( SELECT * FROM "  . DB_POSTS  . " POSTS_TABLE " .
                               " WHERE QUOTES_TABLE.post_id = POSTS_TABLE.ID AND POSTS_TABLE.post_status = '%s') ";


    $post_count = $wpdb->get_var( $wpdb->prepare( $total_post_query_string , 'publish' ) );


    $query_result['post_count'] = $post_count;


    return ( $query_result );

} // BH_quotes_get_post_quotes


/**
 * Gets all the quotes from the database.
 * Can return only limited number by using the $return_count param.
 *
 * @param int  $return_count  - The number of results to return:
 *                              -1 returns all the results
 *
 * @param bool $random - Should the returned  a random rows
 *
 * @return OBJECT - Row object if the query was successful, NULL otherwise
 */
function BH_quotes_get_all_quotes_random( $return_count = -1 , $random = false ) {

    global $wpdb;

    $query_string = " SELECT * FROM " . DB_QUOTES . " QUOTES_TABLE" .
        " WHERE EXISTS ( SELECT * FROM " . DB_POSTS . " POSTS_TABLE " .
        " WHERE QUOTES_TABLE.post_id = POSTS_TABLE.ID AND POSTS_TABLE.post_status = 'publish') ";

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
 * Gets the quotes values from the database by the post's id.
 *
 * @param BIGINT $post_id  - The post's id with the quotes values we wish to obtain
 *
 * @return OBJECT - Row object if the query was successful, NULL otherwise
 */
function BH_quotes_get_post_quotes( $post_id ) {

    global $wpdb;

    $query_string = " SELECT * FROM " . DB_QUOTES   .
                    " WHERE post_id = %d";


    $query_result = $wpdb->get_results( $wpdb->prepare( $query_string  , $post_id ) );

    return ( $query_result );

} // BH_quotes_get_post_quotes




/**
 * Insert's a quote value to the database.
 *
 * @param string $quote    - The $quote to insert
 * @param BIGINT $post_id - The post id the $quote is associate with
 *
 * @return returns false if the row could not be inserted. Otherwise,
 *                 it returns the number of affected rows (which will always be 1).
 */
function BH_add_quote( $quote , $post_id ) {

    global $wpdb;

    sanitize( $quote );

    if ( (empty( $quote )  || empty( $post_id )) OR (!is_numeric( $post_id ))  )
    {
        return ( false );
    }

    $data_to_insert = array(
        'quote_value'      => $quote  ,
        'post_id'          => $post_id
    );

    $data_format = 	array( '%s' ,  '%d' );

    $query_result = $wpdb->insert( DB_QUOTES , $data_to_insert , $data_format );

    return ( $query_result );

} // BH_add_quote



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
 * @param Array  $terms   - Array of quotes to insert
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
function BH_add_quotes( $terms , $post_id ) {

    global $wpdb;


    // Check the validity of the post's id
    if ( ( empty( $post_id )) OR (!is_numeric( $post_id ) )  )
    {
        return ( false );
    }


    // The main query - multiple insert SQL statement, because of lack of support in the current Wordpress WPDB object
    $quotes_terms_insert_query = "INSERT INTO " . DB_QUOTES  . " ( quote_value  , post_id) VALUES ";

    // Holds the MySql SQL INSERT INTO statement which is in the format of ( value 1 , value 2 , ... )
    $query_values = array();

    foreach( $terms as $index => $term )
    {
        sanitize( $terms[$index] );

        if ( (empty( $terms[$index] ) )  )
        {
            return ( false );
        }

        $query_values[] = $wpdb->prepare( "(%s,%d)", $terms[$index]  , $post_id );
    }


    $quotes_terms_insert_query .= implode( ",\n", $query_values );

    $query_result = $wpdb->query( $quotes_terms_insert_query );

    return ( $query_result );

} // BH_add_dictionary_terms

/**
 * updates a dictionary term and it's value to the database.
 *
 * @param string $post_id   - The post id the quote is associate with
 * @param string $quote_id  - The quote id to insert
 * @param string $quote     - The quote value to insert
 *
 * @return  returns the number of rows updated, or false if there is an error
 */
function BH_update_quote( $post_id , $quote_id , $quote = NULL  ) {

    global $wpdb;

    sanitize( $quote );

    if ( (empty( $quote ) || empty( $post_id ) || empty( $quote_id ) ) OR
         (!is_numeric( $post_id ))  OR (!is_numeric( $quote_id ))  )
    {
        return ( false );
    }


    $data_to_update = array();

    $data_to_update['quote_value']  = $quote;


    $what_to_update =  array(
        'post_id'            => $post_id,
        'quote_id' => $quote_id
    );


    $query_result = $wpdb->update( DB_QUOTES , $data_to_update , $what_to_update );

    return ( $query_result );

} // BH_update_dictionary_term



/**
 * Delete's a quote from the database.
 *
 * @param BIGINT $quote_id - The quote id to delete
 *
 * @return
 */
function BH_delete_quote( $quote_id ) {

    global $wpdb;

    $data_to_delete = array(
        'quote_id'  => $quote_id
    );

    $data_format = 	array( '%d' );

    $query_result = $wpdb->delete( DB_QUOTES , $data_to_delete , $data_format);

    return ( $query_result );

} // BH_delete_quote

/**
 * Delete's  multiple quotes from the database.
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
function BH_delete_quotes( $terms_pk ) {

    global $wpdb;


    // Check if we have values
    if ( ( empty( $terms_pk ) )  )
    {
        return ( false );
    }


    // The main query - multiple DELETE SQL statement, because of lack of support in the current Wordpress WPDB object
    $quotes_terms_delete_query = "DELETE FROM " . DB_QUOTES . "  WHERE quote_id IN (";

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
    $quotes_terms_delete_query .= implode( ",", $terms_pk ) . ")";

    $query_result = $wpdb->query( $quotes_terms_delete_query );

    return ( $query_result );

} // BH_delete_dictionary_terms




/**
 * Delete's all the quotes of spesifc post from the database.
 *
 * @param int $post_id - The post id to delete
 *
 * @return returns  false \ NULL on error
 *
 */
function BH_delete_all_post_quotes( $post_id )
{
    global $wpdb;

    $data_to_delete = array(
        'post_id'  => $post_id
    );

    $data_format = 	array( '%d' );

    $query_result = $wpdb->delete( DB_QUOTES , $data_to_delete , $data_format);

    return ( $query_result );
}