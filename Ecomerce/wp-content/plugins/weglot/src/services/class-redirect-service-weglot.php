<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Redirect URL
 *
 * @since 2.0
 */
class Redirect_Service_Weglot {

	/**
	 *
	 * @var boolean
	 */
	protected $no_redirect = false;
	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;
	/**
	 * @var Request_Url_Service_Weglot
	 */
	private $request_url_services;
	/**
	 * @var Language_Service_Weglot
	 */
	private $language_services;

	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->option_services      = weglot_get_service( 'Option_Service_Weglot' );
		$this->request_url_services = weglot_get_service( 'Request_Url_Service_Weglot' );
		$this->language_services    = weglot_get_service( 'Language_Service_Weglot' );
	}

	/**
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function get_no_redirect() {
		return $this->no_redirect;
	}

	/**
	 * @since 2.3.0
	 * @param string $server_lang
	 * @return string
	 */
	protected function language_exception( $server_lang ) {

		if ( in_array( $server_lang, [ 'zh-TW', 'zh-HK' ] ) ) {
			$server_lang = 'tw';
		}

		if ( in_array( $server_lang, [ 'pt-BR' ] ) ) {
			$server_lang = 'br';
		}

		$server_lang = substr( $server_lang, 0, 2 );

		if ( in_array( $server_lang, ['nb', 'nn', ] ) ) { //phpcs:ignore
			// Case Norwegian
			$server_lang = 'no';
		}

		return apply_filters( 'weglot_redirection_language_exception', $server_lang );
	}

	/**
	 * @since 2.0
	 * @version 2.3.0
	 * @return string
	 */
	public function auto_redirect() {
		if ( ! isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) && ! isset( $_SERVER['HTTP_CF_IPCOUNTRY'] ) ) { //phpcs:ignore
			return;
		}

		if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) { //phpcs:ignore
			$server_lang = substr( sanitize_text_field( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ), 0, apply_filters( 'weglot_number_of_character_for_language', 5 ) );
			$server_lang = $this->language_exception( $server_lang );
		} else {
			if ( isset( $_SERVER['HTTP_CF_IPCOUNTRY'] ) ) { // phpcs:ignore
				// Compatibility Cloudfare
				$server_lang = strtolower( $_SERVER['HTTP_CF_IPCOUNTRY'] ); //phpcs:ignore
			}
		}

		$destination_languages_external = $this->language_services->get_destination_languages_external( $this->request_url_services->is_allowed_private() );
		$browser_language               = $this->language_services->get_language_from_internal( $server_lang );

		if ( isset($browser_language) &&
			in_array( $browser_language->getExternalCode(), $destination_languages_external ) && // phpcs:ignore
			$this->language_services->get_original_language() === $this->request_url_services->get_current_language()
		) {
			$url_auto_redirect = apply_filters( 'weglot_url_auto_redirect', $this->request_url_services->get_weglot_url()->getForLanguage( $browser_language ) );
			header( "Location: $url_auto_redirect", true, 302 );
			exit();
		}

		if ( isset($browser_language) &&
			! in_array( $browser_language->getExternalCode(), $destination_languages_external ) // phpcs:ignore
			&& $browser_language !== $this->language_services->get_original_language()
			&& $this->language_services->get_original_language() === $this->request_url_services->get_current_language()
			&& $this->option_services->get_option( 'autoswitch_fallback' ) !== null
		) {
			$fallback_language = $this->language_services->get_language_from_internal( $this->option_services->get_option( 'autoswitch_fallback' ) );
			$url_auto_redirect = apply_filters( 'weglot_url_auto_redirect', $this->request_url_services->get_weglot_url()->getForLanguage( $fallback_language ));

			header( "Location: $url_auto_redirect", true, 302 );
			exit();
		}
	}

	/**
	 * @since 2.0
	 *
	 * @return void
	 */
	public function verify_no_redirect() {
		if ( strpos( $this->request_url_services->get_weglot_url()->getUrl(), '?no_lredirect=true' ) === false ) {
			return;
		}

		$this->no_redirect = true;
		if ( isset( $_SERVER['REQUEST_URI'] ) ) { // phpcs:ignore
			$_SERVER['REQUEST_URI'] = str_replace('?no_lredirect=true' , '?' , str_replace(
				'?no_lredirect=true&',
				'?',
				$_SERVER['REQUEST_URI'] //phpcs:ignore
			));

			$this->request_url_services->init_weglot_url(); //We reset the URL as we removed the parameter from URL
		}
	}
}


