<?php

namespace WeglotWP\Actions\Front;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Flag_Type;
use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Services\Option_Service_Weglot;

/**
 * Enqueue CSS / JS on front
 *
 * @since 2.0
 */
class Front_Enqueue_Weglot implements Hooks_Interface_Weglot {
	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;

	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->option_services = weglot_get_service( 'Option_Service_Weglot' );
	}

	/**
	 * @see Hooks_Interface_Weglot
	 *
	 * @since 2.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'weglot_wp_enqueue_scripts' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'weglot_wp_enqueue_scripts' ) );
	}



	/**
	 * @see wp_enqueue_scripts
	 * @since 2.0
	 *
	 * @return void
	 */
	public function weglot_wp_enqueue_scripts() {

		// Add JS
		wp_register_script( 'wp-weglot-js', WEGLOT_URL_DIST . '/front-js.js', false, WEGLOT_VERSION, false );
		wp_enqueue_script( 'wp-weglot-js' );

		// Add CSS
		wp_register_style( 'weglot-css', WEGLOT_URL_DIST . '/css/front-css.css', false, WEGLOT_VERSION, false );
		wp_enqueue_style( 'weglot-css' );

		//display new flags
		if ( empty( $this->option_services->get_option( 'flag_css' ) )
			&& strpos( $this->option_services->get_css_custom_inline(), 'background-position' ) == false
			&& strpos( $this->option_services->get_css_custom_inline(), 'background-image' ) == false ) {
				Helper_Flag_Type::get_new_flags();
		}

		wp_add_inline_style( 'weglot-css', $this->option_services->get_flag_css() );
		wp_add_inline_style( 'weglot-css', $this->option_services->get_css_custom_inline() );
	}
}
