<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @since      1.0.0
 */
class Media_Blocker_Public {

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
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/media-blocker-public.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/media-blocker-public.js', array( 'jquery' ), $this->version, false );

    }

    /**
     * Block media content based on plugin settings
     *
     * @since    1.0.0
     * @param    string    $content    The content of the post.
     * @return   string                The filtered content.
     */
    public function block_media_content( $content ) {
        // If not in main loop or not a singular post, return content as is
        if ( !is_singular() || !in_the_loop() ) {
            return $content;
        }
        
        // Get blocked media types from settings
        $blocked_types = get_option( 'media_blocker_types', array('image', 'video', 'audio') );
        
        // Process the content based on blocked media types
        if ( in_array( 'image', $blocked_types ) ) {
            $content = $this->block_images( $content );
        }
        
        if ( in_array( 'video', $blocked_types ) ) {
            $content = $this->block_videos( $content );
        }
        
        if ( in_array( 'audio', $blocked_types ) ) {
            $content = $this->block_audio( $content );
        }
        
        return $content;
    }
    
    /**
     * Block images in content
     *
     * @since    1.0.0
     * @param    string    $content    The content to filter.
     * @return   string                The filtered content.
     */
    private function block_images( $content ) {
        // Simple regex to find image tags
        $pattern = '/<img[^>]+>/i';
        
        // Get custom message
        $messages = get_option( 'media_blocker_messages', array(
            'image' => 'Click to view image',
            'video' => 'Click to view video',
            'audio' => 'Click to play audio'
        ));
        $message = isset( $messages['image'] ) ? $messages['image'] : 'Click to view image';
        
        return preg_replace_callback( $pattern, function( $matches ) use ( $message ) {
            $image = $matches[0];
            
            // Create container with overlay
            $blocked_content = sprintf(
                '<div class="media-blocker-container">%s<div class="media-blocker-overlay"><p>%s</p></div></div>',
                $image,
                esc_html__( $message, 'media-blocker' )
            );
            
            return $blocked_content;
        }, $content );
    }
    
    /**
     * Block videos in content
     *
     * @since    1.0.0
     * @param    string    $content    The content to filter.
     * @return   string                The filtered content.
     */
    private function block_videos( $content ) {
        // Match video tags and common video embeds
        $patterns = array(
            '/<video[^>]*>.*?<\/video>/is',
            '/<iframe[^>]*(?:youtube|vimeo|videopress)[^>]*>.*?<\/iframe>/is',
        );
        
        // Get custom message
        $messages = get_option( 'media_blocker_messages', array(
            'image' => 'Click to view image',
            'video' => 'Click to view video',
            'audio' => 'Click to play audio'
        ));
        $message = isset( $messages['video'] ) ? $messages['video'] : 'Click to view video';
        
        foreach ( $patterns as $pattern ) {
            $content = preg_replace_callback( $pattern, function( $matches ) use ( $message ) {
                $video = $matches[0];
                
                // Create container with overlay
                $blocked_content = sprintf(
                    '<div class="media-blocker-container">%s<div class="media-blocker-overlay"><p>%s</p></div></div>',
                    $video,
                    esc_html__( $message, 'media-blocker' )
                );
                
                return $blocked_content;
            }, $content );
        }
        
        return $content;
    }
    
    /**
     * Block audio in content
     *
     * @since    1.0.0
     * @param    string    $content    The content to filter.
     * @return   string                The filtered content.
     */
    private function block_audio( $content ) {
        // Match audio tags
        $pattern = '/<audio[^>]*>.*?<\/audio>/is';
        
        // Get custom message
        $messages = get_option( 'media_blocker_messages', array(
            'image' => 'Click to view image',
            'video' => 'Click to view video',
            'audio' => 'Click to play audio'
        ));
        $message = isset( $messages['audio'] ) ? $messages['audio'] : 'Click to play audio';
        
        return preg_replace_callback( $pattern, function( $matches ) use ( $message ) {
            $audio = $matches[0];
            
            // Create container with overlay
            $blocked_content = sprintf(
                '<div class="media-blocker-container">%s<div class="media-blocker-overlay"><p>%s</p></div></div>',
                $audio,
                esc_html__( $message, 'media-blocker' )
            );
            
            return $blocked_content;
        }, $content );
    }

}