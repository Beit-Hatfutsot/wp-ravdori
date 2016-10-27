<?php
/**
 * Taxonomies definitions and related functions
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


define ( 'SCHOOLS_TAXONOMY'      , 'schools'   );
define ( 'SUBJECTS_TAXONOMY'     , 'subjects'  );
define ( 'SUBTOPICS_TAXONOMY'    , 'subtopics_taxonomy');
define ( 'LANGUAGES_TAXONOMY'    , 'languages_taxonomy');
//define ( 'COUNTRIES_TAXONOMY'    , 'countries' );

/**
 * Registers the theme's taxonomies
 */
function BH_register_taxonomies() {

        BH_register_taxonomy_schools();
        BH_register_taxonomy_subjects();
        BH_register_taxonomy_subtopics();
        BH_register_taxonomy_languages();
     //   BH_register_taxonomy_countries();

}
add_action('init', 'BH_register_taxonomies');



/**
 * Register the school taxonomy
 */
function BH_register_taxonomy_schools() {

        $labels = array(
            'name'                       => _x( 'בתי ספר', 'Taxonomy General Name', 'BH' ),
            'singular_name'              => _x('בית ספר' , 'Taxonomy General Name', 'BH' ),
            'menu_name'                  => __( 'בתי ספר', 'BH' ),
            'all_items'                  => __( 'כל בתי הספר', 'BH' ),
            'parent_item'                => __( 'הורה', 'BH' ),
            'parent_item_colon'          => __( 'הורה:', 'BH' ),
            'new_item_name'              => __( 'שם בית ספר חדש', 'BH' ),
            'add_new_item'               => __( 'הוסף בית ספר', 'BH' ),
            'edit_item'                  => __( 'ערוך בית ספר', 'BH' ),
            'view_item'					 => __('הצג בית ספר', 'BH'),
            'update_item'                => __( 'עדכן בית ספר', 'BH' ),
            'separate_items_with_commas' => __( 'הפרדת בתי ספר עם פסיקים', 'BH' ),
            'search_items'               => __( 'חיפוש בתי ספר', 'BH' ),
            'add_or_remove_items'        => __( 'הוספה או הסרה של בתי ספר', 'BH' ),
            'choose_from_most_used'      => __( 'בחירת בתי הספר הנופצים ביותר', 'BH' ),
            'not_found'                  => __( 'לא נמצאו בתי ספר', 'BH' ),
        );

        $args = array(
            'labels'					=> $labels,
            'public'					=> true,
            'hierarchical'              => true,
            'rewrite'                   => array(
                                                     'with_front'  => true,
                                                     'slug'        =>'author/schools',
                                                ),
        );

        register_taxonomy( SCHOOLS_TAXONOMY , array( STORY_POST_TYPE )  , $args );
}


/**
 * Register the subjects  taxonomy
 */
function BH_register_taxonomy_subjects() {

        $labels = array (
                            "name"                       => __( "נושאים", 'BH' ) ,
                            "label"                      => __( "נושאים", 'BH'),
                            "search_items"               => __( "חיפוש נושאים", 'BH') ,
                            "popular_items"              => __( "נושאים נפוצים", 'BH'),
                            "all_items"                  => __( "כל הנושאים", 'BH'),
                            "parent_item"                => __( "נושא אב", 'BH'),
                            "parent_item_colon"          => __( "נושא אב", 'BH'),
                            "edit_item"                  => __( "עריכת נושא", 'BH'),
                            "update_item"                => __( "עדכון נושא", 'BH'),
                            "add_new_item"               => __( "הוסף נושא חדש", 'BH'),
                            "new_item_name"              => __( "שם נושא חדש", 'BH'),
                            "separate_items_with_commas" => __( "הפרד נושאים עם פסיקים", 'BH'),
                            "add_or_remove_items"        => __( "הוספה או הסרה של נושאים", 'BH'),
                            "choose_from_most_used"      => __( "בחירת הנושאים הנפוצים ביותר", 'BH'),
                         );

        $args = array (
                        "labels"       => $labels ,
                        "hierarchical" => true ,
                        "label"        => "נושאים",
                        "show_ui"      => true ,
                        "query_var"    => true ,
                        "rewrite"      => array( 'slug' => 'subjects', 'with_front' => false ) ,
                        "show_admin_column" => 0 ,
                      );
        register_taxonomy( SUBJECTS_TAXONOMY , array( STORY_POST_TYPE ), $args );

    }


/**
 * Register the subjects  taxonomy
 */
