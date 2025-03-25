<?php
class SJB_Calendar {
    public static function init() {
        add_shortcode('job_calendar', [self::class, 'render_calendar']);
        add_action('wp_enqueue_scripts', [self::class, 'load_assets']);
    }

    public static function load_assets() {
        wp_enqueue_script(
            'fullcalendar',
            'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js',
            [],
            '6.1.9'
        );
        
        wp_enqueue_style(
            'sjb-calendar',
            SJB_PLUGIN_URL . 'assets/css/calendar.css',
            [],
            SJB_VERSION
        );
    }

    public static function render_calendar() {
        ob_start();
        ?>
        <div id="sjb-calendar"></div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('sjb-calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: {
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    extraParams: {
                        action: 'sjb_get_calendar_events'
                    }
                }
            });
            calendar.render();
        });
        </script>
        <?php
        return ob_get_clean();
    }

    public static function get_events() {
        $events = [];
        $jobs = get_posts([
            'post_type' => 'sjb_job',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_sjb_closing_date',
                    'compare' => 'EXISTS'
                ]
            ]
        ]);

        foreach ($jobs as $job) {
            $closing_date = get_post_meta($job->ID, '_sjb_closing_date', true);
            $events[] = [
                'title' => $job->post_title,
                'start' => $closing_date,
                'url' => get_permalink($job->ID)
            ];
        }

        wp_send_json($events);
    }
}
add_action('wp_ajax_sjb_get_calendar_events', ['SJB_Calendar', 'get_events']);
add_action('wp_ajax_nopriv_sjb_get_calendar_events', ['SJB_Calendar', 'get_events']);