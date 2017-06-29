=== Purge Varnish Cache ===
Contributors: devavi
Tags: varnish, purge, cache, caching, flush, speed, plugin, wp-cache, performance, fast, automatic
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 1.0.5
License: GPLv2 or later

Automate purge Varnish cache when post content on your site is created or modified and also allow you to purge Varnish cache manually.


== Description ==

Purge Varnish Cache provides integration between your WordPress site and multiple Varnish Cache servers. Purge Varnish Cache sends a PURGE request to the URL of a page or post every time when post content is created or modified. This occurs when editing, publishing, commenting or deleting an post item, menu items, front page and when changing themes. Varnish is a web application accelerator also known as a caching HTTP reverse proxy.

<strong>Features:</strong>

*   Minimum configuration.
*   admin-socket integration and Varnish admin interface for status etc.
*   Unlimited number of Varnish Cache servers
*   Configurable actions upon events that will expire URLs from varnish cache like reverse proxy caches.
	*   The front page.
	*   The post/page created/updated/status changed.
	*   Any categories or tags associated with the page.
	*   The menu created/updated.
	*   Changing theme.
*   Purge custom URLs.
*   Purge multiple URLs manually from Varnish cache.
*   Purge whole site cache manually.
*   Debugging.

<strong>Requirements:</strong> Apache sockets module/extention should be enabled.

<strong>Purpose:</strong> The main purpose of developing this plugin is to deliver updated copy of content to end user without any delay.

<strong>Enhancement Request:</strong> For any further enhancement, please mail me at <a href="mailto:dev.firoza@gmail.com"><strong>dev.firoza@gmail.com</strong></a>

== Installation ==

<strong>How to install Purge Varnish?</strong>

*   Go to your admin area and select Plugins -> Add new from the menu.
*   Search for "Purge Varnish" or download
*   Click install.
*   Click activate.

<strong>How to configure settings?</strong>

*   Access the link DOMAIN_NAME/wp-admin/admin.php?page=purge-varnish-settings and configure terminal settings.
*   Access the link DOMAIN_NAME/wp-admin/admin.php?page=purge-varnish-expire and configure required actions and events.
*   Access the link DOMAIN_NAME/wp-admin/admin.php?page=purge-varnish-urls for purge urls from varnish cache.
*   Access the link DOMAIN_NAME/wp-admin/admin.php?page=purge-varnish-all to purge all varnish cache.

== Frequently Asked Questions ==

<strong>How can I check everything's working?</strong>

It is not difficult. You should install this pluing and configure varnish terminal setting by accessing the link: DOMAIN_NAME/wp-admin/admin.php?page=purge-varnish-settings. If you have the status 'Varnish running' means everything iss working perfectly!

<strong>What versions of Varnish is supported?</strong>

it is supported all varnish versions of 3.x and 4.x

<strong>How do I manually purge a single URL from varnish cache?</strong>

Click the 'Purge URLs' link or access the link DOMAIN_NAME/wp-admin/admin.php?page=purge-varnish-urls. This interface allow you to purge 1 to 7 urls.

<strong>What if I have multiple varnish Servers/IPs?</strong>

You need to configure multiple IPs in Varnish Control Terminal textfield in 'Terminal' screen like 127.0.0.1:6082 127.0.0.2:6082 127.0.0.3:6082

<strong>How can I debug?</strong>

You need to add this constant <strong>define('WP_VARNISH_PURGE_DEBUG', true);</strong> in wp-config.php file. It will generate the log file 'purge_varnish_log.txt' inside uploads directory.

<strong>How do I manually purge the whole site cache?</strong>

Click on link 'Purge all' or access the link: DOMAIN_NAME/wp-admin/admin.php?page=purge-varnish-all and Click on 'Purge All' button.

<strong>What it purge?</strong>

Basically by default it purge nothing. It allow you to decide and configure expire setting. So you no need to worry. Click on 'Expire' link or access the link: DOMAIN_NAME/wp-admin/admin.php?page=purge-varnish-expire to configure purge expire setting.


== Screenshots ==

1. Terminal settings screen for test connectivity from varnish server. 

2. Action trigger configuration screen to make automate purge varnish cache for post expiration.

3. Action trigger configuration screen to make automate purge varnish cache for comment expiration.

4. Action trigger configuration screen to make automate purge varnish cache on menu update.

5. Action trigger configuration screen to make automate purge varnish cache on theme change.

6. Purge whole site cache.

7. Purge URLs screen to purge urls manually from varnish cache. 


== ChangeLog ==

= 1.0.4 =

Enable expire configuration automatically when plug in enabled.
Add more tags.
Update screens.

= 1.0.5 =

Purge Custom URLs
Update screens.

= Version 2.x =

* PHP 4.x/5.x compatibility.