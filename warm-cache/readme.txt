=== Warm Cache ===
Contributors: ramon fincken
Tags: cache, warm, keep, xml, sitemap, load, speed, quick, tag, w3tc, optimize, page cache, preload, google, pagespeed, webmaster, generator, warmup, cold, expire, expired, google, seo, caching, refresh, varnish, nginx, fresh, preload, preloader
Requires at least: 3.0
Tested up to: 4.4.2
Stable tag: 1.9.4

Crawls your website-pages based on your google XML sitemap. If you have a caching plugin or Varnish/nginx caching this will keep your cache warm. Speeds up your site.

== Description ==

Crawls your website-pages based on any XML sitemap. If you have a caching plugin this will keep your cache warm. 
Speeds up your site.<br>
Compatible with following elements: < sitemap > < url ><br>
All urls in your sitemap will be visited by the plugin to keep the cache up to date.<br>
Will show average page load times and pages visited.<br>

Needs google XML sitemap, Yoast SEO or any decent XML sitemap generator to create the sitemap XML file.<br>
Needs a cronjob (wget or curl) to call the plugin. You need to setup the cronjob yourself! Ask your sysadmin to help you or buy a lifetime subscription at <a href="http://webshop.mijnpress.nl/shop/cronjob-for-warm-cache">our cronjob service</a>.<br>
* Coding by <a href="http://www.mijnpress.nl" title="MijnPress.nl WordPress ontwikkelaars">MijnPress.nl</a><br>
* Crawl script idea by <a href="http://blogs.tech-recipes.com/johnny/2006/09/17/handling-the-digg-effect-with-wordpress-caching/">http://blogs.tech-recipes.com/johnny/2006/09/17/handling-the-digg-effect-with-wordpress-caching/</a><br>
<a href="https://www.managedwphosting.nl/contact/offerte-op-maat/">I offer paid WordPress speed-optimisation consultancy and high-speed Varnish & memcached WordPress webhosting.</a>

== Installation ==

1. Upload directory `warm-cache` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Visit Plugins menu to view your Warm cache options.

== Frequently Asked Questions ==

= How to run a cronjob? =
> Ask your webhost how to set up a get call using wget or curl
<a href="http://webshop.mijnpress.nl/shop/cronjob-for-warm-cache">Or use our cronjob service</a>

= I have set up the cronjob but the stats table on the plugin page remains empty. =
> If you have object caching such as W3 total cache, the statistics cannot be read.<br>
A help topic about this is placed <a href="http://wordpress.org/support/topic/plugin-w3-total-cache-strange-transient-problem?replies=1">on the support forums</a>.
Note that the script is crawling your XML file (check your webhosts access log), but you cannot see the statistics.

= I have a loadbalancer or .. the plugin keeps telling me it crawled 0 pages =
> I you have a correct XML sitemap, please toggle the Flush settings

= I have a lot of questions and I want support where can I go? =
> The support forums over here, drop me a tweet to notify me of your support topic over here.<br>
I always check my tweets, so mention my name with @ramonfincken and your problem.

== Changelog ==
= 1.9.4 =
Added: Debug notices when a sitemap is incorrect

= 1.9.3 =
Needs re-work: Bugfix: Transients without expiration (if you have no external non-persistant storage your options table will grow), Props M. Bastian

= 1.9.2 =
Added: Toggle flush settings

= 1.9.1 =
Added: Transient check for correct syntax of sitemap<br>
Added: Admin notices when sitemap is not present or currupt<br>

= 1.9 =
Changed: Better handling of pre-checks<br>
Changed: Changed API key change message

= 1.8.1 =
Added: Flush to prevent loadbalancer/proxy timeout see https://wordpress.org/support/topic/needs-flush-to-write-buffers-to-prevent-timeouts

= 1.8 =
Added: Sitemap url override<br>
Changed: Info txt in plugin<br>
Changed: Refresh random token<br>
Added: Cron service link

= 1.7 =
Bugfix: Extra if/else for zero pages to fix x/0 errors. Thanks to khromov http://wordpress.org/support/topic/division-by-zero-2 http://wordpress.org/support/profile/khromov

= 1.6 =
Added: Support for sub-sitemaps using < sitemap > format (as used in Beta of Google XML sitemaps). Thanks to Pascal90.de!

= 1.1.2 =
Changed: Random password call as mentioned by swanzai http://wordpress.org/support/topic/plugin-warm-cache-how-to-call-this-plugin-correctly

= 1.1 =
First release

== Screenshots ==

1. Details
<a href="http://s.wordpress.org/extend/plugins/warm-cache/screenshot-1.png">Fullscreen Screenshot 1</a><br>
2. Overview
<a href="http://s.wordpress.org/extend/plugins/warm-cache/screenshot-2.png">Fullscreen Screenshot 2</a><br>
