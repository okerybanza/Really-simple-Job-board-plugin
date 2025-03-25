<?php
class SJB_Job_Shortcodes {
    public static function init() {
        add_shortcode('job_board', [self::class, 'job_board_shortcode']);
        add_filter('the_content', [self::class, 'add_application_form']);
    }

    public static function job_board_shortcode($atts) {
        $atts = shortcode_atts([
            'limit' => -1,
            'type' => '',
        ], $atts);

        $args = [
            'post_type' => 'sjb_job',
            'posts_per_page' => $atts['limit'],
            'post_status' => 'publish',
        ];

        if (!empty($atts['type'])) {
            $args['meta_query'] = [
                [
                    'key' => '_sjb_type',
                    'value' => $atts['type'],
                ],
            ];
        }

        $jobs = new WP_Query($args);

        ob_start();
        if ($jobs->have_posts()) {
            echo '<div class="sjb-job-list">';
            while ($jobs->have_posts()) {
                $jobs->the_post();
                self::render_job_card(get_the_ID());
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p>' . __('No jobs found.', 'simple-job-board') . '</p>';
        }
        return ob_get_clean();
    }

    private static function render_job_card($job_id) {
        $meta = [
            'location' => get_post_meta($job_id, '_sjb_location', true),
            'type' => get_post_meta($job_id, '_sjb_type', true),
            'salary' => get_post_meta($job_id, '_sjb_salary', true),
            'closing_date' => get_post_meta($job_id, '_sjb_closing_date', true),
        ];

        ?>
        <div class="sjb-job-card">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="sjb-job-meta">
                <span class="sjb-location">📍 <?php echo esc_html($meta['location']); ?></span>
                <span class="sjb-type">💼 <?php echo esc_html(ucfirst(str_replace('-', ' ', $meta['type']))); ?></span>
                <span class="sjb-salary">💰 <?php echo esc_html($meta['salary']); ?></span>
                <span class="sjb-closing-date">⏳ <?php echo esc_html($meta['closing_date']); ?></span>
            </div>
        </div>
        <?php
    }

    public static function add_application_form($content) {
        if (is_singular('sjb_job')) {
            $job_id = get_the_ID();
            $closing_date = get_post_meta($job_id, '_sjb_closing_date', true);
            $is_open = empty($closing_date) || strtotime($closing_date) >= current_time('timestamp');

            if ($is_open) {
                ob_start();
                include SJB_PLUGIN_DIR . 'templates/application-form.php';
                $content .= ob_get_clean();
            } else {
                $content .= '<div class="sjb-closed-notice">' . __('This job listing has closed.', 'simple-job-board') . '</div>';
            }
        }
        return $content;
    }
}