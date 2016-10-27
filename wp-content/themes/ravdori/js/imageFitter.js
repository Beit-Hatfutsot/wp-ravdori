var $ = jQuery;

// put it in a function so you can easily reuse it elsewhere
function fitImages(img1, img2, gutter , caption1 , caption2 ) {

    var imagesContainerWidth = 730; //$('.portrait-wrapper').width();

    // turn images into jQuery objects (so we can use .width() )
    var $img1 = $(img1);
    var $img2 = $(img2);
    // calculate the aspect ratio to maintain proportions
    var ratio1 = $img1.width() / $img1.height();
    var ratio2 = $img2.width() / $img2.height();
    // get the target width of the two images combined, taking the gutter into account
    var targetWidth = $img1.parent().width() - gutter;

    // calculate the new width of each image
    var width1 = targetWidth / (ratio1+ratio2) * ratio1;
    var width2 = targetWidth / (ratio1+ratio2) * ratio2;


    // set width, and height in proportion
    $img1.width(width1);
    $img1.height(width1 / ratio1);
    $img2.width(width2);
    $img2.height(width2 / ratio2);

    // add the gutter
    $img1.css('paddingRight', gutter + 'px');

    // Set the container height to be the same as the images
    $img1.parent().height( $img2.height() );

  //  var caption1 = 'test';//'<?php echo  ( get_field('acf-story-images-adult-child-description') ); ?>';
//    var caption2 = 'trtr';//'<?php echo  ( get_field('acf-story-images-adult-past-description')  ); ?>';

    if ( caption1 != '' && caption2 != '' ) {
        addCaption($img2, caption1, gutter, 'fig1', true);
        addCaption($img1, caption2, gutter, 'fig2', false);
    }

    heighestCaption = ( Math.max( $('#fig1').height() , $('#fig2').height() ) );

    $img1.parent().height ( $img1.parent().height() + heighestCaption );

    // Show the images after the manipulations
    $img1.removeClass('hide-img');
    $img2.removeClass('hide-img');

}

function addCaption ( $imgElem , imgCaption , gutter , imgId  , isRight )
{
    var imgStyle = 'position: absolute;';
    imgStyle += 'top:' + $imgElem.height() + 'px;';
    imgStyle += 'max-width:' + $imgElem.width() + 'px;';
    imgStyle += 'padding:0;width:100%;';

    if ( isRight )
        imgStyle += 'right:' +  /*gutter*/ '19' + 'px;';
    else
        imgStyle += 'left:0px;';

    $figureElement  = '<figure id="' + imgId + '" class="wp-caption portrait" itemtype="http://schema.org/ImageObject" style="' + imgStyle + '">';
    $figureElement +=       '<figcaption class="wp-caption-text" itemprop="description">';
    $figureElement +=           imgCaption.replace(/^"(.*)"$/, '$1'); // remove quotes at beginning and end
    $figureElement +=       '</figcaption>';
    $figureElement += '</figure>';

    $imgElem.parent().append( $figureElement );
}
