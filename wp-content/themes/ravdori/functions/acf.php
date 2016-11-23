<?php
/**
 * All Advanced Custom Fields related functions
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



/*
 *
 *
 * ACF Taxonomy Depth Rule
 *
 * Taxonomy depth rule for advanced custom fields, easily display field groups depending on the taxonomy terms depth.
 * You can choose different operators for your rule test (equals, not equals, greater than, less than) and due to my requirements this is also compatible with Shopp.
 *
 * @see https://github.com/LightboxDev/ACF-Taxonomy-Depth-Rule
 *
 * */

//ADD RULE TO SECTION
function acf_location_rules_types( $choices )
{
    $choices['Other']['taxonomy_depth'] = 'Taxonomy Depth';
    return $choices;
}
add_filter('acf/location/rule_types', 'acf_location_rules_types');


//MATCHING OPERATORS
function acf_location_rules_operators( $choices )
{
    //BY DEFAULT WE HAVE == AND !=
    $choices['<'] = 'is less than';
    $choices['>'] = 'is greater than';
    return $choices;
}
add_filter('acf/location/rule_operators', 'acf_location_rules_operators');

//POPULATE LIST WITH OPTIONS
function acf_location_rules_values_taxonomy_depth( $choices )
{
    for ($i=0; $i < 6; $i++)
    {
        $choices[$i] = $i;
    }
    return $choices;
}
add_filter('acf/location/rule_values/taxonomy_depth', 'acf_location_rules_values_taxonomy_depth');


//MATCH THE RULE
function acf_location_rules_match_taxonomy_depth( $match, $rule, $options )
{
    $depth = (int) $rule['value'];
    if(isset($_GET['page']) && $_GET['page'] == SCHOOLS_TAXONOMY && isset($_GET['id']))
    {
        $term_depth = (int) count(get_ancestors($_GET['id'], SCHOOLS_TAXONOMY));
    }
    elseif(isset($_GET['taxonomy']) && isset($_GET['tag_ID']))
    {
        $term_depth = (int) count(get_ancestors($_GET['tag_ID'], $_GET['taxonomy']));
    }

    $term_depth = 4;

    if($rule['operator'] == "==")
    {
        $match = ($term_depth == $depth);
    }
    elseif($rule['operator'] == "!=")
    {
        $match = ($term_depth != $depth);
    }
    elseif($rule['operator'] == "<")
    {
        $match = ($term_depth < $depth);
    }
    elseif($rule['operator'] == ">")
    {
        $match = ($term_depth > $depth);
    }
    return $match;
}
add_filter('acf/location/rule_match/taxonomy_depth', 'acf_location_rules_match_taxonomy_depth', 10, 3);



/* Limit */
function my_relationship_query( args, field, post_id ) {
	
    // only show children of the current post being edited
    args['posts_per_page'] = 20;
	
	
	// return
    return args;
    
}
add_filter('acf/fields/relationship/query/name=acf-main-banner-selected-stories', 'my_relationship_query', 10, 3);
add_filter('acf/fields/relationship/query/name=acf-main-ravdori-picture-line-repeater-url-inner	', 'my_relationship_query', 10, 3);
