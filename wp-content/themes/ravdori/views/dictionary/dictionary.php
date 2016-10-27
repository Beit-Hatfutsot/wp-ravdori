<?php
/**
 * This view shows all the dictionary terms and values in the front end,
 * according to the selected letter, with pagination.
 *
 *
 * @package    views/dictionary
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>
<?php

// Get the current_letter query var which was set from _GET in the letters view (views/dictionary/letters)
$current_letter = get_query_var( 'current_letter' , 'א' );

// Init the current page number for the pagination from the _GET variable (set to 1 if empty)
$page_number = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;

// Get the dictionary terms corresponding to the letter
$dictionary_terms = BH_dictionary_get_letter_terms( $current_letter , $page_number );

// Save the total terms in the query (It will be used in the pagination ),
// and remove it, to hide it in the front end.
$term_count = $dictionary_terms['post_count'];
unset( $dictionary_terms['post_count'] );

?>

<?php

$post_id = get_the_ID();

if ( !empty( $dictionary_terms ) ):

                foreach ( $dictionary_terms as $term ): ?>

                            <article class="post dictionary-term" id="post-<?php echo $post_id; ?>">

                                <div class="title"><?php echo stripslashes( $term->dictionary_term ); ?></div>
                                <div class="description"><?php echo stripslashes( $term->dictionary_value ); ?></div>
                                <div class="term-post-url">
                                        <?php echo __( 'מתוך: ' , 'BH' ); ?>
                                        <a href="<?php echo get_the_permalink( $term->post_id )?>">
                                            <?php echo get_the_title( $term->post_id ); ?>
                                        </a>
                                </div>
                            </article>

                <?php endforeach; ?>

                <?php create_pagination( $term_count , $page_number , MAX_TERMS_PER_PAGE ) ?>

<?php else: ?>
    <?php get_template_part('views/components/not-found'); ?>
<?php endif; ?>