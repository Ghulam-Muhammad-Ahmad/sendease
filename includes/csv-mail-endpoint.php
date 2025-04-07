<?php
add_action('wp_ajax_csv-email-endpoint', 'send_csv_emails');

function send_csv_emails() {
    // Verify nonce and permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
        return;
    }

    // Get form data
    $email_column = intval($_POST['email_column']);
    $subject = sanitize_text_field($_POST['email_subject']);
    $body_template = wp_kses_post($_POST['email_body']);

    // Handle file upload
    if (!isset($_FILES['csv_file'])) {
        wp_send_json_error('No file uploaded');
        return;
    }

    $file = $_FILES['csv_file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        wp_send_json_error('File upload error');
        return;
    }

    // Read CSV
    $handle = fopen($file['tmp_name'], 'r');
    if (!$handle) {
        wp_send_json_error('Could not read file');
        return;
    }

    // Get headers
    $headers = fgetcsv($handle);
    if (!$headers) {
        fclose($handle);
        wp_send_json_error('Invalid CSV format');
        return;
    }

    // Get first row for test email
    $first_row = fgetcsv($handle);
    if (!$first_row) {
        fclose($handle);
        wp_send_json_error('No data rows in CSV');
        return;
    }

    // Check if this is a test email
    if (isset($_POST['test_email']) && $_POST['test_email'] === 'true') {
        $test_email = get_option('sendease_testing_email');
        if (empty($test_email)) {
            wp_send_json_error('Test email address not configured');
            return;
        }

        // Replace placeholders using first row data
        $message = $body_template;
        foreach ($headers as $index => $header) {
            $placeholder = '{' . trim($header) . '}';
            $value = isset($first_row[$index]) ? $first_row[$index] : '';
            $message = str_replace($placeholder, $value, $message);
        }

        // Send test email
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $mail = new WP_Mail_Helper($test_email, $subject, $message, $headers);
        if ($mail->send_mail()) {
            wp_send_json_success('Test email sent successfully');
        } else {
            wp_send_json_error('Failed to send test email');
        }
        fclose($handle);
        return;
    }

    // Regular email sending process
    $success_count = 0;
    $failed_count = 0;
    
    // Reset file pointer to start after headers
    fseek($handle, 0);
    fgetcsv($handle); // Skip headers row

    // Process each row
    while (($row = fgetcsv($handle)) !== false) {
        // Get email from selected column
        if (!isset($row[$email_column])) {
            continue;
        }
        $to = sanitize_email($row[$email_column]);
        if (!is_email($to)) {
            $failed_count++;
            continue;
        }

        // Replace placeholders in body
        $message = $body_template;
        foreach ($headers as $index => $header) {
            $placeholder = '{' . trim($header) . '}';
            $value = isset($row[$index]) ? $row[$index] : '';
            $message = str_replace($placeholder, $value, $message);
        }

        // Send email
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $mail = new WP_Mail_Helper($to, $subject, $message, $headers);
        if ($mail->send_mail()) {
            $success_count++;
        } else {
            $failed_count++;
        }
    }

    fclose($handle);

    wp_send_json_success([
        'success_count' => $success_count,
        'failed_count' => $failed_count
    ]);
}
