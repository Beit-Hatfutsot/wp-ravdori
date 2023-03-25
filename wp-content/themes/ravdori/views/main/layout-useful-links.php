<?php
/**
 * This view outputs the main page useful links area
 *
 *
 * @package    views/main
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>

<?php

$repeaterName = 'acf-main-useful-links-repeater';

?>

<?php if( have_rows( $repeaterName ) ): ?>

<div class="row">
    <div class="col-xs-12">
        <h2 class="title"><?php _e( 'קישורים נוספים' , 'BH' ); ?></h2>
    </div>
</div>


<div class="row useful-links">

    <div class="cycle-slideshow homepage-carousel"
         data-cycle-fx=carousel
         data-cycle-timeout=0
         data-cycle-next="#carousel-next"
         data-cycle-prev="#carousel-prev"
         data-cycle-youtube=true
         data-cycle-youtube-autostart=false
         data-cycle-slides=".carousel-slide"
         data-cycle-carousel-visible=3
         data-cycle-carousel-fluid=true
         data-cycle-log=false
         data-allow-wrap=false
		 data-cycle-swipe=true
		 data-cycle-carousel-slide-dimension=390
        >
    
	<?php $i = 1; ?>
    <?php while( have_rows( $repeaterName ) ): the_row();

        $image = get_sub_field('acf-main-useful-links-repeater-image');
        $text  = get_sub_field('acf-main-useful-links-repeater-text');
        $link  = get_sub_field('acf-main-useful-links-repeater-url-outer');
        $link  = ( $link == null ) ? get_sub_field('acf-main-useful-links-repeater-url-inner') : $link;
        $video_url = get_sub_field('acf-main-useful-links-repeater-video-url');
        $show_video = get_sub_field('acf-main-useful-links-repeater-show-video');
		$image_alt = image_alt_by_url($image);
		
		if ( ! $image_alt ) {

			if ( $text ) {
				$image_alt = $text;
			}
			else {
				$image_alt = 'קישור חיצוני' . ' ' . $i;
			}
		}
		
    ?>

        <?php if ( !$show_video AND $image ): ?>
            <div class="carousel-slide">
					<div class="carousel-content">
						<?php if( $link ): ?>
						 <a href="<?php echo $link; ?>" target="_blank">
						<?php endif; ?>

							<?php if ( $image ): ?>
								<img src="<?php echo $image;?>" alt="<?php echo $image_alt; ?>">
							<?php endif; ?>

							<?php if ( $text ): ?>
								<div class="carousel-slide-overlay">
									<?php echo $text; ?>
								</div>
							<?php endif;?>

						<?php if( $link ): ?>
						   </a>
						<?php endif; ?>
					</div>
			</div>
        <?php endif;?>
		

        <?php if ( $show_video AND $video_url ): ?>

            <div class="carousel-slide utube">
					<?php //echo wp_oembed_get( $video_url ); ?>
					<a href="https://www.youtube.com/watch?v=<?php echo $video_url?>" target="_blank">
						<div class="utube-wrap">
							<img src="https://img.youtube.com/vi/<?php echo $video_url?>/sddefault.jpg" alt="נגן ביוטיוב">
						</div>	
					</a>	
					
            </div>
        <?php endif;?>
	
	<?php $i++;?>
    <?php endwhile; ?>

    </div>


    <div id="carousel-prev" class="cycle-prev cycle-button">
		<div class="button-wrap"></div>
	</div>
    
	<div id="carousel-next"  class="cycle-next cycle-button">
		<div class="button-wrap"></div>
	</div>


</div>

<?php endif; ?>