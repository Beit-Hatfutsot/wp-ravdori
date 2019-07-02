<?php
/**
 * View of the preloader
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>
<script>
jQuery(window).load(function() {
	
  jQuery('#preloader').fadeOut("slow");
  
}); // window.load
</script>
<div id="preloader">
	<div id="loader">
		<?php $preloader_image =  get_bloginfo('stylesheet_directory') . '/images/loader.gif';?>
		<img src="<?php echo $preloader_image;?>" alt="preloader">
	</div>
</div>	