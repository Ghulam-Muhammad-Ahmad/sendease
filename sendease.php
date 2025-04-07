<?php
/**
 * SendEase
 *
 * @package       SENDEASE
 * @author        Ghulam Ahmad
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   SendEase
 * Plugin URI:    https://sendease.com
 * Description:   A plugin for sending emails with ease.
 * Version:       1.0.0
 * Author:        Ghulam Ahmad
 * Author URI:    https://your-author-domain.com
 * Text Domain:   sendease
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Include your custom code here.
require_once plugin_dir_path(__FILE__) . 'includes/class-wp-mail-helper.php';
require_once plugin_dir_path(__FILE__) . 'admin/admin-menus.php';
require_once plugin_dir_path(__FILE__) . 'includes/simplemail-endpoint.php';
require_once plugin_dir_path(__FILE__) . 'includes/csv-mail-endpoint.php';


    add_action('admin_init', 'sendease_register_styles');
    add_action('admin_enqueue_scripts', 'sendease_admin_styles');
    add_action('wp_enqueue_scripts', 'sendease_admin_styles');

    function sendease_register_styles() {
        wp_register_style('sendease-style', plugin_dir_url(__FILE__) . 'assests/style.css', array(), time());
    }

    function sendease_admin_styles() {
        wp_enqueue_style('sendease-style');
    }
    function sendease_display_notice($type, $message) {
        return '<div class="notice notice-' . $type . ' is-dismissible">' .
               '<p>' . $message . '</p>' .
               '</div>';
    }

    function sendease_check_requirements() {
        $notices = array();

        if (empty(get_option('sendease_testing_email'))) {
            $notices[] = sendease_display_notice(
                'warning',
                'You have not set a testing email address. Please set one in the <a href="' . admin_url('admin.php?page=sendease_settings') . '">Settings</a> to ensure proper email functionality.'
            );
        }

        if (!is_plugin_active('wp-mail-smtp/wp_mail_smtp.php')) {
            $notices[] = sendease_display_notice(
                'warning',
                'SMTP plugin is not installed. Please <a href="' . admin_url('plugin-install.php?tab=search&type=term&s=wp+mail+smtp') . '">install</a> and configure SMTP plugin to ensure email delivery.'
            );
        }

        if (!is_plugin_active('wp-mail-logging/wp-mail-logging.php')) {
            $notices[] = sendease_display_notice(
                'warning',
                'Email Logging plugin is not installed. Please <a href="' . admin_url('plugin-install.php?tab=search&type=term&s=wp+mail+logging') . '">install</a> and configure Email Logging plugin to track email delivery.'
            );
        }

        return implode('', $notices);
    }
