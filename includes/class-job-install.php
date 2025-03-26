<?php
if (!defined('ABSPATH')) exit;

class SJB_Job_Install {
    public static function install() {
        self::create_tables();
    }

    private static function create_tables() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'job_applications';
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            job_id BIGINT UNSIGNED NOT NULL,
            applicant_name VARCHAR(255) NOT NULL,
            applicant_email VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            resume_url VARCHAR(255) NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            applied_at DATETIME NOT NULL,
            PRIMARY KEY (id)
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public static function check_version() {
        if (get_option('sjb_version') !== SJB_VERSION) {
            self::install();
            update_option('sjb_version', SJB_VERSION);
        }
    }
}