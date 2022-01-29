jQuery( document ).ready(function() {

	$ = jQuery;

	// translations
	loadTranslations();

	setTranslation();

	initCycle();

	$(".dropdown:not(.advanced-search-dropdown)").hover(
		function() {
			$('.dropdown-menu', this).stop( true, true ).fadeIn("fast");
			$(this).toggleClass('open');
			$('b', this).toggleClass("caret caret-up");
		},
		function() {
			$('.dropdown-menu', this).stop( true, true ).fadeOut("fast");
			$(this).toggleClass('open');
			$('b', this).toggleClass("caret caret-up");
		}
	);


	// Mobile fullscreen search
	$('.btn-mobile-search').on( "click", function() {
		$('#search-fullscreen-overlay').show();
		$('body').addClass('mobile-menu-open');
	});


	$('#search-fullscreen-overlay .closebtn').on( "click", function() {
		$('#search-fullscreen-overlay').hide();
		$('body').removeClass('mobile-menu-open');
	});


	$('.clean-adv-form-btn').on( "click", function() {
	   $('#advanced_search__country').prop('selectedIndex',0);
	   $(".advanced-search-container select").trigger("chosen:updated");

	   clearAdvancedSearchForm();
	});


	function clearAdvancedSearchForm() {
		$('#advanced-search-form input[type="text"]').val('');
		$('#advanced-search-form input[type="checkbox"]').prop("checked", false);
	}


	function updateCountry() {
		sortCountriesByTermName();
		$(".advanced-search-container select").trigger("chosen:updated");
	}


	function sortCountriesByTermName() {
		// Loop for each select element on the page.
		$('.advanced-search-container select').each(function() {
			// Keep track of the selected option.
			var selectedValue = $(this).val();
			// Sort all the options by text.
			$(this).html($('option', $(this)).sort(function(a, b) {
				return $(a).text() == $(b).text() ? 0 : $(a).text() < $(b).text() ? -1 : 1
			}));
			// Select one option.
			$(this).val(selectedValue);
		});
	}


	// Make the country select box searchable
	$(".advanced-search-container select").chosen( { placeholder_text_single :  rh_translation_arr.search_string, no_results_text:rh_translation_arr.no_results_text, search_contains: true  } );

});

/* loadTranslations */
function loadTranslations() {
	// vars
	var lang = js_globals.translations_lang,
		translations = JSON.parse(js_globals.translations);

	// get strings to be translated
	var strings = $('.bh-translate');

	if ( ! lang || ! translations || ! strings )
		return;

	strings.each(function() {
		// get string
		var dom = $(this),
			str = dom.html();

		if ( ! str )
			// continue
			return;

		// locate string in translations
		$.each(translations, function() {
			var entry = $(this),
				translation = '',
				toBeTranslated = false;

			$.each(entry[0], function( l, val ) {
				val = val.replace('&quot;', '"');

				if (l == lang) {
					// keep translation in case match is found
					translation = val;
				}
				else if (val == str) {
					// match is found - string should be translated
					toBeTranslated = true;
				}
			});

			if (toBeTranslated && translation.length) {
				// translate string
				dom.html(translation);

				// break
				return false;
			}
		});
	});
}


/* setTranslation */
function setTranslation() {
	// vars
	var choice = $('.language-switcher li');

	choice.click(function() {
		// vars
		var lang = $(this).attr('id');

		js_globals.translations_lang = lang;

		// switch language
		loadTranslations();

		// set cookie
		Cookies.set( 'bh_translations_lang', lang, { expires: 30 } );
	});
}


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