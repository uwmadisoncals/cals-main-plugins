function getContentSelection(win){
	var word = '', sel, startPos, endPos;
	if (document.selection) {
		win.edCanvas.focus();
	    sel = document.selection.createRange();
		if (sel.text.length > 0) {
			word = sel.text;
		}
	}
	else if (win.edCanvas.selectionStart || win.edCanvas.selectionStart == '0') {
		startPos = win.edCanvas.selectionStart;
		endPos = win.edCanvas.selectionEnd;
		if (startPos != endPos) {
			word = win.edCanvas.value.substring(startPos, endPos);
		}
	}
	return word;
}

function insertLinkLink(elem,shortcode,cito){
	elem = jQuery(elem);
	var winder = window.top;	
	var href,title = '',text;
	var word = getContentSelection(winder);
	if(word.length == 0){
		var text = elem.text();
	}
	else{
		var text = word;
	}
	if(cito == 'on'){
	  var rel = document.relform.rel.value;
	}
	else{
		var rel = "";
	}
	if(shortcode == 'on'){
		var id = elem.attr('id');
		var link = "[cite source='doi']" + elem.attr('href') + '[/cite]';
	}
	else{
		var href = elem.attr('href');
		var title = elem.text();
		var link = '<a href="'+href+'" title="'+title+'" rel="'+rel+'">'+text+'</a>';	
	}

    winder.edInsertContent(winder.edCanvas, link);
	winder.tb_remove();
	return false;
}
function showFilter(){
	jQuery('.showFilter').css('display','none');
	jQuery('.filter').css('display','block');
}
function hideFilter(){
	jQuery('.showFilter').css('display','block');
	jQuery('.filter').css('display','none');
}