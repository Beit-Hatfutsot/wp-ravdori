// Make the schhol's taxonomy parent select box into searchble select box
jQuery( document ).ready(function() {
	
	// sort list
	var my_options = jQuery("#parent option , #schools option");
	my_options.sort(function(a,b) {
		if (a.text > b.text) return 1;
		else if (a.text < b.text) return -1;
		else return 0
	})
	//jQuery("#parent").empty().append(my_options);

	
    jQuery("#parent").addClass('chosen-rtl').chosen( { placeholder_text_single : "בחירת מדינות, ערים, מחוזות ובתי ספר", no_results_text: "לא נמצאו תוצאות ל - " , search_contains: true } );
    
    // Admin post type edit page (all stories list)
    jQuery("#schools").addClass('chosen-rtl').chosen( { placeholder_text_single : "בחירת מדינות, ערים, מחוזות ובתי ספר", no_results_text: "לא נמצאו תוצאות ל - " , search_contains: true } );

    // Admin post type edit page (all adults)
    jQuery("#author_admin_filter").addClass('chosen-rtl').chosen( { placeholder_text_single : "סינון לפי מבוגר", no_results_text: "לא נמצאו תוצאות ל - " , search_contains: true } );


	// Admin post type edit page (all subjects)
    jQuery("#subjects").addClass('chosen-rtl').chosen( { placeholder_text_single : "סינון לפי נושאים", no_results_text: "לא נמצאו תוצאות ל - " , search_contains: true } );


	// Admin post type edit page (all topics)
    jQuery("#subtopics_taxonomy").addClass('chosen-rtl').chosen( { placeholder_text_single : "סינון לפי נושאי משנה", no_results_text: "לא נמצאו תוצאות ל - " , search_contains: true } );


	// Admin post type edit page (all dates)
    jQuery("#filter-by-date").addClass('chosen-rtl').chosen( { placeholder_text_single : "סינון לפי נושאי משנה", no_results_text: "לא נמצאו תוצאות ל - " , search_contains: true } );


    // Admin story post backend (author)
    jQuery("#post_author_override").addClass('chosen-rtl').chosen( { placeholder_text_single : "שם המבוגר שכתב את הסיפור", no_results_text: "לא נמצאו תוצאות ל - " , search_contains: true } );

    jQuery('.tablenav .actions').css( 'overflow' , 'visible');


	jQuery(".chosen-container").css('border' , '3px solid');
});



jQuery(window).load(function() {
 	jQuery("#post_author_override_chosen").css('width','100%');
}); // Window.load
