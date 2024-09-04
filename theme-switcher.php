<?php

/**
* Plugin Name: Theme Switcher
* Description: This plugin allows to switch between themes and child themes.
* Author: Weavemaster
* Author URI: http://teehautecouture.com
* Version: 1.0
*/

/**
 * Theme Switcher Function for Admin Bar, Shortcode Menu, and Simple Icon Switch
 */

// Define user roles that can see and use the theme switcher
$allowed_roles = array('administrator', 'editor'); // You can add more roles here

// Function to check if the user has any of the allowed roles
function user_has_allowed_role($roles) {
    foreach ($roles as $role) {
        if (current_user_can($role)) {
            return true;
        }
    }
    return false;
}

// Function to add the theme switcher to the admin bar based on user role
function add_theme_switcher_to_admin_bar($wp_admin_bar) {
    global $allowed_roles;

    // Check if the user has one of the allowed roles
    if (!user_has_allowed_role($allowed_roles)) {
        return; // Only allow users with the allowed roles to see the theme switcher
    }

    // Get the current theme and all available themes
    $current_theme = wp_get_theme();
    $current_theme_name = $current_theme->get('Name');
    $themes = wp_get_themes();

    // Add the main "Theme Switcher" menu to the admin bar
    $wp_admin_bar->add_menu(array(
        'id' => 'theme_switcher_parent',
        'title' => 'Switch Theme',
        'href' => '#',
    ));

    // Loop through each theme, but only include child themes, excluding the parent theme
    foreach ($themes as $theme_slug => $theme_data) {
        if ($theme_data->get('Name') === $current_theme_name || !$theme_data->parent()) {
            continue; // Skip the current active theme or any parent theme
        }

        // Generate the URL to switch themes and add it as a submenu item
        $theme_switch_url = wp_nonce_url(admin_url('themes.php?action=activate&stylesheet=' . $theme_slug), 'switch-theme_' . $theme_slug);
        $wp_admin_bar->add_menu(array(
            'parent' => 'theme_switcher_parent',
            'id'     => 'theme_switcher_' . $theme_slug,
            'title'  => $theme_data->get('Name'),
            'href'   => $theme_switch_url,
        ));
    }
}

// Hook the theme switcher into the admin bar, only for the allowed roles
add_action('admin_bar_menu', 'add_theme_switcher_to_admin_bar', 100);

// Filter to ensure the admin bar is shown on the frontend, only for the allowed roles
add_filter('show_admin_bar', function($show_admin_bar) {
    global $allowed_roles;
    if (user_has_allowed_role($allowed_roles)) {
        return true;
    }
    return false;
});

// Shortcode for the theme switcher menu (multi-theme dropdown), restricted to allowed roles
function theme_switcher_shortcode() {
    global $allowed_roles;

    // Check if the user has the allowed role
    if (!user_has_allowed_role($allowed_roles)) {
        return ''; // Only allow certain roles to use the shortcode
    }

    // Get the current theme and available themes
    $themes = wp_get_themes();
    $current_theme = wp_get_theme();
    $current_theme_name = $current_theme->get('Name');
    $output = '<ul class="theme-switcher">';

    // Loop through themes and only display child themes, excluding the active theme and parent themes
    foreach ($themes as $theme_slug => $theme_data) {
        if ($theme_data->get('Name') === $current_theme_name || !$theme_data->parent()) {
            continue; // Skip the current active theme and any parent theme
        }
        $theme_switch_url = wp_nonce_url(admin_url('themes.php?action=activate&stylesheet=' . $theme_slug), 'switch-theme_' . $theme_slug);
        $output .= '<li><a href="' . esc_url($theme_switch_url) . '">' . esc_html($theme_data->get('Name')) . '</a></li>';
    }

    $output .= '</ul>';
    return $output;
}

// Register the shortcode for the theme switcher menu
add_shortcode('theme_switcher', 'theme_switcher_shortcode');

// Simple Theme Switcher with one icon toggle between two child themes
function simple_theme_switcher_icon() {
    global $allowed_roles;

    // Check if the user has the allowed role
    if (!user_has_allowed_role($allowed_roles)) {
        return ''; // Only show the switch to users with the allowed role
    }

    // Get the current theme and determine the alternate theme (only child themes)
    $current_theme = wp_get_theme();
    if ($current_theme->parent()) {
        $theme_to_switch = ($current_theme->get('Name') === 'Your Child Theme 1') ? 'your-child-theme-2-slug' : 'your-child-theme-1-slug';

        // Create the switch URL with nonce for security
        $theme_switch_url = wp_nonce_url(admin_url('themes.php?action=activate&stylesheet=' . $theme_to_switch), 'switch-theme_' . $theme_to_switch);

        // Return the icon with a link to toggle the theme
        return '<a href="' . esc_url($theme_switch_url) . '" class="theme-switch-icon">
                    <img src="your-icon-url-here.png" alt="Switch Theme" />
                </a>';
    }

    return ''; // If the current theme is not a child theme, return nothing
}

// Register the shortcode for the simple icon switch
add_shortcode('theme_switch_icon', 'simple_theme_switcher_icon');


