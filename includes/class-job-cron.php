<?php
class SJB_Cron {
    public static function init() {
        add_action('sjb_check_expired_jobs', [self::class, 'close_expired_jobs']);
        add_action('sjb_send_reminders', [self::class, 'send_reminder_emails']);
        add_filter('cron_schedules', [self::class, 'add_custom_schedules']);
        
        register_activation_hook(__FILE__, [self::class, 'activate_crons']);
        register_deactivation_hook(__FILE__, [self::class, 'deactivate_crons']);
    }

    public static function add_custom_schedules($schedules) {
        $schedules['weekly'] = [
            'interval' => 604800,
            'display'  => __('Once Weekly')
        ];
        return $schedules;
    }

    public static function activate_crons() {
        if (!wp_next_scheduled('sjb_check_expired_jobs')) {
            wp_schedule_event(time(), 'daily', 'sjb_check_expired_jobs');
        }
        
        if (!wp_next_scheduled('sjb_send_reminders')) {
            wp_schedule_event(time(), 'weekly', 'sjb_send_reminders');
        }
    }

    public static function deactivate_crons() {
        wp_clear_scheduled_hook('sjb_check_expired_jobs');
        wp_clear_scheduled_hook('sjb_send_reminders');
    }

    public static function close_expired_jobs() {
        $jobs = get_posts([
            'post_type' => 'sjb_job',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_sjb_closing_date',
                    'value' => date('Y-m-d'),
                    'compare' => '<',
                    'type' => 'DATE'
                ],
                [
                    'key' => '_sjb_status',
                    'value' => 'closed',
                    'compare' => '!='
                ]
            ]
        ]);

        foreach ($jobs as $job) {
            update_post_meta($job->ID, '_sjb_status', 'closed');
        }
    }

    public static function send_reminder_emails() {
        $incomplete_apps = get_posts([
            'post_type' => 'sjb_application',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_sjb_status',
                    'value' => 'pending',
                    'compare' => '='
                ]
            ]
        ]);

        foreach ($incomplete_apps as $app) {
            // Send reminder logic
        }
    }
}