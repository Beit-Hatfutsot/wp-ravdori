<?php
/**
 * Template Name: Quotes
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>

    <section class="page-content">

        <div class="container">

            <div class="row">

                <div class="col-xs-4 sidebar-col">
                    <?php
                    get_template_part('views/sidebar/sidebar');
                    ?>
                </div>

                <div class="col-xs-8">
                    <?php  get_template_part('views/quotes/loop');       ?>
                    <?php  get_template_part('views/quotes/quotes'); ?>
                </div>

            </div>
        </div>

    </section>
<?php  get_footer(); ?>