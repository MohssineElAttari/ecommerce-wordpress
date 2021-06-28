<?php

namespace Blocksy;

class ConditionsManager {
	public function __construct() {
	}

	public function condition_matches($rules = [], $args = []) {
		$args = wp_parse_args($args, [
			'relation' => 'OR'
		]);

		if (empty($rules)) {
			return false;
		}

		$all_includes = array_filter($rules, function ($el) {
			return $el['type'] === 'include';
		});

		$all_excludes = array_filter($rules, function ($el) {
			return $el['type'] === 'exclude';
		});

		$resolved_includes = array_filter($all_includes, function ($el) {
			return $this->resolve_single_condition($el);
		});

		$resolved_excludes = array_filter($all_excludes, function ($el) {
			return $this->resolve_single_condition($el);
		});

		// If at least one exclusion is true -- return false
		if (! empty($resolved_excludes)) {
			return false;
		}

		if (empty($all_includes)) {
			return true;
		}

		if (! empty($all_includes)) {
			// If at least one inclusion is true - return true
			if ($args['relation'] === 'OR' && ! empty($resolved_includes)) {
				return true;
			}

			// For AND relation all includes need to be resolved
			if (
				$args['relation'] === 'AND'
				&&
				count($resolved_includes) === count($all_includes)
			) {
				return true;
			}
		}

		return false;
	}

	public function resolve_single_condition($rule) {
		if ($rule['rule'] === 'everywhere') {
			return true;
		}

		if ($rule['rule'] === 'singulars') {
			return is_singular();
		}

		if ($rule['rule'] === 'archives') {
			return is_archive();
		}

		if ($rule['rule'] === '404') {
			return is_404();
		}

		if ($rule['rule'] === 'search') {
			return is_search();
		}

		if ($rule['rule'] === 'blog') {
			return !is_front_page() && is_home();
		}

		if ($rule['rule'] === 'front_page') {
			return is_front_page();
		}

		if ($rule['rule'] === 'date') {
			return is_date();
		}

		if ($rule['rule'] === 'author') {
			return is_author();
		}

		if ($rule['rule'] === 'woo_shop') {
			return function_exists('is_shop') && is_shop();
		}

		if ($rule['rule'] === 'single_post') {
			return is_singular('post');
		}

		if ($rule['rule'] === 'all_post_archives') {
			return is_post_type_archive('post');
		}

		if ($rule['rule'] === 'post_categories') {
			return is_category();
		}

		if ($rule['rule'] === 'post_tags') {
			return is_tag();
		}

		if ($rule['rule'] === 'single_page') {
			return is_singular('page');
		}

		if ($rule['rule'] === 'single_product') {
			return function_exists('is_product') && is_product();
		}

		if ($rule['rule'] === 'all_product_archives') {
			if (function_exists('is_shop')) {
				return is_shop() || is_product_tag() || is_product_category();
			}
		}

		if ($rule['rule'] === 'all_product_categories') {
			if (function_exists('is_shop')) {
				return is_product_category();
			}
		}

		if ($rule['rule'] === 'all_product_tags') {
			if (function_exists('is_shop')) {
				return is_product_tag();
			}
		}

		if ($rule['rule'] === 'user_logged_in') {
			return is_user_logged_in();
		}

		if ($rule['rule'] === 'user_logged_out') {
			return !is_user_logged_in();
		}

		if (strpos($rule['rule'], 'user_role_') !== false) {
			if (! is_user_logged_in()) {
				return false;
			}

			return in_array(
				str_replace('user_role_', '', $rule['rule']),
				get_userdata(wp_get_current_user()->ID)->roles
			);
		}

		if (strpos($rule['rule'], 'post_type_single_') !== false) {
			return is_singular(str_replace(
				'post_type_single_',
				'',
				$rule['rule']
			));
		}

		if (strpos($rule['rule'], 'post_type_archive_') !== false) {
			return is_post_type_archive(str_replace(
				'post_type_archive_',
				'',
				$rule['rule']
			));
		}

		if (
			$rule['rule'] === 'post_ids'
			||
			$rule['rule'] === 'page_ids'
			||
			$rule['rule'] === 'custom_post_type_ids'
		) {
			$is_blocksy_page = blocksy_is_page();

			if (is_singular() || $is_blocksy_page) {
				$post_id = get_the_ID();

				if ($is_blocksy_page) {
					$post_id = $is_blocksy_page;
				}

				global $post;

				if (intval($post_id) === 0 && isset($post->post_name)) {
					$maybe_post = get_page_by_path($post->post_name);

					if ($maybe_post) {
						$post_id = $maybe_post->ID;
					}
				}

				if (
					isset($rule['payload'])
					&&
					isset($rule['payload']['post_id'])
					&&
					$post_id
					&&
					intval($post_id) === intval($rule['payload']['post_id'])
				) {
					return true;
				}
			}
		}

		if (
			$rule['rule'] === 'current_language'
			&&
			function_exists('blocksy_get_current_language')
			&&
			! empty($payload['language'])
		) {
			return $payload['language'] === blocksy_get_current_language();
		}

		if ($rule['rule'] === 'taxonomy_ids') {
			if (is_tax() || is_category() || is_tag()) {
				$tax_id = get_queried_object_id();

				if (
					isset($rule['payload'])
					&&
					isset($rule['payload']['taxonomy_id'])
					&&
					$tax_id
					&&
					intval($tax_id) === intval($rule['payload']['taxonomy_id'])
				) {
					return true;
				}
			}
		}

		if ($rule['rule'] === 'post_with_taxonomy_ids') {
			$is_blocksy_page = blocksy_is_page();

			if (is_singular() || $is_blocksy_page) {
				$post_id = get_the_ID();

				if ($is_blocksy_page) {
					$post_id = $is_blocksy_page;
				}

				global $post;

				if (
					isset($rule['payload'])
					&&
					isset($rule['payload']['taxonomy_id'])
					&&
					$post_id
				) {
					return has_term(
						$rule['payload']['taxonomy_id'],
						get_term($rule['payload']['taxonomy_id'])->taxonomy
					);
				}
			}
		}

		return false;
	}

