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

//Setup ShortCode
function get_hubspot_posts( $atts ) {
	
	// Get JSON data from HubSpot API
    $hubspot_key = $atts['key'];
    $fallback_img_url = $atts['fallback-image'];

    // Takes raw data from the request
    if ( $hubspot_key === NULL ) { return __('Unable to access HubSpot. Please use a valid HubSpot API key.'); }
    if( $content === FALSE ) { return __('Unable to access HubSpot with the provided API key.'); }
	$json = @file_get_contents('https://api.hubapi.com/content/api/v2/blog-posts?hapikey=' . $hubspot_key);
	
	// Converts it into a PHP object
	$data = json_decode($json);

	// The Loop
	if ( $data === NULL ) { return __('There was no HubSpot data to retreive.'); }
	
		$html = '<div id="hsbp" class="hsbp_wrapper">';
		foreach( $data->objects as $hubspot_post ) :
	         
	        // HubSpot Post Variables
	        $title = $hubspot_post->html_title;
	        $publish_date = date('d F Y', $hubspot_post->created);
	        $excerpt = $hubspot_post->meta_description;
	        $url = esc_url($hubspot_post->url);
	        $featured_image = $hubspot_post->featured_image;
	        if ( $featured_image == '' ) {
	        	$featured_image = $fallback_img_url;
	        }
	        
	        // HTML
	        $html .= '<article class="hsbp_post post">';
	        $html .=	'<div class="hsbp_image" style="background-image(' . $featured_image . ')"></div>';
	        $html .=	'<div class="hspb_text">';
	        $html .=		'<h6 class="hsbp_meta">' . $publish_date . '</h6>';
	        $html .=		'<a href="' . $url . '" title="' . $title . '">';
	        $html .=			'<h5 class="hsbp_title">' . $title . '</h5>';
	        $html .=		'</a>';
	        $html .=		'<div class="hsbp_excerpt">' . esc_html( $excerpt ) . '</div>';
	        $html .=		'<a href="' . $url . '" title="' . $title . '">';
	        $html .=			'<div class="hsbp_read-more-btn">></div>';
	        $html .=		'</a>';
	        $html .=	'</div>';
	        $html .= '</article>';
	
	    endforeach;
	    $html .= '</div>';
    
    // Return HTML
    return $html;
    
}
add_shortcode( 'hubspot-posts', 'get_hubspot_posts' );