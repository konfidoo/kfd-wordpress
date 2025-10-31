<?php
/**
 * Plugin Name: Konfidoo Forms
 * Plugin URI: https://github.com/konfidoo/kfd-wordpress
 * Description: Integration of Konfidoo Forms
 * Author: Nico Blum
 * Author URI:        https://konfidoo.de
 * Version: 1.0.3
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt

 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Minimum supported PHP version to avoid parse errors from modern syntax.
define( 'KONFIDOO_MIN_PHP', '5.4.0' );

// Activation check: prevent activation on old PHP versions.
function konfidoo_activation_check() {
	if ( version_compare( PHP_VERSION, KONFIDOO_MIN_PHP, '<' ) ) {
		// If this runs during activation, deactivate and show an error.
		if ( function_exists( 'deactivate_plugins' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
		wp_die( sprintf( esc_html__( 'Konfidoo Forms requires PHP %1$s or greater. Your server is running PHP %2$s. Please upgrade PHP before activating this plugin.', 'konfidoo' ), KONFIDOO_MIN_PHP, PHP_VERSION ), esc_html__( 'Plugin Activation Error', 'konfidoo' ), array( 'back_link' => true ) );
	}
}
register_activation_hook( __FILE__, 'konfidoo_activation_check' );

// If PHP is too old, don't load the rest of the plugin — show admin notice.
if ( version_compare( PHP_VERSION, KONFIDOO_MIN_PHP, '<' ) ) {
	function konfidoo_php_version_notice() {
		echo '<div class="notice notice-error"><p>';
		echo sprintf( esc_html__( 'Konfidoo Forms requires PHP %1$s or greater. Your server is running PHP %2$s. The plugin has been disabled until you upgrade PHP.', 'konfidoo' ), KONFIDOO_MIN_PHP, PHP_VERSION );
		echo '</p></div>';
	}
	add_action( 'admin_notices', 'konfidoo_php_version_notice' );
	return;
}


// Enqueue the Konfidoo front-end script properly instead of echoing in wp_head.
function konfidoo_enqueue_frontend_script() {
	if ( ! is_admin() ) {
		wp_enqueue_script( 'konfidoo-elements', 'https://konfidoo.de/elements/v01/main.js', array(), null, false );
	}
}
add_action( 'wp_enqueue_scripts', 'konfidoo_enqueue_frontend_script' );


/**
 * Block Initializer.
 */
$konfidoo_init = plugin_dir_path( __FILE__ ) . 'src/init.php';
if ( file_exists( $konfidoo_init ) ) {
	require_once $konfidoo_init;
} else {
	// File missing — show an admin notice and bail out to avoid fatal on activation.
	add_action( 'admin_notices', function() use ( $konfidoo_init ) {
		echo '<div class="notice notice-error"><p>';
		echo sprintf( esc_html__( 'Konfidoo Forms failed to load because the required file %s is missing. Please reinstall the plugin or restore that file.', 'konfidoo' ), '<code>' . esc_html( $konfidoo_init ) . '</code>' );
		echo '</p></div>';
	} );
	// Stop loading the rest of the plugin to prevent fatal errors.
	return;
}

/**
 * Admin Settings.
 */
$konfidoo_admin = plugin_dir_path( __FILE__ ) . 'admin-settings.php';
if ( file_exists( $konfidoo_admin ) ) {
	require_once $konfidoo_admin;
} else {
	// Admin settings are optional — show an admin notice but don't fatal.
	add_action( 'admin_notices', function() use ( $konfidoo_admin ) {
		echo '<div class="notice notice-warning"><p>';
		echo sprintf( esc_html__( 'Konfidoo Forms could not load optional admin settings file: %s', 'konfidoo' ), '<code>' . esc_html( $konfidoo_admin ) . '</code>' );
		echo '</p></div>';
	} );
}
