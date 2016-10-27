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