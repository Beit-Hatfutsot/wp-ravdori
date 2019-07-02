<?php
/**
 * Template Name: Search stories by countries
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>
    <section class="page-content">

        <div class="container">

            <div class="row search-topbar">

                <div class="col-xs-12">
                    <?php


                    // Set the current_letter variable as a query variable,
                    // in order to share it with the calling template (views/search/search/search-subjects)
                    set_query_var( 'searchby' , 'countries' );

                    get_template_part('views/search/search', 'countries');

                    ?>
                </div>

            </div>
            <div>
                <?php get_template_part('views/search/search', 'story-archive'); ?>
            </div>
        </div>

    </section>
<?php  get_footer(); ?>