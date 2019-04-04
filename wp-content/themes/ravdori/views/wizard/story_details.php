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


        var errorsMessages = ""; // Used for accumulating all the errors and show them to the user
        var errorListFromValidate = null; // will contain the error list from the Jquary validator plugin
        var hasErrorsFromValidate = false;
        var errorCount = 0;

        var media_uploader = null;


		
        function open_media_uploader_image_adult( )
        {
            media_uploader = wp.media({
                frame:    "post",
                state:    "insert",
                multiple: false
            });

            media_uploader.on("insert", function(){
                var json = media_uploader.state().get("selection").first().toJSON();

                var imageMimeTypes = [ "jpg" , "jpeg" , "gif" , "png" ];

                // If it's an image
                if ( imageMimeTypes.indexOf( json.subtype )  > -1 )
                {
                    var image_url = json.url;
                    var image_caption = json.caption;
                    var image_title = json.title;
                    var image_id = json.id;

                    $('.imgContainerAdultPast').html('<img src="' + image_url + '" /><input type="hidden" name="<?php echo IWizardStep4Fields::IMAGE_ADULT?>" value="' + image_id + '">');
                }
                else
                {


                    $('.imgContainerAdultPast').html('<?php BH__e('קובץ שגוי' , 'BH', $locale);?>');
                }


            });

            media_uploader.open();
        }

        function open_media_uploader_image_young( )
        {
            media_uploader = wp.media({
                frame:    "post",
                state:    "insert",
                multiple: false
            });

            media_uploader.on("insert", function() {
                var json = media_uploader.state().get("selection").first().toJSON();

                var imageMimeTypes = ["jpg", "jpeg", "gif", "png"];

                // If it's an image
                if (imageMimeTypes.indexOf(json.subtype) > -1)
                {
                    var image_url = json.url;
                    var image_caption = json.caption;
                    var image_title = json.title;
                    var image_id = json.id;

                    $('.imgContainerYoung').html('<img src="' + image_url + '" /><input type="hidden" name="<?php echo IWizardStep4Fields::IMAGE_ADULT_STUDENT?>" value="' + image_id + '">');
                }
                else
                {
                    $('.imgContainerYoung').html('<?php BH__e('קובץ שגוי' , 'BH', $locale);?>');
                }


            });

            media_uploader.open();
        }





        var $ = jQuery

        /* Fields validation */
        $().ready(function () {

                // Those 2 lines force the media library to show the upload tab first
                wp.media.controller.Library.prototype.defaults.contentUserSetting = false;
                wp.media.controller.FeaturedImage.prototype.defaults.contentUserSetting = false;
				wp.media.model.settings.post.id = null;

                $("#wizard-form-step4").validate({
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
                                <?php echo IWizardStep4Fields::STORY_TITLE;?>:
                                {
                                    required: true,
                                },

                                <?php echo IWizardStep4Fields::STORY_SUBTITLE;?>:
                                {
                                    required: true,
                                    maxlength: 150,

                                },

                                <?php echo IWizardStep4Fields::STORY_CONTENT;?>:
                                {
                                    required: true,
                                },

                                <?php echo IWizardStep4Fields::IMAGE_ADULT_DESC;?>:
                                {
                                    required: true,
                                    maxlength: 40,
                                },

                                <?php echo IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC;?>:
                                {
                                    required: true,
                                    maxlength: 40,
                                },

                                'STORY_SUBJECTS[]':
                                {
                                    required: true,
									maxlength: 4
                                },
                                /*'STORY_LANGUAGE[]':
                                {
                                    required: true,
                                },*/

                            },

                    messages: {

                                    <?php echo IWizardStep4Fields::STORY_TITLE;?>:
                                    {
                                        required: "<?php BH__e('שדה חובה' , 'BH', $locale);?>",
                                    },
                                    <?php echo IWizardStep4Fields::STORY_SUBTITLE;?>:
                                    {
                                        required: "<?php BH__e('שדה חובה' , 'BH', $locale);?>",
                                        maxlength: "<?php BH__e('ניתן לכתוב עד 150 תווים' , 'BH', $locale);?>",

                                    },
                                    <?php echo IWizardStep4Fields::STORY_CONTENT;?>:
                                    {
                                        required: "<?php BH__e('שדה חובה' , 'BH', $locale);?>",
                                    },

                                    <?php echo IWizardStep4Fields::IMAGE_ADULT_DESC;?>:
                                    {
                                        required: "<?php BH__e('שדה חובה' , 'BH', $locale);?>",
                                        maxlength: "<?php BH__e('ניתן לכתוב עד 40 תווים' , 'BH', $locale);?>",
                                    },

                                    <?php echo IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC;?>:
                                    {
                                        required: "<?php BH__e('שדה חובה' , 'BH', $locale);?>",
                                        maxlength: "<?php BH__e('ניתן לכתוב עד 40 תווים' , 'BH', $locale);?>",
                                    },

                                    'STORY_SUBJECTS[]':
                                    {
                                        required: "<?php BH__e('שדה חובה' , 'BH', $locale);?>",
										maxlength: "<?php BH__e('ניתן לבחור עד 4 נושאים' , 'BH', $locale);?>",
                                    },

                                   /* 'STORY_LANGUAGE[]':
                                    {
                                        required: "שדה חובה",
                                    },*/

                              },

                            showErrors: function(errorMap, errorList)
                            {
                                errorListFromValidate = errorList;
                                hasErrorsFromValidate = true;
                                this.defaultShowErrors();
                            }


        });



        $('#dictionary-repeater').repeater({

            // (Optional)
            // "show" is called just after an item is added.  The item is hidden
            // at this point.  If a show callback is not given the item will
            // have $(this).show() called on it.
            show: function () {
                $(this).slideDown();
            },
            // (Optional)
            // "hide" is called when a user clicks on a data-repeater-delete
            // element.  The item is still visible.  "hide" is passed a function
            // as its first argument which will properly remove the item.
            // "hide" allows for a confirmation step, to send a delete request
            // to the server, etc.  If a hide callback is not given the item
            // will be deleted.
            hide: function (deleteElement) {
                if(confirm("<?php BH__e('למחוק ערך מילון?' , 'BH', $locale);?>")) {
                    $(this).slideUp(deleteElement);
                }
            },
            // (Optional)
            // Removes the delete button from the first list item,
            // defaults to false.
            isFirstItemUndeletable: true
        });


        $('#quotes-repeater').repeater({

            // (Optional)
            // "show" is called just after an item is added.  The item is hidden
            // at this point.  If a show callback is not given the item will
            // have $(this).show() called on it.
            show: function () {
                $(this).slideDown();
            },
            // (Optional)
            // "hide" is called when a user clicks on a data-repeater-delete
            // element.  The item is still visible.  "hide" is passed a function
            // as its first argument which will properly remove the item.
            // "hide" allows for a confirmation step, to send a delete request
            // to the server, etc.  If a hide callback is not given the item
            // will be deleted.
            hide: function (deleteElement) {
                if(confirm("<?php BH__e('למחוק ציטוט?' , 'BH', $locale);?>")) {
                    $(this).slideUp(deleteElement);
                }
            },
            // (Optional)
            // Removes the delete button from the first list item,
            // defaults to false.
            isFirstItemUndeletable: true
        });



        $("#wizard-form-step4").on('submit', function(e){

            var val = $("input[type=submit][clicked=true]").attr('id');

            var isFormValid = true;

            errorCount = 0;
            errorsMessages = "<ul id='error-messages'>";
            $("#wizard-js-errors").addClass("hidden");

            // Quotes
            $("textarea[name*='<?php echo IWizardStep4Fields::QUOTES . '['?>']").each(function(){

                var tmpError = "<li><strong><?php BH__e('ציטוטים:' , 'BH', $locale);?> </strong>";
                var hasError = false;

                // If empty
                if ($.trim($(this).val()).length == 0)
                {
                    $(this).before("<label class='quote_error'><span class='error'><?php BH__e('שדה חובה' , 'BH', $locale);?></span></label>");
                    isFormValid = false;

                    hasError = true;
                    tmpError += "<span><?php BH__e('שדה חובה' , 'BH', $locale);?></span>";
                }

                // If more then 15 chars
                if ( $(this).val().replace(/^[\s,.;]+/, "").replace(/[\s,.;]+$/, "").split(/[\s,.;]+/).length > 15 )
                {
                    $(this).before("<label class='quote_error'><span class='error'><?php BH__e('ניתן לרשום עד 15 מילים' , 'BH', $locale);?></span></label>");
                    isFormValid = false;

                    hasError = true;
                    tmpError += "<span><?php BH__e('ניתן לרשום עד 15 מילים' , 'BH', $locale);?></span>";
                }

                if ( hasError )
                {
                    errorCount++;
                    errorsMessages += tmpError + "</li>";
                }
            });


            // Dictionary term
            $("textarea[name*='<?php echo IWizardStep4Fields::DICTIONARY . '['?>']").each(function(){

                var tmpError = "<li><strong><?php BH__e('ערך מילון:' , 'BH', $locale);?> </strong>";
                var hasError = false;

                // If empty
                if ($.trim($(this).val()).length == 0)
                {
                    $(this).before("<label class='dictionary_error'><span class='error'><?php BH__e('שדה חובה' , 'BH', $locale);?></span></label>");
                    isFormValid = false;

                    hasError = true;
                    tmpError += "<span><?php BH__e('שדה חובה' , 'BH', $locale);?></span>";
                }

                if ( hasError )
                {
                    errorCount++;
                    errorsMessages += tmpError + "</li>";
                }

            });


            // Dictionary term value
            $("input[name*='<?php echo IWizardStep4Fields::DICTIONARY . '['?>']").each(function(){

                var tmpError = "<li><strong><?php BH__e('פירוש מילון:' , 'BH', $locale);?> </strong>";
                var hasError = false;

                // If empty
                if ($.trim($(this).val()).length == 0)
                {
                    $(this).before("<label class='dictionary_error'><span class='error'><?php BH__e('שדה חובה' , 'BH', $locale);?></span></label>");
                    isFormValid = false;

                    hasError = true;
                    tmpError += "<span><?php BH__e('שדה חובה' , 'BH', $locale);?></span>";
                }

                if ( hasError )
                {
                    errorCount++;
                    errorsMessages += tmpError + "</li>";
                }


            });

            // Story subjects
            if ( $("input[name*='<?php echo IWizardStep4Fields::STORY_SUBJECTS . '['?>']:checked").length <=0 )
            {
                $('#lblSubjectsErrors').html("<span class='error'><?php BH__e('שדה חובה' , 'BH', $locale);?></span>");
                isFormValid = false;

                errorsMessages += "<li><strong><?php BH__e('נושאי סיפור:' , 'BH', $locale);?> </strong>";
                errorsMessages += "<span><?php BH__e('שדה חובה' , 'BH', $locale);?></span></li>";
                errorCount++;
            }


            // Image adult
            // If there is no image loaded
            if (  !($(".imgContainerAdultPast").find("img").length > 0)  )
            {
                $("#lblImgContainerAdultPast").html("<span class='error'><?php BH__e('שדה חובה' , 'BH', $locale);?></span>");
                isFormValid = false;

                errorsMessages += "<li><strong><?php BH__e('העלאת תמונה מעברו של המספר:' , 'BH', $locale);?> </strong>";
                errorsMessages += "<span><?php BH__e('שדה חובה' , 'BH', $locale);?></span></li>";
                errorCount++;
            }


            // Image young
            // If there is no image loaded
            if (  !($(".imgContainerYoung").find("img").length > 0)  )
            {
                $("#lblImgContainerYoung").html("<span class='error'><?php BH__e('שדה חובה' , 'BH', $locale);?></span>");
                isFormValid = false;

                errorsMessages += "<li><strong><?php BH__e('העלאת תמונה משותפת של המספר והמתעד:' , 'BH', $locale);?> </strong>";
                errorsMessages += "<span><?php BH__e('שדה חובה' , 'BH', $locale);?></span></li>";
                errorCount++;
            }


                if( $(this).find(".save-clicked").attr("id") === "submitSaveUpper" || $(this).find(".save-clicked").attr("id") === "submitSaveBottom")
                {
				
					if ( $.trim( $("#<?php echo IWizardStep4Fields::STORY_TITLE;?>").val() ).length == 0 )
					{
						<?php
							 global $wizardSessionManager;
							 $step2Data = $wizardSessionManager->getStepData( IWizardStep2Fields::ID );
							 $name      = $step2Data[IWizardStep2Fields::FIRST_NAME ] . ' ' . $step2Data[IWizardStep2Fields::LAST_NAME ];
						 ?>

						$("#<?php echo IWizardStep4Fields::STORY_TITLE;?>").val("<?php echo $name; ?>");
					}
				
                    $('<input />').attr('type', 'hidden')
                        .attr('name', 'do-saving')
                        .attr('value', 'save')
                        .appendTo('#wizard-form-step4');
                }


            if ( val == "submitSaveUpper" || val == "submitSaveBottom" )
            {
                if ( $.trim( $("#<?php echo IWizardStep4Fields::STORY_TITLE;?>").val() ).length == 0 )
                {
                    <?php
                         global $wizardSessionManager;
                         $step2Data = $wizardSessionManager->getStepData( IWizardStep2Fields::ID );
                         $name      = $step2Data[IWizardStep2Fields::FIRST_NAME ] . ' ' . $step2Data[IWizardStep2Fields::LAST_NAME ];
                     ?>

                    $("#<?php echo IWizardStep4Fields::STORY_TITLE;?>").val("<?php echo $name; ?>");
                }

                return true;
            }



            if ( !isFormValid || hasErrorsFromValidate )
            {
                errorListFromValidate.forEach( function( item )
                {
                    errorCount++;

                    var msg = "";

                    switch ( item.element.name )
                    {
                        case "<?php echo IWizardStep4Fields::STORY_TITLE;?>":
                            msg = "<?php BH__e('שם הסיפור:' , 'BH', $locale);?> ";
                        break;

                        case "<?php echo IWizardStep4Fields::STORY_SUBTITLE;?>":
                            msg = "<?php BH__e('כותרת משנה:' , 'BH', $locale);?> ";
                            break;

                        case "<?php echo IWizardStep4Fields::STORY_CONTENT;?>":
                            msg = "<?php BH__e('תוכן הסיפור:' , 'BH', $locale);?> ";
                            break;

                        case "<?php echo IWizardStep4Fields::IMAGE_ADULT_DESC;?>":
                            msg = "<?php BH__e('תיאור תמונת מספר:' , 'BH', $locale);?> ";
                            break;

                        case "<?php echo IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC;?>":
                            msg = " <?php BH__e('תיאור תמונת מספר ומתעד:' , 'BH', $locale);?> ";
                            break;

                        case "<?php echo IWizardStep4Fields::STORY_SUBJECTS;?>[]":
                            msg = "<?php BH__e('נושאי הסיפור:' , 'BH', $locale);?> ";
                            break;

                        case "<?php echo IWizardStep4Fields::STORY_LANGUAGE;?>[]":
                            msg = "<?php BH__e('שפת הסיפור:' , 'BH', $locale);?> ";
                            break;

                    };

                    errorsMessages += "<li><span><strong>" + msg + "</strong>" + item.message + "</span></li>";
                });

                errorsMessages += "</ul>";

                $("#wizard-js-errors strong.error-title").html("<?php BH__e('קיימות' , 'BH', $locale);?> "
                    + errorCount
                    + " <?php BH__e('שגיאות:' , 'BH', $locale);?>");



                $("#wizard-js-errors .error-list").html ( errorsMessages );


                $("#wizard-js-errors").removeClass("hidden");

                $('html, body').animate({
                    scrollTop: $("#wizard-js-errors").offset().top
                }, 1000);
            }


            return isFormValid;
        });



        $('#submitSaveUpper , #submitSaveBottom').on('click', function(e){
            $(this).addClass("save-clicked");
        });


        $("#wizard-form-step4 input[type=submit]").click(function() {
            $("input[type=submit]", $(this).parents("#wizard-form-step4")).removeAttr("clicked");
            $(this).attr("clicked", "true");
        });


        $('#wizard-js-errors a.close').on('click', function(e){
            $("#wizard-js-errors").addClass("hidden");
        });



        $("textarea[name*='<?php echo IWizardStep4Fields::QUOTES . '['?>']").live('keypress', function(kp_e){

            var regex = /\s+/gi;
            wordcount = $(this).val().trim().replace(regex, ' ').split(' ').length;

            if ( wordcount > 15 )
            {
                $(this).prev("label.quote-error").html("<span class='error'><?php BH__e('ניתן לכתוב עד 15 מילים' , 'BH', $locale);?></span>");

                errorsMessages += "<strong><?php BH__e('ציטוטים' , 'BH', $locale);?></strong>";
                errorsMessages += "<span><?php BH__e('ניתן לכתוב עד 15 מילים' , 'BH', $locale);?></span>";

                kp_e.preventDefault();
            }
            else if (  wordcount < 15 )
            {
                $(this).prev("label.quote-error").html("");
            }

        });


        }); //ready

	
	$( window ).load(function() {
		
		/* Change the tinyMce button accroding to the translation */
		jQuery('#insert-media-button').get(0).lastChild.nodeValue = "<?php BH__e('הוספת מדיה' , 'BH', $locale); ?>"
		jQuery('.awsm-embed.button').get(0).lastChild.nodeValue   = "<?php BH__e('הוספת מסמך' , 'BH', $locale); ?>";
		
	}); // Window.load
	

        // Ajax - Autosave function
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        var response = true;
        setInterval( function() { autoSavePost() }  , 5 * (60 * 1000) ); // Save the story every 5 minutes


        function autoSavePost()
        {
			if ( $.trim( $("#<?php echo IWizardStep4Fields::STORY_TITLE;?>").val() ).length == 0 )
			{
				<?php
					 global $wizardSessionManager;
					 $step2Data = $wizardSessionManager->getStepData( IWizardStep2Fields::ID );
					 $name      = $step2Data[IWizardStep2Fields::FIRST_NAME ] . ' ' . $step2Data[IWizardStep2Fields::LAST_NAME ];
				?>

				$("#<?php echo IWizardStep4Fields::STORY_TITLE;?>").val("<?php echo $name; ?>");
			}
			
            if(response == true)
            {

                // Enable the serialization of the wp_editor (@see http://jeromejaglale.com/doc/javascript/tinymce_jquery_ajax_form)
                tinyMCE.triggerSave();


                // This makes it unable to send a new request
                // unless you get response from last request
                response = false;
                var req = $.ajax({
                    type:"post",
                    url: ajaxurl,
                    data: {
                            data: $('#wizard-form-step4').serialize(),
                            'action': 'autoSaveStory_ajax'
                          }
                });

                req.done(function(e)
                {
                    
					/*console.log("Request successful! ");
                    console.log("e: ");
					console.log(e);*/
					
					
					  if ( e.toLowerCase().indexOf("text/html") >= 0 ) {
							// assume that our login has expired - reload our current page
							window.location.reload();
					  }

					
					if ( e == '<?php echo IWizardSessionFields::AJAX_SESSION_EXPIRED; ?>') {
						<?php echo 'window.location.href ="' . esc_url( home_url() ) . '"'; ?>
					}

                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-bottom-right",
                        "preventDuplicates": true,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "4500",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }

                    toastr.info('<?php BH__e('הסיפור נשמר אוטומטית' , 'BH', $locale);?>');
                    // This makes it able to send new request on the next interval
                    response = true;
					
	
					
                });
            }


        }
		
		
		
		function killCurrentSession() {
			
			
				jQuery.ajax({
					url  : ajaxurl,
					type : 'post',
					data : {
							 'session_signal':'kill',
							 'action' : 'autoSaveStory_ajax'
						   },
						   
					success : function( response ) {
							<?php echo 'window.location.href ="' . esc_url( home_url() ) . '"'; ?>
					}
				});

		} // killCurrentSession

		
		setTimeout( killCurrentSession, 1000 * 60 * 97 ); // close after 1h



    </script>


