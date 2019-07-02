<?php
/**
 * This file includes all the functions related to single story post
 * and stories archive.
 *
 * @package    functions/story.php
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*************/
/* Constants */
/************/


/*
 *  GET params for story filtering 
 */
define ( 'STORY_GET_PARAM__NEW_STORIES'     , 'newstories' );
define ( 'STORY_GET_PARAM__OLD_STORIES'     , 'oldstories' );
define ( 'STORY_GET_PARAM__TITLE_DESC'      , 'titledesc' );
define ( 'STORY_GET_PARAM__TITLE_ASC'       , 'titleasc' );
define ( 'STORY_GET_PARAM__BEST_MATCH'      , 'bestmatch' );


/* Those constants should be used in conjunction with the returning array of the
 * @see get_story_meta_data function, when there is a need to get a specific field,
 * instead of using "magic numbers".
 *
 * Example:
 *
 * echo $stories_meta[STORY_META_ARRAY_AUTHOR_NAME]['meta_title'];
 *
 * */
//define ( 'STORY_META_ARRAY_AUTHOR_COUNTRY'   , 1 );

define ( 'STORY_META_ARRAY_AUTHOR_NAME'      , 0 );
define ( 'STORY_META_ARRAY_AUTHOR_COUNTRY'   , 1 );
define ( 'STORY_META_ARRAY_STUDENT_NAME'     , 2 );
define ( 'STORY_META_ARRAY_TEACHER_NAME'     , 3 );
define ( 'STORY_META_ARRAY_STUDENT_LOCATION' , 4 );
define ( 'STORY_META_ARRAY_PUBLISH_DATE'     , 5 );
define ( 'STORY_META_ARRAY_SUBJECTS'         , 6 );
define ( 'STORY_META_ARRAY_SCHOOL_ONLY'      , 7 );



/**
 * This function returns all the meta data of a story.
 * Should be used only inside "the loop" querying the "story" custom post type.
 *
 * @param $arrFields - array containing the fields to display.
 *                     if null or the elements count equal to zero , all fields
 *                     will be returned
 *
 * @return Array - 2D associative array where each cell contains 2 keys:
 *                 1) meta_title: Translatable string of the title needed to be displayed.
 *                 2) meta_data:  The value from the post related to the meta_title.
 *
 */
