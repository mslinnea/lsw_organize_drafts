=== Check Amazon Links ===
Author URI: http://www.linsoftware.com
Plugin URI: http://www.linsoftware.com/organize-drafts/
Contributors: LinSoftware
Donate link: http://www.linsoftware.com/support-free-plugin-development/
Tags: organization, organize, workflow, work-flow, folders, drafts, editing, draft folders, draft categories, categorize
Requires at least: 4.4.0
Tested up to: 4.4.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Organize Wordpress Drafts with "Draft Types."  Think of draft types as folders for sorting your drafts. Use the default types or add your own custom draft types.


== Description ==

Organize Wordpress Drafts with "Draft Types."  Think of draft types as folders for sorting your drafts. Use the default types or add your own custom draft types.

Features:

* Improve your editing workflow and de-clutter your drafts.
* By default, works with posts and pages. See the FAQ for how to configure this to work with custom post types.

Have an idea to improve this plugin?  I'd like to hear about it.  [Contact me here.](http://www.linsoftware
.com/contact/)

== Installation ==

1. Install the plugin in wp-content\plugins
2. Activate the Plugin

== Frequently Asked Questions ==

= How do I use this plugin with a custom post type? =

Add the following code to your theme's functions.php file:

    add_filter('lsw_default_post_types', 'associate_post_types_with_draft_types');
    function associate_post_types_with_draft_types($post_types) {
	    $post_types[] = 'YOUR_CUSTOM_POST_TYPE';
	    return $post_types;
    }

Replace "YOUR_CUSTOM_POST_TYPE" with the name of your custom post type.


== Screenshots ==

1. Manage Draft Types through the Wordpress Admin sidebar.

2. View and Soft Drafts by Draft Types column.


== Changelog ==

= 1.0.0: January 2016 =

* First official release!

== Upgrade Notice ==

= 1.0.0: January 2016 =

* First official release!
