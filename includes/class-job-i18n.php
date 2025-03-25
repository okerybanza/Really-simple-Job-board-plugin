<?php
class SJB_i18n {
    public static function init() {
        add_action('plugins_loaded', [self::class, 'load_textdomain']);
        add_filter('plugin_locale', [self::class, 'set_admin_locale'], 10, 2);
    }

    public static function load_textdomain() {
        load_plugin_textdomain(
            'simple-job-board',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages/'
        );
    }

    public static function set_admin_locale($locale, $domain) {
        if ('simple-job-board' === $domain && is_admin()) {
            return get_user_locale();
        }
        return $locale;
    }
}