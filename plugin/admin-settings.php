<?php
/**
 * konfidoo Admin Settings
 *
 * WordPress admin settings page for configuring global Project ID.
 *
 * @since   1.0.0
 * @package konfidoo
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add konfidoo settings page to admin menu
 */
function konfidoo_add_admin_menu() {
	add_options_page(
		'konfidoo Settings',     // Page title
		'konfidoo',              // Menu title
		'manage_options',        // Capability
		'konfidoo-settings',     // Menu slug
		'konfidoo_settings_page' // Callback function
	);
}
add_action( 'admin_menu', 'konfidoo_add_admin_menu' );

/**
 * Initialize konfidoo settings
 */
function konfidoo_settings_init() {
	// Register setting
	register_setting( 'konfidoo_settings', 'kfd_project_id' );

	// Add settings section
	add_settings_section(
		'konfidoo_settings_section',
		__( 'Global Project Configuration', 'konfidoo' ),
		'konfidoo_settings_section_callback',
		'konfidoo_settings'
	);

	// Add project ID field
	add_settings_field(
		'kfd_project_id',
		__( 'Project ID', 'konfidoo' ),
		'konfidoo_project_id_render',
		'konfidoo_settings',
		'konfidoo_settings_section'
	);
}
add_action( 'admin_init', 'konfidoo_settings_init' );

/**
 * Render Project ID field
 */
function konfidoo_project_id_render() {
	$project_id = get_option( 'kfd_project_id', '' );
	?>
	<input type="text" name="kfd_project_id" value="<?php echo esc_attr( $project_id ); ?>" class="regular-text" />
	<p class="description">
		<?php _e( 'Enter the global Project ID that will be used as a fallback when no block-specific Project ID is set.', 'konfidoo' ); ?>
	</p>
	<?php
}

/**
 * Settings section callback
 */
function konfidoo_settings_section_callback() {
	echo '<p>' . __( 'Configure the global Project ID for konfidoo forms integration. This Project ID will be used as a fallback when individual blocks do not have a specific Project ID configured.', 'konfidoo' ) . '</p>';
}

/**
 * Render settings page
 */
function konfidoo_settings_page() {
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'konfidoo_settings' );
			do_settings_sections( 'konfidoo_settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Add settings link to plugins page
 */
function konfidoo_add_settings_link( $links ) {
	$settings_link = '<a href="' . admin_url( 'options-general.php?page=konfidoo-settings' ) . '">' . __( 'Settings', 'konfidoo' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_konfidoo/plugin.php', 'konfidoo_add_settings_link' );