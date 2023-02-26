<?php
/**
 *
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

get_header();
?>

<?php 

global $wizardSessionManager;

// Get the current local
//$locale = $wizardSessionManager->getField(IWizardSessionFields::LANGUAGE_LOCALE);
$locale = get_language_locale_filename_by_get_param(true);
$locale =  $locale["locale_file"];

?>


    <script>


        var $ = jQuery

        /* Fields validation */
        $().ready(function () {

                // validate the form's fields
                $("#wizard-form-step1").validate({

                    errorPlacement: function (error, element) {
                                                                 // Append error within linked label
                                                                $(element)
                                                                          .closest("form")
                                                                          .find("label[for='" + element.attr("id") + "']")
                                                                          .append(error)
                                                               },

                                                               errorElement: "span",
                                                               rules: {

                                                                           <?php echo IWizardStep1Fields::SCHOOL_CODE;?>:
                                                                           {
                                                                                required: true,
                                                                           },

                                                                           <?php echo IWizardStep1Fields::EMAIL;?>:
                                                                           {
                                                                                required: true,
                                                                                email:    true
                                                                           },

                                                                           <?php echo IWizardStep1Fields::CITY;?>:
                                                                           {
                                                                                required: true,
                                                                           },

                                                                           <?php echo IWizardStep1Fields::SCHOOL_NAME;?>:
                                                                           {
                                                                                required: true,
                                                                           },

                                                                          'agree':
                                                                          {
                                                                                required: true,
                                                                          },
                                                                     },

                                                                    messages:
                                                                    {

                                                                        <?php echo IWizardStep1Fields::EMAIL;?>:
                                                                        {
                                                                            required: '<?php BH__e("שדה חובה" , "BH" , $locale);?>',
                                                                             email: "יש להקיש כתובת דואר אלקטרוני תקינה"
                                                                        },

                                                                        <?php echo IWizardStep1Fields::SCHOOL_NAME;?>:
                                                                        {
                                                                            required: '<?php BH__e("שדה חובה" , "BH" , $locale);?>',

                                                                        },

                                                                        <?php echo IWizardStep1Fields::SCHOOL_CODE;?>:
                                                                        {
                                                                            required: '<?php BH__e("שדה חובה" , "BH" , $locale);?>',

                                                                        },

                                                                        <?php echo IWizardStep1Fields::SCHOOL_NAME;?>:
                                                                        {
                                                                            required: '<?php BH__e("שדה חובה" , "BH" , $locale);?>',
                                                                        },

                                                                        <?php echo IWizardStep1Fields::CITY;?>:
                                                                        {
                                                                            required: '<?php BH__e("שדה חובה" , "BH" , $locale);?>',
                                                                        },


                                                                        'agree':
                                                                        {
                                                                            required: '<?php BH__e("יש לאשר את תנאי השימוש" , "BH" , $locale);?>',
                                                                        }
                                                                    }
                                                });


        }); //Ready



