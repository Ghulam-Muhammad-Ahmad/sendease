<?php
$output = '<div class="wrap">';
$output .= '<img src="' . plugin_dir_url(__FILE__) . '../../assests/sendeaselogo.svg" width="250" />';
$output .= '<p>SendEase is a plugin designed to simplify the process of sending emails from your WordPress site. With its intuitive interface and powerful features, you can easily manage and send emails to your subscribers, customers, or any other group.</p>';

// Use the common function from sendease.php to display notices
$output .= sendease_check_requirements();

// Dashboard Cards Container
$output .= '<div class="sendease-dashboard-cards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">';

// Simple Mail Card
$output .= '<div class="card">';
$output .= '<h2>Simple Mail</h2>';
$output .= '<p>Send individual emails with a rich text editor and preview functionality.</p>';
$output .= '<ul style="list-style-type: none; padding-left: 0;">';
$output .= '<li>✓ Rich text editor</li>';
$output .= '<li>✓ Live preview</li>';
$output .= '<li>✓ PDF export</li>';
$output .= '<li>✓ Test email functionality</li>';
$output .= '</ul>';
$output .= '<a href="' . admin_url('admin.php?page=sendease_simple_mail') . '" class="button button-primary">Go to Simple Mail</a>';
$output .= '</div>';

// CSV Mail Card
$output .= '<div class="card">';
$output .= '<h2>CSV Mail</h2>';
$output .= '<p>Send bulk emails using CSV files with dynamic placeholders.</p>';
$output .= '<ul style="list-style-type: none; padding-left: 0;">';
$output .= '<li>✓ CSV file upload</li>';
$output .= '<li>✓ Dynamic placeholders</li>';
$output .= '<li>✓ Column mapping</li>';
$output .= '<li>✓ Bulk sending</li>';
$output .= '</ul>';
$output .= '<a href="' . admin_url('admin.php?page=sendease_csv_mail') . '" class="button button-primary">Go to CSV Mail</a>';
$output .= '</div>';

// Settings Card
$output .= '<div class="card">';
$output .= '<h2>Settings</h2>';
$output .= '<p>Configure your SendEase plugin settings and preferences.</p>';
$output .= '<ul style="list-style-type: none; padding-left: 0;">';
$output .= '<li>✓ Testing email setup</li>';
$output .= '<li>✓ Plugin requirements</li>';
$output .= '<li>✓ System status</li>';
$output .= '</ul>';
$output .= '<a href="' . admin_url('admin.php?page=sendease_settings') . '" class="button button-primary">Go to Settings</a>';
$output .= '</div>';

$output .= '</div>'; // Close dashboard cards container

// Plugin Requirements Section
$output .= '<div class="card" style="margin-top: 20px;">';
$output .= '<h2>Plugin Requirements</h2>';
$output .= '<table class="widefat" style="margin-top: 10px;">';
$output .= '<tr><th>Plugin</th><th>Status</th><th>Action</th></tr>';
$output .= '<tr><td>Testing Email</td><td>' . (empty(get_option('sendease_testing_email')) ? '<span style="color: #dc3232;">Not Set</span>' : '<span style="color: #46b450;">Configured</span>') . '</td>';
$output .= '<td>' . (empty(get_option('sendease_testing_email')) ? '<a href="' . admin_url('admin.php?page=sendease_settings') . '" class="button button-secondary">Configure</a>' : '<a href="' . admin_url('admin.php?page=sendease_settings') . '" class="button button-secondary">Update</a>') . '</td></tr>';
$output .= '<tr><td>WP Mail SMTP</td><td>' . (is_plugin_active('wp-mail-smtp/wp_mail_smtp.php') ? '<span style="color: #46b450;">Active</span>' : '<span style="color: #dc3232;">Not Active</span>') . '</td>';
$output .= '<td>' . (is_plugin_active('wp-mail-smtp/wp_mail_smtp.php') ? '<a href="' . admin_url('admin.php?page=wp-mail-smtp') . '" class="button button-secondary">Configure</a>' : '<a href="' . admin_url('plugin-install.php?tab=search&type=term&s=wp+mail+smtp') . '" class="button button-secondary">Install</a>') . '</td></tr>';
$output .= '<tr><td>Email Logging</td><td>' . (is_plugin_active('wp-mail-logging/wp-mail-logging.php') ? '<span style="color: #46b450;">Active</span>' : '<span style="color: #dc3232;">Not Active</span>') . '</td>';
$output .= '<td>' . (is_plugin_active('wp-mail-logging/wp-mail-logging.php') ? '<a href="' . admin_url('admin.php?page=wp-mail-logging') . '" class="button button-secondary">Configure</a>' : '<a href="' . admin_url('plugin-install.php?tab=search&type=term&s=wp+mail+logging') . '" class="button button-secondary">Install</a>') . '</td></tr>';
$output .= '</table>';
$output .= '</div>';

$output .= '</div>'; // Close wrap

echo $output;
