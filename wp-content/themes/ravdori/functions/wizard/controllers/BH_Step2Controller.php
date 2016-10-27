<?php

require_once 'BH_Controller.php';

require_once WIZARD_MODELS      . 'BH_Step2Model.php';

require_once WIZARD_DIRECTORY   . 'BH_SessionManager.php';


class BH_Step2Controller extends BH_Controller {


    public $step2Model;


    function __construct()
    {
        $this->step2Model = new BH_Step2Model();
        $this->stepId     = IWizardStep2Fields::ID;
    }


    /**
     * Shows the view of this step
     */
    public function showStepScreen( $errors )
    {
        // Get all the data from the db
        $data = $this->step2Model->getStep2Info();

        // If there are errors add them
        if ( $errors ) {
            $data['errors'] = $errors;
        }

        // Show the page
        $this->view ( 'author_details.php' , $data );
    }

    /**
     * This function will be raise when the user revisits the step
     */
    protected function onStepRevisit()
    {
        global $wizardSessionManager;

        // Set the step to the first one

        // Change the step status to validating
        $wizardSessionManager->setStepStatus( IWizardStep2Fields::ID , IWizardSessionFieldsStatus::VALIDATING );

        // Set the current step as the first
        $wizardSessionManager->setCurrentStep( IWizardStep2Fields::ID );

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

        $firstNameFromUser = isset ( $_POST[ IWizardStep2Fields::FIRST_NAME ] ) ?  namesSanitization( $_POST[ IWizardStep2Fields::FIRST_NAME ] ) : null  ;

        if ( !isset( $firstNameFromUser ) )
        {
            $errors[ IWizardStep2Fields::FIRST_NAME ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }
        else
        {

               if ( strlen ( $firstNameFromUser ) <= 1 ) {
                   $errors[ IWizardStep2Fields::FIRST_NAME ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שם פרטי חייב להכיל לפחות 2 אותיות' );
               }

        }

        #endregion


        #region Last name validation

        $lastNameFromUser = isset ( $_POST[ IWizardStep2Fields::LAST_NAME ] ) ?  namesSanitization( $_POST[ IWizardStep2Fields::LAST_NAME ] ) : null  ;

        if ( !isset( $lastNameFromUser ) )
        {
            $errors[ IWizardStep2Fields::LAST_NAME ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }
        else
        {

            if ( strlen ( $lastNameFromUser ) <= 1 ) {
                $errors[ IWizardStep2Fields::LAST_NAME ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שם משפחה חייב להכיל לפחות 2 אותיות' );
            }

        }

        #endregion


        #region Name before marriage

       /* $nameBeforeMarriageFromUser = isset ( $_POST[ IWizardStep2Fields::ADDITIONAL_NAME ] ) ?  namesSanitization( $_POST[ IWizardStep2Fields::ADDITIONAL_NAME ] ) : null  ;

        if ( isset( $nameBeforeMarriageFromUser ) )
        {
            if ( strlen ( $nameBeforeMarriageFromUser ) <= 1 ) {
                $errors[ IWizardStep2Fields::ADDITIONAL_NAME ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שם חייב להכיל לפחות 2 אותיות' );
            }

        }*/

        #endregion


        #region Country

            $countryFromUser =  isset( $_POST[ IWizardStep2Fields::BIRTH_COUNTRY ] ) ? intval( $_POST[ IWizardStep2Fields::BIRTH_COUNTRY ] ) : null;

            if ( !isset( $countryFromUser ) )
            {
                $errors[IWizardStep2Fields::BIRTH_COUNTRY ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
            }
            else
            {

                // Check if this country ID exist in the DB
                if ( !term_exists( $countryFromUser , SCHOOLS_TAXONOMY) )
                {
                    $errors[ IWizardStep2Fields::BIRTH_COUNTRY ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'מדינה שגויה' );
                }
            }


        #endregion


        return ( $errors );
    }

    /**
     * This function will be raise when this is the first time
     * the step visited by the user (no session fields exists for this step).
     */
    protected function onStepFirstLoad(){}

    /**
     * This function will be raise if the step's fields has errors.
     */
    protected function onStepFieldsErrors()
    {
        global $wizardSessionManager;

        // Set the step to the first one
        $wizardSessionManager->setCurrentStep( IWizardStep2Fields::ID );
    }


    protected  function onStepNoErrors() {

        global $wizardSessionManager;


        // If we are in this step all fields are valid, so we will build an array holding them
        $step2Fields = array();

        $userExist = $wizardSessionManager->getField( IWizardStep2Fields::USER_ID );


            // Take the email from step 1
            $email = $wizardSessionManager->getStepData(IWizardStep1Fields::ID);
            $email = $email[IWizardStep1Fields::EMAIL];


            $step2Fields[ IWizardStep2Fields::FIRST_NAME ]       = sanitize( $_POST[ IWizardStep2Fields::FIRST_NAME ] );
            $step2Fields[ IWizardStep2Fields::LAST_NAME ]        = sanitize( $_POST[ IWizardStep2Fields::LAST_NAME ] );
            $step2Fields[ IWizardStep2Fields::ADDITIONAL_NAME ]  = sanitize( $_POST[ IWizardStep2Fields::ADDITIONAL_NAME ]);
            $step2Fields[ IWizardStep2Fields::BIRTHDAY ]         = $_POST[ IWizardStep2Fields::BIRTHDAY ] ;
            $step2Fields[ IWizardStep2Fields::BIRTH_COUNTRY ]    = $_POST[ IWizardStep2Fields::BIRTH_COUNTRY  ];
            $step2Fields[ IWizardStep2Fields::IMMIGRATION_DATE ] = $_POST[ IWizardStep2Fields::IMMIGRATION_DATE  ];
            $step2Fields[ IWizardStep2Fields::BIRTH_CITY ]       = sanitize( $_POST[ IWizardStep2Fields::BIRTH_CITY ] );


                $random_password = wp_generate_password( 15, true , true );


                $wpInsertArgs =  array (
                                            'user_login'	=>	$email,
                                            'user_pass'   	=>	$random_password,
                                            'first_name'	=>	$step2Fields[ IWizardStep2Fields::FIRST_NAME ],
                                            'last_name'	    =>	$step2Fields[ IWizardStep2Fields::LAST_NAME ],
                                            'user_email'	=>	$email,
                                            'display_name'	=>	$step2Fields[ IWizardStep2Fields::FIRST_NAME ] . ' ' . $step2Fields[ IWizardStep2Fields::LAST_NAME ],
                                            'nickname'	    =>	$step2Fields[ IWizardStep2Fields::FIRST_NAME ] . ' ' . $step2Fields[ IWizardStep2Fields::LAST_NAME ],
                                            'role'		    =>	'adult'
                                        );
                // If it's a returning user, just update the fields
                if ( $userExist ) $wpInsertArgs['ID'] = $userExist;

                $new_user  = wp_insert_user ( $wpInsertArgs );

                $step2Fields[ IWizardStep2Fields::USER_ID ] = $new_user;




                logUserIn( $new_user );


                // Update the ACF fields
                $user_id_acf = 'user_' . $new_user;
                update_field( 'field_54b52b42b1d17'  ,  $step2Fields[ IWizardStep2Fields::ADDITIONAL_NAME ]  , $user_id_acf );
                update_field( 'field_54b52b54b1d18'  ,  $step2Fields[ IWizardStep2Fields::BIRTHDAY ]         , $user_id_acf );
                update_field( 'field_54b52d9641e06'  ,  $step2Fields[ IWizardStep2Fields::BIRTH_COUNTRY ]    , $user_id_acf );
                update_field( 'field_55c0a35e509b1'  ,  $step2Fields[ IWizardStep2Fields::BIRTH_CITY ]       , $user_id_acf );
                update_field( 'field_54b5309aae66c'  ,  $step2Fields[ IWizardStep2Fields::IMMIGRATION_DATE ] , $user_id_acf );

                $thisStep[IWizardStep2Fields::USER_LOADED] = IWizardStep2UserStatus::LOADED;




        $step2Data = $wizardSessionManager->getStepData(IWizardStep2Fields::ID);


         if ( isset( $_POST[ IWizardStep2Fields::STORY_LOADED ] ) )
         {
             if ( isset( $step2Data [ IWizardStep2Fields::STORY_LOADED ] ) AND $step2Data [ IWizardStep2Fields::STORY_LOADED ] != $_POST[ IWizardStep2Fields::STORY_LOADED ]  )
             {
                 $step2Fields[ IWizardStep2Fields::STORY_LOADED ] =  $_POST[ IWizardStep2Fields::STORY_LOADED ];
             }
             else
             {
                 $step2Fields[ IWizardStep2Fields::STORY_LOADED ] = $_POST[ IWizardStep2Fields::STORY_LOADED ] ;
             }
        }
        else
        {
            //$step2Fields[ IWizardStep2Fields::STORY_LOADED ] = //$_POST[ IWizardStep2Fields::STORY_LOADED ] ;
        }



        // Save the fields in the session
        $wizardSessionManager->setStepData( IWizardStep2Fields::ID , $step2Fields );


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
            $wizardSessionManager->setStepStatus(IWizardStep2Fields::ID, IWizardSessionFieldsStatus::NO_ERRORS);

            // Set the next step to First_Time
            $wizardSessionManager->setStepStatus(IWizardStep3Fields::ID, IWizardSessionFieldsStatus::FIRST_TIME);

            // Change to the third step
            $wizardSessionManager->setCurrentStep(IWizardStep3Fields::ID);
        }

        header("Refresh:0");

    }
}