=== YITH WooCommerce moodboard ===

Contributors: yithemes
Tags: moodboard, woocommerce, products, themes, yit, e-commerce, shop, ecommerce moodboard, yith, woocommerce moodboard, woocommerce 2.3 ready, shop moodboard
Requires at least: 4.0
Tested up to: 4.5.2
Stable tag: 2.0.16
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

YITH WooCommerce moodboard add all moodboard features to your website. Needs WooCommerce to work.
WooCommerce 2.6.x compatible.


== Description ==

What can really make the difference in conversions and amount of sales is without a doubt the freedom to share your own moodboard, even on social networks, increasing indirect sales: can you imagine the sales volume you can generate during holidays or birthdays, when relatives and friends will be looking for the moodboard of your clients to buy a gift?

Offer to your visitors a chance to add the products of your woocommerce store to a moodboard page. With YITH WooCommerce moodboard you can add a link in each product detail page,
in order to add the products to the moodboard page. The plugin will create you the specific page and the products will be added in this page and
afterwards add them to the cart or remove them.

Working demo are available:

**[LIVE DEMO 1](http://preview.yithemes.com/room09/product/africa-style/)** - **[LIVE DEMO 2](http://preview.yithemes.com/bazar/shop/ankle-shoes/)**

Full documentation is available [here](http://yithemes.com/docs-plugins/yith-woocommerce-moodboard).

This plugin is 100% compatible with [WPML](http://wpml.org/?aid=24889&affiliate_key=Qn1wDeBNTcZV)

= Available Languages =

**NOTE: The translation process of this plugin has been changed by WordPress. Please, read the correlated FAQ to be updated about the news changes.**

* Chinese - CHINA
* Chinese - TAIWAN
* Danish - DENMARK
* English - UNITED KINGDOM (Default)
* French - FRANCE
* German - GERMANY
* Hebrew - ISRAEL
* Italian - ITALY
* Persian - IRAN, ISLAMIC REPUBLIC OF
* Polish - POLAND
* Portuguese - BRAZIL
* Portuguese - PORTUGAL
* Russian - RUSSIAN FEDERATION
* Spanish - ARGENTINA
* Spanish - SPAIN
* Spanish - MEXICO
* Swedish - SWEDEN
* Turkish - TURKEY
* Ukrainian - UKRAINE

== Installation ==

1. Unzip the downloaded zip file.
2. Upload the plugin folder into the `wp-content/plugins/` directory of your WordPress site.
3. Activate `YITH WooCommerce moodboard` from Plugins page

YITH WooCommerce moodboard will add a new submenu called "moodboard" under "YIT Plugins" menu. Here you are able to configure all the plugin settings.

== Frequently Asked Questions ==

= Can I customize the moodboard page? =
Yes, the page is a simple template and you can override it by putting the file template "moodboard.php" inside the "woocommerce" folder of the theme folder.

= Can I move the position of "Add to moodboard" button? =
Yes, you can move the button to another default position or you can also use the shortcode inside your theme code.

= Can I change the style of "Add to moodboard" button? =
Yes, you can change the colors of background, text and border or apply a custom css. You can also use a link or a button for the "Add to moodboard" feature.

= moodboard page returns a 404 error? =
Try to regenerate permalinks from Settings -> Permalinks by simply saving them again.

= Did icons of your theme disappear after update to moodboard 2.0.x? =
It might be a compatibility problem with the old version of font-awesome, which has been solved with version 2.0.2 of the plugin. Be sure that you are using a plugin version that is greater or equal to 2.0.2. If, after update, you cannot see icons in your theme yet, save again options of YITH WooCommerce moodboard plugin (that you can find in YIT Plugin -> moodboard).

= Have you encountered anomalies after plugin update, that did not exist in the previous version? =
This might depend on the fact that your theme overrides plugin templates. Check if the developer of your theme has released a compatibility update with version 2.0 or later of YITH WooCommerce moodboard. As an alternative you can try the plugin in WordPress default theme to leave out any possible influences by the theme.

= I am currently using moodboard plugin with Catalog Mode enabled in my site. Prices for products should disappear, yet they still appear in the moodboard page. Can I remove them? =
Yes, of course you can. To avoid moodboard page to show product prices, you can hide price column from moodboard table. Go to YIT plugins -> moodboard -> settings and disable option "Show Unit price".

= What are the main changes in plugin translation? =
Recently YITH WooCommerce moodboard has been selected to be included in the "translate.wordpress.org" translate programme.
In order to import correctly the plugin strings in the new system, we had to change the text domain from 'yit' to 'yith-woocommerce-moodboard'.
Once the plugin is imported into the translate.wordpress.org system, the translations of other languages will be downloadable directly from WordPress, without using any .po and .mo files. Moreover, users will be able to participate in a more direct way to plugin translations, suggesting texts in their languages in the dedicated tab on translate.wordpress.org.
During this transition step, .po and .mo files will be used as usual, but in order to be recognized by WordPress, they must have a new nomenclature and be renamed as:
yith-woocommerce-moodboard-&lt;WORDPRESS LOCALE&gt;.po
yith-woocommerce-moodboard-&lt;WORDPRESS LOCALE&gt;.mo
If your theme overrides plugin templates, it might happen that they are still using the old textdomain ('yit'), which is no longer used as reference for translation.
If you are experiencing problems with translation of your YITH WooCommerce moodboard and the theme you are using includes moodboard templates (such as add-to-moodboard.php,
add-to-moodboard-button.php, moodboard-view,php), you could try to update them with the most recent version included in the plugin
(never forget to make a copy of your project before you apply any change).
If you want to keep customisations applied by the theme to moodboard templates (still using the old textdomain), then,
you should ask theme developers to update custom templates and replace the old textdomain with the most recent one.

== Screenshots ==

1. The page with "Add to moodboard" button
2. The moodboard page
3. The moodboard settings page
4. The moodboard settings page

== Changelog ==

= 2.0.16 - Released: Jun, 14 - 2016 =

* Added: WooCommerce 2.6 support
* Tweak: changed uninstall procedure to work with multisite and delete plugin options
* Tweak: removed description and image from facebook share link (fb doesn't allow anymore)
* Fixed: product query (GROUP By and LIMIT statement conflicting)

= 2.0.15 - Released: Apr, 04 - 2016 =

* Added: filter yith_mdbd_is_product_in_moodboard to choose whether a product is in moodboard or not
* Added: filter yith_mdbd_cookie_expiration to set default moodboard cookie expiration time in seconds
* Tweak: updated plugin-fw
* Fixed: get_products query returning product multiple times when product has more then one visibility meta

= 2.0.14 - Released: Mar, 21 - 2016 =

* Added: Dutch translation (thanks to w.vankuipers)
* Added: Danish translation (thanks to Morten)
* Added: yith_mdbd_is_moodboard_page function to identify if current page is moodboard page
* Added: filter yith_mdbd_settings_panel_capability for panel capability
* Added: filter yith_mdbd_current_moodboard_view_params for shortcode view params
* Added: "defined YITH_mdbd" check before every template
* Added: check over existance of $.prettyPhoto.close before using it
* Added: method count_add_to_moodboard to YITH_mdbd class
* Added: function yith_mdbd_count_add_to_moodboard
* Tweak: Changed ajax url to "relative"
* Tweak: Removed yit-common (old plugin-fw) deprecated since 2.0
* Tweak: Removed deprecated WC functions
* Tweak: Skipped removed_from_moodboard query arg adding, when external product
* Tweak: Added transients for wishist counts
* Tweak: Removed DOM structure dependencies from js for moodboard table handling
* Tweak: All methods/functions that prints/counts products in moodboard now skip trashed or not visible products
* Fixed: shortcode callback setting global product in some conditions
* Fixed: typo in hook yith_wccl_table_after_product_name (now set to yith_mdbd_table_after_product_name)
* Fixed: notice appearing when moodboard page slug is empty

= 2.0.13 - Released: Dec, 17 - 2015 =

* Added: check over adding_to_cart event data existance in js procedures
* Added: 'yith_mdbd_added_to_cart_message' filter, to customize added to cart message in moodboard page
* Added: nofollow to "Add to moodboard" links, where missing
* Added: 'yith_mdbd_email_share_subject' filter to customize share by email subject
* Added: 'yith_mdbd_email_share_body' filter to customize share by email body
* Added: function "yith_mdbd_count_all_products"
* Fixed: plugin-fw loading

= 2.0.12 - Released: Oct, 23 - 2015 =

* Added: method to count all products in moodboard
* Tweak: Added moodboard js handling on 'yith_mdbd_init' triggered on document
* Tweak: Performance improved with new plugin core 2.0
* Fixed: occasional fatal error for users with outdated version of plugin-fw on their theme

= 2.0.11 - Released: Sept, 21 - 2015 =

* Added: spanish translation (thanks to Arman S.)
* Added: polish translation (thanks to Roan)
* Added: swedish translation (thanks to Lallex)
* Updated: changed text domain from yit to yith-woocommerce-moodboard
* Updated: changed all language file for the new text domain

= 2.0.10 - Released: Aug, 12 - 2015 =

* Added: Compatibility with WC 2.4.2
* Tweak: added nonce field to moodboard-view form
* Tweak: added yith_mdbd_custom_add_to_cart_text and yith_mdbd_ask_an_estimate_text filters
* Tweak: added check for presence of required function in moodboard script
* Fixed: admin colorpicker field (for WC 2.4.x compatibility)

= 2.0.9 - Released: Jul, 24 - 2015 =

* Added: russian translation
* Added: WooCommerce class to moodboard view form
* Added: spinner to plugin assets
* Added: check on "user_logged_in" for sub-templates in moodboard-view
* Added: WordPress 4.2.3 compatibility
* Added: WPML 3.2.2 compatibility (removed deprecated function)
* Added: new check on is_product_in_moodboard (for unlogged users/default moodboard)
* Tweak: escaped urls on share template
* Tweak: removed new line between html attributes, to improve themes compatibility
* Fixed: WPML 3.2.2 compatibility (fix suggested by Konrad)
* Fixed: regex used to find class attr in "Add to Cart" button
* Fixed: usage of product_id for add_to_moodboard shortcode, when global $product is not defined
* Fixed: icon attribute for yith_mdbd_add_to_moodboard shortcode

= 2.0.8 - Released: May, 29 - 2015 =

* Added: support WP 4.2.2
* Added: Persian translation
* Added: check on cookie content
* Added: Frequently Bought Together integration
* Tweak: moved cookie update before first cookie usage
* Updated: Italian translation
* Removed: login_redirect_url variable

= 2.0.7 - Released: Apr, 30 - 2015 =

* Added: WP 4.2.1 support
* Added: WC 2.3.8 support
* Added: "Added to cart" message in moodboard page
* Added: Portuguese translation
* Updated: revision of all templates
* Fixed: vulnerability for unserialize of cookie content (Warning: in this way all the old serialized plugins will be deleted and all the moodboards of the non-logged users will be lost)
* Fixed: Escaped add_query_arg() and remove_query_arg()
* Removed: use of pretty permalinks if WPML enabled

= 2.0.6 - Released: Apr, 08 - 2015 =

* Added: system to overwrite moodboard js
* Added: trailingslashit() to moodboard permalink
* Added: chinese translation
* Added: "show_empty" filter to get_moodboards() method
* Fixed: count moodboard items
* Fixed: problem with price inclusive of tax
* Fixed: remove from moodboard for not logged user
* Fixed: twitter share summary

= 2.0.5 - Released: Mar, 19 - 2015 =

* Added: icl_object_id to moodboard page id, to translate pages
* Tweak: updated rewrite rules, to include child pages as moodboard pages
* Tweak: moved WC notices from moodboard template to yith_mdbd_before_moodboard_title hook
* Tweak: added moodboard table id to .load(), to update only that part of template
* Fixed: yith_mdbd_locate_template causing 500 Internal Server Error

= 2.0.4 - Released: Mar, 04 - 2015 =

* Added: Options for browse moodboard/already in moodboard/product added strings
* Added: rel nofollow to add to moodboard button
* Tweak: moved moodboard response popup handling to separate js file
* Updated: WPML xml configuration
* Updated: string revision

= 2.0.3 - Released: Feb, 19 - 2015 =

* Tweak: set correct protocol for admin-ajax requests
* Tweak: used wc core function to set cookie
* Tweak: let customization of add_to_moodboard shortcodes
* Fixed: show add to cart column when stock status disabled
* Fixed: product existing in moodboard

= 2.0.2 - Released: Feb, 17 - 2015 =

* Updated: font-awesome library
* Fixed: option with old font-awesome classes

= 2.0.1 - Released: Feb, 13 - 2015 =

* Added: spinner image on loading
* Added: flush rewrite rules on database upgrade
* Fixed: wc_add_to_cart_params not defined issue

= 2.0.0 - Released: Feb, 12 - 2015 =

* Added: Support to woocommerce 2.3
* Added: New color options
* Tweak: Add to cart button from woocommerce template
* Tweak: Share links on template
* Tweak: Code revision
* Tweak: Use wordpress API in ajax call instead of custom script
* Updated: Plugin core framework


= 1.1.7 - Released: Dec, 03 - 2014 =

* Added: Support to WooCommerce Endpoints (@use yit_mdbd_add_to_cart_redirect_url filter)
* Added: Filter to shortcode html
* Added: Title to share

= 1.1.6 - Released: Set, 16 - 2014 =

* Updated: Plugin Core Framework
* Updated: Languages file
* Tweek:   WPML Support Improved

= 1.1.5 - Released: Jun, 30 - 2014 =

* Added: Share moodboard by email

= 1.1.4 - Released: Jun, 26 - 2014 =

* Fixed: wrong string for inline js on remove link
* Fixed: wrong string for inline js on add to cart link

= 1.1.3 - Released: Jun, 05 - 2014 =

* Added: Options Tabs Filter
* Fixed: Various Bugs

= 1.1.2 - Released: Mar, 21 - 2014 =

* Fixed: Warnings when Show Stock Status is disabled
* Fixed: Restored page options on WooCommerce 2.1.x

= 1.1.1 - Released: Feb, 26 - 2014 =

* Fixed: Inability to unistall plugin 
* Fixed: Redirect to cart page from moodboard page

= 1.1.0 - Released: Feb, 13 - 2014 =

* Added: Support to WooCommerce 2.1.x
* Added: Spanish (Mexico) translation by Gabriel Dzul
* Added: French translation by Virginie Garcin
* Fixed: Revision Italian Language po/mo files

= 1.0.6 - Released: Nov, 18 - 2013 =

* Added: Spanish (Argentina) partial translation by Sebastian Jeremias
* Added: Portuguese (Brazil) translation by Lincoln Lemos
* Fixed: Share buttons show also when not logged in
* Fixed: Price shows including or excluding tax based on WooCommerce settings
* Fixed: Better compatibility for WPML 
* Fixed: Price shows "Free!" if the product is without price
* Fixed: DB Table creation on plugin activation

= 1.0.5 - Released: 14, Oct - 2013 =

* Added: Shared moodboards can be seens also by not logged in users
* Added: Support for WPML String translation
* Updated: German translation by Stephanie Schlieske
* Fixed: Add to cart button does not appear if the product is out of stock

= 1.0.4 - Released: Sept, 04 - 2013 =

* Added: partial Ukrainian translation
* Added: complete German translation. Thanks to Stephanie Schliesk
* Added: options to show/hide button add to cart, unit price and stock status in the moodboard page
* Added: Hebrew language (thanks to Gery Grinvald)

= 1.0.3 - Released: Jul, 31 - 2013 =

* Fixed: Minor bugs fixes

= 1.0.2 - Released: Jun, 24 - 2013 =

* Fixed: Fatal error to yit_debug with yit themes

= 1.0.1 - Released: May, 30 - 2013 =

* Tweak: Optimized images
* Updated: internal framework

= 1.0.0 - Released: May, 23 - 2013 =

* Initial release

== Suggestions ==

If you have suggestions about how to improve YITH WooCommerce moodboard, you can [write us](mailto:plugins@yithemes.com "Your Inspiration Themes") so we can bundle them into YITH WooCommerce moodboard.

== Translators ==

= Available Languages =
* Chinese - CHINA
* Chinese - TAIWAN
* Danish - DENMARK
* Dutch - NETHERLANDS
* English - UNITED KINGDOM (Default)
* German - GERMANY
* French - FRANCE
* Hebrew - ISRAEL
* Italian - ITALY
* Persian - IRAN, ISLAMIC REPUBLIC OF
* Polish - POLAND
* Portuguese - BRAZIL
* Portuguese - PORTUGAL
* Russian - RUSSIAN FEDERATION
* Spanish - ARGENTINA
* Spanish - SPAIN
* Spanish - MEXICO
* Swedish - SWEDEN
* Turkish - TURKEY
* Ukrainian - UKRAINE

Some of these translations are not complete.
If you want to contribute to the translation of the plugin, please [go to WordPress official translator platform](https://translate.wordpress.org/ "Translating WordPress") and translate the strings in your own language. In this way, we will be able to increase the languages available for YITH WooCommerce moodboard.


== Documentation ==

Full documentation is available [here](http://yithemes.com/docs-plugins/yith-woocommerce-moodboard).

== Upgrade notice ==

= 2.0.15 - Released: Apr, 04 - 2016 =

* Added: filter yith_mdbd_is_product_in_moodboard to choose whether a product is in moodboard or not
* Added: filter yith_mdbd_cookie_expiration to set default moodboard cookie expiration time in seconds
* Tweak: updated plugin-fw
* Fixed: get_products query returning product multiple times when product has more then one visibility meta
