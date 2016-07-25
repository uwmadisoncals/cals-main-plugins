//instantiate Bricklayer object if .bricklayer is used on page
if(document.querySelector('.bricklayer')){

	var bricklayer = new Bricklayer(document.querySelector('.bricklayer'));

}

// Execute js on page single-team.php (from plugin template)
if($('body').is(".cals_team-single-team")){

	//get length of .entry-title contents
	var titleLen = $("h1.entry-title").html().length;

	//if .entry-title has contents remove first-name and last-name
	if( titleLen > 0 ){

		$(".short-vals-container").each( function(){
		
			$("span.team-item-label:contains(First Name)").parent().css("display","none");

			$("span.team-item-label:contains(Last Name)").parent().css("display","none");

		});
	}
}