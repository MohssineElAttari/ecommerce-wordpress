<?php

if (! isset($root_selector)) {
	$root_selector = ['.ct-header-account'];
}

// Icon size
$accountHeaderIconSize = blocksy_akg( 'accountHeaderIconSize', $atts, 15 );

if ($accountHeaderIconSize !== 15) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_assemble_selector(
			blocksy_mutate_selector([
				'selector' => $root_selector,
				'operation' => 'el-suffix',
				'to_add' => '[data-state="out"]'
			])
		),
		'variableName' => 'icon-size',
		'value' => $accountHeaderIconSize,
	]);
}

$accountHeaderIconSize = blocksy_akg( 'account_loggedin_icon_size', $atts, 15 );

if ($accountHeaderIconSize !== 15) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blocksy_assemble_selector(
			blocksy_mutate_selector([
				'selector' => $root_selector,
				'operation' => 'el-suffix',
				'to_add' => '[data-state="in"]'
			])
		),
		'variableName' => 'icon-size',
		'value' => $accountHeaderIconSize,
	]);
}


// Avatar size
$accountHeaderAvatarSize = blocksy_akg( 'accountHeaderAvatarSize', $atts, 18 );

if ($accountHeaderAvatarSize !== 18) {
	blocksy_output_responsive([
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => blc_call_fn([
			'fn' => 'blocksy_assemble_selector',
			'default' => $root_selector
		], $root_selector),
		'variableName' => 'avatar-size',
		'value' => $accountHeaderAvatarSize,
	]);
}

// Modal background
blocksy_output_background_css([
	'selector' => blc_call_fn([
		'fn' => 'blocksy_assemble_selector',
		'default' => $root_selector
	], blc_call_fn([
		'fn' => 'blocksy_mutate_selector',
	], [
		'selector' => [$root_selector[0]],
		'operation' => 'suffix',
		'to_add' => '#account-modal .ct-account-form'
	])),
	'css' => $css,
	'value' => blocksy_akg('accountHeaderFormBackground', $atts,
		blocksy_background_default_value([
			'backgroundColor' => [
				'default' => [
					'color' => '#ffffff'
				],
			],
		])
	)
]);

// Modal backdrop
blocksy_output_background_css([
	'selector' => blc_call_fn([
		'fn' => 'blocksy_assemble_selector',
		'default' => $root_selector
	], blc_call_fn([
		'fn' => 'blocksy_mutate_selector',
	], [
		'selector' => [$root_selector[0]],
		'operation' => 'suffix',
		'to_add' => '#account-modal'
	])),
	'css' => $css,
	'value' => blocksy_akg('accountHeaderBackground', $atts,
		blocksy_background_default_value([
			'backgroundColor' => [
				'default' => [
					'color' => 'rgba(18, 21, 25, 0.6)'
				],
			],
		])
	)
]);

blocksy_output_box_shadow([
	'css' => $css,
	// 'tablet_css' => $tablet_css,
	// 'mobile_css' => $mobile_css,
	'selector' => blc_call_fn([
		'fn' => 'blocksy_assemble_selector',
		'default' => $root_selector
	], blc_call_fn([
		'fn' => 'blocksy_mutate_selector',
	], [
		'selector' => [$root_selector[0]],
		'operation' => 'suffix',
		'to_add' => '#account-modal .ct-account-form'
	])),
	'value' => blocksy_akg('account_form_shadow', $atts, blocksy_box_shadow_value([
		'enable' => true,
		'h_offset' => 0,
		'v_offset' => 0,
		'blur' => 70,
		'spread' => 0,
		'inset' => false,
		'color' => [
			'color' => 'rgba(0, 0, 0, 0.35)',
		],
	])),
	// 'responsive' => true
]);

// Item margin
blocksy_output_spacing([
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => blc_call_fn([
		'fn' => 'blocksy_assemble_selector',
		'default' => $root_selector
	], $root_selector),
	'important' => true,
	'value' => blocksy_default_akg(
		'accountHeaderMargin', $atts,
		blocksy_spacing_value([
			'linked' => true,
		])
	)
]);

