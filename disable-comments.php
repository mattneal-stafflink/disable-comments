<?php
/**
 * Plugin Name: Disable Comments
 * Description: We hate Commenting. This plugin disables comments on your entire site.
 * Author:      Matthew Neal
 * License:     GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Version:     1.0
 *
 * @package RealCoder
 */

// Basic security, prevents file from being loaded directly.
defined('ABSPATH') or die('Doin a BaMbOoZlE Huh??');

function sldc_disable_comments_admin_bar()
{
    if (is_admin_bar_showing() ) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}

function sldc_remove_comments_from_editor()
{
    // Redirect any user trying to access comments page.
    global $pagenow;
    if ($pagenow === 'edit-comments.php' ) {
        wp_safe_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard.
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types.
    foreach ( get_post_types() as $post_type ) {
        if (post_type_supports($post_type, 'comments') ) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}

function sldc_remove_comments_menu()
{
    remove_menu_page('edit-comments.php');
}

add_action('init', 'sldc_disable_comments_admin_bar');
add_action('admin_init', 'sldc_remove_comments_from_editor');
add_action('admin_menu', 'sldc_remove_comments_menu');
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);