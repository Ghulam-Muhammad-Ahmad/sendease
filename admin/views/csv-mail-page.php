<?php
// Enqueue TinyMCE from CDN
wp_enqueue_script('tinymce-cdn', 'https://cdn.tinymce.com/5/tinymce.min.js', array(), null, true);

// Register and enqueue csv-mail-script.js
wp_register_script('sendease-csv-script', plugin_dir_url(__FILE__) . '../../assests/csv-mail-script.js', array('jquery', 'tinymce-cdn'), time(), true);
wp_localize_script('sendease-csv-script', 'sendease_ajax', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'notices' => array(
        'success' => array(
            'class' => 'notice-success',
            'message' => 'Email sent successfully!'
        ),
        'error' => array(
            'class' => 'notice-error',
            'message' => 'Failed to send email. Please try again.'
        ),
        'warning' => array(
            'class' => 'notice-warning',
            'message' => 'Please check your input before sending.'
        )
    )
));
wp_enqueue_script('sendease-csv-script');

$output = '<div class="wrap">';
$output .= '<img src="' . plugin_dir_url(__FILE__) . '../../assests/sendeaselogo.svg" width="250" />';
$output .= '<p>SendEase is a plugin designed to simplify the process of sending emails from your WordPress site. With its intuitive interface and powerful features, you can easily manage and send emails to your subscribers, customers, or any other group.</p>';
$output .= sendease_check_requirements();
if (empty(get_option('sendease_testing_email'))) {
    $output .= '<div class="notice notice-warning is-dismissible">';
    $output .= '<p>You have not set a testing email address. Please set one in the <a href="' . admin_url('admin.php?page=sendease_settings') . '">Settings</a> to ensure proper email functionality.</p>';
    $output .= '</div>';
}

$output .= '<div class="card" style="min-width:100%;">';
$output .= '<h2>CSV Mail Sender</h2>';

$output .= '<div class="sendease-container" style="display:flex; gap:20px; flex-direction:column;">';

// Status div moved to top
$output .= '<div id="send-status"></div>';

// Form section
$output .= '<div class="sendease-form">';
$output .= '<form method="post" enctype="multipart/form-data" id="csv-upload-form" style="flex: 1;">';
$output .= '<div class="form-group">';
$output .= '<label for="csv_file">Upload CSV File:</label>';
$output .= '<input type="file" name="csv_file" id="csv_file" accept=".csv" required>';
$output .= '</div>';

$output .= '<div id="csv-preview" class="sendease-emailpreview csvpage" style="display:none;">';
$output .= '<h3>CSV Preview</h3>';
$output .= '<div class="sendease-emailpreviewconatiner csvpage">';
$output .= '<table id="csv-preview-table"></table>';
$output .= '</div>';

$output .= '<div class="form-group">';
$output .= '<label for="email-column">Select Email Column:</label>';
$output .= '<select name="email_column" id="email-column" required></select>';
$output .= '</div>';

$output .= '<div class="form-group">';
$output .= '<label for="email-subject">Available Subject Placeholders:</label>';
$output .= '<div id="subject-column-tags" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 10px;"></div>';
$output .= '<label for="email-subject">Email Subject:</label>';
$output .= '<input type="text" name="email_subject" id="email-subject" required>';
$output .= '<p class="description">Click placeholders above to insert into subject</p>';
$output .= '</div>';

$output .= '<div class="form-group">';
$output .= '<label for="email-body">Available Body Placeholders:</label>';
$output .= '<div id="body-column-tags" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 10px;"></div>';
$output .= '<label for="email-body">Email Body:</label>';
ob_start();
wp_editor('', 'email-body', array(
    'textarea_name' => 'email_body',
    'textarea_rows' => 15,
    'media_buttons' => true,
    'teeny' => true
));
$output .= ob_get_clean();
$output .= '<p class="description">Click placeholders above to insert into body</p>';
$output .= '</div>';

$output .= '<div class="form-group">';
$output .= '<button type="button" id="preview-btn" class="button">Preview Email</button>';
$output .= '</div>';

$output .= '</form>';
$output .= '</div>'; // Close form section

// Preview and buttons container
$output .= '<div class="preview-buttons-container" style="display:flex; gap:20px; flex-direction:column;">';

// Preview email container
$output .= '<div class="sendease-preview">';
$output .= '<div id="email-preview" class="sendease-emailpreview csvpage">';
$output .= '<h3>Email Preview</h3>';
$output .= '<div class="preview-content"></div>';
$output .= '</div>';
$output .= '</div>'; // Close preview container

// Send buttons container
$output .= '<div class="form-group">';
$output .= '<button type="button" id="test-email-btn" class="button">Send Test Email</button>';
$output .= '<button type="submit" class="button button-primary" id="email-btn">Send Emails</button>';
$output .= '</div>';

$output .= '</div>'; // Close preview-buttons-container

$output .= '</div>'; // Close main container
$output .= '</div>'; // Close card
$output .= '</div>'; // Close wrap

echo $output;
