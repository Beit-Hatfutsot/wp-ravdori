<?php
/**
 * The story uploader button with languages support
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="header-element languages-switcher">

	<div class="languages-switcher-content">

		<?php
			/**
			 * Display the languages switcher
			 */
			get_template_part( 'views/languages/header/wizard-languages-switcher/language', 'content' );
		?>

	</div>

</div>

