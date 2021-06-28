<?php

class Blocksy_Static_Css_Files {
	public function all_static_files() {
		return [
			[
				'id' => 'ct-main-styles',
				'url' => '/static/bundle/main.min.css'
			],

			[
				'id' => 'ct-admin-frontend-styles',
				'url' => '/static/bundle/admin-frontend.min.css',
				'enabled' => (
					current_user_can('manage_options')
					||
					current_user_can('edit_theme_options')
				)
			],

			[
				'id' => 'ct-main-rtl-styles',
				'url' => '/static/bundle/main-rtl.min.css',
				'enabled' => is_rtl()
			],

			[
				'id' => 'ct-forminator-styles',
				'url' => '/static/bundle/forminator.min.css',
				'deps' => ['ct-main-styles'],
				'enabled' => class_exists('Forminator')
			],

			[
				'id' => 'ct-getwid-styles',
				'url' => '/static/bundle/getwid.min.css',
				'deps' => ['ct-main-styles'],
				'enabled' => class_exists('Getwid\Getwid')
			],

			[
				'id' => 'ct-elementor-styles',
				'url' => '/static/bundle/elementor-frontend.min.css',
				'deps' => ['ct-main-styles'],
				'enabled' => did_action('elementor/loaded')
			],

			[
				'id' => 'ct-tutor-styles',
				'url' => '/static/bundle/tutor.min.css',
				'deps' => ['ct-main-styles'],
				'enabled' => function_exists('tutor_course_enrolled_lead_info')
			],

			[
				'id' => 'ct-sidebar-styles',
				'url' => '/static/bundle/sidebar.min.css',
				'deps' => ['ct-main-styles'],
				'enabled' => (
					blocksy_sidebar_position() !== 'none'
					||
					is_customize_preview()
					||
					(
						function_exists('is_woocommerce')
						&&
						is_woocommerce()
						&&
						get_theme_mod('has_woo_offcanvas_filter', 'no') === 'yes'
					)
				)
			],

			[
				'id' => 'ct-share-box-styles',
				'url' => '/static/bundle/share-box.min.css',
				'deps' => ['ct-main-styles'],
				'enabled' => (
					is_singular()
					&&
					(
						blocksy_has_share_box()
						||
						is_customize_preview()
						||
						(
							function_exists('is_product')
							&&
							is_product()
							&&
							get_theme_mod('product_has_share_box', 'yes') === 'yes'
						)
						||
						is_page(
							get_theme_mod('woocommerce_wish_list_page', '__EMPTY__')
						)
						||
						(
							function_exists('is_account_page')
							&&
							is_account_page()
						)
					)
				)
			],

			[
				'id' => 'ct-comments-styles',
				'url' => '/static/bundle/comments.min.css',
				'deps' => ['ct-main-styles'],
				'enabled' => (
					is_singular()
					&&
					(
						blocksy_has_comments()
						||
						is_customize_preview()
					)
				)
			],

			[
				'id' => 'ct-author-box-styles',
				'url' => '/static/bundle/author-box.min.css',
				'deps' => ['ct-main-styles'],
				'enabled' => (
					is_singular()
					&&
					(
						blocksy_has_author_box()
						||
						is_customize_preview()
					)
				)
			],

			[
				'id' => 'ct-posts-nav-styles',
				'url' => '/static/bundle/posts-nav.min.css',
				'deps' => ['ct-main-styles'],
				'enabled' => (
					is_singular()
					&&
					(
						blocksy_has_post_nav()
						||
						is_customize_preview()
					)
				)
			],

			[
				'id' => 'ct-flexy-styles',
				'url' => '/static/bundle/flexy.min.css',
				'deps' => ['ct-main-styles'],
				'enabled' => (
					function_exists('is_woocommerce')
					||
					is_singular('blc-product-review')
				)
			],
		];
	}

	public function enqueue_static_files($theme) {
		foreach ($this->all_static_files() as $internal_file) {
			$file = wp_parse_args($internal_file, [
				'enabled' => true,
				'deps' => [],
				'url' => ''
			]);

			if (! $file['enabled']) {
				continue;
			}

			$file['url'] = get_template_directory_uri() . $file['url'];

			wp_enqueue_style(
				$file['id'],
				$file['url'],
				$file['deps'],
				$theme->get('Version')
			);
		}
	}
}
