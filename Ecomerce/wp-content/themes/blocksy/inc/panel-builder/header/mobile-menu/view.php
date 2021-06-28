<?php

$mobile_menu_type = blocksy_default_akg('mobile_menu_type', $atts, 'type-1');

$attr['data-type'] = $mobile_menu_type;


ob_start();

$menu_args = [];

$menu = blocksy_default_akg('menu', $atts, 'blocksy_location');

if ($menu === 'blocksy_location') {
} else {
	$menu_args['menu'] = $menu;
}

if ($mobile_menu_type === 'type-2') {
	$menu_args['child_indicator_type'] = 'bordered';
}

add_filter(
	'nav_menu_item_title',
	'blocksy_handle_nav_menu_item_title',
	10, 4
);

wp_nav_menu($menu === 'blocksy_location' ? array_merge([
	'container' => false,
	'menu_class' => false,
	'fallback_cb' => 'blocksy_main_menu_fallback',
	'blocksy_advanced_item' => true,
	'theme_location' => 'menu_mobile'
], $menu_args) : array_merge([
	'container' => false,
	'menu_class' => false,
	'fallback_cb' => 'blocksy_main_menu_fallback',
	'blocksy_advanced_item' => true
], $menu_args));

remove_filter(
	'nav_menu_item_title',
	'blocksy_handle_nav_menu_item_title',
	10, 4
);

$menu_output = ob_get_clean();

$class = 'mobile-menu';

if (strpos($menu_output, 'sub-menu')) {
	$class .= ' has-submenu';
}

?>

<nav class="<?php echo $class ?>" <?php echo blocksy_attr_to_html($attr) ?>>
	<?php echo $menu_output ?>
</nav>