function BH_register_taxonomy_subtopics() {

    $labels = array (
        "name"                       => __( "נושאי משנה", 'BH' ) ,
        "label"                      => __( "נושאי משנה", 'BH'),
        "search_items"               => __( "חיפוש נושאי משנה", 'BH') ,
        "popular_items"              => __( "נושאי משנה נפוצים", 'BH'),
        "all_items"                  => __( "כל נושאי המשנה", 'BH'),
        "parent_item"                => __( "נושא משנה אב", 'BH'),
        "parent_item_colon"          => __( "נושא משנה אב", 'BH'),
        "edit_item"                  => __( "עריכת נושא משנה", 'BH'),
        "update_item"                => __( "עדכון נושא משנה", 'BH'),
        "add_new_item"               => __( "הוסף נושא משנה", 'BH'),
        "new_item_name"              => __( "שם נושא משנה חדש", 'BH'),
        "separate_items_with_commas" => __( "הפרד נושאי משנה עם פסיקים", 'BH'),
        "add_or_remove_items"        => __( "הוספה או הסרה של נושאי משנה", 'BH'),
        "choose_from_most_used"      => __( "בחירת נושאי המשנה הנפוצים ביותר", 'BH'),
    );

    $args = array (
        "labels"       => $labels ,
        "hierarchical" => true ,
        "label"        => "נושאי משנה",
        "show_ui"      => true ,
        "query_var"    => true ,
        "rewrite"      => array( 'slug' => 'subtopics', 'with_front' => false ) ,
        "show_admin_column" => 0 ,
    );
    register_taxonomy( SUBTOPICS_TAXONOMY , array( STORY_POST_TYPE ), $args );

}


/**
 * Register the story's language taxonomy
 */
function BH_register_taxonomy_languages() {

    $labels = array (
        "name"                       => __( "שפות", 'BH' ) ,
        "label"                      => __( "שפות", 'BH'),
        "search_items"               => __( "חיפוש שפות", 'BH') ,
        "popular_items"              => __( "שפות נפוצות", 'BH'),
        "all_items"                  => __( "כל השפות", 'BH'),
        "parent_item"                => __( "שפת אב", 'BH'),
        "parent_item_colon"          => __( "שפת אב פסיק", 'BH'),
        "edit_item"                  => __( "עריכת שפה", 'BH'),
        "update_item"                => __( "עדכון שפה", 'BH'),
        "add_new_item"               => __( "הוסף שפה חדשה", 'BH'),
        "new_item_name"              => __( "שפת משנה חדשה", 'BH'),
        "separate_items_with_commas" => __( "הפרד שפות עם פסיקים", 'BH'),
        "add_or_remove_items"        => __( "הוספה או הסרה של שפות", 'BH'),
        "choose_from_most_used"      => __( "בחירת השפות הנפוצות ביותר", 'BH'),
    );

    $args = array (
        "labels"       => $labels ,
        "hierarchical" => true ,
        "label"        => "שפות",
        "show_ui"      => true ,
        "query_var"    => true ,
        "rewrite"      => array( 'slug' => 'languages', 'with_front' => false ) ,
        "show_admin_column" => 0 ,
    );
    register_taxonomy( LANGUAGES_TAXONOMY , array( STORY_POST_TYPE ), $args );

}


/**
 * Register the countries taxonomy
 */
/*
function BH_register_taxonomy_countries() {

        $labels = array(
            "name"                        => __(  "מדינות", 'BH' ) ,
            "label"                       => __( "מדינות", 'BH' ),
            "search_items"                => __( "חיפוש מדינות", 'BH' ) ,
            "popular_items"               => __( "מדינות פופלריות", 'BH' ),
            "all_items"                   => __( "כל המדינות", 'BH' ),
            "parent_item"                 => __( "מדינת אב", 'BH'  ),
            "parent_item_colon"           => __( "מדינת אב:", 'BH' ),
            "edit_item"                   => __( "ערוך מדינה", 'BH' ) ,
            "update_item"                 => __( "עדכן מדינה", 'BH' ),
            "add_new_item"                => __( "הוסף מדינה חדשה", 'BH' ),
            "new_item_name"               => __(  "צור מדינה חדשה", 'BH' ),
            "separate_items_with_commas"  => __( "הפרד מדינות בפסיקים", 'BH' ),
            "add_or_remove_items"         => __( "הוספה או הסרה של מדינות", 'BH' ),
            "choose_from_most_used"       => __(  "בחר את המדינות הפופלריות", 'BH' ),
        );

        $args = array(
                        "labels"       => $labels,
                        "hierarchical" => 0,
                        "label"        => "מדינות",
                        "show_ui"      => true,
                        "query_var"    => true,
                        "rewrite" => array(
                                             'slug'      => 'countries',
                                             'with_front' => false
                        ),
                        "show_admin_column" => 0,
                     );
        register_taxonomy( COUNTRIES_TAXONOMY , array( STORY_POST_TYPE ), $args );
    }*/
?>