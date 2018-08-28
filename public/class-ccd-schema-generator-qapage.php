<?php

class CCD_Schema_Generator_QAPage extends CCD_Schema_Generator_Public {

    /**
	 * The array of arguments needed to be included in the schema
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    protected $arguments;
    
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
	 * The main array of data splitted on a few arrays according to the list of arguments
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    protected $data;  

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
	 * Get QA Page common info
	 *	
	 * @since    1.0.0
	 */
	public function get_qa_common() {

		$template = [];

		$id = $url = get_the_permalink( $this->post_id );
		$about = get_term_by( 'id', get_post_meta( $this->post_id, 'post_category', true ), 'category' )->name;
		
		if ( $id ) {
			$template['@id'] = $id;
		}

		$arguments = ['url', 'about'];

		foreach ( $arguments as $argument ) {
			if ( $$argument ) {
				$template[$argument] = $$argument;
			}
		}

		return $template;

	}

	/**
	 * Get QA Page reviwer
	 *	
	 * @since    1.0.0
	 */
	public function get_qa_reviewed_by() {

		$template = [];
		$template['reviewedBy'] = [];

		$type = 'Organization';
		$name = get_bloginfo('name');

		if ( $name ) {

			$template['reviewedBy'] = array(
				"@type" => $type,
				"name"	=> $name
			);	
		
		}

		return $template;

	}

	/**
	 * Get QA Page publisher
	 *	
	 * @since    1.0.0
	 */
	public function get_qa_publisher() {

		$template = [];
		$template['publisher'] = [];

		$type = 'Organization';
		$name = get_bloginfo('name');

		if ( $name ) {

			$template['publisher'] = array(
				"@type"	=> $type,
				"name"	=> $name
			);
		
		}

		return $template;

	}

	/**
	 * Get QA Page contributor
	 *	
	 * @since    1.0.0
	 */
	public function get_qa_contributor() {

		$template = [];
		$template['contributor'] = [];

		$contributor_id = get_post_meta( $this->post_id, 'additional_author', true );

		if ( $contributor_id ) {

			$type 	= 'Person';
			$name 	= get_expert_title( $contributor_id );
			$url 	= get_post_permalink( $contributor_id );

			$template['contributor']['@type'] = $type;

			$arguments = ['name', 'url'];
			
			foreach ( $arguments as $argument ) {
				if ( $$argument ) {
					$template['contributor'][$argument] = $$argument;
				}
			}
		
		}

		return $template;

	}

}