document.onmousemove = function(event) {
	var c = event.target;
	if (c.className == "compare_img") {
		for (var i = 0; i < c.childNodes.length; i++) {
		    if (c.childNodes[i].className == "img divisor") {
		    	event.target.childNodes[i].style.width = event.offsetX + 'px';
		    	break;
		    }
		}		
	}
}