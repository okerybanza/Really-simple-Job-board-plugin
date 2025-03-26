<?php
if (!defined('ABSPATH')) exit;

class SJB_Job_Post_Type {
    public static function register() {
        add_action('init', [__CLASS__, 'register_post_type'], 5);
    }

    public static function register_post_type() {
        $labels = [
            'name' => __('Jobs', 'simple-job-board'),
            'singular_name' => __('Job', 'simple-job-board'),
            'add_new' => __('Add New Job', 'simple-job-board'),
            'add_new_item' => __('Add New Job', 'simple-job-board'),
            'edit_item' => __('Edit Job', 'simple-job-board'),
            'new_item' => __('New Job', 'simple-job-board'),
            'view_item' => __('View Job', 'simple-job-board'),
            'search_items' => __('Search Jobs', 'simple-job-board'),
            'not_found' => __('No jobs found', 'simple-job-board'),
            'not_found_in_trash' => __('No jobs found in Trash', 'simple-job-board'),
            'menu_name' => __('Job Board', 'simple-job-board')
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'jobs'],
            'supports' => ['title', 'editor', 'thumbnail'],
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 25, // Below Comments
            'menu_icon' => 'dashicons-clipboard',
            'show_in_rest' => true,
            'capability_type' => 'post',
            'map_meta_cap' => true
        ];

        register_post_type('sjb_job', $args); // Changed from 'job' to avoid conflicts
    }
}