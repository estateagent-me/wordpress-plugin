=== EstateAgent.Me ===
Contributors: dazecoop
Requires at least: 4.1
Tested up to: 5.8.2
Requires PHP: 5.4
Stable tag: 1.2.2

== Description ==
List your properties on a WordPress-powered site via your EstateAgent.Me Agent Account

== Changelog ==
= 1.2.2 =
* Removed plugin dependency of 'WP Control', utilising built-in CRON functionality instead

= 1.2.1 =
* Fixed XML object/array discrepancy upon CRON update attempt

= 1.2.0 =
* Moved to Github for hosting public repository
* Enabled use of EA_DOMAIN, controllable via conf.ini file
* Improved hiding of property types if no properties

= 1.1.93 =
* Fixed unparenthesized support on property search
* Fixed 'Include under offer' checkbox layout issue on some themes
* Hidden property types on search if there are no properties

= 1.1.92 =
* Allow pre-selection of 'Sale' or 'Rent' on Property search via new option on settings page

= 1.1.91 =
* Only show 'available' properties for 'Featured properties'

= 1.1.9 =
* Added 'Login' page, giving ability for Vendors to log into their dashboard
* Added 'Featured properties' on 'Properties' page if no search is performed

= 1.1.8 =
* Bug in search result query with non-standard SQL prefixing

= 1.1.7 =
* Removal of debug code

= 1.1.6 =
* Version bump

= 1.1.5 =
* Property search results list & map views

= 1.1.4 =
* Property types search separated into dependent drop-downs to select parent/child property type

= 1.1.3 =
* Property details media able to open in Lightbox for larger versions
* Property details media showing captions on images
* Property details page showing features below short description
* Property details page show Floorplan/EPC PDF's below descriptions

= 1.1.2 =
* Property details page incorrectly filtering EPC & Floorplans

= 1.1.1 =
* Moved third party CSS/JS scripts to Cloudflare CDN for improved performance
* Moved majority of internal CSS to EstateAgent.Me self CDN
* Removed development environmental variables

= 1.1.0 =
* Improved settings page layout & notice/warning's
* Attempt an XML cURL run on detected first install

= 1.0.99 =
* Passing additional site info to API calls for improved future debugging
* Auto detect if WP-Control plugin is installed & warn if not
* Improved installation instructions

= 1.0.98 =
* Search form switches price sale/rent based on selection
* Improved for sale/for rent search functionality

= 1.0.97 =
* Installation of css-element-queries for ability to control media-queries at container width
* Include max/min price & max/min beds on search
* Improved search form layout based on container width as well as viewport width

= 1.0.96 =
* Switching to built-in jQuery, auto-detect if already init
* Update readme ;)

= 1.0.95 =
* Stripping margin's on .form-control's
* Adding versioning to assets

= 1.0.94 =
* Excluding PDF's from Property Details images
* Moving from $() to jQuery() for improved compatibility
* Adding script dependencies for improved compatibility

= 1.0.93 =
* Change to Property Results cards to include 1st line address