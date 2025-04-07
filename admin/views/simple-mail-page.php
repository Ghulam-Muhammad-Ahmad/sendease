<div class="wrap">
    <img src="<?php echo plugin_dir_url(__FILE__) . '../../assests/sendeaselogo.svg'; ?>" width="250" />
    <p>SendEase is a plugin designed to simplify the process of sending emails from your WordPress site. With its intuitive interface and powerful features, you can easily manage and send emails to your subscribers, customers, or any other group.</p>
    
    <?php if (empty(get_option('sendease_testing_email'))) : ?>
        <div class="notice notice-warning is-dismissible">
            <p>You have not set a testing email address. Please set one in the <a href="<?php echo admin_url('admin.php?page=sendease_settings'); ?>">Settings</a> to ensure proper email functionality.</p>
        </div>
    <?php endif; ?>
    <?php
echo sendease_check_requirements();
?>
    <!-- Notice container for dynamic messages -->
    <div id="sendease-notice-container"></div>

    <div class="sendease-sm-container">
        <div class="card sendease-sm-card">
            <h2>Send a Simple Mail</h2>
            <form method="post" class="sendease-sm-form" id="sendease-sm-form">
                <div class="form-group">
                    <label for="recipient_email">Recipient Email:</label>
                    <input type="email" id="recipient_email" name="recipient_email" required>
                </div>
                
                <div class="form-group">
                    <label for="email_subject">Email Subject:</label>
                    <input type="text" id="email_subject" name="email_subject" required>
                </div>
                
                <div class="form-group">
                    <label for="email_content">Email Content:</label>
                   <?php wp_editor('', 'email_content', array('textarea_rows' => 15)); ?>
                </div>
        
                <div class="sendease-sm-button">
                    <button type="submit" id="reflectchanges" class="button button-primary mt-2">Reflect Changes</button>
                </div>
            </form>              
        </div>
        <div class="card sendease-sm-card">
            <div class="sendease-previewemail-header">
                <h2 class="seandease-preview-tooltip" data-tooltip="Preview the email before sending!">
                    Preview Email 
                    <svg width="20" height="20" viewBox="0 0 496 496" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M248 0C111.2 0 0 111.2 0 248C0 384.8 111.2 496 248 496C384.8 496 496 384.8 496 248C496 111.2 384.8 0 248 0ZM224 370.72V193.6C224 180.32 234.72 169.6 248 169.6C261.28 169.6 272 180.32 272 193.6V370.72C272 384 261.28 394.72 248 394.72C234.72 394.72 224 384 224 370.72ZM248 149.28C234.72 149.28 224 138.56 224 125.28C224 112 234.72 101.28 248 101.28C261.28 101.28 272 112 272 125.28C272 138.4 261.28 149.28 248 149.28Z" fill="black"/>
                    </svg>
                </h2>
                <div class="sendease-sm-button">
                    <button class="button button-primary active" id="preview-email-btn">Email</button>
                    <button class="button button-secondary" id="download-pdf-btn">Email Body In PDF</button>
                </div>
            </div>
            <div class="sendease-emailpreviewconatiner">
                <table>
                <tr>
                    <td>Receiver:</td>
                    <td id="receiver_email"></td>
                </tr>
                <tr>
                    <td>Test Receiver:</td>
                    <td id="test_receiver_email"><?php echo get_option('sendease_testing_email'); ?></td>
                </tr>
                <tr>
                    <td>Subject:</td>
                    <td id="email_subject_preview"></td>
                </tr>
                </table>
                <div class="sendease-emailpreview" id="email_body_preview">
                </div>
            </div>
            <div class="sendease-sm-button">
                <button id="sendease-sm-testsend" class="button button-secondary" data-testemail="<?php echo get_option('sendease_test_email'); ?>">Send Test Email</button>
                <button id="sendease-sm-send" class="button button-primary">Send Email</button>
            </div>
        </div>
    </div>
</div>
<?php
// Enqueue TinyMCE from CDN
wp_enqueue_script('tinymce-cdn', 'https://cdn.tinymce.com/5/tinymce.min.js', array(), null, true);

// Enqueue html2pdf library
wp_enqueue_script('html2pdf', 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js', array(), '0.10.1', true);

wp_register_script('sendease-script', plugin_dir_url(__FILE__) . '../../assests/simple-mail-script.js', array('html2pdf'), time());
wp_localize_script('sendease-script', 'sendease_ajax', array(
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
wp_enqueue_script('sendease-script');
?>
