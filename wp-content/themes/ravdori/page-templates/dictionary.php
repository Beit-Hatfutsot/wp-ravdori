<?php
/**
 * Template Name: Dictionary
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>

    <section class="page-content">

        <div class="container">

            <div class="row change-cols-order">

                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 sidebar-col order-2">
                    <?php
                    get_template_part('views/sidebar/sidebar');
                    ?>
                </div>

                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 order-1">

                    <?php  get_template_part('views/dictionary/letters');    ?>
                    <?php  get_template_part('views/dictionary/loop');       ?>
                    <?php  get_template_part('views/dictionary/dictionary'); ?>

                </div>

            </div>
        </div>

    </section>
<?php  get_footer(); ?>