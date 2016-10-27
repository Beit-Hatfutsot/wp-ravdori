<?php
/**
 * This file contains
 *
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// For uploading an image to the media lib from a url
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

// The csv parser
require_once(FUNCTIONS_DIR . 'story_uploader/' . 'parsecsv.lib.php');
require_once(FUNCTIONS_DIR . 'story_uploader/' . 'progress.php');

set_time_limit(0); 

$bad_st = array();

function get_post_by_title($page_title, $output = OBJECT) {
    global $wpdb;
	global $bad_st;
        $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='story'", $page_title ));
        if ( $post ) {
            
			$pp = get_post($post, $output);
			return  print_r( $pp->ID  , true );
		}

		$bad_st[] = $page_title;
    return 'BAD';
}

function parseTest ( $csvFileName )
{
	set_time_limit(0); 
   global $bad_st; 
$csv       = new parseCSV( FUNCTIONS_DIR . 'story_uploader/xsl/' . $csvFileName  );
$storyData = $csv->data;
	
	foreach ( $storyData as $story )
    {

        // Remove every non-word character
        fixArrayKey($story);
		
		
		echo $story["colp"] . '| ' . get_post_by_title( $story["colp"] ) .  '<br>';
		
		
	}

	
echo '<em>BAD:</em><br><br>';
foreach ( $bad_st as $s )
{

	echo "'" .  str_replace( "'", "''", $s ) . "'" .  ',';

}

//echo '</ol>';


}// EOF




function getGeoLocation( $name , $termID )
{ 
    $geocode = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=" . $name . "&sensor=false&key=AIzaSyC2iDiSZwApfiLb72NgNkZ7goOH3DpgtJM");

    $output = json_decode($geocode);

var_dump($output);

    if ($output->status == 'ZERO_RESULTS')
    {
     echo 'Error In: ' . $name . '<br/><br/>';
    }
    else
    {
            $lat = $output->results[0]->geometry->location->lat;
            $lng = $output->results[0]->geometry->location->lng;
//var_dump($lat);exit;

			if ( $lat != NULL AND $lng != NULL )
			{
				$school_id_acf = SCHOOLS_TAXONOMY . '_' . $termID;
				update_field( 'field_57e3de5b1df80' , $lat ,  $school_id_acf ); // acf-school-latitude	
				update_field( 'field_57e3dea71df81' , $lng   ,  $school_id_acf ); // acf-school-longitude		
			}
    }
}



function addLocations()
{


    $terms = get_terms( array(
        'taxonomy' => SCHOOLS_TAXONOMY,
        'hide_empty' => false,
        'parent'        => 0,
    ) );


    $i = 1;

    echo '<br>' . '<br>' . 'Count: ' . count($terms) . '<br>';

    foreach ( $terms as $term ) {

        //echo  $i++ . ')' .  $term->name.'<br />';

        getGeoLocation ( urlencode ( $term->name ) , $term->term_id);
      //  echo '<br />';
        //sleep(1);
    }


}


function parseStoriesFromCsv( $csvFileName )
{
	set_time_limit(0); 
    // Get all the stories into an array
    $csv       = new parseCSV( FUNCTIONS_DIR . 'story_uploader/xsl/' . $csvFileName  );
    $storyData = $csv->data;

    $PivotCsv  = new parseCSV( FUNCTIONS_DIR . 'story_uploader/xsl/schools.csv' );
    $pivot     = $PivotCsv->data;
    fixArrayKey( $pivot );


    $redirects = array(); // Holds all the redirects for the simple redirection 301 plugin

    echo 'Starting&hellip;<br />';

    global $p;
    echo '<div style="width: 300px;">';
    $p->render();
    echo '</div>';
    echo '<div id="current_story"></div>';

    $i = 0;
    $size = count($storyData);
    // Go throu all the stories
    foreach ( $storyData as $story )
    {

        // Remove every non-word character
        fixArrayKey($story);

//if ( ++$i < 94 ) continue; 

        $dataToShow = $story["adultEmail"] . '<br> | ' . ($i + 1);
        $p->setProgressBarProgress( $i * 100 / $size , $dataToShow);


        $storyID  = $story["storyId"];

        $userEmail       = $story["adultEmail"];
        $random_password = wp_generate_password( 15, true , true );
        $userFirstName   = $story["adultFirstName"];
        $userLastName    = $story["adultLastName"];

        #region Create new user

        $wpInsertArgs =  array (
            'user_login'    =>  $userEmail,
            'user_pass'     =>  $random_password,
            'first_name'    =>  $userFirstName,
            'last_name'     =>  $userLastName,
            'user_email'    =>  $userEmail,
            'display_name'  =>  $story["adultFullName"], //$userFirstName . ' ' . $userLastName,
            'nickname'      =>  $story["adultFullName"] ,//$userFirstName . ' ' . $userLastName,
            'role'          =>  'adult'
        );


        $userExist = email_exists($userEmail);

        // If it's a returning user, just update the fields
        if ( $userExist ) $wpInsertArgs['ID'] = $userExist;



        $new_user = wp_insert_user($wpInsertArgs);


        $userNameBeforeMarriage = $story["adultNameBeforeMarriage"];
        $userBirthYear = $story["adultBirthYear"];
        $userBirthCity = $story["adultBirthCity"];
        $userImmigrationDate = $story["adultAliyaYear"];
        $userBirthCountry = $story["adultBirthCountryNameHebrew"];
        $userBirthCountry = get_term_by('name', $userBirthCountry, SCHOOLS_TAXONOMY);

        if (!is_wp_error($new_user)) {
            // Update the ACF fields
            $user_id_acf = 'user_' . $new_user;
            update_field('field_54b52b42b1d17', $userNameBeforeMarriage, $user_id_acf);
            update_field('field_54b52b54b1d18', $userBirthYear, $user_id_acf);
            update_field('field_54b52d9641e06', $userBirthCountry->term_id, $user_id_acf);
            update_field('field_55c0a35e509b1', $userBirthCity, $user_id_acf);
            update_field('field_54b5309aae66c', $userImmigrationDate, $user_id_acf);

        }

        #endregion



        #region Create the story post

        $storyTitle  = $story["storyName"];

        $school = $story["school"];
        $school = array_filter ( explode(" > " , $school) );

        $country    = "ישראל"; //$school[0];
        $district   = $school[1];
        $city       = $school[2];

        if ( empty($school[3]) ) continue;

        $schoolName = $school[3];

        $district = getDistrictByCity( $city , $pivot );

        echo '<b>Location: </b><br>';
        echo '<em>City:</em> <br>';
        var_dump($city);
        echo '<br>';
        echo '<em>Distrirct:  </em> <br>';
        var_dump($district);
        echo '<br>_____________________________________<br>';


        if ( $district == -1)
            $district = 'ללא מחוז';

        $terms  = array();
        $subjects= $story["subjects"];
        $subjects = array_filter ( explode("|" , $subjects) );


        foreach ( $subjects as $subject )
        {
            $tag        = get_term_by( 'name'  , $subject , SUBJECTS_TAXONOMY );
            $subjectId  = null;
            $isTagExist = term_exists( $subject , SUBJECTS_TAXONOMY );
            
            echo '<b>Subject: </b><br>';
            var_dump( $tag );
            echo '<br>';
            if ( ($tag == false OR $tag == null) And ( $isTagExist == 0 or $isTagExist == null) )
            {
                // Subject not exist - create it
                $tag = wp_insert_term( $subject , SUBJECTS_TAXONOMY );
                
                echo 'Tag recognized as exist';

                if ($tag)
                {
                    $subjectId = $tag['term_id'];
                }
                else 
                {
                    $tag = wp_insert_term( $subject , SUBJECTS_TAXONOMY );
                    $subjectId = $tag['term_id'];
                }
            }
            else
            {
                
                if ( $tag == false)
                {
                   $tag = wp_insert_term( $subject , SUBJECTS_TAXONOMY );
                   var_dump($tag);
                   echo 'creating Tag in ELSE...' ;
                }
                
                echo 'Tag after else';
                var_dump($tag);

                $subjectId = $tag->term_id;
            }
            $terms[]  = $subjectId;
        }


        // Check if the country exists
        $country_term = term_exists( $country, SCHOOLS_TAXONOMY , 0 );

        // Create district if it doesn't exist
        if ( !$country_term ) {
            $country_term = wp_insert_term( $district, SCHOOLS_TAXONOMY , array( 'parent' => 0 ) );
        }


        // Check if the district exists
        $district_term = term_exists( $district, SCHOOLS_TAXONOMY , 3640 );

        // Create district if it doesn't exist
        if ( !$district_term ) {
            $district_term = wp_insert_term( $district, SCHOOLS_TAXONOMY , array( 'parent' => 3640 ) );
        }

        // Check if the city exists
        $city_term = term_exists( $city, SCHOOLS_TAXONOMY, $district_term['term_taxonomy_id'] );

        // Create city if it doesn't exist
        if ( !$city_term ) {
            $city_term = wp_insert_term( $city, SCHOOLS_TAXONOMY , array( 'parent' => $district_term['term_taxonomy_id'] ) );
        }
        //echo '_________________district term: ________________________*<br>';
        //var_dump($district_term['term_taxonomy_id']  );
        //echo '_________________________________________*<br>';

        // Check if the school exists
        $school_term = term_exists( $schoolName , SCHOOLS_TAXONOMY, $city_term['term_taxonomy_id'] );


        // Create school if it doesn't exist
        if ( !$school_term ) {
            $school_term = wp_insert_term( $schoolName, SCHOOLS_TAXONOMY , array( 'parent' => $city_term['term_taxonomy_id'] ) );
            echo 'Second<br>';
                var_dump($school_term);
            $school_code = $story["schoolCode"];
            $school_id_acf = SCHOOLS_TAXONOMY . '_' . $school_term['term_taxonomy_id'];
            update_field( 'field_55bf5f841d1b0'  ,  $school_code  , $school_id_acf ); // field_55bf5f841d1b0 = acf-school-code
            // https://support.advancedcustomfields.com/forums/topic/using-update_field-for-taxonomy-terms/
        }


        $postdate = $story["StoryPublishDate"];
        $postdate = date("Y-m-d H:i:s",strtotime(str_replace('/','-',$postdate)));
        
        $post_information    = array(
            'post_title'     => $storyTitle,
            'post_author'    => $new_user,
            'post_date'      => $postdate,
            'tax_input'      => array(
                SUBJECTS_TAXONOMY  =>  $terms,
                /*SUBTOPICS_TAXONOMY =>  $step4Data[ IWizardStep4Fields::STORY_SUBTOPICS ],*/
                /* LANGUAGES_TAXONOMY =>  $step4Data[ IWizardStep4Fields::STORY_LANGUAGE ],*/
                SCHOOLS_TAXONOMY   =>  array( $school_term['term_taxonomy_id'] , 3640 , $city_term['term_taxonomy_id'] , $district_term['term_taxonomy_id'] ),
            ),
            'post_type'      => STORY_POST_TYPE,
            'post_status'    => 'publish'
        );


        $post_id = wp_insert_post($post_information);



        wp_set_post_terms( $post_id, $terms, SUBJECTS_TAXONOMY );///////////////////////


        if( $post_id )
        {
            #region The story html

            // Get the story content (html)
            $storyHtml = $story["story"];


            // Create a new Html Dom parser,
            // in order to traverse the story's html content
            $doc = new DOMDocument();
            // $item = utf8_decode($storyHtml);
            //$item = htmlentities($storyHtml, ENT_QUOTES, 'UTF-8');
            @$doc->loadHTML( '<?xml encoding="UTF-8">' . $storyHtml );


            // Get all the images
            $tags = $doc->getElementsByTagName('img');


            // Go throu all the images
            foreach ( $tags as $tag ) {

                // Get the source url of the existing image
                $old_site_img_url =   $tag->getAttribute('src');
                $old_site_img_url = makeCorrectImagePath( $old_site_img_url );

                // Download the old image,upload to the media lib, assign to post and return the new
                // current html image path
                //$new_site_img_url = media_sideload_image( $old_site_img_url , $post_id , "" , 'src' );

                $imgId = downloadImageToPost( $post_id , $old_site_img_url );


                $new_site_img_url = wp_get_attachment_url( $imgId );


                // Replace the old image source with the new one
                //$tag->setAttribute( "src" ,encodeURI( $new_site_img_url) );
                $tag->setAttribute( "src" , $new_site_img_url );

            }

            // Save the html with the new image's url's
            $storyHtml = @$doc->saveHTML();

            #endregion


            // Sanitize html
            //$storyHtml = htmlentities($storyHtml, null, 'utf-8');

            //$storyHtml = str_ireplace( "&nbsp;"  , ""  , $storyHtml );
            $storyHtml = str_ireplace( "<html>"  , ""  , $storyHtml );
            $storyHtml = str_ireplace( "</html>" , ""  , $storyHtml );
            $storyHtml = str_ireplace( "<body>"  , ""  , $storyHtml );
            $storyHtml = str_ireplace( "</body>" , ""  , $storyHtml );

            //$storyHtml = html_entity_decode( $storyHtml );
            //$storyHtml = preg_replace('/<span[^>]*><\\/span[^>]*>/', "<br>" , $storyHtml);

            $post_information = array (
                'ID'           => $post_id,
                'post_content' => html_entity_decode( $storyHtml , ENT_NOQUOTES, 'UTF-8' ) ,
            );

            //var_dump($storyHtml);

            wp_update_post($post_information);


            $storySubTitle  = $story["storySubText"];


            //$new_site_img_url = media_sideload_image( encodeURI($story["imageTogetherPath"])  , $post_id , "" , 'src' );
            $image_TogetherID = downloadImageToPost( $post_id , $story["imageTogetherPath"] );
            $image_Together_desc = $story["imageTogetherTitle"];


            //$new_site_img_url = media_sideload_image(  encodeURI($story["imageAdultPastPath"]) , $post_id , "" , 'src' );
            $image_pastID     = downloadImageToPost( $post_id ,  $story["imageAdultPastPath"] );
            $image_adult_desc = $story["imageAdultPastTitle"];

            $studentsNames = $story["studentsNames"];
            $studentsGrade = $story["studentsClass"];


            update_field( 'field_54abc9afd34dc' , $storySubTitle        ,  $post_id ); // acf-story-secondary-text
            update_field( 'field_54abcc61d34de' , $image_TogetherID     ,  $post_id ); // acf-story-images-adult-child
            update_field( 'field_54b644a028442' , $image_Together_desc  ,  $post_id ); // acf-story-images-adult-child-description
            update_field( 'field_54abcca5d34df' , $image_pastID         ,  $post_id ); // acf-story-images-adult-past
            update_field( 'field_54b644fb28443' , $image_adult_desc     ,  $post_id ); // acf-story-images-adult-past-description
            update_field( 'field_556c77ef78b9a' , $studentsNames        ,  $post_id ); // acf-story-student-fname
            update_field( 'field_556c796778b9c' , $studentsGrade        ,  $post_id ); // acf-story-student-class
            //update_field( 'field_556c797678b9d' , $step3Data[IWizardStep3Fields::TEACHER_NAME]         ,  $post_id ); // acf-story-student-teacher


            wp_set_post_terms($post_id, array(3458), LANGUAGES_TAXONOMY); // Set all to hebrew by default


            #region Dictionary terms

            $terms  = array();
            $values = array();
            $dictionary = $story["dictionary"];
            $dictionary = array_filter ( explode("|" , $dictionary) );

            foreach(array_chunk( $dictionary , 2) as $term ) {
                $terms[]  = $term[0];
                $values[] = $term[1];
            }


            BH_delete_all_post_dictionary_terms($post_id);
            BH_add_dictionary_terms($terms, $values, $post_id);

            #endregion


            #region Quotes terms

            $terms  = array();
            $quotes = $story["quotes"];
            $quotes = array_filter ( explode("|" , $quotes) );

            foreach ( $quotes as $quote )
            {
                $terms[]  = $quote;
            }

            BH_delete_all_post_quotes($post_id);
            BH_add_quotes($terms, $post_id);

            #endregion

            // Add the story to the redirects array for the 301 simple redirect plugin
            $old_site_redirect_url             = "/previewpage.aspx?str=" . $storyID;
            $redirects[$old_site_redirect_url] = get_post_permalink( $post_id );
        }


        #endregion


        $i++;
    } // foreach story


    // Update the 301 simple plugin data if there is any
    if ( !empty( $redirects ) )
    {
		// Get Current Redirects
		$current_redirects = get_option('301_redirects');
		
		$redirects = array_merge($redirects, $current_redirects);

        update_option('301_redirects', $redirects);
    }

    $p->setProgressBarProgress(100);
    echo 'Done.<br />';


} // parseStoriesFromCsv


