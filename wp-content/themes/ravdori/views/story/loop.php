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

    echo $content;

    ?>
    <?php get_template_part('views/story/sidebar-data'); ?>
</div>
