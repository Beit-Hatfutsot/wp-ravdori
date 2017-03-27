<?php
/**
 * All Advanced Custom Fields related functions
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// Add backward Combility to ACF 5
// @see: https://www.advancedcustomfields.com/resources/upgrading-v4-v5/
add_filter( 'acf/compatibility/field_wrapper_class', '__return_true' , -1 );

// Since we are using ACF v5 we need to show the opstion pages thro code
if(function_exists('acf_add_options_page')) 
{ 
	acf_add_options_page();
}
