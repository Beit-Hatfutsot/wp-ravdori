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

require_once WIZARD_MODELS      . 'BH_Step3Model.php';

require_once WIZARD_DIRECTORY   . 'BH_SessionManager.php';


class BH_Step3Controller extends BH_Controller{

    public $step3Model;


    function __construct()
    {
        $this->step3Model = new BH_Step3Model();
        $this->stepId     = IWizardStep3Fields::ID;
    }


    /**
     * Shows the view of this step
     */
    public function showStepScreen( $errors )
    {
        // Get all the data from the db
        $data = $this->step3Model->getStep3Info();

        // If there are errors add them
        if ( $errors ) {
            $data['errors'] = $errors;
        }

        // Show the page
        $this->view ( 'recorder_details.php' , $data );
    }

    /**
     * This function will be raise when the user revisits the step
     */
    protected function onStepRevisit()
    {
        global $wizardSessionManager;

        $this->loadStory();

        // Change the step status to validating
        $wizardSessionManager->setStepStatus( IWizardStep3Fields::ID , IWizardSessionFieldsStatus::VALIDATING );


        $wizardSessionManager->setCurrentStep( IWizardStep3Fields::ID );
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


        #region On Saving

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

        // If we just saving, return without errors
        if ( $isSaveState )
            return ( $errors );

        #endregion


        #region First name validation

        $firstNameFromUser = isset ( $_POST[ IWizardStep3Fields::FIRST_NAME ] ) ?  namesSanitization( $_POST[ IWizardStep3Fields::FIRST_NAME ] ) : null  ;

        if ( !isset( $firstNameFromUser ) )
        {
            $errors[ IWizardStep3Fields::FIRST_NAME ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }
        else
        {

            if ( strlen ( $firstNameFromUser ) <= 1 ) {
                $errors[ IWizardStep3Fields::FIRST_NAME ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שם פרטי חייב להכיל לפחות 2 אותיות' );
            }

        }

        #endregion


        #region Last name validation
/*
        $lastNameFromUser = isset ( $_POST[ IWizardStep3Fields::LAST_NAME ] ) ?  namesSanitization( $_POST[ IWizardStep3Fields::LAST_NAME ] ) : null  ;

        if ( !isset( $lastNameFromUser ) )
        {
            $errors[ IWizardStep3Fields::LAST_NAME ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }
        else
        {

            if ( strlen ( $lastNameFromUser ) <= 1 ) {
                $errors[ IWizardStep3Fields::LAST_NAME ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שם משפחה חייב להכיל לפחות 2 אותיות' );
            }

        }*/

        #endregion


        #region grade validation

        $gradeFromUser = isset ( $_POST[ IWizardStep3Fields::GRADE ] ) ?  namesSanitization( $_POST[ IWizardStep3Fields::GRADE ] ) : null  ;

        if ( !isset( $gradeFromUser ) )
        {
            $errors[ IWizardStep3Fields::GRADE ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }

        #endregion


        return ( $errors );
    }

    /**
     * This function will be raise when this is the first time
     * the step visited by the user (no session fields exists for this step).
     */
    protected function onStepFirstLoad() {
        $this->loadStory();
    }

    /**
     * This function will be raise if the step's fields has errors.
     */
    protected function onStepFieldsErrors()
    {
        global $wizardSessionManager;

        $wizardSessionManager->setCurrentStep( IWizardStep3Fields::ID );
    }


    protected function onStepNoErrors() {

        global $wizardSessionManager;


        // If we are in this step all fields are valid, so we will build an array holding them
        $step3Fields = array();

        $step3Fields[ IWizardStep3Fields::FIRST_NAME ]    =  sanitize ( $_POST[ IWizardStep3Fields::FIRST_NAME ] );
        //$step3Fields[ IWizardStep3Fields::LAST_NAME ]     =  sanitize ( $_POST[ IWizardStep3Fields::LAST_NAME ] );
        $step3Fields[ IWizardStep3Fields::GRADE ]         =  sanitize ( $_POST[ IWizardStep3Fields::GRADE ] );
        $step3Fields[ IWizardStep3Fields::TEACHER_NAME ]  =  sanitize ( $_POST[ IWizardStep3Fields::TEACHER_NAME ] );



        // Save the fields in the session
        $wizardSessionManager->setStepData( IWizardStep3Fields::ID , $step3Fields );


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


        // If we are not saving, move to the next step
        if ( !$isSaveState )
        {

            // Set the status to NO_ERRORS
            $wizardSessionManager->setStepStatus(IWizardStep3Fields::ID, IWizardSessionFieldsStatus::NO_ERRORS);

            // Set the next step to First_Time
            $wizardSessionManager->setStepStatus(IWizardStep4Fields::ID, IWizardSessionFieldsStatus::FIRST_TIME);

            // Change to the second step
            $wizardSessionManager->setCurrentStep(IWizardStep4Fields::ID);
        }

        header("Refresh:0");

    }




    private function loadStory()
    {
        global $wizardSessionManager;

        $step2Data = $wizardSessionManager->getStepData( IWizardStep2Fields::ID );

        // If the user choose to load a story, load all the data
        if ( isset( $step2Data[IWizardStep2Fields::STORY_LOADED] ) )
        {

            $post_id = $step2Data[ IWizardStep2Fields::STORY_LOADED ];


           #region Load step 3 fields

           $step3Fields = array();

           $step3Fields[ IWizardStep3Fields::FIRST_NAME ]   = get_field( 'field_556c77ef78b9a' , $post_id  ); // acf-story-student-fname
           $step3Fields[ IWizardStep3Fields::LAST_NAME ]    = get_field( 'field_556c786e78b9b' , $post_id  ); // acf-story-student-lname
           $step3Fields[ IWizardStep3Fields::GRADE ]        = get_field( 'field_556c796778b9c' , $post_id  ); // acf-story-student-class
           $step3Fields[ IWizardStep3Fields::TEACHER_NAME ] = get_field( 'field_556c797678b9d' , $post_id  ); // acf-story-student-teacher

           $wizardSessionManager->setStepData( IWizardStep3Fields::ID , $step3Fields );

           #endregion


           #region step 4 fields

            $step4Fields = array();

            $storyPost = get_post( $post_id );

            $step4Fields[ IWizardStep4Fields::STORY_TITLE  ]             = $storyPost->post_title;
            $step4Fields[ IWizardStep4Fields::STORY_SUBTITLE ]           = get_field( 'field_54abc9afd34dc' ,  $post_id); // acf-story-secondary-text
            $step4Fields[ IWizardStep4Fields::STORY_CONTENT ]            = $storyPost->post_content;
            $step4Fields[ IWizardStep4Fields::IMAGE_ADULT_STUDENT ]      = get_field( 'field_54abcc61d34de' ,  $post_id); //acf-story-images-adult-child
            $step4Fields[ IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC ] = get_field( 'field_54b644a028442' ,  $post_id); // acf-story-images-adult-child-description
            $step4Fields[ IWizardStep4Fields::IMAGE_ADULT ]              = get_field( 'field_54abcca5d34df' ,  $post_id); // acf-story-images-adult-past
            $step4Fields[ IWizardStep4Fields::IMAGE_ADULT_DESC ]         = get_field( 'field_54b644fb28443' ,  $post_id); // acf-story-images-adult-past-description
            $step4Fields[ IWizardStep4Fields::POST_ID ] = $post_id;

            #region get post dictionary

            $postTerms = BH_dictionary_get_post_terms( $post_id );

            $dictionaryArray = array();
            $indexCounter = 0;
            if ( isset ( $postTerms ) )
            {
                foreach ($postTerms as $term)
                {
                    $dictionaryArray[ $indexCounter ]['text-input']      = $term->dictionary_term;
                    $dictionaryArray[ $indexCounter ]['textarea-input1'] = $term->dictionary_value;

                    $indexCounter++;
                }

            }

            #endregion


            #region get post qoutes
            $postTerms = null;
            $postTerms = BH_quotes_get_post_quotes( $post_id );

            $quoteArray = array();
            $indexCounter = 0;
            if ( isset ( $postTerms ) )
            {
                foreach ($postTerms as $term)
                {
                    $quoteArray[ $indexCounter ]['textarea-input2']   =  $term->quote_value;
                    $indexCounter++;
                }

            }
            #endregion


            $step4Fields[ IWizardStep4Fields::DICTIONARY ] = $dictionaryArray;
            $step4Fields[ IWizardStep4Fields::QUOTES ]     = $quoteArray;


            $subjects = get_the_terms ( $post_id , SUBJECTS_TAXONOMY);
            $subjectsArray = array();


            if ( isset( $subjects ) )
            {
                foreach ($subjects as $subject)
                {
                    $subjectsArray[] = $subject->term_id;
                }
            }

            $step4Fields[ IWizardStep4Fields::STORY_SUBJECTS ] = $subjectsArray;



            $subtopics = get_the_terms ( $post_id , SUBTOPICS_TAXONOMY );
            $subtopicsArray = array();

            if ( isset( $subtopics ) )
            {
                foreach ($subtopics as $subtopic)
                {
                    $subtopicsArray[] = $subtopic->term_id;
                }
            }

            $step4Fields[ IWizardStep4Fields::STORY_SUBTOPICS ] = $subtopicsArray;


            $languages = get_the_terms ( $post_id , LANGUAGES_TAXONOMY );
            $languagesArray = array();

            if ( isset( $languages ) )
            {
                foreach ($languages as $lang )
                {
                    $languagesArray[] = $lang->term_id;
                }
            }

            $step4Fields[ IWizardStep4Fields::STORY_LANGUAGE ] = $languagesArray;




            $step4Fields[ IWizardStep4Fields::RAVDORI_FEEDBACK ] = get_field( 'field_55c758c7023d6' ,  $post_id); // acf-story-feedback

            $wizardSessionManager->setStepData( IWizardStep4Fields::ID , $step4Fields );

           #endregion

        }
    }


} // EOC