<?php

class CCD_Schema_Generator_Mainpage extends CCD_Schema_Generator_Public {

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
	public function __construct( $arguments, $type ) {

        $this->arguments = $arguments;
        $this->type = $type;
        $this->data = [];
        $this->schema = '';   
    
    }

	/**
	 * Get common informaton about site
	 *	
	 * @since    1.0.0
	 */
	public function get_common_info() {

		$template = [];
		
		$id = $url = $mainEntityofPage	= get_bloginfo('url');
		$name = $brand					= get_bloginfo('name');
		$email 							= get_field( 'ccd_default_email', 'options' );
		$description					= get_bloginfo('description');

		$logo_field = get_field('schema_brand_logo', 'options');
		$logo = $image = $logo_field['url'];

		if ( $id ) {
			$template['@id'] = $id;
		}
		
		$arguments = ['url', 'mainEntityofPage', 'name', 'email', 'brand', 'description', 'logo', 'image'];

		foreach ( $arguments as $argument ) {
			if ( $$argument ) {
				$template[$argument] = $$argument;
			}
		}

		return $template;

	}

	/**
	 * Get social network links
	 *	
	 * @since    1.0.0
	 */
	public function get_social_media() {

		$template = [];
		$template["sameAs"] = [];
		$requested_networks = [ 'facebook', 'twitter', 'google', 'linkedin', 'youtube' ];

		foreach ( $requested_networks as $network ) {

			if ( ssm_get_field( $network, 'options' ) ) {
				array_push( $template['sameAs'], ssm_get_field( $network, 'options' ) );
			}
			
		}
		
		return $template;

	}

	/**
	 * Get site founder
	 *	
	 * @since    1.0.0
	 */
	public function get_founder() {

		$template = [];
		$template['founder'] = [];

		$type = 'Person';
		$name = get_field('ccd_founder', 'options');

		if ( $name ) {
			
			$template['founder'] = array(
				"@type" => $type,
				"name" 	=> $name
			);
		
		}

		return $template;

	}

}