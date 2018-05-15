<?php
/**
 * This file contains the BH_Step3Controller class
 *
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once 'BH_Controller.php';

require_once WIZARD_MODELS      . 'BH_Step4Model.php';

require_once WIZARD_DIRECTORY   . 'BH_SessionManager.php';


class BH_Step4Controller extends BH_Controller{

    public $step4Model;


    function __construct()
    {
        $this->step4Model = new BH_Step4Model();
        $this->stepId     = IWizardStep4Fields::ID;

        // Add auto save function
        add_action( 'wp_ajax_nopriv_autoSaveStory_ajax' , array( $this, 'autoSaveStory_ajax' ) );
        add_action( 'wp_ajax_autoSaveStory_ajax'        , array( $this, 'autoSaveStory_ajax' ) );
    }


    /**
     * Shows the view of this step
     */
    public function showStepScreen( $errors )
    {
        // Get all the data from the db
        $data = $this->step4Model->getStep4Info();

        if ( $errors ) {
            $data['errors'] = $errors;
        }

        // Show the page
        $this->view ( 'story_details.php' , $data );
    }

    /**
     * This function will be raise when the user revisits the step
     */
    protected function onStepRevisit()
    {
        global $wizardSessionManager;

        // Set the step to the first one

        // Change the step status to validating
        $wizardSessionManager->setStepStatus( IWizardStep4Fields::ID , IWizardSessionFieldsStatus::VALIDATING );


        $wizardSessionManager->setCurrentStep( IWizardStep4Fields::ID );
    }

    /**
     * Checks the validity of the step's fields.
     *
     * This function will be raise after the user clicked the
     * form's submit button.
     */
    protected function onStepFieldsValidation()
    {
        $errors = array();

        $isSaveState = false;

        // Check if we are saving
        if ( isset( $_POST['do-saving'] ) )
        {
            $isSaveState = ( $_POST['do-saving'] );


            if ( $isSaveState == 'save' )
                $isSaveState = true;
            else
                $isSaveState = false;
        }

        // If we just saving, return without errors with a flag
        // saying we are saving the page, and ignore any errors
        if ( $isSaveState )
        {
            return ($errors);
        }


        #region Story title validation

        $storyTitleFromUser = isset ( $_POST[ IWizardStep4Fields::STORY_TITLE ] ) ?  sanitize_text_field ( $_POST[IWizardStep4Fields::STORY_TITLE ] ) : null  ;

        if ( !isset( $storyTitleFromUser ) )
        {
            $errors[ IWizardStep4Fields::STORY_TITLE ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }

        #endregion


        #region Story subtitle validation

        $storySubTitleFromUser = isset ( $_POST[ IWizardStep4Fields::STORY_SUBTITLE ] ) ?  sanitize_text_field( $_POST[IWizardStep4Fields::STORY_SUBTITLE ] ) : null  ;

        if ( !isset( $storySubTitleFromUser ) )
        {
            $errors[ IWizardStep4Fields::STORY_SUBTITLE ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }

        #endregion


        #region Story content validation

        $storyContentFromUser = isset ( $_POST[ IWizardStep4Fields::STORY_CONTENT ] ) ?  namesSanitization( $_POST[IWizardStep4Fields::STORY_CONTENT ] ) : null  ;

        if ( !isset( $storyContentFromUser ) )
        {
            $errors[ IWizardStep4Fields::STORY_CONTENT ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }

        #endregion

/*
        #region image from adult past

        $imageAdultFromUser = isset ( $_POST[ IWizardStep4Fields::IMAGE_ADULT ] ) ?  ( $_POST[IWizardStep4Fields::IMAGE_ADULT ] ) : null  ;

        if ( !isset( $imageAdultFromUser ) )
        {
            $errors[ IWizardStep4Fields::IMAGE_ADULT ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }

        #endregion


        #region image of youngster

        $imageYoungFromUser = isset ( $_POST[ IWizardStep4Fields::IMAGE_ADULT_STUDENT ] ) ?  ( $_POST[IWizardStep4Fields::IMAGE_ADULT_STUDENT ] ) : null  ;

        if ( !isset( $imageYoungFromUser ) )
        {
            $errors[ IWizardStep4Fields::IMAGE_ADULT_STUDENT ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }

        #endregion

*/
        #region Dictionary

            $dictionaryTermsFromUser = isset ( $_POST[ IWizardStep4Fields::DICTIONARY ] ) ?  ( $_POST[IWizardStep4Fields::DICTIONARY ] ) : null  ;

        if ( !isset( $dictionaryTermsFromUser ) )
        {
            $errors[ IWizardStep4Fields::DICTIONARY ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }
        else
        {

            if ( count($_POST[ IWizardStep4Fields::DICTIONARY ]) == 0 ) {
                $errors[IWizardStep4Fields::DICTIONARY] = array(IWizardErrorsFields::IS_VALID => false, IWizardErrorsFields::MESSAGE => 'חובה להזין לפחות ערך אחד');
            }

        }


        #endregion


        #region Quotes

        $quotesTermsFromUser = isset ( $_POST[ IWizardStep4Fields::QUOTES ] ) ?  ( $_POST[IWizardStep4Fields::QUOTES ] ) : null  ;

        if ( !isset( $quotesTermsFromUser ) )
        {
            $errors[ IWizardStep4Fields::QUOTES ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }
        else
        {

            if ( count($_POST[ IWizardStep4Fields::QUOTES ]) == 0 ) {
                $errors[IWizardStep4Fields::QUOTES] = array(IWizardErrorsFields::IS_VALID => false, IWizardErrorsFields::MESSAGE => 'חובה להזין לפחות ערך אחד');
            }

        }

        #endregion



        #region Subjects

        $storySubjectsFromUser = isset ( $_POST[ IWizardStep4Fields::STORY_SUBJECTS ] ) ?  ( $_POST[IWizardStep4Fields::STORY_SUBJECTS ] ) : null  ;

        if ( !isset( $storySubjectsFromUser ) )
        {
            $errors[ IWizardStep4Fields::STORY_SUBJECTS ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }
        else
        {

            if ( count($_POST[ IWizardStep4Fields::STORY_SUBJECTS ]) == 0 ) {
                $errors[IWizardStep4Fields::STORY_SUBJECTS] = array(IWizardErrorsFields::IS_VALID => false, IWizardErrorsFields::MESSAGE => 'חובה להזין לפחות ערך אחד');
            }
        }

        #endregion


        return ( $errors );
    }

    /**
     * This function will be raise when this is the first time
     * the step visited by the user (no session fields exists for this step).
     */
    protected function onStepFirstLoad() { }

    /**
     * This function will be raise if the step's fields has errors.
     */
    protected function onStepFieldsErrors()
    {
        global $wizardSessionManager;

        $wizardSessionManager->setCurrentStep( IWizardStep4Fields::ID );
    }


    protected function onStepNoErrors() {


        global $wizardSessionManager;


        // If we are in this step all fields are valid, so we will build an array holding them
        $step4Fields = array();

        $step4Fields[ IWizardStep4Fields::STORY_TITLE ]         =  $_POST[ IWizardStep4Fields::STORY_TITLE ];  //namesSanitization ( $_POST[ IWizardStep4Fields::STORY_TITLE ] );
        $step4Fields[ IWizardStep4Fields::STORY_SUBTITLE ]      =   ( $_POST[ IWizardStep4Fields::STORY_SUBTITLE ] );
        $step4Fields[ IWizardStep4Fields::STORY_CONTENT ]       =   ( $_POST[ IWizardStep4Fields::STORY_CONTENT ] );
        $step4Fields[ IWizardStep4Fields::IMAGE_ADULT ]         =   ( $_POST[ IWizardStep4Fields::IMAGE_ADULT ] );
        $step4Fields[ IWizardStep4Fields::IMAGE_ADULT_DESC ]    =   ( $_POST[ IWizardStep4Fields::IMAGE_ADULT_DESC ] );
        $step4Fields[ IWizardStep4Fields::IMAGE_ADULT_STUDENT]  =   ( $_POST[ IWizardStep4Fields::IMAGE_ADULT_STUDENT ] );
        $step4Fields[ IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC]  =   ( $_POST[ IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC ] );
        $step4Fields[ IWizardStep4Fields::DICTIONARY]           =   ( $_POST[ IWizardStep4Fields::DICTIONARY ] );
        $step4Fields[ IWizardStep4Fields::QUOTES]               =   ( $_POST[ IWizardStep4Fields::QUOTES ] );
        $step4Fields[ IWizardStep4Fields::STORY_SUBJECTS]       =   isset ( $_POST[ IWizardStep4Fields::STORY_SUBJECTS ] ) ? $_POST[ IWizardStep4Fields::STORY_SUBJECTS ] : null;
        $step4Fields[ IWizardStep4Fields::STORY_SUBTOPICS]      =   isset ( $_POST[ IWizardStep4Fields::STORY_SUBTOPICS ] ) ? $_POST[ IWizardStep4Fields::STORY_SUBTOPICS ]  : null;
        $step4Fields[ IWizardStep4Fields::STORY_LANGUAGE]       =   isset ( $_POST[ IWizardStep4Fields::STORY_LANGUAGE ] ) ? $_POST[ IWizardStep4Fields::STORY_LANGUAGE ]  : null;
        $step4Fields[ IWizardStep4Fields::RAVDORI_FEEDBACK]     =  implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST[ IWizardStep4Fields::RAVDORI_FEEDBACK ] ) ) );




        // Save the post id if one exist
        $step4Data = $wizardSessionManager->getStepData( IWizardStep4Fields::ID );
        if ( isset( $step4Data[IWizardStep4Fields::POST_ID] ) )
        {
            $step4Fields[IWizardStep4Fields::POST_ID] =  $step4Data[IWizardStep4Fields::POST_ID];
        }


        $isSaveState = false;

        // Check if we are saving
        if ( isset( $_POST['do-saving'] ) )
        {
            $isSaveState = ( $_POST['do-saving'] );


            if ( $isSaveState == 'save' ) {
                $isSaveState = true;
                $wizardSessionManager->setField( 'do-saving' , true );
            }
            else
                $isSaveState = false;
        }


        // Save the fields in the session
        $wizardSessionManager->setStepData(IWizardStep4Fields::ID, $step4Fields);


        // Create new story or update the current one
        $this->createNewStoryPost();

        // If we are not saving, move to the next step
        if ( !$isSaveState )
        {
            // Set the status to NO_ERRORS
            $wizardSessionManager->setStepStatus(IWizardStep4Fields::ID, IWizardSessionFieldsStatus::NO_ERRORS);

            // Set the next step to First_Time
            $wizardSessionManager->setStepStatus(IWizardStep5Fields::ID, IWizardSessionFieldsStatus::FIRST_TIME);

            // Change to the second step
            $wizardSessionManager->setCurrentStep(IWizardStep5Fields::ID);

        }

        header("Refresh:0");

    }



    private function createNewStoryPost()
    {
        global $wizardSessionManager;
		
		// Check if the session is not expired,
		// If expired, logout
		//$wizardSessionManager->checkTimeout( false );
		/*if( $wizardSessionManager->isSessionTimeout() ) {
			$wizardSessionManager->closeAndLogout();
			return;
		}*/
		
        $step1Data = $wizardSessionManager->getStepData( IWizardStep1Fields::ID );
        $step2Data = $wizardSessionManager->getStepData( IWizardStep2Fields::ID );
        $step3Data = $wizardSessionManager->getStepData( IWizardStep3Fields::ID );
        $step4Data = $wizardSessionManager->getStepData( IWizardStep4Fields::ID );


        $child_term = get_term(  $step1Data[ IWizardStep1Fields::CITY ]  ,  SCHOOLS_TAXONOMY );
        $district   = get_term( $child_term->parent, SCHOOLS_TAXONOMY );

        // Convert all strings to int, because the TAX_INPUT param for wp_insert_post can receive only INT's
        if ( $step4Data[ IWizardStep4Fields::STORY_SUBJECTS ] )
            $step4Data[ IWizardStep4Fields::STORY_SUBJECTS ]  = array_map(function($var) { return is_numeric($var) ? (int)$var : $var; }, $step4Data[ IWizardStep4Fields::STORY_SUBJECTS ]);

        if ( $step4Data[ IWizardStep4Fields::STORY_SUBTOPICS ] )
            $step4Data[ IWizardStep4Fields::STORY_SUBTOPICS ] = array_map(function($var) { return is_numeric($var) ? (int)$var : $var; }, $step4Data[ IWizardStep4Fields::STORY_SUBTOPICS ]);

        if ( $step4Data[ IWizardStep4Fields::STORY_LANGUAGE ] )
            $step4Data[ IWizardStep4Fields::STORY_LANGUAGE ] = array_map(function($var) { return is_numeric($var) ? (int)$var : $var; }, $step4Data[ IWizardStep4Fields::STORY_LANGUAGE ]);


        $post_information    = array(
            'post_title'     => $step4Data[ IWizardStep4Fields::STORY_TITLE ],
            'post_content'   => $step4Data[ IWizardStep4Fields::STORY_CONTENT ] ,
            'tax_input'      => array(
                SUBJECTS_TAXONOMY  =>  $step4Data[ IWizardStep4Fields::STORY_SUBJECTS ],
                SUBTOPICS_TAXONOMY =>  $step4Data[ IWizardStep4Fields::STORY_SUBTOPICS ],
                LANGUAGES_TAXONOMY =>  $step4Data[ IWizardStep4Fields::STORY_LANGUAGE ],
                SCHOOLS_TAXONOMY   =>  array( $step1Data[ IWizardStep1Fields::SCHOOL_NAME] , $step1Data[ IWizardStep1Fields::COUNTRY ] , $step1Data[ IWizardStep1Fields::CITY ] ,$district->term_id ),
            ),
            'post_type'      => STORY_POST_TYPE,
			'post_date' 	 =>  current_time( 'mysql' ),
            'post_status'    => 'draft'
        );


        // If there is an ID (it's an existing story) , update the post
        if ( isset( $step4Data[IWizardStep4Fields::POST_ID] ) )
        {
            $post_information['ID'] =  $step4Data[IWizardStep4Fields::POST_ID];
        }

        $post_id = wp_insert_post( $post_information );

        // Set the post id in the session
        $wizardSessionManager->setStepDataByField(IWizardStep4Fields::ID, IWizardStep4Fields::POST_ID , $post_id );


        if( $post_id )
        {

            update_field( 'field_54abc9afd34dc' , $step4Data[IWizardStep4Fields::STORY_SUBTITLE]       ,  $post_id ); // acf-story-secondary-text
            update_field( 'field_54abcc61d34de' , $step4Data[IWizardStep4Fields::IMAGE_ADULT_STUDENT]  ,  $post_id ); // acf-story-images-adult-child
            update_field( 'field_54b644a028442' , $step4Data[IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC]  ,  $post_id ); // acf-story-images-adult-child-description
            update_field( 'field_54abcca5d34df' , $step4Data[IWizardStep4Fields::IMAGE_ADULT]               ,  $post_id ); // acf-story-images-adult-past
            update_field( 'field_54b644fb28443' , $step4Data[IWizardStep4Fields::IMAGE_ADULT_DESC]          ,  $post_id ); // acf-story-images-adult-past-description
            update_field( 'field_55c758c7023d6' , $step4Data[IWizardStep4Fields::RAVDORI_FEEDBACK]     ,  $post_id ); // acf-story-feedback
            update_field( 'field_556c77ef78b9a' , $step3Data[IWizardStep3Fields::FIRST_NAME]           ,  $post_id ); // acf-story-student-fname
            //update_field( 'field_556c786e78b9b' , $step3Data[IWizardStep3Fields::LAST_NAME]            ,  $post_id ); // acf-story-student-lname
            update_field( 'field_556c796778b9c' , $step3Data[IWizardStep3Fields::GRADE]                ,  $post_id ); // acf-story-student-class
            update_field( 'field_556c797678b9d' , $step3Data[IWizardStep3Fields::TEACHER_NAME]         ,  $post_id ); // acf-story-student-teacher


            $step4Data = $wizardSessionManager->getStepData( IWizardStep4Fields::ID );


            $terms  = array();
            $values = array();
            $dictionary = $step4Data[IWizardStep4Fields::DICTIONARY];
			
			if (is_array($dictionary) || is_object($dictionary))
			{
				foreach ( $dictionary as $term ){

					$terms[]  = $term['text-input'];
					$values[] = $term['textarea-input1'];

				}

				BH_delete_all_post_dictionary_terms( $post_id );
				BH_add_dictionary_terms( $terms ,$values , $post_id );
			}
			
            $terms  = array();
            $quotes = $step4Data[ IWizardStep4Fields::QUOTES ];
			
			if (is_array($quotes) || is_object($quotes))
			{			
				foreach ( $quotes as $quote )
				{
					$terms[]  = $quote['textarea-input2'];
				}

				BH_delete_all_post_quotes( $post_id );
				BH_add_quotes( $terms , $post_id  );
			}
        }

    }


    function autoSaveStory_ajax()
    {
        global $wizardSessionManager;
		
				$formData = null;
				
				// Deserialize the story details post
				parse_str($_POST['data'], $_POST);


				$step4Fields = array();

				$step4Fields[ IWizardStep4Fields::STORY_TITLE ]              =   isset ( $_POST[ IWizardStep4Fields::STORY_TITLE ]    )            ? sanitize_text_field ( $_POST[ IWizardStep4Fields::STORY_TITLE ] ) : null;
				$step4Fields[ IWizardStep4Fields::STORY_SUBTITLE ]           =   isset ( $_POST[ IWizardStep4Fields::STORY_SUBTITLE ] )            ? $_POST[ IWizardStep4Fields::STORY_SUBTITLE ] : null;
				$step4Fields[ IWizardStep4Fields::STORY_CONTENT ]            =   isset ( $_POST[ IWizardStep4Fields::STORY_CONTENT ]  )            ? $_POST[ IWizardStep4Fields::STORY_CONTENT ]  : null;
				$step4Fields[ IWizardStep4Fields::IMAGE_ADULT ]              =   isset ( $_POST[ IWizardStep4Fields::IMAGE_ADULT ]    )            ? $_POST[ IWizardStep4Fields::IMAGE_ADULT ] : null;
				$step4Fields[ IWizardStep4Fields::IMAGE_ADULT_DESC ]         =   isset ( $_POST[ IWizardStep4Fields::IMAGE_ADULT_DESC ] )          ? $_POST[ IWizardStep4Fields::IMAGE_ADULT_DESC ] : null;
				$step4Fields[ IWizardStep4Fields::IMAGE_ADULT_STUDENT]       =   isset ( $_POST[ IWizardStep4Fields::IMAGE_ADULT_STUDENT ] )       ? $_POST[ IWizardStep4Fields::IMAGE_ADULT_STUDENT ] : null;
				$step4Fields[ IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC]  =   isset  ( $_POST[ IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC ] ) ? $_POST[ IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC ] : null;
				$step4Fields[ IWizardStep4Fields::DICTIONARY]                =   isset ( $_POST[ IWizardStep4Fields::DICTIONARY ] )                ? $_POST[ IWizardStep4Fields::DICTIONARY ] : null;
				$step4Fields[ IWizardStep4Fields::QUOTES]                    =   isset ( $_POST[ IWizardStep4Fields::QUOTES ] )                    ? $_POST[ IWizardStep4Fields::QUOTES ] : null;
				$step4Fields[ IWizardStep4Fields::STORY_SUBJECTS]            =   isset ( $_POST[ IWizardStep4Fields::STORY_SUBJECTS ] )            ? $_POST[ IWizardStep4Fields::STORY_SUBJECTS ] : null;
				$step4Fields[ IWizardStep4Fields::STORY_SUBTOPICS]           =   isset ( $_POST[ IWizardStep4Fields::STORY_SUBTOPICS ] )           ? $_POST[ IWizardStep4Fields::STORY_SUBTOPICS ]  : null;
				$step4Fields[ IWizardStep4Fields::STORY_LANGUAGE]            =   isset ( $_POST[ IWizardStep4Fields::STORY_LANGUAGE ] )            ? $_POST[ IWizardStep4Fields::STORY_LANGUAGE ]  : null;
				$step4Fields[ IWizardStep4Fields::RAVDORI_FEEDBACK]          =   isset( $_POST[ IWizardStep4Fields::RAVDORI_FEEDBACK ] )           ? namesSanitization ( $_POST[ IWizardStep4Fields::RAVDORI_FEEDBACK ] ) : null;


				
				$step4Data = $wizardSessionManager->getStepData( IWizardStep4Fields::ID );
				
				// Save the post if it exist in the system
				if ( isset( $step4Data[IWizardStep4Fields::POST_ID] ) AND (get_permalink( $step4Data[IWizardStep4Fields::POST_ID] ) != false) )
				{
					$step4Fields[IWizardStep4Fields::POST_ID] =  $step4Data[IWizardStep4Fields::POST_ID];
								
					// Save the fields in the session
					$wizardSessionManager->setStepData(IWizardStep4Fields::ID, $step4Fields);

					// Create new story or update the current one
					$this->createNewStoryPost();
				}
				else  // Create if not exists
				{ 
					
					// Save the fields in the session
					$wizardSessionManager->setStepData(IWizardStep4Fields::ID, $step4Fields);

					if( !$wizardSessionManager->isSessionTimeout() ) {
						// Create new story or update the current one
						$this->createNewStoryPost();
					}
				}
				
				/*$str = 'St out ' . $_SESSION['timeout'] . 'Time: ' .  time(). ' Dur: ' .  time() - (int)$_SESSION['timeout']; 
				  $str = 'Time: ' .  time() .' '; 
				  $str .= 'Ses: ' .  $_SESSION['timeout'] .' *'; 
				  echo $str;
				  */
				
				if( $wizardSessionManager->isSessionTimeout() ) 
				{
					echo IWizardSessionFields::AJAX_SESSION_EXPIRED;
				} 
				
        die();
    }



} // EOC