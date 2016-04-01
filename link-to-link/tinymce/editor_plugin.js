/**
 * $Id: editor_plugin.js
 *
 * @author Martin Fenner
 */

(function() {
	tinymce.PluginManager.requireLangPack('link2link');
	tinymce.create('tinymce.plugins.link2link', {
		init : function(ed, url) {
			this.editor = ed;
			// Register commands
			ed.addCommand('mceAddLinkLink', function() {
				var se = ed.selection;
				// No selection and not in link
				if (se.isCollapsed() && !ed.dom.getParent(se.getNode(), 'A'))
					return;
				var content = ed.selection.getContent();
				var re=/(<\/?p)(?:\s[^>]*)?(>)|<[^>]*>/gi;
				content = content.replace(re,'');				
				ed.windowManager.open({
					file : url + '/linktolink.php?validate=1&tri='+content + '&where=all&category=-1',
					width : 600 + parseInt(ed.getLang('link2link.delta_width', 0)),
					height : 500 + parseInt(ed.getLang('link2link.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('link_link', {
				title : 'Link to Link',
				image : url + '/../link_link.png',
				cmd : 'mceAddLinkLink'
			});
			ed.onNodeChange.add(function(ed, cm, n, co) {
				cm.setDisabled('link_link', co && n.nodeName != 'A');
				cm.setActive('link_link', n.nodeName == 'A' && !n.name);
			});
		},

		getInfo : function() {
			return {
				longname : 'link2link',
				author : 'Martin Fenner',
				authorurl : '',
				infourl : '',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('link2link', tinymce.plugins.link2link);
})();