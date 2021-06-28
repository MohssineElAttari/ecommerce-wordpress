<?php

// Mailchimp
blc_call_fn(['fn' => 'blocksy_output_colors'], [
	'value' => get_theme_mod('newsletter_subscribe_content'),
	'default' => [
		'default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
		'hover' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-newsletter-subscribe-block',
			'variable' => 'color'
		],

		'hover' => [
			'selector' => '.ct-newsletter-subscribe-block',
			'variable' => 'linkHoverColor'
		],
	],
]);

blc_call_fn(['fn' => 'blocksy_output_colors'], [
	'value' => get_theme_mod('newsletter_subscribe_button'),
	'default' => [
		'default' => [ 'color' => 'var(--paletteColor1)' ],
		'hover' => [ 'color' => 'var(--paletteColor2)' ],
	],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-newsletter-subscribe-block',
			'variable' => 'buttonInitialColor'
		],

		'hover' => [
			'selector' => '.ct-newsletter-subscribe-block',
			'variable' => 'buttonHoverColor'
		]
	],
]);

blc_call_fn(['fn' => 'blocksy_output_colors'], [
	'value' => get_theme_mod('newsletter_subscribe_background'),
	'default' => ['default' => [ 'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT') ]],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-newsletter-subscribe-block',
			'variable' => 'backgroundColor'
		],
	],
]);

blc_call_fn(['fn' => 'blocksy_output_colors'], [
	'value' => get_theme_mod('newsletter_subscribe_shadow'),
	'default' => ['default' => [ 'color' => 'rgba(210, 213, 218, 0.4)' ]],
	'css' => $css,
	'variables' => [
		'default' => [
			'selector' => '.ct-newsletter-subscribe-block',
			'variable' => 'mailchimpShadow'
		],
	],
]);

blc_call_fn(['fn' => 'blocksy_output_box_shadow'], [
	'css' => $css,
	'tablet_css' => $tablet_css,
	'mobile_css' => $mobile_css,
	'selector' => '.ct-newsletter-subscribe-block',
	'value' => get_theme_mod(
		'newsletter_subscribe_shadow',
		blc_call_fn(['fn' => 'blocksy_box_shadow_value'], [
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
	),
	'responsive' => true
]);

$block_inner_spacing = get_theme_mod('newsletter_subscribe_spacing', 30);

if ($block_inner_spacing !== 30) {
	blc_call_fn(['fn' => 'blocksy_output_responsive'], [
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'selector' => '.ct-newsletter-subscribe-block',
		'variableName' => 'padding',
		'value' => $block_inner_spacing,
		'unit' => 'px'
	]);
}