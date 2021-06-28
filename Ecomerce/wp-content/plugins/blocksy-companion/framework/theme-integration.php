<?php

namespace Blocksy;

class ThemeIntegration {
	public function __construct() {
		add_action(
			'wp_ajax_blc_implement_user_registration',
			[$this, 'blc_implement_user_registration']
		);

		add_action(
			'wp_ajax_nopriv_blc_implement_user_registration',
			[$this, 'blc_implement_user_registration']
		);

		add_filter('blocksy:frontend:dynamic-js-chunks', function ($chunks) {
			$chunks[] = [
				'id' => 'blocksy_account',
				'selector' => implode(', ', [
					'.ct-header-account[data-state="out"][href*="account-modal"]',
					'.must-log-in a'
				]),
				'url' => blc_call_fn(
					[
						'fn' => 'blocksy_cdn_url',
						'default' => BLOCKSY_URL . 'static/bundle/account.js'
					],
					BLOCKSY_URL . 'static/bundle/account.js'
				),
				'trigger' => 'click'
			];

			$chunks[] = [
				'id' => 'blocksy_dark_mode',
				'selector' => '[data-id="dark-mode-switcher"]',
				'url' => blc_call_fn(
					[
						'fn' => 'blocksy_cdn_url',
						'default' => BLOCKSY_URL . 'static/bundle/dark-mode.js'
					],
					BLOCKSY_URL . 'static/bundle/dark-mode.js'
				),
				'trigger' => 'click'
			];

			$chunks[] = [
				'id' => 'blocksy_sticky_header',
				'selector' => 'header [data-sticky]',
				'url' => blc_call_fn(
					[
						'fn' => 'blocksy_cdn_url',
						'default' => BLOCKSY_URL . 'static/bundle/sticky.js'
					],
					BLOCKSY_URL . 'static/bundle/sticky.js'
				),
			];

			return $chunks;
		});

		add_shortcode('blocksy_posts', function ($args, $content) {
			$args = wp_parse_args(
				$args,
				[
					'post_type' => 'post',
					'limit' => 5,

					// post_date | comment_count
					'orderby' => 'post_date',
					'order' => 'DESC',

					// yes | no
					'has_pagination' => 'yes',

					// yes | no
					'ignore_sticky_posts' => 'no',

					'term_ids' => null,
					'exclude_term_ids' => null,
					'post_ids' => null,

					// archive | slider
					'view' => 'archive',
					'slider_image_ratio' => '2/1',
					'slider_autoplay' => 'no',
				]
			);

			$file_path = dirname(__FILE__) . '/views/blocksy-posts.php';

			return blc_call_fn(
				['fn' => 'blocksy_render_view'],
				$file_path,
				[
					'args' => $args
				]
			);
		});

		add_action('wp_ajax_blocksy_conditions_get_all_taxonomies', function () {
			if (! current_user_can('manage_options')) {
				wp_send_json_error();
			}

			$cpts = blocksy_manager()->post_types->get_supported_post_types();

			$cpts[] = 'post';
			$cpts[] = 'page';
			$cpts[] = 'product';

			$taxonomies = [];

			foreach ($cpts as $cpt) {
				$taxonomies = array_merge($taxonomies, array_values(array_diff(
					get_object_taxonomies($cpt),
					['post_format']
				)));
			}

			$terms = [];

			foreach ($taxonomies as $taxonomy) {
				$taxonomy_object = get_taxonomy($taxonomy);

				if (! $taxonomy_object->public) {
					continue;
				}

				$local_terms = array_map(function ($tax) {
					return [
						'id' => $tax->term_id,
						'name' => $tax->name
					];
				}, get_terms(['taxonomy' => $taxonomy, 'lang' => '']));

				if (empty($local_terms)) {
					continue;
				}

				$terms[] = [
					'id' => $taxonomy,
					'name' => $taxonomy,
					'group' => get_taxonomy($taxonomy)->label
				];

				$terms = array_merge($terms, $local_terms);
			}

			$languages = [];

			if (function_exists('blocksy_get_current_language')) {
				$languages = blocksy_get_all_i18n_languages();
			}

			wp_send_json_success([
				'taxonomies' => $terms,
				'languages' => $languages
			]);
		});

		add_action('wp_ajax_blocksy_conditions_get_all_posts', function () {
			if (! current_user_can('manage_options')) {
				wp_send_json_error();
			}

			$maybe_input = json_decode(file_get_contents('php://input'), true);

			if (! $maybe_input) {
				wp_send_json_error();
			}

			if (! isset($maybe_input['post_type'])) {
				wp_send_json_error();
			}

			$query_args = [
				'posts_per_page' => 10,
				'post_type' => $maybe_input['post_type'],
				'suppress_filters' => true,
				'lang' => ''
			];

			if (
				isset($maybe_input['search_query'])
				&&
				! empty($maybe_input['search_query'])
			) {
				if (intval($maybe_input['search_query'])) {
					$query_args['p'] = intval($maybe_input['search_query']);
				} else {
					$query_args['s'] = $maybe_input['search_query'];
				}
			}

			if (strpos($query_args['post_type'], 'ct_cpt') !== false) {
				$query_args['post_type'] = array_diff(
					get_post_types(['public' => true]),
					['post', 'page', 'attachment', 'ct_content_block']
				);
			}

			$query = new \WP_Query($query_args);

			$posts_result = $query->posts;

			if (isset($maybe_input['alsoInclude'])) {
				$maybe_post = get_post($maybe_input['alsoInclude'], 'display');

				if ($maybe_post) {
					$posts_result[] = $maybe_post;
				}
			}

			wp_send_json_success([
				'posts' => $posts_result
			]);
		});

		add_filter(
			'blocksy_add_menu_page',
			function ($res, $options) {
				add_menu_page(
					$options['title'],
					$options['menu-title'],
					$options['permision'],
					$options['top-level-handle'],
					$options['callback'],
					$options['icon-url'],
					2
				);

				return true;
			},
			10, 2
		);

		add_action('rest_api_init', function () {
			return;

			register_rest_field('post', 'images', [
				'get_callback' => function () {
					return wp_prepare_attachment_for_js($object->id);
				},
				'update_callback' => null,
				'schema' => null,
			]);
		});

		add_filter(
			'user_contactmethods',
			function ( $field ) {
				$fields['facebook'] = __( 'Facebook', 'blc' );
				$fields['twitter'] = __( 'Twitter', 'blc' );
				$fields['linkedin'] = __( 'LinkedIn', 'blc' );
				$fields['dribbble'] = __( 'Dribbble', 'blc' );
				$fields['instagram'] = __( 'Instagram', 'blc' );
				$fields['pinterest'] = __( 'Pinterest', 'blc' );
				$fields['wordpress'] = __( 'WordPress', 'blc' );
				$fields['github'] = __( 'GitHub', 'blc' );
				$fields['medium'] = __( 'Medium', 'blc' );
				$fields['youtube'] = __( 'YouTube', 'blc' );
				$fields['vimeo'] = __( 'Vimeo', 'blc' );
				$fields['vkontakte'] = __( 'VKontakte', 'blc' );
				$fields['odnoklassniki'] = __( 'Odnoklassniki', 'blc' );
				$fields['tiktok'] = __( 'TikTok', 'blc' );

				return $fields;
			}
		);

		add_filter(
			'wp_check_filetype_and_ext',
			function ($data=null, $file=null, $filename=null, $mimes=null) {
				if (strpos($filename, '.svg') !== false) {
					$data['type'] = 'image/svg+xml';
					$data['ext'] = 'svg';
				}

				return $data;
			},
			75, 4
		);

		add_filter('upload_mimes', function ($mimes) {
			$mimes['svg'] = 'image/svg+xml';
			return $mimes;
		});

		add_filter('wp_get_attachment_image_attributes', function ($attr, $attachment, $size = 'thumbnail') {
			if (! isset($attachment->ID)) {
				return $attr;
			}

			$mime = get_post_mime_type($attachment->ID);

			if ('image/svg+xml' === $mime) {
				$default_height = 100;
				$default_width  = 100;

				$dimensions = $this->svg_dimensions(get_attached_file($attachment->ID));

				if ($dimensions) {
					$default_height = $dimensions['height'];
					$default_width = $dimensions['width'];
				}

				$attr['height'] = $default_height;
				$attr['width'] = $default_width;
			}

			return $attr;
		}, 10, 3);

		add_filter('blocksy_changelogs_list', function ($changelogs) {
			$changelog = null;
			$access_type = get_filesystem_method();

			if ($access_type === 'direct') {
				$creds = request_filesystem_credentials(
					site_url() . '/wp-admin/',
					'', false, false,
					[]
				);

				if ( WP_Filesystem($creds) ) {
					global $wp_filesystem;

					$readme = $wp_filesystem->get_contents(
						BLOCKSY_PATH . '/readme.txt'
					);

					if ($readme) {
						$readme = explode('== Changelog ==', $readme);

						if (isset($readme[1])) {
							$changelogs[] = [
								'title' => __('Companion', 'blc'),
								'changelog' => trim($readme[1])
							];
						}
					}

					if (
						blc_fs()->is__premium_only()
						&&
						BLOCKSY_PATH . '/framework/premium/changelog.txt'
					) {
						$pro_changelog = $wp_filesystem->get_contents(
							BLOCKSY_PATH . '/framework/premium/changelog.txt'
						);

						$changelogs[] = [
							'title' => __('PRO', 'blc'),
							'changelog' => trim($pro_changelog)
						];
					}
				}
			}

			return $changelogs;
		});

		add_action('wp_enqueue_scripts', function () {
			if (! function_exists('get_plugin_data')){
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			$data = get_plugin_data(BLOCKSY__FILE__);

			if (is_admin()) return;

			/*
			wp_enqueue_style(
				'blocksy-companion-styles',
				BLOCKSY_URL . 'static/bundle/min.css',
				['ct-main-styles'],
				$data['Version']
			);
			 */
		});

		add_action(
			'customize_preview_init',
			function () {
				$data = get_plugin_data(BLOCKSY__FILE__);

				wp_enqueue_script(
					'blocksy-companion-sync-scripts',
					BLOCKSY_URL . 'static/bundle/sync.js',
					['customize-preview', 'ct-scripts', 'wp-date', 'ct-scripts', 'ct-customizer'],
					$data['Version'],
					true
				);
			}
		);

		if (get_theme_mod('emoji_scripts', 'no') !== 'yes') {
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('admin_print_scripts', 'print_emoji_detection_script');
			remove_action('wp_print_styles', 'print_emoji_styles');
			remove_action('admin_print_styles', 'print_emoji_styles');
			remove_filter('the_content_feed', 'wp_staticize_emoji');
			remove_filter('comment_text_rss', 'wp_staticize_emoji');
			remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

			add_filter('tiny_mce_plugins', function ($plugins) {
				if (is_array($plugins)) {
					return array_diff($plugins, array('wpemoji'));
				} else {
					return array();
				}
			});

			add_filter('wp_resource_hints', function ($urls, $relation_type) {
				if ('dns-prefetch' === $relation_type) {
					/** This filter is documented in wp-includes/formatting.php */
					$emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

					$urls = array_diff($urls, array($emoji_svg_url));
				}

				return $urls;
			}, 10, 2);
		}
	}

	protected function svg_dimensions($svg) {
		$svg = @simplexml_load_file($svg);
		$width = 0;
		$height = 0;

		if ($svg) {
			$attributes = $svg->attributes();

			if (
				isset($attributes->width, $attributes->height)
				&&
				is_numeric($attributes->width)
				&&
				is_numeric($attributes->height)
			) {
				$width = floatval($attributes->width);
				$height = floatval($attributes->height);
			} elseif (isset($attributes->viewBox)) {
				$sizes = explode(' ', $attributes->viewBox);

				if (isset($sizes[2], $sizes[3])) {
					$width = floatval($sizes[2]);
					$height = floatval($sizes[3]);
				}
			} else {
				return false;
			}
		}

		return array(
			'width' => $width,
			'height' => $height,
			'orientation' => ($width > $height) ? 'landscape' : 'portrait'
		);
	}

	public function blc_implement_user_registration() {
		ob_start();
		require_once ABSPATH . 'wp-login.php';
		$res = ob_get_clean();

		$users_can_register = get_option('users_can_register');

		if (get_option('woocommerce_enable_myaccount_registration') === 'yes') {
			$users_can_register = true;
		}

		if (! $users_can_register) {
			exit;
		}

		$user_login = '';
		$user_email = '';

		if (
			isset($_POST['user_login'])
			&&
			is_string($_POST['user_login'])
		) {
			$user_login = wp_unslash( $_POST['user_login'] );
		}

		if (isset($_POST['user_email']) && is_string($_POST['user_email'])) {
			$user_email = wp_unslash( $_POST['user_email'] );
		}

		$errors = register_new_user($user_login, $user_email);

		if (! is_wp_error($errors)) {
			$errors = new \WP_Error();

			$errors->add(
				'registered',
				sprintf(
					/* translators: %s: Link to the login page. */
					__( 'Registration complete. Please check your email, then visit the <a href="%s">login page</a>.' ),
					wp_login_url()
				),
				'message'
			);

			$redirect_to = admin_url();
			$errors = apply_filters('wp_login_errors', $errors, $redirect_to);

			login_header(__('Check your email', 'blc'), '', $errors);

			wp_die();
		}

		login_header(
			__('Registration Form', 'blc'),
			'<p class="message register">' . __('Register For This Site', 'blc') . '</p>',
			$errors
		);

		wp_die();
	}
}
