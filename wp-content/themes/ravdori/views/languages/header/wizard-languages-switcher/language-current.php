<?php
/**
 * current language
 *
 * Display current language as the button for the languages switcher
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php $locale = get_language_locale_filename_by_get_param(true); ?>
<div class="languages-switcher-btn">
		<img src="<?php echo IMAGES_DIR . '/general/header/flag-' . $locale['get_param_value'] . '.png'; ?>" />
</div>