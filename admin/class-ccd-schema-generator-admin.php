<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.secretstache.com/
 * @since      1.0.0
 *
 * @package    CCD_Schema_Generator
 * @subpackage CCD_Schema_Generator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CCD_Schema_Generator
 * @subpackage CCD_Schema_Generator/admin
 * @author     Secret Stache Media <alex@secretstache.com>
 */
class CCD_Schema_Generator_Admin {

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

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in CCD_Schema_Generator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The CCD_Schema_Generator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ccd-schema-generator-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in CCD_Schema_Generator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The CCD_Schema_Generator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ccd-schema-generator-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Get initial schema context, such as: @context, @type 
	 *	
	 * @since    1.0.0
	 */
	public function get_initial_context() {

		$template = '';

		$context	= "http://schema.org";
		$type		= "Organization";

		if ( $context && $type ) {
		
			$template .= "
					\"@context\": {$context},
					\"@type\": {$type},
			";
		}

		return $template;

	}

	/**
	 * Get common informaton about site, such as: name, url and logo
	 *	
	 * @since    1.0.0
	 */
	public function get_common_info() {

		$template = '';
		
		$name	= get_bloginfo('name');
		$url	= get_bloginfo('url');

		if ( $name && $url ) {

			$template .= "
					\"name\": {$name},
					\"url\": {$url},
			";

		}

		if ( ssm_get_field( 'brand_logo', 'options' ) ) {

			$logo_type 	= "ImageObject";
			$logo_url	= ssm_get_field('brand_logo', 'options')['url'];

			$template .= "
					\"logo\":
					{
						\"@type\": {$logo_type},
						\"url\": {$logo_url}
					},
			";
		}

		return $template;

	}

	/**
	 * Get social network links
	 *	
	 * @since    1.0.0
	 */
	public function get_social_media() {

		$social_links = array();
		$requested_networks = array( 'facebook', 'linkedin', 'pinterest');

		foreach ( $requested_networks as $network ) {

			if ( ssm_get_field( $network, 'options' ) ) {
				array_push( $social_links, ssm_get_field( $network, 'options' ) );
			}
			
		}

		if ( !empty( $social_links ) ) {
			$template .= " 
					\"sameAs\": [";
	
				foreach ( $social_links as $link ) {
					$template .= "
						{$link},
					";
				}

			$template .= "
					]";
		
		}
		
		return $template;

	}

	/**
	 * Main function
	 *	
	 * @since    1.0.0
	 */
	public function call_registration() {

		$args = array();

		$initial_context 	= $this->get_initial_context();
		$common_info 		= $this->get_common_info();
		$social_media		= $this->get_social_media();

		$args['initial_context']	= $initial_context;	
		$args['common_info'] 		= $common_info;
		$args['social_media']		= $social_media;

		do_action( 'custom_schema_hook', $args );
	}

	/**
	 * The function receives an array of arguments and return a schema
	 *	
	 * @since    1.0.0
	 */
	public function create_schema( $args ) {
		$template = "
			<script type = \"application/ld+json\" >
				{
					\t{$args['initial_context']}
					{$args['common_info']}
					{$args['social_media']}
				} 
			</script>
		";

		die(var_dump($template));

	}

}