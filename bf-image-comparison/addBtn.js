jQuery(document).ready(function($) {
    tinymce.create('tinymce.plugins.compare_img_shortcode_plugin', {
        init: function(ed, url) {
            ed.addCommand('compare_img_insert_shortcode', function() {
                var selected = tinyMCE.activeEditor.selection.getContent(),
                    content = '';
                if(selected) {
                    content = '[compare before="' + selected + '" after="" width="600px" height="400px"]';
                } else {
                    content = '[compare before="" after="" width="600px" height="400px"]';
                }
                tinymce.execCommand('mceInsertContent', false, content);
            });
            ed.addButton('compare_img_shortcode', {
                title: 'Insert Compare Images Shortcode',
                cmd: 'compare_img_insert_shortcode',
                icon: 'wp_code',
            });
        }
    });
    tinymce.PluginManager.add('compare_img_shortcode', tinymce.plugins.compare_img_shortcode_plugin);
});