/**
 * @param $csvFileName
 */
function parseSchoolsFromCsv($csvFileName )
{

    // Get all the schools into an array
    $csv         = new parseCSV( FUNCTIONS_DIR . 'story_uploader/xsl/' . $csvFileName  );
    $schoolsData = $csv->data;

    /* The districts */
    $PivotCsv  = new parseCSV( FUNCTIONS_DIR . 'story_uploader/xsl/pivot.csv' );
    $pivot     = $PivotCsv->data;
    fixArrayKey( $pivot );

	fixArrayKey( $schoolsData );
    foreach ( $schoolsData as $school )
    {

        reset($school);
        $first_key = key($school);

        $school_id   = $school[$first_key];
        $schoolName  = $school['sname'];
        $city        = $school['scity'];
        $country     = "ישראל";
        $district    = $school['sdist'];//getDistrictByCity( $city  , $pivot );

        if ( empty($district) ) continue;


        if ( $district == '' or $district == -1 )
            $district = 'ללא מחוז';

        // Check if the country exists
        $country_term = term_exists( $country, SCHOOLS_TAXONOMY , 0 );

        // Create district if it doesn't exist
        if ( !$country_term ) {
            $country_term = wp_insert_term( $district, SCHOOLS_TAXONOMY , array( 'parent' => 0 ) );
        }


        // Check if the district exists
        $district_term = term_exists( $district, SCHOOLS_TAXONOMY , 3640 );

        // Create district if it doesn't exist
        if ( !$district_term  ) { echo 'SHOLDNT BE HERE';
            $district_term = wp_insert_term( $district, SCHOOLS_TAXONOMY , array( 'parent' => 3640 ) );

        }


        // Check if the city exists
        $city_term = term_exists( $city, SCHOOLS_TAXONOMY, $district_term['term_taxonomy_id'] );


        // Create city if it doesn't exist
        if ( !$city_term ) {
            $city_term = wp_insert_term( $city, SCHOOLS_TAXONOMY , array( 'parent' => $district_term['term_taxonomy_id'] ) );
        }


        // Check if the school exists
        $school_term = term_exists( $schoolName , SCHOOLS_TAXONOMY, $city_term['term_taxonomy_id'] );

        // Create school if it doesn't exist
        if ( !$school_term ) {
            $school_term = wp_insert_term( $schoolName, SCHOOLS_TAXONOMY , array( 'parent' => $city_term['term_taxonomy_id'] ) );
            $school_code = $school_id;
            $school_id_acf = SCHOOLS_TAXONOMY . '_' . $school_term['term_taxonomy_id'];
            update_field( 'field_55bf5f841d1b0'  ,  $school_code  , $school_id_acf ); // field_55bf5f841d1b0 = acf-school-code
            // https://support.advancedcustomfields.com/forums/topic/using-update_field-for-taxonomy-terms/
        }


    } // foreach


} // parseSchoolsFromCsv


