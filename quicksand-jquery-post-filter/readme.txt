=== Quicksand Post Filter jQuery Plugin ===


Contributors: developingtheweb
Donate link: http://www.developingtheweb.co.uk
Tags: quicksand, jquery filter, category filter, post filter
Requires at least: 3.0
Tested up to: 3.9.2
Stable Tag: 3.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Filter posts by their categories using the Quicksand jQuery filter plugin on any page. 

== Description ==

Filter posts by their categories using the Quicksand jQuery filter plugin on any page. 
You can select the categories you want to use in the admin area, choose to show post thumbs or titles.
Works will with other jQuery effects such as Overlays and Lightboxes (not included in this plugin).

Demo at http://www.developingtheweb.co.uk

== Installation ==

1. Unzip the package and upload to your /wp-content/plugins/ directory.
2. Log into WordPress and navigate to the "Plugins" panel and activate the Quicksand Filter plugin
3. In the admin menu under the settings options you will see a new link for QuicksandFilter.
5. This plugin works best with the latest Wordpress updates and HTML 5 Doc Type.
6. Make sure you have posts and categories set up in the post admin area.
7. Navigate to the QuicksandFilter menu option.
8. Click on the add new filter to add a post category to filter posts by,
9. Select whether to show category descriptions or not.
10. Select whether to show the list all posts tab/filter option.
11. Select whether the posts should display the featured image or not.
12. Select whether the posts should display the post title or not.
13. To delete a filter option click the remove link under the option
14. Set image sizes to override plugin defaults. These can also be overridden in custom css files.
15. To get the plugin to display on your selected page you can either:
			a - use the shortcode on the page in the admin dashobard which is [quicksand]
			b - open the template php file and add echo do_shortcode('[quicksand]'); 
			c - open the template php file and run the function  if (function_exists('quicksand')) {	quicksand(); } 

16. Style this plugin using your themes css file. Base css selectors can be easily overridden.

== Changelog ==

= 3.1 =

    1. Fixed bug with deleting an option

= 3 = 

    1. Updated for new wordpress version 3.9.2
    2. Rewritten to enable bolt ons and additions from 3rd parties
    3. Added image size choice option
    4. Added order alphabetically option
    5. Redesigned admin area
    6.

= 2.1 =

    1. Fixed several bugs which stopped updating of options when many filters where chosen.

= 2 =

    1. Total rewrite of the plugin.
    2. Now includes an easier to use admin area
    3. Duplicate post bug fix

= 1.7.2 =

	1. Fixed Quicksand js error new from 1.7.1 changes, stops multiple category filters.

= 1.7.1 =

	1. Read me text update, removed note that mentions posts will only be attributed to one category.
	2. Removed quicksand.js browser detection for older internet explorers. No longer supported by jQuery 1.9+

= 1.7 =

    1. Filter now shows posts that belong to more than one category in all related filters (compared to first one previously).
    2. Added option to show category descriptions.
    3. Added option to hide list all tab.
    4. Added option to show or hide post featured images.
    5. Added option to show or hide post titles.
    6. Few bugs fixed with first install of undefined variables.
    7. Removed post limit as this method only showed limit number from the first category.

= 1.6=

	1. 'All' filter option now does not contain all the post categories but only the posts from selected filter categories. Thanks to Sparksight for bringing this to my attention.

= 1.5 =

	1. Some category numbers caused the posts to dissapear when all 5 input categories where chosen. Error has been fixed with ghost empty category.

= 1.4 =

	1. Some versions of jQuery had issues with selecting the filter categories by category name, this was changed to use category id's instead.

= 1.3 =

 1. Plugin includes naming error has been fixed as the name from local to development was changed to suit wordpress repo, unlikely that any of the previous versions have worked up until this fix.

= 1.2 =

 1. Removed admin option to include jQuery as this is not neccessary. Wordpress core will handle if jQuery is already included from another plugin.

= 1.1 =

 1. Errors occured if post was in multiple categories. Plugin now chooses the posts first category to filter with.

