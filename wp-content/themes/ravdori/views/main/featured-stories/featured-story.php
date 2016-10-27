<?php
/**
 * This view outputs
 *
 *
 * @package    views/main
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */



$images       = array( wp_get_attachment_image( get_field('acf-story-images-adult-past') , 'full' ) , wp_get_attachment_image( get_field('acf-story-images-adult-child') , 'full' ) );
$randomNumber = rand(0, (count( $images ) - 1));

$image = $images[$randomNumber];
?>
<figure>
<div class="row">
<?php if ( get_field('acf-story-images-adult-past') ): ?>
    <div class="col-xs-8 text-center feature-story-image">
        <a href="<?php echo get_the_permalink(); ?>">
            <?php echo $image; ?>
        </a>
    </div>
<?php endif; ?>

</div>

<div class="featured-story-block">
    <div class="featured-story-heading">
        <a href="<?php echo get_the_permalink(); ?>">
            <h3><?php the_title(); ?></h3>
        </a>
        <p class="featured-story-text"><?php the_excerpt(); ?></p>
    </div>
</div>
</figure>

