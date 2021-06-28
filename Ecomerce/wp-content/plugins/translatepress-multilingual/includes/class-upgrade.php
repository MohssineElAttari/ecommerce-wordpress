<?php

/**
 * Class TRP_Upgrade
 *
 * When changing plugin version, do the necessary checks and database upgrades.
 */
class TRP_Upgrade {

	protected $settings;
	protected $db;
	/* @var TRP_Query */
	protected $trp_query;

	/**
	 * TRP_Upgrade constructor.
	 *
	 * @param $settings
	 */
	public function __construct( $settings ){
        global $wpdb;
        $this->db = $wpdb;
		$this->settings = $settings;
	}

	/**
	 * Register Settings subpage for TranslatePress
	 */
	public function register_menu_page(){
		add_submenu_page( 'TRPHidden', 'TranslatePress Remove Duplicate Rows', 'TRPHidden', apply_filters( 'trp_settings_capability', 'manage_options' ), 'trp_remove_duplicate_rows', array($this, 'trp_remove_duplicate_rows') );
		add_submenu_page( 'TRPHidden', 'TranslatePress Update Database', 'TRPHidden', apply_filters( 'trp_settings_capability', 'manage_options' ), 'trp_update_database', array( $this, 'trp_update_database_page' ) );
	}

	/**
	 * When changing plugin version, call certain database upgrade functions.
	 *
	 */
	public function check_for_necessary_updates(){
		$trp = TRP_Translate_Press::get_trp_instance();
		if( ! $this->trp_query ) {
			$this->trp_query = $trp->get_component( 'query' );
		}
		$stored_database_version = get_option('trp_plugin_version');
		if( empty($stored_database_version) ){
			$this->check_if_gettext_tables_exist();
        }else{

            // Updates that require admins to trigger manual update of db because of long duration. Set an option in DB if this is the case.
            $updates = $this->get_updates_details();
            foreach ($updates as $update) {
                if (version_compare($update['version'], $stored_database_version, '>')) {
                    update_option($update['option_name'], 'no');
                }
            }

            // Updates that can be done right way. They should take very little time.
            if ( version_compare( $stored_database_version, '1.3.0', '<=' ) ) {
                $this->trp_query->check_for_block_type_column();
                $this->check_if_gettext_tables_exist();
            }
            if ( version_compare($stored_database_version, '1.5.3', '<=')) {
                $this->add_full_text_index_to_tables();
            }
            if ( version_compare($stored_database_version, '1.6.1', '<=')) {
                $this->upgrade_machine_translation_settings();
            }
            if ( version_compare( $stored_database_version, '1.6.5', '<=' ) ) {
                $this->trp_query->check_for_original_id_column();
                $this->trp_query->check_original_table();
                $this->trp_query->check_original_meta_table();
            }
            if ( version_compare($stored_database_version, '1.9.8', '<=')) {
                $this->set_force_slash_at_end_of_links();
            }
        }

        // don't update the db version unless they are different. Otherwise the query is run on every page load.
        if( version_compare( TRP_PLUGIN_VERSION, $stored_database_version, '!=' ) ){
            update_option( 'trp_plugin_version', TRP_PLUGIN_VERSION );
		}
	}

	/**
	 * Iterates over all languages to call gettext table checking
	 */
	public function check_if_gettext_tables_exist(){
		$trp = TRP_Translate_Press::get_trp_instance();
		if( ! $this->trp_query ) {
			$this->trp_query = $trp->get_component( 'query' );
		}
		if( !empty( $this->settings['translation-languages'] ) ){
			foreach( $this->settings['translation-languages'] as $site_language_code ){
				$this->trp_query->check_gettext_table($site_language_code);
			}
		}
	}

