# Theme and Domain Switcher

## Quick Description
A WordPress plugin for switching between themes based on domains and user roles. Allows dynamic toggling between themes via the admin bar, shortcodes, or a customizable icon. Includes features such as role-based access, dynamic site identity changes (title, tagline, favicon), and secure theme switching with nonce validation.

---

## Theme and Domain Switcher Plugin

### Description

The **Theme and Domain Switcher Plugin** enables dynamic switching between themes based on the domain being visited or by using the admin bar, shortcodes, or a simple icon toggle. Designed for multi-domain or multi-brand websites, it allows different themes, branding, and site identities (title, tagline, favicon) to be tied to specific domains. It also supports role-based access for secure theme switching.

### Features

- **Domain-Based Theme Switching:** Automatically applies specific themes and site identities based on the domain being visited.
- **Admin Bar Theme Switcher:** Provides a "Switch Theme" option in the admin bar for easy switching (restricted to specific user roles).
- **Role-Based Access Control:** Only users with designated roles can view and use the theme/domain switcher.
- **Shortcode for Theme Switching:** Insert a theme-switching menu anywhere on your site using a shortcode.
- **Simple Icon Toggle:** Allows switching between two themes with a single icon, perfect for frontend or backend use.
- **Dynamic Site Identity:** Automatically updates the site title, tagline, and favicon based on the active theme and domain.
- **Secure Theme Switching:** Utilizes WordPress nonces for safe and secure theme changes.

---

## Editable Components

### User Roles
You can control which user roles have access to the theme/domain switcher.
- Roles are defined in the `$theme_switcher_allowed_roles` array.
- Default roles: `administrator`, `editor`.
- To add more roles, simply append them to the array, e.g., `'shop_manager'`, `'custom_role'`.

### Domains and Themes
- The plugin supports multiple domains and assigns specific themes to each.
- Domains and their corresponding themes are stored in the `$domains_identity` array. Customize this array to define the theme, site title, tagline, favicon, and icon for each domain.

### Fallback Domain
- A **fallback domain** is defined in case no specific domain matches. This ensures a consistent site identity if the visitor's domain does not correspond to any in the `$domains_identity` array.

### Site Identity (Title, Tagline, Favicon)
- The **site title**, **tagline**, and **favicon** are dynamically updated based on the active domain and theme.
- These values are easily editable in the `$domains_identity` and `$fallback_domain` arrays.

### Theme Switcher Icon
- The theme switcher icon, used for toggling between two themes, is customizable.
- URLs for the icons of each theme are stored in the `$domains_identity` array under the `icon_url` field.

---

## Installation

1. Upload the plugin files to the `/wp-content/plugins/theme-domain-switcher/` directory, or install the plugin via the WordPress plugins screen directly.
2. Activate the plugin through the "Plugins" screen in WordPress.
3. (Optional) Customize the roles, domains, themes, and site identity by modifying the `$theme_switcher_allowed_roles`, `$domains_identity`, and `$fallback_domain` arrays as needed.

---

## Usage

### Domain-Based Theme Switching

- The plugin automatically applies a specific theme and updates the site identity based on the domain being visited.
- Ensure the domains and their corresponding settings (theme slug, site title, tagline, favicon) are properly set in the `$domains_identity` array.

### Fallback Domain

- If the current domain does not match any in the `$domains_identity` array, the plugin will apply the theme and site identity defined in the `$fallback_domain`.

### Admin Bar Theme Switcher

- Once activated, a **Switch Theme** menu appears in the WordPress admin bar for allowed users.
- The dropdown lists all themes for domains except the currently active one. Clicking on a theme name will switch to that theme/domain.

### Shortcode Theme Switcher

- Use the shortcode `[theme_switcher]` to display a list of themes for switching. This menu is only visible to users with the appropriate roles.

### Simple Icon Theme Toggle

- Use the shortcode `[theme_switch_icon]` to display a clickable icon that toggles between two predefined themes.
- The icon URL and theme slugs are customizable in the `$domains_identity` array.

### Role-Based Access

- Only users with roles specified in the `$theme_switcher_allowed_roles` array can see and use the theme switcher.
- You can customize the allowed roles by editing the array in the plugin file.

---

## Dynamic Site Identity (Title, Tagline, Favicon)

- The plugin automatically updates the **site title**, **tagline**, and **favicon** when themes are switched based on the domain being visited.
- Edit the `$domains_identity` and `$fallback_domain` arrays to define the specific title, tagline, and favicon for each domain.
- Ensure your favicon images are correctly stored and referenced in the array.

---

## Security

- Theme switching is secured using **WordPress nonces** to prevent unauthorized access or misuse.
- The plugin ensures that only users with the appropriate permissions can switch themes.

---

## License

This plugin is licensed under the **GNU General Public License v2.0** or later. You can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation.
