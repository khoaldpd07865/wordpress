=== GA Google Analytics – Connect Google Analytics to WordPress ===

Plugin Name: GA Google Analytics
Plugin URI: https://perishablepress.com/google-analytics-plugin/
Description: Adds your Google Analytics Tracking Code to your WordPress site.
Tags: analytics, google, google analytics, tracking, statistics
Author: Jeff Starr
Author URI: https://plugin-planet.com/
Donate link: https://monzillamedia.com/donate.html
Contributors: specialk
Requires at least: 4.6
Tested up to: 6.5
Stable tag: 20240308
Version:    20240308
Requires PHP: 5.6.20
Text Domain: ga-google-analytics
Domain Path: /languages
License: GPL v2 or later

Adds Google Analytics tracking code to your WordPress site. Supports GA4 and many tracking features.



== Description ==

> Connects Google Analytics to WordPress
> Supports Universal Analytics / analytics.js
> Supports Global Site Tag / gtag.js
> Supports Google Analytics 4

This plugin enables Google Analytics for your entire WordPress site. Lightweight and fast with plenty of great features.


### GA Tracking Options ###

* [Google Tag](https://developers.google.com/analytics/devguides/collection/gtagjs/) / gtag.js
* [Universal Analytics](https://developers.google.com/analytics/devguides/collection/analyticsjs/) / analytics.js
* [Legacy](https://developers.google.com/analytics/devguides/collection/gajs/) / ga.js

__Note:__ Google renamed "Global Site Tag" to "Google Tag".


### Enable Google Analytics 4 ###

Steps to enable Google Analytics 4:

1. Follow [this guide](https://support.google.com/analytics/answer/9306384) to create a GA 4 account
2. During account creation, you'll get a tracking (measurement) ID
3. Add your new tracking ID to the plugin setting, "GA Tracking ID"
4. Select "Google Tag" for the plugin setting, "Tracking Method"

Save changes and done. Wait 24-48 hours before viewing collected data in your GA account.


### GA Feature Support ###

* Supports [Google Analytics 4](https://support.google.com/analytics/answer/9306384)
* Supports [Display Advertising](https://support.google.com/analytics/answer/2444872)
* Supports [Enhanced Link Attribution](https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-link-attribution)
* Supports [IP Anonymization](https://developers.google.com/analytics/devguides/collection/analyticsjs/ip-anonymization)
* Supports [Force SSL](https://developers.google.com/analytics/devguides/collection/analyticsjs/field-reference#forceSSL)
* Supports [Tracker Objects](https://developers.google.com/analytics/devguides/collection/analyticsjs/creating-trackers)
* Supports [Google Optimize](https://support.google.com/optimize/answer/7513085)
* Supports [User Opt-Out](https://developers.google.com/analytics/devguides/collection/analyticsjs/user-opt-out)

Also supports tracking links and conversions via the Custom Code setting. Learn more about [Google Analytics](https://www.google.com/analytics/)!


### Features ###

* Blazing fast performance
* Does one thing and does it well
* Drop-dead simple and easy to use
* Regularly updated and "future proof"
* Stays current with the latest tracking code
* Includes tracking code in header or footer
* Includes tracking code on all WordPress web pages
* Includes option to add your own custom markup
* Sleek plugin Settings page with toggling panels
* Option to disable tracking of admin-level users
* Option to enable page tracking in the Admin Area
* Works with or without Gutenberg Block Editor
* Easy to customize the tracking code
* More features available in the [Pro version&nbsp;&raquo;](https://plugin-planet.com/ga-google-analytics-pro/)

This is a lightweight plugin that inserts the required GA tracking code. To view your site statistics, visit your Google Analytics account.


### Pro Version ###

[GA Google Analytics Pro](https://plugin-planet.com/ga-google-analytics-pro/) includes the same features as the free version, PLUS the following:

* Visitor Opt-Out Box (frontend UI)
* Configure multiple tracking codes
* Live Preview of all tracking codes
* Choose location of multiple tracking codes
* Supports Custom Code in header or footer
* Disable tracking of all logged-in users
* Disable Tracking for any Post IDs, User Roles, Post Types
* Disable Tracking for Search Results and Post Archives
* Display Opt-Out Box automatically or via shortcode
* Complete Inline Help/Documentation
* Priority plugin help and support

Learn more and get [GA Pro &raquo;](https://plugin-planet.com/ga-google-analytics-pro/)


### Privacy ###

__User Data:__ This plugin does not collect any user data. Even so, the tracking code added by this plugin is used by Google to collect all sorts of user data. You can learn more about Google Privacy [here](https://policies.google.com/privacy?hl=en-US).

__Cookies:__ This plugin uses simple cookies for the visitor Opt-Out Box to remember user preference for opt-in or out of Google Analytics.

__Services:__ This plugin does not connect to any third-party locations or services, but it does enable Google to collect all sorts of data.

GA Google Analytics is developed and maintained by [Jeff Starr](https://twitter.com/perishable), 15-year [WordPress developer](https://plugin-planet.com/) and [book author](https://books.perishablepress.com/).


### Support development ###

I develop and maintain this free plugin with love for the WordPress community. To show support, you can [make a donation](https://monzillamedia.com/donate.html) or purchase one of my books: 

* [The Tao of WordPress](https://wp-tao.com/)
* [Digging into WordPress](https://digwp.com/)
* [.htaccess made easy](https://htaccessbook.com/)
* [WordPress Themes In Depth](https://wp-tao.com/wordpress-themes-book/)
* [Wizard's SQL Recipes for WordPress](https://books.perishablepress.com/downloads/wizards-collection-sql-recipes-wordpress/)

And/or purchase one of my premium WordPress plugins:

* [BBQ Pro](https://plugin-planet.com/bbq-pro/) - Super fast WordPress firewall
* [Blackhole Pro](https://plugin-planet.com/blackhole-pro/) - Automatically block bad bots
* [Banhammer Pro](https://plugin-planet.com/banhammer-pro/) - Monitor traffic and ban the bad guys
* [GA Google Analytics Pro](https://plugin-planet.com/ga-google-analytics-pro/) - Connect WordPress to Google Analytics
* [Simple Ajax Chat Pro](https://plugin-planet.com/simple-ajax-chat-pro/) - Unlimited chat rooms
* [USP Pro](https://plugin-planet.com/usp-pro/) - Unlimited front-end forms

Links, tweets and likes also appreciated. Thank you! :)



== Installation ==

### How to install the plugin ###

1. Upload the plugin to your blog and activate
2. Visit the settings to configure your options

After configuring your settings, you can verify that GA tracking code is included by viewing the source code of your web pages.

__Note:__ this plugin adds the required GA code to your web pages. In order for the code to do anything, it must correspond to an active, properly configured Google Analytics account. Learn more at the [Google Analytics Help Center](https://support.google.com/analytics/?hl=en#topic=3544906).

[More info on installing WP plugins &raquo;](https://wordpress.org/support/article/managing-plugins/#installing-plugins)


### How to use the plugin ###

To enable Google Analytics tracking on your site, follow these steps:

1. Visit the plugin settings
2. Toggle open the "Plugin Settings" panel
3. In the first setting, "GA Tracking ID", enter your Tracking ID [1]
4. In the next setting, "Tracking Method", choose either [Google Tag](https://developers.google.com/analytics/devguides/collection/gtagjs/) or [Universal Analytics](https://developers.google.com/analytics/devguides/collection/analyticsjs/) [2]
5. Configure any other plugin settings as desired (optional)

Save changes and done. After 24-48 hours, you can log into your Google Analytics account to view your stats.


__* Notes:__

[1] The "Tracking ID" also may be referred to as "Tag ID", "Measurement ID", or "Property ID", depending on various factors.

[2] Google Tag (aka, Global Site Tag) is required for Google Analytics 4. For steps on setting up GA 4, check out the [plugin homepage](https://wordpress.org/plugins/ga-google-analytics/) (under "GA Tracking Options"). This information also is available at [Plugin Planet](https://plugin-planet.com/ga-pro-enable-google-analytics-4/).

Also note that it can take 24-48 hours after adding the tracking code before any analytical data appears in your [Google Analytics account](https://developers.google.com/analytics/). To check that the GA tacking code is included, look at the source code of your web page(s). Learn more at the [Google Analytics Help Center](https://support.google.com/analytics/?hl=en#topic=3544906).


### Upgrading Analytics ###

Google Analytics tracking methods change over time. First there was `urchin.js`, then `ga.js`, and now `analytics.js`, soon to be replaced officially by `gtag.js`. If you are using an older version and want to upgrade, check out these Google docs:

* [Universal Analytics Upgrade Center](https://developers.google.com/analytics/devguides/collection/upgrade/)
* [Migrate from analytics.js to gtag.js](https://developers.google.com/analytics/devguides/collection/gtagjs/migration)


### Plugin Upgrades ###

To upgrade GA Google Analytics, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

__Note:__ uninstalling the plugin from the WP Plugins screen results in the removal of all settings from the WP database. 

For more information, visit the [GA Plugin Homepage](https://perishablepress.com/google-analytics-plugin/).


### Restore Default Options ###

To restore default plugin options, either uninstall/reinstall the plugin, or visit the plugin settings &gt; Restore Default Options.


### Uninstalling ###

GA Google Analytics cleans up after itself. All plugin settings will be removed from your database when the plugin is uninstalled via the Plugins screen. Your collected GA data will remain in your Google account.


### Pro Version ###

Want more control over your GA Tracking codes? With awesome features like Opt-Out Box and Code Previews? Check out [GA Pro &raquo;](https://plugin-planet.com/ga-google-analytics-pro/)


### Like the plugin? ###

If you like GA Google Analytics, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/ga-google-analytics/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!



== Screenshots ==

1. GA Google Analytics: Plugin Settings (default)
2. GA Google Analytics: Plugin Settings (expanded)

More screenshots available at the [GA Plugin Homepage](https://perishablepress.com/google-analytics-plugin/).



== Upgrade Notice ==

To upgrade GA Google Analytics, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

__Note:__ uninstalling the plugin from the WP Plugins screen results in the removal of all settings from the WP database. 

For more information, visit the [GA Plugin Homepage](https://perishablepress.com/google-analytics-plugin/).



== Frequently Asked Questions ==


**How to enable Google Analytics 4?**

To enable __Google Analytics 4__: 

1. Follow [this guide](https://support.google.com/analytics/answer/9306384) to create a GA 4 account
2. During account creation, you'll get a tracking (measurement) ID
3. Add your new tracking ID to the plugin setting, "GA Tracking ID"
4. Select "Google Tag" for the plugin setting, "Tracking Method"

Save changes and done. Wait 24-48 hours before viewing collected data in your GA account.


**I am confused about all the different tracking methods?**

This article should help to get a better idea of the changes: [History of Google Analytics](https://onward.justia.com/history-of-google-analytics/)


**Tracking code is not displayed in source code?**

If you check the source code of your pages and don't see the GA tracking code, check the following:

* Check that your theme includes the hooks, `wp_head` and `wp_footer`
* If you are using a caching plugin, try clearing the cache

If the GA tracking code still is not displayed, most likely there is interference from another plugin or theme. In this case, the best way to resolve the issue is to do some basic [WordPress troubleshooting](https://perishablepress.com/how-to-troubleshoot-wordpress/).


**Google Analytic says tracking code is not detected?**

You need to wait awhile for Google to collect some data, like at least a day or whatever. Standard stuff for Google Analytics. For more information, check out the [Google Analytics Help Center](https://support.google.com/analytics/?hl=en#topic=3544906).


**Can I filter the output of the "Custom GA Code" setting?**

Yes, you can use the `gap_custom_code` filter hook.


**How to implement Google Optimize?**

Here are the steps:

1. Enable Universal Analytics in the plugin settings
2. Add the Optimize plugin (e.g., `ga('require', 'GTM-XXXXXX');`) to the setting, "Custom GA Code"
3. Add the Page Hiding (flicker) snippet to the setting, "Custom &lt;head&gt; Code"
4. Enable the setting, "Custom &lt;head&gt; Location"

Done! You can view the source code of your web pages to verify the results.

More info about [Google Optimize](https://support.google.com/optimize/answer/6262084).


**How to enable Opt-out of tracking?**

Here are the steps:

1. Add the following code to the plugin setting, "Custom Code": `<script>window['ga-disable-UA-XXXXX-Y'] = true;</script>`
2. Check the box to enable the setting, "Custom Code Location".

Done! You can view the source code of your web pages to verify the results.

More info about [user opt-out](https://developers.google.com/analytics/devguides/collection/analyticsjs/user-opt-out).


**How to disable the "auto" parameter in ga(create)?**

By default the plugin includes the `auto` parameter in the tracking code:

	ga('create', 'GA-123456789000', 'auto');

However some tracking techniques (such as Site Speed Sample Rate) require replacing the `auto` parameter. To do it:

First disable the `auto` parameter by adding the following code to WordPress functions or [custom plugin](https://digwp.com/2022/02/custom-code-wordpress/):

	// GA Google Analytics - Disable auto parameter
	function ga_google_analytics_enable_auto($enable) { return false; }
	add_filter('ga_google_analytics_enable_auto', 'ga_google_analytics_enable_auto');

Now that `auto` is disabled, you can replace it with your own parameter(s). For example, to implement Universal Analytics Site Speed Sample Rate, enter the following code in the plugin setting "Custom Tracker Objects":

	{'siteSpeedSampleRate': 100}

Save changes and done. The resulting tracking code will now look like this:

	ga('create', 'GA-123456789000', {'siteSpeedSampleRate': 100});

So can adjust things as needed to add any parameters that are required.


**How to implement Anonymize?**

1. Add to "Custom Tracker Objects" setting: `{ 'anonymize_ip': true }`
2. Save changes and done.


**Got a question?**

To ask a question, suggest a feature, or provide feedback, [contact me directly](https://plugin-planet.com/support/#contact). Learn more about [Google Analytics](https://www.google.com/analytics/) and [GA tracking methods](https://perishablepress.com/3-ways-track-google-analytics/).



== Changelog ==

*Thank you to everyone who shares feedback for GA Google Analytics!*

If you like GA Google Analytics, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/ga-google-analytics/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!

> New Pro version available! Check out [GA Pro &raquo;](https://plugin-planet.com/ga-google-analytics-pro/)


**20240308**

* Updates plugin settings page
* Updates default translation template
* Improves plugin docs/readme.txt
* Tests on WordPress 6.5 (beta)


Full changelog @ [https://plugin-planet.com/wp/changelog/ga-google-analytics.txt](https://plugin-planet.com/wp/changelog/ga-google-analytics.txt)
