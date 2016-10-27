<?php
/**
 * Global data related to the steps
 *
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



// This array holds all the steps controllers
$stepsArray = array();

// Create all the steps controllers
for ( $i = 1; $i <= WIZARD_NUM_OF_STEPS; $i++ ){

    $class = 'BH_Step' . $i . 'Controller';

    require_once      WIZARD_CONTROLLERS .$class . '.php';

    $stepsArray[ $i ] = new $class();

}



/**
 * Choose which page to show by the current step defined in the session
 */
function setStep()
{
    global $stepsArray;
    global $wizardSessionManager;

    $stepToShow = $wizardSessionManager->getCurrentStep();

    $step = $stepsArray[ $stepToShow ];
    $step->executeStep();
}



// Step 1 Ajax
add_action( 'wp_ajax_nopriv_get_city_ajax' , 'get_city_ajax' );
add_action( 'wp_ajax_get_city_ajax'        , 'get_city_ajax' );
function get_city_ajax()
{

    if ( isset($_REQUEST) )
    {
        // CHECK PARETN SERVER BACK

        // Get the country ID from the user
        $countryId = $_POST[IWizardStep1Fields::COUNTRY];


        // Get the top level (All the districts)
        $districts = get_terms ( SCHOOLS_TAXONOMY , array(
                                                         'hide_empty' => false,
                                                         'parent' => $countryId
                                                       )
                               );


        $all_cities   = array();
        $outputString = null;
        if ( ! empty( $districts ) && ! is_wp_error( $districts ) )
        {

            foreach ( $districts as $district )
            {

                // Get all the cities under the district $district
                $cities = get_terms( SCHOOLS_TAXONOMY , array(
                                                                'parent'     => $district->term_id ,
                                                                'hide_empty' => false,
                                                             )
                                   );

                if ( ! empty( $cities ) && ! is_wp_error( $cities ) )
                {
                    $all_cities = array_merge( $all_cities , $cities);
                }


            }

        }

        if ( ! empty( $all_cities ) && ! is_wp_error( $all_cities ) )
        {

                usort($all_cities, "cmp");

                foreach ($all_cities as $city)
                {
                    $outputString .= "<option value='$city->term_id'>$city->name</option> <br/>";
                }



                echo $outputString;

            global $wizardSessionManager;

            $wizardSessionManager->setField( 'SELECT_CITIES' , $all_cities );

        }

    }
    die();

}





add_action( 'wp_ajax_nopriv_get_schools_ajax' , 'get_schools_ajax' );
add_action( 'wp_ajax_get_schools_ajax'        , 'get_schools_ajax' );
function get_schools_ajax()
{

    if ( isset($_REQUEST) )
    {

        // CHECK PARETN SERVER BACK

        // Get the country ID from the user
        $cityId = $_POST[IWizardStep1Fields::CITY];


        // Get the top level (All the districts)
        $schools = get_terms ( SCHOOLS_TAXONOMY , array(
                'hide_empty' => false,
                'parent' => $cityId
            )
        );

        $outputString = null;

        if ( ! empty( $schools ) && ! is_wp_error( $schools ) )
        {

            foreach ($schools as $school)
            {
                $outputString .= "<option value='$school->term_id'>$school->name</option> <br/>";
            }

            echo $outputString;

            global $wizardSessionManager;

            $schools['SELECTED_CITY']   = $_POST[IWizardStep1Fields::CITY];
            $wizardSessionManager->setField( 'SELECT_SCHOOLS' , $schools );
        }

    }
    die();

}



/* Solve the user capabilties issue */
function wp3344_map_meta_cap( $caps, $cap, $user_id, $args ){
    if ( 'edit_post' == $cap ) {
        $post = get_post( $args[0] );
        $post_type = get_post_type_object( $post->post_type );
        $caps = array();
        if ( $user_id == $post->post_author )
            $caps[] = $post_type->cap->edit_posts;
        else
            $caps[] = $post_type->cap->edit_others_posts;
    }
    return $caps;
}
add_filter( 'map_meta_cap', 'wp3344_map_meta_cap', 10, 4 );