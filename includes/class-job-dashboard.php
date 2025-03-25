<?php
class SJB_Dashboard {
    public static function init() {
        add_shortcode('job_dashboard', [self::class, 'render_dashboard']);
        add_action('wp_enqueue_scripts', [self::class, 'load_assets']);
    }

    public static function load_assets() {
        wp_enqueue_style(
            'sjb-dashboard',
            SJB_PLUGIN_URL . 'assets/css/dashboard.css',
            [],
            SJB_VERSION
        );
    }

    public static function render_dashboard() {
        if (!is_user_logged_in()) {
            return '<div class="sjb-alert">' . 
                   __('Please login to access the dashboard', 'simple-job-board') . 
                   '</div>';
        }

        ob_start();
        include SJB_TEMPLATE_PATH . 'frontend/dashboard.php';
        return ob_get_clean();
    }

    public static function get_user_jobs($user_id) {
        return get_posts([
            'post_type' => 'sjb_job',
            'author' => $user_id,
            'posts_per_page' => -1
        ]);
    }
}