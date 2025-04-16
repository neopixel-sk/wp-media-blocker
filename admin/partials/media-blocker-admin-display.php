<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 */
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form method="post" action="options.php">
        <?php
        // Output security fields for the registered setting
        settings_fields( 'media_blocker_settings' );
        
        // Output setting sections and their fields
        do_settings_sections( 'media-blocker' );
        
        // Output save settings button
        submit_button( __( 'Save Settings', 'media-blocker' ) );
        ?>
    </form>
</div>