# Portfolio Slideshow

This is where *development* for [Portfolio Slideshow](http://wordpress.org/plugins/portfolio-slideshow) happens.

"Development" is highlighted there because anything in the `:develop` branch here on this repo (which is the only branch, usually) could be unstable or not fully tested yet.

If you'd like to download stable versions of Portfolio Slideshow, grab an official tagged release from one of the following locations:

* [GitHub Tags](https://github.com/ggwicz/portfolio-slideshow/releases)
* [WordPress Developer Page](https://wordpress.org/plugins/portfolio-slideshow/developers/)

## Changelog

**`1.11.1`**

* TWEAK: Safer translatable strings.
* FIX: Ditched custom Underscores templating in place of safer and more widely-supported wp.template()
* FIX: Fixed errors with missing "slide URL" attachment fields.

**`1.11.0`**

* NEW: Restored full support for PHP 5.2 and higher!
* TWEAK: Some JS cleanup to being laying the groundwork for the few releases of improvements.
* TWEAK: Moved screenshots and some other assets to wp.org repo /assets folder – makes for a smaller plugin file and thus, hopefully, faster updates.
* TWEAK: Reduced number of variables created in view includes.
* TWEAK: Code cleanup in some of the views. 
* FIX: Fixed the "Slide URL" attachment field to ensure it saves.
* FIX: Repaired functionality of the `openurl` and `target` attributes.
* FIX: Fixed some max-width CSS to ensure) images don't pop out of their container.
* FIX: Fixed bug that prevented `centered="true"` shortcode attribute from working.
* FIX: Relocated and renamed many files for a more organized plugin structure to lay a foundation for future changes (heavily inspired by The Events Calendar).
* FIX: Fixed a broken placeholder image src.
* REMOVAL: Removed unused function `portfolio_slideshow_is_plugin_active()`.
* REMOVAL: Removed unused function `portfolio_slideshow_get_image_sizes()`.
* REMOVAL: Removed unused function `portfolio_slideshow_sanitize_text_field_deep()`.
* REMOVAL: Removed unused file `ps-custom-post-type.js`.
* REMOVAL: Removed unused file `ps-custom-post-type.css`.

**`1.10.0`**

* FIX: A few fixes to address the retrieval of slides, which should mean your pre-1.9.9 slideshows will work fine in many more cases than with the 1.9.9 release itself.
* FIX: Fixed some "Undefined Index" PHP notices with a few slideshow arguments.
* FIX: Removal of unnecessary "protected" access on several class methods and properties.
* FIX: Removal of a handful of unnecessary JavaScript and CSS files that could cause 404 errors on pages if loaded.

**`1.9.9`**

* Ported the existing plugin to PHP 5.3-compatible code and laid the foundation for some major changes in the next few versions: 1.10.x, and then 2.0.0

For the archived changelog for versions 1.5.1 and below, please see http://portfolioslideshow.com/changelog.