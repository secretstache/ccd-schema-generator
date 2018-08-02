<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.secretstache.com/
 * @since             1.0.0
 * @package           CCD_Schema_Generator
 *
 * @wordpress-plugin
 * Plugin Name:       CCD Schema Generator
 * Plugin URI:        https://www.secretstache.com/
 * Description:       SSM Schema Generator
 * Version:           1.0.0
 * Author:            Secret Stache Media
 * Author URI:        https://www.secretstache.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ccd-schema-generator
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ccd-schema-generator-activator.php
 */
function activate_ccd_schema_generator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ccd-schema-generator-activator.php';
	CCD_Schema_Generator_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ccd-schema-generator-deactivator.php
 */
function deactivate_ccd_schema_generator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ccd-schema-generator-deactivator.php';
	CCD_Schema_Generator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ccd_schema_generator' );
register_deactivation_hook( __FILE__, 'deactivate_ccd_schema_generator' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ccd-schema-generator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ccd_schema_generator() {

	$plugin = new CCD_Schema_Generator();
	$plugin->run();

}
run_ccd_schema_generator();
