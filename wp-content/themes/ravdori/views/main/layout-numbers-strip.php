<?php
/**
 * This view outputs the main page running numbers strip
 *
 *
 * @package    views/main
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */?>
<section id="homepage-numbers-strip" class="row voffset5">


    <div class="col-sm-12">
        <div class="row">

            <div class="col-sm-4 text-center">
				<?php  echo BH_sc_running_counter( array( 'number' => 'schools' , 'title' => 'בתי ספר משתתפים בתוכנית') ); ?>
            </div>
			
			<div class="col-sm-4 text-center">
				<?php  echo BH_sc_running_counter( array( 'number' => 'cities' , 'title' => 'יישובים משתתפים בתוכנית') ); ?>
            </div>
			
			<div class="col-sm-4 text-center">
                <?php  echo BH_sc_running_counter( array( 'number' => 'stories' , 'title' => 'סיפורים') ); ?>
            </div>


        </div>



    </div>

</section>