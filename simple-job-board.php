<?php
/**
 * Plugin Name: Really Simple Job Board
 * Description: A lightweight job listing system for WordPress
 * Version: 1.0
 * Author: Crudix
 * Text Domain: simple-job-board
 * Text Domain Path: /languages
 */

// Security check
if (!defined('ABSPATH') || !defined('WPINC')) {
    exit;
}

// Define plugin constants
define('SJB_VERSION', '1.0');
define('SJB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SJB_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SJB_TEMPLATE_PATH', SJB_PLUGIN_DIR . 'templates/');
define('SJB_LANGUAGES_PATH', SJB_PLUGIN_DIR . 'languages/');
define('SJB_MAX_FILE_SIZE', 5 * 1024 * 1024);
define('SJB_MAX_FILE_UPLOADS', 3);
define('SJB_ALLOWED_FILE_TYPES', ['application/pdf']);

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
    
    if (strpos($class, $prefix) !== 0) return;
    
    $relative_class = substr($class, strlen($prefix));
    $file = $base_dir . 'class-' . strtolower(str_replace('_', '-', $relative_class)) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Core components
$core_components = [
    'install'           => 'Job_Install',
    'i18n'              => 'Job_i18n',
    'post-type'         => 'Job_Post_Type',
    'meta'              => 'Job_Meta',
    'shortcodes'        => 'Job_Shortcodes',
    'applications'      => 'Job_Applications',
    'taxonomies'        => 'Job_Taxonomies',
    'filters'           => 'Job_Filters',
    'export'            => 'Job_Export',
    'calendar'          => 'Job_Calendar',
    'cron'              => 'Job_Cron',
    'dashboard'         => 'Job_Dashboard',
    'ajax'              => 'Job_Ajax'
];

// Initialize components
add_action('plugins_loaded', function() use ($core_components) {
    foreach ($core_components as $file => $class) {
        $class_name = "SJB_{$class}";
        if (class_exists($class_name)) {
            if (method_exists($class_name, 'init')) {
                $class_name::init();
            } elseif (method_exists($class_name, 'register')) {
                $class_name::register();
            }
        }
    }
});

// Frontend assets
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('sjb-style', SJB_PLUGIN_URL . 'assets/css/style.css', [], SJB_VERSION);
    wp_enqueue_script('sjb-script', SJB_PLUGIN_URL . 'assets/js/script.js', ['jquery'], SJB_VERSION, true);
    
    wp_localize_script('sjb-script', 'sjb_config', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'max_size' => SJB_MAX_FILE_SIZE,
        'max_files' => SJB_MAX_FILE_UPLOADS,
        'allowed_types' => SJB_ALLOWED_FILE_TYPES,
        'i18n' => [
            'submit_text' => __('Submit Application', 'simple-job-board'),
            'submitting_text' => __('Submitting...', 'simple-job-board'),
            'uploading_text' => __('Uploading: {percent}%', 'simple-job-board'),
            'success_message' => __('Application submitted successfully!', 'simple-job-board'),
            'generic_error' => __('An error occurred. Please try again.', 'simple-job-board'),
            'network_error' => __('Network error. Please check your connection.', 'simple-job-board'),
            'file_too_large' => __('{file} exceeds maximum size of {size}', 'simple-job-board'),
            'invalid_type' => __('{file}: Only PDF files are allowed', 'simple-job-board'),
            'too_many_files' => __('Maximum {max} files allowed', 'simple-job-board'),
            'no_files_selected' => __('Please select at least one file', 'simple-job-board'),
            'drop_files' => __('Drop files here or click to browse', 'simple-job-board')
        ]
    ]);
});

// Admin assets
add_action('admin_init', function() {
    if (is_admin()) {
        add_action('admin_enqueue_scripts', function() {
            wp_enqueue_style('sjb-admin', SJB_PLUGIN_URL . 'assets/css/admin.css', [], SJB_VERSION);
            wp_enqueue_script('sjb-admin', SJB_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], SJB_VERSION);
        });
    }
});