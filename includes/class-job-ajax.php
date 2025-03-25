<?php
class SJB_Job_Ajax {
    public static function init() {
        add_action('wp_ajax_sjb_submit_application', [self::class, 'handle_application']);
        add_action('wp_ajax_nopriv_sjb_submit_application', [self::class, 'handle_application']);
    }

    public static function handle_application() {
        check_ajax_referer('sjb_ajax_nonce', 'nonce');

        $data = [
            'job_id' => absint($_POST['job_id']),
            'name' => sanitize_text_field($_POST['name']),
            'email' => sanitize_email($_POST['email']),
            'phone' => sanitize_text_field($_POST['phone']),
            'message' => sanitize_textarea_field($_POST['message']),
        ];

        // Process file upload
        if (!empty($_FILES['resume'])) {
            $upload = wp_handle_upload($_FILES['resume'], [
                'test_form' => false,
                'mimes' => ['pdf' => 'application/pdf']
            ]);

            if ($upload && !isset($upload['error'])) {
                $data['resume_path'] = $upload['file'];
            } else {
                wp_send_json_error(['message' => __('File upload failed: ', 'simple-job-board') . $upload['error']]);
            }
        }

        // Save to database
        global $wpdb;
        $wpdb->insert(
            "{$wpdb->prefix}sjb_applications",
            [
                'job_id' => $data['job_id'],
                'applicant_name' => $data['name'],
                'applicant_email' => $data['email'],
                'applicant_phone' => $data['phone'],
                'message' => $data['message'],
                'resume_path' => $data['resume_path'] ?? '',
                'application_date' => current_time('mysql'),
                'status' => 'pending'
            ]
        );

        // Send email notification
        self::send_notification_email($data);

        wp_send_json_success(['message' => __('Application submitted successfully!', 'simple-job-board')]);
    }

    private static function send_notification_email($data) {
        $to = get_post_meta($data['job_id'], '_sjb_application_email', true);
        $subject = sprintf(__('New Application for: %s', 'simple-job-board'), get_the_title($data['job_id']));
        
        ob_start();
        include SJB_TEMPLATE_PATH . 'emails/new-application.php';
        $message = ob_get_clean();

        wp_mail($to, $subject, $message, ['Content-Type: text/html; charset=UTF-8']);
    }
}