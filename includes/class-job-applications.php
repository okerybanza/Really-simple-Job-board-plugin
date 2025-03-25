<?php
class SJB_Job_Applications {
    public static function init() {
        add_action('admin_menu', [self::class, 'add_admin_menu']);
        add_action('admin_init', [self::class, 'handle_application_submission']);
    }

    public static function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=sjb_job',
            __('Job Applications', 'simple-job-board'),
            __('Applications', 'simple-job-board'),
            'manage_options',
            'job-applications',
            [self::class, 'render_applications_page']
        );
    }

    public static function render_applications_page() {
        global $wpdb;
        $applications = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}sjb_applications ORDER BY application_date DESC"
        );

        include SJB_PLUGIN_DIR . 'templates/admin/applications-list.php';
    }

    public static function handle_application_submission() {
        if (!isset($_POST['sjb_submit_application'])) return;

        // Validate and process application (expanded version from earlier)
        // Save to custom database table
        global $wpdb;
        
        $wpdb->insert(
            "{$wpdb->prefix}sjb_applications",
            [
                'job_id' => $_POST['job_id'],
                'applicant_name' => sanitize_text_field($_POST['sjb_applicant_name']),
                'applicant_email' => sanitize_email($_POST['sjb_applicant_email']),
                'applicant_phone' => sanitize_text_field($_POST['sjb_applicant_phone']),
                'message' => sanitize_textarea_field($_POST['sjb_applicant_message']),
                'resume_path' => self::handle_file_upload(),
                'application_date' => current_time('mysql'),
                'status' => 'pending'
            ]
        );
    }

    private static function handle_file_upload() {
        // File upload handling logic
    }
}