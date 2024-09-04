<?php

/**
* Plugin Name: Theme Switcher
* Description: This plugin allows to switch between themes and child themes. [with otpional site identity switch]
* Author: Weavemaster
* Author URI: http://teehautecouture.com
* Version: 1.2
*/

/**
 * Theme Switcher Function for Admin Bar /w Shortcode Menu, and Simple Icon Switch & Dynamic Site Identity (Title, Tagline, Favicon)
 */

// Define user roles that can see and use the theme switcher
$allowed_roles = array('administrator', 'editor'); // Add more roles as needed

// Function to check if the user has any of the allowed roles
function user_has_allowed_role($roles) {
    foreach ($roles as $role) {
        if (current_user_can($role)) {
            return true;
        }
    }
    return false;
}

// Function to add the theme switcher to the admin bar
function add_theme_switcher_to_admin_bar($wp_admin_bar) {
    global $allowed_roles;
    if (!user_has_allowed_role($allowed_roles)) {
        return;
    }

    $current_theme = wp_get_theme();
    $themes = wp_get_themes();

    $wp_admin_bar->add_menu(array(
        'id' => 'theme_switcher_parent',
        'title' => 'Switch Theme',
        'href' => '#',
    ));

    foreach ($themes as $theme_slug => $theme_data) {
        if ($theme_data->get('Name') === $current_theme->get('Name') || !$theme_data->parent()) {
            continue; // Skip active theme and parent themes
        }
        $theme_switch_url = wp_nonce_url(admin_url('themes.php?action=activate&stylesheet=' . $theme_slug), 'switch-theme_' . $theme_slug);
        $wp_admin_bar->add_menu(array(
            'parent' => 'theme_switcher_parent',
            'id'     => 'theme_switcher_' . $theme_slug,
            'title'  => $theme_data->get('Name'),
            'href'   => $theme_switch_url,
        ));
    }
}
add_action('admin_bar_menu', 'add_theme_switcher_to_admin_bar', 100);

// Show admin bar for allowed roles
add_filter('show_admin_bar', function($show_admin_bar) {
    global $allowed_roles;
    return user_has_allowed_role($allowed_roles);
});

// Theme switcher menu as a shortcode
function theme_switcher_shortcode() {
    global $allowed_roles;
    if (!user_has_allowed_role($allowed_roles)) return '';

    $themes = wp_get_themes();
    $current_theme = wp_get_theme();
    $output = '<ul class="theme-switcher">';

    foreach ($themes as $theme_slug => $theme_data) {
        if ($theme_data->get('Name') === $current_theme->get('Name') || !$theme_data->parent()) {
            continue;
        }
        $theme_switch_url = wp_nonce_url(admin_url('themes.php?action=activate&stylesheet=' . $theme_slug), 'switch-theme_' . $theme_slug);
        $output .= '<li><a href="' . esc_url($theme_switch_url) . '">' . esc_html($theme_data->get('Name')) . '</a></li>';
    }

    $output .= '</ul>';
    return $output;
}
add_shortcode('theme_switcher', 'theme_switcher_shortcode');

// Simple theme switch icon between two child themes 
// Replace 'your-child-theme-2-slug' & 'your-child-theme-1-slug' with theme slugs
function simple_theme_switcher_icon() {
    global $allowed_roles;
    if (!user_has_allowed_role($allowed_roles)) return '';

    $current_theme = wp_get_theme();
    if ($current_theme->parent()) {
        $theme_to_switch = ($current_theme->get('Name') === 'Your Child Theme 1') ? 'your-child-theme-2-slug' : 'your-child-theme-1-slug';
        $theme_switch_url = wp_nonce_url(admin_url('themes.php?action=activate&stylesheet=' . $theme_to_switch), 'switch-theme_' . $theme_to_switch);
        return '<a href="' . esc_url($theme_switch_url) . '" class="theme-switch-icon">
                    <img src="your-icon-url-here.png" alt="Switch Theme" />
                </a>';
    }
    return '';
}
add_shortcode('theme_switch_icon', 'simple_theme_switcher_icon');

// Function to dynamically change title, tagline, and favicon based on the active theme
function dynamic_site_identity() {
    $current_theme = wp_get_theme();
    if ($current_theme->get('Name') == 'Tee Haute Couture Theme') {
        add_filter('pre_option_blogname', function() { return 'Tee Haute Couture | Eco-Friendly, Quirky, High-Quality Apparel'; });
        add_filter('pre_option_blogdescription', function() { return 'Wear Your Story – Custom Tees with a Conscience'; });
        echo '<link rel="icon" href="' . get_template_directory_uri() . '/images/favicon-thc.ico" type="image/x-icon">';
    } elseif ($current_theme->get('Name') == 'Loominatee Theme') {
        add_filter('pre_option_blogname', function() { return 'Loominatee | The Secret Society of Exclusive Tees'; });
        add_filter('pre_option_blogdescription', function() { return 'Unlock the Secrets of Style – Join the Loominatee'; });
        echo '<link rel="icon" href="' . get_template_directory_uri() . '/images/favicon-loominatee.ico" type="image/x-icon">';
    }
}
add_action('wp_head', 'dynamic_site_identity');
