<?php
/**
 *
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>

<?php $locale = get_language_locale_filename_by_get_param(); ?>

<?php if ( $locale == ISupportedLanguages::EN['locale_file'] ): ?>

	<style>	
		

		.publish-story-caption:after {
				background: transparent url('/wp-content/themes/ravdori/images/general/story/publish-story-bubble-en.png') 0 0 no-repeat !important;
				width: 290px;
				left: 160px;
		}
		
		#story-post .white-shadow-box {
			    text-align: left;
				direction: ltr;
		}
		
		#story-post .white-shadow-box article.single-story .subtitle { float: left; }
		
		.wp-caption .wp-caption-text { text-align: left; }
	</style>

<?php elseif ( $locale == ISupportedLanguages::RU['locale_file'] ): ?>
	
	<style>		

		.publish-story-caption {
			position: relative;
			top: -3px;
			right: -68px;
		}
			
		.publish-story-caption:after {
				background: transparent url('/wp-content/themes/ravdori/images/general/story/publish-story-bubble-ru.png') 0 0 no-repeat !important;
				width: 290px;
				left: 204px;
		}
		
		#story-post .white-shadow-box {
			    text-align: left;
				direction: ltr;
		}
		
		#story-post .white-shadow-box article.single-story .subtitle { float: left; }
		
		.wp-caption .wp-caption-text { text-align: left; }
	</style>
	
<?php endif;?>

<script>

    var $ = jQuery;

	
      function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
	  $(document).on("keydown", disableF5);

	  
    /* Fields validation */
    $().ready(function () {

            <?php
                    global $wizardSessionManager;
                    $step4Data = $wizardSessionManager->getStepData( IWizardStep4Fields::ID );
            ?>

            <?php if ( !empty( $step4Data[ IWizardStep4Fields::RAVDORI_FEEDBACK ] ) ): ?>

            <?php $feedback =  json_encode( $step4Data[ IWizardStep4Fields::RAVDORI_FEEDBACK ] , JSON_UNESCAPED_UNICODE );?>
            var feedbackText;

            <?php if ($feedback): ?>

            feedbackText = '<p><h3 class="story-personal-view">';
            feedbackText += '<?php BH__e('הזוית האישית' , 'BH', $locale);?>';
            feedbackText += '</h3></p>';
            feedbackText += '<p>';
            feedbackText += <?php echo  $feedback;?>.replace( /\n/g, "<br />" );
            feedbackText += '</p>';

            $('.single-story .entry').append( feedbackText );


            <?php endif;?>
            <?php endif;?>

    }); // Ready
</script>

<style>
    .fb-share-button,
    .printfriendly,
    .printfriendly-text2 { display: none !important; }
</style>

    <section class="page-content">

        <div class="container">

            <div class="row">

                <?Php include(WIZARD_VIEWS . '/components/progressbar.php'); //Show the progress bar ?>
                <div class="col-sm-12">

                <form id="wizard-form-step4" class="wizard-form final-step" method="post">
                    <div class="title">
                        <h2><?php echo '5 - ' . $wizard_steps_captions[IWizardStep5Fields::ID - 1]; ?></h2>
                    </div>

                    <div class="submit" style="width: 100%;">
                        <div class="publish-story-caption">
                            <input type="submit" style="float: left;margin-left: 23px;" value="<?php BH__e('סיום ושליחת סיפור &#9664;' , 'BH', $locale);?>"/>
                        </div>
                    </div>


                    <div id="story-post" class="row" style="margin-right: -16px;">
					<!-- Story Data -->
								<?php 
									global $post;
									$post_id =  $step4Data[IWizardStep4Fields::POST_ID];
									
									$old_post = $post;
									$post = get_post( $post_id );
									setup_postdata( $post );
							?>
                    
                            <div class="col-xs-4">
                                  <?php include(locate_template('views/sidebar/sidebar.php')); ?>
                            </div>

								<div class="col-xs-8 white-shadow-box">
									<article class="post single-story print-only" id="post-<?php the_ID(); ?>">
											<?php 
													global $post;
													$old_post = $post;
													$post = get_post( $post_id );
													setup_postdata( $post );
											?>
									
											<?php include(locate_template('views/story/meta.php'));  ?>
											
											<?php 
													global $post;
													$old_post = $post;
													$post = get_post( $post_id );
													setup_postdata( $post );
											?>
											
											<?php include(locate_template('views/story/loop.php'));  ?>
                                    </article>
							   </div>
							   
					<?php $post= $old_post; ?>
					
					<!-- -->
					
					
                    <div class="submit">
                        <div class="publish-story-caption">
                            <input type="submit" style="float: left;margin-left: 23px;" value="<?php BH__e('סיום ושליחת סיפור &#9664;' , 'BH', $locale);?>"/>
                        </div>
                    </div>

                    <input type="hidden" name="step" value="<?php echo IWizardStep5Fields::ID; ?>"/>
                    <?php wp_nonce_field( 'nonce_author_details_form_action' , 'nonce_author_details' ); ?>
					
					
				<form name="step3"  class="wizard-form" method="post">
                    <input name="progstep" value="4" type="hidden">

                    <div class="submit" style="width: 16.6%; height: 91px;">
                            <input type="submit" value="<?php BH__e('&#9654; הקודם' , 'BH', $locale);?>"/>
                    </div>

                </form>
				
				
					</div>



                </form>


              </div>
            </div>
        </div>

    </section>
<?php  get_footer(); ?>
<?php



?>