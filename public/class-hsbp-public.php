<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       DevHouse.se
 * @since      1.0.0
 *
 * @package    Hsbp
 * @subpackage Hsbp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Hsbp
 * @subpackage Hsbp/public
 * @author     Alex Strand <alex@devhouse.se>
 */
class Hsbp_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
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

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hsbp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hsbp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hsbp-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hsbp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hsbp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hsbp-public.js', array( 'jquery' ), $this->version, false );

	}

}

// CSS Enqueue
function hsbp_scripts() {
    wp_enqueue_style( 'hsbp-styles', '/wp-content/plugins/hsbp/public/css/hsbp-public.css' );
}
add_action( 'wp_enqueue_scripts', 'hsbp_scripts' );

// Setup ShortCode
function get_hubspot_posts( $atts ) {
	
	// Get JSON data from HubSpot API
    $hubspot_key		= $atts['key'];
    $fallback_img_url	= $atts['fallback-image'];
    $language			= $atts['language'];
    
    // Takes raw data from the request
    if ( $hubspot_key === NULL ) { return __('Unable to access HubSpot. Please use a valid HubSpot API key.'); }
    
    // Check for WP Transient
    if ( ( $posts = get_transient( "hubspot_posts" . $hubspot_key ) ) === false) :
    	// GET api file contents from HubSpot
    	$json = file_get_contents('https://api.hubapi.com/content/api/v2/blog-posts?hapikey=' . $hubspot_key);
    	// Convert API JSON it into a PHP object
		$data = json_decode($json);
		// Set new transient
		set_transient( "hubspot_posts" . $hubspot_key, $data, HOUR_IN_SECONDS );
    else :
    	$data = get_transient( "hubspot_posts" . $hubspot_key );
    endif; 

	// The Loop
	if ( $data === NULL ) { return __('There was no HubSpot data to retreive.'); }
	
		$html = '<div id="hsbp" class="hsbp_wrapper">';
		foreach( $data->objects as $hubspot_post ) :
	         
	        // HubSpot Post Variables
	        $filter 		= 'style="display: none;"';
	        $title			= $hubspot_post->html_title;
	        $publish_date	= $hubspot_post->created;
	    	$publish_date	= date( 'd M Y', floor( $publish_date / 1000 ) );
	        $excerpt		= wp_trim_words( $hubspot_post->meta_description, 25, '...' );
	        $url			= esc_url($hubspot_post->url);
	        $featured_image = $hubspot_post->featured_image;
	        $article_lang	= $hubspot_post->language;
	        
	        if ( $featured_image == '' ) {
	        	$featured_image = $fallback_img_url;
	        }
	        
	        if ( $article_lang == $language ) {
	        	$filter = 'style="display: inline-block;"';
	        }
	        
	        // HTML
	        $html .= '<article lang="' . $article_lang . ' ' . $language . '" class="hsbp_post post" ' . $filter . '>';
	        $html .=	'<div class="hsbp_image" style="background-image: url(' . __( $featured_image ) . ')"></div>';
	        $html .=	'<div class="hspb_text">';
	        $html .=		'<h6 class="hsbp_meta line-break">' . __( $publish_date ) . '</h6>';
	        $html .=		'<a href="' . __( $url ) . '" title="' . __( $title ) . '">';
	        $html .=			'<h5 class="hsbp_title">' . __( $title ) . '</h5>';
	        $html .=		'</a>';
	        $html .=		'<p class="hsbp_excerpt">' . __( $excerpt ) . '</p>';
	        $html .=		'<a href="' . __( $url ) . '" title="' . __( $title ) . '">';
	        $html .=			'<div class="blue-post-button arrow">></div>';
	        $html .=		'</a>';
	        $html .=	'</div>';
	        $html .= '</article>';
	
	    endforeach;
	    $html .= '</div>';
    
    // Return html
    return $html;
    
}
add_shortcode( 'hubspot-posts', 'get_hubspot_posts' );