<?php if ( $locale == ISupportedLanguages::EN['locale_file'] ): ?>

	<style>			
		body.page-template-wizard #wizard-form-step4 #wp-STORY_CONTENT-media-buttons:before {
				background: transparent url('/wp-content/themes/ravdori/images/general/story/insert-media-bubble-en.png') 0 0 no-repeat !important;
		}
	</style>
			
<?php endif;?>


<?php $errors = isset($data['errors']) ? $data['errors'] : null; ?>
<?php
$isStorySaved = $wizardSessionManager->getField( 'do-saving' );

$wizardSessionManager->setField( 'do-saving' , false );

?>
    <section class="page-content">

        <div class="container">

            <div class="row">

                <?Php include(WIZARD_VIEWS . '/components/progressbar.php'); //Show the progress bar ?>

                <div class="col-xs-12">

                <form id="wizard-form-step4" class="wizard-form" method="post">
                    <div class="title">
                        <h2><?php echo '4 - ' . $wizard_steps_captions[IWizardStep4Fields::ID - 1]; ?></h2>
                    </div>

                    <div class="upper-save-area">
                        <input type="submit" style="float: left;margin-left: 23px;" value="<?php BH__e('שמור והמשך &#9664;' , 'BH', $locale);?>"/>
                        <input id="submitSaveUpper" type="submit" class="cancel" style="float: left;margin-left: 23px;background-color: #999999;" value="<?php BH__e('שמור' , 'BH', $locale);?>"/>
                    </div>

                    <div class="col-sm-7">
                        <?php the_field('acf-options-wizard-step4-text' ,'options'); ?>
                    </div>

                    <?php  if ( $isStorySaved ):  ?>
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" style="text-decoration: none;">&times;</a>
                            <strong> <?php BH__e('הסיפור נשמר בהצלחה' , 'BH', $locale);?> </strong>
                        </div>
                    <?php endif; ?>



                    <div id="wizard-js-errors" class="alert alert-error hidden fade in" role="alert">
                        <a href="#" class="close" style="text-decoration: none;">&times;</a>
                        <strong class="error-title"></strong>
                        <div class="error-list"></div>
                    </div>



                    <!-- Story Title  -->
                    <div class="element-input col-sm-6" >
                        <label id="lblStoryTitle" for="<?php echo IWizardStep4Fields::STORY_TITLE ;?>" class="title"><?php BH__e('* שם הסיפור' , 'BH', $locale);?>
                            <?php showBackendErrors( $errors , IWizardStep4Fields::STORY_TITLE ); ?>
                        </label>

                        <?php

                        $stepData = $wizardSessionManager->getStepData( IWizardStep4Fields::ID ); 
                        $storyTitle = '';
                        if ( isset( $stepData[ IWizardStep4Fields::STORY_TITLE ] ) )
                        {
                            $storyTitle = esc_html( stripslashesFull( $stepData[ IWizardStep4Fields::STORY_TITLE ] ) );
                        }

                        ?>


                        <input id="<?php  echo IWizardStep4Fields::STORY_TITLE ;?>"
                               class="large" type="text"
                               name="<?php echo IWizardStep4Fields::STORY_TITLE ;?>"
                               value="<?php echo $storyTitle;?>"
                               placeholder = "<?php BH__e('איך תקראו לסיפור' , 'BH', $locale);?>"
                        />
                    </div>


                    <!-- Secondary text  -->
                    <div class="element-input col-sm-6" >
                        <label for="<?php echo IWizardStep4Fields::STORY_SUBTITLE ;?>" class="title"><?php BH__e('* כותרת משנה' , 'BH', $locale);?>
                            <?php showBackendErrors( $errors , IWizardStep4Fields::STORY_SUBTITLE ); ?>
                        </label>

                        <?php

                        $stepData = $wizardSessionManager->getStepData( IWizardStep4Fields::ID );
                        $storySubTitle = '';
                        if ( isset( $stepData[ IWizardStep4Fields::STORY_SUBTITLE ] ) )
                        {
                            $storySubTitle = stripslashesFull( $stepData[ IWizardStep4Fields::STORY_SUBTITLE ] );
                        }

                        ?>

                        <input id="<?php  echo IWizardStep4Fields::STORY_SUBTITLE ;?>"
                               class="large"
                               value="<?php echo $storySubTitle ?>"
                               type="text"
                               name="<?php echo IWizardStep4Fields::STORY_SUBTITLE ;?>"
                               placeholder="<?php BH__e('תיאור קצר של עיקר הסיפור' , 'BH', $locale);?>"
                        />

                    </div>




                    <!-- Content  -->
                    <div class="element-input" style="width: 98% !important;">
                        <label for="<?php echo IWizardStep4Fields::STORY_CONTENT ;?>" class="title">
                            <span><?php BH__e('* תוכן הסיפור' , 'BH', $locale);?></span>
                            <div>
                                <strong>
                                    <?php BH__e('להטמעת סרטון וידאו ממקור חיצוני (כגון: יוטיוב) יש להעתיק את כתובת הסרטון (url) ולהדביקה בתוכן הסיפור' , 'BH', $locale);?>
                                </strong>
                            </div>
                            <?php showBackendErrors( $errors , IWizardStep4Fields::STORY_CONTENT ); ?>
                        </label>

                        <?php

                        $stepData = $wizardSessionManager->getStepData( IWizardStep4Fields::ID );
                        $editorContent = '';
                        if ( isset( $stepData[ IWizardStep4Fields::POST_ID ] ) )
                        {
                            $post = get_post( $stepData[ IWizardStep4Fields::POST_ID ] );
                            $editorContent = wpautop($post->post_content);
                        }

                        ?>

                        <?php
                            wp_editor ( $editorContent , IWizardStep4Fields::STORY_CONTENT ,
                                                                                            array(
                                                                                                    'textarea_rows' => 15,
                                                                                                    'wpautop'       => true,
                                                                                                    'tinymce'       => true,
                                                                                                    'teeny'         => true,
                                                                                                    'quicktags'     => false,
                                                                                                    'editor_class'  => 'required',
                                                                                                    'media_buttons' => true,
                                                                                                )
                                      );

                        ?>

                    </div>


                    <div class="row">
							
						 <div class="col-lg-12 text-center" style="border: 4px solid #C00000;padding:15px; margin: 40px 0;font-size: 21px;">	
						   <strong>
							<u><?php BH__e('אסור להעלות תמונות מהרשת!' , 'BH', $locale);?></u> -
							   <?php BH__e('יש להעלות תמונות אישיות משותפות או תמונות מהאלבומים שלכם' , 'BH', $locale);?>
						   </strong>   
						 </div>
							

                        <?php

                        $stepData = $wizardSessionManager->getStepData( IWizardStep4Fields::ID );
                        $imgChild = null;
                        if ( isset( $stepData[ IWizardStep4Fields::IMAGE_ADULT_STUDENT ] ) )
                        {
                            $imgChild = $stepData[ IWizardStep4Fields::IMAGE_ADULT_STUDENT ];
                        }

                        $img =  wp_get_attachment_image( $imgChild , 'full' );

                        ?>


                        <div class="col-sm-6">
                            <h2><?php BH__e('* העלאת תמונה משותפת של המספר והמתעד' , 'BH', $locale);?></h2>
                            <label id="lblImgContainerYoung"><?php showBackendErrors( $errors , IWizardStep4Fields::IMAGE_ADULT_STUDENT ); ?></label>
                            <div class="imgContainerYoung text-center">
                                <?php

                                echo $img;
                                echo '<input type="hidden" name="' . IWizardStep4Fields::IMAGE_ADULT_STUDENT . '" value="' . $imgChild . '">';
                                ?>
                            </div>
                            <input type="button" class="step4LoadImgButton" onclick="open_media_uploader_image_young();" value="<?php BH__e('בחר / החלף תמונה' , 'BH', $locale);?>"/>

                            <?php $imgStudentDesc = $stepData[ IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC ]; 
								  $imgStudentDesc = htmlentities( stripslashesFull( $imgStudentDesc )  , ENT_QUOTES ); 
							?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="<?php echo IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC ?>"><?php BH__e('* תיאור תמונת מספר ומתעד' , 'BH', $locale);?></label>
                                    <textarea id="<?php echo IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC ?>"
                                              name="<?php echo IWizardStep4Fields::IMAGE_ADULT_STUDENT_DESC ?>"
                                              placeholder="<?php BH__e('עד 40 תווים' , 'BH', $locale);?>"><?php echo ( isset($imgStudentDesc) ? $imgStudentDesc : "" );?></textarea>

                                    <div class="wizard-filed-desc"><?php BH__e('להעלאת תמונות נוספות בגוף הסיפור , בחר באופציה של הוספת מדיה שנמצאת בתוכן הסיפור' , 'BH', $locale);?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <h2><?php BH__e('* העלאת תמונה מעברו של המספר' , 'BH', $locale);?></h2>
                            <label id="lblImgContainerAdultPast"><?php showBackendErrors( $errors , IWizardStep4Fields::IMAGE_ADULT ); ?></label>

                            <?php

                            $stepData = $wizardSessionManager->getStepData( IWizardStep4Fields::ID );
                            $imgAdultPast = null;
                            if ( isset( $stepData[ IWizardStep4Fields::IMAGE_ADULT ] ) )
                            {
                                $imgAdultPast = $stepData[ IWizardStep4Fields::IMAGE_ADULT ];
                            }

                            $img =  wp_get_attachment_image( $imgAdultPast , 'full' );

                            ?>

                            <div class="imgContainerAdultPast text-center">
                                <?php
                                echo $img;
                                echo '<input type="hidden" name="' . IWizardStep4Fields::IMAGE_ADULT . '" value="' . $imgAdultPast . '">';
                                ?>
                            </div>
                            <input class="step4LoadImgButton" type="button" onclick="open_media_uploader_image_adult();"  value="<?php BH__e('בחר / החלף תמונה' , 'BH', $locale);?>" />

                            <?php 
								  $imgAdultDesc = $stepData[ IWizardStep4Fields::IMAGE_ADULT_DESC ]; 
								  $imgAdultDesc = htmlentities( stripslashesFull( $imgAdultDesc )  , ENT_QUOTES ); 
							?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="<?php echo IWizardStep4Fields::IMAGE_ADULT_DESC ?>"><?php BH__e('* תיאור תמונת מספר' , 'BH', $locale);?></label>
                                    <textarea id="<?php echo IWizardStep4Fields::IMAGE_ADULT_DESC ?>"
                                              name="<?php echo IWizardStep4Fields::IMAGE_ADULT_DESC ?>"
                                              placeholder="<?php BH__e('עד 40 תווים' , 'BH', $locale);?>"><?php echo ( isset($imgAdultDesc) ? $imgAdultDesc : "" );?></textarea>
                                    <div class="wizard-filed-desc"><?php BH__e('להעלאת תמונות נוספות בגוף הסיפור , בחר באופציה של הוספת מדיה שנמצאת בתוכן הסיפור' , 'BH', $locale);?></div>
                                </div>
                            </div>
                        </div>


                    </div>



                    <hr/>

