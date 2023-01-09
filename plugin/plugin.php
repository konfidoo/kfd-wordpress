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
	<script src="https://dev.konfidoo.de/elements/loader.js"></script>
	<?php
}


/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
