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

$searchBy = (get_query_var('searchby')) ? get_query_var('searchby') : null;


$args  = array (
    'post_type'      => STORY_POST_TYPE,
    'post_status'    => 'publish',
    'paged'          => $paged,
    'posts_per_page' => MAX_ROWS_PER_PAGE ,
    'orderby' 		 => 'date', /* 'orderby' => 'title', */
    'order' 	     => 'DESC',
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

    $countryId = isset ( $_GET['country'] ) ? $_GET['country'] : -1;

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

<div class="stories">
    <div class="row">
        <div class="col-xs-12 search-title">
            <h2> תוצאות חיפוש: </h2>
        </div>
    </div>
    <?php while ( $story_query->have_posts()) : $story_query->the_post(); ?>

        <?php
        // Get the story meta
        $stories_meta = get_story_meta_data( array( STORY_META_ARRAY_STUDENT_NAME , STORY_META_ARRAY_AUTHOR_NAME , STORY_META_ARRAY_SCHOOL_ONLY , STORY_META_ARRAY_SUBJECTS ) );
        ?>
        <div class="row archive-story" style="padding: 40px 0 0px; margin: 10px 0;">

            <div class="col-xs-3 image-container text-center">

                <a href="<?php the_permalink();?>" class="title">
                    <?php $imgChild = wp_get_attachment_image(get_field('acf-story-images-adult-child') , 'story-archive-thumb');?>
                    <?php echo $imgChild; ?>
                </a>

            </div>

            <div class="col-xs-5 meta-data">
                <a href="<?php the_permalink();?>" class="title"> <?php the_title(); ?> </a>
                <?php foreach ( $stories_meta as $story_meta ): ?>

                    <div>
                    <span>
                     <?php if ( $story_meta['meta_data'] ): ?>
                         <strong><?php echo $story_meta['meta_title']; ?></strong>
                         <?php echo $story_meta['meta_data']; ?>
                     <?php endif; ?>
                     </span>
                    </div>

                <?php endforeach; ?>

                <?php $secondary_text = get_field('acf-story-secondary-text'); ?>
                <?php if ( $secondary_text ): ?>
                    <div class="voffset2">
                        <?php echo $secondary_text; ?>
                    </div>
                <?php endif; ?>

            </div>

            <div class="col-xs-4 quote">
                <?php
                $story_quote =  BH_quotes_get_post_quotes( get_the_id() );

                if ( !empty( $story_quote ) ):
                    ?>
                    <blockquote>
                        <p>
                            <?php echo wp_trim_words(  htmlentities( stripslashes( $story_quote[0]->quote_value ) ) , 10 , '...' ); ?>
                        </p>
                    </blockquote>

                <?php    endif; ?>
            </div>

        </div>
    <?php
    endwhile;
    if(function_exists('wp_pagenavi')) {
        echo '<div style="margin-top: 30px">';
            wp_pagenavi( array( 'query' => $story_query ) );
        echo '</div>';
    }
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