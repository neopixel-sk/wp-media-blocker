<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since      1.0.0
 */
class Media_Blocker {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Media_Blocker_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if ( defined( 'MEDIA_BLOCKER_VERSION' ) ) {
            $this->version = MEDIA_BLOCKER_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'media-blocker';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Media_Blocker_Loader. Orchestrates the hooks of the plugin.
     * - Media_Blocker_i18n. Defines internationalization functionality.
     * - Media_Blocker_Admin. Defines all hooks for the admin area.
     * - Media_Blocker_Public. Defines all hooks for the public side of the site.
     * - Media_Blocker_Settings. Defines settings functionality.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once MEDIA_BLOCKER_DIR . 'includes/class-media-blocker-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once MEDIA_BLOCKER_DIR . 'includes/class-media-blocker-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once MEDIA_BLOCKER_DIR . 'admin/class-media-blocker-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once MEDIA_BLOCKER_DIR . 'public/class-media-blocker-public.php';

        /**
         * The class responsible for defining settings functionality
         * of the plugin.
         */
        require_once MEDIA_BLOCKER_DIR . 'includes/class-media-blocker-settings.php';

        $this->loader = new Media_Blocker_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Media_Blocker_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Media_Blocker_i18n();

        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Media_Blocker_Admin( $this->get_plugin_name(), $this->get_version() );
        $plugin_settings = new Media_Blocker_Settings( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        
        // Add admin menu page
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_page' );
        
        // Register settings
        $this->loader->add_action( 'admin_init', $plugin_settings, 'register_settings' );
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new Media_Blocker_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        
        // Add custom filter to block media content
        $this->loader->add_filter( 'the_content', $plugin_public, 'block_media_content' );
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Media_Blocker_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}