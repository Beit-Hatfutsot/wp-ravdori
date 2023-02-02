<?php
/**
 * This file contains all the needed functions for handling the wp editor
 * in the story data step in the wizard
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

/**
* change tinymce's paste-as-text functionality
*/
function change_paste_as_text($mceInit, $editor_id){

    //turn off paste_text_use_dialog and turn on sticky (and default)
    //NB this has no effect on the browser's right-click context menu's paste!
    $mceInit['paste_text_use_dialog'] = false;
    $mceInit['paste_text_sticky'] = true;
    $mceInit['paste_text_sticky_default'] = true;
    $mceInit['paste_as_text'] = true;

    return $mceInit;
}
add_filter('tiny_mce_before_init'   , 'change_paste_as_text' , 1, 2 );
add_filter( 'teeny_mce_before_init' , 'change_paste_as_text' , 1 ,2 );


function my_tinymce_config( $init ) {
    $init['remove_linebreaks'] = false;
    $init['convert_newlines_to_brs'] = true;
    $init['remove_redundant_brs'] = false;
    $init['object_resizing'] = false;
    return $init;
}
add_filter('tiny_mce_before_init', 'my_tinymce_config');


/* Enable TinyMCE paste plugin */
function add_contextmenu_plugin($args) {
    array_push($args,'contextmenu','media' , 'paste' );
    return $args;
}
//add_filter( 'teeny_mce_plugins', 'add_contextmenu_plugin');



// load 'paste' plugin in minimal/pressthis editor
add_filter( 'teeny_mce_plugins', function( $plugins ) {
	$plugins[] = 'paste';
	return $plugins;
});


function enable_more_buttons($buttons) {
    $buttons[] = 'cut';
    $buttons[] = 'copy';
    $buttons[] = 'paste';
   // $buttons[] = 'media';

    return $buttons;
}
add_filter("teeny_mce_buttons", "enable_more_buttons");