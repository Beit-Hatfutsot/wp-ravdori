<div class="entry">
    <?php

    $content = get_the_content(); //
    $content = apply_filters('the_content', $content);


    // Remove the <br> tag
    $content = preg_replace( '/<br[^>]*>/i' , '' , $content );
    $content = preg_replace( '/<\/br>/i'    , '' , $content );

    // Remove font-family
    $content = preg_replace("/font-family\:.+?;/i", "", $content );

    $content = preg_replace("/font-size\:.+?;/i", "", $content );

    $content = preg_replace("/line-height\:.+?;/i", "", $content );

    $content = stripslashes( $content );
	
	// Remove empty <a>
	//$content = preg_replace('/<a[^>]*>(\s|&nbsp;)*<\/a[^>]*>/', '', $content);

	// Remove the video shortcode text
	$content = preg_replace('/\[video.*?\].*?\[\/video\]/', '', $content);
	
	// Add alts for images without it
	$alt_counter = 1;
	$content = preg_replace_callback('/<img([^>]*)alt=""([^>]*)>/i', function ($matches) use (&$alt_counter) { return '<img' . $matches[1] . 'alt="תמונה ' . $alt_counter++ . '"' . $matches[2] . '>';},$content);
	


    echo $content;

    ?>
    <?php get_template_part('views/story/sidebar-data'); ?>
</div>
