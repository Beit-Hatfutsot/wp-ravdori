<?php
/**
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<script>

$ = jQuery;

var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';


    $(document).ajaxStart(function () {
        $("#city-select").attr("disabled", true).css('cursor', 'not-allowed');
       // $('#ajax-loader').show()
      //  $('#<?php echo IWizardStep1Fields::SCHOOL_NAME;?>').attr("disabled", true).css('cursor', 'not-allowed');
    });


    $(document).ajaxComplete(function () {

        $("#city-select").attr("disabled", false).css('cursor', 'pointer');
      //  $('#ajax-loader').hide()
     //   jQuery('#<?php echo IWizardStep1Fields::SCHOOL_NAME;?>').attr("disabled", false).css('cursor', 'pointer');
        //$("#school-select").chosen( { placeholder_text_single : "בחרו בית ספר" , no_results_text: "לא נמצאו תוצאות ל - "  } );

        $("#school-select").togglebutton();

    });



$(document).ready(function () {


// Make the city select searchable
$('#city-select').chosen( { placeholder_text_single : "בחרו יישוב" , no_results_text: "לא נמצאו תוצאות ל - "  } );



$('#city-select').live('change', function () {

        // Clean the school field
        $("#school-field").html('');

        // Get the selected city
        var cityID = $("#city-select").val();

        jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: {
                                'action': 'search_get_schools_ajax',
                                'cityid': cityID
                              },

                        success: function (response)
                        {
                                    $.when( $("#school-field").html(response) ).done(function(){


                                        <?php if ( isset( $_GET['school-select'] ) ):  ?>

                                                var schoolId = <?php echo  !empty ( $_GET['school-select'] ) ? $_GET['school-select'] : '-1'; ?>;

                                                // If the user return id exists in the list
                                              //  if ( $("#school-field option[value=' + schoolId + ']").length > 0 )  alert(schoolId);
                                               // {

                                                    $("#school-field option[value='" + schoolId + "']").prop('selected', true);

                                                    //$("#school-field").trigger("chosen:updated");
                                               // }

                                        <?php endif;?>



                                    });

                                return false;

                        }, // success

                        error: function ( errorThrown ) {}
}); // AJAX


}); // change



    <?php if ( isset( $_GET['city-select'] ) ): ?>
    $("#city-select").trigger('change').trigger('click');
    <?php endif; ?>

	
	
	$("#school-select").live('change', function() {
		$('.wizard-form input[type=submit]').click();
	});

}); // doc.ready



</script>


<?php

// Get from the db
$israelTermId = 3640;

$cityTermId = null;
if ( isset ( $_GET['city-select'] ) )
{
    $cityTermId = $_GET['city-select'];
}
?>

<div class="row">

<form  class="wizard-form" method="get">
        <!-- City -->
    <div class="col-xs-5">
        <div id="city-field"  title="בחר יישוב">
            <div>
                            לחיפוש שם בי"ס יש להקליד
                            תחילה את שם הישוב
            </div>
            <label for="" class="title">שם היישוב:</label>
            <div class="large">
			    <span class="wizard-select-theme">
			        <select id="city-select" name="city-select"  class="custom-select chosen-rtl">
                        <?php echo getCitiesById( $israelTermId , $cityTermId ); ?>
                    </select>
			    </span>
            </div>
        </div>

    </div>

    <div id="ajax-loader" class="text-center" style="display: none;  background: #F1F1F3;margin-right: 0;width:100%">
        <img src="<?php echo IMAGES_DIR . '/general/' . 'loading.gif'; ?>" width="126" height="52" />
        <div>
            <?php _e( 'טוען...' , 'BH'); ?>
        </div>
    </div>

    <div id="school-field" class="col-xs-5">

    </div>

<div>

<div class="col-xs-12">
    <input type="submit" value="חפש" style="margin-right: 0;display:none"></div>
</div>

</form>

</div>

<?php

function getCitiesById( $countryId , $cityId = null )
{

    // Get the top level (All the districts)
    $districts = get_terms(SCHOOLS_TAXONOMY, array(
                                                    'hide_empty' => true,
                                                    'parent' => $countryId
                                                  )
                          );


    $all_cities = array();
    $outputString = null;

    if ( !empty( $districts ) && !is_wp_error( $districts ) )
    {
        foreach ( $districts as $district )
        {
            // Get all the cities under the district $district
            $cities = get_terms(SCHOOLS_TAXONOMY, array(
                                                            'parent' => $district->term_id,
                                                            'hide_empty' => true,
															'orderby' => 'name'
                                                       )
                               );

            if ( !empty( $cities ) && !is_wp_error( $cities ) )
            {
                $all_cities = array_merge($all_cities, $cities);
            }
        }
    }

    if (!empty($all_cities) && !is_wp_error($all_cities)) {
        asort($all_cities);

        $outputString = "<option value='-1'></option> <br/>";
        foreach ( $all_cities as $city )
        {
            $outputString .= '<option value="' . $city->term_id . '" ' . (( $cityId ==  $city->term_id ) ? ' selected ' : '') . ' >' . $city->name . '</option> <br/>';
        }

    }

    return ( $outputString );
}

?>