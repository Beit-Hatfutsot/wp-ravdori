<?php
/**
 * The template for displaying the search form
 *
 * @author		HTMLine
 * @package		htmline-starter-child/views/components
 * @since		1.0.0
 * @version		1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Variables
 */
$search_string = __('חיפוש חופשי / Search', 'BH');

?>


<div id="search-fullscreen-overlay" class="overlay">
    
    <div class="overlay-content">
		<span id="mobile_close" class="closebtn" title="Close Overlay"><i class="icon-leyadhazrif-iconmonstr-x-mark-thin"></i></span>
		<div class="clearfix"></div>
	
        <form method="get" action="<?php echo HOME; ?>">
            <div class="no-left-margin mobile-search-form">
                <label for="fs-search-input" class="visually-hidden">ביטוי לחיפוש</label>
				<input type="text" class="search-field <?php echo ( isset( $_GET['s'] ) && $_GET['s'] ) ? 'active' : ''; ?>" placeholder="<?php echo $search_string; ?>" value="<?php echo ( isset( $_GET['s'] ) && $_GET['s'] ) ? $_GET['s'] : ''; ?>" name="s" id="fs-search-input"/>  
					<button class="btn" type="submit"><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span class="visually-hidden" aria-hidden="true" style="background: #3b3b3b;color: #fff;">ביצוע חיפוש</span></button>
            </div>
        </form>
		
		<?php if ( wp_is_mobile() ): ?>
			<div class="mobile-advanced-search visible-xs" aria-hidden="true">
				<?php get_template_part('views/components/advanced-search', 'form'); ?>
			</div>	
		<?php endif;?>
	</div>

	
	
</div>



