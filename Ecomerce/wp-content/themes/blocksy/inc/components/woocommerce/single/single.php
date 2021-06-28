<?php

function blocksy_woocommerce_has_flexy_view() {
	global $blocksy_is_quick_view;

	if ($blocksy_is_quick_view) {
		return true;
	}

	if (is_customize_preview() && wp_doing_ajax()) {
		return true;
	}

	if (
		(is_product() || wp_doing_ajax())
		&&
		! blocksy_manager()->screen->uses_woo_default_template()
		&&
		! is_customize_preview()
	) {
		return false;
	}

	return ! apply_filters('blocky:woocommerce:product-view:use-default', false);
}

remove_action(
	'woocommerce_single_product_summary',
	'woocommerce_template_single_meta',
	40
);

if (! wp_doing_ajax()) {
	add_filter('template_include', function ($template) {
		if (blocksy_woocommerce_has_flexy_view()) {
			remove_action(
				'woocommerce_product_thumbnails',
				'woocommerce_show_product_thumbnails',
				20
			);
		}

		return $template;
	}, 900000009);
} else {
	add_action('init', function () {
		if (blocksy_woocommerce_has_flexy_view()) {
			remove_action(
				'woocommerce_product_thumbnails',
				'woocommerce_show_product_thumbnails',
				20
			);
		}
    });
}

$action_to_hook = 'wp';

if (wp_doing_ajax()) {
	$action_to_hook = 'init';
}

add_action($action_to_hook, function () {
	if (get_theme_mod('woo_has_product_tabs', 'yes') === 'no') {
		add_filter('woocommerce_product_tabs', function ($tabs) {
			return [];
		}, 99);
	}

	if (get_theme_mod('has_product_single_rating', 'yes') === 'no') {
		remove_action(
			'woocommerce_single_product_summary',
			'woocommerce_template_single_rating',
			10
		);
	}

	if (get_theme_mod('has_product_single_title', 'yes') === 'no') {
		remove_action(
			'woocommerce_single_product_summary',
			'woocommerce_template_single_title',
			5
		);
	}
}, 9000000000);

add_action(
	'woocommerce_single_product_summary',
	function () {
		if (get_theme_mod('has_product_single_meta', 'yes') === 'yes') {
			woocommerce_template_single_meta();
		}
	},
	39
);

add_action(
	'woocommerce_single_product_summary',
	function () {
		do_action('blocksy:woocommerce:product-single:excerpt:before');
	},
	19
);

add_action(
	'woocommerce_single_product_summary',
	function () {
		do_action('blocksy:woocommerce:product-single:excerpt:after');
	},
	21
);

add_action(
	'woocommerce_after_single_product_summary',
	function () {
		do_action('blocksy:woocommerce:product-single:tabs:before');
	},
	9
);

add_action(
	'woocommerce_after_single_product_summary',
	function () {
		do_action('blocksy:woocommerce:product-single:tabs:after');
	},
	11
);

add_action(
	'woocommerce_before_single_product_summary',
	function () {
		echo '<div class="product-entry-wrapper">';

		global $product;

		if ($product->is_in_stock()) {
			if (get_theme_mod('has_product_single_onsale', 'yes') === 'yes') {
				woocommerce_show_product_sale_flash();
			}
		} else {
			echo '<span class="out-of-stock-badge">' . __('Out of stock', 'blocksy') . '</span>';
		}
	},
	1
);

add_action(
	'woocommerce_after_single_product_summary',
	function () {
		echo '</div>';
	},
	1
);

remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

add_action(
	'woocommerce_after_main_content',
	'blocksy_woo_single_product_after_main_content',
	5
);

if (! function_exists('blocksy_woo_single_product_after_main_content')) {
	function blocksy_woo_single_product_after_main_content() {
		if (is_product()) {
			woocommerce_upsell_display();
			woocommerce_output_related_products();
		}
	}
}

function blocksy_woo_single_post_class($classes, $product) {
	if (! is_product()) {
		return $classes;
	}

	if (blocksy_woocommerce_has_flexy_view()) {
		if (count($product->get_gallery_image_ids()) > 0) {
			if (get_theme_mod('gallery_style', 'horizontal') === 'vertical') {
				$classes[] = 'thumbs-left';
			} else {
				$classes[] = 'thumbs-bottom';
			}
		}
	}

	if (get_theme_mod('has_product_sticky_summary', 'no') === 'yes') {
		$classes[] = 'sticky-summary';
	}

	return $classes;
}

add_filter(
	'woocommerce_post_class',
	'blocksy_woo_single_post_class',
	999,
	2
);

add_action('woocommerce_post_class', function ($classes) {
	if (! is_product()) {
		return $classes;
	}

	global $blocksy_is_quick_view;

	if (! $blocksy_is_quick_view) {
		$classes[] = 'ct-default-gallery';
	}

	return $classes;
});

add_filter('woocommerce_output_related_products_args', function ($args) {
	$columns = intval(get_theme_mod(
		'woo_product_related_cards_columns',
		[
			'desktop' => 4,
			'tablet' => 3,
			'mobile' => 1
		]
	)['desktop']);

	$args['columns'] = $columns;
	$args['posts_per_page'] = $columns * intval(get_theme_mod(
		'woo_product_related_cards_rows',
		1
	));

	return $args;
}, 10);

add_filter('woocommerce_upsells_columns', function ($columns) {
	return intval(get_theme_mod(
		'woo_product_related_cards_columns',
		[
			'desktop' => 4,
			'tablet' => 3,
			'mobile' => 1
		]
	)['desktop']);
});
