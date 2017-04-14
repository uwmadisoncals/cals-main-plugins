=== WP Photo Album Plus ===
Contributors: opajaap
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=OpaJaap@OpaJaap.nl&item_name=WP-Photo-Album-Plus&item_number=Support-Open-Source&currency_code=USD&lc=US
Tags: photo, album, gallery, slideshow, video, audio, lightbox, iptc, exif, cloudinary, fotomoto, imagemagick
Version: 6.6.23
Stable tag: 6.6.23
Author: J.N. Breetvelt
Author URI: http://www.opajaap.nl/
Requires at least: 3.9
Tested up to: 4.8-alpha-40396
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is designed to easily manage and display your photos, photo albums, slideshows and videos in a single as well as in a network WP site.

== Description ==

This plugin is designed to easily manage and display your photo albums and slideshows within your WordPress site.

* You can create various albums that contain photos as well as sub albums at the same time.
* You can mix photos and videos throughout the system.
* There is no limitation to the number of albums and photos.
* There is no limitation to the nesting depth of sub-albums.
* You have full control over the display sizes of the photos.
* You can specify the way the albums are ordered.
* You can specify the way the photos are ordered within the albums, both on a system-wide as well as an per album basis.
* The visitor of your site can run a slideshow from the photos in an album by a single mouseclick.
* The visitor can see an overview of thumbnail images of the photos in album.
* The visitor can browse through the photos in each album you decide to publish.
* Individual thumbnails and slides can be linked to off site urls.
* You can add a Photo of the day Sidebar Widget that displays a photo which can be changed every hour, day or week.
* You can add a Search Sidebar Widget which enables the visitors to search albums and photos for certain words in names and descriptions.
* You can enable a rating system and a supporting Top Ten Photos Sidebar Widget that can hold a configurable number of high rated photos.
* You can enable a comment system that allows visitors to enter comments on individual photos.
* You can add a recent comments on photos Widget.
* Apart from the full-size slideshows you can add a Sidebar Widget that displays a mini slideshow.
* There is a widget to display a number of most recently uploaded photos. It can be configured systemwide and/or on an album basis.
* There is a General Purpose widget that is a text widget wherein you can use wppa+ script commands.
* There is an album widget that displays thumbnail images that link to album contents.
* There is a QR code widget that will be updated when the content of the page changes.
* There is a tag cloud widget and a multi tag widget to quickly get a selection of photos with (a) certain tag(s).
* There is an upload widget that allows for frontend uploads even when no wppa+ display is on the page.
* Almost all appearance settings can be done in the settings admin page. No php, html or css knowledge is required to customize the appearence of the photo display.
* International language support for static text: Currently included foreign languages files: Dutch, Japanese, French(outdated), Spanish, German.
* International language support for dynamic text: Album and photo names and descriptions fully support the qTranslate multilanguage rules.
* Contains embedded lightbox support but also supports lightbox 3.
* You can add watermarks to the photos.
* The plugin supports IPTC and EXIF data.
* Supports WP supercache. The cache will be cleared whenever required for wppa+.
* Supports Cube Points. You can assign points to comments and votes.
* There is an easy way to import existing NextGen galleries into WPPA+ albums.

Plugin Admin Features:

You can find the plugin admin section under Menu Photo Albums on the admin screen.

* Photo Albums: Create and manage Albums.
* Upload photos: To upload photos to an album you created.
* Import photos: To bulk import photos to an album that are previously been ftp'd.
* Settings: To control the various settings to customize your needs.
* Sidebar Widget: To specify the behaviour for an optional sidebar photo of the day widget.
* Help & Info: Much information about how to...

Translations:

