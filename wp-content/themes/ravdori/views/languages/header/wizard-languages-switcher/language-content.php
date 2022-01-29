<?php
/**
 * The story uploader button with languages support
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<?php $locale = get_language_locale_filename_by_get_param(true); ?>

<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle btn-story-upload" type="button" data-toggle="dropdown" data-hover="dropdown" <?php echo ( $locale[ 'get_param_value' ] == 'ru' ? ' style="font-size: 14px;"' : ''); ?>>
<?php $locale = get_language_locale_filename_by_get_param(); ?> 
 <span class="bh-translate"><?php BH__e('העלאת סיפור','BH',$locale)?></span>
  	<?php
		/**
		 * Display the current language flag as the switcher button
		 */
		 get_template_part('views/languages/header/wizard-languages-switcher/language','current');
	?>
	<span class="caret"></span>
  </button>

<ul class="languages-switcher dropdown-menu">

	<?php

	// Get the ISupportedLanguages interface's langauge local file name according to the get param "lang"
	$oClass = new ReflectionClass ('ISupportedLanguages');
	$all_languages = $oClass->getConstants();
	unset ($oClass);
	
	$wizard_page_url = get_field( 'acf-options-wizard-page-url' , 'options' );
	
	foreach ( $all_languages as $language ): ?>
		<li>
			<a href="<?php echo $wizard_page_url .'?lang=' . $language[ 'get_param_value' ];  ?>" 
			<?php echo ( $language[ 'get_param_value' ] == 'ru' ? ' style="font-size: 15px; padding: 16px 19px;"' : ''); ?>
			>
				<div class="flag">
					<img src="<?php echo IMAGES_DIR . '/general/header/flag-' . $language[ 'get_param_value' ] . '.png'?>">
				</div>

				<div class="language-name"><?php echo $language["upload-story-name"]; ?></div>
			</a>
		</li>

	<?php endforeach; ?>

</ul>

</div>