	public function get_updates_details(){
		return apply_filters( 'trp_updates_details',
			array(
				'full_trim_originals_140' => array(
					'version'           => '1.4.0',
					'option_name'       => 'trp_updated_database_full_trim_originals_140',
					'callback'          => array( $this, 'trp_updated_database_full_trim_originals_140' ),
					'batch_size'        => 200
				),
				'gettext_empty_rows_145' => array(
					'version'           => '1.4.5',
					'option_name'       => 'trp_updated_database_gettext_empty_rows_145',
					'callback'          => array( $this,'trp_updated_database_gettext_empty_rows_145'),
					'batch_size'        => 20000
				),
                'original_id_insert_166' => array(
                    'version'           => '1.6.6',
                    'option_name'       => 'trp_updated_database_original_id_insert_166',
                    'callback'          => array( $this,'trp_updated_database_original_id_insert_166'),
                    'batch_size'        => 1000,
                    'message_processing'=> __('Inserting original strings for language %s...', 'translatepress-multilingual' )
                ),
                    'original_id_cleanup_166' => array(
                    'version'           => '1.6.6',
                    'option_name'       => 'trp_updated_database_original_id_cleanup_166',
                    'callback'          => array( $this,'trp_updated_database_original_id_cleanup_166'),
                    'progress_message'  => 'clean',
                    'batch_size'        => 1000,
                    'message_initial'   => '',
                    'message_processing'=> __('Cleaning original strings table for language %s...', 'translatepress-multilingual' )
                ),
                'original_id_update_166' => array(
                    'version'           => '1.6.6',
                    'option_name'       => 'trp_updated_database_original_id_update_166',
                    'callback'          => array( $this,'trp_updated_database_original_id_update_166'),
                    'batch_size'        => 5000,
                    'message_initial'   => '',
                    'message_processing'=> __('Updating original string ids for language %s...', 'translatepress-multilingual' )
                ),
                'regenerate_original_meta' => array(
                    'version'           => '0', // independent of tp version, available only on demand
                    'option_name'       => 'trp_regenerate_original_meta_table',
                    'callback'          => array( $this,'trp_regenerate_original_meta_table'),
                    'batch_size'        => 200,
                    'message_initial'   => '',
                    'message_processing'=> __('Regenerating original meta table for language %s...', 'translatepress-multilingual' )
                ),
                'clean_original_meta' => array(
                    'version'           => '0', // independent of tp version, available only on demand
                    'option_name'       => 'trp_clean_original_meta_table',
                    'callback'          => array( $this,'trp_clean_original_meta_table'),
                    'batch_size'        => 20000,
                    'message_initial'   => '',
                    'message_processing'=> __('Cleaning original meta table for language %s...', 'translatepress-multilingual' )
                )
			)
		);
	}

	/**
	 * Show admin notice about updating database
	 */
	public function show_admin_notice(){
		if ( ( isset( $_GET[ 'page'] ) && $_GET['page'] == 'trp_update_database' ) ){
			return;
		}
		$updates_needed = $this->get_updates_details();
		foreach( $updates_needed as $update ){
			$option = get_option( $update['option_name'], 'is not set' );
			if ( $option === 'no' ){
				add_action( 'admin_notices', array( $this, 'admin_notice_update_database' ) );
				break;
			}
		}
	}

	/**
	 * Print admin notice message
	 */
	public function admin_notice_update_database() {

		$url = add_query_arg( array(
			'page'                      => 'trp_update_database',
		), site_url('wp-admin/admin.php') );

		// maybe change notice color to blue #28B1FF
		$html = '<div id="message" class="updated">';
		$html .= '<p><strong>' . esc_html__( 'TranslatePress data update', 'translatepress-multilingual' ) . '</strong> &#8211; ' . esc_html__( 'We need to update your translations database to the latest version.', 'translatepress-multilingual' ) . '</p>';
		$html .= '<p class="submit"><a href="' . esc_url( $url ) . '" onclick="return confirm( \'' . __( 'IMPORTANT: It is strongly recommended to first backup the database!\nAre you sure you want to continue?', 'translatepress-multilingual' ) . '\');" class="button-primary">' . esc_html__( 'Run the updater', 'translatepress-multilingual' ) . '</a></p>';
		$html .= '</div>';
		echo $html;
	}

	public function trp_update_database_page(){
		require_once TRP_PLUGIN_DIR . 'partials/trp-update-database.php';
	}

