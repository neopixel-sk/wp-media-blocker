<?php
/**
 * Plugin Name: Media Blocker
 * Plugin URI: https://wordpress.org/plugins/media-blocker/
 * Description: A WordPress plugin for blocking media content.
 * Version: 1.0.0
 * Author: NeoPixel Agency
 * Author URI: https://neopixel.agency
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: media-blocker
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Current plugin version.
 */
define( 'MEDIA_BLOCKER_VERSION', '1.0.0' );

/**
 * Plugin base file path.
 */
define( 'MEDIA_BLOCKER_FILE', __FILE__ );

/**
 * Plugin directory path.
 */
define( 'MEDIA_BLOCKER_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL.
 */
define( 'MEDIA_BLOCKER_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load the required files for the plugin.
 */
require_once MEDIA_BLOCKER_DIR . 'includes/class-media-blocker.php';

/**
 * Begins execution of the plugin.
 */
function run_media_blocker() {
    $plugin = new Media_Blocker();
    $plugin->run();
}

// Start the plugin
run_media_blocker();