blocksy_output_font_css([
	'font_value' => blocksy_akg( 'account_label_font', $atts,
		blocksy_typography_default_values([
			'size' => '12px',
			'variation' => 'n6',
			'text-transform' => 'uppercase',
		])
	),
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => blc_call_fn([
		'fn' => 'blocksy_assemble_selector',
		'default' => $root_selector
	], blc_call_fn([
		'fn' => 'blocksy_mutate_selector',
	], [
		'selector' => $root_selector,
		'operation' => 'suffix',
		'to_add' => '.ct-label'
	])),
]);

// default state
blocksy_output_colors([
	'value' => blocksy_akg('accountHeaderColor', $atts),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'default' => [
			'selector' => blc_call_fn([
				'fn' => 'blocksy_assemble_selector',
				'default' => $root_selector
			], $root_selector),
			'variable' => 'linkInitialColor'
		],

		'hover' => [
			'selector' => blc_call_fn([
				'fn' => 'blocksy_assemble_selector',
				'default' => $root_selector
			], $root_selector),
			'variable' => 'linkHoverColor'
		],
	],
	'responsive' => true
]);

blocksy_output_colors([
	'value' => blocksy_akg('header_account_icon_color', $atts),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'variables' => [
		'default' => [
			'selector' => blocksy_assemble_selector($root_selector),
			'variable' => 'icon-color'
		],

		'hover' => [
			'selector' => blocksy_assemble_selector($root_selector),
			'variable' => 'icon-hover-color'
		],
	],
	'responsive' => true
]);

// transparent state
if (isset($has_transparent_header) && $has_transparent_header) {
	blocksy_output_colors([
		'value' => blocksy_akg('transparentAccountHeaderColor', $atts),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,

		'variables' => [
			'default' => [
				'selector' => blc_call_fn([
					'fn' => 'blocksy_assemble_selector',
					'default' => $root_selector
				], blc_call_fn([
					'fn' => 'blocksy_mutate_selector',
				], [
					'selector' => $root_selector,
					'to_add' => '[data-transparent-row="yes"]'
				])),
				'variable' => 'linkInitialColor'
			],

			'hover' => [
				'selector' => blc_call_fn([
					'fn' => 'blocksy_assemble_selector',
					'default' => $root_selector
				], blc_call_fn([
					'fn' => 'blocksy_mutate_selector',
				], [
					'selector' => $root_selector,
					'to_add' => '[data-transparent-row="yes"]'
				])),
				'variable' => 'linkHoverColor'
			],
		],
		'responsive' => true
	]);

	blocksy_output_colors([
		'value' => blocksy_akg('transparent_header_account_icon_color', $atts),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,

		'variables' => [
			'default' => [
				'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'between',
					'to_add' => '[data-transparent-row="yes"]'
				])),
				'variable' => 'icon-color'
			],

			'hover' => [
				'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'between',
					'to_add' => '[data-transparent-row="yes"]'
				])),
				'variable' => 'icon-hover-color'
			],
		],
		'responsive' => true
	]);
}

