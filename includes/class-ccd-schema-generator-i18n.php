<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.secretstache.com/
 * @since      1.0.0
 *
 * @package    CCD_Schema_Generator
 * @subpackage CCD_Schema_Generator/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    CCD_Schema_Generator
 * @subpackage CCD_Schema_Generator/includes
 * @author     Secret Stache Media <alex@secretstache.com>
 */
class CCD_Schema_Generator_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ccd-schema-generator',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
