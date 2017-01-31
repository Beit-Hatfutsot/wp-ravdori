<?php $allTopics = getSubjectsAndSubtopics(); ?>

<div class="row">

    <form id="search-subjects-form" class="wizard-form" method="get">

        <div class="col-xs-12">
            <h4>נושאים:</h4>
            <div class="voffset3"></div>

            <?php

            // Get all the subjects from the DB
            $subjects = $allTopics['subjects'];

            $selectedSubjects = null;

            if ( isset( $_GET['subjects'] ) AND is_array( $_GET['subjects'] ) )
            {
                $selectedSubjects = $_GET['subjects'];

                if ( !isArrayContainsOnlyNumbers( $selectedSubjects ) )
                    $selectedSubjects = null;
            }

            set_query_var( 'subjectsArray' , $selectedSubjects );

            if ( isset( $selectedSubjects ) )
            {
                foreach ($subjects as $subject)
                {
                    echo '<div class="col-xs-3">';
                    echo '<label>';
                        echo '<input type="checkbox"' . (( in_array( $subject['id'] , $selectedSubjects )) ? ' checked ' : '') . 'id="' . $subject['id'] . '" name="subjects[]"  value="' . $subject['id'] . '"/ > <span> </span>';
                        echo  '<div class="checkbox-label">' . $subject['name'] .  '</div>' ;
                    echo '</label>';
                    echo '</div>';
                }
            }
            else
            {
                foreach ($subjects as $subject)
                {
                    echo '<div class="col-xs-3">';
                        echo '<label>';
                            echo '<input type="checkbox"' . ' id="' . $subject['id'] . '" name="subjects[]"  value="' . $subject['id'] . '"/ > <span> </span>';
                            echo  '<div class="checkbox-label">' . $subject['name'] .  '</div>' ;
                        echo '</label>';
                    echo '</div>';
                }
            }

            ?>

        </div>


        <div class="col-xs-12">
            <h4>פרויקטים ושיתופי פעולה:</h4>
            <div class="voffset3"></div>

            <?php

            // Get all the subtopics from the DB
            $subtopics = $allTopics['subtopics'];

            $selectedSubjects = null;

            if ( isset( $_GET['subtopics'] ) AND is_array( $_GET['subtopics'] ) )
            {
                $selectedSubjects = $_GET['subtopics'];

                if ( !isArrayContainsOnlyNumbers( $selectedSubjects ) )
                    $selectedSubjects = null;
            }

            set_query_var( 'subtopicsArray' , $selectedSubjects );

            if ( isset( $selectedSubjects ) )
            {
                foreach ($subtopics as $subject)
                {
                    echo '<div class="col-xs-3">';
                        echo '<label>';
                            echo '<input type="checkbox"' . (( in_array( $subject['id'] , $selectedSubjects )) ? ' checked ' : '') . 'id="' . $subject['id'] . '" name="subtopics[]"  value="' . $subject['id'] . '"/ > <span> </span>';
                            echo  '<div class="checkbox-label">' . $subject['name'] .  '</div>' ;
                        echo '</label>';
                    echo '</div>';
                }
            }
            else
            {
                foreach ($subtopics as $subject)
                {
                    echo '<div class="col-xs-3">';
                        echo '<label>';
                        echo  '<input type="checkbox"' . ' id="' . $subject['id'] . '" name="subtopics[]"  value="' . $subject['id'] . '"/ > <span> </span>';
                        echo  '<div class="checkbox-label">' . $subject['name'] .  '</div>' ;
                        echo '</label>';
                    echo '</div>';
                }
            }

            ?>


        </div>
		
		
		<div class="col-xs-12">
            <h4>שפות:</h4>
            <div class="voffset3"></div>

            <?php

            // Get all the languages from the DB
            $languages = $allTopics['languages'];


            $selectedSubjects = null;

            if ( isset( $_GET['languages'] ) AND is_array( $_GET['languages'] ) )
            {
                $selectedSubjects = $_GET['languages'];

                if ( !isArrayContainsOnlyNumbers( $selectedSubjects ) )
                    $selectedSubjects = null;
            }

            set_query_var( 'languagesArray' , $selectedSubjects );

            if ( isset( $selectedSubjects ) )
            {
                foreach ($languages as $subject)
                {
                    echo '<div class="col-xs-3">';
                        echo '<label>';
                            echo '<input type="checkbox"' . (( in_array( $subject['id'] , $selectedSubjects )) ? ' checked ' : '') . 'id="' . $subject['id'] . '" name="languages[]"  value="' . $subject['id'] . '"/ > <span> </span>';
                            echo  '<div class="checkbox-label">' . $subject['name'] .  '</div>' ;
                        echo '</label>';
                    echo '</div>';
                }
            }
            else
            {
                foreach ($languages as $subject)
                {
                    echo '<div class="col-xs-3">';
                        echo '<label>';
                        echo  '<input type="checkbox"' . ' id="' . $subject['id'] . '" name="languages[]"  value="' . $subject['id'] . '"/ > <span> </span>';
                        echo  '<div class="checkbox-label">' . $subject['name'] .  '</div>' ;
                        echo '</label>';
                    echo '</div>';
                }
            }

            ?>


        </div>

        <div class="col-xs-12">
                <input type="submit" name="submit" value="חפש" style="margin-right: 0;">
        </div>

    </form>

</div>


<?php

function getSubjectsAndSubtopics()
{

    $retArray  = array();


	// Get all the subjects
    $taxonomies = get_terms(SUBJECTS_TAXONOMY, array('hide_empty' => false));

    $subjectsArray = array();

	// Save all the taxonomies names and id in an array
    if ($taxonomies) {
        foreach ($taxonomies as $taxonomy) {
            $subjectsArray[] = array('id' => $taxonomy->term_id, 'name' => $taxonomy->name);
        }
    }


    $retArray['subjects'] = $subjectsArray;


	
	
	// Get all the subtopics
    $taxonomies = get_terms(SUBTOPICS_TAXONOMY, array('hide_empty' => false));

    $subtopicsArray = array();

	// Save all the taxonomies names and id in an array
    if ($taxonomies) {
        foreach ($taxonomies as $taxonomy) {
            $subtopicsArray[] = array('id' => $taxonomy->term_id, 'name' => $taxonomy->name);
        }
    }


	// Save them to the returned array
    $retArray['subtopics'] = $subtopicsArray;

	
	
	// Get all the languages
    $taxonomies = get_terms(LANGUAGES_TAXONOMY, array('hide_empty' => false));

    $subtopicsArray = array();

	// Save all the taxonomies names and id in an array
    if ($taxonomies) {
        foreach ($taxonomies as $taxonomy) {
            $subtopicsArray[] = array('id' => $taxonomy->term_id, 'name' => $taxonomy->name);
        }
    }


	// Save them to the returned array
    $retArray['languages'] = $subtopicsArray;
	
	

    return ( $retArray );

}



function isArrayContainsOnlyNumbers( $array )
{
    $noError = true;

    // Check if all the elements in the array are only numbers
    foreach ( $array as $element )
    {
        if( !ctype_digit( $element ) )
        {
            $noError = false;
            break;
        }

    }

    return ( $noError );

}

?>