function fixArrayKey(&$arr)
{
    $arr = array_combine(
        array_map(
            function ($str) {
                return preg_replace("/[^\w\d]/","",$str);
            },
            array_keys($arr)
        ),
        array_values($arr)
    );

    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            fixArrayKey($arr[$key]);
        }
    }
}


function makeCorrectImagePath( $path )
{
    $correctImagePath = "";

    $path = str_replace("http://bo.ravdori.co.il/","" , $path );
    $path = str_replace("http://www.ravdori.co.il/assets/","" , $path );

    if ( strrpos( $path , "assets") === false )
    {
        $correctImagePath = "http://ravdori.co.il/assets/" . str_replace ( "..\\" , "" , str_replace("../", "" , $path) );
    }
    else
    {
        $correctImagePath = "http://ravdori.co.il/" . str_replace ( "..\\" , "" , str_replace("../", "" , $path) );
    }

    return ( $correctImagePath  );
}


function encodeURI($uri)
{
    return preg_replace_callback("{[^0-9a-z_.!~*'();,/?:@&=+$#-]}i", function ($m) {
        return sprintf('%%%02X', ord($m[0]));
    }, $uri);
}



function downloadImageToPost( $post_id , $image_url )
{

    $info = parse_url($image_url);
    $safePath = implode('/', array_map('rawurlencode', explode('/', $info['path'])));
    $image_url = sprintf('%s://%s%s', $info['scheme'], $info['host'], $safePath);


    $upload_dir = wp_upload_dir(); // Set upload folder
    $image_data = file_get_contents($image_url); // Get image data

    $image_url = explode('.', basename( $image_url ) );

    $image_url = uniqid(rand(), true) . '.' . $image_url[1];

    $filename  = $image_url;


// Check folder permission and define file location
    if( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }

//$file = wp_unique_filename( $upload_dir['path'] . '/', $filename );

// Create the image  file on the server
    file_put_contents( $file, $image_data );

// Check image file type
    $wp_filetype = wp_check_filetype( $filename, null );

// Set attachment data
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

// Create the attachment
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );

// Include image.php
    require_once(ABSPATH . 'wp-admin/includes/image.php');

// Define attachment metadata
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

// Assign metadata to attachment
    wp_update_attachment_metadata( $attach_id, $attach_data );

    return ( $attach_id );

}



function getDistrictByCity( $cityToSearch , $cityArray )
{

    $district = -1;


    foreach ( $cityArray as $city )
    {
        $cityName  = $city["scity"]; // namec

        if ( !empty( $cityName ) )
        {

            if ( $cityToSearch == $cityName ) { 
                $district = $city["sdist"]; // dist
//echo $district;
            }
        }

    }

    return ( $district );
}