<?php
/**
 * View of story archive after free text search
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>
<?php  if ( isset ( $_GET['city-select'] ) OR isset ( $_GET['school-select'] ) OR isset( $_GET['country'] ) OR  isset ( $_GET['subjects'] ) Or isset( $_GET['subtopics'] ) Or isset( $_GET['submit'] ) ): ?>
    <script>
        jQuery(function() {
            jQuery('html, body').animate({
                scrollTop: jQuery(".stories").offset().top - 30
            }, 2000);
         });
    </script>
<?php endif; ?>

<?php

// Filter the _GET array
$search_params = array_filter($_GET, "cleanInput");

?>

<?php
$paged    = (get_query_var('paged')) ? get_query_var('paged') : 1;


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
    'orderby' 		 => $orderby,   /*'date',  'orderby' => 'title', */
    'order' 	     => $order,
);


if ( isset(($search_params['advanced_search__story_name'])) AND ! empty($search_params['advanced_search__story_name']) ):
	$args['search_story_title'] = $search_params['advanced_search__story_name'];	
	$args['search_story_title_exact'] = 0;
	
	if ( isset($search_params['story-name-exact']) AND $search_params['story-name-exact'] == 'on' ):
		$args['search_story_title_exact'] = 1;
	endif;
	
endif;


if ( isset($search_params['advanced_search__adult_name']) AND ! empty($search_params['advanced_search__adult_name']) ):
	
	$compare_operator = 'LIKE';
	
	if ( isset($search_params['adult-name-exact']) AND $search_params['adult-name-exact'] == 'on' ):
		$compare_operator = '=';
	endif;

    $args_user_query = [ 
							'fields' 		 => 'ID',
							'role' 			 => 'adult', 
							'search_columns' => ['display_name','user_nicename','user_login'],
							'search' 		 => '*'.esc_attr( $search_params['advanced_search__adult_name'] ).'*',
							
							'meta_query' 	 => [
													'relation' => 'OR',
													[
														'key'     => 'first_name',
														'value'   => $search_params['advanced_search__adult_name'],
														'compare' => $compare_operator
													],
													[
														'key'     => 'last_name',
														'value'   => $search_params['advanced_search__adult_name'],
														'compare' => $compare_operator
													]
												],
					   ];


    $user_query = new WP_User_Query( $args_user_query );
	
	if ( ! empty($user_query->results) ):
		if ( ! isset($args['author__in']) OR empty($args['author__in']) ):
			$args['author__in'] = $user_query->get_results();
		else:
			$args['author__in'] = array_merge($args['author__in'],$user_query->get_results());
		endif;
	else:	
		$args['author__in'] = ['-1'];
	endif;
	
endif;




if ( isset($search_params['advanced_search__student_name']) AND ! empty($search_params['advanced_search__student_name']) ):
	
	if ( ! isset($args['meta_query']) ):
		$args['meta_query'] = [ 'relation' => 'AND' ];
	endif;
	
	
	$compare_operator = 'LIKE';
	
	if ( isset($search_params['student-name-exact']) AND $search_params['student-name-exact'] == 'on' ):
		$compare_operator = '=';
	endif;
	
	$query_arr = [
					'relation' => 'OR',
					[
						'key' 		=> 'acf-story-student-fname',
						'value'		=> $search_params['advanced_search__student_name'],
						'compare'	=> $compare_operator 
					],
					[
						'key' 		=> 'acf-story-student-lname',
						'value'		=> $search_params['advanced_search__student_name'],
						'compare'	=> $compare_operator 
					],						
				];
	
	$args['meta_query'][] = $query_arr;					
	
endif;



if ( isset($search_params['advanced_search__teacher_name']) AND ! empty($search_params['advanced_search__teacher_name']) ):
	
	
	if ( ! isset($args['meta_query']) ):
		$args['meta_query'] = [ 'relation' => 'AND' ];
	endif;
	
	$compare_operator = 'LIKE';
	
	if ( isset($search_params['teacher-name-exact']) AND $search_params['teacher-name-exact'] == 'on' ):
		$compare_operator = '=';
	endif;
	
	$query_arr = [
					[
						'key' 		=> 'acf-story-student-teacher',
						'value'		=> $search_params['advanced_search__teacher_name'],
						'compare'	=> $compare_operator
					],				
				];
	
	$args['meta_query'][] = $query_arr;			
	
	
endif;




