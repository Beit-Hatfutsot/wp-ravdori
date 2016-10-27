<?php
/**
 * Template Name: Team
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>


<?php

/*
require_once(FUNCTIONS_DIR . 'story_uploader/' . 'progress.php');
$errArray = array();
$p        = new ProgressBar();


require_once(FUNCTIONS_DIR . 'story_uploader/' . 'uploader.php');
//parseStoriesFromCsv("6400-6444.csv");
  
  //$school_id_acf = SCHOOLS_TAXONOMY . '_' . '3521';
  //update_field( 'field_57e3de5b1df80' , '33.5' ,  $school_id_acf ); // acf-school-latitude	
  //update_field( 'field_57e3dea71df81' , '39' ,  $school_id_acf ); // acf-school-longitude		
  
  //addLocations();
exit;
*/

/*
//echo '<br>________________________________________<br><br>';
parseTest("1000-1800.csv");
//echo '<br>________________________________________<br><br>';
parseTest("1800-2600.csv");
//echo '<br>________________________________________<br><br>';
parseTest("2600-3400.csv");
//echo '<br>________________________________________<br><br>';
parseTest("3400-4200.csv");
//echo '<br>________________________________________<br><br>';
parseTest("5k-58k.csv");
//echo '<br>________________________________________<br><br>';
parseTest("58k-66k.csv");

*/

	/*
	
	global $bad_st;
	echo '<hr><em>BAD:</em> <br>';
	echo '<ol style="list-style-type:decimal;">';
	foreach ( $bad_st as $title )
	{

	echo  '<li>' .  $title .  '</li>';

	}
	echo '</ol>';
	*/
	
//exit;


/*

require_once(FUNCTIONS_DIR . 'story_uploader/' . 'uploader.php');
parseSchoolsFromCsv("schools.csv");
exit;

*/

/*
require_once(FUNCTIONS_DIR . 'story_uploader/' . 'uploader.php');
parseStoriesFromCsv("lost2.csv");
exit;
*/

/*
require_once(FUNCTIONS_DIR . 'story_uploader/' . 'uploader.php');

//parseStoriesFromCsv("0-1000.csv");
//parseStoriesFromCsv("1000-1800.csv");
//parseStoriesFromCsv("1800-2600.csv");
//parseStoriesFromCsv("2600-3400.csv");
//parseStoriesFromCsv("3400-4200.csv");
//parseStoriesFromCsv("5k-58k.csv");

parseStoriesFromCsv("58k-66k.csv");

exit;

*/


// The csv parser
/*require_once(FUNCTIONS_DIR . 'story_uploader/' . 'parsecsv.lib.php');



set_time_limit(0); 

 // Get all the stories into an array
 $csv       = new parseCSV( FUNCTIONS_DIR . 'story_uploader/xsl/' . '0-1000.csv'  );
 $storyData = $csv->data;

 
echo 'X:<br><ol>';
foreach ( $storyData as $story )
{

	echo  '<li>' .  $story["storyName"] . '</li>';

}
echo '</ol>';

*/

/*

$type = STORY_POST_TYPE;
$args=array(
  'post_type' => $type,
  'post_status' => 'publish',
  'posts_per_page' => -1,
  );
$stor = array();
$my_query = null;
$my_query = new WP_Query($args);
if( $my_query->have_posts() ) {
  while ($my_query->have_posts()) : $my_query->the_post(); ?>
  <?php  $stor[] =  get_the_id(); ?>
    <?php
  endwhile;
*/

 
 
 /*
 $stor = array_map(function($v) {
    return "'" . esc_sql($v) . "'";
}, $stor);

echo "IN (";
foreach ( $stor as $x )
{
	echo $x;
	echo ",";
}
echo ")";*/
/*$stor = implode(',', $stor);
$query = "IN (" . $stor . ")";
echo $query;
 
}*/
//wp_reset_query();  // Restore global post data stomped by the_post().

/*
$args=array(
  'post_type' => STORY_POST_TYPE,
  'post_status' => 'publish',
  'posts_per_page' => -1,
  );

$story_query = null;
$story_query = new WP_Query( $args );
global $wp_query;
$wp_query = $story_query;

if( $story_query->have_posts() ):
$i = 1;
	while ( $story_query->have_posts()) : $story_query->the_post();
		$terms = wp_get_post_terms( get_the_id() , LANGUAGES_TAXONOMY ); 
		
		if ( !$terms  ) 
		{
			$i++;
			the_title();
			echo '<br><br>';
			
		}
	endwhile;
echo 'Ttoal: ' . $i;
endif;
exit;*/

?>

<section class="page-content">

    <div class="container">

        <div class="row">

            <div class="col-xs-4 sidebar-col">
                <?php
                get_template_part('views/sidebar/sidebar');
                ?>
            </div>

            <div class="col-xs-8">

                <h2 class="title"><?php the_title(); ?></h2>

                <article class="post" id="post-<?php the_ID(); ?>">
                    <?php
                        get_template_part('views/team/team');
                    ?>
                </article>

            </div>

        </div>
    </div>

</section>
<?php  get_footer(); ?>