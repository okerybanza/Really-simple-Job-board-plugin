<?php
class SJB_Job_Filters {
    public static function init() {
        add_shortcode('job_search', [self::class, 'search_form_shortcode']);
        add_action('pre_get_posts', [self::class, 'filter_jobs_query']);
    }

    public static function search_form_shortcode() {
        ob_start();
        include SJB_PLUGIN_DIR . 'templates/job-search-form.php';
        return ob_get_clean();
    }

    public static function filter_jobs_query($query) {
        if (!is_admin() && $query->is_main_query() && is_post_type_archive('sjb_job')) {
            $meta_query = [];

            // Location filter
            if (!empty($_GET['location'])) {
                $meta_query[] = [
                    'key' => '_sjb_location',
                    'value' => sanitize_text_field($_GET['location']),
                    'compare' => 'LIKE'
                ];
            }

            // Job type filter
            if (!empty($_GET['job_type'])) {
                $meta_query[] = [
                    'key' => '_sjb_type',
                    'value' => sanitize_text_field($_GET['job_type']),
                ];
            }

            if (!empty($meta_query)) {
                $query->set('meta_query', $meta_query);
            }

            // Category filter
            if (!empty($_GET['category'])) {
                $query->set('tax_query', [
                    [
                        'taxonomy' => 'sjb_category',
                        'field' => 'slug',
                        'terms' => sanitize_text_field($_GET['category']),
                    ]
                ]);
            }
        }
    }
}