# theme-switcher
Quick Description: Wordpress Child-Theme Switcher for quick theme toggle via admin bar menu or shortcodes. Customisable: roles, themes, icon


###

/// Theme Switcher ///

/ Description

The Theme Switcher Plugin adds functionality to dynamically switch between child themes on your WordPress website from the admin bar, via shortcode, or using a simple icon toggle. The plugin is restricted to specific user roles, and allows easy theme switching based on user preferences without needing to manually navigate to the Appearance settings.

This plugin is perfect for multi-theme sites where different user roles may require different themes or where multiple themes are actively developed and tested.
Features

    Admin Bar Theme Switcher: Adds a "Switch Theme" dropdown in the admin bar to toggle between installed child themes.
    Role-based Access Control: Only users with specific roles (editable) can access the theme switcher.
    Shortcode for Theme Switcher: Place a dropdown menu for theme switching anywhere on your site using a simple shortcode.
    Simple Icon Theme Toggle: Allows switching between two predefined child themes using a single icon.
    Security: Uses WordPress nonces to ensure secure theme switching.


/ Editable Components

    User Roles: You can customize which user roles have access to the theme switcher.
        Located in the $allowed_roles array.
        Default roles: administrator, editor.
        To add more roles, simply add them to the array, e.g., 'shop_manager', 'custom_role'.

    Themes:
        The plugin is designed to work with child themes only. You can toggle between specific child themes by adjusting the theme slugs in the simple_theme_switcher_icon function.
        By default, you need to update the child theme slugs to match your themes, e.g., 'your-child-theme-1-slug' and 'your-child-theme-2-slug'.

    Icon URL:
        The URL for the icon used in the simple icon theme switcher is editable.
        Replace 'your-icon-url-here.png' with the actual URL of the icon you want to use.


/ Installation

    Upload the plugin files to the /wp-content/plugins/theme-switcher-plugin directory, or install the plugin through the WordPress plugins screen directly.
    Activate the plugin through the "Plugins" screen in WordPress.
    (Optional) Customize the roles and themes by editing the $allowed_roles array and theme slugs as needed.


/ Usage

	Admin Bar Theme Switcher

		Once installed, a "Switch Theme" option will appear in the WordPress admin bar (only for allowed user roles).
		The dropdown will list all child themes except the active one, allowing users to switch between them with one click.

	Shortcode Theme Switcher

		Use the shortcode [theme_switcher] to add a theme-switching menu anywhere on your site (only visible to allowed user roles).

	Simple Icon Theme Toggle

		Use the shortcode [theme_switch_icon] to display an icon that toggles between two child themes.
		You will need to specify the icon URL and the theme slugs in the plugin code.

	Role-Based Access

		Only users with the specified roles in the $allowed_roles array can see or use the theme switcher.
		You can edit the roles by modifying the array, e.g., adding 'shop_manager', 'custom_role'.


/ Security

The plugin uses WordPress nonce validation to ensure that theme switches are secure and authorized.


/ License

This plugin is licensed under the GNU General Public License v2.0 or later. You can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation.
