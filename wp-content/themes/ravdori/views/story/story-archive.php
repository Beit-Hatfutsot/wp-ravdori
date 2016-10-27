<?php
/**
 * View of story archive
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>
<section class="page-content">

    <div class="container">

        <div class="row">

            <div class="col-xs-8">
                <?php
                get_template_part('views/sidebar/sidebar');
                ?>
            </div>

            <div class="col-xs-4">
                <article class="post story-archive" id="post-<?php the_ID(); ?>">
                    <?php //get_template_part('views/story/meta'); ?>
            </div>
        </div>

    </div>
    </div>

</section>