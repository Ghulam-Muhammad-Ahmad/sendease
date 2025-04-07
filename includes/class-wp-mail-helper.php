<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WP_Mail_Helper {
    private $to;
    private $subject;
    private $message;
    private $headers;
    private $attachments;

    public function __construct($to, $subject, $message, $headers = [], $attachments = []) {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->headers = $headers;
        $this->attachments = $attachments;
    }

    public function send_mail() {
        return wp_mail($this->to, $this->subject, $this->message, $this->headers, $this->attachments);
    }
}
