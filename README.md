# Theme Switcher

## Quick Description
WordPress Child-Theme Switcher for quickly toggling between themes via the admin bar, shortcodes, or a customizable icon. Features include dynamic role-based access, site identity changes (title, tagline, favicon), and secure theme switching with nonce validation.

---

## Theme Switcher Plugin

### Description

The **Theme Switcher Plugin** enables dynamic switching between child themes directly from the WordPress admin bar, through shortcodes, or by using a simple icon toggle. It's designed for multi-theme sites and is limited to specific user roles for secure access. The plugin also dynamically updates the **site title, tagline, and favicon** based on the active theme, allowing for distinct branding across themes.

### Features

- **Admin Bar Theme Switcher:** A "Switch Theme" option is added to the admin bar for easy theme switching (restricted to specific user roles).
- **Role-Based Access Control:** Only users with designated roles can view and use the theme switcher.
- **Shortcode for Theme Switching:** Insert a theme-switching dropdown anywhere on your site using a shortcode.
- **Simple Icon Toggle:** Allows switching between two child themes with a single icon, making it ideal for frontend or backend use.
- **Dynamic Site Identity:** Automatically updates the site title, tagline, and favicon when switching between themes.
- **Secure Theme Switching:** Utilizes WordPress nonces for safe theme changes.

---

## Editable Components

### User Roles
You can control which user roles have access to the theme switcher.
- Roles are defined in the `$theme_switcher_allowed_roles` array.
- Default roles: `administrator`, `editor`.
- To add more roles, simply append them to the array, e.g., `'shop_manager'`, `'custom_role'`.

### Themes
- The plugin supports child themes. The theme slugs for each child theme are stored in the `$themes_identity` array.
- You can switch between any number of child themes by updating this array with the correct theme names and slugs.

### Theme Switcher Icon
- The theme switcher icon, used for toggling between two child themes, is editable.
- URLs for the icons of each theme are stored in the `$themes_identity` array, under the `icon_url` field.

### Site Identity (Title, Tagline, Favicon)
- The **site title**, **tagline**, and **favicon** are dynamically updated based on the active theme.
- These are easily editable in the `$themes_identity` array for each theme.

---

## Installation

1. Upload the plugin files to the `/wp-content/plugins/theme-switcher/` directory, or install the plugin via the WordPress plugins screen directly.
2. Activate the plugin through the "Plugins" screen in WordPress.
3. (Optional) Customize the roles, themes, and site identity by modifying the `$theme_switcher_allowed_roles` array and the `$themes_identity` array as needed.

---

## Usage

### Admin Bar Theme Switcher

- Once activated, a **Switch Theme** menu appears in the WordPress admin bar for allowed users.
- The dropdown lists all child themes except the currently active one. Clicking on a theme name will switch to that theme.

### Shortcode Theme Switcher

- Use the shortcode `[theme_switcher]` to display a list of child themes for switching. This menu is only visible to users with the appropriate roles.

### Simple Icon Theme Toggle

- Use the shortcode `[theme_switch_icon]` to display a clickable icon that toggles between two predefined child themes.
- The icon URL and theme slugs are customizable in the `$themes_identity` array.

### Role-Based Access

- Only users with roles specified in the `$theme_switcher_allowed_roles` array can see and use the theme switcher.
- You can customize the allowed roles by editing the array in the plugin file.

---

## Dynamic Site Identity (Title, Tagline, Favicon)

- The plugin automatically updates the **site title**, **tagline**, and **favicon** when themes are switched.
- Edit the `$themes_identity` array to define the specific title, tagline, and favicon for each child theme.
- Ensure your favicon images are stored in your child themes' `images` folder and are correctly referenced in the array.

---

## Security

- Theme switching is secured using **WordPress nonces** to prevent unauthorized access or misuse.
- The plugin ensures that only users with the appropriate permissions can switch themes.

---

## License

This plugin is licensed under the **GNU General Public License v2.0** or later. You can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation.