function get_story_meta_data( $arrFields = null , $showDistricit = false )
{
    global $post;

    if ( empty( $arrFields ) OR  in_array( STORY_META_ARRAY_AUTHOR_NAME , $arrFields ) ) {

        // Get the adult's (the post's author) full name
        $author_id                   = $post->post_author;
        $adult_name_before_marriage  = get_field('field_54b52b42b1d17' , "user_" . $author_id  );
        $adult_name_before_marriage  = ( $adult_name_before_marriage != "" ) ? ' (' . $adult_name_before_marriage . ')' : '';
        $adult_name                  = get_the_author_meta('nickname', $author_id) . $adult_name_before_marriage;
    }

    $student_name = '';
    
    if ( empty( $arrFields ) OR  in_array( STORY_META_ARRAY_STUDENT_NAME , $arrFields ) ) {
        // Get the student's data
        $student_name = get_field('acf-story-student-fname') . ' ' . get_field('acf-story-student-lname');
    }
    
    $teacher_name = '';
    
    if ( empty( $arrFields ) OR  in_array( STORY_META_ARRAY_TEACHER_NAME , $arrFields ) ) {
        $teacher_name = get_field('acf-story-student-teacher');
    }

    if ( empty( $arrFields ) OR  in_array( STORY_META_ARRAY_SCHOOL_ONLY , $arrFields ) ) {

        // Get the student's school name and location (district and city), and save the it as A HREF tag
        $student_school_taxonomy_meta = wp_get_post_terms($post->ID, SCHOOLS_TAXONOMY );

        $country_term  = null;
        $district_term = null;
        $city_term     = null;
        $school_term   = null;

        if (  is_array( $student_school_taxonomy_meta ) || !empty( $student_school_taxonomy_meta ) )
        {

            // Find the country term
            foreach ( $student_school_taxonomy_meta as $term )
            {
                if ( $term->parent == 0 )
                {
                    $country_term = $term;
                    break;
                }
            }

            // Find the district term
            foreach ( $student_school_taxonomy_meta as $term )
            {

                if ( $term->parent == $country_term->term_id )
                {
                    $district_term = $term;
                    break;
                }

            }

            // Find the city
            foreach ( $student_school_taxonomy_meta as $term )
            {

                if ( $term->parent == $district_term->term_id )
                {
                    $city_term = $term;
                    break;
                }

            }

            // Find the school
            foreach ( $student_school_taxonomy_meta as $term )
            {
                if ( $term->parent == $city_term->term_id )
                {
                    $school_term = $term;
                    break;
                }
            }



        }


        // Permalink to the school \ city search page
        $school_search_page = get_field('acf-options-search-school', 'options');

        // Create url with the correct search string for city and school
        $city_search_url   = $school_search_page . '?city-select='   . $city_term->term_id;
        $school_search_url = $city_search_url    . '&school-select=' . $school_term->term_id;


        // Permalink to the countries search page
        $country_search_page = get_field('acf-options-search-countries', 'options');
        $country_url         = $country_search_page . '?country=' . $country_term->term_id;

        $school  = '<a href="' . $school_search_url . '">' . $school_term->name . '</a>';
        $city    = '<a href="' . $city_search_url   . '">' . $city_term->name . '</a>';
        $country = '<a href="' . $country_url       . '">' . $country_term->name . '</a>';





        if ( empty( $arrFields ) OR !in_array( STORY_META_ARRAY_SCHOOL_ONLY , $arrFields ) ) {

           if ( $showDistricit )
           {
               $district = '<a href="' . '">' . $district_term->name . '</a>';
               $school_full_location = $country . ' - ' . $district . ' - ' . $city . ' - ' . $school;
           }
           else
           {
               $school_full_location = $country . ' - ' . $city . ' - ' . $school;
           }

        }
        else {
            $school_full_location =  $city . ' - ' . $school;
        }
    }

    // The adult country
    $country = get_the_author_meta('acf-user-adult-birth-place', $author_id);
    $country_term_adult = get_term_by('id', $country , SCHOOLS_TAXONOMY);

    if ( !term_exists( $country_term_adult->name, SCHOOLS_TAXONOMY , 0 ) ) {
        $country_term_adult  = wp_insert_term( $country, SCHOOLS_TAXONOMY , array( 'parent' => 0 ) );
    }
    $country_url  = $country_search_page . '?country=' . $country_term_adult->term_id;
    $country = '<a href="' . $country_url       . '">' . $country_term_adult->name . '</a>';


    #region Get the story subjects
	
	$story_subjects = null;
	
    if ( empty( $arrFields ) OR  in_array( STORY_META_ARRAY_SUBJECTS , $arrFields ) ) {

		$terms          = wp_get_object_terms( $post->ID, SUBJECTS_TAXONOMY );
		asort ( $terms );

        if ( !empty($terms) && !is_wp_error($terms) ) {
            $subjects_search_url = get_field('acf-options-search-subject', 'options');

            foreach ( $terms as $term ) {
                $term_link = $subjects_search_url . '?subjects[]=' . $term->term_id;

                $story_subjects .= '<a href="' . $term_link . '" target="_blank" class="meta-term-link">' . $term->name . '</a>';
            }
        }
    }
    #endregion

	
	$locale = get_language_locale_filename_by_get_param();

    // Unite all the meta data to a 2D array
    $stories_meta = array(
        array (
                'meta_title' => BH__('מאת: ', 'BH', $locale),
                'meta_data'  => '<bdi>' . $adult_name . '</bdi>',
                'class'      => 'story-adult-name' ,
              ),

         array (
                 'meta_title' => BH__('ארץ לידה: ', 'BH', $locale),
                 'meta_data' => $country,
                 'class'      => 'story-adult-country' ,
               ),

        array (
                'meta_title' => BH__('מנחה: ', 'BH', $locale),
                'meta_data'  => $student_name,
                'class'      => 'story-student-name' ,
              ),

        array (
            'meta_title' => BH__('שם המורה: ', 'BH', $locale),
            'meta_data'  => $teacher_name,
            'class'      => 'story-teacher-name' ,
        ),

        array (
                'meta_title' => BH__('שם ועיר בית הספר: ', 'BH', $locale),
                'meta_data'  => $school_full_location,
                'class'      => 'story-full-location' ,
              ),


        array (
            'meta_title' => BH__('תאריך פרסום: ', 'BH', $locale),
            'meta_data'  => get_the_date(),
            'class'      => 'story-date' ,
        ),

        array (
                'meta_title' => BH__('נושאי הסיפור:  ', 'BH', $locale),
                'meta_data'  => $story_subjects,
                'class'      => 'story-subjects' ,
              ),

    );


if ( empty( $arrFields ) OR  in_array( STORY_META_ARRAY_SCHOOL_ONLY , $arrFields ) ) {
   // $stories_meta[ STORY_META_ARRAY_STUDENT_LOCATION ]['meta_title'] = 'בית הספר:';
}

return ( $stories_meta );

} // get_story_meta_data




