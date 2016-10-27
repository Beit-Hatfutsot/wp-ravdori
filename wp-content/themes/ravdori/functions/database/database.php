<?php
/**
 * This file handles all the extra database functions and constants
 *
 *
 * @package    functions/database.php
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */


/*************/
/* Constants */
/************/


// Tables names
define ( 'DB_DICTIONARY' , 'wp_dictionary' ); // The name of the dictionary table in the Wordpress database
define ( 'DB_QUOTES'     , 'wp_quotes' );    //  The name of the quotes table in the Wordpress database
define ( 'DB_POSTS'      , 'wp_posts' );    //  The name of the posts table in the Wordpress database


define ( 'MAX_ROWS_PER_PAGE'   , 10 ); // The maximum results to show in a page
define ( 'MAX_QUOTES_PER_PAGE' , 30 ); // The maximum results to show in a page
define ( 'MAX_TERMS_PER_PAGE'  , 20 ); // The maximum results to show in a page


/************/
/* Includes */
/************/


// Dictionary table related functions
require_once('dictionary.php');

// Quotes table related functions
require_once('quotes.php');
?>