/*MIT License */
/*global jQuery */
(function ($) {
    'use strict';
    var definer = $('<div dir="rtl" style="font-size: 14px; width: 4px; height: 1px; position: absolute; top: -1000px; overflow: scroll">ABCD</div>').appendTo('body')[0],
        type = 'reverse';

    if (definer.scrollLeft > 0) {
        type = 'default';
    } else {
        definer.scrollLeft = 1;
        if (definer.scrollLeft === 0) {
            type = 'negative';
        }
    }

    $(definer).remove();
    $.support.rtlScrollType = type;
}(jQuery));

var origScrollLeft = jQuery.fn.scrollLeft;
jQuery.fn.scrollLeft = function(i) {
    var value = origScrollLeft.apply(this, arguments);
    if (i === undefined) {
        switch(jQuery.support.rtlScrollType) {
            case "negative":
                return value + this[0].scrollWidth - this[0].clientWidth;
            case "reverse":
                return this[0].scrollWidth - value - this[0].clientWidth;
        }
    }

    return value;
};