<?php
/**
 * View of story archive after free text search
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>
<section class="page-content">

    <div class="container">

        <div class="row">

			<?php
				/**
				* Display the archive filtering header
				*/
				include( locate_template( 'views/components/preloader.php' ) );
			?>
			
            <div class="col-xs-12 stories">
			
			<?php 

			$story_query = null;
			
			global $wp_query;
			$story_query = $wp_query;
			
			?>
			
			
			<?php
				/**
				* Display the archive filtering header
				*/
				include( locate_template( 'views/story/archive/header.php' ) );
			?>
			
			<?php
				/**
				* Display the archive loop
				*/
				include( locate_template( 'views/story/archive/loop.php' ) );
			?>

            </div>
        </div>

    </div>
    </div>

</section>