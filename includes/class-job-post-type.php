<?php
class SJB_Job_Post_Type {
    public static function register() {
        $labels = [
            'name' => __('Jobs', 'simple-job-board'),
            'singular_name' => __('Job', 'simple-job-board'),
            'menu_name' => __('Job Board', 'simple-job-board'),
            'add_new' => __('Add New Job', 'simple-job-board'),
            'add_new_item' => __('Add New Job', 'simple-job-board'),
            'edit_item' => __('Edit Job', 'simple-job-board'),
            'new_item' => __('New Job', 'simple-job-board'),
            'view_item' => __('View Job', 'simple-job-board'),
            'search_items' => __('Search Jobs', 'simple-job-board'),
            'not_found' => __('No jobs found', 'simple-job-board'),
            'not_found_in_trash' => __('No jobs found in Trash', 'simple-job-board'),
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-clipboard',
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => 'jobs'],
            'show_in_rest' => true,
        ];

        register_post_type('sjb_job', $args);
    }
}