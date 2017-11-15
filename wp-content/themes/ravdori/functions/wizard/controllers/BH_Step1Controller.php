<?php
/**
 * This file contains the BH_Step1Controller class.
 *
 * This class represents the First step of the Wizard
 *
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


require_once 'BH_Controller.php';

require_once WIZARD_MODELS      . 'BH_LoginModel.php';

require_once WIZARD_DIRECTORY   . 'BH_SessionManager.php';

/**
 * The controller for the first screen.
 * Shows the login details
 * */
class BH_Step1Controller extends BH_Controller {

    /**
     * Property for the controller's  model
     * */
    public $loginModel;

    /**
     * */
    function __construct()
    {
        parent::__construct();

        // Init the model and set the step ID to 1
        $this->loginModel = new BH_LoginModel();
        $this->stepId     = IWizardStep1Fields::ID;

     }


    /**
     * Shows the view of this step
     */
    public function showStepScreen( $errors )
    {
        // Get all the data from the db
        $data = $this->loginModel->getLoginInfo();

        // If there are errors add them
        if ( $errors ) {
            $data['errors'] = $errors;
        }

        // Show the page
        $this->view ( 'login.php' , $data );
    }

    /**
     * This function will be raise when the user revisits the step
     */
    protected function onStepRevisit()
    {
        global $wizardSessionManager;

        // Set the step to the first one

        // Change the step status to validating
        $wizardSessionManager->setStepStatus( IWizardStep1Fields::ID , IWizardSessionFieldsStatus::VALIDATING );

        // Set the current step as the first
        $wizardSessionManager->setCurrentStep( IWizardStep1Fields::ID );

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

        #region Email validation

            $email_from_user =  isset ( $_POST[ IWizardStep1Fields::EMAIL ] ) ? $_POST[ IWizardStep1Fields::EMAIL ] : null;

            if ( !isset( $email_from_user ) )
            {
                $errors[ IWizardStep1Fields::EMAIL ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
            }
            else
            {

                if ( !is_email( $email_from_user ) ) {
                    $errors[IWizardStep1Fields::EMAIL] = array(IWizardErrorsFields::IS_VALID => false, IWizardErrorsFields::MESSAGE => 'כתובת שגויה');
                }
            }

        #endregion


        #region Country validation

            $countryFromUser =  isset( $_POST[ IWizardStep1Fields::COUNTRY ] ) ? intval( $_POST[ IWizardStep1Fields::COUNTRY ] ) : null;

            if ( !isset( $countryFromUser ) )
            {
                $errors[ IWizardStep1Fields::COUNTRY ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
            }
            else
            {

                // Check if this country ID exist in the DB
                if ( !term_exists( $countryFromUser , SCHOOLS_TAXONOMY) )
                {
                    $errors[ IWizardStep1Fields::COUNTRY ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'מדינה שגויה' );
                }
            }

        #end region


        #region City validation

            $cityFromUser =  isset( $_POST[ IWizardStep1Fields::CITY ] ) ? intval ( $_POST[ IWizardStep1Fields::CITY ] ) : null;


            if ( !isset( $cityFromUser ) )
            {
                $errors[ IWizardStep1Fields::CITY ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
            }
            else
            {

                // Check if this city ID exist in the DB
                if ( !term_exists( $cityFromUser , SCHOOLS_TAXONOMY) )
                {
                    $errors[ IWizardStep1Fields::CITY ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'עיר שגויה'  );
                }
            }

        #end region


        #region School Name validation

        $schoolNameFromUser =  isset( $_POST[ IWizardStep1Fields::SCHOOL_NAME ] ) ? intval ( $_POST[ IWizardStep1Fields::SCHOOL_NAME ] ) : null;


        if ( !isset( $schoolNameFromUser ) )
        {
            $errors[ IWizardStep1Fields::SCHOOL_NAME ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }
        else
        {

            // Check if this school ID exist in the DB
            if ( !term_exists( $schoolNameFromUser , SCHOOLS_TAXONOMY) )
            {
                $errors[ IWizardStep1Fields::SCHOOL_NAME ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'בית ספר שגוי' );
            }
        }


        #endregion


        $schoolId            =  isset( $_POST[IWizardStep1Fields::SCHOOL_NAME]  ) ? intval ( $_POST[IWizardStep1Fields::SCHOOL_NAME] ) : null;
        $schoolCodeFromUser  =  isset( $_POST[IWizardStep1Fields::SCHOOL_CODE] ) ? ( $_POST[IWizardStep1Fields::SCHOOL_CODE] ) : null;


        $schoolCodeFromDB =  get_field('acf-school-code',SCHOOLS_TAXONOMY . '_' . $schoolId );


        if ( !isset( $schoolCodeFromUser ) )
        {
            $errors[ IWizardStep1Fields::SCHOOL_CODE ] = array( IWizardErrorsFields::IS_VALID => false , IWizardErrorsFields::MESSAGE => 'שדה חובה' );
        }
        else
        {
            if ($schoolCodeFromUser != $schoolCodeFromDB) {
                $errors[IWizardStep1Fields::SCHOOL_CODE] = array(IWizardErrorsFields::IS_VALID => false, IWizardErrorsFields::MESSAGE => 'קוד שגוי');
            }
        }

        return ( $errors );
    }

    /**
     * This function will be raise when this is the first time
     */
    protected function onStepFirstLoad() {
		
		global $wizardSessionManager;
		
		// Set the locale file by the lang GET param
		$wizardSessionManager->setField( IWizardSessionFields::LANGUAGE_LOCALE , get_language_locale_filename_by_get_param( true ) );
		
	}


    /**
     * This function will be raise if the step's fields has errors.
     */
    protected function onStepFieldsErrors()
    {
        global $wizardSessionManager;

        $loginFields = array();

        $loginFields[ IWizardStep1Fields::EMAIL ]        = $_POST[ IWizardStep1Fields::EMAIL ];
        $loginFields[ IWizardStep1Fields::COUNTRY ]      = $_POST[ IWizardStep1Fields::COUNTRY ];
        $loginFields[ IWizardStep1Fields::CITY ]         = $_POST[ IWizardStep1Fields::CITY ];
        $loginFields[ IWizardStep1Fields::SCHOOL_NAME ]  = $_POST[ IWizardStep1Fields::SCHOOL_NAME ];

        $schools['SELECTED_SCHOOL'] = $_POST[IWizardStep1Fields::SCHOOL_NAME];

        // Save the fields in the session
        $wizardSessionManager->setStepData( IWizardStep1Fields::ID , $loginFields );


        // Set the step to the first one
        $wizardSessionManager->setCurrentStep( IWizardStep1Fields::ID );
    }


    /* If no errors were found*/
    protected  function onStepNoErrors() {

       global $wizardSessionManager;


        // If we are in this step all fields are valid, so we will build an array holding them
        $loginFields = array();

        $loginFields[ IWizardStep1Fields::EMAIL ]        = $_POST[ IWizardStep1Fields::EMAIL ];
        $loginFields[ IWizardStep1Fields::COUNTRY ]      = $_POST[ IWizardStep1Fields::COUNTRY ];
        $loginFields[ IWizardStep1Fields::CITY ]         = $_POST[ IWizardStep1Fields::CITY ];
        $loginFields[ IWizardStep1Fields::SCHOOL_NAME ]  = $_POST[ IWizardStep1Fields::SCHOOL_NAME ];
        $loginFields[ IWizardStep1Fields::SCHOOL_CODE ]  = $_POST[ IWizardStep1Fields::SCHOOL_CODE ];

        $schools['SELECTED_SCHOOL'] = $_POST[IWizardStep1Fields::SCHOOL_NAME];

        // Save the fields in the session
        $wizardSessionManager->setStepData( IWizardStep1Fields::ID , $loginFields );

        // Set the status to NO_ERRORS
        $wizardSessionManager->setStepStatus( IWizardStep1Fields::ID , IWizardSessionFieldsStatus::NO_ERRORS );


        // Set the next step to First_Time
        $wizardSessionManager->setStepStatus(IWizardStep2Fields::ID, IWizardSessionFieldsStatus::FIRST_TIME);

        // Change to the second step
        $wizardSessionManager->setCurrentStep(IWizardStep2Fields::ID);


        header("Refresh:0");

    }


} // EOC