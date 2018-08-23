<?php

class CCD_Schema_Generator_Article extends CCD_Schema_Generator_Public {

    /**
	 * The array of arguments needed to be included in the schema
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    protected $arguments;

    /**
	 * The main array of data splitted on a few arrays according to the list of arguments
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    protected $data;
    
    /**
	 * The main type of schema to be set along with context
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    protected $type;

    /**
	 * Current post's ID
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    protected $post_id;

    /**
	 * The final string to output on the page
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	protected $schema;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since   1.0.0
     * @access  public
	 */
	public function __construct( $arguments, $type, $post_id ) {

        $this->arguments = $arguments;
        $this->type = $type;
        $this->post_id = $post_id;
        $this->data = [];
        $this->schema = '';   
    
    }

	/**
	 * Get post publisher
	 *	
	 * @since    1.0.0
	 */
	public function get_post_publisher() {

		$template = [];
		$template['publisher'] = [];

		$type 			= "Organization";
		$name			= get_bloginfo('name');
		$url			= get_bloginfo('url');
		$phone_number 	= get_field( 'ccd_default_phone_number', 'options');
		$email			= get_field( 'ccd_default_email', 'options');

		if ( $name ) {

			$template['publisher'] = array(
				"@type" => $type,
				"name"	=> $name
			);

		}

		if ( get_field( 'schema_brand_logo', 'options' ) ) {

			$type 	= "ImageObject";
			$url	= get_field('schema_brand_logo', 'options')['url'];
			$width	= get_field('schema_brand_logo', 'options')['width'];
			$height	= ssm_get_field('schema_brand_logo', 'options')['height'];

			$template['publisher']['logo']['@type'] = $type;

			$arguments = ['url', 'width', 'height'];

			foreach ( $arguments as $argument ) {
				if ( $$argument ) {
					$template['publisher']['logo'][$argument] = $$argument;
				}
			}

		}

		return $template;

	}

	/**
	 * Get post main information, such as: headline, publication & modification dates,
	 * post permalink, word count, description and body
	 *	
	 * @since    1.0.0
	 */
	public function get_post_info() {

		$template = [];

		$mainEntityOfPage = get_the_permalink( $this->post_id );
		$headline = get_the_title( $this->post_id );
		$datePublished = get_the_date( "Y-m-d\TG:i:s\.000\Z", $this->post_id );
		$dateModified = get_the_modified_date( "Y-m-d\TG:i:s\.000\Z", $this->post_id );

		$arguments = ['mainEntityOfPage', 'headline', 'datePublished', 'dateModified' ];

		foreach ( $arguments as $argument ) {
			if ( $$argument ) {
				$template[$argument] = $$argument;
			}
		}

		return $template;

	}

	/**
	 * Get post author
	 *	
	 * @since    1.0.0
	 */
	public function get_post_author() {

		$template = [];

		$main_author = get_user_meta( get_post_field ('post_author', $this->post_id ) );		
		
		if ( $main_author ) {

			$template['author'] = [];
			$type 	= "Person";
			$name	= $main_author['first_name'][0] . " " . $main_author['last_name'][0];
			$url 	= get_author_posts_url( get_post_field ('post_author', $this->post_id ) );
			
			if ( $name && $url ) {
				$template['author'][] = array(
					"@type" => $type,
					"name"	=> $name,
					"url" 	=> $url
				);
			}
		}

		$additional_author_id = get_post_meta( $this->post_id, 'additional_author', true );

		if ( $additional_author_id ) {

			$type 	= "Person";
			$name	= get_post_field ('post_title', $additional_author_id );
			$url 	= get_post_permalink( $additional_author_id );

			if ( $name && $url ) {

				$template['author'][] = array(
					"@type" => $type,
					"name"	=> $name,
					"url" 	=> $url
				);
				
			}
		}
		
		return $template;
	
	}

	/**
	 * Get post image
	 *	
	 * @since    1.0.0
	 */
	public function get_post_image() {

		$template = [];
		$template['image'] = [];
		$image = array();

		if ( function_exists('get_schema_image_array') ) {
			$image = get_schema_image_array( $this->post_id );
		} else {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $this->post_id ), 'full' );
		}

		if ( !empty( $image ) ) {

			$type 	= "ImageObject";
			$url	= $image['url'];
			$width	= $image['width'];
			$height	= $image['height'];

			$template['image']['@type'] = $type;

			$arguments = ['url', 'width', 'height'];

			foreach ( $arguments as $argument ) {
				if ( $$argument ) {
					$template['image'][$argument] = $$argument;
				}
			}

		}

		return $template;

	}

}