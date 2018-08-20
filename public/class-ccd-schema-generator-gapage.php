<?php

class CCD_Schema_Generator_QAPage extends CCD_Schema_Generator_Public {

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
	 * The main array of questions associated with current QA page
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private $questions;    
    
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
        $this->questions = [];
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
		
		$template = array(
			"@id"	=> $id,
			"url"	=> $url,
			"about"	=> $about
		);

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

		$template['reviewedBy'] = array(
			"@type" => $type,
			"name"	=> $name
		);

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

		$template['publisher'] = array(
			"@type"	=> $type,
			"name"	=> $name
		);

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

			$template['contributor'] = array(
				"@type"	=> $type,
				"name"	=> $name,
				"url"	=> $url
			);
		
		}

		return $template;

	}

	/**
	 * Get FAQ schema questions
	 *	
	 * @since    1.0.0
	 */
	public function get_qa_questions() {

		$template = [];

		$context 	= 'http://schema.org';
		$type 		= 'Question';
		$answerType	= 'Answer';

		$args = array(
			"post_type" 	=> 'ccd_question',
			"numberposts"	=> -1,
			"order"			=> "ASC",

			'meta_query' 	=> array(
				array(
					'key' 		=> 'session_id',
					'value' 	=> $this->post_id,
					'compare' 	=> 'LIKE'
				),
			)
		);

		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
			
			array_push( $template, array(
				"@context" 	=> $context,
				"@type"		=> $type,
				"name" 		=> $post->post_title,
				"acceptedAnswer" => array(
						"@type"   	=> $answerType,
						"answer" 	=> limit_words( $post->post_content, 20 )	
					)
				)
			);
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

        $this->questions = $this->get_qa_questions();

        $general_body = wp_json_encode( $this->data );
        $questions_body = wp_json_encode( $this->questions );

		$this->schema = "
			<script type = \"application/ld+json\" >
				{$general_body}
            </script>
            
            <script type = \"application/ld+json\" >
				{$questions_body}
            </script>
        ";
        
    }

    public function output() {
        echo $this->schema;
    }

}