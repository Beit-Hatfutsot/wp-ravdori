<?php
/**
 * Rest Api releated functions
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


require_once(FUNCTIONS_DIR . 'libs/curl/' . 'curl.php');
require_once(FUNCTIONS_DIR . 'libs/curl/' . 'curl_response.php');


/* This class represnts a basic REST API route */
abstract class BH_BeitHatfutsotRestApiRoute { 


/**
 * @var String - The Rest API URL
 */
 private $restApiUrl  = null;


/**
* Get the JSON data from the API
* @return mixed JSON data
*/ 
 abstract public function doRestApiCall();

}


/* Implemention of the linkify route with POST */
class BH_BeitHatfutsotRestApiRouteLinkifyRoute extends BH_BeitHatfutsotRestApiRoute { 

/* Constants */

/* The LinkifyCollections array indexes */

/**
 * @const Integer - The index of the returned array from the API JSON response with unique values only, and in PHP format, holding the places data
 */	 
const PLACES_ARRAY_INDEX = 0;

/**
 * @const Integer - The index of the returned array from the API JSON response with unique values only, and in PHP format, holding the family names data
 */	 
const FAMILY_NAMES_ARRAY_INDEX = 1;

/**
 * @const Integer - The index of the returned array from the API JSON response with unique values only, and in PHP format, holding the personalities data
 */	 
const PERSONALITIES_ARRAY_INDEX = 2;



/**
 * @var String - The String to send to the API (the story)
 */	
 private $queryString = null;

 
 private $LinkifyCollections = array();
 
 
 
    /* CTOR */
   function __construct( $restApiUrl , $queryString ) {
         $this->restApiUrl  = $restApiUrl;
         $this->queryString = strip_tags($queryString);
   }

