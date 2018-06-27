<?php
/**
 * Theme support
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'BH_before_main_content' , 'BH_theme_wrapper_start' , 10 );
add_action( 'BH_after_main_content'  , 'BH_theme_wrapper_end'   , 10 );


function BH_theme_wrapper_start() {

	echo '<section class="page-content"><div class="container"><div class="row">';
}

function BH_theme_wrapper_end() {

	echo '</div></div></section>';
}


// theme supports
add_theme_support('menus');
add_theme_support('post-thumbnails');

// Additional thumbnails size
add_image_size( 'story-archive-thumb'   , 260 , 200 , true );
add_image_size( 'homepage-slider-thumb' , 514  ,451 , true );


// remove WP defaults
remove_action('wp_head' , 'wp_generator');
remove_action('wp_head' , 'rsd_link');
remove_action('wp_head' , 'wlwmanifest_link');



/**
 * Improves the caption shortcode with HTML5 figure & figcaption; microdata & wai-aria attributes
 *
 * @param  string $val     Empty
 * @param  array  $attr    Shortcode attributes
 * @param  string $content Shortcode content
 * @return string          Shortcode output
 */
function BH_img_caption_shortcode_filter($val, $attr, $content = null)
{
    extract(shortcode_atts(array(
        'id'      => '',
        'align'   => 'aligncenter',
        'width'   => '',
        'caption' => ''
    ), $attr));

    // No caption, no dice... But why width?
    if ( 1 > (int) $width || empty($caption) )
        return $val;

    if ( $id )
        $id = esc_attr( $id );

    // Add itemprop="contentURL" to image - Ugly hack
    $content = str_replace('<img', '<img itemprop="contentURL"', $content);

    // Strip HTML Tags
    $caption = strip_tags( $caption );

    // Clean up things like &amp;
    $caption = html_entity_decode( $caption );

    return  '<figure id="' . $id . '" aria-describedby="figcaption_' . $id . '" class="wp-caption ' . esc_attr($align) . '" itemscope itemtype="http://schema.org/ImageObject" style="width: ' . (0 + (int) $width) . 'px">' . do_shortcode( $content ) . '<figcaption id="figcaption_'. $id . '" class="wp-caption-text" itemprop="description">' . $caption . '</figcaption></figure>';
}
add_filter( 'img_caption_shortcode', 'BH_img_caption_shortcode_filter', 10, 3 );


// Add logging capabilities
if (!function_exists('write_log')) {

    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

}


// Add to log on image deletion
function check_relations( $post_id ){
	
	$logString = PHP_EOL . "Image File Id being deleted:" . $post_id . PHP_EOL . "The File Title: " . get_the_title( $post_id ) . PHP_EOL;  
	
	$parent = get_post_ancestors( $post_id );
	
	$logString .= "Story ID associated with Image: " . $parent[0]  . PHP_EOL;  
	$logString .= "Story title associated with Image: " . get_the_title($parent[0])  . PHP_EOL;  
	
	
	// Get current user Info
	$current_user = wp_get_current_user();
	$logString .= sprintf( __( 'Username: %s', 'textdomain' ), esc_html( $current_user->user_login ) ) . PHP_EOL;
	$logString .= sprintf( __( 'User ID: %s', 'textdomain' ), esc_html( $current_user->ID ) ) . PHP_EOL;
	$logString .= sprintf( __( 'User Email: %s', 'textdomain' ), esc_html( $current_user->user_email ) ) . PHP_EOL;
	$logString .= sprintf( __( 'User Display Name: %s', 'textdomain' ), esc_html( $current_user->display_name ) ) . PHP_EOL;
	$logString .= sprintf( __( 'User First Name: %s', 'textdomain' ), esc_html( $current_user->user_firstname ) ) . PHP_EOL;
	$logString .= sprintf( __( 'User Last Name: %s', 'textdomain' ), esc_html( $current_user->user_lastname ) ) . PHP_EOL;
	
	
	// Write it all to the log file
	write_log( $logString );


}
add_action( 'delete_attachment', 'check_relations' );