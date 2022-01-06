<?php
/**
 * Offcanvas menu
 *
 * Display current language as the button for the languages switcher
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2019 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


     $school_search_url    = get_field( 'acf-options-search-school'    , 'options' );
     $topics_search_url    = get_field( 'acf-options-search-subject'   , 'options' );
     $countries_search_url = get_field( 'acf-options-search-countries' , 'options' );
	 
?>

<!-- Pushy Menu -->
<nav class="pushy pushy-right">
    <div class="pushy-content">
					
			<div class="close-mobile-menu-button menu-btn"></div>

			 <div>
				 <a href="<?php echo HOME?>">
					<img src="<?php echo IMAGES_DIR . '/general/header/logo.png'?>" />
				 </a>
			 </div>
			 
			<?php if ( $school_search_url OR $topics_search_url OR $countries_search_url ): ?>
				<ul>
					<?php if ( $school_search_url ): ?>
						<li class="pushy-link"><a href="<?php echo $school_search_url; ?>"><?php _e('חפשו לפי ביה”ס/ישוב','bhchild'); ?></a></li>
					<?php endif; ?>
					
					<?php if ( $school_search_url ): ?>
						<li class="pushy-link"><a href="<?php echo $topics_search_url; ?>"><?php _e('חפשו לפי נושאים','bhchild'); ?></a></li>
					<?php endif; ?>
					
					<?php if ( $school_search_url ): ?>
						<li class="pushy-link"><a href="<?php echo $countries_search_url; ?>"><?php _e('חפשו לפי ארץ מוצא','bhchild'); ?></a></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
			
			<div>
							<div class="social-link-title">
								<?php _e('שתפו:', 'bhchild'); ?>	
							</div>
							
						     <?php 
							   $facebook_url = get_field( 'acf-options-header-facebook-url', 'options' );
							   $youtube_url  = get_field( 'acf-options-header-youtube-url', 'options' );
							   $twitter_url  = get_field( 'acf-options-header-twitter-url', 'options' );
							 ?>			
                             <ul class="social-links">
								 
								 <?php if ( $facebook_url ): ?>
									  <li class="social-facebook">
										 <a href="<?php echo $facebook_url; ?>" target="_blank">
												<span class="social-icon"></span>
										 </a>
									 </li>
								<?php endif;?>
								
								 <?php if ( $youtube_url ): ?>
									 <li class="social-youtube">
										 <a href="<?php echo $youtube_url; ?>" target="_blank">
												<span class="social-icon"></span>
										 </a>
									 </li>
								<?php endif;?>

								 <?php if ( $twitter_url ): ?>
									 <li class="social-twitter">
										 <a href="<?php echo $twitter_url; ?>" target="_blank">
											<span class="social-icon"></span>
										 </a>
									 </li>
								<?php endif;?>

                             </ul>

             </div>
						 
						 
    </div>
</nav>

<!-- Site Overlay -->
<div class="site-overlay"></div>