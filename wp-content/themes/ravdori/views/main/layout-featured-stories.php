<?php
/**
 * This view outputs the main page featured stories area
 *
 *
 * @package    views/main
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

// The maximum number of the story custom post type to query
// and show in the featured stories area
define ( 'MAIN_PAGE_MAX_FEATURED_STORIES' , 3 );



$type  = 'story';
$featured_story_category = get_field( 'acf-options-featured-stories-category' ,'options' );
$featured_story_category = $featured_story_category[0];

$args  = array (
                    'post_type'      => $type,
                    'post_status'    => 'publish',
                    'cat'            => $featured_story_category,
                    'posts_per_page' => MAIN_PAGE_MAX_FEATURED_STORIES ,
               );

$featured_story_query = null;
$featured_story_query = new WP_Query( $args );

?>


<div class="row featured-stories user-details">


<? if( $featured_story_query->have_posts() ): ?>
    <div id="polaroid">

        <?php while ( $featured_story_query->have_posts()) : $featured_story_query->the_post(); ?>
            <div class="col-xs-4">
                <?php include(locate_template('views/main/featured-stories/featured-story.php')); ?>
            </div>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>

    </div><!--end polaroid-->
<?php endif; ?>








<?php

$featured_story_query_post_count = $featured_story_query->found_posts;

if ( $featured_story_query_post_count < MAIN_PAGE_MAX_FEATURED_STORIES ):


       // remove_all_filters('posts_orderby');

        $args  = array (
                            'post_type'      => $type      ,
                            'post_status'    => 'publish'  ,
                            'cat'            => '-' . $featured_story_category ,
                            'posts_per_page' => ( MAIN_PAGE_MAX_FEATURED_STORIES - $featured_story_query_post_count ) ,
                            'orderby'        => 'rand',
                       );

        $story_query = null;
        $story_query = new WP_Query( $args );

?>

    <? if( $story_query->have_posts() ): ?>
    <div id="polaroid">
        <?php while ( $story_query->have_posts()) : $story_query->the_post(); ?>
            <div class="col-xs-4">
                <?php include(locate_template('views/main/featured-stories/featured-story.php')); ?>
            </div>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    </div>
    <?php endif; ?>

<?php endif; ?>

</div>

