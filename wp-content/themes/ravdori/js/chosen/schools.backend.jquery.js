// Make the schhol's taxonomy parent select box into searchble select box
jQuery( document ).ready(function() {
	
	// sort list
	var my_options = jQuery("#parent option");
	my_options.sort(function(a,b) {
		if (a.text > b.text) return 1;
		else if (a.text < b.text) return -1;
		else return 0
	})
	//jQuery("#parent").empty().append(my_options);

	
    jQuery("#parent").addClass('chosen-rtl').chosen( { placeholder_text_single : "בחירת מדינות, ערים, מחוזות ובתי ספר", no_results_text: "לא נמצאו תוצאות ל - " , search_contains: true } );
	jQuery(".chosen-container").css('border' , '3px solid');
});
