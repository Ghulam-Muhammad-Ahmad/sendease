<?php
if (isset($_POST['submit'])) {
    update_option('sendease_testing_email', $_POST['sendease_testing_email']);
}

$output = '<div class="wrap">';
$output .= sendease_check_requirements();
$output .= '<h1>SendEase Settings</h1>';

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

$output .= '<form method="post">';
$output .= '<table class="form-table">';
$output .= '<tr valign="top">';
$output .= '<th scope="row">Testing Email</th>';
$output .= '<td><input type="email" name="sendease_testing_email" value="' . get_option('sendease_testing_email') . '"/></td>';
$output .= '</tr>';
$output .= '</table>';
$output .= '<p class="submit">';
$output .= '<input type="submit" class="button-primary" value="Save Changes" name="submit" />';
$output .= '</p>';
$output .= '</form>';
$output .= '</div>';

echo $output;
?>
