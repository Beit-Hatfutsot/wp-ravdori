<?php
/**
 *
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>

<script>


var $ = jQuery

/* Fields validation */
$().ready(function () {

                        $("#wizard-form-step3").validate({
                                                           errorPlacement: function (error, element)
                                                           {
                                                               // Append error within linked label
                                                               $(element)
                                                               .closest("form")
                                                               .find("label[for='" + element.attr("id") + "']")
                                                               .append(error)
                                                           },
                                                           errorElement: "span",

                                                           rules: {
                                                                        <?php echo IWizardStep3Fields::FIRST_NAME;?>:
                                                                        {
                                                                            required: true,
                                                                            minlength: 2,
                                                                            onlyLetters: true
                                                                        },

                                                                        <?php echo IWizardStep3Fields::GRADE;?>:
                                                                        {
                                                                            required: true,
                                                                            onlyLetters: true
                                                                        },

                                                                  },

                                                           messages: {

                                                                        <?php echo IWizardStep3Fields::FIRST_NAME;?>:
                                                                        {
                                                                          required: "שדה חובה",
                                                                          minlength: "שם חייב להכיל לפחות 2 אותיות",
                                                                        },

                                                                        <?php echo IWizardStep3Fields::GRADE;?>:
                                                                        {
                                                                            required: "שדה חובה",
                                                                        },

                                                                     }
                                                         });




                                                        $("#wizard-form-step2").on('submit', function(e){

                                                            var isFormValid = true;


                                                            if ( document.getElementById('do-saving') )
                                                            {
                                                                isFormValid = true;
                                                                return true;
                                                            }

                                                            return isFormValid;
                                                        });


                                                        $('#submitSave').on('click', function(e){

                                                            $('<input />').attr('type', 'hidden')
                                                                .attr('id', 'do-saving')
                                                                .attr('name', 'do-saving')
                                                                .attr('value', 'save')
                                                                .appendTo('#wizard-form-step3');
                                                        });



                                                        // Prevent the user from entering wrong data
                                                        var selectors  = "#<?php echo IWizardStep3Fields::FIRST_NAME ?>,#<?php echo IWizardStep3Fields::TEACHER_NAME ?>,#<?php echo IWizardStep3Fields::GRADE ?>";


                                                        $(selectors).alphanum({
                                                            allow              : '()[]/\\,\'- "',
                                                            disallow           : '!@#$%^&*+=[]\\;/{}|":<>?~`._',
                                                            allowSpace         : false,
                                                            allowNumeric       : false,
                                                            allowUpper         : true,
                                                            allowLower         : true,
                                                            allowCaseless      : true,
                                                            allowLatin         : true,
                                                            allowOtherCharSets : true,
                                                            forceUpper         : false,
                                                            forceLower         : false,
                                                            maxLength          : NaN
                                                        });


                        }); // ready

</script>
<?php $errors = isset($data['errors']) ? $data['errors'] : null; ?>
    <section class="page-content">

        <div class="container">

            <div class="row">

                <?Php include(WIZARD_VIEWS . '/components/progressbar.php'); //Show the progress bar ?>

                <div class="col-xs-12">

                <form id="wizard-form-step3" class="wizard-form" method="post">
                    <div class="title">
                        <h2><?php echo '3 - ' . $wizard_steps_captions[IWizardStep3Fields::ID - 1]; ?></h2>
                    </div>


                    <!-- First name -->
                    <div class="element-input" >
                        <label for="<?php echo IWizardStep3Fields::FIRST_NAME ;?>" class="title">* שמות התלמידים:</label>

                        <?php

                        $stepData = $wizardSessionManager->getStepData( IWizardStep3Fields::ID );
                        $firstName = '';
                        if ( isset( $stepData[ IWizardStep3Fields::FIRST_NAME ] ) )
                        {
                            $firstName = $stepData[ IWizardStep3Fields::FIRST_NAME ];
                        }

                        ?>

                        <input id="<?php echo IWizardStep3Fields::FIRST_NAME ;?>"
                               class="large" type="text"
                               placeholder="קודם שם פרטי ואח''כ שם משפחה"
                               name="<?php echo IWizardStep3Fields::FIRST_NAME;?>"
                               value="<?php echo stripslashes ($firstName);?>"/>
                        <div class="wizard-filed-desc">לא ניתן להשתמש בסימן ה '+'. ניתן להשתמש בפסיקים או ברווח</div>
                    </div>



                    <!-- Class name -->
                    <div class="element-input" >
                        <label for="<?php echo IWizardStep3Fields::GRADE;?>" class="title">* כיתה:</label>

                        <?php

                        $stepData = $wizardSessionManager->getStepData( IWizardStep3Fields::ID );
                        $grade = '';
                        if ( isset( $stepData[ IWizardStep3Fields::GRADE ] ) )
                        {
                            $grade = $stepData[ IWizardStep3Fields::GRADE ];
                        }

                        ?>

                        <input id="<?php echo IWizardStep3Fields::GRADE;?>" class="large" type="text" name="<?php echo IWizardStep3Fields::GRADE;?>" value="<?php echo stripslashes ($grade);?>"/>
                        <div class="wizard-filed-desc">לא ניתן להשתמש בסימן ה '+'. ניתן להשתמש בפסיקים או ברווח</div>
                    </div>


                    <!-- Teacher  name -->
                    <div class="element-input" >
                        <label for="<?php echo IWizardStep3Fields::TEACHER_NAME;?>" class="title">שם המורה מוביל התוכנית</label>

                        <?php

                        $stepData = $wizardSessionManager->getStepData( IWizardStep3Fields::ID );
                        $teacherName = '';
                        if ( isset( $stepData[ IWizardStep3Fields::TEACHER_NAME ] ) )
                        {
                            $teacherName = $stepData[ IWizardStep3Fields::TEACHER_NAME ];
                        }

                        ?>

                        <input id="<?php echo IWizardStep3Fields::TEACHER_NAME;?>"
                               class="large"
                               placeholder="קודם שם פרטי ואח''כ שם משפחה"
                               type="text" name="<?php echo IWizardStep3Fields::TEACHER_NAME;?>"
                               value="<?php echo stripslashes ($teacherName);?>"/>
                        <div class="wizard-filed-desc">לא ניתן להשתמש בסימן ה '+'. ניתן להשתמש בפסיקים או ברווח</div>
                    </div>


                    <div class="submit">
                        <input type="submit" style="float: left;margin-left: 23px;" value="שמור והמשך &#9664;"/>
                        <input id="submitSave" type="submit" class="cancel" style="float: left;margin-left: 23px;background-color: #999999;" value="שמור"/>
                    </div>

                    <input type="hidden" name="step" value="<?php echo IWizardStep3Fields::ID; ?>"/>
                    <?php wp_nonce_field( 'nonce_author_details_form_action' , 'nonce_author_details' ); ?>

                </form>

                <form name="step2"  class="wizard-form" method="post">
                    <input name="progstep" value="2" type="hidden">

                    <div class="submit">
                        <input type="submit" value="&#9654; הקודם"/>
                    </div>

                </form>
                </div>
            </div>
        </div>

    </section>
<?php  get_footer(); ?>