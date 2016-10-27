<?php
/**
 * Created by PhpStorm.
 * User: Roy
 * Date: 20/08/2015
 * Time: 13:53
 */


 
 

/*  By default Relevanssi cleans out ampersands (and other punctuation). 
 *  In order to keep them, you’ll have to modify the way the punctuation is handled 
 *  @see http://www.relevanssi.com/knowledge-base/words-ampersands-cant-found/
 */
function saveampersands_1($a) {
    $a = str_replace('&amp;', 'AMPERSAND', $a);
    $a = str_replace('&', 'AMPERSAND', $a);
    return $a;
}
add_filter('relevanssi_remove_punctuation', 'saveampersands_1', 9);
 

function saveampersands_2($a) {
    $a = str_replace('AMPERSAND', '&', $a);
    return $a;
}
add_filter('relevanssi_remove_punctuation', 'saveampersands_2', 11); 
 
 
 

function rlv_fix_order($orderby) {
    return "relevance";
}
add_filter('relevanssi_orderby', 'rlv_fix_order');
 
 /* In order to be able to add and work with 
  * custom query vars there is a need to add them to the public query variables available to WP_Query. 
  */
function add_query_vars_filter( $vars ){
  $vars[] = "exactsearch";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );



function rlv_asc_date($query) {
	
    $query->set('orderby', 'post_date');
    $query->set('order', 'DESC');
	
	/*
	$is_exact_search = '';//get_query_var('exactsearch');
	if ( $is_exact_search == 'true' )
	{
		$search_string = '"' . get_query_var('s') . '"';
		$query->set('s', $search_string );
	}*/
	
    return $query;
}
//add_filter('relevanssi_modify_wp_query', 'rlv_asc_date');


 
function custom_field_weights($match) {
	
	$studentName = get_post_meta($match->doc, 'acf-story-student-fname', true);
	
	$searched_string = get_query_var('s');


	if ( $searched_string == $studentName ) { 
 		$match->weight = $match->weight * 2;
	}
	else {
		$match->weight = $match->weight / 2;
	}
	
	return $match;
}
add_filter('relevanssi_match', 'custom_field_weights'); 
 

 add_filter('relevanssi_match', 'cfdetail');
function cfdetail($match) {
	global $customfield_data;
	$customfield_data[$match->doc] = $match->customfield_detail;
	return $match;
}



add_filter('relevanssi_match', 'rlv_cf_boost');
function rlv_cf_boost($match) {
	$detail = unserialize($match->customfield_detail);
	if (!empty($detail)) {
		if (isset($detail['acf-story-student-fname'])) {
			$match->weight = $match->weight * 10;
		}
	}
	return $match;
}


 
 
/* 
function extra_user_weight($match) {
	$post_type = relevanssi_get_post_type($match->doc);
	if ("user" == $post_type) {
		$match->weight = $match->weight * 2;
	}
	return $match;
}
add_filter('relevanssi_match', 'extra_user_weight');
*/

 
 
add_action( 'wp_ajax_nopriv_search_get_schools_ajax' , 'search_get_schools_ajax' );
add_action( 'wp_ajax_search_get_schools_ajax'        , 'search_get_schools_ajax' );
function search_get_schools_ajax()
{

    if ( isset($_REQUEST) )
    {

        // Get the country ID from the user
        $cityId = $_POST['cityid'];


        // Get the top level (All the districts)
        $schools = get_terms ( SCHOOLS_TAXONOMY , array(
                                                            'hide_empty' => true,
                                                            'parent'     => $cityId,
                                                            'orderby'    => 'name',
                                                            'order'      => 'ASC',

                                                        )
                              );


        $outputString = null;

        if ( ! empty( $schools ) && ! is_wp_error( $schools ) )
        {

            //$outputString =  '<label for="school-select" class="title">מיון לפי בית ספר</label><br/>';
            $outputString .= '<select id="school-select" name="school-select">';

            $outputString .= '<option value="-1">כל בתי הספר</option> <br/>';

            foreach ($schools as $school)
            {
                $outputString .= "<option value='$school->term_id'>$school->name</option> <br/>";
            }
            $outputString .= '</select>';

            echo $outputString;

        }
        else
        {
            echo 'בית ספר לא נמצא';
        }

    }
    die();

}