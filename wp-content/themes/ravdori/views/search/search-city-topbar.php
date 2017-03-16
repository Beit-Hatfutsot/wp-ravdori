<?php
/**
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<script>

$ = jQuery;


function removeParameter(url, parameter)
{
  var urlparts= url.split('?');

  if (urlparts.length>=2)
  {
      var urlBase=urlparts.shift(); //get first part, and remove from array
      var queryString=urlparts.join("?"); //join it back up

      var prefix = encodeURIComponent(parameter)+'=';
      var pars = queryString.split(/[&;]/g);
      for (var i= pars.length; i-->0;)               //reverse iteration as may be destructive
          if (pars[i].lastIndexOf(prefix, 0)!==-1)   //idiom for string.startsWith
              pars.splice(i, 1);
      url = urlBase+'?'+pars.join('&');
  }
  return url;
}


var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';


    $(document).ajaxStart(function () {
        $("#city-select").attr("disabled", true).css('cursor', 'not-allowed');
		$("#city-select").trigger("chosen:updated");							
    });


    $(document).ajaxComplete(function () {

        $("#city-select").attr("disabled", false).css('cursor', 'pointer');
		$("#city-select").trigger("chosen:updated");							
        $("#school-select").togglebutton();

    });



$(document).ready(function () {

// Make the city select searchable
$('#city-select').chosen( { placeholder_text_single : "בחרו יישוב" , no_results_text: "לא נמצאו תוצאות ל - "  } );

jQuery('ul.pagination li a').each(function() {
  var value = jQuery(this).attr('href');
  value =  removeParameter(value,'new-school-selected');
  jQuery(this).attr('href', value);
});

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



/***************/
/* Countries  */
/**************/

// Make the country select searchable
$('#country-select').chosen( { placeholder_text_single : "בחרו מדינה" , no_results_text: "לא נמצאו תוצאות ל - "  } );


$('#country-select').live('change', function () {

		// Clean the school field
        $("#school-field").html('');
		
        // Get the selected country
        var countryId = $("#country-select").val();

        jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: {
                                'action': 'search_get_country_cities_ajax',
                                'countryId': countryId
                              },

                        success: function (response)
                        {
                                    $.when( $("#city-select").html(response) ).done(function(){

										$("#city-select").find('option')
														 .remove()
														 .end()
														 .append( response )
														 
														 
										$("#city-select").trigger("chosen:updated");							
                                    });

                                return false;

                        }, // success

                        error: function ( errorThrown ) {}
}); // AJAX


}); // change





	
	

}); // doc.ready



</script>


<?php


$countryTermId = null;

if ( isset ( $_GET['country-select'] ) )
{
    $countryTermId = $_GET['country-select'];
}
else 
{
	// Get Israel term Id from the DB. This field appears in the options page,
	// and belong to the wizard.
	$israelTermId  = get_field( 'acf-options-wizard-step1-country' , 'options' ); 	/* Optimize??? */	
	
	$countryTermId = $israelTermId; 
}



$cityTermId = null;
if ( isset ( $_GET['city-select'] ) )
{
    $cityTermId = $_GET['city-select'];
}
?>

<div class="row">

<form  class="wizard-form" method="get">
	  
	  <!-- Country -->	
	  <div class="col-xs-5">
        <div id="city-field"  title="בחר יישוב">
            <div>
                            לחיפוש שם מדינה יש להקליד
                            תחילה את שם הישוב
            </div>
            <label for="" class="title">שם המדינה:</label>
            <div class="large">
			    <span class="wizard-select-theme">
			        <select id="country-select" name="country-select"  class="custom-select chosen-rtl">
                        <?php echo getCountries( $countryTermId ); ?>
                    </select>
			    </span>
            </div>
        </div>

    </div>
	
	
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
                        <?php echo getCitiesById( $countryTermId , $cityTermId ); ?>
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

/**
* Get all the cities of country and output each city in <option> tag with name and ID
*
* @param $countryId - The DB ID of the country to get the cities from
* @param $cityId - if not NULL, the option with is param will be selected
*/
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
		
        //asort( $all_cities );

        $outputString = "<option value='-1'></option> <br/>";
        foreach ( $all_cities as $city )
        {
            $outputString .= '<option value="' . $city->term_id . '" ' . (( $cityId ==  $city->term_id ) ? ' selected ' : '') . ' >' . $city->name . '</option> <br/>';
        }

    }

    return ( $outputString );
}



/**
* Get all the countries and output each country in <option> tag with name and ID
*
* @param $countryId - if not NULL, the option with is param will be selected
*/
function getCountries( $countryId = null )
{
	$outputString = null;
	
    // Get the top level (All the districts)
    $countries = get_terms(SCHOOLS_TAXONOMY, array(
                                                    'hide_empty' => false ,
                                                    'parent' 	 => 0 ,
													'orderby' 	 => 'name'
                                                  )
                          );

						  
	if ( !empty($countries) && !is_wp_error($countries) ) 
	{
		
        $outputString = "<option value='-1'></option> <br/>";
		
		asort($countries);

        foreach ( $countries as $country )
        {
            $outputString .= '<option value="' . $country->term_id . '" ' . (( $countryId ==  $country->term_id ) ? ' selected ' : '') . ' >' . $country->name . '</option> <br/>';
        }

    }

    return ( $outputString );					  
						  

   
}
?>