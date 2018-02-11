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
	 
	 $locale = get_language_locale_filename_by_get_param();
?>

<header>

	<div class="container">
							
               <div id="header-top" class="row">
                 <div class="col-sm-6">

                     <div class="row">
                         <div class="col-sm-6">
                             <a href="<?php echo HOME?>">
                                <img src="<?php echo IMAGES_DIR . '/general/header/logo.png'?>" />
                             </a>
                         </div>

                         <div class="col-sm-6 voffset3">
                             <div class="push-left">
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

                 <div class="col-sm-6">
                     <div class="row">
                         <div class="col-sm-8">
                            <?php  get_template_part('views/components/search', 'form'); ?>
                         </div>

                         <div class="col-sm-4">

                             <ul class="social-links">
                                  <li class="social-facebook">
                                     <a href="http://www.facebook.com/groups/260769624535/?fref=ts" target="_blank">
                                        <span class="social-icon"></span>
                                     </a>

                                 </li>

                                 <li class="social-youtube">
                                     <a href="https://www.youtube.com/results?search_query=%D7%A7%D7%A9%D7%A8+%D7%A8%D7%91+%D7%93%D7%95%D7%A8%D7%99&oq=%D7%A7%D7%A9%D7%A8+%D7%A8%D7%91+%D7%93%D7%95%D7%A8%D7%99&gs_l=youtube.12..0i19.2735.5455.0.7353.11.6.0.5.5.0.134.777.0j6.6.0...0.0...1ac.1.1iHfZVeTxPs" target="_blank">
                                         <span class="social-icon"></span>
                                     </a>
                                 </li>

                             </ul>

                         </div>

                     </div>
                 </div>


               </div>

             
              <div id="header-bottom" class="row">
              
                    <div id="navbar-container" class="col-xs-10">
               			<div class="navbar navbar-default" role="navigation">
               				<?php
               
               					// main menu
               					if ($main_menu) :
               					
               						echo '<nav class="main-menu">';
               							echo '<ul class="nav navbar-nav">';
               							
               								echo $main_menu;
               								
               							echo '</ul>';
               						echo '</nav>';

               					endif;
               					
               				?>
               			</div>
                    </div>
                    
                     <div id="image-upload-and-social-container" class="col-xs-2">
						<?php  get_template_part('views/languages/header/wizard-languages-switcher/language','switcher'); ?>
                     </div>
					 
					 
          	</div>

            <?php if ( !is_page_template( 'wizard.php' ) ): ?>
                <div class="homepage-title-container row">
                    <h2><?php BH__e( 'מאגר סיפורי מורשת' , 'BH' ,$locale); ?></h2>
                    <h3><?php BH__e( 'אוצר אנושי מתכנית הקשר הרב דורי'  , 'BH' ,$locale); ?></h3>
                </div>
            <?php endif; ?>
		
	</div> <!-- container -->

</header>