<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @since      1.0.0
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Delete plugin options
delete_option( 'media_blocker_types' );
delete_option( 'media_blocker_messages' );

// For site options in Multisite
delete_site_option( 'media_blocker_types' );
delete_site_option( 'media_blocker_messages' );