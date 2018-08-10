<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.secretstache.com/
 * @since      1.0.0
 *
 * @package    CCD_Schema_Generator
 * @subpackage CCD_Schema_Generator/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    CCD_Schema_Generator
 * @subpackage CCD_Schema_Generator/public
 * @author     Secret Stache Media <alex@secretstache.com>
 */
class CCD_Schema_Generator_Public {

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
		 * defined in CCD_Schema_Generator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The CCD_Schema_Generator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ccd-schema-generator-public.css', array(), $this->version, 'all' );

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
		 * defined in CCD_Schema_Generator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The CCD_Schema_Generator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ccd-schema-generator-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Get initial schema context, such as: @context, @type 
	 *	
	 * @since    1.0.0
	 */
	public function get_initial_context( $type ) {

		$template = '';

		$context	= "http://schema.org";

		if ( $context && $type ) {
		
			$template .= "
					\"@context\": \"{$context}\",
					\"@type\": \"{$type}\",
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
					\"name\": \"{$name}\",
					\"url\": \"{$url}\",
			";

		}

		if ( ssm_get_field( 'brand_logo', 'options' ) ) {

			$logo_type 	= "ImageObject";
			$logo_url	= ssm_get_field('brand_logo', 'options')['url'];

			$template .= "
					\"logo\":
					{
						\"@type\": \"{$logo_type}\",
						\"url\": \"{$logo_url}\"
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
						\"{$link}\",
					";
				}

			$template .= "
					]";
		
		}
		
