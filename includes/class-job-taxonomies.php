<?php
class SJB_Job_Taxonomies {
    public static function init() {
        add_action('init', [self::class, 'register_taxonomies']);
    }

    public static function register_taxonomies() {
        register_taxonomy('sjb_category', 'sjb_job', [
            'labels' => [
                'name' => __('Job Categories', 'simple-job-board'),
                'singular_name' => __('Category', 'simple-job-board'),
                'menu_name' => __('Categories', 'simple-job-board'),
            ],
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => ['slug' => 'job-category'],
            'show_in_rest' => true,
        ]);
    }
}