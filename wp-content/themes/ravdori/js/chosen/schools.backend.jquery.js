// Make the schhol's taxonomy parent select box into searchble select box
jQuery( document ).ready(function() {
    jQuery("#parent").addClass('chosen-rtl').chosen( { placeholder_text_single : "בחירת מדינות, ערים, מחוזות ובתי ספר", no_results_text: "לא נמצאו תוצאות ל - "  } );
	jQuery(".chosen-container").css('border' , '3px solid');
});