if ( isset($search_params['advanced_search__country']) AND ! empty($search_params['advanced_search__country']) ):

    $countryId = $search_params['advanced_search__country'];

	
    $args_user_query = [
						'fields' => 'ID',
						'role'   => 'adult',
						'search_columns' => ['display_name','user_nicename','user_login'],
						'meta_query' => [
											 'relation' => 'AND',
											[
												'key'     => 'acf-user-adult-birth-place',
												'value'   => $countryId,
												'compare' => '='
											]
										],
						
					   ];
					   


    $user_query = new WP_User_Query( $args_user_query );

	
	if ( ! isset($args['author__in']) OR empty($args['author__in']) ):
		$args['author__in'] = (empty($user_query->results) ? [-1] : $user_query->results);
	else:
		$args['author__in'] = array_merge($args['author__in'],(empty($user_query->results) ? [-1] : $user_query->results));
	endif;
  

endif;


if ( isset(($search_params['advanced_search__word_name'])) AND ! empty($search_params['advanced_search__word_name']) ):
	$args['search_story_content'] = $search_params['advanced_search__word_name'];	
	
	$args['search_story_content_exact'] = 0;
	
	if ( isset($search_params['word-name-exact']) AND $search_params['word-name-exact'] == 'on' ):
		$args['search_story_content_exact'] = 1;
	endif;
	
endif;


if ( isset($search_params['advanced_search__quote_name']) AND ! empty($search_params['advanced_search__quote_name']) ):

$compare_operator = 'LIKE';

if ( isset($search_params['quote-name-exact']) AND $search_params['quote-name-exact'] == 'on' ):
	$compare_operator = '=';
endif;
	
$quotes = BH_quotes_search_quotes( $search_params['advanced_search__quote_name'], $compare_operator );

$stories_ids_with_quotes = [];


if ( ! empty($quotes) ):
	
	foreach( $quotes as $quote_obj ):	
		$stories_ids_with_quotes[] = $quote_obj->post_id;
	endforeach;
	
endif;


if ( ! empty($stories_ids_with_quotes) ):
	$args['post__in'] = $stories_ids_with_quotes;
else:
	$args['post__in'] = [-1];
endif;	
	
	
endif;


global $wp_query;


if ( isset(($search_params['advanced_search__story_name'])) AND ! empty($search_params['advanced_search__story_name']) ):
	add_filter( 'posts_where', 'advanced_search_title_filter', 10, 2 );
endif;

if ( isset(($search_params['advanced_search__word_name'])) AND ! empty($search_params['advanced_search__word_name']) ):
	add_filter( 'posts_where', 'advanced_search_content_filter', 10, 2 );
endif;

$story_query = new WP_Query( $args );
$wp_query 	 = $story_query;

if ( isset(($search_params['advanced_search__story_name'])) AND ! empty($search_params['advanced_search__story_name']) ):
	remove_filter( 'posts_where', 'advanced_search_title_filter', 10, 2 );
endif;

if ( isset(($search_params['advanced_search__word_name'])) AND ! empty($search_params['advanced_search__word_name']) ):
	remove_filter( 'posts_where', 'advanced_search_content_filter', 10, 2 );
endif;
	
if( $story_query->have_posts() ): ?>

   <?php
		/**
		* Display the archive filtering header
		*/
		include( locate_template( 'views/components/preloader.php' ) );
	?>
			
	<?php
			/**
			* Display the archive filtering header
			*/
			include( locate_template( 'views/story/archive/header.php' ) );
	?>
			
<div class="stories">

	<?php show_wp_pagenavi( $story_query , true ); ?>
	
    <?php while ( $story_query->have_posts()) : $story_query->the_post(); ?>

       <?php
	   
	   		 // Get the story meta
			 $stories_meta = get_story_meta_data( array( STORY_META_ARRAY_STUDENT_NAME , STORY_META_ARRAY_AUTHOR_NAME , STORY_META_ARRAY_SCHOOL_ONLY , STORY_META_ARRAY_PUBLISH_DATE , STORY_META_ARRAY_TEACHER_NAME) );
				
			 // Show a story
			 include(locate_template('views/story/archive/loop-item.php'));

		?>
		
    <?php
    endwhile;
  //  if(function_exists('wp_pagenavi')) {
        echo '<div style="margin-top: 30px">';
            //wp_pagenavi( array( 'query' => $story_query ) );
		show_wp_pagenavi( $story_query , false );
        echo '</div>';
    //}
    wp_reset_postdata();
    ?>
</div>


<?php else: ?>

    <?php if ( count($_GET) >  1 ): ?>
        <div class="row">
            <div class="col-xs-12">
                <?php get_template_part('views/components/not-found'); ?>
            </div>
        </div>
    <?php endif; ?>

<?php endif; ?>