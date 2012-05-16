jQuery(function() {
	jQuery("input#add_to_global").on("change", function() {
		if(jQuery(this).is(":checked")) {
			jQuery("input.global-list").prop("disabled",false);
		}
		else {
			jQuery("input.global-list").prop("disabled",true);
		}
	});
	
	jQuery("div#global-list-info").qtip({
		content: "This is a global list for updates to Nerd Niters all over the world. There shouldn't be more than one or two emails a month and they'll be about Nerd Nite stuff, not random junk.",
		position: {
      		my: 'top left',  // Position my top left...
      		at: 'bottom left', // at the bottom right of...
      		target: jQuery("div#global-list-info") // my target
   		}
	});
	
	jQuery("li.NerdNiteSignup h2.widgettitle").bind('click',function(){
	    jQuery('div#signup-content').slideToggle();
	});
});