/* Wrap images with block */
function filter_images( $content )
{
    if ( is_page_template( 'wizard.php' ) || is_singular( STORY_POST_TYPE ) ) {
        return preg_replace('/<img (.*) \/>\s*/iU', '<div class="wp-caption aligncenter"><img \1 /></div>', $content);
    }

    return $content;
}
add_filter('the_content', 'filter_images');


function authorPendingPostNotification( $post_id ) {
    $post   = get_post( $post_id );
    $author = get_userdata( $post->post_author );


    // Configure mail for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    $email_msg  = '<div style="direction:rtl;text-align:right">';
    ob_start();
    ?>
    <p dir="RTL">
        משתתף/ת יקר/ה,
    </p>
    <p dir="RTL">
        אנו שמחים להודיע שהסיפור שתיעדת במסגרת תכנית הקשר הרב דורי התקבל במערכת.
    </p>
    <p dir="RTL">
        בימים הקרובים הסיפור יפורסם במאגר סיפורי המורשת הלאומי של בית התפוצות – מוזיאון העם היהודי למשמרת לדורות הבאים.
    </p>
    <p dir="RTL">
        הודעה תשלח אליך עם פרסום הסיפור.
    </p>

    <?php

    $email_msg .= getEmailSignature();
    $email_msg .= ob_get_contents();
    ob_end_clean();

    $email_msg .= '</div>';


    $to =  filter_var( $author->user_email , FILTER_SANITIZE_EMAIL );

    mail($to, "סיפורך בקשר הרב דורי התקבל במערכת", $email_msg , $headers);

}


// Notify the adult about new post
function authorNotification( $post_id ) {
    $post   = get_post( $post_id );
    $author = get_userdata( $post->post_author );


    // Configure mail for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    $email_msg  = '<div style="direction:rtl;text-align:right">';
    ob_start();
    ?>

    <p dir="RTL">
        שלום  <?php echo $author->display_name?>
    </p>
    <p dir="RTL">
        אנו שמחים להודיע שהסיפור שתיעדת במסגרת תכנית הקשר הרב דורי פורסם במאגר סיפורי המורשת הלאומי של בית התפוצות - מוזיאון העם היהודי למשמרת לדורות הבאים.
        <br/>
    </p>
    <p dir="RTL">
        ניתן לראות את הסיפור בקישור הבא:
    </p>
    <p dir="RTL">
       <a href="<?php echo get_the_permalink( $post_id ); ?>">
            <?php echo $post->post_title ?>
       </a>
    </p>
    <p dir="RTL">
        תודה ששיתפתם אותנו בסיפורכם.
    </p>

    <?php
    $email_msg .= getEmailSignature();
    $email_msg .= ob_get_contents();
    ob_end_clean();

    $email_msg .= '</div>';


    $to =  filter_var( $author->user_email , FILTER_SANITIZE_EMAIL );

    mail($to, "סיפורך בקשר הרב דורי פורסם", $email_msg , $headers);


}
//add_action('publish_story' , 'authorNotification' , 10, 2);



