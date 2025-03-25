<?php
class SJB_Job_Install {
    const DB_VERSION = '1.0';

    public static function activate() {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'sjb_applications';

        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            job_id bigint(20) NOT NULL,
            applicant_name varchar(100) NOT NULL,
            applicant_email varchar(100) NOT NULL,
            applicant_phone varchar(30) DEFAULT NULL,
            message text DEFAULT NULL,
            resume_path varchar(255) DEFAULT NULL,
            application_date datetime NOT NULL,
            status varchar(20) DEFAULT 'pending',
            PRIMARY KEY (id),
            KEY job_id (job_id)
        ) $charset_collate;";

        dbDelta($sql);
        add_option('sjb_db_version', self::DB_VERSION);
        flush_rewrite_rules();
    }

    public static function uninstall() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sjb_applications';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
        delete_option('sjb_db_version');
    }
}