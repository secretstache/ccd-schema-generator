<?php

class CCD_Schema_Generator_AuthorPage extends CCD_Schema_Generator_Public {

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
	 * Get Author Page Common info
	 *	
	 * @since    1.0.0
	 */
	public function get_author_common() {

		$template = [];

        $id = $url = $mainEntityofPage = get_author_posts_url( $this->post_id );
        $name = get_the_author_meta( 'first_name', $this->post_id ) . " " . get_the_author_meta( 'last_name', $this->post_id );
        $email = get_the_author_meta( 'email', $this->post_id );
		$description = "Contributor at The Cell Culture Dish.";
		$image = get_avatar_url( $this->post_id );

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
	 * Get Author Page social media info
	 *	
	 * @since    1.0.0
	 */
	public function get_author_media() {
		
		$template = [];
		$template['sameAs'] = [];

        $linkedin = get_user_meta( $this->post_id, 'user_linkedin_profile', true );

		if ( $linkedin ) {
			$template['sameAs'] = array( $linkedin );
		}

		return $template;

	}

	/**
	 * Get Author Page affiliation info
	 *	
	 * @since    1.0.0
	 */
	public function get_author_affiliation() {

		$template = [];
		$template['affiliation'] = [];

		$type = 'Organization';
        $name = get_user_meta( $this->post_id, 'user_company', true );
        
		if ( $name ) {
		
			$template['affiliation'] = array(
				"@type"	=> $type,
				"name"	=> $name
			);
		
		}

		return $template;

	}

	/**
	 * Get Author Page organization info
	 *	
	 * @since    1.0.0
	 */
	public function get_author_organization() {

		$template = [];
        $template['memberOf'] = [];

		$type = 'Organization';
		$name = get_user_meta( $this->post_id, 'user_company', true );

		if ( $name ) {

			$template['memberOf'] = array(
				"@type" => $type,
				"name"	=> $name
			);

		}

		return $template;

	}

	/**
	 * Get Author Page company info
	 *	
	 * @since    1.0.0
	 */
	public function get_author_company() {

		$template = [];
		$template['worksFor'] = [];

		$type = 'Organization';
		$name = get_user_meta( $this->post_id, 'user_company', true );

		if ($name) {

			$template['worksFor'] = array(
				"@type"	=> $type,
				"name"	=> $name
			);

		}

		return $template;

	}

	/**
	 * Get Author Page title
	 *	
	 * @since    1.0.0
	 */
	public function get_author_title() {

		$template = [];

		$jobTitle = get_user_meta( $this->post_id, 'user_job_title', true );

		if ( $jobTitle ) {

			$template = array(
				"jobTitle"	=> $jobTitle
			);

		}

		return $template;

	}

}