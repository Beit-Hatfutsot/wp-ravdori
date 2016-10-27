<?php
/**
 * Add embed services to the theme
 * @see http://codex.wordpress.org/Embeds
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Register embed handlers for services not supported by Wordpress
 *
 */
add_action( 'init', function()
{

    // Register handler for Flipsnack
    wp_embed_register_handler('flipsnack', '/^(http\\:\\/\\/|https\\:\\/\\/)?(?:cdns\\.)?snacktools\\.net\\/flipsnack\\/embed_https\\.html\\?hash\\=([a-zA-Z0-9_-]+)/', 'wp_embed_handler_flipsnack');

});



#region Handlers


/**
 * The Flipsnack embed handler callback. Flipsnack does not support oEmbed.
 *
 * @see WP_Embed::register_handler()
 * @see WP_Embed::shortcode()
 *
 * @param array $matches The regex matches from the provided regex when calling {@link wp_embed_register_handler()}.
 * @param array $attr Embed attributes.
 * @param string $url The original URL that was matched by the regex.
 * @param array $rawattr The original unmodified attributes.
 *
 * @return string The embed HTML.
 */
function wp_embed_handler_flipsnack( $matches, $attr, $url, $rawattr ) {

    $embed = sprintf(
        '<div class="text-center"><iframe src="https://cdns.snacktools.net/flipsnack/embed_https.html?hash=%1$s" width="600" height="400" frameborder="0" scrolling="no" marginwidth="0" marginheight="0"></iframe></div>',
        esc_attr($matches[2])
    );

    return apply_filters( 'embed_flipsnack', $embed, $matches, $attr, $url, $rawattr );
}


#endregion



/* Select the "large" image size to show by default*/
function my_default_image_size () {
    return 'large';
}
add_filter( 'pre_option_image_default_size', 'my_default_image_size' );

?>