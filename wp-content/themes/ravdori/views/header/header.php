<?php
	
     // Init the main menu
	$args = array(
		'theme_location'		=> 'main-menu',
		'container'		    	=> false,
		'items_wrap'			=> '%3$s',
		'link_before'			=> '<span>',
		'link_after'			=> '</span>',
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
                 <div class="col-xs-3 col-sm-3 col-md-6 col-lg-6">

                     <div class="row">
                         <div class="col-sm-10 col-md-4 col-lg-6 header-logo-container">
                             <a href="<?php echo HOME?>">
                                <img src="<?php echo IMAGES_DIR . '/general/header/logo.png'?>" style="margin-top: 6px;"/>
                             </a>
                         </div>

                         <div class="visible-lg visible-md col-sm-8 col-lg-6 voffset3">
                             <div class="push-left visible-lg visible-md">
                                <span style="color:#666766;font-size: 16px;"><?php _e('חפש לפי: ' , 'BH'); ?></span>

                                 <a href="<?php echo $school_search_url;?>" class="no-underline">
                                     <div class="search-button">
                                             <div class="search-button-caption">
                                                     <?php _e( 'ביה"ס/  ישוב' , 'BH' ); ?>
                                             </div>
                                     </div>
                                 </a>

                                 <a href="<?php echo $topics_search_url;?>" class="no-underline">
                                     <div class="search-button">

                                            <div class="search-button-caption voffset1">
                                                    <?php _e( 'נושאים' , 'BH' ); ?>
                                            </div>
                                     </div>
                                 </a>

                                 <a href="<?php echo $countries_search_url;?>" class="no-underline">
                                     <div class="search-button origin-country">
                                             <div class="search-button-caption voffset1">
                                                    <?php _e( 'ארץ מוצא' , 'BH' ); ?>
                                             </div>

                                     </div>
                                 </a>

                             </div>

                         </div>

                     </div>

                 </div>

                 <div class="col-xs-6 col-sm-6">
                     <div class="row">
                         <div class="hidden-xs col-sm-12 col-md-7  col-lg-8 header-search-container">
                            <?php  get_template_part('views/components/search', 'form'); ?>
                            <?php  get_template_part('views/components/advanced-search', 'form'); ?>
                         </div>
						 
						 <div class="col-xs-push-2 col-xs-12 visible-xs header-mobile-search-button-container">
							<button class="btn btn-mobile-search"><i class="glyphicon glyphicon-search"></i></button>
						 </div>

                         <div class="col-sm-5 col-lg-4 visible-lg visible-md">
								
						     <?php 
							   $facebook_url = get_field( 'acf-options-header-facebook-url', 'options' );
							   $youtube_url  = get_field( 'acf-options-header-youtube-url', 'options' );
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
                             </ul>

                         </div>

                     </div>
                 </div>
				
				
				 <div class="col-sm-3 col-xs-3 visible-sm visible-xs">
					<a href="#" class="menu-btn">
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
								<span class="nav-scroller-btn__text"><?php _e('עוד...','BH'); ?></span>
								<span class="nav-scroller-btn__arrow">
									<div class="nav-scroller-arrow"></div>
								</span>
							</button>
							
							<button class="nav-scroller-btn nav-scroller-btn--right">
							
							<span class="nav-scroller-btn__arrow">
									<div class="nav-scroller-arrow"></div>
							</span>
								
							</button>
               			</div>
                    </div>
                    
                     <div id="image-upload-and-social-container" class="col-xs-2">
						<?php  get_template_part('views/languages/header/wizard-languages-switcher/language','switcher'); ?>
                     </div>
					 
					 
          	</div>

            <?php if ( !is_page_template( 'wizard.php' ) ): ?>
                <div class="homepage-title-container row">
                    <h2><?php _e( 'מאגר סיפורי מורשת' , 'BH'); ?></h2>
                    <h3><?php _e( 'אוצר אנושי מתכנית הקשר הרב דורי'  , 'BH'); ?></h3>
                </div>
            <?php endif; ?>
		
	</div> <!-- container -->

	
	
<?php  
	/* Mobile fullscreen search*/
	get_template_part('views/components/full', 'screen-search'); 
	
	//get_template_part('views/components/advanced-search', 'form'); 
?>	
	
</header>