	/**
	 * Call all functions to update database
	 *
	 * hooked to wp_ajax_trp_update_database
	 */
	public function trp_update_database(){
		if ( ! current_user_can( apply_filters('trp_update_database_capability', 'manage_options') ) ){
			$this->stop_and_print_error( __('Update aborted! Your user account doesn\'t have the capability to perform database updates.', 'translatepress-multilingual' ) );
		}

		$nonce = wp_verify_nonce( $_REQUEST['trp_updb_nonce'], 'tpupdatedatabase' );
		if ( $nonce === false ){
			$this->stop_and_print_error( __('Update aborted! Invalid nonce.', 'translatepress-multilingual' ) );
		}

		$request = array();
		$request['progress_message'] = '';
		$updates_needed = $this->get_updates_details();
		if ( empty ( $_REQUEST['trp_updb_action'] ) ){
			foreach( $updates_needed as $update_action_key => $update ) {
				$option = get_option( $update['option_name'], 'is not set' );
				if ( $option === 'no' ) {
					$_REQUEST['trp_updb_action'] = $update_action_key;
					break;
				}
			}
			if ( empty ( $_REQUEST['trp_updb_action'] ) ){
				$back_to_settings_button = '<p><a href="' . site_url('wp-admin/options-general.php?page=translate-press') . '"> <input type="button" value="' . __('Back to TranslatePress Settings', 'translatepress-multilingual' ) . '" class="button-primary"></a></p>';
				// finished successfully
				echo json_encode( array(
					'trp_update_completed' => 'yes',
					'progress_message'  => '<p><strong>' . __('Successfully updated database!', 'translatepress-multilingual' ) . '</strong></p>' . $back_to_settings_button
				));
				wp_die();
			}else{
				$_REQUEST['trp_updb_lang'] = $this->settings['translation-languages'][0];
				$_REQUEST['trp_updb_batch'] = 0;

                $update_message_initial = isset( $updates_needed[$_REQUEST['trp_updb_action']]['message_initial'] ) ?
                                            $updates_needed[$_REQUEST['trp_updb_action']]['message_initial']
                                            : __('Updating database to version %s+', 'translatepress-multilingual' );

                $update_message_processing = isset( $updates_needed[$_REQUEST['trp_updb_action']]['message_processing'] ) ?
                                                $updates_needed[$_REQUEST['trp_updb_action']]['message_processing']
                                                : __('Processing table for language %s...', 'translatepress-multilingual' );

				$request['progress_message'] .= '<p>' . sprintf( $update_message_initial,  $updates_needed[ $_REQUEST['trp_updb_action'] ]['version'] ) . '</p>';
				$request['progress_message'] .= sprintf( $update_message_processing, $_REQUEST['trp_updb_lang'] );
			}
		}else{
			if ( !isset( $updates_needed[ $_REQUEST['trp_updb_action'] ] ) ){
				$this->stop_and_print_error( __('Update aborted! Incorrect action.', 'translatepress-multilingual' ) );
			}
			if ( !in_array( $_REQUEST['trp_updb_lang'], $this->settings['translation-languages'] ) ) {
				$this->stop_and_print_error( __('Update aborted! Incorrect language code.', 'translatepress-multilingual' ) );
			}
		}

		$request['trp_updb_action'] = $_REQUEST['trp_updb_action'];
		if ( !empty( $_REQUEST['trp_updb_batch'] ) && (int) $_REQUEST['trp_updb_batch'] > 0 ) {
			$get_batch = (int)$_REQUEST['trp_updb_batch'];
		}else{
			$get_batch = 0;
		}

		$request['trp_updb_batch'] = 0;
		$update_details = $updates_needed[$_REQUEST['trp_updb_action']];
		$batch_size = apply_filters( 'trp_updb_batch_size', $update_details['batch_size'], $_REQUEST['trp_updb_action'], $update_details );
		$language_code = $_REQUEST['trp_updb_lang'];

		if ( ! $this->trp_query ) {
			$trp = TRP_Translate_Press::get_trp_instance();
			/* @var TRP_Query */
			$this->trp_query = $trp->get_component( 'query' );
		}

		$start_time = microtime(true);
		$duration = 0;
		while( $duration < 2 ){
			$inferior_limit = $batch_size * $get_batch;
			$finished_with_language = call_user_func( $update_details['callback'], $language_code, $inferior_limit, $batch_size );

			if ( $finished_with_language ) {
				break;
			}else {
				$get_batch = $get_batch + 1;
			}
			$stop_time = microtime( true );
			$duration = $stop_time - $start_time;
		}
		if ( ! $finished_with_language ) {
			$request['trp_updb_batch'] = $get_batch;
		}


		if ( $finished_with_language ) {
			// finished with the current language
			$index = array_search( $language_code, $this->settings['translation-languages'] );
			if ( isset ( $this->settings['translation-languages'][ $index + 1 ] ) ) {
				// next language code in array
				$request['trp_updb_lang'] = $this->settings['translation-languages'][ $index + 1 ];
				$request['progress_message'] .= __(' done.', 'translatepress-multilingual' ) . '</br>';
                $update_message_processing = isset( $updates_needed[$_REQUEST['trp_updb_action']]['message_processing'] ) ?
                                                $updates_needed[$_REQUEST['trp_updb_action']]['message_processing']
                                                : __('Processing table for language %s...', 'translatepress-multilingual' );
				$request['progress_message'] .= '</br>' . sprintf( $update_message_processing,  $request['trp_updb_lang'] );
			} else {
				// finish action due to completing all the translation languages
				$request['progress_message'] .= __(' done.', 'translatepress-multilingual' ) . '</br>';
				$request['trp_updb_lang'] = '';
				// this will stop showing the admin notice
				update_option( $update_details['option_name'], 'yes' );
				$request['trp_updb_action'] = '';
			}
		}else{
			$request['trp_updb_lang'] = $language_code;
            $request['progress_message'] = '.';
		}

		$query_arguments = array(
			'action'                    => 'trp_update_database',
			'trp_updb_action'           => $request['trp_updb_action'],
			'trp_updb_lang'             => $request['trp_updb_lang'],
			'trp_updb_batch'            => $request['trp_updb_batch'],
			'trp_updb_nonce'            => wp_create_nonce('tpupdatedatabase'),
			'trp_update_completed'      => 'no',
			'progress_message'          => $request['progress_message']
		);
		echo( json_encode( $query_arguments ));
		wp_die();
	}

