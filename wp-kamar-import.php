<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              allteams.com
 * @since             1.0.0
 * @package           Wp_Kamar_Import
 *
 * @wordpress-plugin
 * Plugin Name:       WP Kamar Import
 * Plugin URI:        wp-kamar-import
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            AllTeams
 * Author URI:        allteams.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-kamar-import
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_KAMAR_IMPORT_VERSION', '1.0.0' );
define( 'WP_KAMAR_JSON_DIRECTORY', ABSPATH . 'kamar/directoryservices/json/data/' );
define( 'WP_KAMAR_JSON_DIRECTORY_DONE_NOTICES', ABSPATH . 'kamar/directoryservices/json/data-done-notices/' );
define( 'WP_KAMAR_JSON_DIRECTORY_DONE_EVENTS', ABSPATH . 'kamar/directoryservices/json/data-done-events/' );

/**
 * For autoloading classes
 */
spl_autoload_register( 'wki_directory_autoload_class' );

function wki_directory_autoload_class( $class_name ) {
	if ( false !== strpos( $class_name, 'WKI' ) ) {

		$include_classes_dir = realpath( get_template_directory( __FILE__ ) ) . DIRECTORY_SEPARATOR;
		$admin_classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR;
		$class_file = str_replace( '_', DIRECTORY_SEPARATOR, $class_name ) . '.php';

		if ( file_exists( $include_classes_dir . $class_file ) ) {
			require_once $include_classes_dir . $class_file;
		}

		if ( file_exists( $admin_classes_dir . $class_file ) ) {
			require_once $admin_classes_dir . $class_file;
		}

	}
}

function wki_get_plugin_details(){
	// Check if get_plugins() function exists. This is required on the front end of the
	// site, since it is in a file that is normally only loaded in the admin.
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$ret = get_plugins();
	return $ret['wp-kamar-import/wp-kamar-import.php'];
}

function wki_get_text_domain(){
	$ret = wki_get_plugin_details();
	return $ret['TextDomain'];
}

function wki_get_plugin_dir(){
	return plugin_dir_path( __FILE__ );
}

/**
* get the plugin url path.
**/
function wki_get_plugin_dir_url() {
 return plugin_dir_url( __FILE__ );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-kamar-import-activator.php
 */
function activate_wp_kamar_import() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-kamar-import-activator.php';
	Wp_Kamar_Import_Activator::activate();

	if ( ! wp_next_scheduled( 'wki_cron_hook' ) ) {
    wp_schedule_event( time(), 'hourly', 'wki_cron_hook' );
	}

}

add_action( 'wki_cron_hook', 'wki_cron_hook_function' );
//create your function, that runs on cron
function wki_cron_hook_function() {
	WKI_SyncTo::get_instance()->sync_to();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-kamar-import-deactivator.php
 */
function deactivate_wp_kamar_import() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-kamar-import-deactivator.php';
	Wp_Kamar_Import_Deactivator::deactivate();
	wp_clear_scheduled_hook( 'wki_cron_hook' );
}

register_activation_hook( __FILE__, 'activate_wp_kamar_import' );
register_deactivation_hook( __FILE__, 'deactivate_wp_kamar_import' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-kamar-import.php';
require plugin_dir_path( __FILE__ ) . 'functions/helper.php';
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_kamar_import() {

	$plugin = new Wp_Kamar_Import();
	$plugin->run();

	WKI_Menu::get_instance();
	//shortcodes
	WKI_Shortcode_Notices::get_instance();

}
add_action( 'plugins_loaded', 'run_wp_kamar_import' );
//run_wp_kamar_import();

add_action( 'init' , 'init_wp_kamar_import');
function init_wp_kamar_import() {
	WKI_CPT_Init::get_instance()->init_cpt();
	WKI_SyncTo::get_instance()->manual();
}
