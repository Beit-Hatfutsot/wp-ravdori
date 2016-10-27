<?php
/**
 * Template Name: Search stories by location
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>
    <section class="page-content">

        <div class="container">

            <div class="row search-topbar">

                <div class="col-xs-12">

                    <?php
                    set_query_var( 'searchby' , 'city' );
                    ?>

                    <?php get_template_part('views/search/search', 'city-topbar'); ?>
                </div>

            </div>
            <div>
                   <?php get_template_part('views/search/search', 'story-archive'); ?>
            </div>
        </div>

    </section>
<?php  get_footer(); ?>