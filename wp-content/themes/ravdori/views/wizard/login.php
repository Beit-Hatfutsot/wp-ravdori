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
                                                                            required: "שדה חובה",
                                                                             email: "יש להקיש כתובת דואר אלקטרוני תקינה"
                                                                        },

                                                                        <?php echo IWizardStep1Fields::SCHOOL_NAME;?>:
                                                                        {
                                                                            required: "שדה חובה",

                                                                        },

                                                                        <?php echo IWizardStep1Fields::SCHOOL_CODE;?>:
                                                                        {
                                                                            required: "שדה חובה",

                                                                        },

                                                                        <?php echo IWizardStep1Fields::SCHOOL_NAME;?>:
                                                                        {
                                                                            required: "שדה חובה",
                                                                        },

                                                                        <?php echo IWizardStep1Fields::CITY;?>:
                                                                        {
                                                                            required: "שדה חובה",
                                                                        },


                                                                        'agree':
                                                                        {
                                                                            required: "יש לאשר את תנאי השימוש",
                                                                        }
                                                                    }
                                                });


        }); //Ready



jQuery(document).ready(function () {


        // Make the country, city and school name select boxes to searchable
        $("#<?php echo IWizardStep1Fields::COUNTRY ?>").chosen( { placeholder_text_single : "בחרו מדינה", no_results_text: "לא נמצאו תוצאות ל - "  } );
        $("#<?php echo IWizardStep1Fields::CITY ?>").chosen( { placeholder_text_single : "בחרו עיר", no_results_text: "לא נמצאו תוצאות ל - "  } );
        $("#<?php echo IWizardStep1Fields::SCHOOL_NAME ?>").chosen( { placeholder_text_single : "בחרו בית ספר", no_results_text: "לא נמצאו תוצאות ל - "  } );


        // Chain the above select box to make them behave "AJAX like" offline
        $("#<?php echo IWizardStep1Fields::CITY ?>").chained("#<?php echo IWizardStep1Fields::COUNTRY ?>");
        $("#<?php echo IWizardStep1Fields::SCHOOL_NAME ?>").chained("#<?php echo IWizardStep1Fields::CITY ?>" );

        updateChosen();

        // The countries select
        jQuery("#<?php echo IWizardStep1Fields::COUNTRY ?>").change(function () { updateChosen(); }); // Change event

        // The schools select
        jQuery("#<?php echo IWizardStep1Fields::CITY; ?>").live('change', function () { updateChosen(); });


        // The schools select
        jQuery("#<?php echo IWizardStep1Fields::SCHOOL_NAME; ?>").live('change', function () { updateChosen(); });


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
                        <label for="<?php echo IWizardStep1Fields::EMAIL; ?>" class="title">* כתובת מייל:
                            <?php showBackendErrors( $errors , IWizardStep1Fields::EMAIL ); ?>
                        </label>
                        <input class="large" type="text" id="<?php echo IWizardStep1Fields::EMAIL; ?>"
                               placeholder="לא נשלח ספאם"
                               lang="en"
                               name="<?php echo IWizardStep1Fields::EMAIL; ?>"
                                <?php

                                    $step1Data = $wizardSessionManager->getStepData( IWizardStep1Fields::ID );
                                    echo (   ( isset( $step1Data[IWizardStep1Fields::EMAIL] ) ? 'value="' . $step1Data[IWizardStep1Fields::EMAIL] . '"' : '' )  );

                                ?>
                            />
                    </div>

                    <fieldset>
                        <legend>פרטי בית הספר</legend>
                    <!-- Country -->
                    <div id="country-field" class="element-select" title="בחר מדינה">
                        <label for="<?php echo IWizardStep1Fields::COUNTRY ?>" class="title">* מדינה
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
                        <label for="<?php echo IWizardStep1Fields::CITY; ?>" class="title">* עיר:
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
                    <div class="element-select" title="בחר בית ספר">
                        <label for="<?php echo IWizardStep1Fields::SCHOOL_NAME; ?>" class="title">* בית ספר:
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
                    <div class="element-number" title="קוד בית ספר">
                        <label for="<?php echo IWizardStep1Fields::SCHOOL_CODE; ?>" class="title">* קוד בית הספר:
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
                    <div class="element-checkbox" title="אישור תנאי שימוש">

                        <div class="column column1 terms-column">
                            <input type="checkbox" checked id="agree" name="agree"  value="אישור תנאי שימוש" / ><span class="terms-label">אישור <a href="<?php the_field( 'acf-options-wizard-step1-agreement' , 'options' ); ?>" target="_blank">תנאי שימוש</a></span>
                            <label for="agree" style="    position: absolute; top: 0px; right: 174px;"></label>
                        </div>

                        <span class="clearfix"></span>
                    </div>

                    </fieldset>

                    <div class="submit" style="width: 100%; text-align: left;">
                        <input type="submit" style="margin-left: 20px;" value="המשך &#9664;"/>
                    </div>

                    <input type="hidden" name="step" value="<?php echo IWizardStep2Fields::ID; ?>"/>
                    <?php wp_nonce_field('nonce_login_form_action', 'nonce_login'); ?>


                </form>
                </div>
            </div>
        </div>

    </section>
<?php get_footer(); ?>