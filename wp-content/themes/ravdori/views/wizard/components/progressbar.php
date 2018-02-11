<?php
/**
 * This file contains
 *
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once WIZARD_DIRECTORY   . 'BH_SessionManager.php';
?>

<?php
$i = 1;
$j = 1;
global $wizard_steps_captions;
global $wizard_steps_colors;
global $wizardSessionManager;

?>
<style>
    <?php foreach ( $wizard_steps_colors as $color ): ?>
        #progressbar li.btnStep<?php echo $j;?>:before {  background: <?php echo $wizard_steps_colors[ $j - 1 ]; ?> !important; /*opacity: 0.5;*/ color:#fff;}
        /*#progressbar li.available.btnStep<?php echo $j;?>:before { opacity: 1; }*/
       /* #progressbar li.active.btnStep<?php echo $j;?>:before { background: transparent !important; border:2px solid <?php echo $wizard_steps_colors[ $j - 1 ]; ?>!important; color: <?php echo $wizard_steps_colors[ $j - 1 ]; ?>;}*/
       /* #progressbar li.btnStep<?php echo $j;?>.active { color: <?php echo $wizard_steps_colors[ $j - 1 ]; ?>!important; }*/

        #progressbar li.btnStep<?php echo $j;?>.active { font-family: "opensanshebrew-bold", Arial, Helvetica, Sans-serif; font-size: 21px; color: #666666; }
        #progressbar li.btnStep<?php echo $j;?>.active:before { font-size:38px; }


        <?php $rgbVal = hex2rgb (  $wizard_steps_colors[ $j - 1 ] );?>

        #progressbar li.btnStep<?php echo $j;?> button { background: rgba( <?php echo $rgbVal[0] . ',' . $rgbVal[1] . ',' . $rgbVal[2] . ',0.3'  ?>  ); }
        #progressbar li.btnStep<?php echo $j;?> button:after { border-color: transparent  rgba( <?php echo $rgbVal[0] . ',' . $rgbVal[1] . ',' . $rgbVal[2] . ',0.3'  ?>  ) transparent  transparent ; }

    <?php $j++; ?>
    <?php endforeach; ?>

 <?php $j =  $wizardSessionManager->getCurrentStep() - 1; ?>



.wizard-form div.title { background-color: <?php echo $wizard_steps_colors[ $j ]; ?> }

.wizard-form input[type="submit"] { background-color: <?php echo $wizard_steps_colors[ $j ]; ?> }


.wizard-form input[type="text"]:hover,
.wizard-form input[type="password"]:hover,
.wizard-form input[type="email"]:hover,
.wizard-form input[type="url"]:hover,
.wizard-form input[type="date"]:hover,
.wizard-form input[type="number"]:hover,
.wizard-form textarea:hover,
.wizard-form .element-file .file_text:hover,
.wizard-form select:hover,
.wizard-form input[type=text]:focus,
.wizard-form input[type=password]:focus,
.wizard-form input[type=email]:focus,
.wizard-form input[type=url]:focus,
.wizard-form input[type=date]:focus,
.wizard-form input[type=number]:focus,
.title-underline { border-color: <?php echo $wizard_steps_colors[ $j ]; ?>!important;  }


body.page-template-wizard #wizard-form-step4 input[type=checkbox]:checked + span:before,
body.page-template-wizard #wizard-form-step4 input[type=checkbox]:hover + span:before,
.wizard-form input[type=checkbox]:hover + span:before,
.wizard-form input[type=radio]:hover + span:before { color:<?php echo $wizard_steps_colors[ $j ]; ?>; }

body.page-template-wizard #wizard-form-step4 .imgContainerYoung,
body.page-template-wizard #wizard-form-step4 .imgContainerAdultPast { border: 3px solid <?php echo $wizard_steps_colors[ $j ]; ?>; }

