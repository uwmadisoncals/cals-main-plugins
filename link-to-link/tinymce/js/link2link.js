tinyMCEPopup.requireLangPack();

function insertLinkLink(elem,shortcode,cito){
	elem = $(elem);
	var ed = tinyMCEPopup.editor, dom = ed.dom, n = ed.selection.getNode();
	e = dom.getParent(n, 'A');
	if(e == null){
		if(cito == 'on'){
			var relid = document.getElementById('rel');
			var relval = relid.options[relid.selectedIndex].value;
			var reltext = " rel='" + relval + "'";
		}else{
			var relval = "";
			var reltext = relval;
		}
		if(shortcode == 'on'){
			var linktext = elem.attr('href');
			if (linktext.indexOf("http://dx.doi.org") != -1){
				linktext = linktext.substr(18);
				tinyMCEPopup.execCommand("mceInsertContent", false, "[cite source='doi'" + reltext + "]" + linktext + "[/cite]");
			}else{
				tinyMCEPopup.execCommand("mceInsertContent", false, "[cite source='url'" + reltext + "]" + linktext + "[/cite]");
			}
		}
		else{
			tinyMCEPopup.execCommand("CreateLink", false, "#mce_temp_url#", {skip_undo : 1});
			tinymce.each(ed.dom.select("a"), function(n) {
				if (ed.dom.getAttrib(n, 'href') == '#mce_temp_url#') {
					e = n;
					ed.dom.setAttribs(e, {
						title : elem.text()
					});
					ed.dom.setAttribs(e, {
						href : elem.attr('href')
					});
					ed.dom.setAttribs(e, {
						rel : relval
					});
				}
			});
		}
	}
	else{
			ed.dom.setAttribs(e, {
				title : elem.text()
			});
			ed.dom.setAttribs(e, {
				href : elem.attr('href')
			});
			if(cito == 'on'){
				var relid = document.getElementById('rel');
				var relval = relid.options[relid.selectedIndex].value
			}else{
				relval : ""
			}
			ed.dom.setAttribs(e, {
				rel : relval
			});	
	}
	tinyMCEPopup.close();
	return false;
}
function showFilter(){
	$('.showFilter').css('display','none');
	$('.filter').css('display','block');
}
function hideFilter(){
	$('.showFilter').css('display','block');
	$('.filter').css('display','none');
}