<?php
class SJB_Job_Meta {
    public static function init() {
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_action('save_post', [self::class, 'save_meta']);
    }

    public static function add_meta_boxes() {
        add_meta_box(
            'sjb_job_details',
            __('Job Details', 'simple-job-board'),
            [self::class, 'render_meta_box'],
            'sjb_job',
            'normal',
            'high'
        );
    }

    public static function render_meta_box($post) {
        wp_nonce_field('sjb_save_meta', 'sjb_meta_nonce');

        $meta = [
            'location' => get_post_meta($post->ID, '_sjb_location', true),
            'type' => get_post_meta($post->ID, '_sjb_type', true),
            'salary' => get_post_meta($post->ID, '_sjb_salary', true),
            'closing_date' => get_post_meta($post->ID, '_sjb_closing_date', true),
            'application_email' => get_post_meta($post->ID, '_sjb_application_email', true),
        ];

        include SJB_TEMPLATE_PATH . 'admin/meta-box.php';
    }

    public static function save_meta($post_id) {
        if (!isset($_POST['sjb_meta_nonce']) || !wp_verify_nonce($_POST['sjb_meta_nonce'], 'sjb_save_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = [
            'sjb_location',
            'sjb_type',
            'sjb_salary',
            'sjb_closing_date',
            'sjb_application_email',
        ];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
}