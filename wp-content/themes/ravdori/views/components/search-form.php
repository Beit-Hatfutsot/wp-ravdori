<?php $search_string = __(' חיפוש חופשי', 'BH'); ?>

<form id="search-form" class="navbar-form voffset3" role="search" method="get" action="<?php echo HOME; ?>">

    <div class="input-group add-on">

        <input  placeholder="<?php echo $search_string; ?>" name="s" id="search-field" type="text">
        <input type="hidden" name="post_type" value="<?php echo STORY_POST_TYPE; ?>" />

        <div class="input-group-btn">
            <button class="btn" type="submit"><i class="glyphicon glyphicon-search"></i></button>
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