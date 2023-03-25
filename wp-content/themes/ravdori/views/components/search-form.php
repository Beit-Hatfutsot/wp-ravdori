<?php $search_string = __('חיפוש חופשי / Search', 'BH'); ?>

<form id="search-form" class="navbar-form voffset3" role="search" method="get" action="<?php echo HOME; ?>">

    <div class="input-group add-on">
		<label for="search-field" class="visually-hidden">חיפוש</label>
        <input  placeholder="<?php echo $search_string; ?>" name="s" id="search-field" type="text" value="<?php echo ( is_search() ? get_search_query() : '' ); ?>">
        <input type="hidden" name="post_type" value="<?php echo STORY_POST_TYPE; ?>" />

        <div class="input-group-btn">
            <button class="btn" type="submit"><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span class="visually-hidden" aria-hidden="true" style="background: #3b3b3b;color: #fff;">ביצוע חיפוש</span></button>
        </div>
		<?php
		/*
		<input type="checkbox" name="exactsearch" id="exactsearch" value="true" checked style="margin-top: 11px;"> 
		<label style="position: absolute;width: 60px;font-size: 10px;top: 6px;padding-right: 3px;">
			חיפוש מדויק 
		</label>			
		*/
		?>
    </div>
</form>