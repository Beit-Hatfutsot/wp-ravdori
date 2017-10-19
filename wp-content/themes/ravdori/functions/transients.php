<?php
/**
 * Handles all the transients in the website
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */


define ( 'LOCATION_TRANSIENT_ID'  , 'LOCATION_TRANSIENT' );


/**
 *
 */
function BH_get_cached_location()
{
    $posts = get_transient( LOCATION_TRANSIENT_ID );

    if( $posts === false )
    {
        $posts = get_taxonomy_hierarchy ( SCHOOLS_TAXONOMY );
        set_transient( LOCATION_TRANSIENT_ID , $posts,  DAY_IN_SECONDS );
    }

    return ( $posts );

}




/**
 * Return an array of full hierarchical taxonomy tree
 * @param $taxonomy - The taxonomy to query
 * @param int $parent - Specifies the root parent (default is 0 - the main root)
 * @return array - Array of the taxonomy in a "tree" form
 */
function get_taxonomy_hierarchy( $taxonomy, $parent = 0 ) {
    // only 1 taxonomy
    $taxonomy = is_array( $taxonomy ) ? array_shift( $taxonomy ) : $taxonomy;

    // get all direct decendents of the $parent
    $terms = get_terms( $taxonomy, array( 'parent' => $parent , 'hide_empty' => false ) );

    // prepare a new array.  these are the children of $parent
    // we'll ultimately copy all the $terms into this new array, but only after they
    // find their own children
    $children = array();

    // go through all the direct decendents of $parent, and gather their children
    foreach ( $terms as $term ){
        // recurse to get the direct decendents of "this" term
        $term->children = get_taxonomy_hierarchy( $taxonomy, $term->term_id );

        // add the term to our new array
        $children[ $term->term_id ] = $term;
    }

    // send the results back to the caller


    return $children;
}



// Used above in the usort as a compering method
function cmp( $a, $b ) { return strcmp( $a->name, $b->name ); }



/* Clear Transient */

// Create a simple function to delete the location transient
function term_delete_transient() {
     delete_transient( LOCATION_TRANSIENT_ID );
}
// Add the function to the creation, edit and deletion hooks of a taxonomy,  so it runs when categories/tags are edited / created / deleted
add_action( 'create_term'  , 'term_delete_transient' );
add_action( 'edit_term'    , 'term_delete_transient' );
add_action( 'delete_term'  , 'term_delete_transient' );