body.page-template-wizard #wizard-form-step4 #STORY_CONTENT_ifr { border: 2px solid <?php echo $wizard_steps_colors[ $j ]; ?>; }

body.page-template-wizard #wizard-form-step4 .step4LoadImgButton,
body.page-template-wizard #wizard-form-step4 .btnAdd,
body.page-template-wizard #wizard-form-step4 #wp-STORY_CONTENT-media-buttons .button { background-color: <?php echo $wizard_steps_colors[ $j ]; ?> !important; }


<?php $rgbVal = hex2rgb (  $wizard_steps_colors[ $j ] );?>




.wizard-form input[type="text"],
.wizard-form input[type="password"],
.wizard-form input[type="email"],
.wizard-form input[type="url"],
.wizard-form input[type="date"],
.wizard-form input[type="number"],
.wizard-form textarea,
.wizard-form .element-file .file_text,
.wizard-form select  { border-color:  rgba( <?php echo $rgbVal[0] . ',' . $rgbVal[1] . ',' . $rgbVal[2] . ',0.5'  ?>  ); border-width: 3px;  }

.wizard-select-theme .chosen-container .chosen-drop, .chosen-container-single .chosen-single  { border: 3px solid rgba( <?php echo $rgbVal[0] . ',' . $rgbVal[1] . ',' . $rgbVal[2] . ',0.5'  ?>  ); }

.wizard-select-theme .chosen-container .chosen-results li.highlighted { background: rgba( <?php echo $rgbVal[0] . ',' . $rgbVal[1] . ',' . $rgbVal[2] . ',0.8'  ?> ); color: #666766; font-size: 20px; }

#wizard-form-step2 .wizard-select-theme .chosen-container .chosen-results li.highlighted { color:#fff; }

.wizard-select-theme .chosen-container-single .chosen-single div b:after,
.wizard-form input[type="checkbox"]:hover,
.wizard-form input[type="checkbox"]:checked + span:before,
.wizard-form input[type="radio"]:checked + span:before { color: <?php echo $wizard_steps_colors[ $j ]; ?>; }


/* Language Direction */
<?php 
// Get the current local
$locale = get_language_locale_filename_by_get_param(true); 
$text_direction = $locale['dir'];
$locale = $locale['locale_file'];
if ( $text_direction == 'ltr'):?>

/* Global */
.wizard-form label {
    text-align: left !important;
    direction: ltr;
    display: block;
}

.wizard-form ::-webkit-input-placeholder { /* Chrome/Opera/Safari */
  text-align: left;
  direction: ltr;
}
.wizard-form ::-moz-placeholder { /* Firefox 19+ */
  text-align: left;
  direction: ltr;
}
.wizard-form :-ms-input-placeholder { /* IE 10+ */
  text-align: left;
  direction: ltr;
}
.wizard-form :-moz-placeholder { /* Firefox 18- */
  text-align: left;
  direction: ltr;
}

.progress-bar-title {
    font-weight: normal;
    margin-right: 0;
    margin-left: 9px;
    text-align: left;
    direction: ltr;
}


#progressbar { direction: ltr; }

#progressbar li button {
    padding-right: 0;
    padding-left: 35px;
    font-size: 13px;
    width: 160px;
	border-radius: 17px 0px 0px 18px;
}


#progressbar li button:after {
	left:auto;
	right: -18px;
	transform: scaleX(-1);
}

#progressbar li:last-child button:after {
	right: -23px;
	left: auto;
	
}

.chosen-rtl {
    text-align: left;
}


.wizard-form div.submit {
	direction:ltr;
}


.disconnect-area .logout-link {
    float: right;
    padding-right: 34px;
}

.wizard-select-theme .chosen-rtl .chosen-single {
    padding: 4px 0 4px 8px;
}

.chosen-rtl .chosen-single div {
    right: 13px;
    left: auto;
}


#wizard-form-step1 > div.submit {text-align:right !important;}

