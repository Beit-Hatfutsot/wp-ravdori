<?php
/**
 * Template Name: Main
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>
<?php ?>
    <section class="page-content">

        <div class="container main-page">
            <?php
                get_template_part('views/main/layout', 'banner');
                get_template_part('views/main/layout', 'quotes-dictionary');
				get_template_part('views/main/layout', 'news');
                get_template_part('views/main/layout', 'rav-dori');
				get_template_part('views/main/layout', 'numbers-strip');
                get_template_part('views/main/layout', 'useful-links');
            ?>

        </div>

    </section>
<?php  get_footer(); ?>



<?php  get_footer(); ?>