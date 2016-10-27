<?php
/**
 *
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class BH_Step2Model {

    public function getStep2Info() {

        global $wizardSessionManager;

        // The returning array which will hold all the data from the DB
        $step2InfoArray  = array ();

        /*
        #region Get all the countries from the DB


        // Get all the countries
        $taxonomies = get_terms(SCHOOLS_TAXONOMY, array('hide_empty' => false , 'parent'  => 0 ));

        $countriesArray = array();

        // Save all the countries taxonomies names and id in an array
        if ($taxonomies) {
            foreach ($taxonomies as $taxonomy) {
                $countriesArray[] = array('id' => $taxonomy->term_id, 'name' => $taxonomy->name);
            }
        }


        // Save them to the returned array
        $step2InfoArray['countries'] = $countriesArray;*/

        $step2InfoArray['countries'] = BH_get_cached_location( SCHOOLS_TAXONOMY );

        #endregion


        // Get the mail and user ID
        $email = $wizardSessionManager->getStepData( IWizardStep1Fields::ID );
        $email = $email[ IWizardStep1Fields::EMAIL ];
        $user_exists = email_exists( $email );




        // If the user exist in the system
        // and the session has no data about any user
        if ( $user_exists  )
        {


            // Mark the user as existing
            $step2InfoArray['user']['exist'] = true;
            $wizardSessionManager->setField( IWizardStep2Fields::USER_ID , $user_exists );

            // Initial arg for the following queries
            $args = array(
                            'post_type'   => STORY_POST_TYPE,
                            'author'      => $user_exists,
                         );


            // Save all the queries objects in the array

            $args['post_status']  =  array('draft');
            $draft = new WP_Query( $args );
            $step2InfoArray['user']['draft'] = $draft;


            $args['post_status'] = array('pending');
            $pending = new WP_Query( $args );
            $step2InfoArray['user']['pending'] = $pending;


            $args['post_status'] = array('publish');
            $publish = new WP_Query( $args );
            $step2InfoArray['user']['publish'] = $publish;

            wp_reset_postdata();
            wp_reset_query();
            $step2Fields = array();

            $user_info           = get_userdata( $user_exists );
            $name_before_marrige = get_field('field_54b52b42b1d17' , 'user_' . $user_exists );
            $birthyear           = get_field('field_54b52b54b1d18' , 'user_' . $user_exists );
            $birthcountry        = get_field('field_54b52d9641e06' , 'user_' . $user_exists );
            $birthcity           = get_field('field_55c0a35e509b1' , 'user_' . $user_exists );
            $immegrationyear     = get_field('field_54b5309aae66c' , 'user_' . $user_exists );




            $step2Fields[IWizardStep2Fields::FIRST_NAME]        = $user_info->first_name;
            $step2Fields[IWizardStep2Fields::LAST_NAME]         = $user_info->last_name;
            $step2Fields[IWizardStep2Fields::BIRTHDAY]          = $birthyear;
            $step2Fields[IWizardStep2Fields::IMMIGRATION_DATE]  = $immegrationyear;
            $step2Fields[IWizardStep2Fields::ADDITIONAL_NAME]   = $name_before_marrige;
            $step2Fields[IWizardStep2Fields::BIRTH_CITY]        = $birthcity;
            $step2Fields[IWizardStep2Fields::BIRTH_COUNTRY]     = $birthcountry;


            // Get all the user fields and init the session with them
            $wizardSessionManager->setStepData( IWizardStep2Fields::ID , $step2Fields );

            $thisStep[IWizardStep2Fields::USER_LOADED] = IWizardStep2UserStatus::LOADED;


            logUserIn( $user_exists );


        }
        else
        {
            // Mark the user as non existing
            $step2InfoArray['user']['exist'] = false;
            $wizardSessionManager->setField( IWizardStep2Fields::USER_ID , null );
        }

        return ( $step2InfoArray );
    }

} 