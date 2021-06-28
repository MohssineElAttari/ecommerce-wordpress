<?php

$options = [
	blocksy_rand_md5() => [
		'label' => __( 'Gallery Options', 'blocksy' ),
		'type' => 'ct-panel',
		'inner-options' => [

			[
				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [
						'product_view_type' => 'default-gallery|stacked-gallery',
					],
					'options' => [

						'productGalleryWidth' => [
							'label' => __( 'Product Gallery Width', 'blocksy' ),
							'type' => 'ct-slider',
							'defaultUnit' => '%',
							'value' => 50,
							'min' => 20,
							'max' => 70,
							'setting' => [ 'transport' => 'postMessage' ],
						],

						blocksy_rand_md5() => [
							'type' => 'ct-divider',
						],

					],
				],
			],

			apply_filters(
				'blocksy:options:single_product:gallery-options:start',
				[]
			),

			'product_thumbs_spacing' => [
				'label' => [
					__( 'Thumnbnails Spacing', 'blocksy' ) => [
						'product_view_type' => '!columns-top-gallery|!stacked-gallery'
					],
					__( 'Columns Spacing', 'blocksy' ) => [
						'product_view_type' => 'columns-top-gallery|stacked-gallery'
					],
				],
				'type' => 'ct-slider',
				'value' => '15px',
				'units' => blocksy_units_config([
					[ 'unit' => 'px', 'min' => 0, 'max' => 100 ],
				]),
				'responsive' => true,
				'setting' => [ 'transport' => 'postMessage' ],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'product_view_type' => 'default-gallery' ],
				'options' => [

					'gallery_style' => [
						'label' => __('Thumbnails Position', 'blocksy'),
						'type' => 'ct-radio',
						'value' => 'horizontal',
						'view' => 'text',
						'design' => 'block',
						'divider' => 'top',
						'choices' => [
							'horizontal' => __( 'Horizontal', 'blocksy' ),
							'vertical' => __( 'Vertical', 'blocksy' ),
						],

						'sync' => blocksy_sync_whole_page([
							'loader_selector' => '.woocommerce-product-gallery',
							'prefix' => 'product'
						])
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-divider',
			],

			'product_gallery_ratio' => [
				'label' => __( 'Image', 'blocksy' ),
				'type' => 'ct-ratio',
				'value' => '3/4',
				'design' => 'inline',
				'attr' => [ 'data-type' => 'compact' ],
				'setting' => [ 'transport' => 'postMessage' ],
				'preview_width_key' => 'woocommerce_single_image_width',
				'inner-options' => [

					'woocommerce_single_image_width' => [
						'type' => 'text',
						'label' => __('Image Size', 'blocksy'),
						'desc' => __('Image size used for the main image on single product pages.', 'blocksy'),
						'value' => 600,
						'design' => 'inline',
						'setting' => [
							'type' => 'option',
							'capability' => 'manage_woocommerce',
						]
					],

				],
			],

			'has_product_single_lightbox' => [
				'label' => __( 'Lightbox', 'blocksy' ),
				'type' => 'ct-switch',
				'value' => 'no',
				'sync' => blocksy_sync_whole_page([
					'prefix' => 'product',
					'loader_selector' => '.woocommerce-product-gallery'
				]),
			],

			'has_product_single_zoom' => [
				'label' => __( 'Zoom Effect', 'blocksy' ),
				'type' => 'ct-switch',
				'value' => 'yes',
				'sync' => blocksy_sync_whole_page([
					'prefix' => 'product',
					'loader_selector' => '.woocommerce-product-gallery'
				]),
			],

		],
	],
];
