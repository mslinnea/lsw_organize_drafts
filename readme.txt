=== Organize Drafts ===
Author URI: https://www.linsoftware.com
Plugin URI: https://www.linsoftware.com/organize-drafts/
Contributors: LinSoftware
Tags: organization, organize, workflow, work-flow, folders, drafts, editing, draft folders, draft categories, categorize
Requires at least: 4.4.0
Tested up to: 6.7.1
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Organize WordPress Drafts with "Draft Types."  Think of draft types as folders for sorting your drafts.

== Description ==

Organize WordPress Drafts with "Draft Types."  Think of draft types as folders for sorting your drafts. Use the default types or add your own custom draft types.

Features:

* Assign "Draft Types" to your drafts to improve your editing workflow and de-clutter your drafts.
* Create your own Draft Types.  Default types are: Idea, Outline, Rough Draft, In Review, Needs Images, Ready to Publish.
* Sort your draft posts by draft types.  When viewing your drafts, you will see a draft types column that can be sorted alphabetically.
* By default, works with posts and pages. See the FAQ for how to configure this to work with custom post types.

Have an idea to improve this plugin?  I'd like to hear about it.

== Installation ==

1. Install the plugin in the plugins directory.
2. Activate the Plugin.

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

1. Manage Draft Types through the WordPress Admin sidebar.

2. View and Soft Drafts by Draft Types column.

3. Easily select the Draft Type from a dropdown menu.


== Changelog ==

= 1.1.0: December 2024 =

* PHP 8.3 compatibility
* Change Default Draft Types.

= 1.0.1 =

* Fixed issue with meta box appearing on published posts.

= 1.0.0: January 2016 =

* First official release!

== Upgrade Notice ==

= 1.0.1 =

* Fixed issue with meta box appearing on published posts.

= 1.0.0: January 2016 =

* First official release!