<div class="row">
    <div class="col-sm-6">
                    <!-- Dictionary Repeater  -->
                    <div id="dictionary-repeater">
                        <h2 class="title-underline"><?php BH__e('* מילון' , 'BH', $locale);?></h2>

                        <div class="col-sm-12">
<?php BH__e( "                 אנחנו רוצים לדעת אם למדתם מילים חדשות במהלך העבודה
                            המשותפת שלכם. האם למדתם מלים בשפה אחרת? האם למדתם מלים חדשות בעברית?
                            האם למדתם ביטויים שלא הכרתם?" 
	, "BH" , $locale);
?>
                        </div>

                        <label><?php showBackendErrors( $errors , IWizardStep4Fields::DICTIONARY ); ?></label>
                        <div data-repeater-list="<?php echo IWizardStep4Fields::DICTIONARY?>">


                            <?php $terms = $stepData[IWizardStep4Fields::DICTIONARY]; ?>

                            <?php if ( !isset( $terms ) OR empty( $terms ) ): ?>

                                <div data-repeater-item class="row">
                                    <div class="col-sm-12">
                                        <label for="text-input"><?php BH__e('כתבו כאן את המילה:' , 'BH', $locale);?></label>
                                        <input type="text" placeholder="<?php BH__e('או הביטוי' , 'BH', $locale);?>" name="text-input" value="" style="width: 100%" />
                                    </div>

                                    <div class="col-sm-12">
                                        <label for="textarea-input1"><?php BH__e('כתבו כאן את פירושה:' , 'BH', $locale);?></label>
                                        <textarea name="textarea-input1" style="width: 100%"></textarea>
                                    </div>

                                    <div class="col-sm-12  text-left">
                                        <input class="btnRemove" data-repeater-delete="" type="button" value="<?php BH__e('מחיקת ערך מילון' , 'BH', $locale);?>"/>
                                    </div>
                                </div>

                            <?php else: ?>

                                <?php  foreach ( $terms as $term ): ?>

                                    <div data-repeater-item class="row voffset5">
                                        <div class="col-sm-12">
                                            <label for="text-input"><?php BH__e('כתבו כאן את המילה:' , 'BH', $locale);?></label>
                                            <input type="text" placeholder="<?php BH__e('או הביטוי' , 'BH', $locale);?>"  name="text-input" style="width: 100%" value="<?php echo htmlentities( stripslashesFull($term['text-input'])  , ENT_QUOTES ); ?>"/>
                                        </div>

                                        <div class="col-sm-12">
                                            <label for="textarea-input1"><?php BH__e('כתבו כאן את פירושה:' , 'BH', $locale);?></label>
                                            <textarea name="textarea-input1" style="width: 100%"><?php echo htmlentities( stripslashesFull($term['textarea-input1'])  , ENT_QUOTES ); ?></textarea>
                                        </div>

                                        <div class="col-sm-12 text-left">
                                            <input class="btnRemove" data-repeater-delete="" type="button" value="<?php BH__e('מחיקת ערך מילון' , 'BH', $locale);?>"/>
                                        </div>
                                    </div>


                                <?php endforeach ?>
                            <?php endif; ?>


                        </div>
                                <span data-repeater-create="" class="btnAdd">
                                        <span class="glyphicon glyphicon-plus"></span> <?php BH__e('הוספת ערך מילון' , 'BH', $locale);?>
                                </span>
                    </div>
    </div>




        <div class="col-sm-6">
                    <!-- Quotes Repeater  -->
                    <div id="quotes-repeater">
                        <h2 class="title-underline"><?php BH__e('* ציטוטים' , 'BH', $locale);?></h2>

                        <div class="col-sm-12">