	public function get_all_rules() {
		$has_woo = class_exists('WooCommerce');

		$cpts = [];

		$custom_post_types = array_diff(
			get_post_types(['public' => true]),
			[
				'post',
				'page',
				'attachment',
				'documentation',
				'ct_content_block',
				'product'
			]
		);

		foreach ($custom_post_types as $custom_post_type) {
			$post_type_object = get_post_type_object($custom_post_type);

			$cpts[] = [
				'id' => 'post_type_single_' . $custom_post_type,
				'title' => sprintf(
					__('%s Single', 'blc'),
					$post_type_object->labels->singular_name
				)
			];

			$cpts[] = [
				'id' => 'post_type_archive_' . $custom_post_type,
				'title' => sprintf(
					__('%s Archive', 'blc'),
					$post_type_object->labels->singular_name
				)
			];
		}

		return array_merge([
			[
				'title' => '',
				'rules' => [
					[
						'id' => 'everywhere',
						'title' => __('Entire Website', 'blc')
					]
				]
			],

			[
				'title' => __('Basic', 'blc'),
				'rules' => [
					[
						'id' => 'singulars',
						'title' => __('Singulars', 'blc')
					],

					[
						'id' => 'archives',
						'title' => __('Archives', 'blc')
					]
				]
			],

			[
				'title' => __('Posts', 'blc'),
				'rules' => [
					[
						'id' => 'single_post',
						'title' => __('Single Post', 'blc')
					],

					[
						'id' => 'all_post_archives',
						'title' => __('All Post Archives', 'blc')
					],

					[
						'id' => 'post_categories',
						'title' => __('Post Categories', 'blc')
					],

					[
						'id' => 'post_tags',
						'title' => __('Post Tags', 'blc')
					],
				]
			],

			[
				'title' => __('Pages', 'blc'),
				'rules' => [
					[
						'id' => 'single_page',
						'title' => __('Single Page', 'blc')
					],
				]
			],
		],

		$has_woo ? [
			[
				'title' => __('WooCommerce', 'blc'),
				'rules' => [
					[
						'id' => 'woo_shop',
						'title' => __('Shop Home', 'blc')
					],

					[
						'id' => 'single_product',
						'title' => __('Single Product', 'blc')
					],

					[
						'id' => 'all_product_archives',
						'title' => __('Product Archives', 'blc')
					],

					[
						'id' => 'all_product_categories',
						'title' => __('Product Categories', 'blc')
					],

					[
						'id' => 'all_product_tags',
						'title' => __('Product Tags', 'blc')
					],
				]
			]
		] : [],

		count($cpts) > 0 ? [
			[
				'title' => __('Custom Post Types', 'blc'),
				'rules' => $cpts
			]
		] : [],

		[
			[
				'title' => __('Specific', 'blc'),
				'rules' => [
					[
						'id' => 'post_ids',
						'title' => __('Post ID', 'blc')
					],

					[
						'id' => 'page_ids',
						'title' => __('Page ID', 'blc')
					],

					[
						'id' => 'custom_post_type_ids',
						'title' => __('Custom Post Type ID', 'blc')
					],

					[
						'id' => 'taxonomy_ids',
						'title' => __('Taxonomy ID', 'blc')
					],

					[
						'id' => 'post_with_taxonomy_ids',
						'title' => __('Post with Taxonomy ID', 'blc')
					],
				]
			],

			[
				'title' => __('Other Pages', 'blc'),
				'rules' => [
					[
						'id' => '404',
						'title' => __('404', 'blc')
					],

					[
						'id' => 'search',
						'title' => __('Search', 'blc')
					],

					[
						'id' => 'blog',
						'title' => __('Blog', 'blc')
					],

					[
						'id' => 'front_page',
						'title' => __('Front Page', 'blc')
					],

					/*
					[
						'id' => 'date',
						'title' => __('Date', 'blc')
					],
					 */

					[
						'id' => 'author',
						'title' => __('Author', 'blc')
					],
				],
			],

			[
				'title' => __('User Auth', 'blc'),
				'rules' => [
					[
						'id' => 'user_logged_in',
						'title' => __('User Logged In', 'blc')
					],

					[
						'id' => 'user_logged_out',
						'title' => __('User Logged Out', 'blc')
					],
				]
			],

			[
				'title' => __('User Roles', 'blc'),
				'rules' => $this->get_user_roles_rules()
            ],
		],

		(
			function_exists('blocksy_get_current_language')
			&&
			blocksy_get_current_language() !== '__NOT_KNOWN__'
		) ? [
			[
				'title' => __('Languages', 'blc'),
				'rules' => [
					[
						'id' => 'current_language',
						'title' => __('Current Language', 'blc')
					]
				]
			]
		] : []);
	}