jQuery(document).ready(function () {


        // Make the country, city and school name select boxes to searchable
        $("#<?php echo IWizardStep1Fields::COUNTRY ?>").chosen( { placeholder_text_single :  '<?php BH__e("בחרו מדינה" , "BH" , $locale);?>', no_results_text:'<?php BH__e("לא נמצאו תוצאות ל - " , "BH" , $locale);?>'  } );
        $("#<?php echo IWizardStep1Fields::CITY ?>").chosen( { placeholder_text_single : '<?php BH__e("בחרו עיר" , "BH" , $locale);?>', no_results_text: '<?php BH__e("לא נמצאו תוצאות ל - " , "BH" , $locale);?>'  } );
        $("#<?php echo IWizardStep1Fields::SCHOOL_NAME ?>").chosen( { placeholder_text_single : '<?php BH__e("בחרו בית ספר" , "BH" , $locale);?>', no_results_text: '<?php BH__e("לא נמצאו תוצאות ל - " , "BH" , $locale);?>'  } );


        // Chain the above select box to make them behave "AJAX like" offline
        $("#<?php echo IWizardStep1Fields::CITY ?>").chained("#<?php echo IWizardStep1Fields::COUNTRY ?>");
        $("#<?php echo IWizardStep1Fields::SCHOOL_NAME ?>").chained("#<?php echo IWizardStep1Fields::CITY ?>" );

        updateChosen();

        // The countries select
        jQuery("#<?php echo IWizardStep1Fields::COUNTRY ?>").change(function () { updateChosen(); }); // Change event

        // The schools select
        jQuery("#<?php echo IWizardStep1Fields::CITY; ?>").on('change', function () { updateChosen(); });


        // The schools select
        jQuery("#<?php echo IWizardStep1Fields::SCHOOL_NAME; ?>").on('change', function () { updateChosen(); });


<?php
        global $wizardSessionManager;

        $step1Data = $wizardSessionManager->getStepData( IWizardStep1Fields::ID );

        if ( isset( $step1Data[IWizardStep1Fields::CITY] ) ): ?>

            jQuery("#<?php echo IWizardStep1Fields::COUNTRY ?>").triggerHandler('change');
            jQuery("#<?php echo IWizardStep1Fields::CITY?>").triggerHandler('change');

        <?php endif; ?>




}); // Document ready



    function updateChosen()
    {
        sortSelectByTermName();
        $("#<?php echo IWizardStep1Fields::COUNTRY ?>").trigger("chosen:updated");
        $("#<?php echo IWizardStep1Fields::CITY ?>").trigger("chosen:updated");
        $("#<?php echo IWizardStep1Fields::SCHOOL_NAME ?>").trigger("chosen:updated");
    }


        function sortSelectByTermName()
        {

            // Loop for each select element on the page.
            $('select').each(function() {
                // Keep track of the selected option.
                var selectedValue = $(this).val();
                // Sort all the options by text.
                $(this).html($('option', $(this)).sort(function(a, b) {
                    return $(a).text() == $(b).text() ? 0 : $(a).text() < $(b).text() ? -1 : 1
                }));
                // Select one option.
                $(this).val(selectedValue);
            });
        }


    </script>

    <?php $errors = isset($data['errors']) ? $data['errors'] : null; ?>
    <section class="page-content">

        <div class="container">
            <div class="row">
                <?php include(WIZARD_VIEWS . '/components/progressbar.php'); //Show the progress bar ?>

                <div class="col-xs-12">

                <form id="wizard-form-step1" class="wizard-form" method="post">
                    <div class="title">
                        <h2><?php echo '1 - ' . $wizard_steps_captions[IWizardStep1Fields::ID - 1]; ?></h2>
                    </div>

                    <!-- Mail -->
                    <div class="element-input">
                        <label for="<?php echo IWizardStep1Fields::EMAIL; ?>" class="title"><?php BH__e("* כתובת מייל:" , "BH", $locale);?>
                            <?php showBackendErrors( $errors , IWizardStep1Fields::EMAIL ); ?>
                        </label>
                        <input class="large" type="text" id="<?php echo IWizardStep1Fields::EMAIL; ?>"
                               placeholder="<?php BH__e("לא נשלח ספאם" , "BH" , $locale);?>"
                               lang="en"
                               name="<?php echo IWizardStep1Fields::EMAIL; ?>"
                                <?php

                                    $step1Data = $wizardSessionManager->getStepData( IWizardStep1Fields::ID );
                                    echo (   ( isset( $step1Data[IWizardStep1Fields::EMAIL] ) ? 'value="' . $step1Data[IWizardStep1Fields::EMAIL] . '"' : '' )  );

                                ?>
                            />
                    </div>

                    <fieldset>
                        <legend><?php BH__e("פרטי בית הספר", "BH" , $locale); ?></legend>
                    <!-- Country -->
                    <div id="country-field" class="element-select" title="<?php BH__e("בחר מדינה" , "BH" , $locale);?>">
                        <label for="<?php echo IWizardStep1Fields::COUNTRY ?>" class="title"><?php BH__e("* מדינה" , "BH" , $locale);?>
                            <?php showBackendErrors( $errors , IWizardStep1Fields::COUNTRY ); ?>
                        </label>

                        <div class="large">
			                <span class="wizard-select-theme">
			                    <select id="<?php echo IWizardStep1Fields::COUNTRY ?>"
                                        name="<?php echo IWizardStep1Fields::COUNTRY ?>" required="required" class="chosen-rtl">
                                    <?php $countries = $data['countries']; ?>
                                    <?php $countryToShow = get_field( 'acf-options-wizard-step1-country' , 'options' ); ?>

                                    <?php

                                    // Default is Israel
                                    $step1Data = $wizardSessionManager->getStepData( IWizardStep1Fields::ID );

                                    if (  isset( $step1Data[ IWizardStep1Fields::COUNTRY ] ) ) {

                                        $countryToShow = $step1Data[ IWizardStep1Fields::COUNTRY ];

                                    }

                                    ?>

                                    <?php foreach ($countries as $country): ?>
                                        <option <?php echo($country->term_id == $countryToShow ? 'selected' : ''); // If israel );?>
                                            value="<?php echo $country->term_id; ?>"
                                            class="<?php echo 'country-' . $country->term_id; ?>">

                                            <?php echo $country->name; ?>

                                        </option>
                                        <br/>
                                    <?php endforeach ?>
                                </select>
			                </span>
                        </div>
                    </div>


                    <!-- City -->
                    <div class="element-select" title="בחר עיר">
                        <label for="<?php echo IWizardStep1Fields::CITY; ?>" class="title"><?php BH__e("* עיר:" , "BH" , $locale);?>
                            <?php showBackendErrors( $errors , IWizardStep1Fields::CITY ); ?>
                        </label>

                        <div class="large">
                            <span class="wizard-select-theme">
                            <select id="<?php echo IWizardStep1Fields::CITY; ?>"
                                    name="<?php echo IWizardStep1Fields::CITY; ?>" required="required" class="chosen-rtl">
                                <option> </option>
                                <?php

                                    //$cities    = $wizardSessionManager->getField('SELECT_CITIES');
                                    $schools   = $wizardSessionManager->getField('SELECT_SCHOOLS');

                                    $step1Data = $wizardSessionManager->getStepData( IWizardStep1Fields::ID );

                                    $selected_city  = $step1Data[ IWizardStep1Fields::CITY ];


                                    foreach ($countries as $country):

                                        $districts = isset( $country->children ) ? $country->children : null;

                                        if( $districts )
                                        {
                                            foreach ( $districts as $district ):
                                                $cities = isset( $district->children ) ? $district->children : null;
                                                if ( $cities )
                                                {
                                                    foreach ( $cities as $city ):
                                                    ?>
                                                        <option <?php echo ( ( $city->term_id == $selected_city ) ? " selected " : "" ); ?>  class="<?php echo $country->term_id . ' '.  $city->term_id; ?>" value="<?php echo $city->term_id; ?>"><?php echo $city->name; ?></option>
                                                    <?php

                                                    endforeach;
                                                }

                                            endforeach;
                                        }

                                    endforeach;
                                ?>

                            </select>

                            </span>
                        </div>
                    </div>


                    <!-- School -->
                    <div class="element-select" title="<?php BH__e("בחר בית ספר" , "BH" , $locale);?>">
                        <label for="<?php echo IWizardStep1Fields::SCHOOL_NAME; ?>" class="title"><?php BH__e("* בית ספר:" , "BH" , $locale);?>
                            <?php showBackendErrors( $errors , IWizardStep1Fields::SCHOOL_NAME ); ?>
                        </label>

                        <div class="large">
                            <span class="wizard-select-theme">
                                <select id="<?php echo IWizardStep1Fields::SCHOOL_NAME; ?>" name="<?php echo IWizardStep1Fields::SCHOOL_NAME; ?>" required="required" style="disabled:true;" class="chosen-rtl">

                                    <option> </option>
                                    <?php

                                    $schools   = $wizardSessionManager->getField('SELECT_SCHOOLS');
                                    $step1Data = $wizardSessionManager->getStepData( IWizardStep1Fields::ID );

                                    $selected_school = null;
                                    if ( isset( $step1Data[IWizardStep1Fields::SCHOOL_NAME] ) )
                                    {
                                        $selected_school = $step1Data[IWizardStep1Fields::SCHOOL_NAME];
                                    }


                                    foreach ($countries as $country):

                                        $districts = isset( $country->children ) ? $country->children : null;

                                        if( $districts )
                                        {
                                            foreach ( $districts as $district ):
                                                $cities = isset( $district->children ) ? $district->children : null;

                                                if ( $cities )
                                                {
                                                    foreach ( $cities as $city ):

                                                        $schools =  isset( $city->children ) ? $city->children : null;

                                                        if ( $schools )
                                                        {
                                                            foreach ( $schools as $school ):
                                                                echo "<option" . ( ( $school->term_id == $selected_school )  ? " selected " : "" ).   " class='$city->term_id' value='$school->term_id'> $school->name </option> <br/>";
                                                            endforeach;
                                                        }
                                                    endforeach;
                                                }

                                            endforeach;
                                        }

                                    endforeach; ?>



                                </select>
                            </span>
                        </div>
                    </div>

                    <!-- School Code -->
                    <div class="element-number" title="<?php BH__e("קוד בית ספר" , "BH" , $locale);?>">
                        <label for="<?php echo IWizardStep1Fields::SCHOOL_CODE; ?>" class="title"><?php BH__e("* קוד בית הספר:" , "BH" , $locale);?>
                            <?php showBackendErrors( $errors , IWizardStep1Fields::SCHOOL_CODE ); ?>
                        </label>

                        <?php

                        $step1Data = $wizardSessionManager->getStepData( IWizardStep1Fields::ID );
                        $schoolCode = "";
                        if ( isset( $step1Data[IWizardStep1Fields::SCHOOL_CODE] ) )
                        {
                            $schoolCode = $step1Data[IWizardStep1Fields::SCHOOL_CODE];
                        }

                        ?>
                        <input  id="<?php echo IWizardStep1Fields::SCHOOL_CODE; ?>" class="small" type="text" min="0" max="99999999" name="<?php echo IWizardStep1Fields::SCHOOL_CODE; ?>" value="<?php echo $schoolCode;?>"/>
                    </div>

                    <!-- Terms -->
                    <div class="element-checkbox" title="<?php BH__e("אישור תנאי שימוש" , "BH" , $locale);?>">

					<?php 
					
					// Get the terms of agreement url page based the on the language

					// Get the current language data
					$lang_code = get_language_locale_filename_by_get_param( true );

					// If Hebrew
					if ( !isset (  $lang_code["get_param_value"]  ) OR $lang_code["get_param_value"] == ISupportedLanguages::HE['get_param_value'] )
					{
						$acf_wizard_terms_url_field_name = "acf-options-wizard-step1-agreement";
					}	
					else {
						$acf_wizard_terms_url_field_name = "acf-options-wizard-step1-agreement-" . $lang_code["get_param_value"];
					}
						
					
					?>
					
                        <div class="column column1 terms-column">
                            <input type="checkbox" id="agree" name="agree"  value="<?php BH__e("אישור תנאי שימוש" , "BH" , $locale);?>" / ><span class="terms-label">
							<?php BH__e("אישור " , "BH" , $locale);?><a href="<?php the_field( $acf_wizard_terms_url_field_name , 'options' ); ?>" target="_blank"><?php BH__e("תנאי שימוש" , "BH" , $locale);?></a>
							</span>
                            <label for="agree" style="    position: absolute; top: 0px; right: 174px;"></label>
                        </div>

                        <span class="clearfix"></span>
                    </div>

                    </fieldset>

                    <div class="submit" style="width: 100%; text-align: left;">
                        <input type="submit" style="margin-left: 20px;" value="<?php BH__e("המשך &#9664;" , "BH" , $locale);?>"/>
                    </div>

                    <input type="hidden" name="step" value="<?php echo IWizardStep2Fields::ID; ?>"/>
                    <?php wp_nonce_field('nonce_login_form_action', 'nonce_login'); ?>


                </form>
                </div>
            </div>
        </div>

    </section>
<?php get_footer(); ?>