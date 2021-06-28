<?php

add_action('init', function () {
	if (
		get_option(
			'elementor_disable_color_schemes',
			'__DEFAULT__'
		) === '__DEFAULT__'
	) {
		update_option('elementor_disable_color_schemes', 'yes');
	}

	add_filter('elementor/schemes/enabled_schemes', function ($s) {
		// blocksy_print($s);
		// return ['color'];
		return $s;
	});

	if (
		get_option(
			'elementor_disable_typography_schemes',
			'__DEFAULT__'
		) === '__DEFAULT__'
	) {
		update_option('elementor_disable_typography_schemes', 'yes');
	}

	if (! get_option('elementor_viewport_lg')) {
		update_option('elementor_viewport_lg', 1000);
	}

	if (! get_option('elementor_viewport_md')) {
		update_option('elementor_viewport_md', 690);
	}

	add_filter(
		'rest_request_after_callbacks',
		function ($response, $handler, \WP_REST_Request $request) {
			$route = $request->get_route();
			$rest_id = substr($route, strrpos($route, '/') + 1);

			$palettes = [
				'blocksy_palette_1' => [
					'id' => 'blocksy_palette_1',
					'title' => __('Theme Color Palette 1', 'blocksy'),
					'value' => 'var(--paletteColor1)'
				],

				'blocksy_palette_2' => [
					'id' => 'blocksy_palette_2',
					'title' => __('Theme Color Palette 2', 'blocksy'),
					'value' => 'var(--paletteColor2)'
				],

				'blocksy_palette_3' => [
					'id' => 'blocksy_palette_3',
					'title' => __('Theme Color Palette 3', 'blocksy'),
					'value' => 'var(--paletteColor3)'
				],

				'blocksy_palette_4' => [
					'id' => 'blocksy_palette_4',
					'title' => __('Theme Color Palette 4', 'blocksy'),
					'value' => 'var(--paletteColor4)'
				],

				'blocksy_palette_5' => [
					'id' => 'blocksy_palette_5',
					'title' => __('Theme Color Palette 5', 'blocksy'),
					'value' => 'var(--paletteColor5)'
				],

				'blocksy_palette_6' => [
					'id' => 'blocksy_palette_6',
					'title' => __('Theme Color Palette 6', 'blocksy'),
					'value' => 'var(--paletteColor6)'
				],

				'blocksy_palette_7' => [
					'id' => 'blocksy_palette_7',
					'title' => __('Theme Color Palette 7', 'blocksy'),
					'value' => 'var(--paletteColor7)'
				],

				'blocksy_palette_8' => [
					'id' => 'blocksy_palette_8',
					'title' => __('Theme Color Palette 8', 'blocksy'),
					'value' => 'var(--paletteColor8)'
				]
			];

			if (isset($palettes[$rest_id])) {
				return new \WP_REST_Response($palettes[$rest_id]);
			}

			if ($route === '/elementor/v1/globals') {
				$data = $response->get_data();

				$colors = blocksy_get_colors(get_theme_mod('colorPalette'), [
					'color1' => [ 'color' => '#3eaf7c' ],
					'color2' => [ 'color' => '#33a370' ],
					'color3' => [ 'color' => '#415161' ],
					'color4' => [ 'color' => '#2c3e50' ],
					'color5' => [ 'color' => '#E2E7ED' ],
					'color6' => [ 'color' => '#edeff2' ],
					'color7' => [ 'color' => '#f8f9fb' ],
					'color8' => [ 'color' => '#ffffff' ],
				]);

				$colors_for_palette = [
					'blocksy_palette_1' => 'color1',
					'blocksy_palette_2' => 'color2',
					'blocksy_palette_3' => 'color3',
					'blocksy_palette_4' => 'color4',
					'blocksy_palette_5' => 'color5',
					'blocksy_palette_6' => 'color6',
					'blocksy_palette_7' => 'color7',
					'blocksy_palette_8' => 'color8'
				];

				foreach ($palettes as $key => $value) {
					$value['value'] = $colors[
						$colors_for_palette[$key]
					];

					$data['colors'][$key] = $value;
				}

				$response->set_data($data);
			}

			return $response;
		},
		1000, 3
	);

	/*
	add_action('elementor/frontend/section/before_render', function ($element) {
		$settings = $element->get_settings_for_display();

		if (
			! $element->get_data('isInner')
			&&
			blocksy_akg('blocksy_stretch_section', $settings, '') !== 'stretched'
		) {
			$element->add_render_attribute('_wrapper', [
				'class' => 'ct-section-boxed'
			]);
		}
	});
	 */

	add_action(
		'elementor/element/section/section_layout/after_section_start',
		function ($element, $args) {
			$element->add_control('blocksy_stretch_section', [
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Full Width Section', 'blocksy' ),
				// 'description' => esc_html__( 'It will remove the "weird" columns gap added by Elementor on the left and right side of each section (when `Columns Gap` is active). This helps you to have consistent content width without having to manually readjust it everytime you create sections with `Columns Gap`', 'blocksy' ),
				'return_value' => 'stretched',
				'hide_in_inner' => true,
				'default' => '',
				'separator' => 'after',
				'prefix_class' => 'ct-section-',
			]);
		},
		10, 2
	);

	add_action(
		'elementor/element/section/section_layout/before_section_end',
		function ($element, $args) {
			$element->remove_control('stretch_section');
			$element->add_control('fix_columns_alignment', [
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Columns Alignment Fix', 'blocksy' ),
				// 'description' => esc_html__( 'It will remove the "weird" columns gap added by Elementor on the left and right side of each section (when `Columns Gap` is active). This helps you to have consistent content width without having to manually readjust it everytime you create sections with `Columns Gap`', 'blocksy' ),
				'return_value' => 'fix',
				'default' => apply_filters(
					'blocksy:integrations:elementor:fix_columns_alignment:default',
					''
				),
				'separator' => 'before',
				'prefix_class' => 'ct-columns-alignment-',
			]);
		},
		10, 2
	);

	add_action('elementor/editor/after_enqueue_styles', function () {
		$theme = blocksy_get_wp_parent_theme();

		wp_enqueue_style(
			'blocksy-elementor-styles',
			get_template_directory_uri() . '/static/bundle/elementor.min.css',
			[],
			$theme->get('Version')
		);
	});
});

add_filter('fl_builder_settings_form_defaults', function ($defaults, $form_type) {
	if ('global' === $form_type) {
		$defaults->row_padding = '0';
		$defaults->row_width = '1290';
		$defaults->medium_breakpoint = '1000';
		$defaults->responsive_breakpoint = '690';
	}

	return $defaults;
}, 10, 2);

add_action(
	'elementor/theme/register_locations',
	function ($elementor_theme_manager) {
		$elementor_theme_manager->register_all_core_location();
	}
);

