<?php
/**
 * The Template for displaying search page
 *
 * @author 		Beit Hatfutsot
 * @package 	bh
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>

		<?php
				/**
				* Display the archive loop
				*/
				include( locate_template( 'views/search/free-search-story-archive.php' ) );
			?>

<?php get_footer(); ?>