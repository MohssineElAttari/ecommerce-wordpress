<?php

$link_options = [
	'profile' => __( 'Profile Page', 'blc' ),
	'dashboard' => __( 'Dashboard Page', 'blc' ),
	// 'menu' => __( 'Menu', 'blc' ),
	'custom' => __( 'Custom Link', 'blc' ),
	'logout' => __( 'Logout', 'blc' ),
];

$logout_link_options = [
	'modal' => __( 'Modal', 'blc' ),
	'custom' => __( 'Custom Link', 'blc' ),
];

if (class_exists('WooCommerce')) {
	$link_options['woocommerce_account'] = __('WooCommerce Account', 'blc');
	$logout_link_options['woocommerce_account'] = __('WooCommerce Account', 'blc');
}

$options = [
	blocksy_rand_md5() => [
		'type' => 'ct-title',
		'label' => __( 'Customizing: Logged in State', 'blc' ),
	],

	'account_state' => [
		'label' => false,
		'type' => 'ct-image-picker',
		'value' => 'in',
		'attr' => [ 'data-type' => 'background' ],
		'switchDeviceOnChange' => 'desktop',
		'choices' => [
			'in' => [
				'src'   => blocksy_image_picker_url( 'log-in-state.svg' ),
				'title' => __( 'Logged In Options', 'blc' ),
			],

			'out' => [
				'src' => blocksy_image_picker_url('log-out-state.svg'),
				'title' => __('Logged Out Options', 'blc'),
			],
		],
	],

	blocksy_rand_md5() => [
		'type' => 'ct-divider',
	],

	blocksy_rand_md5() => [
		'title' => __( 'General', 'blocksy' ),
		'type' => 'tab',
		'options' => [

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'account_state' => 'in' ],
				'options' => [

					'account_link' => [
						'label' => __( 'Account Action', 'blc' ),
						'type' => 'ct-select',
						'value' => 'profile',
						'view' => 'text',
						'design' => 'inline',
						'choices' => blocksy_ordered_keys($link_options)
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'account_link' => 'menu' ],
						'options' => [
							'loggedin_account_menu' => [
								'label' => __('Select Menu', 'blc'),
								'type' => 'ct-select',
								'value' => 'blocksy_location',
								'view' => 'text',
								'design' => 'inline',
								'setting' => ['transport' => 'postMessage'],
								'placeholder' => __('Select menu...', 'blc'),
								'choices' => blocksy_ordered_keys(blocksy_get_menus_items()),
								'desc' => sprintf(
									// translators: placeholder here means the actual URL.
									__( 'Manage your menu items in the %sMenus screen%s.', 'blc' ),
									sprintf(
										'<a href="%s" target="_blank">',
										admin_url('/nav-menus.php')
									),
									'</a>'
								),
							],
						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'account_link' => 'custom' ],
						'options' => [

							'account_custom_page' => [
								'label' => __( 'Custom Page Link', 'blc' ),
								'type' => 'text',
								'design' => 'inline',
								'disableRevertButton' => true,
								'value' => ''
							],

						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],

					'loggedin_media' => [
						'label' => __( 'Account Image', 'blc' ),
						'type' => 'ct-radio',
						'design' => 'block',
						'view' => 'text',
						'value' => 'avatar',
						'choices' => [
							'avatar' => __( 'Avatar', 'blc' ),
							'icon' => __( 'Icon', 'blc' ),
							'none' => __( 'None', 'blc' ),
						],
						'setting' => [ 'transport' => 'postMessage' ],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'loggedin_media' => 'avatar' ],
						'options' => [

							'accountHeaderAvatarSize' => [
								'label' => __( 'Avatar Size', 'blc' ),
								'type' => 'ct-slider',
								'min' => 10,
								'max' => 40,
								'value' => 18,
								'responsive' => true,
								'divider' => 'top',
								'setting' => [ 'transport' => 'postMessage' ],
							],

						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'loggedin_media' => 'icon' ],
						'options' => [

							'account_loggedin_icon' => [
								'label' => false,
								'type' => 'ct-image-picker',
								'value' => 'type-1',
								'attr' => [
									'data-type' => 'background',
									'data-columns' => '3',
								],
								'divider' => 'top',
								'setting' => [ 'transport' => 'postMessage' ],
								'choices' => [

									'type-1' => [
										'src'   => blocksy_image_picker_file( 'account-1' ),
										'title' => __( 'Type 1', 'blocksy' ),
									],

									'type-2' => [
										'src'   => blocksy_image_picker_file( 'account-2' ),
										'title' => __( 'Type 2', 'blocksy' ),
									],

									'type-3' => [
										'src'   => blocksy_image_picker_file( 'account-3' ),
										'title' => __( 'Type 3', 'blocksy' ),
									],

									'type-4' => [
										'src'   => blocksy_image_picker_file( 'account-4' ),
										'title' => __( 'Type 4', 'blocksy' ),
									],

									'type-5' => [
										'src'   => blocksy_image_picker_file( 'account-5' ),
										'title' => __( 'Type 5', 'blocksy' ),
									],

									'type-6' => [
										'src'   => blocksy_image_picker_file( 'account-6' ),
										'title' => __( 'Type 6', 'blocksy' ),
									],
								],
							],

							'account_loggedin_icon_size' => [
								'label' => __( 'Icon Size', 'blc' ),
								'type' => 'ct-slider',
								'min' => 5,
								'max' => 50,
								'value' => 15,
								'responsive' => true,
								'divider' => 'top',
								'setting' => [ 'transport' => 'postMessage' ],
							],

						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],

					'loggedin_account_label_visibility' => [
						'label' => __( 'Label Visibility', 'blc' ),
						'type' => 'ct-visibility',
						'design' => 'block',
						'allow_empty' => true,
						'setting' => [ 'transport' => 'postMessage' ],
						'value' => [
							'desktop' => false,
							'tablet' => false,
							'mobile' => false,
						],

						'choices' => blocksy_ordered_keys([
							'desktop' => __( 'Desktop', 'blocksy' ),
							'tablet' => __( 'Tablet', 'blocksy' ),
							'mobile' => __( 'Mobile', 'blocksy' ),
						]),
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [
							'any' => [
								'loggedin_account_label_visibility/desktop' => true,
								'loggedin_account_label_visibility/tablet' => true,
								'loggedin_account_label_visibility/mobile' => true,
							]
						],
						'options' => [

							blocksy_rand_md5() => [
								'type' => 'ct-condition',
								'condition' => [ 'loggedin_media' => '!none' ],
								'options' => [

									'loggedin_label_position' => [
										'type' => 'ct-radio',
										'label' => __( 'Label Position', 'blc' ),
										'value' => 'right',
										'view' => 'text',
										'design' => 'block',
										'divider' => 'top',
										'responsive' => [ 'tablet' => 'skip' ],
										'choices' => [
											'left' => __( 'Left', 'blc' ),
											'right' => __( 'Right', 'blc' ),
											'bottom' => __( 'Bottom', 'blc' ),
										],
									],

								],
							],

							'loggedin_text' => [
								'label' => __('Label Type', 'blc'),
								'type' => 'ct-radio',
								'view' => 'text',
								'design' => 'block',
								'divider' => 'top',
								'setting' => ['transport' => 'postMessage'],
								'value' => 'label',
								'choices' => [
									'label' => __('Text', 'blc'),
									'username' => __('Name', 'blc'),
								],
							],

							blocksy_rand_md5() => [
								'type' => 'ct-condition',
								'condition' => ['loggedin_text' => 'label'],
								'options' => [

									'loggedin_label' => [
										'label' => __('Label Text', 'blc'),
										'type' => 'text',
										'design' => 'block',
										'divider' => 'top',
										'setting' => ['transport' => 'postMessage'],
										'value' => __('My Account', 'blc')
									],

								],
							],

						],
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'account_state' => 'out' ],
				'options' => [

					'login_account_action' => [
						'label' => __( 'Account Action', 'blc' ),
						'type' => 'ct-select',
						'value' => 'modal',
						'view' => 'text',
						'design' => 'inline',
						'setting' => [ 'transport' => 'postMessage' ],
						'choices' => blocksy_ordered_keys($logout_link_options)
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'login_account_action' => 'custom' ],
						'options' => [

							'loggedout_account_custom_page' => [
								'label' => __( 'Custom Page Link', 'blc' ),
								'type' => 'text',
								'design' => 'inline',
								'disableRevertButton' => true,
								'setting' => [ 'transport' => 'postMessage' ],
								'value' => ''
							],

						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],

					'logged_out_style' => [
						'label' => __( 'Account Image', 'blc' ),
						'type' => 'ct-radio',
						'design' => 'block',
						'view' => 'text',
						'value' => 'icon',
						'choices' => [
							'icon' => __( 'Icon', 'blc' ),
							'none' => __( 'None', 'blc' ),
						],
						'setting' => [ 'transport' => 'postMessage' ],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'logged_out_style' => 'icon' ],
						'options' => [

							'accountHeaderIcon' => [
								'label' => false,
								'type' => 'ct-image-picker',
								'value' => 'type-1',
								'attr' => [
									'data-type' => 'background',
									'data-columns' => '3',
								],
								'divider' => 'top',
								'setting' => [ 'transport' => 'postMessage' ],
								'choices' => [

									'type-1' => [
										'src'   => blocksy_image_picker_file( 'account-1' ),
										'title' => __( 'Type 1', 'blocksy' ),
									],

									'type-2' => [
										'src'   => blocksy_image_picker_file( 'account-2' ),
										'title' => __( 'Type 2', 'blocksy' ),
									],

									'type-3' => [
										'src'   => blocksy_image_picker_file( 'account-3' ),
										'title' => __( 'Type 3', 'blocksy' ),
									],

									'type-4' => [
										'src'   => blocksy_image_picker_file( 'account-4' ),
										'title' => __( 'Type 4', 'blocksy' ),
									],

									'type-5' => [
										'src'   => blocksy_image_picker_file( 'account-5' ),
										'title' => __( 'Type 5', 'blocksy' ),
									],

									'type-6' => [
										'src'   => blocksy_image_picker_file( 'account-6' ),
										'title' => __( 'Type 6', 'blocksy' ),
									],
								],
							],

							'accountHeaderIconSize' => [
								'label' => __( 'Icon Size', 'blc' ),
								'type' => 'ct-slider',
								'min' => 5,
								'max' => 50,
								'value' => 15,
								'responsive' => true,
								'divider' => 'top',
								'setting' => [ 'transport' => 'postMessage' ],
							],

						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],

					'loggedout_account_label_visibility' => [
						'label' => __( 'Label Visibility', 'blc' ),
						'type' => 'ct-visibility',
						'design' => 'block',
						'allow_empty' => true,
						'setting' => [ 'transport' => 'postMessage' ],
						'value' => [
							'desktop' => false,
							'tablet' => false,
							'mobile' => false,
						],

						'choices' => blocksy_ordered_keys([
							'desktop' => __( 'Desktop', 'blocksy' ),
							'tablet' => __( 'Tablet', 'blocksy' ),
							'mobile' => __( 'Mobile', 'blocksy' ),
						]),
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [
							'any' => [
								'loggedout_account_label_visibility/desktop' => true,
								'loggedout_account_label_visibility/tablet' => true,
								'loggedout_account_label_visibility/mobile' => true,
							]
						],
						'options' => [

							blocksy_rand_md5() => [
								'type' => 'ct-condition',
								'condition' => [ 'logged_out_style' => 'icon' ],
								'options' => [

									'loggedout_label_position' => [
										'type' => 'ct-radio',
										'label' => __( 'Label Position', 'blc' ),
										'value' => 'right',
										'view' => 'text',
										'design' => 'block',
										'divider' => 'top',
										'responsive' => [ 'tablet' => 'skip' ],
										'choices' => [
											'left' => __( 'Left', 'blc' ),
											'right' => __( 'Right', 'blc' ),
											'bottom' => __( 'Bottom', 'blc' ),
										],
									],

								],
							],

							'login_label' => [
								'label' => __('Label Text', 'blc'),
								'type' => 'text',
								'design' => 'block',
								'divider' => 'top',
								'disableRevertButton' => true,
								'setting' => [ 'transport' => 'postMessage' ],
								'value' => __('Login', 'blc')
							],

						],
					],

				],
			],

		],
	],




	blocksy_rand_md5() => [
		'title' => __( 'Design', 'blocksy' ),
		'type' => 'tab',
		'options' => [

			'account_label_font' => [
				'type' => 'ct-typography',
				'label' => __( 'Label Font', 'blc' ),
				'value' => blocksy_typography_default_values([
					'size' => '12px',
					'variation' => 'n6',
					'text-transform' => 'uppercase',
				]),
				'setting' => [ 'transport' => 'postMessage' ],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-labeled-group',
				'label' => __( 'Label Color', 'blc' ),
				'responsive' => true,
				'choices' => [
					[
						'id' => 'accountHeaderColor',
						'label' => __('Default State', 'blc')
					],

					[
						'id' => 'transparentAccountHeaderColor',
						'label' => __('Transparent State', 'blc'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_transparent_header' => 'yes',
						],
					],

					[
						'id' => 'stickyAccountHeaderColor',
						'label' => __('Sticky State', 'blc'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_sticky_header' => 'yes',
						],
					],
				],
				'options' => [

					'accountHeaderColor' => [
						'label' => __( 'Label Color', 'blc' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
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

					'transparentAccountHeaderColor' => [
						'label' => __( 'Label Color', 'blc' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
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
							],

							[
								'title' => __( 'Hover', 'blc' ),
								'id' => 'hover',
							],
						],
					],

					'stickyAccountHeaderColor' => [
						'label' => __( 'Label Color', 'blc' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,
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
							],

							[
								'title' => __( 'Hover', 'blc' ),
								'id' => 'hover',
							],
						],
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-divider',
			],


			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'any' => [
						'all' => [
							'account_state' => 'in',
							'loggedin_media' => 'icon'
						],

						'all~' => [
							'account_state' => 'out',
							'logged_out_style' => 'icon'
						]
					]
				],
				'options' => [
					blocksy_rand_md5() => [
						'type' => 'ct-labeled-group',
						'label' => __( 'Icon Color', 'blc' ),
						'responsive' => true,
						'choices' => [
							[
								'id' => 'header_account_icon_color',
								'label' => __('Default State', 'blc'),
							],

							[
								'id' => 'transparent_header_account_icon_color',
								'label' => __('Transparent State', 'blc'),
								'condition' => [
									'row' => '!offcanvas',
									'builderSettings/has_transparent_header' => 'yes',
								],
							],

							[
								'id' => 'sticky_header_account_icon_color',
								'label' => __('Sticky State', 'blc'),
								'condition' => [
									'row' => '!offcanvas',
									'builderSettings/has_sticky_header' => 'yes',
								],
							],
						],
						'options' => [
							'header_account_icon_color' => [
								'label' => __( 'Icon Color', 'blc' ),
								'type'  => 'ct-color-picker',
								'design' => 'block:right',
								'responsive' => true,
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
										'inherit' => 'var(--color)',
									],

									[
										'title' => __( 'Hover', 'blc' ),
										'id' => 'hover',
										'inherit' => 'var(--paletteColor2)',
									],
								],
							],

							'transparent_header_account_icon_color' => [
								'label' => __( 'Icon Color', 'blc' ),
								'type'  => 'ct-color-picker',
								'design' => 'block:right',
								'responsive' => true,
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
									],

									[
										'title' => __( 'Hover', 'blc' ),
										'id' => 'hover',
									],
								],
							],

							'sticky_header_account_icon_color' => [
								'label' => __( 'Icon Color', 'blc' ),
								'type'  => 'ct-color-picker',
								'design' => 'block:right',
								'responsive' => true,
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
									],

									[
										'title' => __( 'Hover', 'blc' ),
										'id' => 'hover',
									],
								],
							],
						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],
				]
			],

			'accountHeaderMargin' => [
				'label' => __( 'Item Margin', 'blc' ),
				'type' => 'ct-spacing',
				'setting' => [ 'transport' => 'postMessage' ],
				'value' => blocksy_spacing_value([
					'linked' => true,
				]),
				'responsive' => true
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [
					'account_state' => 'out',
					'login_account_action' => 'modal'
				],
				'options' => [

					blocksy_rand_md5() => [
						'type' => 'ct-title',
						'label' => __( 'Modal Options', 'blocksy' ),
					],

					'account_modal_font_color' => [
						'label' => __( 'Font Color', 'blc' ),
						'type'  => 'ct-color-picker',
						'design' => 'inline',
						'divider' => 'bottom',
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

					'account_form_shadow' => [
						'label' => __( 'Modal Shadow', 'blc' ),
						'type' => 'ct-box-shadow',
						'design' => 'inline',
						// 'responsive' => true,
						'value' => blocksy_box_shadow_value([
							'enable' => true,
							'h_offset' => 0,
							'v_offset' => 0,
							'blur' => 70,
							'spread' => 0,
							'inset' => false,
							'color' => [
								'color' => 'rgba(0, 0, 0, 0.35)',
							],
						])
					],

					'account_close_button_color' => [
						'label' => __( 'Close Icon Color', 'blc' ),
						'type'  => 'ct-color-picker',
						'design' => 'inline',
						'divider' => 'top',
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
								'inherit' => 'rgba(255, 255, 255, 0.7)'
							],

							[
								'title' => __( 'Hover', 'blc' ),
								'id' => 'hover',
								'inherit' => '#ffffff'
							],
						],
					],

					'account_close_button_shape_color' => [
						'label' => __( 'Close Icon Background', 'blc' ),
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
								'inherit' => 'rgba(0, 0, 0, 0.5)'
							],

							[
								'title' => __( 'Hover', 'blc' ),
								'id' => 'hover',
								'inherit' => 'rgba(0, 0, 0, 0.5)'
							],
						],
					],

					'accountHeaderFormBackground' => [
						'label' => __( 'Modal Background', 'blc' ),
						'type'  => 'ct-background',
						'design' => 'inline',
						'divider' => 'top',
						'setting' => [ 'transport' => 'postMessage' ],
						'value' => blocksy_background_default_value([
							'backgroundColor' => [
								'default' => [
									'color' => '#ffffff'
								],
							],
						])
					],

					'accountHeaderBackground' => [
						'label' => __( 'Modal Backdrop', 'blc' ),
						'type'  => 'ct-background',
						'design' => 'inline',
						'divider' => 'top',
						'setting' => [ 'transport' => 'postMessage' ],
						'value' => blocksy_background_default_value([
							'backgroundColor' => [
								'default' => [
									'color' => 'rgba(18, 21, 25, 0.6)'
								],
							],
						])
					],

				],
			],

		],
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'wp_customizer_current_view' => 'tablet|mobile' ],
		'options' => [

			blocksy_rand_md5() => [
				'type' => 'ct-divider',
			],

			'header_account_visibility' => [
				'label' => __( 'Element Visibility', 'blocksy' ),
				'type' => 'ct-visibility',
				'design' => 'block',
				'setting' => [ 'transport' => 'postMessage' ],
				'allow_empty' => true,
				'value' => [
					'tablet' => true,
					'mobile' => true,
				],

				'choices' => blocksy_ordered_keys([
					'tablet' => __( 'Tablet', 'blocksy' ),
					'mobile' => __( 'Mobile', 'blocksy' ),
				]),
			],

		],
	],
];
