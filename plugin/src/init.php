<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function konfidoo_cgb_block_assets() { // phpcs:ignore
	// Path to the generated asset file which contains dependencies and version.
	$asset_file = include plugin_dir_path( __FILE__ ) . 'build/index.asset.php';

	// Register block styles for both frontend + backend.
	wp_register_style(
		'konfidoo-cgb-style-css', // Handle.
		plugins_url( 'build/style-index.css', dirname( __FILE__ ) ), // Block style CSS (frontend + backend).
		is_admin() ? array( 'wp-editor' ) : null, // Dependency to include the CSS after it.
		isset( $asset_file['version'] ) ? $asset_file['version'] : null
	);

	// Register block editor script for backend. Use asset file for deps & version.
	wp_register_script(
		'konfidoo-cgb-block-js', // Handle.
		plugins_url( 'build/index.js', dirname( __FILE__ ) ), // Built block JS.
		isset( $asset_file['dependencies'] ) ? $asset_file['dependencies'] : array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies.
		isset( $asset_file['version'] ) ? $asset_file['version'] : null, // Version: from asset file.
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
		'konfidoo-cgb-block-editor-css', // Handle.
		plugins_url( 'build/index.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		isset( $asset_file['version'] ) ? $asset_file['version'] : null
	);

	// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
	wp_localize_script(
		'konfidoo-cgb-block-js',
		'cgbGlobal', // Array containing dynamic data for a JS Global.
		array(
			'pluginDirPath' => plugin_dir_path( __DIR__ ),
			'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
			'globalProjectId' => get_option( 'kfd_project_id', '' ),
			// Add more data here that you want to access from `cgbGlobal` object.
		)
	);

	// Register the block on server-side to ensure that the block
	// scripts and styles for both frontend and backend are
	// enqueued when the editor loads.
	register_block_type(
		'cgb/block-konfidoo', array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'konfidoo-cgb-style-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'konfidoo-cgb-block-js',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'konfidoo-cgb-block-editor-css',
		)
	);
}

// Hook: Block assets.
add_action( 'init', 'konfidoo_cgb_block_assets' );
