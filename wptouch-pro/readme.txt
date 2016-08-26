=== Plugin Name ===
Requires at least: 4.0
Stable tag: 4.1.8

= Version 4.1.8 (July 19, 2016) =

* Updated: Spanish translations
* Fixed: Search behaviour in some languages
* Fixed: Better compatibility with multisite

= Version 4.1.7 (July 5, 2016) =

* Fixed: Bug preventing some desktop theme shortcode content from displaying
* Fixed: Display issues with RTL menus and some themes
* Fixed: Conflicting JavaScript in some themes

= Version 4.1.6 (June 30, 2016) =

* Added: Desktop shortcode compatibility output now supports paginated posts and pages
* Updated: Autosave in admin panel is now a little more robust and optimized.
* Changed: Replaced library used for off-canvas menu in some themes (now using Slideout)

= Version 4.1.5 (June 17, 2016) =

* Added: Theme browser displays more details (long description, changelog) for custom themes
* Added: Option to choose which page the featured slider is shown on (Bauhaus, certain configurations)
* Added: Ability to delay desktop theme shortcode processing by defining a value for the WPTOUCH_SHORTCODE_TIMING constant in wp-config.php (allows plugins/themes with late 'init' hooks to add shortcode handlers)
* Changed: Auto-expand menu section if on a page within that section
* Changed: Reduced frequency of API calls in admin
* Changed: Cache warning is no longer displayed for sites running W3 Total Cache if a user agent group has been enabled to pass through mobile requests. As it is incompatible with this cache configuration, the desktop/mobile theme switch is disabled for sites using this cache configuration.
* Fixed: Autoplay slider was stopping on slide 2 when 'Repeat slides' was disabled
* Fixed: Bug preventing mobile template selector from being displayed
* Fixed: Incorrect XML sitemap output when Featured Slider was enabled
* Updated: Italian, Portuguese, Spanish translations

= Version 4.1.3 (May 26, 2016) =

* Added: Support for new setting types in admin
* Added: Descriptive labels throughout Customizer
* Added: Improved RTL support for featured slider
* Changed: Only display icon selection tool on items already saved to the menu (WordPress limitation)
* Changed: Replaced library used for featured slider (now using Owl Carousel)
* Changed: No icon selection shown for pending items (cannot select icons until item saved in menu)
* Changed: Only offer mobile template selector after the setup wizard has been completed
* Changed: Removed JavaScript alerts on multisite deployment and child theme creation
* Changed: Priority of theme filter for WordPress customizer when editing the mobile theme
* Fixed: Icon selector not fully overlaying other menu items when they've been expanded
* Fixed: Sites running child themes of Bauhaus can set featured slider type
* Fixed: No longer attempt to process desktop theme shortcodes on WooCommerce account page.
* Fixed: Table handling in several themes (no longer scroll the full content area)
* Fixed: Warnings when featured slider finds no content to display
* Fixed: Menu fields showing through icon picker
* Fixed: Display of long (non-breaking) titles in next/previous links
* Fixed: Multisite setting deployment could fail when a selected subsite hadn't completed the setup wizard
* Fixed: 'Save settings' spinner triggered when non-saving actions were taken
* Updated: Hebrew translation

= Version 4.1.2 (May 3, 2016) =

* Fixed: Auto-disable free plugin when WPtouch Pro is activated
* Fixed: Ensure correct theme active when downgrading from WPtouch Pro
* Updated: Translations for Arabic, French, Indonesian, Japanese, Portuguese

= Version 4.1.1 (April 11, 2016) =

* Added: Compatibility with WordPress 4.5
* Added: Setting to control which side the Bauhaus menu is shown on
* Added: Support for WPTOUCH_CLIENT_MODE constant to hide license page in admin menu
* Fixed: Bug with custom latest posts page when not set to the same as WordPress homepage
* Fixed: Search forms now allow searching while in preview mode
* Fixed: Eliminated PHP warnings
* Changed: If theme is installed, allow site admin to activate it, even if they cannot install new plugins/files
* Changed: Adjusted colour conversion for Luma-based colouring, ensuring true value is used
* Changed: Re-added 'show login' setting for sites that require users to be registered to comment.
* Changed: Spacing for AMP extension icon in admin tabs

= Version 4.1 (March 24, 2016) =

* Fixed: An issue where available themes may not show in the wizard when setting up the plugin
* Fixed: Custom themes with parentheses in the name were not selectable
* Fixed: Case where featured posts would always be repeated in listing if featured slider was set to show only 1 post
* Fixed: Error when page was not found
* Changed: Eliminated wizard reload after theme selection
* Added: Updates Available tab also installs WPtouch Pro updates when available
* Added: Greater compatibility with Storefront Customizer plugin
* Added: Better shortcode handling for sites mixing desktop theme and plugin shortcodes
* Added: New filters to support AMP extension
* Updated: Opera users agents to better specify supported opera versions and platforms
* Updated: Owl Carousel module assets to latest version

= Version 4.0.18 (February 8, 2016) =

* Fixed: WPtouch Pro menu no longer appears in Network Admin if plugin is not network activated
* Fixed: Multisite-aware content URLs for extensions (Advanced Type, Web App Mode)

= Version 4.0.17 (January 28, 2016) =

