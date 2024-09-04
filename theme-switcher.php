<?php

/**
* Plugin Name: Theme Switcher
* Description: This plugin allows to switch between themes and child themes. [with otpional site identity switch]
* Author: Weavemaster
* Author URI: http://teehautecouture.com
 * Version: 1.4
 */

/**
 * ======== Customizable Variables Section ========
 * You can modify these variables to set your theme slugs, names, site titles, taglines, and favicon URLs.
 */

// Allowed roles for theme switching
$theme_switcher_allowed_roles = array('administrator', 'editor'); // Add more roles as needed

// Theme data for switching and site identity
$themes_identity = array(
    'child-theme-1-slug' => array(
        'name'            => 'Child Theme 1 Name',
        'blogname'        => 'Site Title for Theme 1',
        'blogdescription' => 'Tagline for Theme 1',
        'favicon_url'     => get_stylesheet_directory_uri() . '/images/favicon-theme1.png', // Favicon for Theme 1
        'icon_url'        => 'your-icon-url-for-theme-1.png' // Icon when Child Theme 1 is active
    ),
    'child-theme-2-slug' => array(
        'name'            => 'Child Theme 2 Name',
        'blogname'        => 'Site Title for Theme 2',
        'blogdescription' => 'Tagline for Theme 2',
        'favicon_url'     => get_stylesheet_directory_uri() . '/images/favicon-theme2.png', // Favicon for Theme 2
        'icon_url'        => 'your-icon-url-for-theme-2.png' // Icon when Child Theme 2 is active
    )
);

/**
 * ======== End of Customizable Variables Section ========
 */

/**
 * Function to get allowed user roles that can see and use the theme switcher
 */
function get_allowed_roles() {
    global $theme_switcher_allowed_roles;
    return $theme_switcher_allowed_roles;
}

/**
 * Function to check if the current user has any of the allowed roles
 */
function user_has_allowed_role() {
    $roles = get_allowed_roles();
    foreach ($roles as $role) {
        if (current_user_can($role)) {
            return true;
        }
    }
    return false;
}

/**
 * Function to generate the theme switch URL with nonce
 */
function get_theme_switch_url($theme_slug) {
    return wp_nonce_url(
        admin_url('themes.php?action=activate&stylesheet=' . $theme_slug),
        'switch-theme_' . $theme_slug
    );
}

/**
 * Function to add the theme switcher to the admin bar
 */
function add_theme_switcher_to_admin_bar($wp_admin_bar) {
    if (!user_has_allowed_role()) {
        return;
    }

    $current_theme = wp_get_theme();
    $themes = wp_get_themes();
    global $themes_identity;

    $wp_admin_bar->add_menu(array(
        'id'    => 'theme_switcher_parent',
        'title' => 'Switch Theme',
        'href'  => '#',
    ));

    foreach ($themes_identity as $theme_slug => $theme_data) {
        if ($current_theme->get('Name') === $theme_data['name']) {
            continue; // Skip active theme
        }
        $wp_admin_bar->add_menu(array(
            'parent' => 'theme_switcher_parent',
            'id'     => 'theme_switcher_' . $theme_slug,
            'title'  => $theme_data['name'],
            'href'   => get_theme_switch_url($theme_slug),
        ));
    }
}
add_action('admin_bar_menu', 'add_theme_switcher_to_admin_bar', 100);

/**
 * Show admin bar for allowed roles only
 */
add_filter('show_admin_bar', function($show_admin_bar) {
    return user_has_allowed_role();
});

/**
 * Theme switcher menu as a shortcode
 * Usage: [theme_switcher]
 */
function theme_switcher_shortcode() {
    if (!user_has_allowed_role()) {
        return '';
    }

    $current_theme = wp_get_theme();
    global $themes_identity;
    $output = '<ul class="theme-switcher">';

    foreach ($themes_identity as $theme_slug => $theme_data) {
        if ($current_theme->get('Name') === $theme_data['name']) {
            continue; // Skip active theme
        }
        $output .= '<li><a href="' . esc_url(get_theme_switch_url($theme_slug)) . '">'
            . esc_html($theme_data['name']) . '</a></li>';
    }

    $output .= '</ul>';
    return $output;
}
add_shortcode('theme_switcher', 'theme_switcher_shortcode');

/**
 * Simple theme switch icon between two child themes that also switches with the theme
 * Usage: [theme_switch_icon]
 */
function simple_theme_switcher_icon() {
    if (!user_has_allowed_role()) {
        return '';
    }

    $current_theme = wp_get_theme();
    global $themes_identity;

    // Determine which theme is active and set the icon and theme switch URL
    foreach ($themes_identity as $theme_slug => $theme_data) {
        if ($current_theme->get('Name') === $theme_data['name']) {
            $next_theme_slug = array_keys($themes_identity)[1 - array_search($theme_slug, array_keys($themes_identity))]; // Switch to the other theme
            $icon_url = $themes_identity[$theme_slug]['icon_url'];
            break;
        }
    }

    $theme_switch_url = get_theme_switch_url($next_theme_slug);

    return '<a href="' . esc_url($theme_switch_url) . '" class="theme-switch-icon">
                <img src="' . esc_url($icon_url) . '" alt="Switch Theme" />
            </a>';
}
add_shortcode('theme_switch_icon', 'simple_theme_switcher_icon');

/**
 * Function to dynamically change the site title, tagline, and favicon based on the active theme
 */
function dynamic_site_identity() {
    $current_theme = wp_get_theme();
    global $themes_identity;

    foreach ($themes_identity as $theme_slug => $theme_data) {
        if ($current_theme->get('Name') === $theme_data['name']) {
            add_filter('pre_option_blogname', function() use ($theme_data) {
                return $theme_data['blogname'];
            });
            add_filter('pre_option_blogdescription', function() use ($theme_data) {
                return $theme_data['blogdescription'];
            });

            // Enqueue favicon
            add_action('wp_enqueue_scripts', function() use ($theme_data) {
                echo '<link rel="icon" href="' . esc_url($theme_data['favicon_url']) . '" type="image/x-icon">';
            });
        }
    }
}
add_action('after_setup_theme', 'dynamic_site_identity');