	public function stop_and_print_error( $error_message ){
		$back_to_settings_button = '<p><a href="' . site_url('wp-admin/options-general.php?page=translate-press') . '"> <input type="button" value="' . __('Back to TranslatePress Settings', 'translatepress-multilingual' ) . '" class="button-primary"></a></p>';
		$query_arguments = array(
			'trp_update_completed'      => 'yes',
			'progress_message'          => '<p><strong>' . $error_message . '</strong></strong></p>' . $back_to_settings_button
		);
		echo( json_encode( $query_arguments ));
		wp_die();
	}

	/**
	 * Get all originals from the table, trim them and update originals back into table
	 *
	 * @param string $language_code     Language code of the table
	 * @param int $inferior_limit       Omit first X rows
	 * @param int $batch_size           How many rows to query
	 *
	 * @return bool
	 */
	public function trp_updated_database_full_trim_originals_140( $language_code, $inferior_limit, $batch_size ){
		if ( ! $this->trp_query ) {
			$trp = TRP_Translate_Press::get_trp_instance();
			/* @var TRP_Query */
			$this->trp_query = $trp->get_component( 'query' );
		}
		if ( $language_code == $this->settings['default-language']){
			// default language doesn't have a dictionary table
			return true;
		}
		$strings = $this->trp_query->get_rows_from_location( $language_code, $inferior_limit, $batch_size, array( 'id', 'original' ) );
		if ( count( $strings ) == 0 ) {
			return true;
		}
		foreach( $strings as $key => $string ){
			$strings[$key]['original'] = trp_full_trim( $strings[ $key ]['original'] );
		}

		// overwrite original only
		$this->trp_query->update_strings( $strings, $language_code, array( 'id', 'original' ) );
		return false;
	}

