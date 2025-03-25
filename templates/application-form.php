<div class="sjb-application-form">
    <h3><?php _e('Apply for this position', 'simple-job-board'); ?></h3>
    <form method="post" enctype="multipart/form-data">
        <p>
            <label for="sjb_applicant_name"><?php _e('Your Name', 'simple-job-board'); ?> *</label>
            <input type="text" id="sjb_applicant_name" name="sjb_applicant_name" required>
        </p>
        <p>
            <label for="sjb_applicant_email"><?php _e('Email Address', 'simple-job-board'); ?> *</label>
            <input type="email" id="sjb_applicant_email" name="sjb_applicant_email" required>
        </p>
        <p>
            <label for="sjb_applicant_phone"><?php _e('Phone Number', 'simple-job-board'); ?></label>
            <input type="tel" id="sjb_applicant_phone" name="sjb_applicant_phone">
        </p>
        <p>
            <label for="sjb_applicant_message"><?php _e('Cover Letter', 'simple-job-board'); ?> *</label>
            <textarea id="sjb_applicant_message" name="sjb_applicant_message" rows="5" required></textarea>
        </p>
        
        <!-- Enhanced File Upload Section -->
        <div class="sjb-upload-section">
            <label><?php _e('Resume Upload', 'simple-job-board'); ?> *</label>
            <div class="sjb-upload-dropzone">
                <div class="sjb-dropzone-content">
                    <svg class="sjb-upload-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    <p><?php _e('Drag & drop PDF files here', 'simple-job-board'); ?></p>
                    <p class="sjb-dropzone-hint"><?php _e('or click to browse files', 'simple-job-board'); ?></p>
                    <p class="sjb-dropzone-requirements">
                        <?php _e('Max 3 files • 5MB each • PDF only', 'simple-job-board'); ?>
                    </p>
                </div>
            </div>
            <input type="file" id="sjb_applicant_resume" name="sjb_files[]" accept=".pdf" multiple style="display:none;">
            <div class="sjb-file-list"></div>
        </div>
        
        <button type="submit" name="sjb_submit_application" class="sjb-submit-button">
            <?php _e('Submit Application', 'simple-job-board'); ?>
        </button>
    </form>
</div>