<?php
/**
 * This view outputs the main page running numbers strip
 *
 *
 * @package    views/main
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
 
 require_once(FUNCTIONS_DIR . '/' . 'google-analytics-api.php');
 ?>
<section id="homepage-numbers-strip" class="row voffset5">


    <div class="col-sm-12">
        <div class="row">

			<div class="col-xs-3 col-sm-3 text-center">
				<?php  echo BH_sc_running_counter( array( 'number' => 'cities' , 'title' => 'יישובים משתתפים בתוכנית') ); ?>
            </div>
			
			
            <div class="col-xs-3 col-sm-3 text-center">
				<?php  echo BH_sc_running_counter( array( 'number' => 'schools' , 'title' => 'בתי ספר משתתפים בתוכנית') ); ?>
            </div>
			
			
			<div class="col-xs-3 col-sm-3 text-center">
                <?php  echo BH_sc_running_counter( array( 'number' => 'stories' , 'title' => 'סיפורים') ); ?>
            </div>

			<div class="col-xs-3 col-sm-3 text-center">
				<?php 
					
					// Init the Google Analytics API
					$analytics = initializeAnalytics();
					
					// Get the report
					$response = getReport($analytics);
					
					// Get hte number of vistors
					$vistors = getNumberOfVisits($response);
				
				?>
				
				<div class="running-numbers__cube counter">
		
					<div class="running-numbers__cube__number timer count-title count-number" data-to="<?php echo $vistors;?>" data-speed="1500">
					</div>
					
					<div class="running-numbers__cube__title">
						<?php echo 'כניסות לאתר השנה'; ?>
					</div>
		
				</div>
			</div>

        </div>



    </div>

</section>