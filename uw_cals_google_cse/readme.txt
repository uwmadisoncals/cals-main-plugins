=== UW CALS Google Custom Search Engine ===
Contributors: uwcals
Tags: uw, madison, wisconsin, cals, google, custom, search, engine, search engine
Requires at least: 2.8
Tested up to: 3.1
Stable tag: trunk

Replaces the default WordPress search with Google Custom Search Engine (CSE). 

== Description ==

Replaces the default WordPress search with Google Custom Search Engine (CSE) (http://www.google.com/cse/). An active Google CSE account is required to enable this plugin.

== Installation ==

1. Get a Google CSE account (if necessary)
	1. Register for a Google CSE account at http://www.google.com/cse/. Follow the 3 basic steps to set up the search engine.

1. Get Search Engine's unique ID
	1. Log in to your Google CSE account (if necessary)
	1. Get the Search Engine's unique ID (Go to Google Custom Search home > Manage your existing search engines > Your Search Engine's Control Panel
	1. Copy your Search Engine's Unique ID (e.g. '016039371683713681917:pyykxxxx-xx');

1. Set up the UW CALS Google CSE plugin
	1. Upload 'uw_cals_google_cse.php' to the '/wp_content/plugins/' directory 
	1. Activate the plugin through the 'Plugins' menu in WordPress
	1. In the WP Dashboard, go to Settings > UW CALS Google Custom Search
	1. Paste the Search Engine Unique ID in the indicated field
	1. Select a Search Results Page where search results will be displayed. Create a new page if necessary.
	
1. Test the search form to make sure it works (note: if your Search Engine is brand new, relevant results may take a while to show up)

1. Configure the look and feel of your Google CSE results
	The look and feel of the Search Form can be altered by adding new CSS rules to your style.css file in your WP theme. However, the search results are more easily customized by using the available tools in the "Look and Feel" section of the Google CSE's Control Panel
	
== Screenshots ==
1. UW CALS Google CSE plugin's configuration panel.
2. Google CSE results on "Search Results" page.

== Changelog ==
= 1.0 =
* Initial release.