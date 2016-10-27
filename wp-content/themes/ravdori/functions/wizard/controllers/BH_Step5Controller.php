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


require_once WIZARD_DIRECTORY   . 'BH_SessionManager.php';


class BH_Step5controller extends BH_Controller{

    public $postID;


    function __construct()
    {
        $this->stepId   = IWizardStep5Fields::ID;
    }


    /**
     * Shows the view of this step
     */
    public function showStepScreen( $errors )
    {
        $data = array();


        // If there are errors add them
        if ( $errors ) {
            $data['errors'] = $errors;
        }

        // Show the page
        $this->view ( 'story_preview.php' , $data );
    }

    /**
     * This function will be raise when the user revisits the step
     */
    protected function onStepRevisit()
    {
        global $wizardSessionManager;


        // Change the step status to validating
        $wizardSessionManager->setStepStatus( IWizardStep5Fields::ID , IWizardSessionFieldsStatus::VALIDATING );


        $wizardSessionManager->setCurrentStep( IWizardStep5Fields::ID );
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

        return ( $errors );
    }

    /**
     * This function will be raise when this is the first time
     * the step visited by the user (no session fields exists for this step).
     */
    protected function onStepFirstLoad() {
        $_SESSION[ IWizardSessionFields::STEPS ][ IWizardStep5Fields::ID ] = 'OK';
    }

    /**
     * This function will be raise if the step's fields has errors.
     */
    protected function onStepFieldsErrors()
    {
        global $wizardSessionManager;

        $wizardSessionManager->setCurrentStep( IWizardStep5Fields::ID );
    }


    protected function onStepNoErrors()
    {

        global $wizardSessionManager;
        $step4Data = $wizardSessionManager->getStepData( IWizardStep4Fields::ID );
        $step1Data = $wizardSessionManager->getStepData( IWizardStep1Fields::ID );


        $post_id   = $step4Data[ IWizardStep4Fields::POST_ID ];


        if ( isset( $step4Data[ IWizardStep4Fields::RAVDORI_FEEDBACK ] ) ) {
            $post_content =  $step4Data[ IWizardStep4Fields::STORY_CONTENT ];
            $post_content .= '<br/><p><h3 class="story-personal-view">';
            $post_content .= __( 'הזוית האישית' , 'BH');
            $post_content .= '</h3></p>';
            $post_content .= '<p>';
            $post_content .= $step4Data[IWizardStep4Fields::RAVDORI_FEEDBACK];
            $post_content .= '</p>';
        }
        // Change the post status to pending
        wp_update_post( array(
                                'ID'            =>  $post_id,
                                'post_status'   =>  'pending',
                                'post_content'  =>  $post_content
                             )
                       );


        // Send mail to the user
        authorPendingPostNotification ( $post_id );
        adminNotification  ( $post_id );

        // Destroy the session
        $wizardSessionManager->destroy();

        // Log off the user
        wp_logout();



        header("Location:" . get_site_url() . "/?p=281");

    }






} 