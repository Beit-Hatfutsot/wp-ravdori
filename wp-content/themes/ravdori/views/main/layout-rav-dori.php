<?php
/**
 * This view outputs the main page about the program area
 *
 *
 * @package    views/main
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>

<?php

$repeaterName = 'acf-main-ravdori-picture-line-repeater';
$text = get_field('acf-main-ravdori-the-program-text');

?>

<?php if( have_rows( $repeaterName ) OR $text ): ?>

<div class="row">
    <div class="col-sm-3">
        <h2 class="title"><?php _e( 'הקשר הרב הדורי' , 'BH'); ?></h2>
    </div>
</div>


<section class="row homepage-mobile-wizard-notice hidden-lg hidden-md">
	<div class="homepage-mobile-wizard-notice-text text-center col-sm-12">
			<div>
				<strong class="title">משתתפי התוכנית</strong>
				<div class="text">הוספת הסיפור שלכם מתאפשרת רק דרך מחשב</div>
			</div>
	</div>
</section>

<section id="homepage_ravdori" class="row bh-display-flex">

    <?php if ( $text ): ?>
        <div class="col-sm-5 col-homepage-about-the-program">
            <div class="homepage-about-the-program">
                <?php echo $text; ?>
            </div>
        </div>
    <?php endif; ?>

<?php if( have_rows( $repeaterName ) ): ?>
    <div class="col-sm-6">
        <?php $i = 1; ?>
        <?php while( have_rows( $repeaterName ) ): the_row();

            $image = get_sub_field('acf-main-ravdori-picture-line-repeater-picture');
            $text  = get_sub_field('acf-main-ravdori-picture-line-repeater-text');
            $link  = get_sub_field('acf-main-ravdori-picture-line-repeater-url-outer');
            $link  = ( $link == null ) ? get_sub_field('acf-main-ravdori-picture-line-repeater-url-inner') : $link;
        ?>

              <div class="row col-wrap <?php echo ( $i == 2 ? 'voffset3' : ''); ?>">
                  <?php if( $link ): ?>
                  <a href="<?php echo $link; ?>">
                  <?php endif; ?>

                      <div class="col-sm-4 no-left-padding">
                          <?php if( $image ): ?>
                            <img src="<?php echo $image; ?>">
                          <?php endif; ?>
                      </div>

                      <div class="col-sm-8 col">
                          <div class="homepage-about-the-program-links">
                              <?php echo $text; ?>
                          </div>
                      </div>

                  <?php if( $link ): ?>
                  </a>
                  <?php endif; ?>
              </div>

        <?php $i++; ?>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

</section>

<?php endif; ?>