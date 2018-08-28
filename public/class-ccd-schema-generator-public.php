<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.secretstache.com/
 * @since      1.0.0
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Loads main dependencies of the plugin, contains main entry points and
 * common functions for all of classes like get_initial_context()
 *
 */
class CCD_Schema_Generator_Public {

	/**
	 * Initialize the class and load its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		
		$this->load_dependencies();

	}

	/**
	 * Include the main classes responsible for output of the schemas on the page.
	 *
	 */
	public function load_dependencies() {
		
		/**
		 * The class responsible for the main page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/public/class-ccd-schema-generator-mainpage.php';
		
		/**
		 * The class responsible for pages that contain a breadcrumbs.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/public/class-ccd-schema-generator-breadcrumbs.php';
		
		/**
		 * The class responsible for Posts.
		 */
	   require_once plugin_dir_path( dirname( __FILE__ ) ) . '/public/class-ccd-schema-generator-article.php';

	   /**
		 * The class responsible for QA Page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/public/class-ccd-schema-generator-qapage.php';

		/**
		 * The class responsible for Expert Page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/public/class-ccd-schema-generator-expertpage.php';
		
		/**
		 * The class responsible for Single Question page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/public/class-ccd-schema-generator-question.php';
		

		/**
		 * The class responsible for Author Page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/public/class-ccd-schema-generator-authorpage.php';
   
	}

	/**
	 * Get initial schema context which contains @context and @type -
	 * common function for all of the classes
	 *	
	 * @since    1.0.0
	 */
	public function get_initial_context( $type ) {

		$template = [];

		$context = "http://schema.org";

		if ( $context && $type ) {
		
			$template = array(
				"@context" 	=> $context,
				"@type" 	=> $type
			);

		}

		return $template;

	}

	/**
	 * Get an initial context, receives an array of arguments and return a general schema
	 *	
	 * @since    1.0.0
	 */
	public function build() {

		$this->data = $this->get_initial_context( $this->type );

        foreach ( $this->arguments as $argument ) {
            
			$result = call_user_func( array( $this, "get_{$argument}" ) );

			if ( !empty( array_filter( $result ) ) ) {
	            $this->data = array_merge( $this->data, $result );
			}

        }

		$body = wp_json_encode( $this->data );

		if ( $body ) {
			$this->schema = "
				<script type = \"application/ld+json\" >
					{$body}
				</script>
			";
		}
        
	}
	
	/**
	 * Output the result on the page
	 *	
	 * @since    1.0.0
	 */
	public function output() {
        echo ( $this->schema ) ? $this->schema : '';
	}
	
	/**
	 * The function is the main entry point for header scripts, it checks
	 * for the type of the page, initializes required class and outputs the schema
	 *	
	 * @since    1.0.0
	 */
	public function header_schema() {

		// Front Page schema
		if ( is_front_page() ) {

			$arguments	= [ 'common_info', 'social_media', 'founder' ];
			$type 		= 'Organization';

			$schema = new CCD_Schema_Generator_Mainpage( $arguments, $type );
			$schema->build();
			$schema->output();

		}

		// Check the current type of the post		
		if (is_author()) {
			$post_id = get_post_field( 'post_author', get_the_ID() );
		} else {
			$post_id = get_the_ID();
		}

		$post_type	= get_post_type( $post_id );
		$post_format_slug = get_term_by( 'id', get_post_meta( $post_id, 'post_format', true ), 'ccd_post_format' )->slug;

		// General Article schema
		if ( is_singular('post') && $post_format_slug != 'expert-sessions' ) {

			$arguments	= [ 'post_publisher', 'post_info', 'post_author', 'post_image' ];
			$type 		= 'Article';

			$schema = new CCD_Schema_Generator_Article( $arguments, $type, $post_id );
			$schema->build();
			$schema->output();
		
		}

		// QA Page Schema
		if ( $post_format_slug == 'expert-sessions' ) {

			$arguments	= [ 'qa_common', 'qa_reviewed_by', 'qa_publisher', 'qa_contributor' ];
			$type 		= 'QAPage';
			
			$schema = new CCD_Schema_Generator_QAPage( $arguments, $type, $post_id );

			$schema->build();
			$schema->output();

		}

		// Single Question page
		if ( $post_type == 'ccd_question' ) {
			
			$arguments = ['question_common'];
			$type = 'Question';

			$schema = new CCD_Schema_Generator_Question( $arguments, $type, $post_id );
			$schema->build();
			$schema->output();


		}

		// Expert Page schema
		if ( $post_type == "ccd_expert" ) {

			$arguments	= [ 'expert_common', 'expert_media', 'expert_affiliation', 'expert_organization', 'expert_company', 'expert_title' ];
			$type 		= 'Person';

			$schema = new CCD_Schema_Generator_ExpertPage( $arguments, $type, $post_id );
			$schema->build();
			$schema->output();

		}

		// Author Page schema
		if ( is_author() ) {

			$arguments	= [ 'author_common', 'author_media', 'author_affiliation', 'author_organization', 'author_company', 'author_title' ];
			$type 		= 'Person';

			$schema = new CCD_Schema_Generator_AuthorPage( $arguments, $type, $post_id );
			$schema->build();
			$schema->output();

		}	
	}


	/**
	 * The function is the main entry point for footer scripts, it checks the availability of
	 * breadcrumbs on the current page, initializes required class and outputs the schema
	 *	
	 * @since    1.0.0
	 */
	public function footer_schema() {

		// Check if current page contains Breadcrumbs (not a Front Page)
		if ( !is_front_page() ) {

			$arguments = [ 'breadcrumb_list' ];
			$type = 'BreadcrumbList';
			$breadcrumbs = $GLOBALS['breadcrumbs'];

			$schema = new CCD_Schema_Generator_Breadcrumbs( $arguments, $type, $breadcrumbs );
			$schema->build();
			$schema->output();

		}

	}

}