		return $template;

	}

	/**
	 * Get post main information, such as: headline, publication & modification dates,
	 * post permalink, word count, description and body
	 *	
	 * @since    1.0.0
	 */
	public function get_post_info( $post_id ) {

		$template = "";

		$link = get_the_permalink( $post_id );

		if ( $link ) {

			$template .= "
					\"mainEntityOfPage\": \"{$link}\",
				";
		}

		$title = get_the_title( $post_id );

		if ( $title ) {

			$template .= "
					\"headline\": \"{$title}\",
				";
		}

		$date_published = get_the_date( "Y-m-d\TG:i:s\.000\Z", $post_id );

		if ( $date_published ) {

			$template .= "
					\"datePublished\": \"{$date_published}\",
				";
		}

		$date_modified = get_the_modified_date( "Y-m-d\TG:i:s\.000\Z", $post_id );

		if ( $date_modified ) {

			$template .= "
					\"dateModified\": \"{$date_modified}\",
				";
		}

		// $word_count = str_word_count( get_post_field( 'post_content', $post_id ), 0 );

		// if ( $word_count ) {

		// 	$template .= "
		// 			\"wordCount\": \"{$word_count}\",
		// 		";
		// }

		// $description = get_post_field( 'post_excerpt', $post_id );
		
		// if ( !$description ) {
		// 	$description = explode( ".", get_post_field( 'post_content', $post_id ) )[0];
		// }

		// if ( $description ) {

		// 	$template .= "
		// 			\"description\": \"{$description}\",
		// 		";
		// }

		// $content = get_post_field( 'post_content', $post_id );
		// $content = substr( $content, 0, 200 ) . "..."; //temp

		// if ( $content ) {

		// 	$template .= "
		// 			\"articleBody\": \"{$content}\",
		// 		";
		// }

		return $template;

	}

	/**
	 * Get post image
	 *	
	 * @since    1.0.0
	 */
	public function get_post_image( $post_id ) {

		$template = "";

		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );

		if ( $image ) {

				$image_type 	= "ImageObject";
				$image_url		= $image[0];
				$image_width	= $image[1];
				$image_height	= $image[2];
	
				$template .= "
					\"image\":
					{
						\"@type\": \"{$image_type}\",
						\"url\": \"{$image_url}\",
						\"width\": {$image_width},
						\"height\": {$image_height}
					},
				";

			}

		return $template;

	}

	/**
	 * Get post author
	 *	
	 * @since    1.0.0
	 */
	public function get_post_author( $post_id ) {

		$template = "";

		$main_author = get_user_meta( get_post_field ('post_author', $post_id ) );		

		if ($main_author) {

			$template .= " 
					\"author\": [";

			$type 			= "Person";
			$name	 		= $main_author['first_name'][0] . " " . $main_author['last_name'][0];
			$url 	= get_author_posts_url( get_post_field ('post_author', $post_id ) );

			$template .= "
					{
						\"@type\": \"{$type}\",
						\"name\": \"{$name}\",
						\"url\": \"{$url}\"	
					},
			";
				
		}

		$additional_author_id 	= get_post_meta( $post_id, 'additional_author', true );
		$additional_author 		= get_post_meta( $additional_author_id );

		if ( $additional_author ) {

			$type 			= "Person";
			$name			= get_post_field ('post_title', $additional_author_id );
			$url 			= get_post_permalink( $additional_author_id );

			// $prefix 		= $additional_author['expert_prefix'][0];
			// $suffix 		= $additional_author['expert_suffix'][0];
			// $title 			= $additional_author['expert_job_title'][0];
			// $company 		= $additional_author['expert_company'][0];
			// $description	= get_post_field ('post_content', $additional_author_id );

			$template .= "
					{
						\"@type\": \"{$type}\",
						\"name\": \"{$name}\",
						\"url\": \"{$url}\"
					},			
			";

		}

		// \"honorificPrefix\": \"{$prefix}\",
		// \"honorificSuffix\": \"{$suffix}\",
		// \"jobTitle\": \"{$title}\",
		// \"worksFor\": \"{$company}\",
		// \"description\": \"{$description}\",

		$template .= "
					]";

		return $template;

	}

	/**
	 * Get post publisher
	 *	
	 * @since    1.0.0
	 */
	public function get_post_publisher() {

		$template = "";

		$type 			= "Organization";
		$name			= get_bloginfo('name');
		// $url			= get_bloginfo('url');
		// $phone_number 	= ssm_get_field( 'primary_phone_number', 'options');
		// $email			= ssm_get_field( 'primary_email_address', 'options');

		if ( $name ) {

			$template .= " 
					\"publisher\": 
					{";

			$template .= "
						\"@type\": \"$type\",
						\"name\": \"{$name}\",
			";

			// \"url\": \"{$url}\",
			// \"phone_number\": \"{$phone_number}\",
			// \"email\": \"{$email}\",

		}

		if ( ssm_get_field( 'brand_logo', 'options' ) ) {

			$logo_type 		= "ImageObject";
			$logo_url		= ssm_get_field('brand_logo', 'options')['url'];
			$logo_width		= ssm_get_field('brand_logo', 'options')['sizes']['medium-width'];
			$logo_height	= ssm_get_field('brand_logo', 'options')['sizes']['medium-height'];

			$template .= "
						\"logo\":
						{
							\"@type\": \"{$logo_type}\",
							\"url\": \"{$logo_url}\"
							\"width\": \"{$logo_width}\"
							\"height\": \"{$logo_height}\"
						}
			";
		}

		$template .= " 
					},";

		return $template;

	}

	/**
	 * Get post sponsor
	 *	
	 * @since    1.0.0
	 */
	public function get_post_sponsor( $post_id ) {

		$template = "";

		$sponsor_id = get_post_meta( $post_id, 'post_sponsorship_sponsor', true );

		if ( $sponsor_id ) {

			$type 			= "Organization";
			$name 			= get_post_field ('post_title', $sponsor_id );
			$description	= get_post_field ('post_content', $sponsor_id );
			$url 			= get_post_permalink( $sponsor_id );

			$template .= "
					\"sponsor\":
					{
						\"@type\": \"{$type}\",
						\"name\": \"{$name}\",
						\"description\": \"{$description}\",
						\"url\": \"{$url}\"
					},			
			";
		
		}

		return $template;
	}

	/**
	 * The function receives an array of arguments and return a genaeral schema
	 *	
	 * @since    1.0.0
	 */
	public function create_mainpage_schema( $args ) {
		
		$template = "
			<script type = \"application/ld+json\" >
				{
					{$args['initial_context']}
					{$args['common_info']}
					{$args['social_media']}
				} 
			</script>
		";

	}

	/**
	 * The function receives an array of arguments and return a schema for a post
	 *	
	 * @since    1.0.0
	 */
	public function create_post_schema( $args ) {
		
		$template = "
			<script type = \"application/ld+json\" >
				{
					{$args['initial_context']}
					{$args['post_publisher']}
					{$args['post_info']}
					{$args['post_author']}
					{$args['post_image']}
				} 
			</script>
		";

		// {$args['post_sponsor']}

		return $template;

	}

	/**
	 * The function is get the current post ID, collect required information, pass the
	 * variables through template and output the result in '<head>' section 
	 *	
	 * @since    1.0.0
	 */

	public function show_schema() {

		if ( is_single() ) {

			$post_id = get_the_ID();
			
			$initial_context 	= $this->get_initial_context( 'Article' );
			$post_publisher		= $this->get_post_publisher( $post_id );
			$post_info			= $this->get_post_info( $post_id );
			$post_author		= $this->get_post_author( $post_id );
			$post_image			= $this->get_post_image( $post_id );
			// $post_sponsor		= $this->get_post_sponsor( $post_id );

			$post_args['initial_context']	= $initial_context;
			$post_args['post_publisher']	= $post_publisher;
			$post_args['post_info'] 		= $post_info;
			$post_args['post_author']		= $post_author;
			$post_args['post_image']		= $post_image;
			// $post_args['post_sponsor']		= $post_sponsor;

			echo $this->create_post_schema( $post_args );
		
		}
	}

}
