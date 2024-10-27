<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.adapti.me
 * @since             2.0.1
 * @package           Adapti_Link
 *
 * @wordpress-plugin
 * Plugin Name:       Personalize Content
 * Plugin URI:        www.adapti.me/wordpress-plugin/
 * Description:       Manage your website to properly use adapti.me web adaptation service.
 * Version:           2.0.2
 * Author:            Adapti.me
 * Author URI:        www.adapti.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       adapti-link
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-adapti-link-activator.php
 */
function activate_adapti_link() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-adapti-link-activator.php';
	Adapti_Link_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-adapti-link-deactivator.php
 */
function deactivate_adapti_link() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-adapti-link-deactivator.php';
	Adapti_Link_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_adapti_link' );
register_deactivation_hook( __FILE__, 'deactivate_adapti_link' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-adapti-link.php';
// Import public functions
require plugin_dir_path( __FILE__ ) . 'includes/public-functions.php';



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_adapti_link() {

	$plugin = new Adapti_Link();
	$plugin->run();

}
run_adapti_link();
