(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=1452911084999057";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
jQuery(document).ready(function($){
	var timelineBlocks = $('.ctl-social-timeline-block'),
		offset = 0.8;

	//hide timeline blocks which are outside the viewport
	hideBlocks(timelineBlocks, offset);

	//on scolling, show/animate timeline blocks when enter the viewport
	$(window).on('scroll', function(){
		(!window.requestAnimationFrame)
			? setTimeout(function(){ showBlocks(timelineBlocks, offset); }, 100)
			: window.requestAnimationFrame(function(){ showBlocks(timelineBlocks, offset); });
	});

	function hideBlocks(blocks, offset) {
		blocks.each(function(){
			( $(this).offset().top > $(window).scrollTop()+$(window).height()*offset ) && $(this).find('.ctl-social-timeline-img, .ctl-social-timeline-content').addClass('is-hidden');
		});
	}

	function showBlocks(blocks, offset) {
		blocks.each(function(){
			( $(this).offset().top <= $(window).scrollTop()+$(window).height()*offset && $(this).find('.ctl-social-timeline-img').hasClass('is-hidden') ) && $(this).find('.ctl-social-timeline-img, .ctl-social-timeline-content').removeClass('is-hidden').addClass('bounce-in');
		});
	}
	$(".share-toggle-button").click(function(){
			var el = $(this),
			newone = el.clone(true);
			el.before(newone);
			$(this).remove();
			$( "#ctl-social-timeline" ).find(".share-items").css( "display", "block" );
	});
});

/*
//<![CDATA[
jQuery(window).on('load', function() { // makes sure the whole site is loaded
	jQuery('#status').fadeOut(); // will first fade out the loading animation
	jQuery('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
	jQuery('.cool-social-timeline').delay(350).css({'overflow':'visible'});
});
//]]>
		*/