<?php
/**
 * View of single story post
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>
<section class="page-content">

    <div class="container">

        <div class="row">

            <div class="col-xs-4">
                <?php
                get_template_part('views/sidebar/sidebar');
                ?>
            </div>

            <div class="col-xs-8 white-shadow-box">
                <article class="post single-story print-only" id="post-<?php the_ID(); ?>">
                    <?php
                    // content
                    if (have_posts()) : the_post(); ?>
                        <?php get_template_part('views/story/meta'); ?>
                        <?php get_template_part('views/story/loop'); ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

</section>