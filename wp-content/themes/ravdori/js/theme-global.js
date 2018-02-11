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
    