<ul>
<li>Dutch translation by OpaJaap himself (<a href="http://www.opajaap.nl">Opa Jaap's Weblog</a>) (both)</li>
<li>Slovak translation by Branco Radenovich (<a href="http://webhostinggeeks.com/user-reviews/">WebHostingGeeks.com</a>) (frontend)</li>
<li>Polish translation by Maciej Matysiak (both)</li>
<li>Ukranian translation by Michael Yunat (<a href="http://getvoip.com/blog">http://getvoip.com</a>) (both)</li>
<li>Italian translation by Giacomo Mazzullo (<a href="http://gidibao.net">http://gidibao.net</a> & <a href="http://charmingpress.com">http://charmingpress.com</a>) (both)</li>
</ul>

== Installation ==

= Requirements =

* The plugin requires at least wp version 3.1.
* The theme should have a call to wp_head() in its header.php file and wp_footer() in its footer.php file.
* The theme should load enqueued scripts in the header if the scripts are enqueued without the $in_footer switch (like wppa.js and jQuery).
* The theme should not prevent this plugin from loading the jQuery library in its default wp manner, i.e. the library jQuery in safe mode (uses jQuery() and not $()).
* The theme should not use remove_action() or remove_all_actions() when it affects actions added by wppa+.
Most themes comply with these requirements.
However, check these requirements in case of problems with new installations with themes you never had used before with wppa+ or when you modifies your theme.
* The server should have at least 64MB of memory.

= Standard installation when not from the wp plugins page =
* Unzip and upload the wppa plugin folder to wp-content/plugins/
* Make sure that the folder wp-content/uploads/ exists and is writable by the server (CHMOD 755, some systems need CHMOD 777)
* Activate the plugin in WP Admin -> Plugins.
* If, after installation, you are unable to upload photos, check the existance and rights (CHMOD 755, some systems need CHMOD 777) of:
for the single site mode installation: the folders .../wp-content/uploads/wppa/ and .../wp-content/uploads/wppa/thumbs/,
and for the multisite mode installation (example for blog id 92): the folders path: .../wp-content/blogs.dir/92/wppa/ and .../wp-content/blogs.dir/92/wppa/thumbs/.
In rare cases you will need to create them manually. You can see the actual pathnames and urls in the lowest table of the Photo Albums -> Settings page.
* If you upgraded from WP Photo Album (without plus) and you had copied wppa_theme.php and/or wppa_style.css
to your theme directory, you must remove them or replace them with the newly supplied versions. The fullsize will be reset to 640 px.
See Table I-A1 and Table I-B1,2 of the Photo Albums -> Settings admin page.

== Frequently Asked Questions ==

= What do i have to do when converting to multisite? =

* If your WP installation is a new installation and you want to have only one - global - WPPA system, add to wp-config.php:
**define( 'WPPA_MULTISITE_GLOBAL', true );**
* If your WP installation is a new installation and you want to have a separate WPPA system for each sub-site, add to wp-config.php:
**define( 'WPPA_MULTISITE_INDIVIDUAL', true );**
* If your WP installation is older than 3.5 an you want to have only one - global - WPPA system, ad to wp-config.php:
**define( 'WPPA_MULTISITE_GLOBAL', true );**
* If your WP installation is older than 3.5 an you want to have a separate WPPA system for each sub-site, add to wp-config.php:
**define( 'WPPA_MULTISITE_BLOGSDIR', true );**
* If you want to convert your multisite WP installation that is prior to 3.5 to a version later than 3.5 and you want to convert an existing WPPA multisite installation
to the new multisite standards, do the following:
1. Update WP to version 3.5 or later.
1. Upate WPPA+ to version 5.4.7 or later.
1. Perform the network migration utility from the network admin which moves all the files from wp-content/blogs.dir/xx to wp-content/uploads/sites/xx
1. **Add** to wp-config.php: **define( 'WPPA_MULTISITE_INDIVIDUAL', true );**
1. If it is there, **Remove** from wp-config.php: **define( 'WPPA_MULTISITE_BLOGSDIR', true );**

= Which other plugins do you recommand to use with WPPA+, and which not? =

* Recommanded plugins: qTranslate, WP Super Cache, Cube Points, Simple Cart & Buy Now, Google-Maps-GPX-Viewer.
* Plugins that break up WPPA+: My Live Signature.
* Google Analytics for WordPress will break the slideshow in most cases when *Track outbound clicks & downloads:* has been checked in its configuration.

= Which themes have problems with wppa+ ? =

* Photocrati has a problem with the wppa+ embedded lightbox when using page templates with sidebar.

= Are there special requirements for responsive (mobile) themes? =

* Yes! Go to the Photo Albums -> Settings admin page. Enter **auto** in Table I-A1. Lowercase letters, no quotes.
* Do not use size="[any number]", use size="0.80" for 80% with etc.
* If you use the Slideshow widget, set the width also to **auto**, and the vertical alignment to **fit**.
* You may also need to change the thumbnail sizes for widgets in *Table I-F 2,4,6 and 8*. Set to 75 if you want 3 columns in the theme *Responsive*.

= After update, many things seem to go wrong =

* After an update, always clear your browser cache (CTRL+F5) and clear your temp internetfiles, this will ensure the new versions of js files will be loaded.
* And - most important - if you use a server side caching program (like WP Total Cavhe) clear its cache.
* Make sure any minifying plugin (like W3 Total Cache) is also reset to make sure the new version files are used.
* Visit the Photo Albums -> Settings page -> Table VII-A1 and press Do it!
* When upload fails after an upgrade, one or more columns may be added to one of the db tables. In rare cases this may have been failed.
Unfortunately this is hard to determine.
If this happens, make sure (ask your hosting provider) that you have all the rights to modify db tables and run action Table VII-A1 again.

= How does the search widget work? =

* A space between words means AND, a comma between words means OR.
Example: search for 'one two, three four, five' gives a result when either 'one' AND 'two' appears in the same (combination of) name and description.
If it matches the name and description of an album, you get the album, and photo vice versa.
OR this might apply for ('three' AND 'four') OR 'five'.
If you use indexed search, the tokens must be at least 3 characters in length.

= How can i translate the plugin into my language? =

* See the documentation on the WPPA+ Docs & Demos site: http://wppa.opajaap.nl/?page_id=1349

= How do i install a hotfix? =

* See the documentation on the WPPA+ Docs & Demos site: http://wppa.opajaap.nl/?page_id=823

= What to do if i get errors during upload or import photos? =

* It is always the best to downsize your photos to the Full Size before uploading. It is the fastest and safest way to add photos to your photo albums.
Photos that are way too large take unnessesary long time to download, so your visitors will expierience a slow website.
Therefor the photos should not be larger (in terms of pixelsizes) than the largest size you are going to display them on the screen.
WP-photo-album-plus is capable to downsize the photos for you, but very often this fails because of configuration problems.
Here is explained why:
Modern cameras produce photos of 7 megapixels or even more. To downsize the photos to either an automaticly downsized photo or
even a thumbnail image, the server has to create internally a fullsize fullcolor image of the photo you are uploading/importing.
This will require one byte of memory for each color (Red, Green, Blue) and for every pixel.
So, apart form the memory required for the server's program and the resized image, you will need 21 MB (or even more) of memory just for the intermediate image.
As most hosting providers do not allow you more than 64 MB, you will get 'Out of memory' errormessages when you try to upload large pictures.
You can configure WP to use 128 MB (That would be enough in most cases) by specifying *define('WP_MEMORY_LIMIT', '128M');* in wp-config.php,
but, as explained earlier, this does not help when your hosting provider does not allows the use of that much memory.
If you have control over the server yourself: configure it to allow the use of enough memory.
Oh, just Google on 'picture resizer' and you will find a bunch of free programs that will easily perform the resizing task for you.

== Changelog ==

See for additional information: <a href="http://www.wppa.nl/changelog/" >The documentation website</a>

= 6.6.23 =

= Bug Fixes =

* Fixed db version number 

= 6.6.22 =

= Bug Fxes =

* The header in the edit photo and moderate photo pages was below the photo(s). Fixed.

= New Features =

* Administrators, wppa superusers and moderators can now schedule the time for the automatic future deletion of photos.

= Other Changes =

* Status of confirmation boxes on bulk photo admin page are now saved in cookies.
* Image urls in og:matatags have no longer a version number for cleaner urls.
* Urls in og:metatags are now always pretty, regardless of Table IV-A6. The wp permalink structure still needs to be non-default.
* Extra calls to the functions that remove spinners are added running 1 second later to fix possible timing related issues where spinners do not disappear where they should.

= 6.6.21 =

= Bug Fixes =

* QR widget now works as designed

= 6.6.20 =

= Bug Fixes =

* Fixed a Qr code display issue.

= Other Changes =

* Spinners are now .svg images (except in IE) and have the same color as the navigation symbols as listed in Table II-B14 and 15, and the corner rounding is like the setting in Table II-J11.
* Added charset=utf8 to the headers in mails sent by wppa.
* Share text to Twitter is shortened.

= 6.6.19 =

= New Features =

* Table IV-E12.1 Rating on two lines. Works only with graphic display when show average is on.
* Deleted photos can be undeleted during at least one hour by the admin or any wppa super user. See the Album table after one or more photos have been deleted.
* Table IV-F11. Enable clickable links in comments.

= Other Changes =

* Source file deletion is now also done at the background.
* QR codes can be cached. This reduces the load on the qrserver and hence prevents DoS. Default off, Setting: Table IX-K1.4. Qrcodes are svg images in this case.

= 6.6.18 =

= Bug Fixes =

* The max photo pixelsize message in the frontend upload dialog now also checks for the setting in Table VII-B10.
If this value is set, it has precedence over the memory check.
Make sure you do not set this value larger than the memory can handle.
* Improved background indexing to ensure faster update of indexes in all cases.
* On ssl pages, on the comments section, the gravatar url was insecure. Fixed.
* The QR widget is no longer insecure on ssl pages.
* Photo of the day selection. If set to random, restored the feature that more than one photos of the day on a single page will be different.

= New Features =

* A few more buttons on the Edit Album information screen.

= Other Changes =

* ImageMagick can now be used together with Cloudinary.
* If there is only one Grant Parent, the users album will now be created regardless of if it is going to be used.

= 6.6.17 =

= Bug Fixes =

* Default image audiostub.png was missing. Re-added.
* When the php ini setting max_execution_time is not set properly, cron jobs will run at least 15 seconds, not just for one item.

= New Features =

* ImageMagick Phase II. When ImageMagick is activated, the photo admin page has over a dozen extra manipulation buttons that act instantaneously.

= Other Changes =

= 6.6.16 =

= Bug Fixes =

* Treecounts on album cover set to 'total' (Table II-E5) did not display the sub-sub-album count on sub-albums. Fixed.
* Removed the link to album/photo edit on the upload admin page when the user has no rights to edit.
* Fixed the admin bar menu options in case the user has no edit rights.
* Rating V was no longer replaced by *. Fixed.
* User skips for indexing (Table IX-E18.2) were not processed properly. Fixed.
* Upload Box B stopped working at version 6.5.02. Fixed.

= New Features =

* You can configure retries for failed mails in Table IX-A10. Retries take place every hour.

= Other Changes =

* Downsizing and thumbnail creation by ImageMagick is implemented.
* Button text is now reset after frontend upload.
* Improved delete album and cleanup deleted photos.
* Performance improvements during upload by removing redundant double creating thumbnails.

= 6.6.15 =

= Bug Fixes =

* Lightbox exit button did not work when the background click action was set to Browse. Fixed.
* After entering a comment, sometimes a statusmessage was displayed. Fixed.
* Fixed an intermittant css issue on masonry horizontal thumbnail display.
* When Table II-D5.1: <i>Thumbnail virt album</i> was ticked, only the first page of thumbnails had the subtext. Fixed.

= New Features =

* ImageMagick phase I. Command interface. See Table IX-K7. Implemented: rotation of non-standard orientation, rotate and flip on the photo admin page.
This feature is still under development. If you see unexpected behaviour, please report it here: https://wordpress.org/support/plugin/wp-photo-album-plus

= Other Changes =

* Removed russian language files. They are now covered by the Polyglots system.
* Album order in album selection lists is now in accordance with the album sequence order settings.

= 6.6.14 =

= Bug Fixes =

* Single quotes in photo names crashed the slideshow. Fixed.
* Removed/fixed various minor cosmetic diagnostic and warning messages.

= Other Changes =

* If the initial column width (Table I-A1.1) is less than the Mini Treshold (Table I-B7) the short texts for 'Avarage' and 'My' rating is used under single photos with extended caption.
* The size of rating stars is the navigation fontsize plus 3 pixels. The text was also 3 px larger. This is corrected to the navigation text fontsize.
* Processing... and Done! in the upload progression bar are now translatable.
* Photos marked for deletion are no longer found in search operations when they are not yet physically removed by cron.

= 6.6.13 =

= Bug Fixes =

* Indexing of qTranslate-able phrases in all available langages in photo and album names and descriptions now works as designed.
* Rating stars in Chrome had an opacity problem. Fixed.

= New Features =

* New album description keyword w#fsviews used to display the total number of fullsize views of photos in the album.
* New shortcode [photo xx] where xx is an existing photo number, to display a single photo. Related settings in Table IX-L.
* During import .csv file (for custom data) also the following fields can be modified:
album, name, description, p_order, linkurl, linktitle, linktarget, owner, timestamp, status, tags, alt, modified, location, views, clicks, exifdtm.
The first header item must be <b>filename</b> in this case.
* Photo name can now contain keyword w#id. 'Photo w#id' has beed added to the selections in <b>Table IX-D10</b>: <i>Default photo name</i>
* Added 'Album' in the admin bar 'New' menu for users with the rights to add top-level albums.

= Other Changes =

* Provided a workaround for a bug in Safari browser resulting in not working of the download links of image files.
* Added class wppa-rating-star to rating stars and wppa-rating-thumb to the dislike thumb down.
* Right mouse button on lightbox image is now disabled.

= 6.6.12 =

= Bug Fixes =

* Under certain circumstances the close button of the new style frontend edit photo modal dialog box did not work. Fixed.
* Fixed the incorrectly working link 'Top of page' on the album admin screen.
* Fixed a missing '&lt;/table>' in wppa_get_multitag_html();
* Fixed a layout issue of the album multi selection box on the photo of the day admin page.

= New Features =

* Shortcode attribute cols="...", used with shortcode type="multitag" can now have two numbers; one for pc (1st) and one for mobile (2nd). Example: [wppa type="multitag" cols="6,2"]
* The maintenance of the search index db table as well as the actual deletion of photos is now performed at the background. To monitor this activity: Tick the box in Table IX-A9: Log Cron.
* Table IX-E18.x: Additional search engine settings.

= Other Changes =

* Crashed cron jobs are now automaticly re-animated. There is a check every hour.
* Significant performance enhancements, especially in editing album/photo info and photo deletion.

= 6.6.11 =

= Bug Fixes =

* Adjusted the calculation for the size of the thumbnail image files to take into account when responsive cover images are used.
* Responsive cover images not always worked. Fixed.
* When VII-B-12 (Fe alert) was unchecked and the fe upload is blogged, the redirect to the blog post failed. Fixed.
* Max one alertbox on fe upload/blog. Select when in Table VII-B12.
* Fixed a possible errormessage on fe delete photo.

= New Features =

* Table VIII-B12.2 Add HD tags. All photoa that are available in resolution >= 1920 x 1080 px get tag HD.
* Albums can have their own watermark settings. New uploaded photos will have that watermark in stead of the system default as specified in Table IX-F.

= Other Changes =

* You can disable the shortcode generator in Table IX-B19 for users who are neither administrator nor wppa superuser.
* Tags GPX and HD will always be in capital letters. Existing tags can be fixed using Table VIII-B16 Fix Tags.
* The caching of wppa options has been removed to avoid problems on systems that do not allow db fields > 64kB.
* The togo-field of running cron jobs (Table VIII) is now being updated every 5 seconds.

= 6.6.10 =

= Bug Fixes =

* Under certain circumstances the granted album was not created. Fixed: It is no longer required to have an upload widget on the members page.

= New Features =

* New shortcode attribute: alt="...". The alt attrinute may conatin an existing photo id number.
In case the shortcode can not be rendered, you used to see the 'placeholder' [WPPA+ Photo display (fsh)].
In some circumstances this is not due to a configuration issue, but you can e.g. have plugin AMP installed and AMP applies to the content.
When the alt attribute is added to the shortcode, a plain photo will be displayed. If you do not want anything to be displayed (no photo and no errormessage)
set the alt attrinbute to none: alt="none". Full shortcode example: [wppa type="slide" album="255" alt="4187"]Any comment[/wppa]

= Other Changes =

* It is no longer possible to delete a photo that is explicitly used as a single image in a post or page.
* After Blog it! (front-end upload) You will now be redirected to the post you blogged.
* Table II-B0: Navigation type. Can be set to one out of: 'Icons', 'Icons on mobile, text on pc' or 'Text'.
This makes the old text style coming back for those who really want it.
* Table II-H16: Blog it! is now a selectionbox. You can select one out of: disabled, optional and always.
This makes it possible to force users to blog their uploads.
* You can now have responsive album cover images. Table I-D5.x and I-D6.x

= 6.6.09 =

= Bug Fixes =

* Fixed a spurious db error on certain search ops resulting in erroneously nothing found.
* Related count was not obeyed. Fixed.

= New Features =

* Photo viewcounts can now be displayed under the album cover photo. Table II-E9: Viewcount on cover.
* Table IV-C10: Run nl2br or wpautop on description. For thumbnail text.

= Other Changes =

* The treecounts system (number of sub-albums, photos, pending and scheduled as well as photo viewcounts)
has been re-implemented; data is now stored in the albums table.
Viewing admin pages cause immediate recalculation, so admin pages always show correct numbers.
On the foreground - to reduce overhead actions that may slow down pageloads for visitors -
the updates are done in a background process (cron job) and may result in max 30 seconds delay after upload,
create sub album or viewing fs images.
* On some systems it has been shown that the eventhandler for onload is not executed on svg images.
To avoid svg navigation icons from not getting the right colors and showing up,
the function wppaReplaceSvg() is now called at various smart moments.
* The album cover text is now wrapped in a div rather than a p to avoid layout problems when Table IV-D8 is set to 'Linebreaks and paragraphs'.

= 6.6.08 =

= Bug Fixes =

* Fixed an error in the html for google+ share code.
* A running slideshow no longer interrupts running audio.
* Fixed an alignment problem of the audio bar on theme Twenty Seventeen.
* Fixed cursor (is now a pointer) in case a single image has a link to it.
* Fixed a spurious php error in wppa_in_widget().
* Hilite is working again. (Indication on thumbnail when linked from slideshow to thumbnails).

= Other Changes =

* The first/last arrow icons on the filmstrip do no longer show up if the first/last image is displayed.
* Cosmetic changes to filmstrip tooltips (titles).
* Functional enhancements in bulk edit photos i.c. immediate move por photo.
* Table IX-H16 ( iptc needs utf8 conversion ) has been removed. This is now autodetected.
* It is no longer possible to generate negative photo or album or any other db table ids.

= 6.6.07 =

= Bug Fixes =

* Various fixes to better meet the w3c validation standards.

= New Features =

* Table VI-A5, A7, A8: Thumbnail linktype: added option: 'the thumbnails album in a slideshow'.
* Uploader widget: added 2 switches to display total number of contributors and total number of photos.

= 6.6.06 =

= Bug Fixes =

* Fixed a default covertype layout issue on static (non-responsive) display.
* On some systems the rating system did not work. Fixed.

= New Features =

* Table IV-A9.2: Track clickcounts. Counts the clicks on single images and slideshow images when there is a link url. Totals per album show up on the album admin page.
* Table VI-B2: Thumbnail linktype: added option: 'the thumbnails album in a slideshow'. Shows the slideshow of the album where the thumbnail belongs. Starting at the pointed image.
This always shows the physical album where the thumbnail belongs to as a slideshow, even when the collection of thumbnails is the content of a virtual album;
as opposed to 'the fullsize photo in a slidshow' (the default) that always shows the slideshow of the selection where the thumbnail belongs to.

= 6.6.05 =

= New Features =

* Table IV-E11: <i>Rating display type</i> can now be set to 'Likes'. Works on thumbnails (II-D4), slideshow (II-B7) and lightbox (II-G21).
* Table IX-F8.2 and IX-F8.3 The color of textual watermarks can now be set.
* Table VI-A6: LasTen widget linktype: added option: 'the thumbnails album in a slideshow'. Shows the slideshow of the album where the thumbnail belongs. Starting at the pointed image.

= 6.6.04 =

= Bug Fixes =

* Delete link for pending photos under slide and thumbnail did not work but generated a security check failure. Fixed.
* Uploading photo file formats other than .jpg, .jpeg, .png or .gif at the frontend did not produce an appropriate user errormessage, but resulted in a missing photo and thumbnail. Fixed.
* Fixed initialisation errors that caused a php warning without functional mis-behaviour.
* Settings in Table IX-F3: Watermark file and position were not settable. Fixed.

= New Features =

* The administrator can now use the downloaded db table .csv-files from Table XII for import on the Photo Albums -> Import screen. For backup and for moving to another website/host.
See http://wppa.nl/changelog/installation-notes/#moving
* Max number of albums to show up in a selectionbox on the photo admin screen is now settable in Table IX-B6.2. If this max is exceeded, a number input field is displayed.
* Maintenance proc Table VIII-B8.2 All to lower. Convert file-extensions to lower case. To fix very old systems.
Recommended before moving the site, especially when you use a windows pc to copy the photofiles.
Window does not accept two files with the same name with different case in file extension. It simply overwrites the files.

= Other Changes =

* Various performance improvements.
* Various typos in texts fixed.

= 6.6.03 =

= Bug Fixes =

* On the upload admin screen, when the server allows you to upload as much as gigabytes, you will no longer get the erroneous message that the total filesize is too big.
* Lightbox start/stop button only showed up initially but disappeared after used once. Fixed.
* List comments by name ( Table VIII-C5 ) did not work. Fixed.
* Fixed a breadcrumb issue.
* Import from remote url stopped working. Fixed.

= Other Changes =

* Table VII-B7 has been renamed Table VII-B7.0. Table VII-B7.1 added: Front-end uploads get status private.
This overrules the setting of VII-B7.0, but modaretion emails will still be sent if VII-B7.0 is ticked.

= 6.6.02 =

= Bug Fixes =

* If <i>Upload owner only</i> was unticked ( Table VII-D1.2 ), it worked like it was still ticked. Fixed.
In this case the upload dialog now also appears on album covers and thumbnail areas.
* Fixed breascrumb go to thumbnails icon for virtual albums.
* Next page and link and direct access page links on admin page Moderate photos gave no rights error. Fixed.

= Other Changes =

* (Photo)file permissions are now always set to 0644 and wppa created directories to 0755 to grant accessability, even when server defaults change magically.
* If ewww image optimizer is installed, created photo files will now always be optimized if Table IX-D17 is ticked.
* Commenting your own photos will no longer give you credit points.
* Items in a .csv file will now be trimmed from spaces.
* Extended settings for counters on album covers: Table II-E5 and Table II-E8.

= 6.6.01 =

= Bug Fixes =

* Under some circumstances there were many grant albums created for the same user. Fixed.
* Fixed a spurious db error in upload widget/upload box.
* Breadcrumb on slideshow stopped updating photoname. Fixed.
* Cron job Remake did not work. Fixed.
* Svg icons do not work in ie, now using .png in ie.

= Other Changes =

* Breadcrumb link to current page now uses ajax if ajax enabled.

= 6.6.00 =

= Bug Fixes =

* Fixed photo permalinks for (double) quotes in album names.
* Granted albums are now added to the permalink redirect .htaccess file immediately after creation.
* Fixed photo permalinks for albums with multi language names using qTranslate-x.
* Fixed a problem with facebook sharing when photo- (and/or album-) names in urls was activated and there were spaces in the names.

= New Features =

* Besides rotating the image, you can now also flip the image on the photo admin page.
* Certain maintenance tasks can now alternatively be done in the background as cron job.

= Other Changes =

* All slideshow and lightbox navigation is now performed by scalable vector graphic buttons.
The fill- and background colors can be set in Table III-B14 and 15. The style (the amount of rounding the corners of the square icons) can be set in Table II-J11.
* The lightbox exit and (exit)fullscreen icons are now located at the upper right corner in all displays.
* The 'normal' lightbox display on portrait mobile devices no longer leave big left and right margins.
* Custom navigation symbol urls and miscellaneous settings have been dropped ( Table II-K is removed ).
* %%wppa%% scripts are no longer supported. If you still have scripts, you can convert them here: http://wppa.nl/changelog/script-to-shortcode-conversion/
* Completely re-designed the album and photo admin pages. This also makes it more mobile friendly.
* og:image:width and og:image:height added to speed-up fb sharing.
* Added Table IV-D8 to add paragraphs and/or linebreaks to album descriptions. Also changed IV-B11 to enable linebreaks only.
* Many more small enhancements, bug fixes and performance improvements.

= 6.5.09 =

= Bug Fixes =

* Most of the albums did not show up in the album table for non admin users with album admin access to a few albums. Fixed.
* Fixed a capacity issue with very many grant parents.

= 6.5.08 =

= Bug Fixes =

* Lightbox on stereo images now always shows the anaglyph in stead of the double image.
* Under certain circumstances (relative urls activated) share urls did not work. Fixed.

= Other Changes =

* On mobile devices you can now select the start mode independantly in Table IV-G10. On mobile devices, lightbox in fs mode has now a button in the upper left corner to switch to standard mode.
* Shortcode generator has an extra (search) field to reduce the album selection box options.
* While searching from combined wp/wppa search template, album/thumbnail pagination is now always off.
* Album admin table is now paginatable. Table IX-B6.1.
Note that the number of albums listed may differ when the style is collapsable, the (grand)parent albums will be added to the partial table display for clarity.

= 6.5.07 =

= Bug Fixes =

* Facebook comments does not show up if your have relative urls active in Table IV-A25. Fixed.
* HTML text in the slideshow custom box will now work properly even when Table IX-B1 is unticked.
* Front-end edit photo stopped working. Fixed.

= New Features =

* New shortcode [wppa type="share"] produces share buttons for the current page/post. This can be used to replace other buggy sm plugins.
* Sutcliffe style photo description with counter on slideshow. Table IV-B16.

= Other Changes =

* Improved behaviour on mobile devices for links to lightbox.
A link to lightbox works only if the image that links to lightbox has been touched for less than 250 milliseconds,
so touching the image for srolling no longer opens the lightbox.
* Enqueueing styles and scripts is now done at the hook wp_enqueue_scripts rather than init, wp_print_styles or wp_print_scripts.

= 6.5.06 =

= Bug Fixes =

* Sorting albums by clicking the new up/down arrows repeatedly now skips useless updates and therefor no longer takes more time than needed.
* Importing videofiles larger than 64MB now work correctly.
The files are no longer truncated due to a php copy bug.
The files will be removed from the depot regardless of the remove checkbox setting because rename() is used insted of copy().
* Fixed source image permalinks for photos in albums with spaces in the name.
* If Fotomoto is in use and switched off for the page, you will no longer see a link to the checkout page.
* Fixed a spurious bad ratio issue on type="photo" and type="sphoto".
* Fixed a possible 'album does not exist' log message caused by fe upload selection box when the last album used non longer exists.

= New Features =

* You can now catch the ajax rendered content in a modal box. An easy way to get back to the original display by quitting the modal box.
Related settings: Table IV-A1.2 to switch the feature on; Table III-B13 to set the background color;
* Table II-E8. Display photocount along with album title. You can use css class .wppa-cover-pcount to change style.
* New shortcodes:
[wppa type="pcount" album="13"][/wppa] and [wppa type="acount" album="13"][/wppa] print photo count and subalbum count of the given album.
[wppa type="pcount" parent="13"][/wppa] and [wppa type="acount" parent="13"][/wppa] print photo count and subalbum count of the given album including sub-albums.
Note: these shortcodes only accept positive integers for album="" and parent="" attributes.
* Table IV-A29. Load wppa .js and .css files only when wppa is used on the page.

= Other Changes =

* Sharing form type="mphoto" or type="xphoto" will now always share the single image, regardless of the setting of Table II-C99.
* Moved loading js files from action 'init' to action 'wp_print_scripts'.
* Upload edit new style is now done in a modal dialog rather than a pop-up browser window.
* Table VIII-C listings are now displayed in a modal dialog box rather than a pop-up browser window.

= 6.5.05 =

= Bug Fixes =

* Rating display on lightbox is now updated directly.
* Fixed inconsistent behaviour of 'last album used'.
* Title 'paused' is now also displayed on the slideframearea outside the image.
* Fixed initial displaysize of single responsive photos.

= New Features =

* New shortcode type="xphoto". Extended media type photo. Like type="mphoto" but also displays rating, share buttons and commentform if enabled in Table II-B7, B10 and C1.
* Up/down arrows on the album sortable list.

= Other Changes =

* Blog it! is now a button. Makes it easier to blog a photo from a smartphone.

= 6.5.04 =

= Bug Fixes =

* The spinner while waiting for album download did not work when encryoted links was active. Fixed.
* Fixed alt attribute in slideshows and lightbox.
* Poster images for videos are now also applied to videos in a slideshow.

= Other Changes =

* Various textual enhancements.
* The photo of the day admin page has been rewritten and is now in the style of the settings page. Includes a few enhancements to the settings.
* Incomplete comments are marked as spam. To avoid eventual auto deletion when they are correct, they will now also be reported as requiring moderation on the main menu.

= 6.5.03 =

= Bug Fixes =

* type="slphoto" did not work. Fixed.
* type="slidef" added to support the scripting equivalence %%slidef=...%%%
* photo results in combined wp/wppa search results were not responsive on responsive themes. Fixed.
* Fixed a missing initialisation that could cause a slideshow not to start in spurious situations.

= New Features =

* Shortcode type upload can now have attribute parent="album id" as replacement for album="album id".
This will enable uploads to the album including its (grand) children (only the albums the visitor has the rights to upload to).
* You can configure more than one grant parent. This causes auto album creation for logged in users in all grant parents.

= Other Changes =

* Conversion tool inside notification box.
* Blog shortcode is now configurable in Table II-H18
* Granted albums are now only created when needed.
* You can only Blog It! from upload box or widget. No longer from thumb area or cover.

= 6.5.02 =

= Bug Fixes =

= New Features =

* Shortcode attribute size"auto" can now be extended with a maximum value in pixels. Example: size="auto,550"
* Blog It!. If you enable it in Table II-H16,17, users with edit_post rigths can send a post with one or more photos and text directly from the upload widget/box.

= Other Changes =

* Viewlink div on imagefactory covers have class .wppa-viewlink-sym as opposed to .wppa-viewlink for other cover types.
* Potential security fixes by improved input data sanitizations.
* Captcha question for album create can now be switched off in Table VII-B3.

= 6.5.01 =

= Bug Fixes =

* Fixed a layout issue in comment admin.
* Rating on lightbox did not work as expected. Fixed.

= New Features =

= Other Changes =

* Fixed a potential security issue in comment admin.

= 6.5.00 =

= Bug Fixes =

* Fixed erroneous hi res urls when the wppa-source directory was 'outside' ABSPATH
* Fixed an issue where certain iptc and exif data was not findable by supersearch. If you still hsve this problem, run Table VIII-A7: Recuperate.
* In certain themes there was a line across the cover image when there was a link on the cover image. Fixed.

= New Features =

* Custom fields for albums. See Table II-J9, II-J9.xabc.
* The album attribute in shortcodes: album="#lasten,{album},{maxcount}" has been extended with an owner part: album="#lasten,{album},{maxcount},{owner}".
* Display optionally a counter on the photo of the day widget that indicates how many more photos there are in the album. Links to thumbnails or slideshow. See the Photo of the day admin page.
* Table II-G21. Rating on lightbox. Works only with display type 5 or 10 stars, not for single votes or numerical display.

= Other Changes =

* Filmstrip adjustment time is now equal to the animation speed set in Table IV-B6.
* Added two linktypes to slideshow to the Bestof widget.
* The receiver of points for comments now also gets the points when no moderation of the comment is required.

= 6.4.20 =

= Bug Fixes =

* Import using the update switch remade thumbnails from the old image source. Fixed.
* Combined search results did not work any longer, fixed.

= Other Changes =

* Minimized the help page, changed link to new documantation site http://www.wppa.nl/
* Added combined search template for theme twentysixteen.
* The keyword w#displayname will now be translated to <i>Nomen Nescio</i> if the user is no longer exists, and to <i>Anonymus</i> if the user was not logged in during upload.

= 6.4.19 =

= Bug Fixes =

* The display of thumbnails of any kind of virtual album will not have upload or create subalbum links.
* The display of the upload and create links can now be set at the top or bottom of the thumbnail area. Table II-D19.
* Settings in Table VII-A could no longer be changed. Fixed.
* Fixed a spurious filename issue when importing from local while filesystem is tree.

= New Features =

* Table VIII-A19.x allows you to move all photos from one album to another, without being timed out.
The move does not check for duplicates and does not update timestamps, but it does move sourcefiles if present.
* Table II-C7. You can select pages where no share buttons appear.
This is usefull for protected pages that require login.
Bots and crawlers are not logged in, so they can not find the photos to share.
* You can limit rating to once per hour/day/week in Table IV-E3.0

= Other Changes =

* Improved behaviour of ugly browse buttons, both on pc and mobile.
* Lightbox on mobile now always runs in (padded) fullscreen mode. There is only a quit icon in the upper right corner. You can swipe left/right to browse through the images.
* You can enter *all* in Table IX-A8 to make all settings runtime modifyable.

= 6.4.18 =

= Bug Fixes =

* Fixed a layout issue of lightbox on Edge: display was 5 px to low and it left a white line after closure on certain themes.
* Ticking the arrows on lightbox on mobile devices executed 'next' and 'previous' twice. Fixed.
* Changing background and border colors stopped working in 6.4.17. Fixed.

= New Features =

* Table I-A11: Sticky header size. To scroll the result of an ajax rendering to a lower position.

= Other Changes =

* Twitter image can now post the image directly on twitter. For this feature to work, it is required to fill in a twitter account name in Table II-C13.1.
There will no longer be pre-edited text in the tweet. There will be a subtitle to the image, but this does not count to the 140 chars. Leave the link in the tweet as it is.
* Various performance improveents.
* In shortcodes: album="$Albumname" will now return all albums with the supplied name.
* Provided a workaround for a php bug causing upload of selfies to fail.

= 6.4.17 =

= Bug Fixes =

* Single button vote under thumbnails now also works when encrypted links is configured.
* The shortcode for a single image in the style of a slideshow now also works for the photo of the day: [wppa type="slphoto" photo="#potd"][/wppa]
* Slideshow widget. If display name is set to on, it will now show up.
* Responsiveness was lost during an ajax render operation. Fixed.
* In shortcode type="cover" there will no longer an empty thumbnail area be displayed, even when Table II-D18 is ticked ( Show empty thumbnail area ).
* Fixed a bug in the photo selection when searched on uploader photos.
* Fixed a bug in url encryption.

= Other Changes =

* If the photo of the day is used in a shortcode like [wppa type="photo" photo="#potd"][/wppa] and the photo of the day is not found due to misconfiguration, it now prints an errormessage in stead of '0'.
* You now select the photo of the day in the shortcode generator in the single photo shortcodes.
* Performance improvements.
* The logfile is now: .../wp-content/wppa-log.txt

= 6.4.16 =

= Bug Fixes =

* Duplicate uploads of the same file at the frontend caused the file-extension to become xxx ( as if it was a multimedia item ). Fixed.
* If a size is explicitly given, and it is not a fraction, the display will be that size, also when Table I-A1 is set to auto.

= New Features =

* You can now specify a standard user login name as the owner for new uploads. Need not to be an existing user. Default is the uploader.
* You can now select 'include subalbums' on the LastTen widget.

= Other Changes =

* w#displayname added to the standard photo keywords, meaning the display name of the owner of the photo.
* Due to changes in the wp language system, the ability to prevent the loading of wppa language files is discontinued. Table IX-A9 has been removed.
* Dramaticly reduced number of querie when Table IV-A28: Set owner to name is ticked.
* Removed query statistcs diagnostic code. If you want query statistics, use the excellent plugin Query Monitor.

= 6.4.15 =

= Bug Fixes =

* Fixed aspect ratio and star opacity to have a decimal point for locales that use the comma as radix delimiter.

= New Features

* myCRED / CubePoints can now also be given to the owner of a photo that received an approved comment. Table IX-J4.1.
* Predefined watermarktext may now contain w#displayname for the owners display name, all standard photo description keywords, all iptc (2#nnn) and exif (E#nnnn) keywords.

= Other Changes =

* Improved configurability of comment approved notification email. (Table IV-F5.3).
* Fixed various warnings/errors caused by mis-behaving robots and some development left-over diagnostic log messages.
* Updated language files of not complete, not polyglot released translations. Removed all fuzzy garbage and removed some languages that har hardly any sentences translated.
Fuzzy data still present is at least in the right language.
* Reduced number of queries for albums containing over 2500 photos; internal cache enlarged from 2500 to 5000.
* Reduced number of duplicate queries when art monkey link and/or Admins Choice is used.
* Reduced queries on iptc and exif tables.

= 6.4.14 =

= Bug Fixes =

* In shortcode type="search" root="#{album-id}" the search was not always limited to the photos below the album root. Fixed.

= New Features =

* New shortcode type="choice" admin="admin1,admin2,..." Displayes a box like the admins choice widget, with only the zips from the listed adminstrators/superusers.
If the admin="" arg is omitted, all available zips are being displayed. Background colors settable in Table III-B12.
* Send Email to photo owner when comment is approved. Table IV-F5.2.
* Set owner to the user who's display name equals photoname. Table IV-A28 to set the feature, and Table VIII-B18 to fix existing items.
* The ability to delete photos at the front-end is now separately settable in Table VII-D2.7, and no longer coupled to the right to edit.

= Other Changes =

* Reduced number of queries during upload/import.
* Changed selecions of Table VII-D2.2.

= 6.4.13 =

= Bug Fixes =

* Delete photo did not work on the album admin page due to a programming error. Fixed.

= 6.4.12 =

= Bug Fixes =

* Removed redundant page loads when using Ajax.

= New Features =

* Front end audio / video upload implemented. Mind your server limitations! Settable in Table II-H1.1 and II-H1.2. It is strongly recommended to keep II-H3 (User upload Ajax) ticked.

= Other Changes =

* The accept argument in front-end upload as well as the selction button text reflect the settings II-H1.1,2 (User upload Video/Audio), II-H15 (Camera connect) and VII-B6 (Upload one only).
* Cosmetic changes to the photo admin page in case of video.
* Various performance improvements.
* Added inline style display:inline; for cover images to overrule the css of certain themes.

= 6.4.11 =

= Bug Fixes =

* Fixed layout for front-end upload dialog on responsive themes.
* Removed the limit of 255 chars for photo tags and album cats.
* Default thumbnails of videos without poster image were not properly displayed. Fixed.
* Import photos now stops after timeout or lost connection.

= New Features =

* There is a new style front-end edit photo dialog. Related settings in Table VII-2.x.

= Other Changes =

* The front-end upload widget is now responsive on responsive themes without any action required.
* Improved algorithm to find an album by name. It is case insensitive.

= 6.4.10 =

= Bug Fixes =

* Non-standard oriented photos ( from mobile devices ) will now properly be displayed in all cases, including when Cloudinary is active and sources are used for lightbox, etc.
To fix existing photos, run Table VIII-A15.
* Admins choice widget damages layout for the logged in user having a zipfile. Fixed.

= Other Changes =

* Improved integrity check on settings, leading to performance improvement, especially when using qTranslate-x.
* The Admins choice widget no longer automaticly switches the feature on, but gives a warning when it is used and not switched on.
* If selecting album by $Name has more than one match, all album ids are returned.

= 6.4.09 =

= Bug Fixes =

* Fixed wrong aspect ratios of videos on lightbox. Make sure you set ether the default size values correct in Table I-H or on the individual edit photo pages.
* Auto started video on lightbox is no longer stopped by other running slideshows.
* Links with spaces not worked correctly when pretty links are enabled. Fixed.
* The Upload photo dialog on thumbnail areas of real albums now do no longer display an album selection list, but upload takes place to the album involved.

= New Features =

* Table IV-G9 allows you to select a starting mode for lightbox: normal or one of the fullsceen modes.

= Other Changes =

* The cached options are checked for validity by means of a hash check. This will fix spurious unexplainable changing settings.
* Fixed spurious error messages.
* Videos on lightbox in a set no longer display the start/stop running slideshow button to avoid confusion with the start video button.
* Opening lightbox now only stops the running slideshow where it is initiated from. Previously all shows were stopped, leading to unwanted behaviour in case of running filmonly as a banner applications.

= 6.4.08 =

= Bug Fixes =

* Mail should work always now.
* Download album link did not work when encryption was enabled. Fixed.
* Links do not work when qTranslate-x is active and pretty links is enabled. Fixed.
* Edit photo link on lightbox did not work if the lightbox was invoked on a different image than a slideshow image. Fixed.
* Fixed an issue with avatars not being displayed in all cases.

= New Features =

* The administrator can download any wppa databasa table as .csv file. See Table XII.

= Other Changes =

* You can set Table IV-F4 (Comment email required) now to 'Required', 'Optional' or 'None'.
* Performance improvements, especially of the settings admin page.

= 6.4.07 =

= Bug Fixes =

* When album names in urls enabled, and the name of one album also appears as a part of the name of an other album, the wrong album may be dsiplayed. Fixed.
* Album names in urls did not work on front page. Fixed.
* When the coverimage is a video and the covertype is imagefactory and there is no link on the coverimage, the image was not shown. Fixed.

= New Features =

* Added 'Medals Only' switch to the TopTen widget activation screen.
* Added 'Medals only' option to the #topten virtual album shortcode attribute: album="#topten,{album},{count},medals"

= Other Changes =

* Reduced pageload for slideonly displays.
* Added linktype slideshow for slideshows. This enables the linking from a small slideonly to a fullsize real slideshow.
This is ment to be used in inline settings only to prevent slideshows to link to them selves.
* You can limit the number of slides in a slideonly display in Table I-B10.
* Added Edit link on lightbox (if enabled in Table VII-D2.1).
* Dramaticly reduced duplicate queries. Thanx to the plugin: Query monitor.
* wp_mail() is now used as opposed to mail(), allowing other plugins to modify mail behaviour.

= 6.4.06 =

= Bug Fixes =

* Links containing tags that contained ampersands ( & ) were broken. Fixed.
* If no albums found at #cat in shortcode and cats include subs was ticked, all albums were selected. Fixed.
* The upload shortcode now also works with virtual album selections e.g.: [wppa type="upload" album="#cat,Mycat1;Mycat2"][/wppa]

= New Features =

* You can specify the target shortcode number ( occurrance ) for the Tagcloud widget in Table VI-C3d.
* You can specify the target shortcode number ( occurrance ) for the Tags filter widget in Table VI-C4d.
* New mechanism: Admins Choice. Enables the creation of zipfiles with selected photos by admin or wppa superusres.
Use the Admins Choice widget to make the files downloadable for users. Table IV-A27 to enable it, or just activate the widget.
* On the album admin -> Edit page, the album categor(y)(ies) can now be copied or added to to all (grand)children albums.
* New feature in album spec in a shortcode: album="#cat,Mycat|#tags,Mytag" results in photos with tag Mytag from albums with category Mycat. Multiple tags and cats are allowed.
Note #cat first, without an s, and #tags last, with an s.

= Other Changes =

* Removed calls to deprecated function get_currentuserinfo();

= 6.4.05 =

= Bug Fixes =

* Lightbox did not work on slideshow when it was set to ligtbox single image and there were no other links to lightbox. Fixed.

= Other Changes =

* Extended shortcode attribute: album="#cat,Category" now supports multiple categories:
album="#cat,Cat1;Cat2;Cat3" meaning all albums with category Cat1, Cat2 or Cat3.
album="#cat,Cat1,Cat2,Cat3" meaning all albums with category Cat1, Cat2 and Cat3.
* The search shortcode extensions introduced in version 6.4.03 are now supported by the shortcode generator.
* On hovering the lightbox image, all navigation buttons will fade-in and on leaving the image fad-out now rather than on the bottons themselves.

= 6.4.04 =

= Bug Fixes =

* Fixed a bug in the rights system.

= New Features =

* Table I-B9: Filmstrip Thumbnail Size. The size of the filmstrip images can now be set independantly.

= 6.4.03 =

= Bug Fixes =

* Under some circumstances the album table did not display where it should. Fixed.
* Use hires files for lightbox did not work on slideshows. Fixed.
* In Table XII, the display for WPPA_ALBUMS was incorrect for multisite installations with WPPA_MULTISITE_GLOBAL set to true. Fixed.
* Under some circumstances an empty alertbox appeared after front-end upload. Fixed.

= New Features =

* Extended search widget and shortcode functionality.
In the widget you can select an album as being a fixed search root ( selection will be done in the given album and its (grand)children only ).
Shortcode equivalence for album 47: [wppa type="search" root="#47"][/wppa].
In the widget you can select a landing page. Shortcode equivalence for page id 765: [wppa type="search" root="#47" landing="765"][/wppa].
In the shortcode you can enter any page/post id for the landing page/post, but make sure the page/post has a wppa shortcode - preferrably with: type="landing" - in its content.
Thes options are not yet supported by the shortcode generator.
* Table IV-A26. To switch off the automatic capitilisation of tags and cats.

= Other Changes =

* Removed Italian language files; they are now provided outside wppa by the polylang system.

= 6.4.02 =

= Bug Fixes =

* Removed scoped styles. They only work in Firefox.
* Fixed incorrect initialisation in Import, resulting in failing to import .csv file if no photo import had been done before.
* All photos from mobile devices should be oriented correctly now. If you want to fix the orientation of existing photos: Tick Table VIII-A11a and run VIII-A11.
* Fixed a php warning if smilies are disabled in wp but not in wppa.
* Comment notifications were not always sent when multiple users should receive them. Fixed.

= Other Changes =

* Table VII-B12: Fe alert. Shows alertbox on successful front-end upload/create. Can now be switched off.

= 6.4.01 =

= Bug Fixes =

* Under some circumstances the multitag widget did not work. Fixed.
* Could not update photo at the backend when sncrypted urls was active. Fixed.
* Move and copy photo did not work when the translated text of 'Please select an album to move the photo to first.' contained quotes as in French. Fixed.
* Filmstrip does not display correctly unless Table IV-A19 is ticked when encrypted urls is active. Fixed.

= Other Changes =

* Album crypt is shown on the album admin page.
* Added option: create 'no hotlinks' .htaccess files to Table IV-A18.

= 6.4.00 =

= New Features =

* Privacy phase one. In Table IV-A6.1 you can switch to encrypted urls. Photo and album identifiers in urls must be their encryption codes rather than their db table ids.
This prevents users from 'guessing' ids of photos or albums that they should not be able to see.
If Table IV-A6.2 is also ticked, unencrypted album and photo ids will be refused.
* Album navigator widget selection added: --- owner/public ---. This shows only the logged in users albums and public albums.
* Topten widget selection of albums: added --- owner/public ---. This shows only the photos from albums owned by the logged in user and from public albums.
* Featen widget selection of albums: added --- owner/public ---. This shows only the photos from albums owned by the logged in user and from public albums.
* Allow HTML tags in Custom data fields. Table IX-B1.1. If On: Only script and style tags are stripped, if off (default): All tags are stripped.

= Other Changes =

* Changed Table IV-A18: Enable photo html access to a choice of 'create .htaccess', 'remove .htaccess' (default) or 'do not change' to allow for a custom .htaccess file in ../uploads/wppa/ and ../wppa/thumbs/.
* Added w#rating to the photo description keywords, displaying the rating in float format.

= 6.3.18 =

= Bug Fixes =

* Photo of the day selection result for Display method Change every ( any time period ), when the Use albums selection is - top rated photos - returned Not found. Fixed.
* Go to fullscreen mode by icon button in lightbox did not work properly under certain circumstances. Fixed.
* On the frontpage the links are no longer 'prettyfied' to avoid 404 errors due to improper redirection.

= Other Changes =

* Photo of the day: Day of year is order #, has now jan 1 = 0, in stead of jan 1 = 366.
* Fixed a missing initialization.
* Added alt="..." for the fullscreen icon button in lightbox.
* Table II-H15 allows you to (dis)-connect frontend upload to the camera on mobile divices.
* Ajaxified breadcrumb links.

= 6.3.17 =

= Bug Fixes =

* Fixed plurals for i18n.
* Fixed a capacity issue in photo of the day.

= New Features =

* You can change album order if odering method is set to order# or order # desc in Table IV-D1 for the generc top-level albums, or in the album admin for the albums sub-albums.
The user must have access to all the (sub)albums to be able to change their sequence order number.

= 6.3.16 =

= Bug Fixes =

* Fixed a possible devide by zero error in wppa-breadcrumb.php
* Minor fixes to lightbox. Added 'q' and 'x' to quit(exit) lightbox from all modes.
* In some sites relative urls for lightbox navigation symbols did not work. Fixed.

= 6.3.15 =

= Bug Fixes =

* The keyboard handler for lightbox processed keystrokes twice. Fixed.
* The spinner on lightbox now behaves as designed.

= Other Changes =

* Removed Chech language files, they are now provided by wp polyglot system.
* Noticable improved (pre)loading algorithm for lightbox sets.

= 6.3.14 =

= Bug Fixes =

* The error messages for wrong setting input disappeared. Fixed.
* The album navigator widget vanished. Fixed.

= New Features =

* Alt thumbsize now also works for masonry style thumbnails, if the album is one real album.
The size is still an approximation, due to the implementation of filling the space.

= Other Changes =

* Changed defaults for Table VI-C11 to none, none.
* Lightbox has been face-lifted. See Table I-G3,4 and Table II-K.
* The recent patch for Windows 10 has been reverted. See https://wordpress.org/support/topic/front-end-uploader-not-working-in-explorer?replies=18

= 6.3.13 =

= Bug Fixes =

* Rating did not work when lightbox keyname is other than wppa. Fixed.
* Files with unsanitized names are now correctly removed in Import dir to album.

= New Features =

* You can select various types of layout for the sub-album links on album covers. Table VI-C11.
* Additional checkbox on the Import Photos screen when the input is set to --- My Depot --- or a subfolder thereof: Remove from depot after failed import.
* Added to the photo of the day settings screen: Change every: day of the year is photo order#. Added offset to all 3 day of ... options.

= Other Changes =

* Changed the default settings of the photo of the day feature, so it always works when there are photos and nothing has been configured.
* Layout changes/fixes to the photo of the day preview images.
* If you do not like the textual New and Modified labels, you can now specify urls to custom images. Defaults to the old new.png. See Table IX-D1.5, 1.8 and 1.9
* jQueryfied ajax and improved errorhandling in maintenance operations ( Table VIII ).

= 6.3.12 =

= Bug Fixes =

* The shortcode [wppa_set] stopped working. Fixed.
* Fixed crash in slideshow when language is French and slideshow contained photos with audio and/or video.

= New Features =

* The New indicator is no longer an image, but created with text/css. There is also an 'Modified' indicator, to indicate recently modified albums and photos.
The text and the colors of the indicaors can be set in Table IX-D1.5 and 1.6. Albums now also have a date/time modified stored in their meta data.
An album is now regarded to be 'modified' when the album metadata is changed or when new photos have been uploaded.
Both New and Modified qualifications of sub(sub)albums propagate upward to their (grand)parents.
New has a higher priority than Modified, i.e. if an item is both new and modified, only the new inicator is shown.
For related settings: See Table IX-D1.x.
* On Dir to album import from directories inside the users depot, you can now prevent deletion of the depot files. See Table IX-H17.

= Other Changes =

* Due to bugs in Windows10/Edge, The frontend upload will not use the Ajax method with the progression bar on the Edge browser.
* Table VI-C11 has an additional checkbox to indicate if you want sub-albums to be displayed also.

= 6.3.11 =

= Bug Fixes =

* Updating custom data fields of photo now updates date/time modified.

= New Features =

* You can decide to use date/time modified rather than dat/time of upload on LasTen widget/shortcode. Table IX-D2.2.
* Table VI-C11 makes it possible to show the sub-album names on album covers, linking to the content or the slideshow of the sub-album.
* New album cover image selection option: Most recent from (grand)children.

= Other Changes =

* The conversion to utf8 of iptc data is now optional ( Table IX-H16 ).
* If all albums have unique names, untick Table IX-H15 to get the old behaviour back of importing dirs to albums. ( No tree structure required ).

= 6.3.10 =

= Bug Fixes =

* The Odd/Even toggle of background colors stopped working a few revs ago. Fixed.
If you have unwanted background colors of album covers and/or thumbnail areas, change Table III-B1 ( make equal to III-B2 to get the previous results ).

= New Features =

* You can now import photos from 'outside' wp-content, even outside the wp install dir, on the same servers filesystem.
In Table IX-B17 you can specify the highest root where to search for imporatble files from.
The wppa source directories are not ment to import from, they are skipped by default; if you really want to import from those locations, tick Table IX-B18.
* You can now add an 'Inverse' checkbox to the Multitag widget / box to invert the selection. Table IX-E10.1.
* New shortcode type: url. Example: [wppa type="url" photo="4711"][/wppa]. Returns the url to the highest resolution file to the photo with id=4711.
Example use in template ( php ): echo '<img src=".do_shortcode('[wppa type="url" photo="4711"][/wppa]')." />';
This is equivalent to echo '<img src=".wppa_get_hires_url(4711)." />';, but you can use 'photo="#potd"' in the shortcode version.
Example use in page content: <img src='[wppa type="url" photo="4711"][/wppa]' />. Note the single quotes in the src attribute of img.

= Other Changes =

* Various minor cosmetic changes.
* The wppa session system is now more stable; logging in will not open a new session anymore. The session id is now only dependant of ip address and user agent.

= 6.3.9 =

= Bug Fixes =

* IPTC data is now converted to utf8 to accomodate for iso characters in iptc data.
* Album names with quotes broke slideshow. Fixed: Search root ( album name ) is now properly escaped.
* Fixed an incompatibility issue of the wppa search widget when used in a Beaver Page Builder page module rather than in a sidebar.
* Fixed a page link and link back problem from slideshow to thumbnails during search when pretty links were enabled.
* Album view count stopped to be bumped. Fixed.

= Other Changes =

* Changed the default value for Table VII-D1.1: Owners only to TRUE.
* It is no longer possible to mis-configure Table VII so that logged out users can edit name and description of albums at the front-end.

= 6.3.8 =

= Bug Fixes =

* The tagcloud widget now uses the font size settings from Table I-F13 and 14, like the shortcode variant already did.
* Phrases in wppa-theme.php will now properly be translated if a language file exists.
* All widgets now reset the 'in widget' switch correctly, preventing unwanted behaviour when widgets are displayed before shortcode initiated displays.
* The search widget/box now behaves as expected also in themes that display widgets before the page content.

= Other Changes =

* Many internal changes to improve stability.
* You can now add (html) text at the top of the search widget / box. Table IX-E15.
* Table IX-E16,17: You can now edit the label texts for root and sub search.
* Widget init no longer uses anonymous functions.
* Cosmetic changes to search widget/box.

= 6.3.7 =

= Bug Fixes =

* Stereo images were not correctly displayed when Fotomoto or Cloudinary was active. Fixed.
* Selecting any stereomode on photo admin page now always recreates thumbnail.
* If download album fails you will see a proper errormessage most of the time.
* The display of tags is now trimmed from comma's when converted from w#tags.

= Other Changes =

* Email notifications are now sent in plain text if the server can not handle emails containing html code.
* All ajax timeouts are now set to 60 seconds.
* Changed the minimum thumbnails for Imagefactory covers from 2 to 1.
* Cosmetic changes to the logfile ( Table VIII-C1 ).

= 6.3.6 =

= Bug Fixes =

* Scripts in the wppa text widget stopped working. Fixed.

= New Features =

* Support for 3d stero images. Enable in Table IV-A24.
* Table IX-A9: To switch off the loading of any language file for wppa+.

= Other Changes =

* Removed swedish and dutch language files, they are now maintained by the wp polyglot team.

= 6.3.5 =

= Bug Fixes =

* In album admin: the state of the wp editor ( Visible or Text ) will no longer change during an update.
* In Import, when importing dirs to albums, when sub dirs of different top dirs had the same name, photos were placed all in one of the sub albums. Fixed.
* Table VIII-B15: Sync Cloudinary Now works as expected. Table IX-K4.4: Update uploads has been removed, run Table VIII-B15 instead.

= Other Changes =

* Album Admin pages show a spinner during load.
* The frontend Ajax spinner is now always at the center of the screen, no longer cenetered on the wppa container.
* Table VII-D5: Comment captcha is now a selection box: none, logged out, all users.

= 6.3.4 =

= Bug Fixes =

* Smiley picker will no longer break ssl.

= Other Changes =

* You now switch off the recently implemented feature to run wppa shortcodes on wppa filter priority in Table IX-A1.3
* Use WP editor ( Table IX-B3 ) Now uses tinyMce. Tanx to xdanaskos.

= 6.3.3 =

= Bug Fixes =

* Fixed names of french language files
* Swipe did not work properly. Fixed.

= 6.3.2 =

= New Features =

* Central slideshow start/stop icons. See Table II-B13.2.

= Other Changes =

* To prevent damage to the html created by wppa, a new way to process shortcodes is implemented.
The expanded shortcode, produced at priority level 11, See Table IX-A1.2, is first saved in memory,
and later inserted into the page/post at a higher priority level, See Table IX-A1.1.
Not for widgets yet.
On templates, no longer use
<strong>do_shortcode( '[wppa ...][/wppa]' );</strong> but use
<strong>apply_filters( 'the_content, '[wppa ...][/wppa]' );</strong>

= 6.3.1 =

= Bug Fixes =

* When history.pushState fails, history.replaceState is attempted. This ensures updating the browser address line properly when Table IV-A7 is ticked.
* Files with uppercase extensions can be imported.

= New Features =

* Status filter on potd admin page.

= Other Changes =

* Changed the language system to comply with the wp standards for WordPress.org to manage translations for this plugin.
These changes imply that the separate plugin wppa-admin-language-pack no longer works;
but the translations will become automaticly updated ( on the wp update page ) in the near future.
* Improved compatibility with qTranslate-x.

= 6.2.12 =

= Bug Fixes =

* # was removed from tags, but also when it was needed for untranslated exif and iptc tags. Fixed.

= New Features =

* Table IV-F1.1: Comments view login. If set, existing comments are only visible for logged in visitors.

= 6.2.11 =

= Bug Fixes =

* If no tags or cats were used, the 'need conversion' message kept re-appearing. Fixed.
* Fixed a potential server error message in import files that occurred when the default upload album was deleted.
* Fixed an error in SuperSearch box when there are no phototags in the system.

= 6.2.10 =

= Bug Fixes =

* Added missing images for fullsize lightbox display buttons.
* The author of last comment given is now shown in the subtext on the comment widget and the last comment in its tooltip.
* Superview photos now shows photos only.

= New Features =

* Checkbox in Table VII-B11: Home after upload. After successfull upload, go to the home page.
* Table II-D4.1 Display Comment count under default Thumbnail.
* After ajax replaces wppa container content, the page will scroll to the right position.
* Table VIII-B10.1. Delete all auto pages.
* Table IX-B16: Confirm create ( album ). Asks if you really want to create an album on album admin page. Default on.

= Other Changes =

* The way photo tags and album cats are stored has changed, and the characters #, @ and & are added to the list of illegal characters in tags and cats.
If you use tags, you will be requested to run the update procedure Fix tags in Table VIII-B16, and Fix cats in Table VIII-B17.
* Improved way to find the origin sitename in notifiction emails.
* All js files are now in subdirectory js/
* Lifted the limitation that having more than approx 20.000 photos in an album or in the results of a search action on systems with 128 Mb caused an Memory exhausted server error.
You can now have 40.000 photos in an album or as a search result ( simple search, supersearch or tag(s) ) on systems with only 32 Mb available server memory without getting the error.
However, it is still recommended to have at least 64Mb memory available.

= 6.2.9 =

= Bug Fixes =

* In certain virtual albums, when the number of photos was less than the photocount treshold value, they did not show up where they should. Fixed.
* Local avatars now also work when the users email address is not required in coments.
* Fixed a capacity problem in search on tags ( multitag and tagcloud widgets ).
* If there is an album selection box in the frontend upload dialog and the submit button is pressed, it is now checked if an album has been selected before the upload is started.

= Other Changes =

* To create albums at the frontend, the pre-existance of any album that the user can upload to, is no longer required.
* The submit button and the ajax progression bar in the frontend upload dialog are moved to the bottom of the box.

= 6.2.8 =

= Bug Fixes =

* Uploader cache was not updated during front-end uploads, causing errors in user photo counts in the uploader widget. Fixed.

= New Features =

* The lightbox overlay now shows a icon link at the upper right corner to switch between fullscreen and normal mode.
You can switch it off in Table II-G20.

= Other Changes =

* Added image urls in Album Admin -> Edit -> Manage Photos.

= 6.2.7 =

= Bug Fixes =

* The fix in 6.2.6 for thumbnail style 'masonry rows' damaged 'masonry columns' style. Fixed.

= 6.2.6 =

= Bug Fixes =

* Local avatars were not found when the login name was different from the users display name. Fixed.
* Latin/ISO characters are now properly recognized in import .csv files.
* Thumbnail style 'masonry rows' now properly works for both responsive and static themes, even in IE and Chrome.
* Mouseover effect did not work correctly on masonry style thumbnails. Fixed.

= Other Changes =

* Roles that have wppa_moderate capability can edit/delete photos at the front-end, like administrators can.

= 6.2.5 =

= Bug Fixes =

* Files of type .pmf with unsanitized filenames were not recognized in Import Photos. Fixed.
* Fixed several potential undefined value warnings.

= New Features =

* Bulk edit can now also change photo owner if you are administrator and Table VII-D10 is ticked.
* Bulk edit has a quick delete link at each photo.

= Other Changes =

* Added calendar based shortcodes to the shorcode generator.

= 6.2.4 =

= New Features =

* Re-upload existing photo on the Photo admin screen and the front-end edit photo screen.
For administrators or for anybody that sees the edit photo screen when Table VII-C8 (Update photofiles restricted) is unchecked.
* Edit photo description can be disabled for non-administrators in Table VII-C7.
* Shortcode attribute reverse="1" for type="calendar", to get the youngest first.
* You can select black or lightgray for the Ugly Browse Buttons in Table II-B13.1

= 6.2.3 =

= Bug Fixes =

* Ajax links stopped working in Chrome and IE. Fixed.

= Other Changes =

* The album='..' attribute now works in the calendar shortcodes.
* Performance improvement in calendar shortcodes with attribute all="1".

= 6.2.2 =

= Bug Fixes =

= New Features =

* New shortcode type calendar. Calendar types: exifdtm, timestamp, modified. Optional argument: all="1" to initially display all.
examples: [wppa type="calendar" calendar="exifdtm"][/wppa], [wppa type="calendar" calendar="modified" all="1"][/wppa].
This feature requires Ajax active ( Table IV-A1.0 ).

= Other Changes =

* Exif date is now editable for administrators.
* Social Media Widget return link can specify the occurrance in Table VI-C10.
* improved performance on synchronisation with Cloudinary.

= 6.2.1 =

= Bug Fixes =

* Fixed a spurious problem in import from remote

= New Features =

* You can select either Home or Landing for the return link from social media shares that are invoked from widgets. Table VI-C10.

= Other Changes =

* Mods to Cloudinary support. Table IX-K4.4 no longer uploads over-aged photos.
* After rotating a photo, the stored dimensions are now properly reset.

= 6.2.0 =

= New Features =

* You can configure a limited use of Cloudinary CDN. In Table IX-K4.7 you can specify a max lifetime. Older photos will NOT be loaded from Cloudinary.
To remove them from Cloudinary, run Table VIII-B15 on a regular basis.

= Other Changes =

* This is a maintenance release for compatibility with PHP5 object constructors.

= 6.1.16 =

= Bug Fixes =

* If Show empty thumbnail area was on ( Table II-D18 ) and the album was an enumeration, the thumbnail page crashed. Fixed.

= New Features =

* Table IV-D2: Default cover photo selection method ( for new alums ), and added option '--- Random from (grand)children ---' on the album admin page.

= Other Changes =

* You can select a text for the close link in frontend upload/create/edit dialogs in Table II-J1.
* The switch Table IV-F10 now works for all non-admin receivers of comment notifications emails.
* If you set Table I-A9 to 0, the pagelink bar shows n/m in the center.

= 6.1.15 =

= Bug Fixes =

* Fixed a bug in a security check on front-end album deletion.
* Fixed high resolution image urls for videos / audios on slideshows.

= New Features =

* WPPA+ Text widget has now an extra checkbox, to set if you want the widget to be seen by logged in users only.
* Table II-D18: Show empty thumbnail area. Check this to see the thumbnail area of empty albums for the upload link in it.
* There are now close links in the front-end upload and album edit / create dialogs.

= Other Changes =

* Improved compatibility with lightbox plugin prettyPhoto. Slideshows work on it ( but still no videos or audios ).
* There are no loger empty titles or titles with only a space on images.
* If the WPPA+ embedded lightbox is used, the subtitles are now transferred to lightbox by data-lbtitle="..",
to prevent hughe tooltip boxes while hovering over the image that links to lightbox while Table II-G1 is unticked.
If you use a non-default lightbox, make sure the liughtbox titles are empty, or Table II-G91 is ticked.

= 6.1.14 =

= Bug Fixes =

* On servers where the function readdir() not properly workes, the import page never showed up. Fixed.
* Many minor fixes for w3c validation.

= Other Changes =

* For browsers that display empty tooltip boxes: there are no longer empty title attributes generated.
* wppa lightbox now uses data-rel="wppa" to meet w3c standards.
* Table II-G19: Overlay show legenda. Regardless of this setting, it will not show up on mobile devices,
but the keyboard handler will be installed to facillitate tablet/laptop converable devices.
* There was a serious performance problem with the new smilies: emoji.
Especially on firefox and using ajax On one of my testsites a slideshow with 15 slides and comments enabled and the smiley picker displayed
used to take 4 seconds to load. Now it takes up to a minute; the browser even does not respond for over 50 seconds.
As a work around for this, i coded my own convert_smilies() function: wppa_convert_smilies(), located in wppa_utils.php,
just for the creation of the html for the smileypicker and the smilies in the comments on photos.
It still uses the emoji images, but by direct coding and not through a character code.

= 6.1.13 =

* Intermediate test version, not released.

= 6.1.12 =

= Bug Fixes =

* Rotating an image will always produce a rotated thumbnail created out of the display file, regardless of setting Table IX-F12.
If you have rotated images and you want to remake all thumbnails and you have source files saved, tick Table IX-F12 to make sure all thumbnails will have the right orientation.
* Thumbnail type *masonry style rows* is now usable on static themes. There still is a problem with Thumbnail type ( Table IV-C3 ) *masonry style rows.*
On static themes: untick Table IV-C6: *Thumb mouseover* to fix the behaviour in Internet Explorer.
On responsive themes, in Internet Explorer and Google Chrome show odd layouts. Do not use *masonry style rows* on responive themes until this issue is fixed.
*masonry style columns* works as expected in all browsers, both in responsive and static themes.
* Layout fix on album cover if album full.

= New Features =

* Topten Widget can have owner and album displayed in the subtitle, album will be a link to the photos album.
* You can now also use keywords for exif and iptc labels in photo descriptions. Use *2#L080* for *Photographer:*, *E#L9003* For *Date time original* etc, where *2#080* and *E#9003* return the photo specific data..
* New settings for lightbox: Table II-G18 and 19 to hide Start/Stop and Fullscreen legenda.

= 6.1.11 =

= Bug Fixes =

* Fixed an error in statistics for logged out visitors.

= New Features =

* Bulk import custom data. see http://wppa.opajaap.nl/using-custom-data-fields/

= 6.1.10 =

= Bug Fixes =

* Supersearch now also works in I.E.
* Fixed breadcrumb for supersearch displays.

= New Features =

* New photo status: private. Private photos are only shown to logged in visitors. This will only work in normal pageviews. A full url to an image file will not be rejected.

= Other Changes =

* Performance improvements in supersearch.
* Added Table IX-13 and E-14 to reduce select box options.
* Exif and Iptc systems now clean up garbage automaticly.
* Removed 'hover to select' and 'click to add/remove' from supersearch selectboxes because i.e. does not support event handlers on option tags.

= 6.1.9 =

= Bug Fixes =

* Fixed a layout issue of Com alt displays on responsive themes.

= New Features =

* The default album cover linktype that will be set at album creation is now settable in Table IX-D18.
* New shortcode: type="supersearch". Related settings: Table VI-C9, Table IX-E13.

= Other Changes =

* Split js files in logical units, prevent loading of not used code.

= 6.1.8 =

= Bug Fixes =

* Custom data is now properly indexed ( 6.1.7.001 )
* Skip empty albums now correctly tests on user role administrator as opposed to capability wppa_admin.
* Thumbnail popup did not work properly on chrome browser on certain themes. Fixed.
* Fixed potential problems with setting options that have leading or trailing spaces.

= 6.1.7 =

= New Features =

* Custom datafields are now imputtable at the front-end upload dialog box. Table II-H10. Tags switch is now Table II-H11.

= Other Changes =

* Cosmetic changes in page link bar.
* Set input field width in seach box to 60%, added class wppa-search-input to input field. This fixes a lay-out issue on theme twentyfifteen.

= 6.1.6 =

= Bug Fixes =

* If no album selected in frontend upload widget/box an alertbox will be displayed.
* Fotomoto 'hide when running' now works.

= New Features =

* Up to 10 custom datafields for photos can be defined. See Table II-J10(.x).
See http://wppa.opajaap.nl/using-custom-data-fields/ for an explanation.

= Other Changes =

* wppa.js is now split into 4 files.
* All front-end ajax actions are now asynchronous using jQuery.ajax().

= 6.1.5 =

= Bug Fixes =

* Selected albums did not show up in album selection lists. Fixed.
* Alt thumbsizes stopped working. Fixed.

= 6.1.4 =

= Bug Fixes =

* Fixed a regression vs 6.1.2: The upload link on an album should only show up if the user hass album access to the album.

= Other Changes =

* Setting Table VII-D1 ( Owner only ) has been split into VII-D1.1 referring to album admin access and VII-D1.2 refering to album upload access.

= 6.1.3 =

= Bug Fixes =

* The message: 'Comment added' stopped to be displayed even if Table IV-F6 was ticked. Fixed.
* Smilies in photo descriptions are now displayed.
* Improved randomness of random selected photos in multiple albums.
* Fixed consistency in random sequence between first and successive pages in paginated displays.
* Improved algorithm to decide when to display the front-end upload and creat album link.
* Fixed an inconsistency in rights on the album admin table.
* Security fix.

= New Features =

* The ability to limit the number of albums for a user based on user role. Table VII-B5a.x

= 6.1.2 =

= Bug Fixes =

* The new wp (4.2) implementation of smileys broke the smileypicker. Fixed.
* The BestOf widget could not handle video's. Fixed.

= 6.1.1 =

= Bug Fixes =

* Poster image files could no be import-updated. Fixed.
* The photo of the day widget could not handle videos. Fixed.

= New Features =

* Audio support. Supported filetypes: .mp3, .wav and .ogg.
* Added filetype .jpeg for photos.

= Other Changes =

* Table II-D ( Visibility: Thumbnails ) has been renumbered.
* When a video plays, a running slideshow will be suspended until the video finishes. Same for videos on running lightbox slideshows.
* Fixed a lay-out issue on horizontal masonry thumbnails.
* The thumbnail subtext is now displayed as title ( hover text ) on masonry style thumbnails.

= 6.0.0 =

= New Features =

* The support of videos. You can mix photos and videos throughout the system including lightbox.
See the <a href="http://wppa.opajaap.nl/video-support/">documentation.</a>

= Other Changes =

* Added link types to various virtual album widgets.

== About and Credits ==

* WP Photo Album Plus is extended with many new features and is maintained by J.N. Breetvelt, ( http://www.opajaap.nl/ ) a.k.a. OpaJaap
* Thanx to R.J. Kaplan for WP Photo Album 1.5.1, the basis of this plugin.

== Licence ==

WP Photo Album is released under the GNU GPL licence. ( http://www.gnu.org/copyleft/gpl.html ))