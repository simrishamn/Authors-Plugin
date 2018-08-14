<?php
/**
 * Plugin Name: Multi authors plugin
 * Description: A simple plugin for adding multiple authors to a post
 * Version: 0.0
 * Author: Magdalena Korpela
 * Author URI: https://github.com/magosb
 * License: MIT
 * 
 * @package WordPress
 * @author  Magdalena Korpela
 * @version 1.0
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
define( 'MULTI_AUTHORS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-multi-authors-activator.php
 */
function activate_multi_authors() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-multi-authors-activator.php';
	Multi_Authors_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-multi-authors-deactivator.php
 */
function deactivate_multi_authors() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-multi-authors-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_multi_authors' );
register_deactivation_hook( __FILE__, 'deactivate_multi_authors' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-multi-authors.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_multi_authors() {

	$plugin = new Plugin_Name();
	$plugin->run();

}
run_multi_authors();

