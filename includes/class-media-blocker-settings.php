<?php

/**
 * The settings functionality of the plugin.
 *
 * @since      1.0.0
 */
class Media_Blocker_Settings {

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
     * @param    string    $plugin_name    The name of this plugin.
     * @param    string    $version        The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register all settings for the plugin
     *
     * @since    1.0.0
     */
    public function register_settings() {
        // Register a setting for media types to block
        register_setting(
            'media_blocker_settings',      // Option group
            'media_blocker_types',         // Option name
            array(
                'type'              => 'array',
                'sanitize_callback' => array( $this, 'sanitize_media_types' ),
                'default'           => array( 'image', 'video', 'audio' )
            )
        );

        // Register a setting for custom message
        register_setting(
            'media_blocker_settings',      // Option group
            'media_blocker_messages',      // Option name
            array(
                'type'              => 'array',
                'sanitize_callback' => array( $this, 'sanitize_messages' ),
                'default'           => array(
                    'image' => 'Click to view image',
                    'video' => 'Click to view video',
                    'audio' => 'Click to play audio'
                )
            )
        );

        // Add a settings section
        add_settings_section(
            'media_blocker_general_section', 
            __( 'General Settings', 'media-blocker' ),
            array( $this, 'settings_section_callback' ),
            'media-blocker'
        );

        // Add settings fields
        add_settings_field(
            'media_types_to_block',
            __( 'Media Types to Block', 'media-blocker' ),
            array( $this, 'media_types_field_callback' ),
            'media-blocker',
            'media_blocker_general_section'
        );

        add_settings_field(
            'media_block_messages',
            __( 'Block Messages', 'media-blocker' ),
            array( $this, 'messages_field_callback' ),
            'media-blocker',
            'media_blocker_general_section'
        );
    }

    /**
     * Sanitize the media types array
     *
     * @since     1.0.0
     * @param     array    $input    The array to sanitize.
     * @return    array              The sanitized array.
     */
    public function sanitize_media_types( $input ) {
        $valid_types = array( 'image', 'video', 'audio' );
        $sanitized = array();
        
        if ( is_array( $input ) ) {
            foreach ( $input as $type ) {
                if ( in_array( $type, $valid_types ) ) {
                    $sanitized[] = $type;
                }
            }
        }
        
        return $sanitized;
    }

    /**
     * Sanitize the messages array
     *
     * @since     1.0.0
     * @param     array    $input    The array to sanitize.
     * @return    array              The sanitized array.
     */
    public function sanitize_messages( $input ) {
        $sanitized = array();
        
        if ( is_array( $input ) ) {
            foreach ( $input as $key => $message ) {
                if ( in_array( $key, array( 'image', 'video', 'audio' ) ) ) {
                    $sanitized[$key] = sanitize_text_field( $message );
                }
            }
        }
        
        return $sanitized;
    }

    /**
     * Render the settings section description
     *
     * @since    1.0.0
     */
    public function settings_section_callback() {
        echo '<p>' . esc_html__( 'Configure which media types should be blocked and the messages displayed.', 'media-blocker' ) . '</p>';
    }

    /**
     * Render the media types field
     *
     * @since    1.0.0
     */
    public function media_types_field_callback() {
        $options = get_option( 'media_blocker_types', array( 'image', 'video', 'audio' ) );
        
        $media_types = array(
            'image' => __( 'Images', 'media-blocker' ),
            'video' => __( 'Videos', 'media-blocker' ),
            'audio' => __( 'Audio', 'media-blocker' )
        );
        
        foreach ( $media_types as $value => $label ) {
            ?>
            <label style="display: block; margin-bottom: 10px;">
                <input type="checkbox" name="media_blocker_types[]" 
                       value="<?php echo esc_attr( $value ); ?>" 
                       <?php checked( in_array( $value, $options ) ); ?> />
                <?php echo esc_html( $label ); ?>
            </label>
            <?php
        }
        echo '<p class="description">' . esc_html__( 'Select which media types should be blocked.', 'media-blocker' ) . '</p>';
    }

    /**
     * Render the messages field
     *
     * @since    1.0.0
     */
    public function messages_field_callback() {
        $options = get_option( 'media_blocker_messages', array(
            'image' => 'Click to view image',
            'video' => 'Click to view video',
            'audio' => 'Click to play audio'
        ) );
        
        $media_types = array(
            'image' => __( 'Image Block Message', 'media-blocker' ),
            'video' => __( 'Video Block Message', 'media-blocker' ),
            'audio' => __( 'Audio Block Message', 'media-blocker' )
        );
        
        foreach ( $media_types as $key => $label ) {
            $value = isset( $options[$key] ) ? $options[$key] : '';
            ?>
            <div style="margin-bottom: 15px;">
                <label for="media_blocker_messages_<?php echo esc_attr( $key ); ?>">
                    <?php echo esc_html( $label ); ?>
                </label>
                <input type="text" 
                       id="media_blocker_messages_<?php echo esc_attr( $key ); ?>" 
                       name="media_blocker_messages[<?php echo esc_attr( $key ); ?>]" 
                       value="<?php echo esc_attr( $value ); ?>" 
                       class="regular-text" />
            </div>
            <?php
        }
        echo '<p class="description">' . esc_html__( 'Customize the messages shown on blocked media.', 'media-blocker' ) . '</p>';
    }
}