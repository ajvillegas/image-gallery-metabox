<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.alexisvillegas.com
 * @since             1.0.0
 * @package           Image_Gallery_Metabox
 *
 * @wordpress-plugin
 * Plugin Name:       Image Gallery Metabox
 * Plugin URI:        https://github.com/ajvillegas/image-gallery-metabox
 * Description:       This plugin adds an intuitive image gallery meta box to the page editor screen.
 * Version:           1.1.0
 * Author:            Alexis J. Villegas
 * Author URI:        http://www.alexisvillegas.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       image-gallery-metabox
 * Domain Path:       /languages
 * GitHub Plugin URI: ajvillegas/image-gallery-metabox
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-image-gallery-metabox.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_image_gallery_metabox() {

	$plugin = new Image_Gallery_Metabox();
	$plugin->run();

}

run_image_gallery_metabox();
