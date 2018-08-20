<?php

class CCD_Schema_Generator_Article extends CCD_Schema_Generator_Public {

    /**
	 * The array of arguments needed to be included in the schema
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private $arguments;

    /**
	 * The main array of data splitted on a few arrays according to the list of arguments
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private $data;
    
    /**
	 * The main type of schema to be set along with context
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private $type;

    /**
	 * Current post's ID
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private $post_id;

    /**
	 * The final string to output on the page
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private $schema;

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

			$logo_type 		= "ImageObject";
			$logo_url		= get_field('schema_brand_logo', 'options')['url'];
			$logo_width		= get_field('schema_brand_logo', 'options')['width'];
			$logo_height	= ssm_get_field('schema_brand_logo', 'options')['height'];

			$template['publisher']['logo'] = array(
				"@type" 	=> $logo_type,
				"url"		=> $logo_url,
				"width"		=> $logo_width,
				"height"	=> $logo_height
			);

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

		$link = get_the_permalink( $this->post_id );
		$title = get_the_title( $this->post_id );
		$date_published = get_the_date( "Y-m-d\TG:i:s\.000\Z", $this->post_id );
		$date_modified = get_the_modified_date( "Y-m-d\TG:i:s\.000\Z", $this->post_id );


		$template['mainEntityOfPage']	= $link ? $link : '';
		$template['headline']			= $title ? $title : '';
		$template['datePublished']		= $date_published ? $date_published : '';
		$template['dateModified']		= $date_modified ? $date_modified : '';

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

		if ($main_author) {

			$template['author'] = [];

			$type 	= "Person";
			$name	= $main_author['first_name'][0] . " " . $main_author['last_name'][0];
			$url 	= get_author_posts_url( get_post_field ('post_author', $this->post_id ) );

			$template['author'][] = array(
				"@type" => $type,
				"name"	=> $name,
				"url" 	=> $url
			);
				
		}

		$additional_author_id = get_post_meta( $this->post_id, 'additional_author', true );

		if ( $additional_author_id ) {

			$type 	= "Person";
			$name	= get_post_field ('post_title', $additional_author_id );
			$url 	= get_post_permalink( $additional_author_id );

			$template['author'][] = array(
				"@type" => $type,
				"name"	=> $name,
				"url" 	=> $url
			);

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

		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $this->post_id ), 'full' );

		if ( $image ) {

				$image_type 	= "ImageObject";
				$image_url		= $image[0];
				$image_width	= $image[1];
				$image_height	= $image[2];
	
				$template['image'] = array(
					"@type" 	=> $image_type,
					"url"		=> $image_url,
					"width" 	=> $image_width,
					"height"	=> $image_height
				);

		}

		return $template;

	}

	/**
	 * Get post sponsor
	 *	
	 * @since    1.0.0
	 */
	public function get_post_sponsor() {

		$template = "";

		$sponsor_id = get_post_meta( $this->post_id, 'post_sponsorship_sponsor', true );

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
	 * Get an initial context, Receives an array of arguments and return a genaeral schema
	 *	
	 * @since    1.0.0
	 */
	public function build() {

        $this->data = $this->get_initial_context( $this->type );

        foreach ( $this->arguments as $argument ) {
            
            $argument = call_user_func( array( $this, "get_{$argument}" ) );
            $this->data = array_merge( $this->data, $argument );
        
        }

		$body = wp_json_encode( $this->data );

		$this->schema = "
			<script type = \"application/ld+json\" >
				{$body}
			</script>
        ";
        
    }

    public function output() {
        echo $this->schema;
    }

}