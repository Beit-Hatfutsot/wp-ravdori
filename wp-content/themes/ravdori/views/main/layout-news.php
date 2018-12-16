<?php
/**
 * This view outputs the main page news and updates area
 *
 *
 * @package    views/main
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>

<?php

$text = get_field('acf-main-ravdori-news');

?>

<?php if( $text ): ?>

<div class="row">
    <div class="col-sm-3">
        <h2 class="title"><?php _e( 'חדשות ועדכונים' , 'BH'); ?></h2>
    </div>
</div>



<section id="homepage_ravdori" class="row">

    <?php if ( $text ): ?>
        <div class="col-sm-12">
            <div class="homepage-news">
                <?php echo $text; ?>
            </div>
        </div>
    <?php endif; ?>


</section>

<?php endif; ?>