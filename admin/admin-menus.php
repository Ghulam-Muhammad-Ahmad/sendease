<?php

if (!defined('ABSPATH')) {
    exit;
}

// Add the main menu, submenus, and settings page
function cam_add_admin_menu() {
    add_menu_page(
        'SendEase Dashboard', 
        'SendEase', 
        'manage_options', 
        'sendease_dashboard', 
        'sendease_dashboard_page', 
        'dashicons-email', 
        2
    );

    add_submenu_page(
        'sendease_dashboard', 
        'SendEase Simple Mail', 
        'Simple Mail', 
        'manage_options', 
        'sendease_simple_mail', 
        'sendease_simple_mail_page'
    );

    add_submenu_page(
        'sendease_dashboard', 
        'SendEase CSV Mail', 
        'CSV Mail', 
        'manage_options', 
        'sendease_csv_mail', 
        'sendease_csv_mail_page'
    );

    add_submenu_page(
        'sendease_dashboard', 
        'SendEase Settings', 
        'Settings', 
        'manage_options', 
        'sendease_settings', 
        'sendease_settings_page'
    );
}
add_action('admin_menu', 'cam_add_admin_menu');

// Dashboard Page
function sendease_dashboard_page() {
    include plugin_dir_path(__FILE__) . 'views/dashboard-page.php';
}

// Simple Mail Page
function sendease_simple_mail_page() {
    include plugin_dir_path(__FILE__) . 'views/simple-mail-page.php';
}

// CSV Mail Page
function sendease_csv_mail_page() {
    include plugin_dir_path(__FILE__) . 'views/csv-mail-page.php';
}

// Settings Page
function sendease_settings_page() {
    include plugin_dir_path(__FILE__) . 'views/setting-page.php';
}

// Add testing_email option
function sendease_add_options() {
    add_option('sendease_admin_email');
}
add_action('admin_init', 'sendease_add_options');