<?php
/**
 * The template's shortcodes
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



/**
 * Get a hierarchical "tree" structure containg a category and it's children
 *
 * @param (int) $parent - The category parent ID
 * @param (string) $categories - a categories structure from a get_categories() call
 * @return hierarchical "tree" structure containg a category and it's children
 *
 */
function get_cat_tree( $parent , $categories ) {
    $result = array();
    foreach($categories as $category){
        if ($parent == $category->category_parent) {
            $category->children = get_cat_tree($category->cat_ID,$categories);
            $result[] = $category;
        }
    }
    return $result;
}



/**
* Returns an  array containing the number of schools and cities in the db
*/
function get_cities_schools_count() {
	
	
$schools_data =  get_categories( array( 'taxonomy' => SCHOOLS_TAXONOMY , 'hide_empty' => true )  );
$tax_tree     =  get_cat_tree ( 0 , $schools_data );


$citiesCounter  = 0;	
$schoolsCounter = 0;

// Iterate all the countries
foreach ( $tax_tree as $country ) {
	
	// Get all the districts of the country
	$districts = $country->children;

	foreach ( $districts as $district ) {
		
		// Each district children are cities, so we just need to count them
		$citiesCounter += count( $district->children );
		
		$cities = $district->children;
		foreach ( $cities as $city ) {
		
				// Each city children are schools, so we just need to count them
				$schoolsCounter += count( $city->children );
		}
		
	}

}


return ( array ( 'cities' => $citiesCounter , 'schools' => $schoolsCounter ) );

}



// Show a running number element
function BH_sc_running_counter( $atts ) {

	// Attributes
	 extract( shortcode_atts( array(
			'number' => '0',
			'title' => '',
      ), $atts ) );
	
	$number_to_show = 0;
	
	switch ( $number ) {
		
		case "cities":
		
			$cities_count = get_cities_schools_count();
			$number_to_show = $cities_count['cities'];
			
		break;
		
		
		case "schools":
		
			$schools_count = get_cities_schools_count();
			$number_to_show = $schools_count['schools'];
			
		break;
		
		
		case "stories":
			
			$number_to_show = wp_count_posts( STORY_POST_TYPE )->publish;
			
		break;

		
		default:
		
			if ( is_numeric( $number ) ){
				$number_to_show = $number;
			}
		  
	};
	

wp_enqueue_script( 'running-numbers' );
ob_start();?>

<div class="running-numbers__cube counter">
		
		<div class="running-numbers__cube__number timer count-title count-number" data-to="<?php echo $number_to_show;?>" data-speed="1500">
		</div>
		
		<div class="running-numbers__cube__title">
			<?php echo $title; ?>
		</div>
		
</div>
<?php	
$contents = ob_get_contents(); 
ob_end_clean(); 

return $contents;
}
add_shortcode( 'running-counter', 'BH_sc_running_counter' );



