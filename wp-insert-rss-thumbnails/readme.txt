=== Insert RSS Thumbnails ===
Contributors: admn.bpg
Donate link: http://bonplangratos.fr
Tags: rss thumbnails,rss images,rss enclosure
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 4.3

Display image thumbnails in your feeds, that will be visible in RSS aggregators for your readers.

== Description ==

This plugin offers you the possibility to have a small icon, in front of each blog entries, news headlines, website articles... in your RSS / Atom feeds.

The image thumbnail is automatically detected by aggregators (like Netvibes) and is automatically displayed, which gives a more professional look to your RSS feed.

The plugin used the feed 'enclosure' tag functionality to display media.

Visit [bonplangratos.fr/wp/insert-rss-thumbnails](http://bonplangratos.fr/wp/insert-rss-thumbnails) for more details and features.

It is easy to use:<br />
- You upload a picture (ideally not to big) in your WP media library gallery<br />
- You specify the path of this picture in the meta-data field named 'thumbnail' in your posts<br />
- And that rocks

You do that for each post, and a thumbnail will be automatically added in front of each of your RSS articles.

You can also ask the plugin to look for images in your post or display a default image (if there is no thumbnail specified).

This plugin is fully compatible with the feedburner plugin, which redirects your main feed to feedburner.com and enable you to insert ads, track visitors...

= Plugin's Official Site =

[http://bonplangratos.fr](http://bonplangratos.fr/)



== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates
1. Specify the path of your thumbnails in the custom meta-data field named 'thumbnail' of each post.
1. Update the options (default thumbnails...) if needed

== Frequently Asked Questions ==

I do not see any changes:<br />
- RSS will be regenerated only when you update or create a post<br />
- Changes might not be immediately visible if you are using feedburner, which refreshes your feed evey 30 minutes. Use http://feedburner.google.com/fb/a/ping to force refresh.<br />

= Screenshots and tuto =
[Plugin official webpage](http://bonplangratos.fr/wp/insert-rss-thumbnails)

== Screenshots ==

Display image thumbnails in your feeds, that will be visible in RSS aggregators for your readers (like Netvibes here):

1. screenshot-1.jpg
2. screenshot-2.jpg

= More screenshots and tuto =
[Plugin official webpage](http://bonplangratos.fr/wp/insert-rss-thumbnails)

== Changelog ==

= 0.1 =
* Beta release

= 0.2 =
* I forgot one '}', this is corrected now (blocking issue during the plugin activation)
* new option: possibility to setup a relative path for thumbs URL

= 1.0 =
* optimization of the code
* search for an image in the post, if no thumbnail

== Upgrade Notice ==

