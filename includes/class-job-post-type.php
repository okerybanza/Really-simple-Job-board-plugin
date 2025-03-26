<?php
if (!defined('ABSPATH')) exit;

class SJB_Job_Post_Type {
    public static function register() {
        add_action('init', [__CLASS__, 'register_post_type'], 5);
    }

    public static function register_post_type() {
        register_post_type('job', [
            'labels' => [
                'name' => __('Jobs'),
                'singular_name' => __('Job'),
                'add_new_item' => __('Add New Job')
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'jobs'],
            'supports' => ['title', 'editor', 'thumbnail'],
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-portfolio'
        ]);
    }
}