	public function humanize_conditions($conditions) {
		$result = [];

		foreach ($conditions as $condition) {
			$type = $condition['type'] === 'include' ? __('Include', 'blc') : __(
				'Exclude', 'blc'
			);

			$maybe_descriptor = $this->find_rule_descriptor(
				$condition['rule']
			);

			if (! $maybe_descriptor) {
				continue;
			}

			$to_append = $type . ' ' . $maybe_descriptor['title'];

			if (
				(
					$condition['rule'] === 'post_ids'
					||
					$condition['rule'] === 'page_ids'
					||
					$condition['rule'] === 'custom_post_type_ids'
				) && isset($condition['payload']['post_id'])
			) {
				$to_append .= ' (<a href="' . get_edit_post_link(
					$condition['payload']['post_id']
				) . '" target="_blank">' . get_the_title($condition['payload']['post_id']) . '</a>)';
			}

			if (
				(
					$condition['rule'] === 'taxonomy_ids'
					||
					$condition['rule'] === 'post_with_taxonomy_ids'
				) && isset($condition['payload']['taxonomy_id'])
			) {
				$tax = get_term_by(
					'term_taxonomy_id',
					$condition['payload']['taxonomy_id']
				);

				$to_append .= ' (<a href="' . get_edit_term_link(
					$condition['payload']['taxonomy_id']
				) . '" target="_blank">' . $tax->name . '</a>)';
			}

			if ($condition['rule'] === 'current_language') {
				$to_append = null;

				if (
					isset($condition['payload']['language'])
					&&
					function_exists('blocksy_get_all_i18n_languages')
				) {
					foreach (blocksy_get_all_i18n_languages() as $lang) {
						if ($lang['id'] === $condition['payload']['language']) {
							$to_append = $type . ' ' . $lang['name'] . ' ' . __(
								'Language', 'blc'
							);
						}
					}
				}
			}

			if ($to_append) {
				$result[] = $to_append;
			}
		}

		return $result;
	}

	private function find_rule_descriptor($rule) {
		$all = $this->get_all_rules();

		foreach ($all as $rules_group) {
			foreach ($rules_group['rules'] as $single_rule) {
				if ($single_rule['id'] === $rule) {
					return $single_rule;
				}
			}
		}

		return null;
	}

	private function get_user_roles_rules() {
		$result = [];

		foreach (get_editable_roles() as $role_id => $role_info) {
			$result[] = [
				'id' => 'user_role_' . $role_id,
				'title' => $role_info['name']
			];
		}

		return $result;
	}
}

