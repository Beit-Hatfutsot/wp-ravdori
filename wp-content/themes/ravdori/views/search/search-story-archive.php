<?php
/**
 * Show all the stories by a query
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>
<?php 
if ( isset ( $_GET['city-select'] ) OR isset ( $_GET['school-select'] ) OR isset( $_GET['country'] ) OR  isset ( $_GET['subjects'] ) Or isset( $_GET['subtopics'] ) Or isset( $_GET['submit'] ) ):
?>
    <script>
        jQuery(function() {
            jQuery('html, body').animate({
                scrollTop: jQuery(".stories").offset().top - 30
            }, 2000);
         });
    </script>
<?php endif; ?>

<?php

$cityId   = null;
$schoolId = null;

if ( isset( $_GET['city-select'] ) )
{
    $cityId = cleanInput($_GET['city-select']);
}

if ( isset( $_GET['school-select'] ) )
{
    $schoolId = cleanInput($_GET['school-select']);
}

?>


<?php
$paged    = (get_query_var('paged')) ? get_query_var('paged') : 1;


if ( array_key_exists('new-school-selected' , $_GET)) {
	$paged = 1;
}

$searchBy = (get_query_var('searchby')) ? get_query_var('searchby') : null;



// Get the ordering
$orderby = filter_input(INPUT_GET, 'orderby', FILTER_SANITIZE_STRING);

if ($orderby == FALSE OR in_array( $orderby, [STORY_GET_PARAM__NEW_STORIES, STORY_GET_PARAM__OLD_STORIES, STORY_GET_PARAM__TITLE_DESC, STORY_GET_PARAM__TITLE_ASC] ) == FALSE ) {
	$orderby = STORY_GET_PARAM__NEW_STORIES;	
}

$order = 'desc';

switch ( $orderby ) {
	
	case STORY_GET_PARAM__NEW_STORIES:
		$orderby = 'date';
		$order   = 'desc';
	break;
	
	case STORY_GET_PARAM__OLD_STORIES:
		$orderby = 'date';
		$order   = 'asc';
	break;
	
	case STORY_GET_PARAM__TITLE_DESC:
		$orderby = 'title';
		$order   = 'desc';
	break;
	
	case STORY_GET_PARAM__TITLE_ASC:
		$orderby = 'title';
		$order   = 'asc';
	break;
	
};



$args  = array (
    'post_type'      => STORY_POST_TYPE,
    'post_status'    => 'publish',
    'paged'          => $paged,
    'posts_per_page' => MAX_ROWS_PER_PAGE ,
    'orderby' 		 => $orderby,   /*'date',  'orderby' => 'title', */
    'order' 	     => $order,
);


if ( $searchBy == 'city' ):

    if ( isset ( $cityId ) or isset ( $schoolId )) :


       $arrTerms = array();
       $arrTerms[] =  $cityId;

       if ( $schoolId != NULL AND $schoolId != -1 )
           $arrTerms[] = $schoolId;

       $args['tax_query'] = array(
                                    array(
                                            'taxonomy'         => SCHOOLS_TAXONOMY,
                                            'field'            => 'term_id',
                                            'operator'         => 'AND',
                                            'terms'            => $arrTerms, //array ( $cityId , $schoolId ),
                                            'include_children' => false
                                        )
                                );
    endif;

elseif ( $searchBy == 'topics' ):


    $subjects  = (get_query_var('subjectsArray'))  ? get_query_var('subjectsArray')  : -1;
    $subtopics = (get_query_var('subtopicsArray')) ? get_query_var('subtopicsArray') : -1;
    $langauges = (get_query_var('languagesArray')) ? get_query_var('languagesArray') : -1;

	
	$tax_query_args = array( 'relation' => 'AND' );
	
	if ( $subjects != -1 )
	{
		$tax_query_args[] =  array(
                                    'taxonomy'      => SUBJECTS_TAXONOMY,
                                    'field'         => 'term_id',
                                    'terms'         => $subjects,
                                );
	}
	
	
	if ( $subtopics != -1 )
	{
		$tax_query_args[] =   array(
                                    'taxonomy'      => SUBTOPICS_TAXONOMY,
                                    'field'         => 'term_id',
                                    'terms'         => $subtopics,
                                   );
	}
	
	
	if ( $langauges != -1 )
	{
		$tax_query_args[] =  array(
                                    'taxonomy'      => LANGUAGES_TAXONOMY,
                                    'field'         => 'term_id',
                                    'terms'         => $langauges,
                                );
	}
	
    $args['tax_query'] = $tax_query_args;



elseif ( $searchBy ==  'countries'):

    $countryId = ( isset ( $_GET['country'] ) AND ($_GET['country'] != null ) ) ? $_GET['country'] : -1;

    /*$args['tax_query'] = array(
        array(
            'taxonomy'  => SCHOOLS_TAXONOMY,
            'field'     => 'term_id',
            'terms'     => array ( $countryId  ),
            'include_children' => false
        )
    );*/


// WP_User_Query arguments
    $args_user_query = array ( 'role' => 'adult' );

// The User Query
    $user_query = new WP_User_Query( $args_user_query );

    $users_in_country = array();

// The User Loop
    if ( ! empty( $user_query->results ) )
    {
        foreach ( $user_query->results as $user )
        {
            $birthCountryID = get_field( 'acf-user-adult-birth-place', 'user_' . $user->ID );

            if ( $countryId == $birthCountryID )
            {
                $users_in_country[] = $user->ID;
            }

        }
    }

    $args['author__in'] = $users_in_country;

endif;


if ( $searchBy == 'city'  AND !isset ( $cityId ) AND  !isset ( $schoolId ))
{
	$args  = array ( 'post_type'  => 'NON_EXIST_CPT' );
}
else if( $searchBy == 'countries'  AND $countryId == -1 )
{
	$args  = array ( 'post_type'  => 'NON_EXIST_CPT' );
}


$story_query = null;
$story_query = new WP_Query( $args );
global $wp_query;
$wp_query = $story_query;

if( $story_query->have_posts() ): ?>

   <?php
		/**
		* Display the archive filtering header
		*/
		include( locate_template( 'views/components/preloader.php' ) );
	?>
			
	<?php
			/**
			* Display the archive filtering header
			*/
			include( locate_template( 'views/story/archive/header.php' ) );
	?>
			
<div class="stories">

	<?php show_wp_pagenavi( $story_query , true ); ?>
	
    <?php while ( $story_query->have_posts()) : $story_query->the_post(); ?>

       <?php
	   
	   		 // Get the story meta
			 $stories_meta = get_story_meta_data( array( STORY_META_ARRAY_STUDENT_NAME , STORY_META_ARRAY_AUTHOR_NAME , STORY_META_ARRAY_SCHOOL_ONLY , STORY_META_ARRAY_PUBLISH_DATE , STORY_META_ARRAY_TEACHER_NAME) );
				
			 // Show a story
			 include(locate_template('views/story/archive/loop-item.php'));

		?>
		
    <?php
    endwhile;
  //  if(function_exists('wp_pagenavi')) {
        echo '<div style="margin-top: 30px">';
            //wp_pagenavi( array( 'query' => $story_query ) );
		show_wp_pagenavi( $story_query , false );
        echo '</div>';
    //}
    wp_reset_postdata();
    ?>
</div>


<?php else: ?>

    <?php if ( count($_GET) >  1 ): ?>
        <div class="row">
            <div class="col-xs-12">
                <?php get_template_part('views/components/not-found'); ?>
            </div>
        </div>
    <?php endif; ?>

<?php endif; ?>