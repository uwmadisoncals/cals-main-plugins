=== EasyTube for Youtube & Vimeo ===
Contributors: paulmbain
Donate link: http://www.paulbain.com/donate/
Tags: youtube, google video, flash, video, vimeo

Requires at least: 1.5
Tested up to: 3.333
Stable tag: 1.10

Allows Wordpress users to easily embed YouTube and Google Videos using one simple tag and places a preview image of the YouTube videos in your RSS feed linked to the video.

== Installation ==

1. Copy easytube.php into your wordpress plugins folder, normally located
   in /wp-content/plugins/

2. Login to Wordpress Admin and activate the plugin


A wordpress player can be embedded in a post using a tag of the following
form:

[youtube:URL]

e.g. [youtube:http://www.youtube.com/watch?v=ZSWZng4zBJI]

The URL is the url found on YouTube for a particular video.

You can also set the dimensions:

[youtube:URL WIDTH HEIGHT]

e.g. [youtube:http://www.youtube.com/watch?v=ZSWZng4zBJI 300 200]

Same tag format for Vimeo and Google Vidoes but with googlevideo instead of youtube
e.g. [googlevideo:URL] [vimeo:URL]

You can also set global options in the EasyTube option menu.

== Screenshots ==
1. Options pane for EasyTube global settings.

== Support ==
For support please visit http://www.paulbain.com/

== Changelog == 

= 1.10 =
* Fixed an issue when embedding Vimeo content
* Switched the default size to be widescreen for YouTube
* Updated the licence to BSD 2-Clause

= 1.9 = 
* Updated to use normal full PHP tag rather than short tags, should work better on newer version of PHP
* Set the default video size to be medium widescreen

= 1.8 = 
* Added Vimeo

= 1.7 = 
* Fixed a massive bug relating to video sizes
* Added widescreen sizes

= 1.6 = 
* Fixed issues with medium player size
* Updated compatibility
* Added fullscreen mode

