<?php
/**
 *
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 **/
class BH_Step4Model {

    public function getStep4Info()
    {

        // The returning array which will hold all the data from the DB
        $step4Array  = array ();


        // Get all the subjects
        $taxonomies = get_terms(SUBJECTS_TAXONOMY, array('hide_empty' => false ));

        $subjectsArray = array();

        // Save all the subjects taxonomies names and id in an array
        if ( $taxonomies ) {
            foreach ($taxonomies as $taxonomy) {
				
				// Get the subject's language
				$subjectLanguage = get_field('acf-subjects-language' , 'term_' . $taxonomy->term_id ); 
				
				if ( $subjectLanguage == null ) 
					$subjectLanguage = 'he';
			
				
				// Get the wizard current language
				global $wizardSessionManager;
				$currentLanguage = $wizardSessionManager->getField(IWizardSessionFields::LANGUAGE_LOCALE);	

				if ( $currentLanguage["get_param_value"] == $subjectLanguage ) {
					$subjectsArray[] = array('id' => $taxonomy->term_id, 'name' => $taxonomy->name);
				}
            }
        }


        $step4Array['subjects'] = $subjectsArray;



        // Get all the subtopics
        $taxonomies = get_terms(SUBTOPICS_TAXONOMY, array('hide_empty' => false ));

        $subtopicsArray = array();

        // Save all the subtopics taxonomies names and id in an array
        if ( $taxonomies ) {
            foreach ($taxonomies as $taxonomy) {
				
				// Get the subject's language
				$subjectLanguage = get_field('acf-subjects-language' , 'term_' . $taxonomy->term_id ); 
				
				if ( $subjectLanguage == null ) 
					$subjectLanguage = 'he';
			
				
				// Get the wizard current language
				global $wizardSessionManager;
				$currentLanguage = $wizardSessionManager->getField(IWizardSessionFields::LANGUAGE_LOCALE);	

				if ( $currentLanguage["get_param_value"] == $subjectLanguage ) {
					$subtopicsArray[] = array('id' => $taxonomy->term_id, 'name' => $taxonomy->name);
				}
            }
        }


        // Save them to the returned array
        $step4Array['subtopics'] = $subtopicsArray;




        // Get all the languages
        $taxonomies = get_terms(LANGUAGES_TAXONOMY, array('hide_empty' => false ));

        $languagesArray = array();

        // Save all the languages taxonomies names and id in an array
        if ( $taxonomies ) {
            foreach ($taxonomies as $taxonomy) {
                $languagesArray[] = array('id' => $taxonomy->term_id, 'name' => $taxonomy->name);
            }
        }


        // Save them to the returned array
        $step4Array['languages'] = $languagesArray;



        return ( $step4Array );

    }


} // EOC