	/**
	 * Delete all empty gettext rows
	 *
	 * @param string $language_code     Language code of the table
	 * @param int $inferior_limit       Omit first X rows
	 * @param int $batch_size           How many rows to query
	 *
	 * @return bool
	 */
	public function trp_updated_database_gettext_empty_rows_145( $language_code, $inferior_limit, $batch_size ){
		if ( ! $this->trp_query ) {
			$trp = TRP_Translate_Press::get_trp_instance();
			/* @var TRP_Query */
			$this->trp_query = $trp->get_component( 'query' );
		}
		$rows_affected = $this->trp_query->delete_empty_gettext_strings( $language_code, $batch_size );
		if ( $rows_affected > 0 ){
			return false;
		}else{
			return true;
		}
	}

	/**
     * Normalize original ids for all dictionary entries
     *
     * @param string $language_code     Language code of the table
     * @param int $inferior_limit       Omit first X rows
     * @param int $batch_size           How many rows to query
     *
     * @return bool
     */
    public function trp_updated_database_original_id_insert_166( $language_code, $inferior_limit, $batch_size ){
        if ( ! $this->trp_query ) {
            $trp = TRP_Translate_Press::get_trp_instance();
            /* @var TRP_Query */
            $this->trp_query = $trp->get_component( 'query' );
        }

        $rows_inserted = $this->trp_query->original_ids_insert( $language_code, $inferior_limit, $batch_size );

        if ( $rows_inserted > 0 ){
            return false;
        }else{
            return true;
        }
    }

    public function trp_updated_database_original_id_cleanup_166( $language_code, $inferior_limit, $batch_size ){
    if ( ! $this->trp_query ) {
        $trp = TRP_Translate_Press::get_trp_instance();
        /* @var TRP_Query */
        $this->trp_query = $trp->get_component( 'query' );
    }

    $this->trp_query->original_ids_cleanup();

    return true;
}

    /**
 * Normalize original ids for all dictionary entries
 *
 * @param string $language_code     Language code of the table
 * @param int $inferior_limit       Omit first X rows
 * @param int $batch_size           How many rows to query
 *
 * @return bool
 */
    public function trp_updated_database_original_id_update_166( $language_code, $inferior_limit, $batch_size ){
        if ( ! $this->trp_query ) {
            $trp = TRP_Translate_Press::get_trp_instance();
            /* @var TRP_Query */
            $this->trp_query = $trp->get_component( 'query' );
        }

        $rows_updated = $this->trp_query->original_ids_reindex( $language_code, $inferior_limit, $batch_size );

        if ( $rows_updated > 0 ){
            return false;
        }else {
            return true;
        }
    }