#progressbar li.active button {
    box-shadow: rgba(0, 0, 0, 0.3) -14px 3px 21px 0px;
    -moz-box-shadow: rgba(0, 0, 0, 0.3) -14px 3px 21px 0px;
    -webkit-box-shadow: rgba(0, 0, 0, 0.3) -14px 3px 21px 0px;
}

#progressbar li:last-child button {
	    padding-left: 58px;
        padding-right: 0;
}

#progressbar li.active button span {
    padding-right: 0px;
}

header .homepage-title-container { padding-right:0; }

header .homepage-title-container h2 {
     float: left;
    font-size: 30px;
    padding-left: 17px;
    padding-top: 11px;
}


header .homepage-title-container h3 {
	    margin-right: 0;
		float: right;
		font-size: 27px;
		margin-top: 26px;
		padding-right: 23px;
}



/* Step 1 */
#STEP1_SCHOOL_CODE { float: left; }
.wizard-form input#agree { left: -26px; right:auto !important; }
.terms-label { float:left;  padding-left: 16px; }


#wizard-form-step1.wizard-form input#agree + span:before { position:absolute !important; }

#wizard-form-step1.wizard-form input[type=checkbox]:checked + span:before {
    right: auto;
    left: -30px;
}

#wizard-form-step1.wizard-form input[type=checkbox] + span:before {
	position: absolute!important;
    top: -9px;
    right: auto;
    left: -34px;
}


body.page-template-wizard #wizard-form-step1 fieldset legend { left: -100%; }


/* Step 3 */
.wizard-filed-desc { text-align:left;  direction: ltr; }


/* Step 4 */
.wizard-form h2 { text-align: left;  direction: ltr; }

#wizard-form-step4 .checkbox-label {
    padding-right: 0;
    padding-left: 28px;
}


#wizard-form-step4 input[type=checkbox] + span:before {
    right: auto;
    left: 0;
}


#wizard-form-step4 > div:nth-child(13) > div:nth-child(2),
#wizard-form-step4 > div:nth-child(12) > div:nth-child(2),
#wizard-form-step4 > div:nth-child(11) > div:nth-child(2),
#quotes-repeater > div.col-sm-12,
#dictionary-repeater > div.col-sm-12 {
	text-align: left;
    direction: ltr;
}

<?php endif;?>


</style>

<div class="col-xs-12">
    <h3 class="progress-bar-title"><?php BH__e( 'העלאת סיפור בחמישה שלבים פשוטים:' , 'BH' , $locale); ?></h3>
	<ul id="progressbar">
        <?php foreach ( $wizard_steps_captions as $caption ): ?>
            <li class="btnStep<?php echo $i;?><?php echo ( $i == $wizardSessionManager->getCurrentStep() ) ? ' active ' : ''; ?><?php echo ( $wizardSessionManager->isStepAvailable( $i ) ) ? ' available ' : ''; ?>"  >
                <form name="step<?php echo $i; ?>" method="post">
                    <?php

                            if ( $wizardSessionManager->isStepAvailable( $i ) AND $i != $wizardSessionManager->getCurrentStep() )
                            {
                               echo ' <input type="hidden" name="progstep" value="' . $i .'" style="background:#fff;"/>';
                               echo '<button>';
                                    echo $caption;
                               echo '</button>';
                            }
                            else
                            {
                                echo '<button disabled>';
                                    echo '<span>' .  $caption . '</span>';
                                echo '</button>';
                            }
                    ?>
                </form>
            </li>
        <?php $i++; ?>
        <?php endforeach; ?>
	</ul>

<?php if ( is_user_logged_in() ): ?>
    <div class="col-sm-12 disconnect-area">
        <a class="logout-link" href="<?php echo wp_logout_url( home_url() ); ?>"><?php BH__e('התנתקות / זה לא המשתמש שלי' , 'BH' ,  $locale ); ?></a>
    </div>
<?php endif; ?>
</div>