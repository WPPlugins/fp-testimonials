=== FP Testimonials ===
Contributors: Flourish Pixel
Tags: plugin, testimonial, shortcode, slider, widget, scroller, testimonials
Requires at least: 2.0.0
Tested up to: 3.5.2
Stable tag: 1.0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will display testimonials in sidebar with several effects. You can manage the options from backend.Also you can use Shortcode for pages.

== Description ==

FP Testimonials allows you to create testimonials and display the associated testimonials on your sidebar using widgets. The sliding options are managable from wordpress admin area. You can use shortcode ([textimonial limit=5]) for showing in page, here 5 is for example, you can place yours, if you don't want to place limit then just place [testimonial], it will display 10 posts by default.

You can control over your testimonial sliding transion effect, time, direction etc.

** Pagination is available when you use shortcode.
** Client image can be added now.

= Plugin Settings =

* Div ID - textbox (by default it is - testimonial, you can use anything but no space between words)
* Speed - textbox (You can use 300, 400, 500 etc, Remember they are in Milisecond)
* Delay Time - textbox (You can use 3000, 4000, 5000 etc, Remember they are in Milisecond)
* Direction - Options
* Slide Limit - Options
* Pagination - Options
* Hover Control - Options
* Control Buttons - Options
* Pagination - Options
* Show - Options (Full Text, Excerpt)
* Auto Play - Options
* Link Title - Options
* Image - Options (Client Image)
* Image Size - textbox (Image Width in pixel[px])


== Installation ==

This section describes how to install the plugin and get it working.

1. Download the plugin file to your computer and Unzip the downloaded archive
2. Upload the folder **fp_testimonials** to your */wp-content/plugins/* directory
3. Activate the plugin from *Plugins > FP Testimonials > Activate*, under WordPress admin interface
4. Use this shortcode in your post or page to display FP Testimonial *[testimonial] or [textimonial limit=5]*.
5. Manage Widget Settings.

== Frequently Asked Questions ==
1. What is slide ID? 
	Answer: Slide ID is mainly the container ID which contains the testimonials and the JS file animate the contants in it.

2. What is Hover Control ?
	Answer: When the slide running on and you hover on it, if you assign TRUE, then it pauses the transition until you remove the mouse.
	
3. How i show the testimonials in a pge?
	Answer: Just use [testimonial]. By default it will display 10 posts if you don't set the limit. Otherwise, you can set as [testimonial limit=5], then it will display 5 posts.

4. How i add client image?
	Answer: You need to add client image using featured image option.
	

== Screenshots ==

1. View of Backend Testimonial area.
2. View Backend Widget Settings.
3. View Frontend Widget View.
4. View Frontend Testimonial Page with Pagination.

== Changelog ==

= 1.0.0 =
* initial release of FP testimonials

= 1.0.1 =
* Pagination is added for Page view, when you use shortcode.

= 1.0.2 =
* Backend Style Modification.

= 1.0.3 =
* Backend Style Modification.

= 1.0.4 =
* Pagination screenshot added.

= 1.0.5 =
* Widget Query updated for Wordpress 3.5.2.

= 1.0.6 =
* Client Image Option is added.

= 1.0.7 =
* Small CSS Modification.
