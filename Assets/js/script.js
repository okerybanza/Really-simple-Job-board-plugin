jQuery(document).ready(function($) {
    // Initialize drop zone
    const dropZone = $('.sjb-upload-dropzone');
    const fileInput = $('#sjb_applicant_resume');
    const fileList = $('.sjb-file-list');
    const maxFiles = 3; // Configure maximum files
    const maxSize = 5 * 1024 * 1024; // 5MB in bytes

    // Drag and drop functionality
    dropZone.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('sjb-dragover');
    }).on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('sjb-dragover');
    }).on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('sjb-dragover');
        handleFiles(e.originalEvent.dataTransfer.files);
    }).on('click', function() {
        fileInput.click();
    });

    // File input change handler
    fileInput.on('change', function() {
        handleFiles(this.files);
    });

    // Handle selected files
    function handleFiles(files) {
        let validFiles = [];
        let errors = [];
        
        // Clear previous errors
        $('.sjb-file-error').remove();
        
        // Validate files
        Array.from(files).forEach(file => {
            // Check file type
            if (!file.type.match('application/pdf')) {
                errors.push(sjb_i18n.invalid_type.replace('{file}', file.name));
                return;
            }
            
            // Check file size
            if (file.size > maxSize) {
                errors.push(sjb_i18n.file_too_large
                    .replace('{file}', file.name)
                    .replace('{size}', formatFileSize(maxSize)));
                return;
            }
            
            // Check total files
            if (validFiles.length >= maxFiles) {
                errors.push(sjb_i18n.too_many_files.replace('{max}', maxFiles));
                return;
            }
            
            validFiles.push(file);
        });
        
        // Display errors
        if (errors.length > 0) {
            fileList.before(`<div class="sjb-file-error">${errors.join('<br>')}</div>`);
        }
        
        // Display valid files
        fileList.empty();
        validFiles.forEach((file, index) => {
            const fileItem = $(`
                <div class="sjb-file-item" data-index="${index}">
                    <span class="sjb-file-name">${file.name}</span>
                    <span class="sjb-file-size">(${formatFileSize(file.size)})</span>
                    <button type="button" class="sjb-file-remove">&times;</button>
                </div>
            `);
            fileList.append(fileItem);
        });
        
        // Update form data
        updateFormData(validFiles);
    }
    
    // Remove file handler
    fileList.on('click', '.sjb-file-remove', function() {
        const index = $(this).parent().data('index');
        $(this).parent().remove();
        const files = Array.from(fileInput[0].files);
        files.splice(index, 1);
        
        // Create new DataTransfer for updated files
        const dt = new DataTransfer();
        files.forEach(file => dt.items.add(file));
        fileInput[0].files = dt.files;
        
        updateFormData(files);
    });
    
    // Helper to format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Update form data storage
    function updateFormData(files) {
        $('.sjb-application-form').data('files', files);
    }

    // Form submission
    $('.sjb-application-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const progressBar = $('<div class="sjb-progress"><div class="sjb-progress-bar"></div></div>');
        
        // Validate files before upload
        const files = form.data('files') || [];
        if (files.length === 0) {
            showError(form, sjb_i18n.no_files_selected);
            return;
        }
        
        // Prepare form data
        const formData = new FormData(form[0]);
        files.forEach(file => {
            formData.append('sjb_files[]', file);
        });
        
        // Add progress bar
        form.find('.sjb-alert').remove();
        form.append(progressBar);
        
        // Set loading state
        submitBtn.prop('disabled', true)
                .text(sjb_i18n.submitting_text);

        // AJAX request
        $.ajax({
            url: sjb_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressBar.find('.sjb-progress-bar').css('width', percent + '%');
                        if (percent < 100) {
                            submitBtn.text(sjb_i18n.uploading_text.replace('{percent}', percent));
                        }
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    form.replaceWith(
                        `<div class="sjb-alert sjb-alert-success">
                            ${response.data.message || sjb_i18n.success_message}
                        </div>`
                    );
                } else {
                    showError(form, response.data?.message || sjb_i18n.generic_error);
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.data || 
                              xhr.statusText || 
                              sjb_i18n.network_error;
                showError(form, message);
            },
            complete: function() {
                progressBar.remove();
            }
        });
    });

    function showError(formElement, message) {
        formElement.find('.sjb-alert-error').remove();
        formElement.prepend(
            `<div class="sjb-alert sjb-alert-error">
                ${message}
            </div>`
        );
        formElement.find('button[type="submit"]')
            .prop('disabled', false)
            .text(sjb_i18n.submit_text);
        
        $('html, body').animate({
            scrollTop: formElement.offset().top - 100
        }, 300);
    }
});