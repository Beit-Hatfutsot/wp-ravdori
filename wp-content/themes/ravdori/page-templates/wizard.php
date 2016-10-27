<?php
/**
 * Template Name: Wizard
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once  WIZARD_DIRECTORY  . 'BH_SessionManager.php';

global $stepsArray;
global $wizardSessionManager;


$currentStep = $wizardSessionManager->getCurrentStep();

if ( !isset( $currentStep ) ) {
    $wizardSessionManager->setCurrentStep(IWizardStep1Fields::ID);
}

else
{

    if ( isset( $_POST['progstep'] )  ) {
        $wizardSessionManager->setCurrentStep(  $_POST['progstep']  );
    }

    // If we've finished with the current step
    else if (  $wizardSessionManager->isStepAvailable( $currentStep ) AND $wizardSessionManager->getStepStatus( $currentStep ) == IWizardSessionFieldsStatus::NO_ERRORS ) {
        $x = $_POST['step'] + 1;
        $wizardSessionManager->setCurrentStep( $x );
    }
}


// Load the current step
setStep();

?>