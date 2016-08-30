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

function insertWrap(){
	$(".bricklayer-column").each( function(i){
	
		$(this).addClass('added');
		//$(this).children().wrapAll("<div class='new' />");

 		if( $(this).find('.new').length = 0){

 			$(this).children().wrapAll("<div class='new' />");
 		}

	});
}

if($('body').is(".post-type-archive-team")){
	//$(".member_wrapper").wrapAll("<div class='new' />");
	
/*	$(window).resize(function(){
		var wWidth = $(window).width();
		//console.log("resized! :" + wWidth );

		if(wWidth >= 1200){

			//insertWrap();

			$(".site-content-inner").removeClass("orangie");
			$(".site-content-inner").removeClass("greenie");


		//}else if( wWidth <= 1199 && wWidth >= 980){
		}else if( wWidth = 1199 ){
			insertWrap();

			//console.log("its less than 1199  AND greater than ");

			$(".site-content-inner").removeClass("orangie");
			$(".site-content-inner").addClass("greenie");

		//}else if(wWidth <= 980 && wWidth >= 640){
		}else if(wWidth = 980){
			insertWrap();

			$(".site-content-inner").removeClass("greenie");
			$(".site-content-inner").addClass("orangie");

		}else{
			//insertWrap();
			$(".site-content-inner").removeClass("orangie");
		}
	});
*/

}