	/**
	 * Remove duplicate rows from DB for trp_dictionary tables.
	 * Removes untranslated strings if there is a translated version.
	 *
	 * Iterates over languages. Each language is iterated in batches of 10 000
	 *
	 * Not accessible from anywhere else
	 * http://example.com/wp-admin/admin.php?page=trp_remove_duplicate_rows
	 */
	public function trp_remove_duplicate_rows(){
		if ( ! current_user_can( 'manage_options' ) ){
			return;
		}
		// prepare page structure
		require_once TRP_PLUGIN_DIR . 'partials/trp-remove-duplicate-rows.php';

        if ( isset( $_GET['trp_rm_duplicates_original_strings'] ) ){
            $this->trp_remove_duplicate_original_strings();
            exit;
        }

		if ( empty( $_GET['trp_rm_duplicates'] ) ){
			// iteration not started
			return;
		}
		if ( sanitize_text_field( $_GET['trp_rm_duplicates'] ) === 'done' ){
			// iteration finished
			echo esc_html__('Done.', 'translatepress-multilingual' ) . '<br><br><a href="' . esc_url( site_url('wp-admin/admin.php?page=trp_advanced_page') ) . '"> <input type="button" value="' . esc_attr__('Back to TranslatePress Settings', 'translatepress-multilingual' ) . '" class="button-primary"></a>';
			return;
		}
		$nonce = wp_verify_nonce( $_GET['trp_rm_nonce'], 'tpremoveduplicaterows' );
		if ( $nonce === false ){
			echo esc_html__('Invalid nonce.', 'translatepress-multilingual' ) . '<br><br><a href="' . esc_url( site_url('wp-admin/admin.php?page=trp_advanced_page') ) . '"> <input type="button" value="' . esc_attr__('Back to TranslatePress Settings', 'translatepress-multilingual' ) . '" class="button-primary"></a>';
			return;
		}

		$next_get_batch = 1;
		$batch_size = apply_filters( 'trp_rm_duplicate_batch_size', 10000 );
		if ( !empty( $_GET['trp_rm_batch_size'] )  && (int) $_GET['trp_rm_batch'] > 0 ){
			$batch_size = (int) $_GET['trp_rm_batch_size'];
		}
		if ( in_array( sanitize_text_field( $_GET['trp_rm_duplicates'] ), $this->settings['translation-languages'] ) ) {
			// language code found in array
			$language_code = sanitize_text_field( $_GET['trp_rm_duplicates'] );
			// skip default language since it doesn't have a table
			if ( ( isset($_GET['trp_rm_duplicates_dictionary']) && $language_code != $this->settings['default-language'] ) || isset($_GET['trp_rm_duplicates_gettext']) ) {
				if ( ! $this->trp_query ) {
					$trp = TRP_Translate_Press::get_trp_instance();
					/* @var TRP_Query */
					$this->trp_query = $trp->get_component( 'query' );
				}
                if (isset($_GET['trp_rm_duplicates_dictionary']) )
				    $table_name = $this->trp_query->get_table_name( $language_code );
                else if (isset($_GET['trp_rm_duplicates_gettext']) )
                    $table_name = $this->trp_query->get_gettext_table_name( $language_code );

				echo '<div>' . sprintf( wp_kses( __( 'Querying table <strong>%s</strong>', 'translatepress-multilingual' ), [ 'strong' => [] ] ), $table_name ) . '</div>';

				$last_id = $this->trp_query->get_last_id( $table_name );
				if ( !empty( $_GET['trp_rm_batch'] ) && (int) $_GET['trp_rm_batch'] > 0 ) {
					$get_batch = (int)$_GET['trp_rm_batch'];
				}else{
					$get_batch = 1;
				}
				$batch = $batch_size * $get_batch;

				/* Execute this query only for string with ID < $batch. This ensures that the query is fast.
				 * Deleting duplicate rows for the first 20k rows might take too long.
				 * As a solution we are deleting the duplicates of the first 10k rows ( 1 to 10 000),
				 * then delete duplicates of the first 20k rows( 1 to 20 000, not 10 000 to 20 000 because we there could still be duplicates).
				 * Same goes for higher numbers.
				 */
                if (isset($_GET['trp_rm_duplicates_dictionary']) ) {
                    $result1 = $this->trp_query->remove_duplicate_rows_in_dictionary_table($language_code, $batch);
                    $result2 = 0;
                    if ($batch > $last_id) {
                        // execute this query only when we do not have any more duplicate rows
                        $result2 = $this->trp_query->remove_untranslated_strings_if_translation_available($language_code);
                    } else {
                        $next_get_batch = $get_batch + 1;
                    }
                }
                else if (isset($_GET['trp_rm_duplicates_gettext']) ){
                    $result1 = $this->trp_query->remove_duplicate_rows_in_gettext_table($language_code, $batch);
                    $result2 = 0;
                    if ($batch > $last_id) {
                        // execute this query only when we do not have any more duplicate rows
                        $result2 = $this->trp_query->remove_untranslated_strings_if_gettext_translation_available($language_code);
                    } else {
                        $next_get_batch = $get_batch + 1;
                    }
                }

				if ( ( $result1 === false ) || ( $result2 === false ) ) {
					// if query outputted error do not continue iteration
					return;
				}else{
					$result = $result1 + $result2;
					echo '<div>' . sprintf( esc_html__( '%s duplicates removed', 'translatepress-multilingual' ), $result ) . '</div>';
				}
			}
			if ( $next_get_batch == 1 ) {
				// finished with the current language
				$index = array_search( $language_code, $this->settings['translation-languages'] );
				if ( isset ( $this->settings['translation-languages'][ $index + 1 ] ) ) {
					// next language code in array
					$next_language = $this->settings['translation-languages'][ $index + 1 ];
				} else {
					// finish iteration due to completing all the translation languages
					$next_language = 'done';
				}
			}else{
				$next_language = $language_code;
			}
		}else{
			// finish iteration due to incorrect translation language
			$next_language = 'done';
		}

		// construct and redirect to next url
        $next_page_args =  array(
            'page'                      => 'trp_remove_duplicate_rows',
            'trp_rm_duplicates'         => $next_language,
            'trp_rm_batch'              => $next_get_batch,
            'trp_rm_batch_size'         => $batch_size,
            'trp_rm_nonce'              => wp_create_nonce('tpremoveduplicaterows')
        );

        if (isset($_GET['trp_rm_duplicates_dictionary']) )
            $next_page_args['trp_rm_duplicates_dictionary'] = true;
        else if (isset($_GET['trp_rm_duplicates_gettext']) ){
            $next_page_args['trp_rm_duplicates_gettext'] = true;
        }

		$url = add_query_arg( $next_page_args, site_url('wp-admin/admin.php') );
		echo '<meta http-equiv="refresh" content="0; url=' . esc_url( $url ) . '" />';
		echo '<br> ' . esc_html__( 'If the page does not redirect automatically', 'translatepress-multilingual' ) . ' <a href="' . esc_url( $url ) . '" >' . esc_html__( 'click here', 'translatepress-multilingual' ) . '.</a>';
		exit;
	}

