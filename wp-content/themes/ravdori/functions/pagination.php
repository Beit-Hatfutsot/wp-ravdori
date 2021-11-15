<?php
/**
 * All the theme's pagination related functions
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.3.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define ( 'PAGINATION_STYLE__NUM_LIST' , 1 );
define ( 'PAGINATION_STYLE__SELECT'   , 2 );
define ( 'GLBL_WP_NAV__STYLE'   , 1 );


/**
 * Creates an array of links based on Wordpress's paginate_links function,
 * and outputs a Bootstrap 3 pagination
 *
 * @param  int    $post_count  - The maximum number of posts in th query
 * @param  int    $page_number - The current page in the pagination
 * @param  int    $max_rows    - The maximum rows per page ( MAX_ROWS_PER_PAGE is the default)
 * @return Array  Array of links with numbers to the correct pages
 */
function create_pagination( $post_count , $page_number , $max_rows = MAX_ROWS_PER_PAGE )
{
    $total_posts     = $post_count;
    $number_of_pages = ceil( $total_posts / $max_rows );

    $pages  = paginate_links( array   (
                                            'base'      => add_query_arg( 'pagenum', '%#%' ),
                                            'format'    => '',
                                            'prev_text' => __( '&laquo;', 'aag' ),
                                            'next_text' => __( '&raquo;', 'aag' ),
                                            'total'     => $number_of_pages,
                                            'current'   => $page_number,
                                            'type'      => 'array'
                                        )
                            );

    ?>

    <?php if ( is_array($pages) ): ?>
                <div class="pagination-container">
                    <ul class="pagination non-story-pager wp-pagenavi">
                        <?php
                            foreach ($pages as $i => $page)
                            {
                                if ($page_number == 1 && $i == 0)
                                {
                                    echo "<li class='active'>$page</li>";
                                }
                                else
                                {
                                    if ($page_number != 1 && $page_number == $i)
                                    {
                                        echo "<li class='active'>$page</li>";
                                    }
                                    else
                                    {
                                        echo "<li>$page</li>";
                                    }
                                }
                            }
                        ?>
                    </ul>
                </div>
    <?php endif; ?>


    <?php
    return ( $pages );
}





// Adjust the WP-Pagenavi plugin output HTML to suit the one of Bootstrap 3

function bh_pagination( $html )
{

    global $wp_query;

	$showTextOnTop = $GLOBALS[GLBL_WP_NAV_TOP_BOTTOM];
	$navStyle      = $GLOBALS[GLBL_WP_NAV__STYLE];

    $out    = '';
    $retStr = '';

    //wrap a's and span's in li's
    $out = str_replace("<div","",$html);
    $out = str_replace("class='wp-pagenavi' role='navigation'>","",$out);
    $out = str_replace("<a","<li><a",$out);
    $out = str_replace("</a>","</a></li>",$out);
    $out = str_replace("<span","<li><span",$out);
    $out = str_replace("<span class='current'","<li class='active'><span",$out);
    $out = str_replace("</span>","</span></li>",$out);
    $out = str_replace("</div>","",$out);

	$out = str_replace("<select",'<span class="wizard-select-theme"><select class="chosen-rtl" id="story-archive-page-select" ',$out);
	$out = str_replace("</select>","</select></span>",$out);

    $currentDisplayedPage  = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $currentNumberOfPosts  = ( ($currentDisplayedPage * MAX_ROWS_PER_PAGE) - MAX_ROWS_PER_PAGE ) + 1;

    $postsNumberSoFar      =  ( $currentDisplayedPage * MAX_ROWS_PER_PAGE ) - ( MAX_ROWS_PER_PAGE - $wp_query->post_count );

    $showingStoryOutOf  = 'מציג תוצאה ';
    $showingStoryOutOf .= $currentNumberOfPosts . ' - ';
    $showingStoryOutOf .= $postsNumberSoFar . ' ';
    $showingStoryOutOf .= 'מתוך: ';
    $showingStoryOutOf .= $wp_query->found_posts;

	$top_pagintion_margin = '';

	if ( $showTextOnTop ) { $top_pagintion_margin = 'style="margin: 15px 0 30px 0;"'; }

	$retStr  = '<div class="row" ' . $top_pagintion_margin . ' >';
	$retStr .= '<div class="col-xs-12 text-center">';

	if ( $showTextOnTop ) {
		$retStr .=  '<div class="stories-num-out-of">' .$showingStoryOutOf . '</div>';
	}

	if ( $navStyle == PAGINATION_STYLE__NUM_LIST )
		$retStr .= '<ul class="pagination">' . $out . '</ul>';
	else
	{
		$total_pages = max( 1, absint( $wp_query->max_num_pages ) );

		$next_page_link = get_next_posts_link('הבא');
		$next_page_link = ($next_page_link) ? '<span class="next-story-page-link">' . $next_page_link . '</span>' : '<span class="next-story-page-link next-story-page-link--disabled">' . __('הבא') . '</span>';

		$prev_page_link = get_previous_posts_link('הקודם');
		$prev_page_link = ($prev_page_link) ? '<span class="prev-story-page-link">' . $prev_page_link . '</span>' : '<span class="prev-story-page-link prev-story-page-link--disabled">' . __('הקודם') . '</span>';



		$retStr .= '<div class="select-pager-container">';
		$retStr .= '<span class="select-pager-container__page-label">' . __( 'עמוד:' , 'BH' ) . '</span>';
		$retStr .= $out;
		$retStr .= sprintf('<span class="select-pager-container__page-of-label">%s %s</span>', __( 'מתוך:' , 'BH' ), $total_pages );
		$retStr .= '<span class="next-prev-links">';
		$retStr .= $prev_page_link;
		$retStr .= $next_page_link;
		$retStr .= '</span>';
		$retStr .= '</div>';
	}

	if ( !$showTextOnTop ) {
		$retStr .=  '<div>' .$showingStoryOutOf . '</div>';
	}


	$retStr .= '</div>';
	$retStr .= '</div>';

    return( $retStr );
}
add_filter( 'wp_pagenavi', 'bh_pagination', 10, 2 ); //attach our function to the wp_pagenavi filter



function show_wp_pagenavi( $story_query , $text_on_top = false , $style = PAGINATION_STYLE__NUM_LIST )
{
   if(function_exists('wp_pagenavi')):

		  $GLOBALS[GLBL_WP_NAV_TOP_BOTTOM] = $text_on_top;
		  $GLOBALS[GLBL_WP_NAV__STYLE] 	   = $style;

          wp_pagenavi( array( 'query' => $story_query, 'options' => ['style' => $style], ) );

   endif;
}

