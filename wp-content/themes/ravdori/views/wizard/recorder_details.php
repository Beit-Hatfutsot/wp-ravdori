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

<?php $locale = get_language_locale_filename_by_get_param(); ?>

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
                                                                          required: "<?php BH__e('שדה חובה' , 'BH', $locale);?>",
                                                                          minlength: "<?php BH__e('שם חייב להכיל לפחות 2 אותיות' , 'BH', $locale);?>",
                                                                        },

                                                                        <?php echo IWizardStep3Fields::GRADE;?>:
                                                                        {
                                                                            required: "<?php BH__e('שדה חובה' , 'BH', $locale);?>",
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
                                                            allowNumeric       : true,
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
                        <label for="<?php echo IWizardStep3Fields::FIRST_NAME ;?>" class="title"><?php BH__e('* שמות התלמידים:' , 'BH', $locale);?></label>

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
                               placeholder="<?php BH__e("קודם שם פרטי ואח''כ שם משפחה" , 'BH', $locale);?>"
                               name="<?php echo IWizardStep3Fields::FIRST_NAME;?>"
                               value="<?php echo stripslashes ($firstName);?>"/>
                        <div class="wizard-filed-desc"><?php BH__e("לא ניתן להשתמש בסימן ה '+'. ניתן להשתמש בפסיקים או ברווח" , 'BH', $locale);?></div>
                    </div>



                    <!-- Class name -->
                    <div class="element-input" >
                        <label for="<?php echo IWizardStep3Fields::GRADE;?>" class="title"><?php BH__e('* כיתה:' , 'BH', $locale);?></label>

                        <?php

                        $stepData = $wizardSessionManager->getStepData( IWizardStep3Fields::ID );
                        $grade = '';
                        if ( isset( $stepData[ IWizardStep3Fields::GRADE ] ) )
                        {
                            $grade = $stepData[ IWizardStep3Fields::GRADE ];
                        }

                        ?>

                        <input id="<?php echo IWizardStep3Fields::GRADE;?>" class="large" type="text" name="<?php echo IWizardStep3Fields::GRADE;?>" value="<?php echo stripslashes ($grade);?>"/>
                        <div class="wizard-filed-desc"><?php BH__e("לא ניתן להשתמש בסימן ה '+'. ניתן להשתמש בפסיקים או ברווח" , 'BH', $locale);?></div>
                    </div>


                    <!-- Teacher  name -->
                    <div class="element-input" >
                        <label for="<?php echo IWizardStep3Fields::TEACHER_NAME;?>" class="title"><?php BH__e('שם המורה מוביל התוכנית' , 'BH', $locale);?></label>

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
                               placeholder="<?php BH__e("קודם שם פרטי ואח''כ שם משפחה" , 'BH', $locale);?>"
                               type="text" name="<?php echo IWizardStep3Fields::TEACHER_NAME;?>"
                               value="<?php echo stripslashes ($teacherName);?>"/>
                        <div class="wizard-filed-desc"><?php BH__e("לא ניתן להשתמש בסימן ה '+'. ניתן להשתמש בפסיקים או ברווח" , 'BH', $locale);?></div>
                    </div>


                    <div class="submit">
                        <input type="submit" style="float: left;margin-left: 23px;" value="<?php BH__e('שמור והמשך &#9664;', 'BH', $locale);?>"/>
                        <input id="submitSave" type="submit" class="cancel" style="float: left;margin-left: 23px;background-color: #999999;" value="<?php BH__e('שמור' , 'BH', $locale);?>"/>
                    </div>

                    <input type="hidden" name="step" value="<?php echo IWizardStep3Fields::ID; ?>"/>
                    <?php wp_nonce_field( 'nonce_author_details_form_action' , 'nonce_author_details' ); ?>

                </form>

                <form name="step2"  class="wizard-form" method="post">
                    <input name="progstep" value="2" type="hidden">

                    <div class="submit">
                        <input type="submit" value="<?php BH__e('&#9654; הקודם' , 'BH', $locale);?>"/>
                    </div>

                </form>
                </div>
            </div>
        </div>

    </section>
<?php  get_footer(); ?>