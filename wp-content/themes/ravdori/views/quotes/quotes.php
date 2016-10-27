<?php
/**
 * This view shows all the quotes in the front end,
 *
 *
 * @package    views/quotes
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>
<?php

// Init the current page number for the pagination from the _GET variable (set to 1 if empty)
$page_number = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;

$quotes = BH_quotes_get_all_quotes( $page_number );

// Save the total terms in the query (It will be used in the pagination ),
// and remove it, to hide it in the front end.
$term_count = $quotes['post_count'];
unset( $quotes['post_count'] );

?>

<?php

$post_id = get_the_ID();

if ( !empty( $quotes ) ):

    foreach ( $quotes as $quote ): ?>

        <article class="post quote-term" id="post-<?php echo $post_id; ?>">

            <div class="title"><?php echo stripslashesFull( $quote->quote_value ); ?></div>
            <div class="term-post-url">
                <?php echo __( 'מתוך: ' , 'BH' ); ?>
                <a href="<?php echo get_the_permalink ( $quote->post_id )?>">
                    <?php echo get_the_title( $quote->post_id ); ?>
                </a>
            </div>
        </article>

    <?php endforeach; ?>

    <?php create_pagination( $term_count , $page_number , MAX_QUOTES_PER_PAGE ) ?>

<?php else: ?>
    <?php get_template_part('views/components/not-found'); ?>
<?php endif; ?>