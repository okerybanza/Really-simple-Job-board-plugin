<?php
/**
 * Plugin Name: Really Simple Job Board
 * Description: A lightweight job listing system for WordPress
 * Version: 1.1
 * Author: Crudix
 * Text Domain: simple-job-board
 * Domain Path: /languages
 * Requires PHP: 7.4
 * Requires at least: 5.6
 */

// Security check
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

// Define plugin constants
define('SJB_VERSION', '1.1');
define('SJB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SJB_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SJB_TEMPLATE_PATH', SJB_PLUGIN_DIR . 'templates/');
define('SJB_LANGUAGES_PATH', SJB_PLUGIN_DIR . 'languages/');
define('SJB_MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('SJB_MAX_FILE_UPLOADS', 3);
define('SJB_ALLOWED_FILE_TYPES', ['pdf' => 'application/pdf']);

// Load translations
add_action('init', function() {
    load_plugin_textdomain(
        'simple-job-board',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages/'
    );
});

// Autoloader for classes
spl_autoload_register(function ($class) {
    $prefix = 'SJB_';
    $base_dir = SJB_PLUGIN_DIR . 'includes/';
    
    // Check if class uses our prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;

    $relative_class = substr($class, $len);
    $file = $base_dir . 'class-' . strtolower(str_replace('_', '-', $relative_class)) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Core components with initialization priority
$core_components = [
    'install'           => 'Job_Install',
    'post-type'         => 'Job_Post_Type',  // Fixed rewrite initialization
    'taxonomies'        => 'Job_Taxonomies',
    'meta'              => 'Job_Meta',
    'applications'      => 'Job_Applications',
    'shortcodes'        => 'Job_Shortcodes',
    'ajax'              => 'Job_Ajax',
    'filters'           => 'Job_Filters',
    'dashboard'         => 'Job_Dashboard'
];

// Initialize components with proper hooks
add_action('plugins_loaded', function() use ($core_components) {
    // First initialize essential systems
    SJB_Job_Install::check_version();
    
    // Register post types and taxonomies early on init
    add_action('init', function() use ($core_components) {
        foreach (['post-type', 'taxonomies'] as $key) {
            if (isset($core_components[$key])) {
                $class_name = "SJB_{$core_components[$key]}";
                if (method_exists($class_name, 'register')) {
                    $class_name::register();
                }
            }
        }
    }, 5);  // Early priority for CPT registration

    // Initialize other components
    add_action('init', function() use ($core_components) {
        foreach ($core_components as $key => $class) {
            if (in_array($key, ['post-type', 'taxonomies'])) continue;
            
            $class_name = "SJB_{$class}";
            if (class_exists($class_name)) {
                if (method_exists($class_name, 'init')) {
                    $class_name::init();
                }
            }
        }
    }, 10);
});

// Register activation/deactivation hooks
register_activation_hook(__FILE__, function() {
    require_once SJB_PLUGIN_DIR . 'includes/class-job-install.php';
    SJB_Job_Install::install();
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});

// Frontend assets
add_action('wp_enqueue_scripts', function() {
    if (is_singular('job')) {
        wp_enqueue_style(
            'sjb-frontend',
            SJB_PLUGIN_URL . 'assets/css/frontend.css',
            [],
            SJB_VERSION
        );
        
        wp_enqueue_script(
            'sjb-application',
            SJB_PLUGIN_URL . 'assets/js/application.js',
            ['jquery', 'wp-util'],
            SJB_VERSION,
            true
        );

        wp_localize_script('sjb-application', 'sjb_config', [
            'ajax_url'   => admin_url('admin-ajax.php'),
            'nonce'      => wp_create_nonce('sjb_application_nonce'),
            'max_size'   => SJB_MAX_FILE_SIZE,
            'max_files' => SJB_MAX_FILE_UPLOADS,
            'allowed_types' => array_keys(SJB_ALLOWED_FILE_TYPES),
            'i18n'       => [
                'validation_error' => __('Please fill all required fields', 'simple-job-board'),
                'file_error'       => __('Invalid file type or size', 'simple-job-board'),
                'success_title'    => __('Application Submitted!', 'simple-job-board'),
                'success_message'  => __('We received your application.', 'simple-job-board')
            ]
        ]);
    }
});

// Admin assets
add_action('admin_enqueue_scripts', function($hook) {
    if (in_array($hook, ['edit.php', 'post.php', 'post-new.php'])) {
        $screen = get_current_screen();
        
        if ($screen->post_type === 'job') {
            wp_enqueue_style(
                'sjb-admin',
                SJB_PLUGIN_URL . 'assets/css/admin.css',
                [],
                SJB_VERSION
            );
            
            wp_enqueue_script(
                'sjb-admin',
                SJB_PLUGIN_URL . 'assets/js/admin.js',
                ['jquery', 'wp-util', 'jquery-ui-datepicker'],
                SJB_VERSION
            );
        }
    }
});