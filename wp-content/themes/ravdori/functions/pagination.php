<?php
/**
 * All the theme's pagination related functions
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



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
                    <ul class="pagination">
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

    $out    = '';
    $retStr = '';

    //wrap a's and span's in li's
    $out = str_replace("<div","",$html);
    $out = str_replace("class='wp-pagenavi'>","",$out);
    $out = str_replace("<a","<li><a",$out);
    $out = str_replace("</a>","</a></li>",$out);
    $out = str_replace("<span","<li><span",$out);
    $out = str_replace("<span class='current'","<li class='active'><span",$out);
    $out = str_replace("</span>","</span></li>",$out);
    $out = str_replace("</div>","",$out);


    $currentDisplayedPage  = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $currentNumberOfPosts  = ( ($currentDisplayedPage * MAX_ROWS_PER_PAGE) - MAX_ROWS_PER_PAGE ) + 1;

    $postsNumberSoFar      =  ( $currentDisplayedPage * MAX_ROWS_PER_PAGE ) - ( MAX_ROWS_PER_PAGE - $wp_query->post_count );

    $showingStoryOutOf  = 'מציג ';
    $showingStoryOutOf .= $currentNumberOfPosts . ' - ';
    $showingStoryOutOf .= $postsNumberSoFar . ' ';
    $showingStoryOutOf .= 'מתוך: ';
    $showingStoryOutOf .= $wp_query->found_posts;
	
	$top_pagintion_margin = '';
	
	if ( $showTextOnTop ) { $top_pagintion_margin = 'style="margin: 15px 0 30px 0;"'; }
	
	$retStr  = '<div class="row" ' . $top_pagintion_margin . ' >';
	$retStr .= '<div class="col-xs-12 text-center">';
	if ( $showTextOnTop ) {  $retStr .=  '<div>' .$showingStoryOutOf . '</div>'; }
	$retStr .= '<ul class="pagination">' . $out . '</ul>';
	if ( !$showTextOnTop ) { $retStr .=  '<div>' .$showingStoryOutOf . '</div>'; }
	$retStr .= '</div>';
	$retStr .= '</div>';

    return( $retStr );
}
add_filter( 'wp_pagenavi', 'bh_pagination', 10, 2 ); //attach our function to the wp_pagenavi filter



function show_wp_pagenavi( $story_query , $text_on_top = false ) 
{
	if(function_exists('wp_pagenavi')) {

		  $GLOBALS[GLBL_WP_NAV_TOP_BOTTOM] = $text_on_top;
          wp_pagenavi( array( 'query' => $story_query ) );
      }
}

