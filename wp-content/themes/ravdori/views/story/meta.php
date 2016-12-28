<?php
/**
 * View of single story post meta data
 *
 * @package    views/story/meta
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>
<script src="<?php echo get_bloginfo('stylesheet_directory') . '/js/imageFitter.js'?>"></script>
<script>

    var $ = jQuery;

    var caption1 = '<?php echo  json_encode ( get_field('acf-story-images-adult-child-description') ); ?>';
    var caption2 = '<?php echo  json_encode ( get_field('acf-story-images-adult-past-description')  ); ?>';

    // Wait until all the images are loaded
    $(window).bind('load', function() {

        // cache the image container
        var $wrapper = $('.portrait-wrapper');

        fitImages( $wrapper.children().get(0), $wrapper.children().get(1), 20 , caption1  , caption2 );

        $('.mejs-container').each(function() {
            $(this).css( 'width' , 'auto').css('max-height' , '600px'); //.css( 'min-height' , '400px')
        });



    });


    /* Watch for changes in the <HTML> tag
    *  The MediaElementJs doesn't have a fullscreen event,
     * but it adds a class to the <html> tag, so we can check it instead.
    * */
    function registerObserver()
    {
        // select the target node
        var target = document.documentElement;

        // create an observer instance
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation)
            {
                if ( $('html').hasClass('mejs-fullscreen') ) // If fullscreen
                {
                    $('.mejs-container').each(function() {
                        $(this).css( 'width' , 'auto').css( 'height' , '400px').css('max-height' , 'initial');
                    });
                }
                else
                {
                    $('.mejs-container').each(function() {
                        $(this).css( 'width' , 'auto').css( 'height' , '400px').css('max-height' , '400px');
                    });
                }

            });
        });

        // configuration of the observer:
        var config = { attributes: true };

        // pass in the target node, as well as the observer options
        observer.observe(target, config);
    }


    $(document).ready(function()
    {

        registerObserver();

        /* Wrap every image with a div, to force it to be max 600px in height*/
        $('.entry img').each(function() {
            $(this).css('max-height','650px').css('margin','0 auto').css('width','auto');
        });
    });



</script>


<div class="row" style="margin-top: -9px;">
    <div class="col-sm-5"></div>
    <div class="col-sm-7">
        <?php get_template_part('views/social-networks/facebook'); ?>
        <?php get_template_part('views/components/print'); ?>
    </div>
</div>

<h2 class="title"><?php the_title(); ?></h2>


<?php $stories_meta = get_story_meta_data(); ?>

<?php foreach ( $stories_meta as $story_meta ): ?>

<div class="story-meta-title">
    <span>
        <?php if ( $story_meta['meta_data'] ): ?>
            <strong><?php echo $story_meta['meta_title']; ?></strong>
            <span class="<?php echo  $story_meta['class']; ?>"><?php echo $story_meta['meta_data']; ?></span>
        <?php endif; ?>
    </span>
</div>

<?php endforeach; ?>



<div class="portrait-wrapper text-center voffset4 container">

    <?php $imgChild =  wp_get_attachment_image( get_field('acf-story-images-adult-past') , 'full', false , array( 'class' => 'portrait' ) ); /* hide-img*/ ?>
    <?php echo $imgChild;?>

    <?php $imgAdult =  wp_get_attachment_image( get_field('acf-story-images-adult-child') , 'full', false , array( 'class' => 'portrait' ) ); /* hide-img*/ ?>
    <?php echo $imgAdult;?>

</div>



<?php $secondary_text = get_field('acf-story-secondary-text'); ?>
<?php if ( $secondary_text ): ?>
    <div class="col-sm-12 voffset2">
        <strong class="subtitle"> <?php echo $secondary_text; ?> </strong>
    </div>
<?php endif; ?>


<div id="lightbox" class="modal fade and carousel slide print-no" >
    <div class="modal-dialog">
        <button type="button" class="close " data-dismiss="modal" aria-hidden="true">×</button>
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="text-right">שליחת הסיפור בדואר אלקטרוני</h2>
                <hr>
                <div id="cf7-mail-story">
                    <?php echo do_shortcode('[contact-form-7 id="660" title="שליחת סיפור במייל"]'); ?>
                </div>
            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
