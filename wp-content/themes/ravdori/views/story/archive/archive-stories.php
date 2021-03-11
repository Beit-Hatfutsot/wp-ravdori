<?php
/**
 * Show all the stories
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>

<?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args  = array (
                        'post_type'      => STORY_POST_TYPE,
                        'post_status'    => 'publish',
                        'paged'          => $paged,
                        'posts_per_page' => MAX_ROWS_PER_PAGE ,
                       );

        $story_query = null;
        $story_query = new WP_Query($args);


if( $story_query->have_posts() ): ?>

<?php //show_wp_pagenavi( $story_query , true ); ?>

<?php while ( $story_query->have_posts()) : $story_query->the_post(); ?>

    <?php
         // Get the story meta
         $stories_meta = get_story_meta_data( array( STORY_META_ARRAY_STUDENT_NAME , STORY_META_ARRAY_AUTHOR_NAME , STORY_META_ARRAY_SCHOOL_ONLY , STORY_META_ARRAY_SUBJECTS ) );
    ?>
<div class="row archive-story">

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
      show_wp_pagenavi( $story_query , false );
      wp_reset_postdata();
?>



<?php else: ?>

    <?php get_template_part('views/components/not-found'); ?>

<?php endif; ?>