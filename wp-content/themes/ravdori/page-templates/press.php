<?php
/**
 * Template Name: Press
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

                    <h2><?php the_title(); ?></h2>

                    <article class="post" id="post-<?php the_ID(); ?>">
                        <?php
                        get_template_part('views/press/loop');
                        get_template_part('views/press/press');
                        ?>
                    </article>

                </div>

            </div>
        </div>

    </section>
<?php  get_footer(); ?>