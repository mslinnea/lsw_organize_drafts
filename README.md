# Organize Drafts (WordPress Plugin)

Author URI: https://www.linsoftware.com

Plugin URI: https://www.linsoftware.com/organize-drafts/

Tags: organization, organize, workflow, work-flow, folders, drafts, editing, draft folders, draft categories, categorize

Requires at least: 4.4.0

Tested up to: 6.7.1

Stable tag: 1.1.0

License: GPLv2 or later

License URI: http://www.gnu.org/licenses/gpl-2.0.html

Organize WordPress Drafts with "Draft Types."  Think of draft types as folders for sorting your drafts. Use the default types or add your own custom draft types.

## Description

Organize WordPress Drafts with "Draft Types."  Think of draft types as folders for sorting your drafts. Use the default types or add your own custom draft types.

### Features:

* Improve your editing workflow and de-clutter your drafts.
* By default, works with posts and pages. See the FAQ for how to configure this to work with custom post types.

## Installation

1. Install the plugin in the plugins directory.
2. Activate the Plugin.

## Frequently Asked Questions

### How do I use this plugin with a custom post type?

Add the following code to your theme's functions.php file:

    add_filter( 'lsw_default_post_types', 'associate_post_types_with_draft_types' );
    function associate_post_types_with_draft_types( $post_types ) {
	    $post_types[] = 'YOUR_CUSTOM_POST_TYPE';
	    return $post_types;
    }

Replace "YOUR_CUSTOM_POST_TYPE" with the name of your custom post type.



