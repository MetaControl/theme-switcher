<?php

/**
 * Plugin Name: Theme and Domain Switcher
 * Description: A plugin to switch between themes and child themes based on domains with dynamic site identity and icon switching.
 * Author: WeaveMaster
 * Author URI: https://example.com
 * Version: 2.0
 */

// ======== Customizable Variables Section ========

// Allowed roles for theme switching
$theme_switcher_allowed_roles = array('administrator', 'editor'); // Add more roles as needed

// Fallback or Base Domain (Core Domain) - used if no specific domain matches
$fallback_domain = array(
    'url'            => 'https://fallback-domain.com',  // Replace with the actual fallback domain URL
    'theme_slug'     => 'fallback_theme_slug',  // Replace with the slug of the fallback theme
    'blogname'       => 'Fallback Site Title',  // Replace with the site title for the fallback domain
    'blogdescription'=> 'Fallback Site Tagline',  // Replace with the site tagline for the fallback domain
    'favicon_url'    => content_url('/path/to/favicon-fallback.png'),  // Path to favicon for the fallback domain
    'icon_url'       => '/path/to/icon-fallback.png'  // Icon URL for the fallback domain
);

// Domain data for switching and site identity
$domains_identity = array(
    'domain_1' => array(  // Domain 1
        'url'            => 'https://domain1.com',  // Replace with the actual domain URL
        'theme_slug'     => 'theme_slug_1',  // Replace with the actual slug of the theme for this domain
        'blogname'       => 'Site Title 1',  // Replace with the site title for this domain
        'blogdescription'=> 'Site Tagline 1',  // Replace with the site tagline for this domain
        'favicon_url'    => content_url('/path/to/favicon1.png'),  // Path to favicon for this domain
        'icon_url'       => '/path/to/icon1.png'  // Icon URL for this domain
    ),
    'domain_2' => array(  // Domain 2
        'url'            => 'https://domain2.com',  // Replace with the actual domain URL
        'theme_slug'     => 'theme_slug_2',  // Replace with the actual slug of the theme for this domain
        'blogname'       => 'Site Title 2',  // Replace with the site title for this domain
        'blogdescription'=> 'Site Tagline 2',  // Replace with the site tagline for this domain
        'favicon_url'    => content_url('/path/to/favicon2.png'),  // Path to favicon for this domain
        'icon_url'       => '/path/to/icon2.png'  // Icon URL for this domain
    )
);

// ======== End of Customizable Variables Section ========

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
 * Function to get the current domain settings
 */
function get_current_domain_settings() {
    global $domains_identity, $fallback_domain;
    $current_domain = home_url();  // Get current site URL

    // Loop through each domain in the identity list
    foreach ($domains_identity as $domain_key => $domain_data) {
        if (strpos($current_domain, $domain_data['url']) !== false) {
            return $domain_data;
        }
    }

    // Return fallback domain if no match found
    return $fallback_domain;
}

/**
 * Function to dynamically change the site title, tagline, and favicon based on the current domain
 */
function dynamic_domain_identity() {
    $domain_settings = get_current_domain_settings();
    
    // Update site title, tagline, and favicon based on the current domain
    add_filter('option_blogname', function() use ($domain_settings) {
        return $domain_settings['blogname'];
    });
    
    add_filter('option_blogdescription', function() use ($domain_settings) {
        return $domain_settings['blogdescription'];
    });
    
    add_filter('get_site_icon_url', function() use ($domain_settings) {
        return $domain_settings['favicon_url'];
    });

    // Switch to the appropriate theme based on the domain
    switch_theme($domain_settings['theme_slug']);
}
add_action('init', 'dynamic_domain_identity');

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
 * Function to add theme switcher to admin bar (optional, shown for allowed roles)
 */
function add_theme_switcher_to_admin_bar($wp_admin_bar) {
    if (!user_has_allowed_role()) {
        return;
    }

    $current_domain = get_current_domain_settings();
    global $domains_identity;

    $wp_admin_bar->add_menu(array(
        'id'    => 'domain_switcher_parent',
        'title' => 'Switch Site',
        'href'  => '#',
    ));

    foreach ($domains_identity as $domain_key => $domain_data) {
        if ($current_domain['url'] === $domain_data['url']) {
            continue;  // Skip the current domain
        }
        $wp_admin_bar->add_menu(array(
            'parent' => 'domain_switcher_parent',
            'id'     => 'domain_switcher_' . $domain_key,
            'title'  => esc_html($domain_data['blogname']),
            'href'   => esc_url($domain_data['url']),
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
    global $domains_identity;
    $output = '<ul class="theme-switcher">';

    foreach ($domains_identity as $theme_slug => $theme_data) {
        if ($current_theme->get('Name') === $theme_data['blogname']) {
            continue; // Skip active theme
        }
        $output .= '<li><a href="' . esc_url(get_theme_switch_url($theme_slug)) . '">'
            . esc_html($theme_data['blogname']) . '</a></li>';
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
    global $domains_identity;

    // Determine which theme is active and set the icon and theme switch URL
    foreach ($domains_identity as $theme_slug => $theme_data) {
        if ($current_theme->get('Name') === $theme_data['blogname']) {
            $next_theme_slug = array_keys($domains_identity)[1 - array_search($theme_slug, array_keys($domains_identity))]; // Switch to the other theme
            $icon_url = $domains_identity[$theme_slug]['icon_url'];
            break;
        }
    }

    $theme_switch_url = get_theme_switch_url($next_theme_slug);

    return '<a href="' . esc_url($theme_switch_url) . '" class="theme-switch-icon">
                <img src="' . esc_url($icon_url) . '" alt="Switch Theme" />
            </a>';
}
add_shortcode('theme_switch_icon', 'simple_theme_switcher_icon');