<div>
    <?php BH__e('האם יש ציטוט או משפט מיוחד בעל מסר ומשמעות שלמדת מהמבוגר?' , 'BH', $locale);?><br/><br/>
</div>
                        </div>

                        <label><?php showBackendErrors( $errors , IWizardStep4Fields::QUOTES ); ?></label>
                        <div data-repeater-list="<?php echo IWizardStep4Fields::QUOTES?>">

                            <?php $quotes = $stepData[IWizardStep4Fields::QUOTES]; ?>

                            <?php if ( !isset( $quotes ) OR empty($quotes) ): ?>

                                <div data-repeater-item class="row">

                                    <div class="col-sm-12">
                                        <label for="textarea-input2"><?php BH__e('כתבו כאן את הציטוט (עד 15 מילים)' , 'BH', $locale);?></label>
                                        <label class="quote-error"></label>
                                        <textarea name="textarea-input2" placeholder="<?php BH__e('עד 15 מילה' , 'BH', $locale);?>" style="width: 100%"></textarea>
                                    </div>

                                    <div class="col-sm-12 text-left">
                                        <input class="btnRemove" data-repeater-delete="" type="button"  value="<?php BH__e('מחיקת ציטוט' , 'BH', $locale);?>" />
                                    </div>

                                </div>

                            <?php else:  ?>

                                <?php  foreach ( $quotes as $quote ): ?>

                                    <div data-repeater-item class="row voffset5">

                                        <div class="col-sm-12">
                                            <label for="textarea-input2"><?php BH__e('כתבו כאן את הציטוט' , 'BH', $locale);?></label>
                                            <label class="quote-error"></label>
                                            <textarea name="textarea-input2"  placeholder="<?php BH__e('עד 15 מילה' , 'BH', $locale);?>"   style="width: 100%"><?php echo isset ( $quote['textarea-input2'] ) ? stripslashesFull ( $quote['textarea-input2'] ) : "" ;?></textarea>
                                        </div>

                                        <div class="col-sm-12 text-left">
                                            <input class="btnRemove" data-repeater-delete="" type="button"  value="<?php BH__e('מחיקת ציטוט' , 'BH', $locale);?>" />
                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </div>
                                <span data-repeater-create="" class="btnAdd">
                                        <span class="glyphicon glyphicon-plus"></span><?php BH__e('הוספת ציטוט' , 'BH', $locale);?>
                                </span>
                    </div>

            </div>
    </div>




                    <div class="row">
                        <h2 class="title-underline" ><?php BH__e('* נושאים (ניתן לבחור עד 4 נושאים)' , 'BH', $locale);?></h2>
                        <div class="col-sm-12"><?php BH__e('סמנו מילים המתארות באופן הטוב ביותר את הנושאים המרכזיים בסיפור.' , 'BH', $locale);?> </div>
                        <div class="col-sm-12 voffset3"></div>
                        <label id="lblSubjectsErrors"><?php showBackendErrors( $errors , IWizardStep4Fields::STORY_SUBJECTS ); ?></label>
                        <?php
                        $subjects = $data['subjects'];
                        $selectedSubjects = $stepData[IWizardStep4Fields::STORY_SUBJECTS];
						
						aasort( $subjects , "name");

                        if ( isset( $selectedSubjects ) )
                        {
                            foreach ($subjects as $subject)
                            {
                                echo '<div class="col-xs-3">';
                                echo '<label>';
                                echo '<input type="checkbox"' . (( in_array( $subject['id'] , $selectedSubjects )) ? ' checked ' : '') . 'id="' . $subject['id'] . '" name="' . IWizardStep4Fields::STORY_SUBJECTS . '[]"  value="' . $subject['id'] . '"/ > <span> </span>';
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
                                echo '<input type="checkbox"' . ' id="' . $subject['id'] . '" name="' . IWizardStep4Fields::STORY_SUBJECTS . '[]"  value="' . $subject['id'] . '"/ > <span> </span>';
                                echo  '<div class="checkbox-label">' . $subject['name'] .  '</div>' ;
                                echo '</label>';
                                echo '</div>';
                            }
                        }
                        ?>

                    </div>



                    <div class="row">
                        <h2 class="title-underline" ><?php BH__e('שפת כתיבת הסיפור' , 'BH', $locale);?></h2>
                        <div class="col-sm-12"><?php BH__e('סמן את שפת כתיבת הסיפור' , 'BH', $locale);?></div>
                        <div class="col-sm-12 voffset3"></div>
                        <label id="lblSubjectsErrors"><?php showBackendErrors( $errors , IWizardStep4Fields::STORY_LANGUAGE ); ?></label>
                        <?php
                        $subjects = $data['languages'];
                        $selectedSubjects = $stepData[IWizardStep4Fields::STORY_LANGUAGE];

						aasort( $subjects , "name");
						
                        if ( isset( $selectedSubjects ) )
                        {
                            foreach ($subjects as $subject)
                            {
                                echo '<div class="col-xs-3">';
                                echo '<label>';
                                echo '<input type="checkbox"' . (( in_array( $subject['id'] , $selectedSubjects )) ? ' checked ' : '') . 'id="' . $subject['id'] . '" name="' . IWizardStep4Fields::STORY_LANGUAGE . '[]"  value="' . $subject['id'] . '"/ > <span> </span>';
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
                                echo '<input type="checkbox"' . ( ( $subject['id'] == '1124') ? ' checked ' : '' ) . ' id="' . $subject['id'] . '" name="' . IWizardStep4Fields::STORY_LANGUAGE . '[]"  value="' . $subject['id'] . '"/ > <span> </span>';
                                echo  '<div class="checkbox-label">' . $subject['name'] .  '</div>' ;
                                echo '</label>';
                                echo '</div>';
                            }
                        }
                        ?>

                    </div>



                    <div class="row">
                        <h2 class="title-underline"><?php BH__e('תגיות' , 'BH', $locale);?></h2>
                        <div class="col-sm-12"><?php BH__e('האם בית ספרכם מפעיל את התכנית במודל מיוחד? בשיתוף פעולה עם ארגון חיצוני? אנא סמנו.' , 'BH', $locale);?> </div>
                        <div class="col-sm-12 voffset3"></div>
                        <label><?php showBackendErrors( $errors , IWizardStep4Fields::STORY_SUBTOPICS ); ?></label>
                        <?php
                        
						$subtopics = $data['subtopics'];
                        $selectedSubtopics = $stepData[IWizardStep4Fields::STORY_SUBTOPICS];
						
						aasort( $subtopics , "name");
								
                        if ( $selectedSubtopics ) {
                            foreach ($subtopics as $subtopic)
                            {
                                    echo '<div class="col-xs-3">';
                                        echo '<label>';
                                               echo '<input type="checkbox"' . ((in_array( $subtopic['id'] , $selectedSubtopics )) ? ' checked ' : '') . 'id="' . $subtopic['id'] . '" name="' . IWizardStep4Fields::STORY_SUBTOPICS . '[]"  value="' . $subtopic['id'] . '"/ > <span> </span>';
                                               echo  '<div class="checkbox-label">' . $subtopic['name'] .  '</div>' ;
                                        echo '</label>';
                                    echo '</div>';

                            }
                        }
                        else
                        {
                            foreach ($subtopics as $subtopic)
                            {

                                    echo '<div class="col-xs-3">';
                                        echo '<label>';
                                            echo '<input type="checkbox" id="' . $subtopic['id'] . '" name="' . IWizardStep4Fields::STORY_SUBTOPICS . '[]"  value="' . $subtopic['id'] . '"/ > <span></span>';
                                            echo  '<div class="checkbox-label">' . $subtopic['name'] .  '</div>' ;
                                        echo '</label>';
                                echo '</div>';

                            }
                        }
                        ?>

                    </div>

                    <?php 
							$feedback = $stepData[IWizardStep4Fields::RAVDORI_FEEDBACK];
							$feedback = htmlentities( stripslashesFull($feedback)  , ENT_QUOTES ); 
					?>
                    <div>
                        <h2><?php BH__e('מהזוית האישית - מספר ומתעד' , 'BH', $locale);?></h2>
                        <label><?php showBackendErrors( $errors , IWizardStep4Fields::RAVDORI_FEEDBACK ); ?></label>
                        <textarea name="<?php echo IWizardStep4Fields::RAVDORI_FEEDBACK; ?>"
                                  placeholder="<?php BH__e('ספרו לנו על חווייתכם מהשתתפותכם בתכנית ומהעבודה המשותפת. מה הייתם רוצים להגיד ולאחל אחד לשני?' , 'BH', $locale);?>"
                                  style="width: 100%"><?php echo ( isset($feedback) ? $feedback : "" ) ?></textarea>
                    </div>


                    <div class="submit">
                        <input type="submit" style="float: left;margin-left: 23px;" value="<?php BH__e('שמור והמשך &#9664;' , 'BH', $locale);?>"/>
                        <input id="submitSaveBottom" type="submit" class="cancel" style="float: left;margin-left: 23px;background-color: #999999;" value="<?php BH__e('שמור' , 'BH', $locale);?>"/>
                    </div>



                    <input type="hidden" name="step" value="<?php echo IWizardStep5Fields::ID; ?>"/>
                    <?php wp_nonce_field( 'nonce_author_details_form_action' , 'nonce_author_details' ); ?>

                </form>

                <form name="step3"  class="wizard-form" method="post">
                    <input name="progstep" value="3" type="hidden">

                    <div class="submit">
                        <input type="submit" value="<?php BH__e('&#9654; הקודם' , 'BH', $locale);?>" style="background-color: #999999;" />
                    </div>

                </form>
                </div>
            </div>
        </div>

    </section>
<?php  get_footer(); ?>