	public function enqueue_update_script( $hook ) {
		if ( $hook === 'admin_page_trp_update_database' ) {
			wp_enqueue_script( 'trp-update-database', TRP_PLUGIN_URL . 'assets/js/trp-update-database.js', array(
				'jquery',
			), TRP_PLUGIN_VERSION );
		}

		wp_localize_script( 'trp-update-database', 'trp_updb_localized ', array(
			'admin_ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce('tpupdatedatabase')
		) );
	}

    /**
     * Add full text index on the dictionary and gettext tables.
     * Gets executed once after update.
     */
	private function add_full_text_index_to_tables(){
	    $table_names = $this->trp_query->get_all_table_names('', array());
        $gettext_table_names = $this->trp_query->get_all_gettext_table_names();

        foreach (array_merge($table_names, $gettext_table_names) as $table_name){
            $possible_index = "SHOW INDEX FROM {$table_name} WHERE Key_name = 'original_fulltext';";
            if ($this->db->query($possible_index) === 1){
                continue;
            };

            $sql_index = "CREATE FULLTEXT INDEX original_fulltext ON `" . $table_name . "`(original);";
            $this->db->query( $sql_index );
        }
    }

    /**
     * Moving some settings from trp_settings option to trp_machine_translation_settings
     *
     * Upgrade settings from TP version 1.5.8 or earlier to 1.6.2
     */
    private function upgrade_machine_translation_settings(){
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_settings = $trp->get_component('settings' );
        $machine_translation_settings = get_option( 'trp_machine_translation_settings', false );

        $default_machine_translation_settings = $trp_settings->get_default_trp_machine_translation_settings();

        if ( $machine_translation_settings == false ) {
            // 1.5.8 did not have any machine_settings so port g-translate-key and g-translate settings if exists
            $machine_translation_settings = $default_machine_translation_settings;
            // move the old API key option
            if (!empty($this->settings['g-translate-key'] ) ) {
                $machine_translation_settings['google-translate-key'] = $this->settings['g-translate-key'];
            }

            // enable machine translation if it was activated before
            if (!empty($this->settings['g-translate']) && $this->settings['g-translate'] == 'yes'){
                $machine_translation_settings['machine-translation'] = 'yes';
            }
            update_option('trp_machine_translation_settings', $machine_translation_settings);
        }else{
            // targeting 1.5.9 to 1.6.1 where incomplete machine-translation settings may have resulted
            $machine_translation_settings = array_merge( $default_machine_translation_settings, $machine_translation_settings );
            update_option('trp_machine_translation_settings', $machine_translation_settings);
        }
    }

