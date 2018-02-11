<?php
/**
 * All wizard related constants
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */



// The wizard's MVC paths
define( 'WIZARD_DIRECTORY'   , $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/ravdori/functions/wizard/' );

define( 'WIZARD_CONTROLLERS' , WIZARD_DIRECTORY  . 'controllers/' );
define( 'WIZARD_MODELS'      , WIZARD_DIRECTORY  . 'models/' );
define( 'WIZARD_VIEWS'       , $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/ravdori/views/wizard/' );


// How many steps does the wizard has
define( 'WIZARD_NUM_OF_STEPS' , 5 );


// The wizard's steps titles -> will be used as the page's title
// and in the step indicator.
// The order in this array should be by the order of the steps


$wizard_steps_captions = array (
                                    __( 'מסך כניסה' , 'BH' ) ,
                                    __( 'קליטת פרטי משתמש' , 'BH' ) ,
                                    __( 'קליטת פרטי מתעד' , 'BH' ),
                                    __( 'קליטת פרטי סיפור' , 'BH' ),
                                    __( 'תצוגה מקדימה ואישור' , 'BH' ),
                                );



$wizard_steps_captions = array ();
$wizard_steps_colors   = array ();


// Get the wizards title based the on the language

// Get the current language data
$lang_code = get_language_locale_filename_by_get_param( true );

// If Hebrew
if ( !isset (  $lang_code["get_param_value"]  ) OR $lang_code["get_param_value"] == ISupportedLanguages::HE['get_param_value'] )
{
	$acf_wizard_title_field_name = "acf-options-wizard-title";
}	
else {
	$acf_wizard_title_field_name = "acf-options-wizard-title-" . $lang_code["get_param_value"];
}
	


if( have_rows('acf-options-wizard-steps','options') ):


    while( have_rows('acf-options-wizard-steps','options') ): the_row();
        $title = get_sub_field($acf_wizard_title_field_name,'options');
        $color = get_sub_field('acf-options-wizard-color','options');

        $wizard_steps_captions[] =  $title;
        $wizard_steps_colors[]   = $color;
    endwhile;

endif;



// The following interfaces are used as an ENUM.
// Each interface has an ID for the step, and
// all the fields that will be in this state.

interface IWizardStep1Fields  {

    const ID           = 1;
    const COUNTRY      = 'STEP1_COUNTRY';
    const CITY         = 'STEP1_CITY';
    const EMAIL        = 'STEP1_EMAIL';
    const SCHOOL_NAME  = 'STEP1_SCHOOL_NAME';
    const SCHOOL_CODE  = 'STEP1_SCHOOL_CODE';

}


interface IWizardStep2Fields  {

    const ID                = 2;
    const FIRST_NAME        = 'STEP2_FIRST_NAME';
    const LAST_NAME         = 'STEP2_LAST_NAME';
    const ADDITIONAL_NAME   = 'STEP2_ADDITIONAL_NAME';
    const BIRTHDAY          = 'STEP2_BIRTHDAY';
    const BIRTH_COUNTRY     = 'STEP2_BIRTH_COUNTRY';
    const BIRTH_CITY        = 'STEP2_BIRTH_CITY';
    const IMMIGRATION_DATE  = 'STEP2_IMMIGRATION_DATE';
    const USER_ID           = 'USER_ID';
    const USER_LOADED       = 'USER_LOADED';
    const STORY_LOADED      = 'STORY_LOADED';
}


interface IWizardStep3Fields  {

    const ID            = 3;
    const FIRST_NAME    = 'STEP4_FIRST_NAME';
    const LAST_NAME     = 'STEP4_LAST_NAME';
    const GRADE         = 'STEP4_GRADE';
    const TEACHER_NAME  = 'STEP4_TEACHER_NAME';
}


interface IWizardStep4Fields {

    const ID                  = 4;
    const STORY_TITLE         = 'STORY_TITLE';
    const STORY_SUBTITLE      = 'STORY_SUBTITLE';
    const STORY_CONTENT       = 'STORY_CONTENT';
    const IMAGE_ADULT         = 'IMAGE_ADULT';
    const IMAGE_ADULT_DESC    = 'IMAGE_ADULT_DESC';
    const IMAGE_ADULT_STUDENT_DESC = 'IMAGE_ADULT_STUDENT_DESC';
    const IMAGE_ADULT_STUDENT = 'IMAGE_ADULT_STUDENT';
    const DICTIONARY          = 'DICTIONARY_TERMS';
    const QUOTES              = 'QUOTES_TERMS';
    const STORY_SUBJECTS      = 'STORY_SUBJECTS';
    const STORY_LANGUAGE      = 'STORY_LANGUAGE';
    const STORY_SUBTOPICS     = 'STORY_SUBTOPICS';
    const RAVDORI_FEEDBACK    = 'RAVDORI_FEEDBACK';
    const POST_ID             = 'POST_ID';

}

interface IWizardStep5Fields {

    const ID = 5;

}



interface IWizardErrorsFields {

    const MESSAGE  = 'MESSAGE';
    const IS_VALID = 'IS_VALID';
}

interface IWizardStep2UserStatus
{
    const LOADED     = 'LOADED';
    const NOT_LOADED = 'NOT_LOADED';
}