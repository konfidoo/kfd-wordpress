<?php
/**
 * Plugin Name: konfidoo
 * Plugin URI: https://konfidoo.de/plugins/kfd-forms/
 * Description: Integration of konfidoo Forms
 * Author: Nico Blum
 * Author URI:        https://konfidoo.de
 * Version: 1.0.2
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt

 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action( 'wp_head', 'my_header_scripts' );
function my_header_scripts(){
	?>
	<script src="https://konfidoo.de/elements/v01/main.js"></script>
	<?php
}


/**
 * Add admin menu for konfidoo settings.
 */
add_action( 'admin_menu', 'konfidoo_admin_menu' );
function konfidoo_admin_menu() {
	add_options_page(
		'konfidoo Settings',
		'konfidoo',
		'manage_options',
		'konfidoo-settings',
		'konfidoo_settings_page'
	);
}

/**
 * Register settings.
 */
add_action( 'admin_init', 'konfidoo_admin_init' );
function konfidoo_admin_init() {
	register_setting( 'konfidoo_settings', 'konfidoo_global_project_id' );
}

/**
 * Settings page callback.
 */
function konfidoo_settings_page() {
	if ( isset( $_POST['submit'] ) ) {
		update_option( 'konfidoo_global_project_id', sanitize_text_field( $_POST['konfidoo_global_project_id'] ) );
		echo '<div class="notice notice-success is-dismissible"><p>Settings saved!</p></div>';
	}
	
	$global_project_id = get_option( 'konfidoo_global_project_id', '' );
	?>
	<div class="wrap">
		<h1>konfidoo Settings</h1>
		<form method="post" action="">
			<table class="form-table">
				<tr>
					<th scope="row">Global Project ID</th>
					<td>
						<input type="text" name="konfidoo_global_project_id" value="<?php echo esc_attr( $global_project_id ); ?>" class="regular-text" />
						<p class="description">This Project ID will be used as a fallback when blocks don't have a specific Project ID set.</p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
