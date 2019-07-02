<?php
/**
 * This view contains Facebook's share and like button
 *
 * @package    views/social-networks/facebook
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>
<?php $url=urlencode(get_the_permalink()); ?>
<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
      //$image = $thumb['0'];
?>

<div class="fb-share-button">
<a onClick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[url]=<?php echo $url; ?>&amp;&p[images][0]=<?php echo $image;?>', 'sharer', 'toolbar=0,status=0,width=548,height=325');" target="_parent" href="javascript: void(0)">
        <span class="social-icon"><i class="fab fa-facebook-f"></i></span>
</a>
</div>