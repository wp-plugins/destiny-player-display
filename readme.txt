=== Destiny Player Display ===
Contributors: james.gooding, richard.white
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=J2SAU67CD7QW8
Tags: destiny, api, playstation, xbox
Requires at least: 3.2
Tested up to: 4.2.2
Stable tag: 1.2
License: GPLv2 or later

Destiny Player Display provides a widget/shortcode to display your Destiny scorecard on the Wordpress platform.

== Description ==

Destiny Player Display provides a widget/shortcode to display your Destiny scorecard on the Wordpress platform.
Both XBOX and PlayStation platforms are supported.

Major features in Destiny Player Display include:

* Support for XBOX Live and PlayStation Network.
* Any characters on a Destiny account can be selected.
* Emblem and banner graphics are displayed.
* Multiple accounts can be displayed.
* Statistics for both story and crucible mode are displayed and automatically updated by Bungie.

== Installation ==

Upload the Destiny Player Display plugin to your blog and activate it.

Note: The allow_url_fopen directive needs to be enabled in your php.ini configuration to allow the plugin to call the
Bungie Destiny API.

== Screenshots ==

1. Destiny scorecard as it will appear on your site.
2. Configuring the widget settings in Wordpress.
3. The completed widget configuration panel.

== Frequently Asked Questions ==

= Are Wordpress shortcodes supported =

Yes. Once you have activated the plugin, create a new instance of the widget in the 'Inactive Widgets' panel.
Use the shortcode provided wherever you would like the widget to appear.

= Will I need to authenticate with Bungie to pull my player data =

No, a Destiny username and platform are all that is needed to pull character data. You will never have to log on to
use the plugin.

= Am I limited on the number of character widgets I can display on the page? =

No. Although, you probably want to keep the number of characters to a minimum to ensure optimal page load times.

= Are both XBOX and Playstation users supported =

Yes! You can select either XBOX or PSN in the widget settings panel.

= Does the widget support Destiny clans? =

Not at the moment, but we're looking into it for a future release. All suggestions welcome!

== Changelog ==

= 1.2 =
*Release Date - 4th July, 2015*

* Calls to Bungie API now use cURL rather than file_get_contents

= 1.1 =
*Release Date - 27th June, 2015*

* Removed inline styling and base64 encoded images.
* Minified CSS.
* Better error handling.
* Inclusion of sepia images.

= 1.0 =
*Release Date - 21st June, 2015*

* First release.

 == Upgrade Notice ==

= 1.2. =
 Removed file_get_contents in favour of cURL for maximum performance and compatibility.

 = 1.1. =
 Minified CSS to improve page load time. Friendly error message when widget can't connect to the Bungie API.