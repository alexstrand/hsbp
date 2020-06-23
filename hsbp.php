<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              DevHouse.se
 * @since             1.0.0
 * @package           Hsbp
 *
 * @wordpress-plugin
 * Plugin Name:       HubSpot Blog Posts
 * Plugin URI:        DevHouse.se
 * Description:       A simple plugin that will display your HubSpot posts with the use of a shortcode.
 * Version:           1.0.0
 * Author:            Alex Strand
 * Author URI:        DevHouse.se
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hsbp
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
define( 'HSBP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hsbp-activator.php
 */
function activate_hsbp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hsbp-activator.php';
	Hsbp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hsbp-deactivator.php
 */
function deactivate_hsbp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hsbp-deactivator.php';
	Hsbp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_hsbp' );
register_deactivation_hook( __FILE__, 'deactivate_hsbp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hsbp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_hsbp() {

	$plugin = new Hsbp();
	$plugin->run();

}
run_hsbp();
