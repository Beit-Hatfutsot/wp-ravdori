<?php
/**
 * This view outputs all the press posts (including pagination).
 * If no posts exists, related message will be shown.
 *
 *
 * @package    views/press
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */


// Get all the press posts

$paged = get_query_var('paged') ? get_query_var('paged') : 1;

$args = array(
    'post_type'        => PRESS_POST_TYPE ,
    'post_status'      => 'publish' ,
    'paged'	   		   => $paged ,
    'posts_per_page'   => MAX_ROWS_PER_PAGE ,
);


$press_query = null;
$press_query = new WP_Query($args);


if( $press_query->have_posts() ):

       while ( $press_query->have_posts() ) : $press_query->the_post(); ?>

        <div class="row voffset5">

            <div class="col-xs-12">

                <?php the_content(); ?>

            </div>

        </div>

    <?php endwhile; ?>

    <div class="row">
        <div class="col-xs-12 text-center">
            <?php if(function_exists('wp_pagenavi')) { wp_pagenavi( array( 'query' => $press_query )  ); } ?>
        </div>
    </div>

<?php else: ?>

<div class="row voffset5">
    <div class="col-xs-12 text-center">

        <div class="alert alert-warning">

            <strong><?php _e( 'לא נמצאו כתבות', 'BH' );?></strong>
        </div>
    </div>
</div>

<?php endif;

wp_reset_postdata();
?>