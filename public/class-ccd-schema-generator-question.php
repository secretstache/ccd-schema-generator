<?php

class CCD_Schema_Generator_Question extends CCD_Schema_Generator_Public {

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
	public function get_question_common() {

        $template = [];

		$name = get_the_title( $this->post_id );
        $text = get_post_field( 'post_content', $this->post_id );
        		
		if ( $name ) {
			$template['name'] = $name;
		}

		if ( $text ) {

			$template['acceptedAnswer'] = array(
				"@type" => 'Answer',
				"text"	=> $text
			);

		}

		return $template;

	}

}