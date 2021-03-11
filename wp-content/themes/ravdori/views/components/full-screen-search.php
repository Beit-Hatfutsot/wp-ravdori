<?php
/**
 * The template for displaying the search form
 *
 * @author		HTMLine
 * @package		htmline-starter-child/views/components
 * @since		1.0.0
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Variables
 */
$search_string = __(' חיפוש חופשי', 'BH');

?>


<div id="search-fullscreen-overlay" class="overlay">
    
    <div class="overlay-content">
		<span class="closebtn" onclick="closeSearch()" title="Close Overlay"><i class="icon-leyadhazrif-iconmonstr-x-mark-thin"></i></span>
		<div class="clearfix"></div>
	
        <form method="get" action="<?php echo HOME; ?>">
            <div class="no-left-margin mobile-search-form">
                <input type="text" class="search-field <?php echo ( isset( $_GET['s'] ) && $_GET['s'] ) ? 'active' : ''; ?>" placeholder="<?php echo $search_string; ?>" value="<?php echo ( isset( $_GET['s'] ) && $_GET['s'] ) ? $_GET['s'] : ''; ?>" name="s" />  
					<button class="btn" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </form>
		
		<div class="mobile-advanced-search visible-xs">
			<?php get_template_part('views/components/advanced-search', 'form'); ?>
		</div>	
	</div>

	
	
</div>