    /**
     *
     */
    private function set_force_slash_at_end_of_links(){
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_settings = $trp->get_component('settings' );
        $settings = $trp_settings->get_settings();

        if( !empty( $settings['trp_advanced_settings'] ) && !isset( $settings['trp_advanced_settings']['force_slash_at_end_of_links'] ) ){
            $advanced_settings = $settings['trp_advanced_settings'];
            $advanced_settings['force_slash_at_end_of_links'] = 'yes';
            update_option('trp_advanced_settings', $advanced_settings );
        }

    }


    public function trp_remove_duplicate_original_strings(){
        if ( ! $this->trp_query ) {
            $trp = TRP_Translate_Press::get_trp_instance();
            /* @var TRP_Query */
            $this->trp_query = $trp->get_component( 'query' );
        }
        $this->trp_query->rename_originals_table();
        $this->trp_query->check_original_table();

        echo '<br> ' . esc_html__( 'Recreated original strings table.', 'translatepress-multilingual' );

        update_option( 'trp_updated_database_original_id_insert_166', 'no' );
        update_option( 'trp_updated_database_original_id_cleanup_166', 'no' );
        update_option( 'trp_updated_database_original_id_update_166', 'no' );

        update_option( 'trp_regenerate_original_meta_table', 'no' );
        update_option( 'trp_clean_original_meta_table', 'no' );

        $url = add_query_arg( array('page'=>'trp_update_database'), site_url('wp-admin/admin.php') );
        echo '<meta http-equiv="refresh" content="0; url=' . esc_url( $url ) . '" />';
        echo '<br> ' . esc_html__( 'If the page does not redirect automatically', 'translatepress-multilingual' ) . ' <a href="' . esc_url( $url ) . '" >' . esc_html__( 'click here', 'translatepress-multilingual' ) . '.</a>';
        exit;
    }

    public function trp_regenerate_original_meta_table($language_code, $inferior_limit, $batch_size ){

        if ( $language_code != $this->settings['default-language']) {
            // perform regeneration of original meta table only once
            return true;
        }
        if ( ! $this->trp_query ) {
            $trp = TRP_Translate_Press::get_trp_instance();
            /* @var TRP_Query */
            $this->trp_query = $trp->get_component( 'query' );
        }
        $this->trp_query->regenerate_original_meta_table($inferior_limit, $batch_size);

        $last_id = $this->db->get_var("SELECT MAX(meta_id) FROM " .  $this->trp_query->get_table_name_for_original_meta() );
        if ( $last_id < $inferior_limit ){
            // reached end of table
            return true;
        }else{
            // not done. get another batch
            return false;
        }
    }

    public function trp_clean_original_meta_table($language_code, $inferior_limit, $batch_size){
        if ( $language_code != $this->settings['default-language']) {
            // perform regeneration of original meta table only once
            return true;
        }
        if ( ! $this->trp_query ) {
            $trp = TRP_Translate_Press::get_trp_instance();
            /* @var TRP_Query */
            $this->trp_query = $trp->get_component( 'query' );
        }
        $rows_affected = $this->trp_query->clean_original_meta( $batch_size );
        if ( $rows_affected > 0 ){
            return false;
        }else{
            $old_originals_table = get_option( 'trp_original_strings_table_for_recovery', '' );
            if ( !empty ( $old_originals_table) && strpos($old_originals_table, 'trp_original_strings1') !== false ) {
                delete_option('trp_original_strings_table_for_recovery');
                $this->trp_query->drop_table( $old_originals_table );
            }
            return true;
        }
    }
}
