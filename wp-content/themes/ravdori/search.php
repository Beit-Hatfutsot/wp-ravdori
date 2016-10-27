<?php
/**
 * The Template for displaying search page
 *
 * @author 		Beit Hatfutsot
 * @package 	bh
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>
    <section id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php if ( have_posts() ) : ?>

                <header class="page-header">
                    <h1 class="page-title"><?php printf( __( 'תוצאות חיפוש עבור:  %s', 'BH' ), get_search_query() ); ?></h1>
                </header><!-- .page-header -->

                <?php
                // Start the loop.
                while ( have_posts() ) : the_post(); ?>

                    <?php
                    get_template_part( 'views/search/search', 'free' );

                endwhile;

                wp_pagenavi();

            // If no content, include the "No posts found" template.
            else :

                get_template_part('views/components/not-found');

            endif;
            ?>

        </main><!-- .site-main -->
    </section><!-- .content-area -->


<?php get_footer(); ?>