   /* Public functions */   
	
	
   /**
   * Get all the collections ("places", "personalities", "familyNames") from the linkify route
   */
   public function doRestApiCall() {
	   
	   // Get the JSON data
	   $curl 	 = new Curl;
	   $response = $curl->post( $this->restApiUrl , array('html' => $this->queryString ));
	   $response = (array)(json_decode ($response));
	   
	   /*$response = get_post_meta('40271' , 'roy');
	 
	   
	   $this->LinkifyCollections[ self::FAMILY_NAMES_ARRAY_INDEX ]  = $response[0]['familyNames'];
	   $this->LinkifyCollections[ self::PERSONALITIES_ARRAY_INDEX ] = $response[0]['personalities'];
       $this->LinkifyCollections[ self::PLACES_ARRAY_INDEX ]	   		= $response[0]['places'];*/
	   
	   /* Set all the collection and remove duplicates */
	   if ( $response )
	   {
		   $this->LinkifyCollections[ self::PLACES_ARRAY_INDEX ]        = array_unique_multidimensional( $response['places'] );
		   $this->LinkifyCollections[ self::FAMILY_NAMES_ARRAY_INDEX ]  = array_unique_multidimensional( $response['familyNames'] );
		   $this->LinkifyCollections[ self::PERSONALITIES_ARRAY_INDEX ] = array_unique_multidimensional( $response['personalities'] );
		   
		   return (true);
	   }
	   
	   
	   return ( false );
   }
   
   
   public function linkifyStory( $story ) {
	   
	   
	   foreach( $this->LinkifyCollections as $LinkifyCollection ) 
	   {
		   // If we are checking the places array, check also for variations of the word with prefixes.
		   $respectPrefix = ( $LinkifyCollection == $this->LinkifyCollections[ self::PLACES_ARRAY_INDEX ]);		   
		   
		   $story = $this->addLinksFromLinkifyCollection( $LinkifyCollection ,  $story , $respectPrefix );

	   }
	   
	   return ( $story );
	   
   }
   
   
   /* Private functions */
   
   
  /**
   * Takes an input string $haystack and returns the string with every instance 
   * 
   *
   * 
   */
   private function addLinksFromLinkifyCollection( $collection , $story , $respectPrefix = false )
   {
	   echo '<pre>';
			var_dump($collection);
	   echo '</pre>';

	   foreach ( $collection as $link ) 
	   {
    
				$url   = $link->url;
				$title = $link->title;
				
				// If Hebrew link, reorder the category and post name
				if (strpos($url, 'he') !== false) 
				{
					// Get all the url parts
					$url_parts = explode("/", $url);
					
					// Get the category and post name part
					$url_post_name 	   = $url_parts[5];
					$url_category_name = $url_parts[4];
					
					
					// Change the order
					$a = $url_post_name 	. '/' .  $url_category_name;
					$b = $url_category_name . '/' .  $url_post_name;
					
					$url = str_replace( $b , $a , $url);
				}
				
			
				if ( $respectPrefix )
				{
					$prefixesArray = array ('ì' , 'å' , 'á' , 'î');
					
					
					 foreach ( $prefixesArray as $prefix ) 
					 {
						 $prefixedTitle = $prefix . $title;
						 $story 		= $this->addLinksToText($story, $prefixedTitle ,  $url );
					 }
					
				}
				else
				{
					$story = $this->addLinksToText($story, $title ,  $url );
				}
		}
		
				   
		   echo '<br><br>';
		   echo $story;
	
		return ( $story );
		
   }
   
   
   
	   
	/**
	 * Takes an input string $haystack and returns the string with every instance 
	 * of the word $needle as a link with the url $url and class $HrefClassName. 
	 *
	 * The pattern of matching is as the following:
	 *
	 *
	 * 1) It search for the whole word only:
	 * 	  For instance, if the searched word is "other" , and the given string to search in is: "His brother is the other guy",
	 *    the word that will be marked is only "other" and not br*other*.
	 *
	 *
	 * 2) It won't replace the word if it is in a tag attribute, for example:
	 *    the word "test" in <a href="example.com/test">, <span class="test"> won't be replaced.
	 * 
	 *
	 * 3) It won't replace the word if it's already inside a link, for example:
	 *    the word "test" in <a href="example.com">This is a <a href="example.com/test">test</a> </a>  won't be replaced.
	 *
	 *
	 * @param String $haystack: The string to search in.
	 * @param String $needle:   The word to search.
	 * @param Array  $url: 		The URL to the <A> tag HREF attribute wrapping the searched word.
	 * @param Array  $HrefClassName: The class attribute of the <A> tag wrapping the searched word.
	 */
	private function addLinksToText( $haystack, $needle, $url , $HrefClassName = 'external-link' ) {
		
		// return $haystack if the params are wrong
		if (strlen($HrefClassName) < 1 || strlen($haystack) < 1 || strlen($needle) < 1 || strlen($url) < 1) {
			return $haystack;
		}
		
		
		$regEx = '~(?:<a\s[^>]*>[^<]*<\/a>|<[^>]*>)(*SKIP)(*F)|\b' . $needle . '\b~u';
		
		
		$haystack= preg_replace ($regEx, '<a href="' . $url  . '" class="' . $HrefClassName . '" target="_blank">' . $needle . '</a>' ,$haystack);
		
		return $haystack;
		
	}

       

	/* Getters & Setters */

	public function getQueryString(){
		return $this->queryString;
	}

	public function setQueryString($queryString){
		$this->queryString = $queryString;
	}

	public function getPlacesArray(){
		return $this->LinkifyCollections[ self::PLACES_ARRAY_INDEX ];
	}


	public function getFamilyNamesArray(){
		return $this->LinkifyCollections[ self::FAMILY_NAMES_ARRAY_INDEX ];
	}


	public function getPersonalitiesArray(){
		return $this->LinkifyCollections[ self::PERSONALITIES_ARRAY_INDEX ];
	}
	
} // EOC




/**
* Class to communicate with Beit Hatfutsot REST API
* @author Roy Hizkya (Htmline)
*/
class BH_BeitHatfutsotRestApi {

	public $linkifyRoute = null;
	   
}
