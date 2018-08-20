<?php

class CCD_Schema_Generator_Breadcrumbs extends CCD_Schema_Generator_Public {

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
	 * The array contains the information about breadcrumbs of the current page
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private $breadcrumbs;

    /**
	 * The main type of schema to be set along with context
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private $type;

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
	public function __construct( $arguments, $type, $breadcrumbs ) {

        $this->arguments = $arguments;
        $this->type = $type;
        $this->breadcrumbs = $breadcrumbs;
        $this->data = [];
        $this->schema = '';   
    
    }

	/**
	 * Get breadcrumb list
	 *	
	 * @since    1.0.0
	 */
	public function get_breadcrumb_list() {

		$template = [];

		if ( $this->breadcrumbs['elementCount'] > 0 ) {

			$template['itemListElement'] = [];

			foreach ( $this->breadcrumbs['elements'] as $key => $element ) {

				$position = $key + 1;
				$id = $element['id'];
				$name = $element['name'];

				array_push( $template['itemListElement'], array(
					"@type" 		=> "ListItem",
					"position" 		=> $position,
					"item"			=> array(
						"@id" 	=> $id,
						"name"	=> $name
					)
				) );

			}

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