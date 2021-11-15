<?php
/**
 * Show the story archive top toolbar
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2019 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
 
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<script>

$ = jQuery;

$(window).on('load', function () {
	
	// Need to make it more prettier and smart...
	
	
	// Make selects in chosen style
	$('#story-archive-sort-asc-desc').chosen( { disable_search: true,rtl: true  } );
	$('#story-archive-orderby').chosen( { disable_search: true,rtl: true  } );
	$('#story-archive-page-select').chosen( { disable_search: true,rtl: true  } );
	
	
	// If user has no viewing style selected yet, the default will be list	
	if ( Cookies.get('bh_stories_view_mode') === 'grid' ) {
		$('body').addClass('story-grid-view');
		Cookies.set('bh_stories_view_mode', 'grid', { expires: 7 });
		$('#story-archive-orderby').val("grid").trigger('chosen:updated');
	}
	else if ( Cookies.get('bh_stories_view_mode') === undefined || Cookies.get('bh_stories_view_mode') == 'list' || Cookies.get('bh_stories_view_mode') != 'list' ) 
	{
		$('body').addClass('story-list-view');
		Cookies.set('bh_stories_view_mode', 'list', { expires: 7 });
		$('#story-archive-orderby').val("list").trigger('chosen:updated');
	}
	

	
	$('#story-archive-orderby').on('change', function(e, params) {
		
		$('body').removeClass('story-list-view').removeClass('story-grid-view')
		
		if ( this.value == 'list') {
			$('body').addClass('story-list-view');
			Cookies.set('bh_stories_view_mode', 'list', { expires: 7 });
		}
		else if ( this.value == 'grid') {
			$('body').addClass('story-grid-view');
			Cookies.set('bh_stories_view_mode', 'grid', { expires: 7 });
		}
		else {
			$('body').addClass('story-list-view');
			Cookies.set('bh_stories_view_mode', 'list', { expires: 7 });
		}
    
	});
	
	
}); // Doc.ready


</script>
<?php  
// Get the current URL with it's params
$current_url = get_current_url(); 

// Get the ordering
$orderby = filter_input(INPUT_GET, 'orderby', FILTER_SANITIZE_STRING);

if ($orderby == FALSE OR in_array( $orderby, [STORY_GET_PARAM__NEW_STORIES, STORY_GET_PARAM__OLD_STORIES, STORY_GET_PARAM__TITLE_DESC, STORY_GET_PARAM__TITLE_ASC,STORY_GET_PARAM__BEST_MATCH] ) == FALSE ) {
	
	if ( $orderby == STORY_GET_PARAM__BEST_MATCH AND !is_search() ) {
		$orderby = STORY_GET_PARAM__NEW_STORIES;		
	}
	
	if ( !is_search() )
		$orderby = STORY_GET_PARAM__NEW_STORIES;	
	else {
		$orderby = STORY_GET_PARAM__BEST_MATCH;	
	}
}

?>
<div class="row header-toolbar-main">
	<div class="col-xs-12 col-md-3 col-lg-3 text-center header-toolbar-main__orderby">
		<form action="" method="get" class="wizard-form form-toolbar">
		<span class="wizard-select-theme">
			
			<label class="lblSelectAscDesc" for="selectAscDesc"><?php _e('מיון על פי:','BH'); ?></label>
		
			<select name="selectAscDesc" id="story-archive-sort-asc-desc" class="custom-select chosen-rtl" size="1" onchange="document.location.href = this.options[this.selectedIndex].value;">
			<?php if ( is_search() ): ?>
				<option value="<?php echo esc_url( add_query_arg('orderby',STORY_GET_PARAM__BEST_MATCH,$current_url)); ?>" <?php echo ($orderby == STORY_GET_PARAM__BEST_MATCH ? 'selected' : '')?>><?php _e('הכי רלוונטים','BH'); ?></option>
			<?php endif; ?>
			<option value="<?php echo esc_url( add_query_arg('orderby',STORY_GET_PARAM__NEW_STORIES,$current_url)); ?>" <?php echo ($orderby == STORY_GET_PARAM__NEW_STORIES ? 'selected' : '')?>><?php _e('הכי חדשים','BH'); ?></option>
			<option value="<?php echo esc_url( add_query_arg('orderby',STORY_GET_PARAM__OLD_STORIES,$current_url)); ?>" <?php echo ($orderby == STORY_GET_PARAM__OLD_STORIES ? 'selected' : '')?>><?php _e('הכי ישנים','BH'); ?></option>
			<option value="<?php echo esc_url( add_query_arg('orderby',STORY_GET_PARAM__TITLE_ASC  ,$current_url)); ?>" <?php echo ($orderby == STORY_GET_PARAM__TITLE_ASC  ? 'selected' : '')?>><?php _e('א,ב...','BH'); ?></option>
			<option value="<?php echo esc_url( add_query_arg('orderby',STORY_GET_PARAM__TITLE_DESC ,$current_url)); ?>" <?php echo ($orderby == STORY_GET_PARAM__TITLE_DESC  ? 'selected' : '')?>><?php _e('ת,ש...','BH'); ?></option>
			</select>
		</span>
		</form>
	</div>
	
	<div class="col-xs-12 col-md-6 col-lg-6 text-center bh-no-padding header-toolbar-main__pager">
	<?php  show_wp_pagenavi( $story_query, true, PAGINATION_STYLE__SELECT ); ?>
	</div>
	
	<div class="col-xs-12 col-md-3 col-lg-3 text-center header-toolbar-main__view">
	
	 <form action="" method="get" class="wizard-form  form-toolbar order-by-container">
		<span class="wizard-select-theme">
			
			<label class="lblSelectOrderBy" for="selectOrderBy"><?php _e('סידור על פי:','BH'); ?></label>
			
			<select name="selectOrderBy" id="story-archive-orderby" size="1" class="custom-select chosen-rtl chosen-selectOrderBy">
			<option value="list"><?php _e('שורות','BH'); ?></option>
			<option value="grid"><?php _e('טורים','BH'); ?></option>
			</select>
		</span>
	</form>
		
	</div>
</div>