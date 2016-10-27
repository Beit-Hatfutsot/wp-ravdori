<?php
/**
 * The Template for displaying single post page
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>

<?php

	// content
	if (have_posts()) : the_post();

        get_template_part('views/blog/single');

	endif;
	
?>

<?php get_footer(); ?>