<?php
/**
 * Show all the stories
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>

<?php if( $story_query->have_posts() ): ?>

<div class="royw">

	<?php while ( $story_query->have_posts()) : $story_query->the_post(); ?>

		<?php
			 // Get the story meta
			 $stories_meta = get_story_meta_data( array( STORY_META_ARRAY_STUDENT_NAME , STORY_META_ARRAY_AUTHOR_NAME , STORY_META_ARRAY_SCHOOL_ONLY ) );
		?>
		
		
		<?php
			 // Show a story
			 include(locate_template('views/story/archive/loop-item.php'));

		?>
			

	<?php
		  endwhile;
		  show_wp_pagenavi( $story_query , false );
		  wp_reset_postdata();
	?>

</div>



<?php else: ?>

    <?php get_template_part('views/components/not-found'); ?>

<?php endif; ?>