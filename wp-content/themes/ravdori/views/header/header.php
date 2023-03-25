<?php
	
     // Init the main menu
	$args = array(
		'theme_location'		=> 'main-menu',
		'container'		    	=> false,
		'items_wrap'			=> '%3$s',
		'link_before'			=> '<span>',
		'link_after'			=> '</span>',
		'walker'				=> new BH_main_walker_nav_menu,
		'echo'				    => 0
	);
	
     $main_menu       = wp_nav_menu($args);
     $wizard_page_url = get_field( 'acf-options-wizard-page-url' , 'options' );

     $school_search_url    = get_field( 'acf-options-search-school'    , 'options' );
     $topics_search_url    = get_field( 'acf-options-search-subject'   , 'options' );
     $countries_search_url = get_field( 'acf-options-search-countries' , 'options' );
	 
?>

<header>

	<div class="container">
							
               <div id="header-top" class="row">
                 <div class="col-xs-7 col-sm-5 col-md-6 col-lg-6">

                     <div class="row">
                         <div class="col-sm-10 col-md-5 col-lg-6 header-logo-container">
                             <a class="ravdori-logo" style="max-width: 68%; display: inline-block;" href="<?php echo HOME?>">
                                <img src="<?php echo IMAGES_DIR . '/general/header/logo.png'?>" style="margin-top: 6px;" alt="הקשר הרב דורי"/>
                             </a>
                             <a class="gov-logo" style="max-width: 29%; display: inline-block;" href="https://www.gov.il/he/departments/ministry_for_social_equality/govil-landing-page" target="_blank">
                                <img src="<?php echo IMAGES_DIR . '/general/ministry_for_social_equality.png'?>" style="margin-top: 6px; max-height: 40px;" alt="המשרד לשיוויון חברתי"/>
                             </a>
                         </div>

                         <div class="visible-lg visible-md col-sm-8 col-lg-6 voffset3 col-md-7">
                             <div class="push-left visible-lg visible-md">
                                <span style="color:#666766;font-size: 16px;"><span class="bh-translate"><?php _e('חפש לפי' , 'BH'); ?></span>:</span>

                                 <a href="<?php echo $school_search_url;?>" class="no-underline">
                                     <div class="search-button">
                                             <div class="search-button-caption">
                                                    <span class="bh-translate"><?php _e( 'ביה”ס / ישוב' , 'BH' ); ?></span>
                                             </div>
                                     </div>
                                 </a>

                                 <a href="<?php echo $topics_search_url;?>" class="no-underline">
                                     <div class="search-button">

                                            <div class="search-button-caption voffset1">
                                                    <span class="bh-translate"><?php _e( 'נושאים' , 'BH' ); ?></span>
                                            </div>
                                     </div>
                                 </a>

                                 <a href="<?php echo $countries_search_url;?>" class="no-underline">
                                     <div class="search-button origin-country">
                                             <div class="search-button-caption voffset1">
                                                    <span class="bh-translate"><?php _e( 'ארץ מוצא' , 'BH' ); ?></span>
                                             </div>

                                     </div>
                                 </a>

                             </div>

                         </div>

                     </div>

                 </div>

                 <div class="col-xs-3 col-sm-5 col-md-6">
                     <div class="row">
                         <div class="hidden-xs col-sm-12 col-md-6  col-lg-8 header-search-container">
                            <?php  get_template_part('views/components/search', 'form'); ?>
                            <?php  get_template_part('views/components/advanced-search', 'form'); ?>
                         </div>
						 
						 <div class="col-xs-push-2 col-xs-12 visible-xs header-mobile-search-button-container">
							<button class="btn btn-mobile-search"><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span style="background: #3b3b3b;color: #fff;" class="visually-hidden">חיפוש</span></button>
						 </div>

                         <div class="col-sm-5 col-lg-4 visible-lg visible-md col-md-6">
								
						     <?php 
							   $facebook_url = get_field( 'acf-options-header-facebook-url', 'options' );
							   $youtube_url  = get_field( 'acf-options-header-youtube-url', 'options' );
							   $twitter_url  = get_field( 'acf-options-header-twitter-url', 'options' );
							 ?>			
                             <ul class="social-links">
								 
								 <?php if ( $facebook_url ): ?>
									  <li class="social-facebook">
										 <a href="<?php echo $facebook_url; ?>" target="_blank" aria-label="קישור לפייסבוק">
											<span class="social-icon"></span>
										 </a>
									 </li>
								<?php endif;?>
								
								 <?php if ( $youtube_url ): ?>
									 <li class="social-youtube">
										 <a href="<?php echo $youtube_url; ?>" target="_blank" aria-label="קישור ליוטיוב">
											<span class="social-icon"></span>
										 </a>
									 </li>
								<?php endif;?>

								 <?php if ( $twitter_url ): ?>
									 <li class="social-twitter">
										 <a href="<?php echo $twitter_url; ?>" target="_blank" aria-label="קישור לטוויטר">
											<span class="social-icon"></span>
										 </a>
									 </li>
								<?php endif;?>

                             </ul>

                         </div>

                     </div>
                 </div>
				
				
				 <div class="col-sm-2 col-xs-2 visible-sm visible-xs col-md-2">
					<a href="#" class="menu-btn" aria-label="פתיחת תפריט מובייל">
						<div class="hamburger-menu-button"></div>
					</a>
				 </div>
				

               </div>

             
              <div id="header-bottom" class="row">
              
                    <div id="navbar-container" class="col-sm-12 col-lg-10">
               			<div class="navbar navbar-default nav-scroller" role="navigation">
               				<?php
               
               					// main menu
               					if ($main_menu) :
               					
               						echo '<nav class="main-menu nav-scroller-nav">';
               							echo '<ul class="nav navbar-nav nav-scroller-content">';
               							
               								echo $main_menu;
               								
               							echo '</ul>';
               						echo '</nav>';

               					endif;
               					
               				?>
							<button class="nav-scroller-btn nav-scroller-btn--left">
								<span class="nav-scroller-btn__text" aria-hidden="true"><?php _e('עוד...','BH'); ?></span>
								<span class="nav-scroller-btn__arrow" aria-hidden="true" >
									<div class="nav-scroller-arrow" aria-hidden="true"></div>
								</span>
								<span class="visually-hidden" aria-hidden="true" style="background: #3b3b3b;color: #fff;"><?php _e('עוד...','BH'); ?></span>
							</button>
							
							<button class="nav-scroller-btn nav-scroller-btn--right">
							
								<span class="nav-scroller-btn__arrow" aria-hidden="true">
										<div class="nav-scroller-arrow" aria-hidden="true"></div>
								</span>
								<span class="visually-hidden" style="background: #3b3b3b;color: #fff;"><?php _e('עוד...','BH'); ?></span>
							</button>
               			</div>
                    </div>
                    
                     <div id="image-upload-and-social-container" class="col-xs-2">
						<?php  get_template_part('views/languages/header/wizard-languages-switcher/language','switcher'); ?>
                     </div>
					 
					 
          	</div>

            <?php if ( !is_page_template( 'wizard.php' ) ): ?>
                <div class="homepage-title-container row">
                    <h1><span class="bh-translate"><?php _e( 'מאגר סיפורי מורשת' , 'BH'); ?></span></h1>
                    <h2 class="header-subtitle"><span class="bh-translate"><?php _e( 'אוצר אנושי מתוכנית הקשר הרב-דורי'  , 'BH'); ?></span></h2>
                </div>
            <?php endif; ?>
		
	</div> <!-- container -->

	
	
<?php  
	/* Mobile fullscreen search*/
	get_template_part('views/components/full', 'screen-search'); 
	
	//get_template_part('views/components/advanced-search', 'form'); 
?>	
	
</header>

<?php get_template_part( 'views/header/language-switcher' ); ?>