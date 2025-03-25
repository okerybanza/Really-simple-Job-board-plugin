<?php
class SJB_Job_Export {
    public static function init() {
        add_action('admin_init', [self::class, 'handle_export']);
        add_action('admin_menu', [self::class, 'add_export_menu']);
    }

    public static function add_export_menu() {
        add_submenu_page(
            'edit.php?post_type=sjb_job',
            __('Export Applications', 'simple-job-board'),
            __('Export', 'simple-job-board'),
            'manage_options',
            'job-export',
            [self::class, 'render_export_page']
        );
    }

    public static function render_export_page() {
        include SJB_PLUGIN_DIR . 'templates/admin/export-page.php';
    }

    public static function handle_export() {
        if (!isset($_POST['sjb_export_applications'])) return;

        check_admin_referer('sjb_export');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=job-applications-' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, [
            __('Job Title', 'simple-job-board'),
            __('Applicant Name', 'simple-job-board'),
            __('Email', 'simple-job-board'),
            __('Phone', 'simple-job-board'),
            __('Application Date', 'simple-job-board'),
            __('Status', 'simple-job-board')
        ]);

        // Get applications
        global $wpdb;
        $applications = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}sjb_applications ORDER BY application_date DESC"
        );

        // Add rows
        foreach ($applications as $app) {
            fputcsv($output, [
                get_the_title($app->job_id),
                $app->applicant_name,
                $app->applicant_email,
                $app->applicant_phone,
                $app->application_date,
                $app->status
            ]);
        }

        fclose($output);
        exit;
    }
}