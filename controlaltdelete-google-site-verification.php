<?php
/**
 * Plugin Name: Control Alt Delete - Google Site Verification Plugin
 * Description: Adds the Google Site Verification meta tag to your site. The value can be set in the admin dashboard.
 * Version: 1.0
 * Author: Michiel Gerritsen
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ControlAltDeleteGoogleSiteVerification {
    public function __construct()
    {
        add_action('admin_menu', [$this, 'addMenuItem']);
        add_action('admin_init', [$this, 'addSettingsSection']);
        add_action('wp_head', [$this, 'renderMetaTag']);
    }

    public function addMenuItem(): void {
        add_options_page(
            'Google Site Verification', // Page title
            'Google Site Verification', // Menu title
            'manage_options',           // Capability
            'controlaltdelete-googlesiteverification-plugin',               // Menu slug
            [$this, 'renderSettingsPage']  // Callback function
        );
    }

    public function renderSettingsPage(): void {
        ?>
        <div class="wrap">
            <h1>Google Site Verification</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('controlaltdelete_googlesiteverification_plugin_settings');
                do_settings_sections('controlaltdelete-googlesiteverification-plugin');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function addSettingsSection(): void {
        register_setting('controlaltdelete_googlesiteverification_plugin_settings', 'controlaltdelete_googlesiteverification_plugin_meta_tag');

        add_settings_section(
            'controlaltdelete_googlesiteverification_plugin_section',
            'Google Site Verification Settings',
            [$this, 'renderSection'],
            'controlaltdelete-googlesiteverification-plugin'
        );

        add_settings_field(
            'controlaltdelete_googlesiteverification_plugin_meta_tag',
            'Google Site Verification Meta Tag',
            [$this, 'renderMetaTagField'],
            'controlaltdelete-googlesiteverification-plugin',
            'controlaltdelete_googlesiteverification_plugin_section'
        );
    }

    public function renderSection(): void {
        echo '<p>Enter your Google Site Verification meta tag value below:</p>';
    }

    public function renderMetaTagField(): void {
        $value = get_option('controlaltdelete_googlesiteverification_plugin_meta_tag', '');
        echo '<input type="text" name="controlaltdelete_googlesiteverification_plugin_meta_tag" value="' . esc_attr($value) . '" class="regular-text">';
    }

    public function renderMetaTag(): void {
        $meta_tag_value = trim(get_option('controlaltdelete_googlesiteverification_plugin_meta_tag', ''));

        if (!$meta_tag_value) {
            return;
        }

        echo '<meta name="google-site-verification" content="' . esc_attr($meta_tag_value) . '" />' . "\n";
    }
}

new ControlAltDeleteGoogleSiteVerification();