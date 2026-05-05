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

/** Default URL for the Konfidoo front-end script. */
define( 'KONFIDOO_DEFAULT_SCRIPT_URL', 'https://konfidoo.de/elements/v01/main.js' );

/**
 * Initialize konfidoo settings
 */
function konfidoo_settings_init() {
	// Register settings
	register_setting( 'konfidoo_settings', 'kfd_project_id' );
	register_setting( 'konfidoo_settings', 'kfd_script_url' );
	register_setting( 'konfidoo_settings', 'kfd_script_loading' );

	// Add settings section
	add_settings_section(
		'konfidoo_settings_section',
		__( 'Konfidoo Forms Configuration', 'konfidoo' ),
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

	// Add script URL field
	add_settings_field(
		'kfd_script_url',
		__( 'Script URL', 'konfidoo' ),
		'konfidoo_script_url_render',
		'konfidoo_settings',
		'konfidoo_settings_section'
	);

	// Add script loading method field
	add_settings_field(
		'kfd_script_loading',
		__( 'Script-Lademethode', 'konfidoo' ),
		'konfidoo_script_loading_render',
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
	$project_id_esc = esc_attr( $project_id );

	// Build project URL only when a project ID exists
	$project_url = '';
	if ( ! empty( $project_id ) ) {
		$project_url = 'https://cms.konfidoo.de/project/' . rawurlencode( $project_id ) . '/forms';
	}
	?>
	<input type="text" name="kfd_project_id" value="<?php echo $project_id_esc; ?>" class="regular-text" />

	<?php if ( $project_url ) : ?>
		<p class="description">
			<?php /* translators: %s: project URL */
			printf( __( 'Project link: %s', 'konfidoo' ), '<a href="' . esc_url( $project_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $project_url ) . '</a>' ); ?>
		</p>
	<?php endif; ?>

	<p class="description">
		<?php _e( 'Enter the global Project ID that will be used as a fallback when no block-specific Project ID is set.', 'konfidoo' ); ?>
	</p>
	<?php
}

/**
 * Render Script URL field
 */
function konfidoo_script_url_render() {
	$script_url = get_option( 'kfd_script_url', '' );
	$placeholder = esc_attr( KONFIDOO_DEFAULT_SCRIPT_URL );
	$script_url_esc = esc_attr( $script_url );
	?>
	<input type="url" name="kfd_script_url" value="<?php echo $script_url_esc; ?>" class="large-text" placeholder="<?php echo $placeholder; ?>" />
	<p class="description">
		<?php printf(
			/* translators: %s: default script URL */
			__( 'URL of the Konfidoo front-end script. Leave empty to use the default: %s', 'konfidoo' ),
			'<code>' . esc_html( KONFIDOO_DEFAULT_SCRIPT_URL ) . '</code>'
		); ?>
	</p>
	<?php
}

/**
 * Render Script Loading Method field with a warning for synchronous loading.
 */
function konfidoo_script_loading_render() {
	$value = get_option( 'kfd_script_loading', 'defer' );
	?>
	<select name="kfd_script_loading" id="kfd_script_loading">
		<option value="defer"  <?php selected( $value, 'defer' );  ?>><?php _e( 'defer (empfohlen)', 'konfidoo' ); ?></option>
		<option value="async"  <?php selected( $value, 'async' );  ?>><?php _e( 'async', 'konfidoo' ); ?></option>
		<option value="sync"   <?php selected( $value, 'sync' );   ?>><?php _e( 'synchron', 'konfidoo' ); ?></option>
	</select>

	<ul class="description" style="margin-top:.5em;list-style:disc;padding-left:1.5em;">
		<li><strong>defer</strong> – <?php _e( 'Script wird parallel zum HTML heruntergeladen und erst nach dem vollständigen Parsen der Seite ausgeführt. Kein Einfluss auf die Ladezeit, Reihenfolge bleibt erhalten. <em>Empfohlen.</em>', 'konfidoo' ); ?></li>
		<li><strong>async</strong> – <?php _e( 'Script wird parallel heruntergeladen und sofort nach dem Download ausgeführt – unabhängig vom Parsing-Fortschritt. Geeignet, wenn das Script keine anderen Scripts oder DOM-Elemente benötigt.', 'konfidoo' ); ?></li>
		<li><strong><?php _e( 'synchron', 'konfidoo' ); ?></strong> – <?php _e( 'Script wird sofort heruntergeladen und ausgeführt; das Parsen der Seite pausiert dabei. Verlangsamt den Seitenaufbau und sollte nur verwendet werden, wenn es technisch zwingend erforderlich ist.', 'konfidoo' ); ?></li>
	</ul>
	<p id="kfd-sync-warning" class="description" style="color:#d63638;margin-top:.4em;<?php echo $value !== 'sync' ? 'display:none;' : ''; ?>">
		<?php _e( 'Warnung: Synchrones Laden blockiert den Seitenaufbau und kann die Ladezeit deutlich verlängern. Nur verwenden, wenn es technisch erforderlich ist.', 'konfidoo' ); ?>
	</p>

	<script>
		(function () {
			var sel = document.getElementById('kfd_script_loading');
			var warn = document.getElementById('kfd-sync-warning');
			if (!sel || !warn) return;
			sel.addEventListener('change', function () {
				warn.style.display = sel.value === 'sync' ? '' : 'none';
			});
		}());
	</script>
	<?php
}

/**
 * Settings section callback
 */
function konfidoo_settings_section_callback() {
	$site_url = 'https://konfidoo.de';
	$doc_url  = 'https://konfidoo.de/support/documentation';

	$message = sprintf(
		/* translators: 1: konfidoo.de URL, 2: documentation URL */
		__( 'Note: To use the plugin, you must create a project and at least one form on <a href="%1$s" target="_blank" rel="noopener noreferrer">konfidoo.de</a>. Detailed instructions are available in the <a href="%2$s" target="_blank" rel="noopener noreferrer">documentation</a>.', 'konfidoo' ),
		esc_url( $site_url ),
		esc_url( $doc_url )
	);

	echo '<p>' . wp_kses( $message, array( 'a' => array( 'href' => array(), 'target' => array(), 'rel' => array() ) ) ) . '</p>';
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