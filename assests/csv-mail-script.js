document.getElementById("csv_file").addEventListener("change", function(e) {
    const file = e.target.files[0];
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const text = e.target.result;
        const lines = text.split("\n");
        const headers = lines[0].split(",");
        
        // Populate column select
        const select = document.getElementById("email-column");
        select.innerHTML = "";
        headers.forEach((header, index) => {
            const option = document.createElement("option");
            option.value = index;
            option.textContent = header.trim();
            select.appendChild(option);
        });

        // Create column tags for subject and body
        ["subject-column-tags", "body-column-tags"].forEach(tagContainerId => {
            const container = document.getElementById(tagContainerId);
            container.innerHTML = "";
            headers.forEach(header => {
                const tag = document.createElement("button");
                tag.type = "button";
                tag.className = "button";
                tag.textContent = header.trim();
                tag.style.margin = "5px";
                tag.onclick = function() {
                    const placeholder = `{${header.trim()}}`;
                    if (tagContainerId === "subject-column-tags") {
                        const subject = document.getElementById("email-subject");
                        const start = subject.selectionStart;
                        const end = subject.selectionEnd;
                        subject.value = subject.value.substring(0, start) + placeholder + subject.value.substring(end);
                    } else {
                        const editor = tinymce.get("email-body");
                        editor.execCommand("mceInsertContent", false, placeholder);
                    }
                };
                container.appendChild(tag);
            });
        });
        
        // Create preview table
        let tableHtml = "<tr>";
        headers.forEach(header => {
            tableHtml += `<th>${header.trim()}</th>`;
        });
        tableHtml += "</tr>";
        
        lines.slice(1).forEach(row => {
            if(row.trim() !== "") {
                const cells = row.split(",");
                tableHtml += "<tr>";
                cells.forEach(cell => {
                    tableHtml += `<td>${cell.trim()}</td>`;
                });
                tableHtml += "</tr>";
            }
        });
        
        document.getElementById("csv-preview-table").innerHTML = tableHtml;
        document.getElementById("csv-preview").style.display = "block";
    };
    
    reader.readAsText(file);
});

// Preview email with first row data
document.getElementById("preview-btn").addEventListener("click", function() {
    const emailSubject = document.getElementById("email-subject").value;
    const emailBody = tinymce.get("email-body").getContent();
    const table = document.getElementById("csv-preview-table");
    
    if(!table.rows[1]) {
        alert("Please upload a CSV file first");
        return;
    }
    
    const headers = Array.from(table.rows[0].cells).map(cell => cell.textContent.trim());
    const firstRowData = Array.from(table.rows[1].cells).map(cell => cell.textContent.trim());
    
    let previewBody = emailBody;
    let previewSubject = emailSubject;
    
    headers.forEach((header, index) => {
        const placeholder = new RegExp("\\{" + header + "\\}", "g");
        previewBody = previewBody.replace(placeholder, firstRowData[index]);
        previewSubject = previewSubject.replace(placeholder, firstRowData[index]);
    });
    
    document.querySelector("#email-preview .preview-content").innerHTML = `
        <h4>Subject: ${previewSubject}</h4>
        <div class="email-body">${previewBody}</div>
    `;
    document.getElementById("email-preview").style.display = "block";
});

// Send test email
document.getElementById("test-email-btn").addEventListener("click", function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    sendEmail(true);
});

// Send all emails
document.getElementById("email-btn").addEventListener("click", function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    sendEmail(false);
});

function sendEmail(isTest = false) {
    const formData = new FormData(document.getElementById("csv-upload-form"));
    formData.append("action", "csv-email-endpoint");
    formData.append("test_email", isTest);
    formData.append("email_body", tinymce.get("email-body").getContent());
    
    const table = document.getElementById("csv-preview-table");
    let emailSubject = document.getElementById("email-subject").value;
    
    if(table.rows[1]) {
        const headers = Array.from(table.rows[0].cells).map(cell => cell.textContent.trim());
        const firstRowData = Array.from(table.rows[1].cells).map(cell => cell.textContent.trim());
        
        headers.forEach((header, index) => {
            const placeholder = new RegExp("\\{" + header + "\\}", "g");
            emailSubject = emailSubject.replace(placeholder, firstRowData[index]);
        });
    }
    
    formData.append("email_subject", emailSubject);
    
    const statusDiv = document.getElementById("send-status");
    statusDiv.innerHTML = `<p>${isTest ? 'Sending test email' : 'Sending emails'}... Please wait.</p>`;
    
    fetch(sendease_ajax.ajax_url, {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const message = isTest ? 
                sendease_ajax.notices.success.message :
                `Emails sent successfully! Success: ${data.data.success_count}, Failed: ${data.data.failed_count}`;
            
            statusDiv.innerHTML = `<div class="notice ${sendease_ajax.notices.success.class}">
                <p>${message}</p>
            </div>`;
        } else {
            statusDiv.innerHTML = `<div class="notice ${sendease_ajax.notices.error.class}">
                <p>Error: ${data.data}</p>
            </div>`;
        }
        setTimeout(() => {
            statusDiv.innerHTML = "";
        }, 3000);
    })
    .catch(error => {
        statusDiv.innerHTML = `<div class="notice ${sendease_ajax.notices.error.class}">
            <p>Error: ${error.message}</p>
        </div>`;
        setTimeout(() => {
            statusDiv.innerHTML = "";
        }, 3000);
    });
}
