<?php
/**
 * Template Name: Advacned Search
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>
    <section class="page-content">

        <div class="container">

            <div>
                <?php get_template_part('views/search/search', 'advanced-story-archive'); ?>
            </div>
        </div>

    </section>
<?php  get_footer(); ?>