<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.secretstache.com/
 * @since      1.0.0
 *
 * @package    CCD_Schema_Generator
 * @subpackage CCD_Schema_Generator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CCD_Schema_Generator
 * @subpackage CCD_Schema_Generator/admin
 * @author     Secret Stache Media <alex@secretstache.com>
 */
class CCD_Schema_Generator_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in CCD_Schema_Generator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The CCD_Schema_Generator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ccd-schema-generator-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in CCD_Schema_Generator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The CCD_Schema_Generator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ccd-schema-generator-admin.js', array( 'jquery' ), $this->version, false );

	}

	/* Immutable functions */

	/**
	 * It extracts the array of cpt's, loop through each of them and register it.
	 *	
	 * @since    1.0.0
	 */
	public function register_post_types( $args ) {

		$defaults = array();

		$defaults['general'] = array(
			'can_export'   			=> TRUE,
			'capability_type' 		=> "page",
			'description' 			=> '',
			'exclude_from_search' 	=> FALSE,
			'has_archive' 			=> TRUE,
			'hierarchical'	 		=> FALSE,
			'map_meta_cap'			=> TRUE,
			'menu_icon' 			=> 'dashicons-admin-page',
			'menu_position' 		=> 25,
			'public' 				=> TRUE,
			'publicly_querable' 	=> TRUE,
			'query_var' 			=> TRUE,
			'register_meta_box_cb'	=> '',
			'rewrite' 				=> TRUE,
			'show_in_admin_bar'		=> TRUE,
			'show_in_menu'			=> TRUE,
			'show_in_nav_menu' 		=> TRUE,
			'show_ui' 				=> TRUE,
			'supports' 				=> array( 'title', 'editor', 'thumbnail' ),
			'taxonomies' 			=> array(),
			'show_in_rest' 			=> TRUE
		);
		

		foreach ( $args as $post_type ) {

			// Obligatory variables

			$cpt_name 		= $post_type[ 'cpt_name' ];
			$slug			= $post_type[ 'slug' ];
			$text_domain 	= $post_type[ 'text_domain' ];
			$single 		= $post_type[ 'single' ];
			$plural 		= $post_type[ 'plural' ];
			
			// Optional variables

			$opts = array();

			foreach ( $defaults['general'] as $parameter => $value ) {
				$$parameter = ( isset( $post_type[ $parameter] ) && !is_null( $post_type[ $parameter ] ) ) ? $post_type[ $parameter ] : $defaults['general'][ $parameter ];
				$opts[ $parameter ] = $$parameter;
			}

			$defaults['labels'] = array(
				'name'					=> esc_html__( $plural, $text_domain ),
				'singular_name'			=> esc_html__( $single, $text_domain ),
				'add_new'				=> esc_html__( "Add New {$single}", $text_domain ),
				'add_new_item'			=> esc_html__( "Add New {$single}", $text_domain ),
				'edit_item'				=> esc_html__( "Edit {$single}", $text_domain ),
				'new_item'				=> esc_html__( "New {$single}", $text_domain ),
				'view_item'				=> esc_html__( "View {$single}", $text_domain ),
				'view_items'			=> esc_html__( "View {$plural}", $text_domain ),
				'search_items'			=> esc_html__( "Search {$plural}", $text_domain ),
				'not_found'				=> esc_html__( "No {$plural} Found", $text_domain ),
				'not_found_in_trash'	=> esc_html__( "No {$plural} Found in Trash", $text_domain ),
				'parent_item_colon'		=> esc_html__( "Parent {$plural} :", $text_domain ),
				'all_items'				=> esc_html__( $plural, $text_domain ),
				'archives'				=> esc_html__( "{$single} Archives", $text_domain ),
				'attributes' 			=> esc_html__( "{$single} Attributes", $text_domain ),
				'insert_into_item'		=> esc_html__( "Insert into {$single}", $text_domain ),
				'uploaded_to_this_item' => esc_html__( "Uploaded to this {$single}", $text_domain ),
				'featured_image'		=> esc_html__( "Featured image", $text_domain ),
				'set_featured_image'	=> esc_html__( "Set featured image for this", $text_domain ),
				'remove_featured_image' => esc_html__( "Remove featured image", $text_domain ),
				'use_featured_image'	=> esc_html__( "Use as featured image", $text_domain ),
				'menu_name'				=> esc_html__( $plural, $text_domain ),
				'filter_items_list'		=> esc_html__( "Filter {$plural} list", $text_domain ),
				'items_list_navigation' => esc_html__( "{$plural} list navigation", $text_domain ),
				'items_list'			=> esc_html__( "{$plural} list", $text_domain ),
				'name_admin_bar'		=> esc_html__( $single, $text_domain )
			);

			if ( isset( $post_type['labels'] ) ) {
				foreach ( $defaults['labels'] as $parameter => $value ) {
					$opts['labels'][ $parameter ] = !is_null( $post_type['labels'][ $parameter ] ) ? $post_type['labels'][ $parameter ] : $defaults['labels'][ $parameter ];
				}
			} else {
				$opts['labels'] = $defaults['labels'];
			}

			$defaults['capabilities'] = array(
				'delete_others_posts'			=> "delete_others_{$capability_type}s",
				'delete_post'					=> "delete_{$capability_type}",
				'delete_posts'					=> "delete_{$capability_type}s",
				'delete_private_posts'			=> "delete_private_{$capability_type}s",
				'delete_published_posts'		=> "delete_published_{$capability_type}s",
				'edit_others_posts'				=> "edit_others_{$capability_type}s",
				'edit_post'						=> "edit_{$capability_type}",
				'edit_posts'					=> "edit_{$capability_type}s",
				'edit_private_posts'			=> "edit_private_{$capability_type}s",
				'edit_published_posts'			=> "edit_published_{$capability_type}s",
				'publish_posts'					=> "publish_{$capability_type}s",
				'read_post'						=> "read_{$capability_type}",
				'read_private_posts'			=> "read_private_{$capability_type}s"
			);

			if ( isset( $post_type['capabilities'] ) ) {
				foreach ( $defaults['capabilities'] as $parameter => $value ) {
					$opts['capabilities'][ $parameter ] = !is_null( $post_type['capabilities'][ $parameter ] ) ? $post_type['capabilities'][ $parameter ] : $defaults['capabilities'][ $parameter ];
				}
			} else {
				$opts['capabilities'] = $defaults['capabilities'];
			}

			$defaults['rewrite'] = array(
				'ep_mask'						=> EP_PERMALINK,
				'feeds'							=> FALSE,
				'pages'							=> TRUE,
				'slug'							=> esc_html__( strtolower( $slug ), $text_domain ),
				'with_front'					=> FALSE
			);

			if ( isset( $post_type['rewrite'] ) ) {
				foreach ( $defaults['rewrite'] as $parameter => $value ) {
					$opts['rewrite'][ $parameter ] = !is_null( $post_type['rewrite'][ $parameter ] ) ? $post_type['rewrite'][ $parameter ] : $defaults['rewrite'][ $parameter ];
				}
			} else {
				$opts['rewrite'] = $defaults['rewrite'];
			}

			$opts = apply_filters( "ssm-" . $slug . "-cpt-options", $opts );

			register_post_type( strtolower( $cpt_name ), $opts );

		}

	}

	/**
	 * It extracts the array of taxonomies, loop through each of them and register it to the corresponding cpt's.
	 *	
	 * @since    1.0.0
	 */
	public function register_taxonomies( $args ) {

		$defaults = array();

		$defaults['general'] = array(
			'hierarchical' 		=> FALSE,
			'show_ui' 			=> TRUE,
			'show_admin_column' => TRUE,
			'query_var' 		=> TRUE
		);

		foreach ( $args as $taxonomy ) {

			// Obligatory variables

			$tax_name 		= $taxonomy['tax_name']; 
			$cpt_name 		= $taxonomy['cpt_name'];
			$slug 			= $taxonomy['slug'];
			$text_domain 	= $taxonomy['text_domain'];
			$single 		= $taxonomy['single'];
			$plural 		= $taxonomy['plural'];

			// Optional variables

			$opts = array();

			foreach ( $defaults['general'] as $parameter => $value ) {
				$$parameter = ( isset( $taxonomy[ $parameter ] ) && !is_null( $taxonomy[ $parameter ] ) ) ? $taxonomy[ $parameter ] : $defaults['general'][ $parameter ];
				$opts[ $parameter ] = $$parameter;
			}

			$defaults['labels'] = array(
				'name'						=> esc_html__( $plural, $text_domain ),
				'singular_name'				=> esc_html__( $single, $text_domain ),
				'menu_name'					=> esc_html__( $plural, $text_domain ),
				'all_items'					=> esc_html__( $plural, $text_domain ),
				'edit_item'					=> esc_html__( "Edit {$single}", $text_domain ),
				'view_item'					=> esc_html__( "View {$single}", $text_domain ),
				'view_items'				=> esc_html__( "View {$plural}", $text_domain ),
				'update_item'				=> esc_html__( "Update {$single}", $text_domain ),
				'add_new'					=> esc_html__( "Add New {$single}", $text_domain ),
				'add_new_item'				=> esc_html__( "Add New {$single}", $text_domain ),
				'new_item_name'				=> esc_html__( "New {$single} name", $text_domain ),
				'parent_item'				=> esc_html__( "Parent {$single} :", $text_domain ),
				'parent_item_colon'			=> esc_html__( "Parent {$plural} :", $text_domain ),
				'search_items'				=> esc_html__( "Search {$plural}", $text_domain ),
				'popular_items'				=> esc_html__( "Popular {$plural}", $text_domain ),
				'separate_items_with_commas'=> esc_html__( "Separate {$plural} with commas", $text_domain ),
				'add_or_remove_items' 		=> esc_html__( "Add or remove {$plural}", $text_domain ),
				'choose_from_most_used'		=> esc_html__( "Choose from most used", $text_domain ),
				'not_found'					=> esc_html__( "No {$plural} Found", $text_domain ),
			);

			if ( isset( $taxonomy['labels'] ) ) {
				
				foreach ( $defaults['labels'] as $parameter => $value ) {
					$opts['labels'][ $parameter ] = !is_null( $taxonomy['labels'][ $parameter ] ) ? $taxonomy['labels'][ $parameter ] : $defaults['labels'][ $parameter ];
				}

			} else {
				$opts['labels'] = $defaults['labels'];
			}

			$defaults['capabilities'] = array(
				'manage_terms' 		=> "manage_categories",
				'edit_terms' 		=> "manage_categories",
				'delete_terms' 		=> "manage_categories",
				'assign_terms'		=> "edit_posts"
			);

			if ( isset( $taxonomy['capabilities'] ) ) {
				foreach ( $defaults['capabilities'] as $parameter => $value ) {
					$opts['capabilities'][ $parameter ] = !is_null( $taxonomy['capabilities'][ $parameter ] ) ? $taxonomy['capabilities'][ $parameter ] : $defaults['capabilities'][ $parameter ];
				}
			} else {
				$opts['capabilities'] = $defaults['capabilities'];
			}

			$defaults['rewrite'] = array(
				'ep_mask'			=> EP_PERMALINK,
				'feeds'				=> FALSE,
				'pages'				=> TRUE,
				'slug'				=> esc_html__( strtolower( $slug ), $text_domain ),
				'with_front'		=> FALSE
			);

			if ( isset( $taxonomy['rewrite'] ) ) {
				foreach ( $defaults['rewrite'] as $parameter => $value ) {
					$opts['rewrite'][ $parameter ] = !is_null( $taxonomy['rewrite'][ $parameter ] ) ? $taxonomy['rewrite'][ $parameter ] : $defaults['rewrite'][ $parameter ];
				}
			} else {
				$opts['rewrite'] = $defaults['rewrite'];
			}

			$opts = apply_filters( "ssm-" . $slug . "-options", $opts );

			register_taxonomy( $tax_name, $cpt_name, $opts );

		}

	}

	/**
	 * It extracts the array of terms, loop through each of them and register it to the corresponding taxonomies.
	 *	
	 * @since    1.0.0
	 */
	public function register_terms( $args ) {

		$defaults = array(
			'alias_of' 		=> '',
			'description' 	=> '',
			'parent' 		=> 0
		);

		foreach ( $args as $term ) {

			// Obligatory variables

			$term_name 		= $term['term_name']; 
			$taxonomy_name 	= $term['taxonomy_name'];
			$slug 			= $term['slug'];

			// Optional variabls

			$opts = array();

			foreach ( $defaults as $parameter => $value ) {
				$$parameter = ( isset( $term[ $parameter ] ) && !is_null( $term[ $parameter ] ) ) ? $term[ $parameter ] : $defaults[ $parameter ];
				$opts[ $parameter ] = $$parameter;
			}

			$opts['slug'] = strtolower( $slug );

			$opts = apply_filters( "ssm-" . $slug . "-options", $opts );

			wp_insert_term( $term_name, $taxonomy_name, $opts );

		}

	}

	/* Mutable functions */

	/**
	 * Step by step, it adds items to main multidemensional array of post types,
	 * taxonomies and terms and call corresponding hooks using do_action().
	 *	
	 * @since    1.0.0
	 */
	public function call_registration() {

		// Registration of CPT

		$cpt_args = array();

		array_push( $cpt_args, array(
			"cpt_name" 			=> "test",
			"slug" 				=> "ssm-test",
			"text_domain" 		=> "ssm-test",
			"single" 			=> "Test",
			"plural" 			=> "Tests",

			"capability_type" 	=> "page",
			"menu_icon"			=> "dashicons-admin-page",
			"menu_position"		=> 25,
			"show_in_menu"		=> TRUE,
			"supports" 			=> array( 'title', 'editor', 'thumbnail' ),

			"labels" 			=> array(
				'set_featured_image'	=> "Another formulation of Set featured image",
				'remove_featured_image' => "Another formulation of Remove featured image"
			),
			"capabilities"		=> array(
				'delete_others_posts' 	=> "delete_others_posts"
			),
			"rewrite" 			=> array(
				'with_front'			=> TRUE
			)
		));

		// new post types go here...

		if ( !empty( $cpt_args ) ) {
			do_action( 'custom_cpt_hook', $cpt_args );
		}

		// Registration of Taxonomies

		$tax_args = array();

		array_push( $tax_args, array(
			"tax_name" 		=> "test_type",
			"cpt_name" 		=> "test",
			"slug" 			=> "ssm-test-type",
			"text_domain"	=> "ssm-test-type",
			"single" 		=> "Type",
			"plural" 		=> "Types",
			
			'hierarchical' 		=> TRUE,

			"labels" 			=> array(
				'add_new_item'	=> "Another formulation of Add new Type",
			),
			"capabilities"		=> array(
				'assign_terms'			=> "edit_pages"
			),
			"rewrite" 			=> array(
				'with_front'			=> TRUE
			)
		) );
	
		// new taxonomies go here...

		if ( !empty( $tax_args ) ) {
			do_action( 'custom_taxonomies_hook', $tax_args );
		}


		// Registration of Terms

		$terms_args = array();

		array_push( $terms_args, array(
			"term_name" 		=> "Term",
			"taxonomy_name" 	=> "test_type",
			"slug"				=> "test_type_term",

			"description" 		=> "Test description"
		) );
		
		// new terms go here...

		if ( !empty( $terms_args ) ) {
			do_action( 'custom_terms_hook', $terms_args );
		}

	}


	/**
	 * Prevent adding new terms to custom taxonomies.
	 *	
	 * @since    1.0.0
	 */
	function term_adding_prevent( $term, $taxonomy ) {

		$taxonomies = array( "test_type" ); //list of taxonomies we don't allow to add term into

		return ( in_array( $taxonomy, $taxonomies ) ) ? new WP_Error( 'term_addition_blocked', __( 'You cannot add terms to this taxonomy.' ) ) : $term;
		
	}

	/**
	 * Prevent removing terms from custom taxonomies.
	 *	
	 * @since    1.0.0
	 */
	function term_removing_prevent( $term_id ) {

		$terms = array();
		$taxonomies = array( "test_type" ); //list of taxonomies whose terms we don't allow to remove

		foreach ( $taxonomies as $taxonomy ) {
			$terms = array_merge( $terms, get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) ) );
		}

		foreach ( $terms as $term ) {
			if( get_term( $term_id )->slug === $term->slug ) 
				wp_die( 'You cannot remove terms from this taxonomy.' );
		}
	
	}

	/**
	 * Set default terms for custom taxonomies on post save.
	 *	
	 * @since    1.0.0
	 */
	function set_default_terms( $post_id, $post ) {

		if ( 'publish' === $post->post_status ) {

			$defaults = array(
				'test_type' 	=> array( 'test_type_term' ),
				// and so on...
			);

			$taxonomies = get_object_taxonomies( $post->post_type );

			foreach ( (array) $taxonomies as $taxonomy ) {

				$terms = wp_get_post_terms( $post_id, $taxonomy );
				
				if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
					wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
				}

			}
		}
	}	

}
