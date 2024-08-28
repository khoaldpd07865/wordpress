=== Woocommerce Sale Discount Scheduler ===
Contributors: rajkakadiya, rvadhel
Donate link: https://paypal.me/rvadhel
Tags: schedule discount, upcoming discount, discount occasionally, product discount schedule timer
Requires PHP: 7.4
Requires at least: 6.0
Tested up to: 6.5
WC tested up to: 8.9.0
Requires Plugins: woocommerce
Stable tag: 1.9.1

This Plugin provide you options to schedule the discount throughout seasonally and occasionally of all your woocommerce products

== Description ==

This Plugin provide you options to manage the discount throughout seasonally and occasionally of all your woocommerce products, scheduling discount throughout Any Date and Time.
You will have the flexibility to choose Date and time range when your products will be available with some lucrative discount to your customers for purchasing, automatically hiding/showing the “schedule discount price” on shop, category and product pages, showing a countdown timer. 
 

== PRE DISCOUNT SALE COUNTDOWN TIMERS ==

Woocommerce Sale Discount Scheduler plugin gives you option to display pre discount countdown timer that lets your customer know how much time misses to start purchasing with lucrative discount prices.

== ON DISCOUNT SALE COUNTDOWN TIMERS ==

Woocommerce Sale Discount Scheduler plugin gives you the option to display on discount countdown timer that lets your customers know how much time misses to end purchasing with discount price time or to the expiration date and time. 

== EXPIRING DATE TIME ==

This Plugin you can set an expiring discount date time for every product. After this date time the product will be unavailable for purchasing with discount price . Optionally the product can be still setted as visbile with normal sale prices.

 
Also works with WordPress Multisite installs (each blog from the network has it’s own maintenance settings).

**Plugin Features** 
 
* Set up Flash sales scheduled to start on a set date/time 
* Create Seasonal offers scheduled to trigger on set dates 
* Schedule the sales for simple / variable products 
* Percentage and Fixed discount applied for each product on Regulare price 
* Display next opening sale time 
* Discount sale available per time range, per product 
* Manually disable, Enable schedule Sale Discount 
* Countdown Timer for pre schedule Sale and On sale 
* Shortcode and widget for listing of pre schedule Sale and On sale products 
* Discount visible on products on shop page, product detail page and cart page.
 
 
 = You can use shortcodes =

<code>
[wsds_schedule_sale_discount future_sale='true']
[wsds_schedule_sale_discount future_sale='true' on_sale='true']
[wsds_schedule_sale_discount future_sale='true' on_sale='true' limit='5']
[wsds_schedule_sale_discount future_sale='true' on_sale='true' limit='5' columns='5']</code> 

= Here is Template code =

<code><?php echo do_shortcode('[wsds_schedule_sale_discount future_sale='true']'); ?></code>

= Widget =

After Plugin Activation go to Appearance->Widgets->Woocommerce Sale Discount Products

== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

After Plugin Active go to Products-> Add new -> Schedule Sale Discount(tab).

== Screenshots ==
1. Add product page -> Schedule Sale Discount
2. Schedule Sale Discount -> Status
3. Schedule Sale Discount -> Set Schedule Time
4. Schedule Sale Discount -> Set Discount Type
5. Schedule Sale Discount -> Set Discount Sale Price
6. Schedule Sale Discount -> Set CountDown
7. Schedule Sale Discount -> Set On Sale CountDown 
8. Variable Product -> Set Schedule Discount
9. Variable Product -> Sale Start Countdown Preview
10. Shortcode for Schedule Sale Discount Products
11. Widget for Schedule Sale Discount Products

== Changelog ==
= 1.9.1 =
 Added option to schedule variable products
 Fixed translation strings and bugs

= 1.9 =
 Fixed bugs.

= 1.8 =
 Added option - Display Countdown on shop loop or Single product page 
 Fixed bugs - shop loop sale discount price

= 1.7 =
 Added woocommerce HPOS support.
 Tested up to 6.4 wordpress version.

= 1.6 =
 Tested upto wp version 6.2.2

= 1.5 =
 Tested upto wp version 6.0.1

= 1.4 =
 Tested upto wp version 5.9

= 1.3 =
 Fixed bug.

= 1.2 =
 Fixed bug.
 
= 1.1 =
 Fixed bug.
 
= 1.0 =
 Initial release