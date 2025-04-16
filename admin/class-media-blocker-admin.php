<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 */
class Media_Blocker_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/media-blocker-admin.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/media-blocker-admin.js', array( 'jquery' ), $this->version, false );

    }

    /**
     * Add settings page to the admin menu
     * 
     * @since    1.0.0
     */
    public function add_menu_page() {
        add_options_page(
            __( 'Media Blocker Settings', 'media-blocker' ),
            __( 'Media Blocker', 'media-blocker' ),
            'manage_options',
            'media-blocker',
            array( $this, 'display_settings_page' )
        );
    }

    /**
     * Display the settings page content
     * 
     * @since    1.0.0
     */
    public function display_settings_page() {
        include_once 'partials/media-blocker-admin-display.php';
    }

}