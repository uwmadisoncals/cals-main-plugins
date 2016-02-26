jQuery.noConflict();

jQuery(document).ready(function() {

  // get the action filter option item on page load
  var jQueryfilterType = jQuery('#filterOptions li.active a').attr('class');

	
  // get and assign the ourHolder element to the
	// jQueryholder varible for use later
  var jQueryholder = jQuery('ul.ourHolder');
  var jQuerydescription = jQuery('.descriptions');
  // clone all items within the pre-assigned jQueryholder element
  var jQuerydata = jQueryholder.clone();
  
  // attempt to call Quicksand when a filter option
	// item is clicked
	jQuery('#filterOptions li a').click(function(e) {
		// reset the active class on all the buttons
		jQuery('#filterOptions li').removeClass('active');
		
		// assign the class of the clicked filter option
		// element to our jQueryfilterType variable
		var jQueryfilterType = jQuery(this).attr('class');
		jQuery(this).parent().addClass('active');
		

                  // show correct description
            jQuery('.descriptions h3').hide();
            
            jQuery('.descriptions').find('h3[cat-id="' + jQueryfilterType + '"]').fadeIn("Slow");
                
		if (jQueryfilterType == 'all') {
			// assign all li items to the jQueryfilteredData var when
			// the 'All' filter option is clicked
			var jQueryfilteredData = jQuerydata.find('li');
		} 
		else {
			// find all li elements that have our required jQueryfilterType
			// values for the data-type element

                              var jQueryfilteredData = jQuerydata.find('li[data-type~=' + jQueryfilterType + ']');                       			
		}
		
		// call quicksand and assign transition parameters
		jQueryholder.quicksand(jQueryfilteredData, {
			duration: 800,
			easing: 'easeInOutQuad'
		});
		return false;
	});

});

