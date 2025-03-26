<?php
if (!defined('ABSPATH')) exit;

class SJB_Job_Post_Type {
    public static function register() {
        // Ensure WordPress environment is loaded
        global $wp_rewrite;

        // Register post type on 'init' hook properly
        add_action('init', [__CLASS__, 'register_job_post_type'], 10);
    }

    public static function register_job_post_type() {
        $labels = [
            'name' => __('Jobs'),
            'singular_name' => __('Job'),
            'menu_name' => __('Job Board'),
            // Add other labels as needed
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'jobs'],
            'supports' => ['title', 'editor', 'thumbnail'],
            'show_in_rest' => true, // Enable Gutenberg editor
            'capability_type' => 'post',
        ];

        register_post_type('job', $args);

        // Flush rewrite rules on plugin activation
        flush_rewrite_rules(false);
    }
}