<?php

if (! function_exists('blocksy_render_archive_cards')) {
	return;
}

if (get_query_var('paged')) {
	$paged = get_query_var('paged');
} elseif (get_query_var('page')) {
	$paged = get_query_var('page');
} else {
	$paged = 1;
}

$query_args = [
	'order' => $args['order'],
	'ignore_sticky_posts' => true,
	'post_type' => explode(',', $args['post_type']),
	'orderby' => $args['orderby'],
	'posts_per_page' => $args['limit'],
	'paged' => $paged,
	'ignore_sticky_posts' => $args['ignore_sticky_posts'] === 'yes'
];

if (isset($args['post_ids']) && $args['post_ids']) {
	$query_args['post__in'] = explode(',', $args['post_ids']);
}

if (
	isset($args['term_ids']) && $args['term_ids']
	||
	isset($args['exclude_term_ids']) && $args['exclude_term_ids']
) {
	$tax_query = [];

	$to_include = [
		'relation' => 'OR'
	];

	$to_exclude = [
		'relation' => 'AND'
	];

	if (
		isset($args['term_ids']) && $args['term_ids']
		&&
		isset($args['exclude_term_ids']) && $args['exclude_term_ids']
	) {
		$tax_query['relation'] = 'AND';
	}

	if ($args['term_ids']) {
		foreach (explode(',', $args['term_ids']) as $internal_term_id) {
			$term_id = trim($internal_term_id);
			$term = get_term($term_id);

			if (! $term) {
				continue;
			}

			$to_include[] = [
				'field' => 'term_id',
				'taxonomy' => $term->taxonomy,
				'terms' => [$term_id]
			];
		}
	}

	if ($args['exclude_term_ids']) {
		foreach (explode(',', $args['exclude_term_ids']) as $internal_term_id) {
			$term_id = trim($internal_term_id);
			$term = get_term($term_id);

			if (! $term) {
				continue;
			}

			$to_exclude[] = [
				'field' => 'term_id',
				'taxonomy' => $term->taxonomy,
				'terms' => [$term_id],
				'operator' => 'NOT IN'
			];
		}
	}

	if (count($to_include) > 1) {
		$tax_query[] = $to_include;
	}
	if (count($to_exclude) > 1) {
		$tax_query[] = $to_exclude;
	}

	$query_args['tax_query'] = $tax_query;
}

$query = new WP_Query(apply_filters(
	'blocksy:general:shortcodes:blocksy-posts:args',
	$query_args
));

if ($args['view'] === 'slider') {
	$items = '';

	$posts_to_render = [];
	$images = [];

	foreach ($query->posts as $single_post) {
		$attachment_id = get_post_thumbnail_id($single_post);

		if (! $attachment_id) {
			continue;
		}

		$posts_to_render[] = $single_post;
		$images[] = $attachment_id;
	}

	$slider_args = [];

	if (intval($args['slider_autoplay'])) {
		$slider_args['autoplay'] = intval($args['slider_autoplay']);
	}

	echo blocksy_flexy(array_merge([
		'class' => 'ct-posts-shortcode',
		'images' => $images,
		'slide_image_args' => function ($index, $args) use ($posts_to_render) {
			$post = $posts_to_render[$index];
			$args['html_atts']['href'] = get_permalink($post);

			return $args;
		},
		'images_ratio' => $args['slider_image_ratio']
	], $slider_args));
} else {
	$prefix = 'blog';

	$custom_post_types = blocksy_manager()->post_types->get_supported_post_types();

	foreach ($custom_post_types as $cpt) {
		if ($cpt === explode(',', $args['post_type'])[0]) {
			$prefix = $cpt . '_archive';
		}
	}

	echo '<div class="ct-posts-shortcode" data-prefix="' . $prefix . '">';

	echo blocksy_render_archive_cards([
		'prefix' => $prefix,
		'query' => $query,
		'has_pagination' => $args['has_pagination'] === 'yes'
	]);

	wp_reset_postdata();

	echo '</div>';
}

