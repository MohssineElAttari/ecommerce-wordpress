<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Client\Api\LanguageEntry;
use WeglotWP\Helpers\Helper_Flag_Type;
use WeglotWP\Third\Amp\Amp_Service_Weglot;


/**
 * Button services
 *
 * @since 2.0
 */
class Button_Service_Weglot {
	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;
	/**
	 * @var Language_Service_Weglot
	 */
	private $language_services;
	/**
	 * @var Request_Url_Service_Weglot
	 */
	private $request_url_services;
	/**
	 * @var Amp_Service_Weglot
	 */
	private $amp_services;


	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->option_services      = weglot_get_service( 'Option_Service_Weglot' );
		$this->request_url_services = weglot_get_service( 'Request_Url_Service_Weglot' );
		$this->language_services    = weglot_get_service( 'Language_Service_Weglot' );
		$this->amp_services         = weglot_get_service( 'Amp_Service_Weglot' );
	}

	/**
	 * @since 2.3.0
	 * @version 3.0.0
	 * @return string
	 */
	public function get_flag_class() {
		$type_flags = $this->option_services->get_option_button( 'type_flags' );
		$with_flags = $this->option_services->get_option_button( 'with_flags' );

		$flag_class = $with_flags ? 'weglot-flags ' : '';
		$type_flags = Helper_Flag_Type::get_flag_number_with_type( $type_flags );
		if ( '0' !== $type_flags ) {
			$flag_class .= sprintf( 'flag-%s ', $type_flags );
		}

		return apply_filters( 'weglot_get_flag_class', $flag_class );
	}

	/**
	 * @since 2.3.0
	 * @version 3.0.0
	 * @param LanguageEntry $language_entry
	 * @return string
	 */
	public function get_name_with_language_entry( $language_entry ) {
		if ( $this->option_services->get_option_button( 'with_name' ) ) {
			$name = ( $this->option_services->get_option( 'is_fullname' ) ) ? $language_entry->getLocalName() : strtoupper( $language_entry->getExternalCode() );
		} else {
			$name = '';
			remove_filter( 'the_title', 'twenty_twenty_one_post_title' );
		}

		return apply_filters( 'weglot_get_name_with_language_entry', $name, $language_entry );
	}

	/**
	 * @since 2.3.0
	 * @version 3.0.0
	 * @return string
	 */
	public function get_class_dropdown() {
		$is_dropdown = $this->option_services->get_option_button( 'is_dropdown' );
		$class       = $is_dropdown ? 'weglot-dropdown ' : 'weglot-inline ';

		return apply_filters( 'weglot_get_class_dropdown', $class );
	}



	/**
	 * Get html button switcher
	 *
	 * @since 2.0
	 * @version 2.3.1
	 * @return string
	 * @param string $add_class
	 */
	public function get_html( $add_class = '' ) {

		if ( apply_filters( 'weglot_view_button_html', ! $this->request_url_services->is_eligible_url() ) ) {
			return '';
		}

		$weglot_url       = $this->request_url_services->get_weglot_url();
		$amp_regex        = $this->amp_services->get_regex( true );
		$current_language = $this->request_url_services->get_current_language();

		if ( weglot_get_translate_amp_translation() && preg_match( '#' . $amp_regex . '#', $weglot_url->getUrl() ) === 1 ) {
			$add_class .= ' weglot-invert';
		}

		$flag_class  = $this->get_flag_class();
		$class_aside = $this->get_class_dropdown();

		$button_html  = sprintf( '<!--Weglot %s-->', WEGLOT_VERSION );
		$button_html .= sprintf( '<aside data-wg-notranslate class="country-selector %s">', $class_aside . $add_class );

		$name = $this->get_name_with_language_entry( $current_language );

		$uniq_id      = 'wg' . uniqid( strtotime( 'now' ) ) . wp_rand( 1, 1000 );
		$button_html .= sprintf( '<input id="%s" class="weglot_choice" type="checkbox" name="menu"/><label for="%s" class="wgcurrent wg-li weglot-lang weglot-language %s" data-code-language="%s" data-name-language="%s"><span class="wglanguage-name">%s</span></label>', esc_attr( $uniq_id ), esc_attr( $uniq_id ), esc_attr( $flag_class . $current_language->getInternalCode() ), esc_attr( $current_language->getInternalCode() ), esc_html( $name ), esc_html( $name ) );

		$button_html .= '<ul>';

		foreach ( $this->language_services->get_original_and_destination_languages( $this->request_url_services->is_allowed_private() ) as $language ) {

			if ( $language->getInternalCode() === $current_language->getInternalCode() ) {
				continue;
			}

			$link_button = $this->request_url_services->get_weglot_url()->getForLanguage( $language );
			if ( ! $link_button ) {
				continue;
			}

			$button_html .= sprintf( '<li class="wg-li weglot-lang weglot-language %s" data-code-language="%s">', $flag_class . $language->getInternalCode(), $language->getInternalCode() );
			$name         = $this->get_name_with_language_entry( $language );

			if ( $language === $this->language_services->get_original_language() &&
				strpos( $link_button, 'no_lredirect' ) === false && // If not exist
				( is_home() || is_front_page() )
				&& $this->option_services->get_option( 'auto_redirect' )
			) { // Only for homepage
				if( strpos($link_button, '?') !== false ) {
					$link_button = str_replace('?' , '?no_lredirect=true' , $link_button);
				} else {
					$link_button .= '?no_lredirect=true';
				}
			}

			$button_html .= sprintf(
				'<a data-wg-notranslate href="%s">%s</a>',
				esc_url( $link_button ),
				esc_html( $name )
			);

			$button_html .= '</li>';
		}

		$button_html .= '</ul>';

		$button_html .= '</aside>';

		return apply_filters( 'weglot_button_html', $button_html, $add_class );
	}
}
