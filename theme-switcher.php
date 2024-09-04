<?php

/**
 * Plugin Name: Theme Switcher
 * Description: This plugin allows you to switch between themes and child themes with dynamic site identity and icon switching.
 * Author: WeaveMaster
 * Author URI: https://teehautecouture.com
 * Version: 1.5
 */
/**
 * ======== Customizable Variables Section ========
 * You can modify these variables to set your theme slugs, names, site titles, taglines, and favicon URLs.
 * the favicons here are set up to be PNGs, but it should work with an .ico too.
 */

// Allowed roles for theme switching
$theme_switcher_allowed_roles = array('administrator', 'editor'); // Add more roles as needed

// Theme data for switching and site identity
$themes_identity = array(
    'theme_slug_1' => array(  // Replace 'theme_slug_1' with the actual slug of the first theme
        'name'            => 'Theme 1 Name', // The name of the first theme
        'blogname'        => 'Site Title for Theme 1', // The site title to be used for this theme
        'blogdescription' => 'Site Tagline for Theme 1', // The site tagline to be used for this theme
        'favicon_url'     => content_url('/path/to/favicon-1.png'), // Path to the favicon for this theme
        'icon_url'        => '/path/to/icon-1.png' // Icon URL for the admin bar or any icon use for this theme
    ),
    'theme_slug_2' => array(  // Replace 'theme_slug_2' with the actual slug of the second theme
        'name'            => 'Theme 2 Name', // The name of the second theme
        'blogname'        => 'Site Title for Theme 2', // The site title to be used for this theme
        'blogdescription' => 'Site Tagline for Theme 2', // The site tagline to be used for this theme
        'favicon_url'     => content_url('/path/to/favicon-2.png'), // Path to the favicon for this theme
        'icon_url'        => '/path/to/icon-2.png' // Icon URL for the admin bar or any icon use for this theme
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
    global $themes_identity;
    $current_theme = wp_get_theme();

    // Loop through the theme identity array to find the current theme
    foreach ($themes_identity as $slug => $theme_data) {
        if ($current_theme->get('Name') === $theme_data['name']) {
            // Update title and tagline
            add_filter('option_blogname', function() use ($theme_data) { return $theme_data['blogname']; });
            add_filter('option_blogdescription', function() use ($theme_data) { return $theme_data['blogdescription']; });
            
            // Override the favicon with the correct one for the active theme
            add_filter('get_site_icon_url', function() use ($theme_data) {
                return $theme_data['favicon_url'];
            });
        }
    }
}
add_action('init', 'dynamic_site_identity');


}
add_action('wp_head', 'dynamic_site_identity');
