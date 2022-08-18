<?php
/**
 * Adding Support for multiple languages - adding theme support and related translation frunction
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


interface ISupportedLanguages  {
	
    const HE = array( 'get_param_value' => 'he', 'locale_file' => 'he_IL'  , 'dir' => 'rtl' , 'display_name'  => 'עברית'    , 'upload-story-name' => 'העלאת סיפור' );
	const EN = array( 'get_param_value' => 'en', 'locale_file' => 'en_GB'  , 'dir' => 'ltr' , 'display_name'  => 'English'  , 'upload-story-name' => 'Upload Story' );
	const RU = array( 'get_param_value' => 'ru', 'locale_file' => 'ru_RU'  , 'dir'  => 'ltr' , 'display_name' => 'русский'  , 'upload-story-name' => 'Загрузите свою Историю' );
	const ES = array( 'get_param_value' => 'es', 'locale_file' => 'es_ES'  , 'dir'  => 'ltr' , 'display_name' => 'Español'  , 'upload-story-name' => 'Subir historia' );

}


load_theme_textdomain('BH', get_template_directory() . '/languages');



function setup_text_domain(){
    load_theme_textdomain('BH', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'setup_text_domain');


	/**
	 * Get a translated string by langauge.
	 * 
	 * Works like __(), but with an locale argument
	 *
	 * @param String $string: The string to translate.
	 * @param String $textdomain:  The theme's textdomain.
	 * @param Array  $locale: The language to get the translateion in .
	 *
	 * Return: String: The String $string translated in
	 */
function BH__( $string, $textdomain, $locale ) {
  global $l10n;
  
  if ( $locale == ISupportedLanguages::HE['locale_file'])
	  return $string;
  
  if ( in_array( $textdomain , $l10n ) ) {
	$backup = $l10n[$textdomain];
  }
  

	
  load_textdomain($textdomain, get_template_directory() . '/languages/'. $locale . '.mo');
  $translation = __($string,$textdomain);
  
  if ( in_array( $textdomain , $l10n ) ) {
	$l10n[$textdomain] = $backup;
  }
  
  return( htmlspecialchars($translation ,ENT_COMPAT | ENT_HTML401 ,  ini_get("default_charset") , false) );
}


function BH__e($string, $textdomain, $locale){
  echo BH__($string, $textdomain, $locale);
}



	/**
	 * Get a local file name based a language code given in a get param
	 * 
	 *
	 * @param Boolean $returnFullLanguagedata: True to return the full language inforamtion, i.e direction,the get param name etc.
	 *		  otherwise, only the locale file name is returned	
	 *
	 * Return: String: The name of the local file according to the ISupportedLanguages interface
	 */
function get_language_locale_filename_by_get_param( $returnFullLanguageData = false ) {
	
	// Get the current language from the GET param and sanitize the input,
	// Put the default value (Hebrew) if null
	if (  !isset (  $_GET['lang'] ) || $_GET['lang'] == NULL) $_GET['lang'] = ISupportedLanguages::HE['get_param_value'];
	$current_language_by_get = sanitize( $_GET['lang'] );
	
	// Get the ISupportedLanguages interface's langauge local file name according to the get param "lang"
	$oClass = new ReflectionClass ('ISupportedLanguages');
	$current_language_by_get = $oClass->getConstant ( strtoupper ( $current_language_by_get ) );
	unset ($oClass);

	
	if ( $returnFullLanguageData == false)
	{
		// if non valid langauge passed return Hebrew as default, else return the choose langauge
		$locale = ( $current_language_by_get == false ? ISupportedLanguages::HE['get_param_value'] : $current_language_by_get['locale_file'] );
	}
	else 
	{
		// if non valid langauge passed retuen Hebrew as default, else return the interface
		$locale = ( $current_language_by_get == false ? ISupportedLanguages::HE : $current_language_by_get );
	}
	

	return ( $locale );
	
}








/**
 * 
 *
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 */
function theme_change_comment_field_names( $translated_text, $text, $domain ) {

	require_once  $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/ravdori/functions/wizard/BH_SessionManager.php';

	$locale = get_language_locale_filename_by_get_param(true);
	$locale =  $locale["locale_file"];
		
		   
    if ( isset( $_SESSION[ 'CURRENT_STEP' ] ) AND $_SESSION[ 'CURRENT_STEP' ] == 4 ) 
	{
		
		$locale = get_language_locale_filename_by_get_param(true);
		$locale =  $locale["locale_file"];
	
		   if ( $translated_text == "להכניס לתוכן" ) 
		   { 
				$translated_text = BH__("להכניס לתוכן" , "BH" , $locale);
		   }
		   
		
		   switch ( $translated_text ) {

		   
            case 'בחירת קבצים' : $translated_text = BH__('בחירת קבצים' , "BH" , $locale);
            break;
			
	  	    case 'הוספת מדיה' : $translated_text = BH__('הוספת מדיה' , "BH" , $locale);
            break;
			
			case "יש לשחרר את הקבצים במקום כלשהו בכדי להעלות": $translated_text = BH__("יש לשחרר את הקבצים במקום כלשהו בכדי להעלות" , "BH" , $locale);
            break;
			
			case "גודל קובץ מקסימלי: %s." : $translated_text = BH__("גודל קובץ מקסימלי: %s." , "BH" , $locale);
            break;
			
			case "חיפוש בפריטי מדיה..." : $translated_text = BH__("חיפוש בפריטי מדיה..." , "BH" , $locale);
            break;
			
			case "לא נמצאו קבצי מדיה." : $translated_text = BH__("לא נמצאו קבצי מדיה." , "BH" , $locale);
            break;
			
			case "יש לגרור את קבצי המדיה כדי לשנות מיקום." : $translated_text = BH__("יש לגרור את קבצי המדיה כדי לשנות מיקום." , "BH" , $locale);
            break;
		
			case  "העלאת קבצים" : $translated_text = BH__( "העלאת קבצים" , "BH" , $locale);
            break;
			
			case "להכניס גלריה": $translated_text = BH__("להכניס גלריה" , "BH" , $locale);
            break;
			
			case "ספריית מדיה" : $translated_text = BH__("ספריית מדיה" , "BH" , $locale);
            break;
			
			case "להכניס לתוכן" : $translated_text = BH__("להכניס לתוכן" , "BH" , $locale);
            break;
			
			case "להוסיף לתוכן" : $translated_text = BH__("להוסיף לתוכן" , "BH" , $locale);
            break;			
					
			case "למחוק לצמיתות" : $translated_text = BH__("למחוק לצמיתות" , "BH" , $locale);
            break;


        };
				
    }
		
    return $translated_text;
}
add_filter( 'gettext', 'theme_change_comment_field_names', 20, 3 );
