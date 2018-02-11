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
	
    const HE = array( 'get_param_value' => 'he', 'locale_file' => 'he_IL'  , 'dir' => 'rtl' , 'display_name' => 'עברית'   );
	const EN = array( 'get_param_value' => 'en', 'locale_file' => 'en_GB'  , 'dir' => 'ltr' , 'display_name' => 'English' );
	const RU = array( 'get_param_value' => 'ru', 'locale_file' => 'localc' , 'dir' => 'ltr' , 'display_name' => 'русский' );

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