// sticky state
if (isset($has_sticky_header) && $has_sticky_header) {
	blocksy_output_colors([
		'value' => blocksy_akg('stickyAccountHeaderColor', $atts),
		'default' => [
			'default' => ['color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT')],
			'hover' => ['color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT')],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,

		'variables' => [
			'default' => [
				'selector' => blc_call_fn([
					'fn' => 'blocksy_assemble_selector',
					'default' => $root_selector
				], blc_call_fn([
					'fn' => 'blocksy_mutate_selector',
				], [
					'selector' => $root_selector,
					'to_add' => '[data-sticky*="yes"]'
				])),
				'variable' => 'linkInitialColor'
			],

			'hover' => [
				'selector' => blc_call_fn([
					'fn' => 'blocksy_assemble_selector',
					'default' => $root_selector
				], blc_call_fn([
					'fn' => 'blocksy_mutate_selector',
				], [
					'selector' => $root_selector,
					'to_add' => '[data-sticky*="yes"]'
				])),
				'variable' => 'linkHoverColor'
			],
		],
		'responsive' => true
	]);

	blocksy_output_colors([
		'value' => blocksy_akg('sticky_header_account_icon_color', $atts),
		'default' => [
			'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
			'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		],
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,

		'variables' => [
			'default' => [
				'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'between',
					'to_add' => '[data-sticky*="yes"]'
				])),
				'variable' => 'icon-color'
			],

			'hover' => [
				'selector' => blocksy_assemble_selector(blocksy_mutate_selector([
					'selector' => $root_selector,
					'operation' => 'between',
					'to_add' => '[data-sticky*="yes"]'
				])),
				'variable' => 'icon-hover-color'
			],
		],
		'responsive' => true
	]);
}

blocksy_output_colors([
	'value' => blocksy_akg('account_modal_font_color', $atts),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,

	'variables' => [
		'default' => [
			'selector' => blc_call_fn([
				'fn' => 'blocksy_assemble_selector',
				'default' => $root_selector
			], blc_call_fn([
				'fn' => 'blocksy_mutate_selector',
			], [
				'selector' => [$root_selector[0]],
				'operation' => 'suffix',
				'to_add' => '#account-modal .ct-account-form'
			])),
			'variable' => 'color'
		],

		'hover' => [
			'selector' => blc_call_fn([
				'fn' => 'blocksy_assemble_selector',
				'default' => $root_selector
			], blc_call_fn([
				'fn' => 'blocksy_mutate_selector',
			], [
				'selector' => [$root_selector[0]],
				'operation' => 'suffix',
				'to_add' => '#account-modal .ct-account-form'
			])),
			'variable' => 'linkHoverColor'
		]
	],
]);

blocksy_output_colors([
	'value' => blocksy_akg('account_close_button_color', $atts),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,

	'variables' => [
		'default' => [
			'selector' => blc_call_fn([
				'fn' => 'blocksy_assemble_selector',
				'default' => $root_selector
			], blc_call_fn([
				'fn' => 'blocksy_mutate_selector',
			], [
				'selector' => [$root_selector[0]],
				'operation' => 'suffix',
				'to_add' => '#account-modal .ct-close-button'
			])),
			'variable' => 'icon-color'
		],

		'hover' => [
			'selector' => blc_call_fn([
				'fn' => 'blocksy_assemble_selector',
				'default' => $root_selector
			], blc_call_fn([
				'fn' => 'blocksy_mutate_selector',
			], [
				'selector' => [$root_selector[0]],
				'operation' => 'suffix',
				'to_add' => '#account-modal .ct-close-button'
			])),
			'variable' => 'icon-hover-color'
		]
	],
]);

blocksy_output_colors([
	'value' => blocksy_akg('account_close_button_shape_color', $atts),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,

	'variables' => [
		'default' => [
			'selector' => blc_call_fn([
				'fn' => 'blocksy_assemble_selector',
				'default' => $root_selector
			], blc_call_fn([
				'fn' => 'blocksy_mutate_selector',
			], [
				'selector' => [$root_selector[0]],
				'operation' => 'suffix',
				'to_add' => '#account-modal .ct-close-button'
			])),
			'variable' => 'closeButtonBackground'
		],

		'hover' => [
			'selector' => blc_call_fn([
				'fn' => 'blocksy_assemble_selector',
				'default' => $root_selector
			], blc_call_fn([
				'fn' => 'blocksy_mutate_selector',
			], [
				'selector' => [$root_selector[0]],
				'operation' => 'suffix',
				'to_add' => '#account-modal .ct-close-button'
			])),
			'variable' => 'closeButtonHoverBackground'
		]
	],
]);
