<?php
/**
 * This view outputs the main page top banner
 *
 *
 * @package    views/main
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

?>

<?php

$show_random_stories = get_field( 'acf-main-banner-random' );

$args  = array (
                    'post_type'      => STORY_POST_TYPE,
                    'post_status'    => 'publish',
                    'showposts'      => '8',
                    'orderby'        => 'rand',
                );

if ( !$show_random_stories )
{
    $selected_stories = get_field('acf-main-banner-selected-stories');
	
    if ( $selected_stories )
        $args['post__in' ] = $selected_stories;
}

$story_query = null;
$story_query = new WP_Query($args);


if( $story_query->have_posts() ): ?>


    <section id="homepage-stories-slider" class="row">

        <div class="cycle-slideshow"
             data-cycle-timeout=50000
			 data-cycle-swipe=true
			 data-cycle-swipe-fx=scrollHorz
             data-cycle-fx=scrollHorz
             data-cycle-prev="#stories-slider-prev"
             data-cycle-next="#stories-slider-next"
             data-cycle-slides="> div"
             data-cycle-log=false
             data-cycle-pause-on-hover=true
             data-cycle-pager="#homepage-stories-pager"
             data-cycle-loader="wait"
            >


            <?php while ( $story_query->have_posts()) : $story_query->the_post(); ?>
                <div class="slide-container grow">
					 <div class="slide-content">	
							<a href="<?php echo get_the_permalink(); ?>" aria-label="לקריאת הסיפור">
								<div class="slide-caption">
									<h2><?php the_title();?></h2>
									
									<?php $second_text = get_field('acf-story-secondary-text'); ?>
									
									<?php if ( $second_text ): ?>
										<h3><?php echo $second_text;?></h3>
									<?php endif; ?>	
									
									<div class="read-story-button"><?php _e( 'לקריאת הסיפור' , 'BH' ); ?></div>
								</div>
								<div class="slide-image">
								<?php echo wp_get_attachment_image( get_field('acf-story-images-adult-past') , 'homepage-slider-thumb' );?>
								</div>
							</a>
					</div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_query(); ?>


        </div>
        <div class="stories-slider-nav">
            <a href=# id="stories-slider-next" aria-label="לשקופית הבאה"></a>
            <a href=# id="stories-slider-prev" aria-label="לשקופית הקודמת"></a>
        </div>

        <div id="homepage-stories-pager" class="text-center voffset3"></div>
    </section>

<?php endif; ?>