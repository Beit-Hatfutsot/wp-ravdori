<?php
/**
 * View of story archive
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
			
			/* The main query */
		    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			

		    // Get the ordering
			$orderby = filter_input(INPUT_GET, 'orderby', FILTER_SANITIZE_STRING);

			if ($orderby == FALSE OR in_array( $orderby, [STORY_GET_PARAM__NEW_STORIES, STORY_GET_PARAM__OLD_STORIES, STORY_GET_PARAM__TITLE_DESC, STORY_GET_PARAM__TITLE_ASC] ) == FALSE ) {
				$orderby = STORY_GET_PARAM__NEW_STORIES;	
			}
			
			$order = 'desc';
			
			switch ( $orderby ) {
				
				case STORY_GET_PARAM__NEW_STORIES:
					$orderby = 'date';
					$order   = 'desc';
				break;
				
				case STORY_GET_PARAM__OLD_STORIES:
					$orderby = 'date';
					$order   = 'asc';
				break;
				
				case STORY_GET_PARAM__TITLE_DESC:
					$orderby = 'title';
					$order   = 'desc';
				break;
				
				case STORY_GET_PARAM__TITLE_ASC:
					$orderby = 'title';
					$order   = 'asc';
				break;
				
			};
				
			$args  = array (
							'post_type'      => STORY_POST_TYPE,
							'post_status'    => 'publish',
							'paged'          => $paged,
							'posts_per_page' => MAX_ROWS_PER_PAGE ,
							'orderby' 		 => $orderby,
							'order'   		 => $order,
						   );
						   

			$story_query = null;
			$story_query = new WP_Query($args);
			
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