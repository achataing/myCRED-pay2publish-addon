# myCRED-pay2publish-addon
This plugin is an addon for myCRED plugin. It allows you to charge for Publishing Pages, Post, Custom Post Types both in backend and frontend (you must wrap your form using the shortcode [p2p_price]). Requires myCRED version 1.4 and above


In order to use this plugin myCRED must be installed and configured
=== Plugin Name ===
Contributors: achataing
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JRE6UZXBJK5VL
Tags: myCRED, Pay, publish, pages, post, custom post type, pay to publish, shortcode, front-end, frontend publishing payments
Requires at least: 3.0.1
Tested up to: 4.3.1
Stable tag: 4.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is an addon for myCRED plugin. It allows you to charge for Publishing Pages, Post, Custom Post Types both in backend and frontend (you must wrap your form using the shortcode [p2p_price]). Requires myCRED version 1.4 and above.

== Description ==
This plugin requires myCRED plugin installed and configured.

With this plugin you can set rules for each myCRED Type and Post type including Pages, Posts and Custom Post Types. It is simple to use and comes bundled with a shortcode [p2p_price] that you can use to charge for front-end publishing as well.

It is program to charge the amount of myCRED type you decide and it supports more than 1 Point Type
This is the long description.


A few notes about the sections above:

*   "Contributors" achataing
*   "Tags" myCRED, Pay, publish, pages, post, custom post type, pay to publish, shortcode, front-end, frontend publishing
*   "Requires Wordpress 3.0.1 and myCRED 1.4 and abote
*   "Tested up to" 4.3.1


== Installation ==

From your WordPress dashboard

    Visit 'Plugins > Add New'
    Search for 'WPLMS MyCred Addon'
    Activate WPLMS MyCred from your Plugins page.

From WordPress.org

    Download WPLMS MyCred.
    Upload the 'WPLMS-MyCred-addon' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
    Activate WPLMS MyCred from your Plugins page.


== Frequently Asked Questions ==

= How to setup a price for publishing a post? =

Since myCRED allows you to create several types of credits, you must check under the "Mycred" menu for the "Pay 2 Publish" submenu if you have nothing but the default myCRED type. But, if you have more than 1 myCRED Type, then check under each mycred_type menu, and you will there find the "Pay 2 Publish" submenu under each myCRED_Type menu. Click there and then press "Add new price". Pick a post_type (Pages are included) and then select a price and save.</p><p>Note: If you select "Any" from the drop-down field, this rule will override any other rules previously set. Also, if you create 2 rules of the same post type, only the first one will apply

= What happens if I set several rules on the same Post Type? =

If you create 2 or more rules for the same POST type inside the same myCRED Type, then only the first rule of that POST Type for that myCRED POINT Type will apply...<p>But, if you have a rule for the same POST Type in different myCRED Types, then the user will be charged in every single Point Types that have a rule on that particular Post Type

= How to apply the stablished rules for front-end publishing =

This plugin comes bundled with a shortcode [p2p_price] use it to apply the same rules you have set for front-end publishing as follows: [p2p_price message="Not enough funds" type="post_type"][Your-form-shortcode][/p2p_price]. Please note that you can override the global message if you decide to use the message attribute. IMPORTANT! since there is no way to know from the front-end which Post Type is being published, it is a must to provide it here manually unless or it will default to post_type="post

= What happens if my front-end form sets the post Status to "Pending" instead of publish? =

If that is the case, then the user will not be charged for that post until its status is set to "Publish". IMPORTANT: This plugin is programmed to charge a user when the status goes from "Pending" to "Published" only.... This means, if the status is manually set back to "Pending" and then back to "Published" the user will be charged agai!n

== Screenshots ==

1. This screen shot description corresponds to the panel where the administrator or superadmins can add a new price rule. Corresponds to screenshot_1.png
2. This is the second screen shot when you click on "Add new price". Screenshot_2.png
3. The main panel where the global message can be set. Screenshot_3.png
4. This screenshot is that message displayed when the user tries to add a new post. Screenshot_4.png
5. Add new link is removed is the user does not have enough credit. Screenshot_5.png
6. This screenshot shows a message at the front-end when a user tries to publish and has no credits. Screenshot_6.png

== Changelog ==

= 1.0 =
* First release of this plugin
