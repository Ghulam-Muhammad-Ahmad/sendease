<?php
add_action('wp_ajax_simple-email-endpoint', 'send_simple_email');

function send_simple_email() {
    $to = $_POST['to'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $headers = $_POST['headers'];
    $attachments = $_POST['attachments'];

    $mail = new WP_Mail_Helper($to, $subject, $message, $headers, $attachments);
    $result = $mail->send_mail();

    if ($result) {
        wp_send_json_success('Email sent successfully');
    } else {
        wp_send_json_error('Failed to send email');
    }
}

