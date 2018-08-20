<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.secretstache.com/
 * @since      1.0.0
 *
 * @package    CCD_Schema_Generator
 * @subpackage CCD_Schema_Generator/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    CCD_Schema_Generator
 * @subpackage CCD_Schema_Generator/public
 * @author     Secret Stache Media <alex@secretstache.com>
 */
class CCD_Schema_Generator_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Get initial schema context, such as: @context, @type 
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
	 * Get common informaton about site, such as: name, url and logo
	 *	
	 * @since    1.0.0
	 */
	public function get_common_info() {

		$template = [];
		
		$id = $url = $mainEntity	= get_bloginfo('url');
		$name = $brand				= get_bloginfo('name');
		$email 						= get_field( 'ccd_default_email', 'options' );
		$description				= get_bloginfo('description');

		$logo = get_field('schema_brand_logo', 'options');
		$logo_url = $logo['url'];
		$logo_width = $logo['width'];
		$logo_height = $logo['height'];
		$image_url = $logo['url'];

		if ( $id && $name ) {

			$template = array(
					"@id" 				=> $id,
					"url" 				=> $url,
					"mainEntityofPage"	=> $mainEntity,
					"name"				=> $name,
					"email"				=> $email,
					"brand"				=> $brand,
					"description"		=> $description,
					"logo"				=> $logo_url,
					"image"				=> $image_url
			);

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

		$template['founder'] = array(
			"@type" => $type,
			"name" 	=> $name
		);

		return $template;

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
	public function get_post_info( $post_id ) {

		$template = [];

		$link = get_the_permalink( $post_id );
		$title = get_the_title( $post_id );
		$date_published = get_the_date( "Y-m-d\TG:i:s\.000\Z", $post_id );
		$date_modified = get_the_modified_date( "Y-m-d\TG:i:s\.000\Z", $post_id );


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
	public function get_post_author( $post_id ) {

		$template = [];

		$main_author = get_user_meta( get_post_field ('post_author', $post_id ) );		

		if ($main_author) {

			$template['author'] = [];

			$type 	= "Person";
			$name	= $main_author['first_name'][0] . " " . $main_author['last_name'][0];
			$url 	= get_author_posts_url( get_post_field ('post_author', $post_id ) );

			$template['author'][] = array(
				"@type" => $type,
				"name"	=> $name,
				"url" 	=> $url
			);
				
		}

		$additional_author_id = get_post_meta( $post_id, 'additional_author', true );

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
	public function get_post_image( $post_id ) {

		$template = [];
		$template['image'] = [];

		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );

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
	public function get_post_sponsor( $post_id ) {

		$template = "";

		$sponsor_id = get_post_meta( $post_id, 'post_sponsorship_sponsor', true );

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
	 * Get breadcrumbs list
	 *	
	 * @since    1.0.0
	 */
	public function get_breadcrumbs_list( $breadcrumbs ) {

		$template = [];

		if ( $breadcrumbs['elementCount'] > 0 ) {

			$template['itemListElement'] = [];

			foreach ( $breadcrumbs['elements'] as $key => $element ) {

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
	 * Get QA Page common info
	 *	
	 * @since    1.0.0
	 */
	public function get_faq_common( $post_id ) {

		$template = [];

		$id = $url = get_the_permalink( $post_id );
		$about = get_term_by( 'id', get_post_meta( $post_id, 'post_category', true ), 'category' )->name;
		
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
	public function get_faq_reviewed_by( $post_id ) {

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
	public function get_faq_publisher( $post_id ) {

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
	public function get_faq_contributor( $post_id ) {

		$template = [];
		$template['contributor'] = [];

		$contributor_id = get_post_meta( $post_id, 'additional_author', true );

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
	public function get_faq_questions( $post_id ) {

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
					'value' 	=> $post_id,
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
	 * Get Expert Page Common info
	 *	
	 * @since    1.0.0
	 */
	public function get_expert_common( $post_id ) {

		$template = [];

		$id = $url = $mainEntity = get_post_permalink( $post_id );
		$name = get_expert_title( $post_id );
		$description = "Contributor at The Cell Culture Dish.";
		$image = get_home_url() . wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' )[0];
		
		if ( $id && $name && $description && $image ) {

			$template = array(
				"@id"				=> $id,
				"url"				=> $url,
				"mainEntityofPage"	=> $mainEntity,
				"name"				=> $name,
				"description"		=> $description,
				"image"				=> $image
			);

			return $template;
		}

	}

	/**
	 * Get Expert Page social media info
	 *	
	 * @since    1.0.0
	 */
	public function get_expert_media( $post_id ) {
		
		$template = [];
		$template['sameAs'] = [];

		$linkedin = get_field( 'expert_linkedin_profile', $post_id );

		$template['sameAs'] = array( $linkedin );

		return $template;

	}

	/**
	 * Get Expert Page affiliation info
	 *	
	 * @since    1.0.0
	 */
	public function get_expert_affiliation( $post_id ) {

		$template = [];
		$template['affiliation'] = [];

		$type = 'Organization';
		$name = get_post_field( 'post_title', get_field( 'expert_company' , $post_id ) );

		$template['affiliation'] = array(
			"@type"	=> $type,
			"name"	=> $name
		);

		return $template;

	}

	/**
	 * Get Expert Page organization info
	 *	
	 * @since    1.0.0
	 */
	public function get_expert_organization( $post_id ) {

		$template = [];
		$template['memberOf'] = [];

		$type = 'Organization';
		$name = get_post_field( 'post_title', get_field( 'expert_company' , $post_id ) );

		$template['memberOf'] = array(
			"@type" => $type,
			"name"	=> $name
		);

		return $template;

	}

	/**
	 * Get Expert Page company info
	 *	
	 * @since    1.0.0
	 */
	public function get_expert_company( $post_id ) {

		$template = [];
		$template['worksFor'] = [];

		$type = 'Organization';
		$name = get_post_field( 'post_title', get_field( 'expert_company' , $post_id ) );

		$template['worksFor'] = array(
			"@type"	=> $type,
			"name"	=> $name
		);

		return $template;

	}

	/**
	 * Get Expert Page title
	 *	
	 * @since    1.0.0
	 */
	public function get_expert_title( $post_id ) {

		$template = [];

		$title = get_field( 'expert_job_title', $post_id );

		$template = array(
			"jobTitle"	=> $title
		);

		return $template;

	}

	/**
	 * The function receives an array of arguments and return a genaeral schema
	 *	
	 * @since    1.0.0
	 */
	public function create_mainpage_schema( $args ) {
		
		$body = wp_json_encode( array_merge( 
				$args['initial_context'],
				$args['common_info'],
				$args['social_media'],
				$args['founder']
			)	
		);

		$template = "
			<script type = \"application/ld+json\" >
				{$body}
			</script>
		";

		return $template;

	}

	/**
	 * The function receives an array of arguments and return a schema for a post
	 *	
	 * @since    1.0.0
	 */
	public function create_post_schema( $post_args ) {
		
		$body = wp_json_encode( array_merge( 
				$post_args['initial_context'],
				$post_args['post_publisher'],
				$post_args['post_info'],
				$post_args['post_author'],
				$post_args['post_image']
			)	
		);

		$template = "
			<script type = \"application/ld+json\" >
				{$body} 
			</script>
		";

		return $template;

	}

	/**
	 * The function receives an array of arguments and return a schema for any page which has breadcrumbs
	 *	
	 * @since    1.0.0
	 */
	public function create_breadcrumbs_schema( $breadcrumbs_args ) {

		$body = wp_json_encode( array_merge(
				$breadcrumbs_args['initial_context'],
				$breadcrumbs_args['breadcrumbs_list'] 
			) 
		);

		$template = "
			<script type = \"application/ld+json\" >
					{$body}
			</script>
		";

		return $template;

	}

	/**
	 * The function receives an array of arguments and return a schema
	 * for post with post format 'Expert Sessions'
	 *	
	 * @since    1.0.0
	 */
	public function create_qapage_schema( $qapage_args ) {

		$body = wp_json_encode( array_merge( 
				$qapage_args['initial_context'],
				$qapage_args['faq_common'],
				$qapage_args['faq_reviewed_by'],
				$qapage_args['faq_publisher'],
				$qapage_args['faq_contributor']
			) 
		);

		$template = "
			<script type = \"application/ld+json\" >
				{$body} 
			</script>
		";

		return $template;

	}

	/**
	 * The function receives an array of arguments and return a schema
	 * which contains set of question-answer for post with post format 'Expert Sessions'
	 *	
	 * @since    1.0.0
	 */
	public function create_qapage_questions_schema( $qapage_questions_args ) {

		$body = wp_json_encode( $qapage_questions_args['questions'] );

		$template = "
			<script type= \"application/ld+json\" >
					{$body}
			</script>
		";

		return $template;

	}

	/**
	 * The function receives an array of arguments and return a schema
	 * for the expert page.
	 *	
	 * @since    1.0.0
	 */
	public function create_expert_schema( $expert_args ) {

		$body = wp_json_encode( array_merge( 
				$expert_args['initial_context'],
				$expert_args['expert_common'],
				$expert_args['expert_media'],
				$expert_args['expert_affiliation'],
				$expert_args['expert_organization'],
				$expert_args['expert_company'],
				$expert_args['expert_title']
			)	
		);

		$template = "
			<script type = \"application/ld+json\" >
				{$body} 
			</script>
		";

		return $template;

	}

	/**
	 * The function is the main entry point, it gets all neccesarry
	 * variables, pass it through template and output the result in '<head>' section 
	 *	
	 * @since    1.0.0
	 */
	public function show_schema() {

		if ( is_front_page() ) {

			$initial_context	= $this->get_initial_context( 'Organization' );
			$common_info		= $this->get_common_info();
			$social_media		= $this->get_social_media();
			$founder			= $this->get_founder();

			$mainpage_args['initial_context']	= $initial_context;
			$mainpage_args['common_info']		= $common_info;
			$mainpage_args['social_media']		= $social_media;
			$mainpage_args['founder']			= $founder;

			echo $this->create_mainpage_schema( $mainpage_args );
		
		}

		if ( is_single() ) {

			$post_id = get_the_ID();
			
			$initial_context 	= $this->get_initial_context( 'Article' );
			$post_publisher		= $this->get_post_publisher( $post_id );
			$post_info			= $this->get_post_info( $post_id );
			$post_author		= $this->get_post_author( $post_id );
			$post_image			= $this->get_post_image( $post_id );

			$post_args['initial_context']	= $initial_context;
			$post_args['post_publisher']	= $post_publisher;
			$post_args['post_info'] 		= $post_info;
			$post_args['post_author']		= $post_author;
			$post_args['post_image']		= $post_image;

			echo $this->create_post_schema( $post_args );
			
			$post_format_slug = get_term_by( 'id', get_post_meta( $post_id, 'post_format', true ), 'ccd_post_format' )->slug;

			if ( $post_format_slug == 'expert-sessions' ) {

				$initial_context 	= $this->get_initial_context( 'QAPage' );
				$faq_common			= $this->get_faq_common( $post_id );
				$faq_reviewed_by	= $this->get_faq_reviewed_by( $post_id );
				$faq_publisher		= $this->get_faq_publisher( $post_id );
				$faq_contributor	= $this->get_faq_contributor( $post_id );

				$qapage_args['initial_context']	= $initial_context;
				$qapage_args['faq_common']		= $faq_common;
				$qapage_args['faq_reviewed_by'] = $faq_reviewed_by;
				$qapage_args['faq_publisher']	= $faq_publisher;
				$qapage_args['faq_contributor']	= $faq_contributor;
				
				echo $this->create_qapage_schema( $qapage_args );

				$questions = $this->get_faq_questions( $post_id );

				$qapage_questions_args['questions'] = $questions;

				echo $this->create_qapage_questions_schema( $qapage_questions_args );

			}

			if ( get_post_type( $post_id ) == "ccd_expert" ) {

				$initial_context 		= $this->get_initial_context( 'Person' );
				$expert_common			= $this->get_expert_common( $post_id );
				$expert_media			= $this->get_expert_media( $post_id );
				$expert_affiliation		= $this->get_expert_affiliation( $post_id );
				$expert_organization	= $this->get_expert_organization( $post_id );
				$expert_company			= $this->get_expert_company( $post_id );
				$expert_title			= $this->get_expert_title( $post_id );

				$expert_args['initial_context']		= $initial_context;
				$expert_args['expert_common']		= $expert_common;
				$expert_args['expert_media']		= $expert_media;
				$expert_args['expert_affiliation']	= $expert_affiliation;
				$expert_args['expert_organization']	= $expert_organization;
				$expert_args['expert_company']		= $expert_company;
				$expert_args['expert_title']		= $expert_title;

				echo $this->create_expert_schema( $expert_args );

			}

		}
	}

	/**
	 * The function is the main entry point, it gets the whole breadcrumbs array,
	 * pass it through template and output the result in '<footer>' section 
	 *	
	 * @since    1.0.0
	 */
	public function show_breadcrumbs_schema() {

		if ( !is_front_page() ) {

			$breadcrumbs = $GLOBALS['breadcrumbs'];

			$initial_context 	= $this->get_initial_context( 'BreadcrumbList' );
			$breadcrumbs_list 	= $this->get_breadcrumbs_list( $breadcrumbs );

			$breadcrumbs_args['initial_context'] 	= $initial_context;
			$breadcrumbs_args['breadcrumbs_list'] 	= $breadcrumbs_list;

			echo $this->create_breadcrumbs_schema( $breadcrumbs_args ); 

		}

	}

}