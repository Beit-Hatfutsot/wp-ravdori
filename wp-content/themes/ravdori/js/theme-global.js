jQuery(function(){
    jQuery(".dropdown").hover(            
            function() {
                jQuery('.dropdown-menu', this).stop( true, true ).fadeIn("fast");
                jQuery(this).toggleClass('open');
                jQuery('b', this).toggleClass("caret caret-up");                
            },
            function() {
                jQuery('.dropdown-menu', this).stop( true, true ).fadeOut("fast");
                jQuery(this).toggleClass('open');
                jQuery('b', this).toggleClass("caret caret-up");                
            });
    
    });



jQuery( document ).ready(function() {
    
    initCycle();  
    
    // Mobile fullscreen search
    jQuery('.btn-mobile-search').on( "click", function() {
            jQuery('#search-fullscreen-overlay').show();
    });


    jQuery('#search-fullscreen-overlay .closebtn').on( "click", function() {
        jQuery('#search-fullscreen-overlay').hide();
    });



});




/* Cycle 2 */
function buildCarousel(visibleSlides) {
    jQuery('.homepage-carousel').cycle({
        fx: 'carousel',
        slides: '.carousel-slide',
        carouselVisible: visibleSlides,
        carouselFluid: true,
        swipe:true,
        swipefx:'scrollHorz',
        next: '#carousel-next',
        prev: '#carousel-prev',
    });
    
    //$('.slideshow li img').css('opacity','1');
}


function initCycle() {
    var width = jQuery(document).width();
    var visibleSlides = 3;
    
    if (width <= 766) {
        visibleSlides = 1;
    } else if (width >= 767 && width <= 991) {
        visibleSlides = 2;
    } else if (width >= 992 && width <= 1199) {
       visibleSlides = 3;
        } else if (width > 1200 && width < 1920) {
        visibleSlides = 3;
    } else {
        visibleSlides = 3;
    }

    buildCarousel(visibleSlides);
}

function reinit_cycle() {
    var width = jQuery(window).width();
    
    
    var destroyCarousel = function() { 
        jQuery('.homepage-carousel').cycle('destroy');
    }

    if (width <= 766) {
        destroyCarousel();
        reinitCycle(1);
    } else if (width > 767 && width < 991) {
        destroyCarousel();
        reinitCycle(2);
    } else if (width > 992 && width < 1199) {
        destroyCarousel();
        reinitCycle(3);
        } else if (width > 1200 && width < 1920) {
        destroyCarousel();
        reinitCycle(3);
    } else {
        destroyCarousel();
        reinitCycle(3);
    }
}

function reinitCycle(visibleSlides) {
    buildCarousel(visibleSlides);
}
var reinitTimer;
jQuery(window).resize(function() {
    clearTimeout(reinitTimer);
    reinitTimer = setTimeout(reinit_cycle, 100);
});

