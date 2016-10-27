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

 <?php $j =  $wizardSessionManager->getCurrentStep() - 1?>



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
</style>

<div class="col-xs-12">
    <h3 class="progress-bar-title"><?php _e( 'העלאת סיפור בחמישה שלבים פשוטים:' , 'BH' ); ?></h3>
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
        <a class="logout-link" href="<?php echo wp_logout_url( home_url() ); ?>">התנתקות / זה לא המשתמש שלי</a>
    </div>
<?php endif; ?>
</div>