* Fixed: WPML languages in cart preview (MobileStore)
* Fixed: Retain WPML language parameter when redirecting to mobile landing page
* Fixed: Load more posts on custom posts page did not load posts in certain configurations
* Fixed: Web App Mode-related error in MobileStore theme.
* Fixed: Default link color applied to Related Posts in CMS
* Fixed: Parent theme JavaScript was not loading when child theme was active
* Changed: More consistent menu references in CMS

= Version 4.0.16 (January 19, 2016) =

* Fixed: Toggle fields not saving on some server configurations
* Fixed: Improve featured slider compatibility
* Fixed: Black field on blog index when featured slider is disabled.
* Changed: Allow new settings to have defaults recognized in the Customizer
* Changed: Homepage Redirect target is loaded in Customizer instead of standard homepage when set

= Version 4.0.15 (January 5, 2016) =

* Fixed: Styling of RTL admin updates available page
* Fixed: Menu icon selection when using relative-protocol media URLs
* Fixed: License entry form
* Fixed: Custom landing page redirect to custom latest posts page redirect loop

= Version 4.0.14 (December 29, 2015) =

* Added: On upgrade, migrate sites using the old "WordPress Pages" menu option to a custom WordPress menu
* Fixed: Custom Latest Posts Page handling
* Changed: Reduced cases when customizer cookie is set
* Changed: Fixed a case when the Featured Slider div was output even if disabled

= Version 4.0.13 (December 22, 2015) =

* Fixed: Minor RTL display issues in admin panel
* Fixed: Improper upgrade listing after upgrading to 4.x
* Fixed: Some websites' configuration caused incorrect links to uploaded images
* Fixed: Improved multisite compatibility for theme & extension updates
* Fixed: Occasionally improper filtering of posts outside the mobile theme

= Version 4.0.12 (December 3, 2015) =

* Changed: Now filtering sticky posts from the featured slider when a vategory or tag is chosen as the source
* Fixed: Desktop/mobile switch occasionally did not continue to apply when navigating

= Version 4.0.11 (November 24, 2015) =

* Changed: If plugin is network-activated, no longer show the license page in subsite admin menus.
* Fixed: More robust menu initialization
* Fixed: Better support for old themes
* Fixed: Compatibility with plugins adding custom metaboxes to admin

= Version 4.0.10 (November 13th, 2015) =

* Fixed: Security nonce used by AJAX requests
* Changed: Improvements to the setup wizard

= Version 4.0.9 (November 12th, 2015) =

* Changed: Improved upgrade experience for users of older themes
* Fixed: Default menu icons restored if upgrading from version 3
* Fixed: Social sharing link color if using social network colors

= Version 4.0.8 (November 11th, 2015) =

* Changed: Improved support for Windows/IIS installations
* Fixed: Persistent 'repair' message for certain free-to-pro migration conditions

= Version 4.0.7 (November 10th, 2015) =

* Changed: Make sure all WPtouch Pro scripts and css files are refreshed when the plugin is updated
* Changed: Make sure scripts and css files are loaded in the correct order
* Changed: Allow download for themes and extensions if auto-install fails
* Fixed: An issue which could prevent moving forward during wizard setup

= Version 4.0.6 (November 6th, 2015) =

* Changed: Prevent WordPress theme validation from being run when customizing the mobile theme
* Fixed: Some server configurations were saving incorrect stylesheet paths
* Fixed: An issue which could set WPtouch Pro's Display Mode setting to off after upgrade

= Version 4.0.4 (November 3rd, 2015) =

* Fixed: Customizer support for certain setting types

= Version 4.0.3 (November 2nd, 2015) =

* Changed: Now preserve settings when switching themes after upgrading from 3.x
* Fixed: WPtouch stylesheet was loading on the desktop theme
* Fixed: An issue which could cause shortcode not to be displayed properly
* Fixed: Preventing desktop themes from affecting WPtouch admin styling

= Version 4.0.1 (October 31st, 2015) =

* Changed: Updates to the wizard

= Version 4.0 (October 29th, 2015) =

* Added: Setup wizard for fast and easy configuration of key settings
* Added: Live editing of your chosen mobile theme via the WordPress Customizer
* Added: Full menu management within the WordPress Menu editor
* Added: Streamlined admin interface with clearer settings and options
* Added: Auto-install themes and extensions when you click activate
* Added: License & Support page with ability to de-license the current site and erase, delete, and deactivate functions
* Added: New icon set, Open Iconic
* Added: Custom licensing - buy just the themes and extensions and as many site activations as you want

* Changed: Basic Ads, Related Posts, and Web-App Mode are now optional extensions
* Changed: Moved settings backup/restore to account page
* Changed: Sharing links offer pinterest instead of google+
* Changed: Removed admin notifications code and pointers code
* Changed: Removed theme auto-update
* Changed: Fastclick script was updated
* Changed: Removed references to Twitter and WordTwit

* Fixed: Custom Post Types could not all be deselected
* Fixed: Featured Slider uses custom thumbnail field if one has been selected in blog settings
* Fixed: Private Posts were not being included in the featured slider if a user was logged in and able to view the post
* Fixed: Search in MobileStore using non-ASCII characters could cause JavaScript errors
* Fixed: Untranslated strings in MobileStore
* Fixed: JavaScript conflict introduced in 3.8.7
