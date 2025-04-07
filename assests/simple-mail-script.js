jQuery(document).ready(function ($) {
    wp.editor.initialize('email_content', {
        tinymce: true,
        quicktags: true
    });

    $("#reflectchanges").click(function (e) {
        e.preventDefault();
        $("#receiver_email").text($("#recipient_email").val());
        $("#email_subject_preview").text($("#email_subject").val());
        // Access the TinyMCE editor instance
        var editor = tinymce.get('email_content');
        if (editor) {
            var content = editor.getContent();
            $('#email_body_preview').html(content);
        }
    });

    function showNotice(type) {
        const notice = sendease_ajax.notices[type];
        const noticeHtml = `
            <div class="notice ${notice.class} is-dismissible">
                <p>${notice.message}</p>
            </div>
        `;
        $("#sendease-notice-container").html(noticeHtml);

        // Remove notice after 3 seconds
        setTimeout(() => {
            $("#sendease-notice-container").empty();
        }, 3000);
    }

    // Add PDF download functionality
    $("#download-pdf-btn").click(function () {
        const element = document.querySelector('#email_body_preview');

        // Store original styles
        const originalStyles = {
            height: element.style.height,
            overflow: element.style.overflow
        };

        // Temporarily adjust styles
        element.style.height = 'auto';
        element.style.overflow = 'visible';

        const opt = {
            margin: 1,
            filename: `email-preview-${new Date().toISOString().slice(0, 19).replace(/[:]/g, '-')}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait', autoPaging: true }
        };

        // Generate PDF
        html2pdf()
            .set(opt)
            .from(element)
            .save()
            .then(() => {
                // Restore original styles after PDF is saved
                element.style.height = originalStyles.height;
                element.style.overflow = originalStyles.overflow;
            })
            .catch(err => {
                // Restore original styles in case of error
                element.style.height = originalStyles.height;
                element.style.overflow = originalStyles.overflow;
                showNotice('error');
                console.error('PDF generation failed:', err);
            });
    });



    $("#sendease-sm-testsend").click(() => {
        var testreceiver = $("#test_receiver_email").text();
        var testsubject = $("#email_subject_preview").text();
        var testemailbody = $("#email_body_preview").html();

        if (!testreceiver || !testsubject || !testemailbody) {
            showNotice('warning');
            return;
        }

        $.ajax({
            url: sendease_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'simple-email-endpoint',
                to: testreceiver,
                subject: testsubject,
                message: testemailbody,
                headers: 'Content-Type: text/html; charset=UTF-8'
            },
            success: function (response) {
                showNotice('success');
            },
            error: function (xhr, status, error) {
                showNotice('error');
            }
        });
    });

    $("#sendease-sm-send").click(() => {
        var receiver = $("#receiver_email").text();
        var subject = $("#email_subject_preview").text();
        var emailbody = $("#email_body_preview").html();

        if (!receiver || !subject || !emailbody) {
            showNotice('warning');
            return;
        }

        $.ajax({
            url: sendease_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'simple-email-endpoint',
                to: receiver,
                subject: subject,
                message: emailbody,
                headers: 'Content-Type: text/html; charset=UTF-8'
            },
            success: function (response) {
                showNotice('success');
            },
            error: function (xhr, status, error) {
                showNotice('error');
            }
        });
    });
});
