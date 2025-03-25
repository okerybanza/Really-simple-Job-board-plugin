<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

// Delete custom tables
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}sjb_applications");

// Delete options
delete_option('sjb_version');

// Cleanup post meta
$wpdb->query(
    "DELETE FROM {$wpdb->postmeta} 
    WHERE meta_key LIKE '_sjb_%'"
);

// Remove cron jobs
wp_clear_scheduled_hook('sjb_check_expired_jobs');
wp_clear_scheduled_hook('sjb_send_reminders');