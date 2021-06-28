<?php

$options = [
	'label' => __( 'Subscribe Form', 'blc' ),
	'type' => 'ct-panel',
	'switch' => true,
	'value' => 'yes',
	'setting' => [ 'transport' => 'postMessage' ],
	'inner-options' => [

		blocksy_rand_md5() => [
			'title' => __( 'General', 'blc' ),
			'type' => 'tab',
			'options' => [

				'newsletter_subscribe_title' => [
					'type' => 'text',
					'label' => __( 'Title', 'blc' ),
					'field_attr' => [ 'id' => 'widget-title' ],
					'design' => 'block',
					'value' => __( 'Newsletter Updates', 'blc' ),
					'disableRevertButton' => true,
					'setting' => [ 'transport' => 'postMessage' ],
				],

				'newsletter_subscribe_text' => [
					'label' => __( 'Message', 'blc' ),
					'type' => 'textarea',
					'value' => __( 'Enter your email address below to subscribe to our newsletter', 'blc' ),
					'design' => 'block',
					'disableRevertButton' => true,
					'setting' => [ 'transport' => 'postMessage' ],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-divider',
					'attr' => [ 'data-type' => 'small' ],
				],

				'newsletter_subscribe_list_id_source' => [
					'type' => 'ct-radio',
					'label' => __( 'List Source', 'blc' ),
					'value' => 'default',
					'view' => 'radio',
					'inline' => true,
					'design' => 'inline',
					'disableRevertButton' => true,
					'choices' => [
						'default' => __('Default', 'blc'),
						'custom' => __('Custom', 'blc'),
					],

					'setting' => [ 'transport' => 'postMessage' ],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'newsletter_subscribe_list_id_source' => 'custom' ],
					'options' => [

						'newsletter_subscribe_list_id' => [
							'label' => __( 'List ID', 'blc' ),
							'type' => 'blocksy-newsletter-subscribe',
							'value' => '',
							'design' => 'inline',
							'disableRevertButton' => true,
							'setting' => [ 'transport' => 'postMessage' ],
						],

					],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-divider',
					'attr' => [ 'data-type' => 'small' ],
				],

				'has_newsletter_subscribe_name' => [
					'type'  => 'ct-switch',
					'label' => __( 'Name Field', 'blc' ),
					'value' => 'no',
					'disableRevertButton' => true,
					'setting' => [ 'transport' => 'postMessage' ],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-condition',
					'condition' => [ 'has_newsletter_subscribe_name' => 'yes' ],
					'options' => [

						'newsletter_subscribe_name_label' => [
							'type' => 'text',
							'label' => __( 'Name Label', 'blc' ),
							'design' => 'inline',
							'value' => __( 'Your name', 'blc' ),
							'disableRevertButton' => true,
							'setting' => [ 'transport' => 'postMessage' ],
						],

					],
				],

				'newsletter_subscribe_mail_label' => [
					'type' => 'text',
					'label' => __( 'Mail Label', 'blc' ),
					'design' => 'inline',
					'value' => __( 'Your email', 'blc' ),
					'disableRevertButton' => true,
					'setting' => [ 'transport' => 'postMessage' ],
				],

				'newsletter_subscribe_button_text' => [
					'type' => 'text',
					'label' => __( 'Button Label', 'blc' ),
					'design' => 'inline',
					'value' => __( 'Subscribe', 'blc' ),
					'disableRevertButton' => true,
					'setting' => [ 'transport' => 'postMessage' ],
				],

				blocksy_rand_md5() => [
					'type' => 'ct-divider',
				],

				'newsletter_subscribe_subscribe_visibility' => [
					'label' => __( 'Visibility', 'blc' ),
					'type' => 'ct-visibility',
					'design' => 'block',
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => [
						'desktop' => true,
						'tablet' => true,
						'mobile' => false,
					],

					'choices' => blocksy_ordered_keys([
						'desktop' => __( 'Desktop', 'blc' ),
						'tablet' => __( 'Tablet', 'blc' ),
						'mobile' => __( 'Mobile', 'blc' ),
					]),
				],

			],
		],

		blocksy_rand_md5() => [
			'title' => __( 'Design', 'blc' ),
			'type' => 'tab',
			'options' => [

				'newsletter_subscribe_content' => [
					'label' => __( 'Content Color', 'blc' ),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
					'setting' => [ 'transport' => 'postMessage' ],

					'value' => [
						'default' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],

						'hover' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],
					],

					'pickers' => [
						[
							'title' => __( 'Initial', 'blc' ),
							'id' => 'default',
							'inherit' => 'var(--color)'
						],

						[
							'title' => __( 'Hover', 'blc' ),
							'id' => 'hover',
							'inherit' => 'var(--linkHoverColor)'
						],
					],
				],

				'newsletter_subscribe_button' => [
					'label' => __( 'Button Color', 'blc' ),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
					'setting' => [ 'transport' => 'postMessage' ],

					'value' => [
						'default' => [
							'color' => 'var(--paletteColor1)',
						],

						'hover' => [
							'color' => 'var(--paletteColor2)',
						],
					],

					'pickers' => [
						[
							'title' => __( 'Initial', 'blc' ),
							'id' => 'default',
						],

						[
							'title' => __( 'Hover', 'blc' ),
							'id' => 'hover',
						],
					],
				],

				'newsletter_subscribe_background' => [
					'label' => __( 'Background Color', 'blc' ),
					'type'  => 'ct-color-picker',
					'design' => 'inline',
					'setting' => [ 'transport' => 'postMessage' ],

					'value' => [
						'default' => [
							'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
						],
					],

					'pickers' => [
						[
							'title' => __( 'Initial', 'blc' ),
							'id' => 'default',
							'inherit' => '#ffffff'
						],
					],
				],

				'newsletter_subscribe_spacing' => [
					'label' => __( 'Container Inner Spacing', 'blc' ),
					'type' => 'ct-slider',
					'value' => 30,
					'min' => 0,
					'max' => 300,
					'responsive' => true,
					'divider' => 'top',
					'setting' => [ 'transport' => 'postMessage' ],
				],

				'newsletter_subscribe_shadow' => [
					'label' => __( 'Shadow', 'blocksy' ),
					'type' => 'ct-box-shadow',
					'responsive' => true,
					'divider' => 'top',
					'setting' => [ 'transport' => 'postMessage' ],
					'value' => blocksy_box_shadow_value([
						'enable' => true,
						'h_offset' => 0,
						'v_offset' => 50,
						'blur' => 90,
						'spread' => 0,
						'inset' => false,
						'color' => [
							'color' => 'rgba(210, 213, 218, 0.4)',
						],
					])
				],
			],
		],
	],
];
