<?php
/**
 * This file contains the definition of the BH_Controller - the base controller class.
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The base class for all the controllers
 * Save the necessary data:
 *                          1) $model - holds the model to talk with
 *                          2) $step  - holds the current step
 **/
abstract class BH_Controller {

    /**
     * @var The model of this step's controller
     */
    protected  $model;
    /**
     * @var The step's ID
     */
    protected  $stepId;

    protected $errors;



    function __construct() {}


    /**
     * Load's the view based on the file name and enabling it access to the model's
     * data via the variable $data
     *
     * @param String $fileName - The name of the php file containing the view to show (Should be under /view/wizard/)
     * @param Array $data      - Array of data from the model
     */
    function view( $fileName , $data = null ) {

        if( is_array( $data ) ) {

            // Gives the view access to the variable (bring to it's scope), by loading
            // it into the local symbol table
            extract( $data );
        }

        // Show the page
        include ( WIZARD_VIEWS . $fileName );
    }


    /**
     *
     * @return mixed
     */
    function initStep()
    {
        global $wizardSessionManager;


        // If we are coming back from previous page, just show the fields
        if ( $wizardSessionManager->isStepAvailable( $this->stepId ) and $wizardSessionManager->getStepStatus( $wizardSessionManager->getCurrentStep() ) == IWizardSessionFieldsStatus::NO_ERRORS  )
        {
               // Put every step into NO_ERRORS mode
               for ( $stepsCounter = 1; $stepsCounter <= WIZARD_NUM_OF_STEPS; $stepsCounter++ )
               {
                       if (  $wizardSessionManager->isStepAvailable( $stepsCounter ) )
                       {
                           $wizardSessionManager->setStepStatus( $stepsCounter , IWizardSessionFieldsStatus::NO_ERRORS );
                       }
               }

              $this->onStepRevisit();
              $this->showStepScreen(null);
              return;
        }


        // If we have info from the end user
        if( $_SERVER['REQUEST_METHOD'] == 'POST' AND !empty( $_POST ) and $wizardSessionManager->getStepStatus( $wizardSessionManager->getCurrentStep() ) == IWizardSessionFieldsStatus::VALIDATING ) {

           $this->errors = $this->onStepFieldsValidation();

            // Check if the fields are valid
            if ( count ( $this->errors ) == 0 )
            {
                $this->onStepNoErrors();
            }
            else
            {
                // Errors exist
                $this->onStepFieldsErrors();
                $this->showStepScreen( $this->errors );
            }
        }
        else
        {
            // First time the page loaded
            $this->onStepFirstLoad();
            $wizardSessionManager->setStepStatus( $wizardSessionManager->getCurrentStep() , IWizardSessionFieldsStatus::VALIDATING );

            $this->showStepScreen(null);
        }

        return ( $this->errors );

    }


    public function executeStep() {

        global $wizardSessionManager;

        $stepStatus = $wizardSessionManager->getStepStatus( $wizardSessionManager->getCurrentStep() );

        if ( $stepStatus == null)
        {
            $wizardSessionManager->setStepStatus($wizardSessionManager->getCurrentStep(), IWizardSessionFieldsStatus::FIRST_TIME);
        }

        $this->errors = null;
        $this->initStep();
    }



    /**
     * Shows the view of this step
     */
    public abstract function showStepScreen( $errors );

    /**
     * This function will be raise when the user revisits the step
     */
    protected abstract function onStepRevisit();

    /**
     * Checks the validity of the step's fields.
     *
     * This function will be raise after the user clicked the
     * form's submit button.
     */
    protected abstract function onStepFieldsValidation();


    /**
     * This function will be raise when this is the first time
     * the step visited by the user (no session fields exists for this step).
     */
    protected abstract function onStepFirstLoad();


    /**
     * This function will be raise if the step's fields has errors.
     */
    protected abstract function onStepFieldsErrors();

    /**
     * This function will be raise if the step's fields checked to be
     * OK, and no errors were found
     */
    protected abstract function onStepNoErrors();

} // EOC