<?php

class CCD_Schema_Generator_ExpertPage extends CCD_Schema_Generator_Public {

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
	 * Get Expert Page Common info
	 *	
	 * @since    1.0.0
	 */
	public function get_expert_common() {

		$template = [];

		$id = $url = $mainEntityofPage = get_post_permalink( $this->post_id );
		$name = get_expert_title( $this->post_id );
		$email = "";
		$description = "Contributor at The Cell Culture Dish.";
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $this->post_id ), 'full' )[0];
		
		if ( $id ) {
			$template['@id'] = $id;
		}

		$arguments = ['url', 'mainEntityofPage', 'name', 'email', 'description', 'image'];

		foreach ( $arguments as $argument ) {
			if ( $$argument ) {
				$template[$argument] = $$argument;
			}
		}

		return $template;

	}

	/**
	 * Get Expert Page social media info
	 *	
	 * @since    1.0.0
	 */
	public function get_expert_media() {
		
		$template = [];
		$template['sameAs'] = [];

		$linkedin = get_field( 'expert_linkedin_profile', $this->post_id );

		if ( $linkedin ) {
			$template['sameAs'] = array( $linkedin );
		}

		return $template;

	}

	/**
	 * Get Expert Page affiliation info
	 *	
	 * @since    1.0.0
	 */
	public function get_expert_affiliation() {

		$template = [];
		$template['affiliation'] = [];

		$type = 'Organization';
		$name = get_post_field( 'post_title', get_field( 'expert_company' , $this->post_id ) );

		if ( $name ) {
		
			$template['affiliation'] = array(
				"@type"	=> $type,
				"name"	=> $name
			);
		
		}

		return $template;

	}

	/**
	 * Get Expert Page organization info
	 *	
	 * @since    1.0.0
	 */
	public function get_expert_organization() {

		$template = [];
		$template['memberOf'] = [];

		$type = 'Organization';
		$name = get_post_field( 'post_title', get_field( 'expert_company' , $this->post_id ) );

		if ( $name ) {

			$template['memberOf'] = array(
				"@type" => $type,
				"name"	=> $name
			);

		}

		return $template;

	}

	/**
	 * Get Expert Page company info
	 *	
	 * @since    1.0.0
	 */
	public function get_expert_company() {

		$template = [];
		$template['worksFor'] = [];

		$type = 'Organization';
		$name = get_post_field( 'post_title', get_field( 'expert_company' , $this->post_id ) );

		if ($name) {

			$template['worksFor'] = array(
				"@type"	=> $type,
				"name"	=> $name
			);

		}

		return $template;

	}

	/**
	 * Get Expert Page title
	 *	
	 * @since    1.0.0
	 */
	public function get_expert_title() {

		$template = [];

		$title = get_field( 'expert_job_title', $this->post_id );

		if ( $title ) {

			$template = array(
				"jobTitle"	=> $title
			);

		}

		return $template;

	}

}