// Notify the admin about new post
function adminNotification( $post_id ) {
    $post   = get_post( $post_id );
    $author = get_userdata( $post->post_author );


    // Configure mail for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    $email_msg  = '<div style="direction:rtl;text-align:right">';

    $email_msg .='הסיפור'  .  '<strong>"' .  $post->post_title . '"</strong> ' . 'התפרסם.' . '<br/>';
    $email_msg .=  '<a href="' . get_the_permalink( $post_id ) . '">' . 'לחצו כאן לצפייה בסיפור' . '</a><br/>';
    $email_msg .=  '<a href="' . get_edit_post_link( $post_id ) . '">' . 'לחצו כאן לעריכת הסיפור (חובה להיות מחוברים כמנהל)' . '</a><br/>';
    $email_msg .= '<img src="' .  IMAGES_DIR . '/general/logo-mail.jpg" />';

    $email_msg .= getEmailSignature();
    $email_msg .= ob_get_contents();
    ob_end_clean();
    $email_msg .= '</div>';

    
	$options_page_mail = get_field ('acf-options-mail-notification', 'options');	
	
	if ( !$options_page_mail  ){
		$options_page_mail = get_bloginfo('admin_email');
	}
	
    $to =  filter_var( $options_page_mail , FILTER_SANITIZE_EMAIL );

    mail($to, "סיפור חדש התפרסם בקשר הרב דורי", $email_msg , $headers);


}



// Delete all quotes and dictionary values from the DB
function cleanStoryData( $post_id )
{
    $post = get_post( $post_id );

    if ( $post->post_type == STORY_POST_TYPE )
    {
        BH_delete_all_post_dictionary_terms( $post_id );
        BH_delete_all_post_quotes( $post_id );

    }

}
add_action('delete_post', 'cleanStoryData');

function cleanStoryImages( $post_id )
{
    $post = get_post( $post_id );

    if ( $post->post_type == STORY_POST_TYPE ) {
        delete_post_media( $post_id );
    }
}
//add_action('before_delete_post', 'cleanStoryImages');



// Returns a string representing the email signature
function getEmailSignature()
{
    ob_start();
?>
    <p dir="RTL">
        <strong>כולנו חלק מהסיפור</strong>
    </p>
    <p dir="RTL">
        לקריאת סיפורים נוספים: <a href="www.ravdori.co.il">www.ravdori.co.il</a>
    </p>
    <p dir="RTL">
        בברכה,
    </p>
    <p dir="RTL">
        צוות תכנית הקשר הרב דורי
    </p>
    <p dir="RTL">
        <a href="mailto:Kesher.rav.dori@bh.org.il">Kesher.rav.dori@bh.org.il</a>
    </p>
    <p dir="RTL">
        <a href="tel:037457928">
            03-7457928
        </a>
    </p>
<br/>
<?php
    $emailSignature = ob_get_contents();
    ob_end_clean();
    $emailSignature .= '<img src="' .  IMAGES_DIR . '/general/logo-mail.jpg" />';

    return ( $emailSignature );
}




function delete_post_media( $post_id ) {

    if(!isset($post_id)) return; // Will die in case you run a function like this: delete_post_media($post_id); if you will remove this line - ALL ATTACHMENTS WHO HAS A PARENT WILL BE DELETED PERMANENTLY!
    elseif($post_id == 0) return; // Will die in case you have 0 set. there's no page id called 0 :)
    elseif(is_array($post_id)) return; // Will die in case you place there an array of pages.

    else {

        $attachments = get_posts( array(
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'post_parent'    => $post_id
        ) );

        foreach ( $attachments as $attachment ) {
            if ( false === wp_delete_attachment( $attachment->ID ) ) {
                // Log failure to delete attachment.
            }
        }
    }
}