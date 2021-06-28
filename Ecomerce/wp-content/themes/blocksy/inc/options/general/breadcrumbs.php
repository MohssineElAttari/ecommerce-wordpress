<?php

/**
 * Breadcrumbs options
 *
 * @copyright 2019-present Creative Themes
 * @license   http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package   Blocksy
 */

$source_options = [
	'default' => __('Default', 'blocksy')
];

if (function_exists('rank_math_the_breadcrumbs')) {
	$source_options['rankmath'] = __('RankMath', 'blocksy');
}

if (function_exists('yoast_breadcrumb')) {
	$source_options['yoast'] = __('Yoast', 'blocksy');
}

if (function_exists('seopress_display_breadcrumbs')) {
	$source_options['seopress'] = __('SeoPress', 'blocksy');
}

if (function_exists('bcn_display')) {
	$source_options['bcnxt'] = __('Breadcrumb NavXT', 'blocksy');
}

$breadcrumbs_options = [
	'breadcrumb_separator' => [
		'label' => __('Separator', 'blocksy'),
		'type' => 'ct-image-picker',
		'value' => 'type-1',
		'attr' => [ 'data-columns' => '3' ],
		'divider' => 'bottom',
		'choices' => [

			'type-1' => [
				'src'   => blocksy_image_picker_file( 'breadcrumb-sep-1' ),
				'title' => __( 'Type 1', 'blocksy' ),
			],

			'type-2' => [
				'src'   => blocksy_image_picker_file( 'breadcrumb-sep-2' ),
				'title' => __( 'Type 2', 'blocksy' ),
			],

			'type-3' => [
				'src'   => blocksy_image_picker_file( 'breadcrumb-sep-3' ),
				'title' => __( 'Type 3', 'blocksy' ),
			],
		],

		'sync' => blocksy_sync_whole_page([
			'loader_selector' => '.ct-breadcrumbs'
		]),
	],

	'breadcrumb_home_item' => [
		'label' => __('Home Item', 'blocksy'),
		'type' => 'ct-radio',
		'value' => 'text',
		'view' => 'text',
		'choices' => [
			'text' => __('Text', 'blocksy'),
			'icon' => __('Icon', 'blocksy'),
		],
		'sync' => blocksy_sync_whole_page([
			'loader_selector' => '.ct-breadcrumbs'
		]),
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'breadcrumb_home_item' => 'text' ],
		'options' => [

			'breadcrumb_home_text' => [
				'label' => __( 'Home Page Text', 'blocksy' ),
				'type' => 'text',
				'design' => 'block',
				'value' => __( 'Home', 'blocksy' ),
				'sync' => blocksy_sync_whole_page([
					'loader_selector' => '.ct-breadcrumbs'
				]),
			],

		],
	],

	'breadcrumb_page_title' => [
		'label' => __( 'Current Page/Post Title', 'blocksy' ),
		'type' => 'ct-switch',
		'value' => 'yes',
		'divider' => 'top',
		'sync' => blocksy_sync_whole_page([
			'loader_selector' => '.ct-breadcrumbs'
		]),
	],

	'breadcrumb_taxonomy_title' => [
		'label' => __( 'Current Taxonomy Title', 'blocksy' ),
		'type' => 'ct-switch',
		'value' => 'yes',
		'divider' => 'top',
		'sync' => blocksy_sync_whole_page([
			'loader_selector' => '.ct-breadcrumbs'
		]),
	],
];

$options = [

	'breadcrumbs_panel' => [
		'label' => __( 'Breadcrumbs', 'blocksy' ),
		'type' => 'ct-panel',
		'setting' => [ 'transport' => 'postMessage' ],
		'inner-options' => array_merge(count($source_options) > 1 ? [
			'breadcrumbs_source' => [
				'label' => __('Breadcrumbs Source', 'blocksy'),
				'type' => 'ct-select',
				'value' => 'default',
				'choices' => $source_options,
				'divider' => 'bottom'
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'breadcrumbs_source' => 'default'
				],
				'options' => $breadcrumbs_options
			]
		] : $breadcrumbs_options)
	],

];
