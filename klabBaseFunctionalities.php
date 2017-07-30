<?php

/**Used Wordpress Plugin Boilerplate as base.
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       Klab site functionalities
 * Description:       Contains custom post types and other post related features for the site.
 * Version:           1.0.0
 * Author:            Aino
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       klabBaseFunctionalities
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-klabBaseFunctionalities-activator.php
 */
function activate_klabBaseFunctionalities() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-klabBaseFunctionalities-activator.php';
    klabBaseFunctionalities_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-klabBaseFunctionalities-deactivator.php
 */
function deactivate_klabBaseFunctionalities() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-klabBaseFunctionalities-deactivator.php';
	KlabBaseFunctionalities_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_klabBaseFunctionalities' );
register_deactivation_hook( __FILE__, 'deactivate_klabBaseFunctionalities' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-klabBaseFunctionalities.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_klabBaseFunctionalities() {

	$plugin = new klabBaseFunctionalities();
	$plugin->run();

}
run_klabBaseFunctionalities();
