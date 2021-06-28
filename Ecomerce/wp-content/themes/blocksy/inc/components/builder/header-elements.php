<?php

class Blocksy_Header_Builder_Elements {
	private $current_section_id = null;

	public function __construct($args = []) {
		$args = wp_parse_args($args, [
			'current_section_id' => null
		]);

		$this->current_section_id = $args['current_section_id'];
	}

	public function render_offcanvas($args = []) {
		$args = wp_parse_args($args, [
			'has_container' => true,
			'device' => 'mobile'
		]);

		$render = new Blocksy_Header_Builder_Render([
			'current_section_id' => $this->current_section_id
		]);

		if (! $render->contains_item('trigger')) {
			if (! is_customize_preview()) {
				return '';
			}
		}

		$mobile_content = '';
		$desktop_content = '';

		$current_layout = $render->get_current_section()['mobile'];

		foreach ($current_layout as $row) {
			if ($row['id'] !== 'offcanvas') {
				continue;
			}

			if ($render->is_row_empty($row)) {
				// return '';
			}

			$mobile_content .= $render->render_items_collection(
				$row['placements'][0]['items']
			);
		}

		$current_layout = $render->get_current_section()['desktop'];

		foreach ($current_layout as $row) {
			if ($row['id'] !== 'offcanvas') {
				continue;
			}

			if (! empty($desktop_content)) {
				continue;
			}

			$desktop_content = $render->render_items_collection(
				$row['placements'][0]['items']
			);
		}

		$atts = $render->get_item_data_for('offcanvas');
		$row_config = $render->get_item_config_for('offcanvas');

		$class = 'ct-panel ct-header';
		$behavior = 'modal';

		$position_output = [];

		if (blocksy_default_akg('offcanvas_behavior', $atts, 'panel') !== 'modal') {
			$behavior = blocksy_default_akg(
				'side_panel_position', $atts, 'right'
			) . '-side';
		}

		ob_start();
		do_action('blocksy:header:offcanvas:desktop:top');
		$desktop_content = ob_get_clean() . $desktop_content;

		ob_start();
		do_action('blocksy:header:offcanvas:desktop:bottom');
		$desktop_content = $desktop_content . ob_get_clean();

		ob_start();
		do_action('blocksy:header:offcanvas:mobile:top');
		$mobile_content = ob_get_clean() . $mobile_content;

		ob_start();
		do_action('blocksy:header:offcanvas:mobile:bottom');
		$mobile_content = $mobile_content . ob_get_clean();

		$without_container = blocksy_html_tag(
			'div',
			array_merge(
				[
					'class' => 'ct-panel-content',
					'data-device' => 'desktop'
				],
				is_customize_preview() ? [
					'data-item-label' => $row_config['config']['name'],
					'data-location' => $render->get_customizer_location_for('offcanvas')
				] : []
			),
			$desktop_content
		) . blocksy_html_tag(
			'div',
			array_merge(
				[
					'class' => 'ct-panel-content',
					'data-device' => 'mobile'
				],
				is_customize_preview() ? [
					'data-item-label' => $row_config['config']['name'],
					'data-location' => $render->get_customizer_location_for('offcanvas')
				] : []
			),
			$mobile_content
		);

		$without_container = '
		<div class="ct-panel-actions">
			<span class="ct-close-button">
				<svg class="ct-icon" width="12" height="12" viewBox="0 0 15 15">
					<path d="M1 15a1 1 0 01-.71-.29 1 1 0 010-1.41l5.8-5.8-5.8-5.8A1 1 0 011.7.29l5.8 5.8 5.8-5.8a1 1 0 011.41 1.41l-5.8 5.8 5.8 5.8a1 1 0 01-1.41 1.41l-5.8-5.8-5.8 5.8A1 1 0 011 15z"/>
				</svg>
			</span>
		</div>
		' .  $without_container;

		if (blocksy_default_akg(
			'offcanvas_behavior',
			$atts,
			'panel'
		) === 'panel') {
			$without_container = '<section>' . $without_container . '</section>';
		}

		if (! $args['has_container']) {
			return $without_container;
		}

		return blocksy_html_tag(
			'div',
			array_merge(
				[
					'id' => 'offcanvas',
					'class' => $class,
					'data-behaviour' => $behavior,
					'data-device' => $args['device']
				],
				$position_output
			),
			$without_container
		);
	}

	public function render_search_modal() {
		$render = new Blocksy_Header_Builder_Render([
			'current_section_id' => $this->current_section_id
		]);

		if (! $render->contains_item('search')) {
			return;
		}

		$atts = $render->get_item_data_for('search');

		$search_through = blocksy_akg('search_through', $atts, [
			'post' => true,
			'page' => true,
			'product' => true
		]);

		$search_placeholder = blocksy_akg(
			'header_search_placeholder',
			$atts,
			__('Search', 'blocksy')
		);

		$all_cpts = blocksy_manager()->post_types->get_supported_post_types();

		if (function_exists('is_bbpress')) {
			$all_cpts[] = 'forum';
			$all_cpts[] = 'topic';
			$all_cpts[] = 'reply';
		}

		foreach ($all_cpts as $single_cpt) {
			if (! isset($search_through[$single_cpt])) {
				$search_through[$single_cpt] = true;
			}
		}

		$post_type = [];

		foreach ($search_through as $single_post_type => $enabled) {
			if (! $enabled) {
				continue;
			}

			$post_type[] = $single_post_type;
		}

		?>

		<div id="search-modal" class="ct-panel" data-behaviour="modal">
			<div class="ct-panel-actions">
				<span class="ct-close-button">
					<svg class="ct-icon" width="12" height="12" viewBox="0 0 15 15">
						<path d="M1 15a1 1 0 01-.71-.29 1 1 0 010-1.41l5.8-5.8-5.8-5.8A1 1 0 011.7.29l5.8 5.8 5.8-5.8a1 1 0 011.41 1.41l-5.8 5.8 5.8 5.8a1 1 0 01-1.41 1.41l-5.8-5.8-5.8 5.8A1 1 0 011 15z"/>
					</svg>
				</span>
			</div>

			<div class="ct-panel-content">
				<?php get_search_form([
					'enable_search_field_class' => true,
					'live_results_attr' => blocksy_akg('searchHeaderImages', $atts, 'yes') === 'yes' ? 'thumbs' : '',
					'ct_post_type' => $post_type,
					'search_placeholder' => $search_placeholder
				]); ?>
			</div>
		</div>

		<?php
	}

	public function render_cart_offcanvas($args = []) {
		$args = wp_parse_args($args, [
			'has_container' => true,
			'device' => 'mobile'
		]);

		$render = new Blocksy_Header_Builder_Render([
			'current_section_id' => $this->current_section_id
		]);

		if (! $render->contains_item('cart')) {
			return '';
		}

		if (! function_exists('woocommerce_mini_cart')) {
			return '';
		}

		$atts = $render->get_item_data_for('cart');

		$has_cart_dropdown = blocksy_default_akg(
			'has_cart_dropdown',
			$atts,
			'yes'
		) === 'yes';

		$cart_drawer_type = blocksy_default_akg('cart_drawer_type', $atts, 'dropdown');

		if (! $has_cart_dropdown) {
			return;
		}

		if ($cart_drawer_type !== 'offcanvas') {
			return;
		}

		if (blocksy_default_akg('has_cart_panel_quantity', $atts, 'no') === 'yes') {
			add_filter(
				'woocommerce_widget_cart_item_quantity',
				'blocksy_add_minicart_quantity_fields',
				10, 3
			);
		}

		global $blocksy_is_offcanvas_cart;
		$blocksy_is_offcanvas_cart = true;

		ob_start();
		woocommerce_mini_cart();
		$content = ob_get_clean();

		remove_filter(
			'woocommerce_widget_cart_item_quantity',
			'blocksy_add_minicart_quantity_fields',
			10, 3
		);

		$class = 'ct-panel';
		$behavior = 'modal';

		$position_output = [];

		if (blocksy_default_akg('offcanvas_behavior', $atts, 'panel') !== 'modal') {
			$behavior = blocksy_default_akg(
				'cart_panel_position',
				$atts,
				'right'
			) . '-side';
		}

		$without_container = blocksy_html_tag(
			'div',
			array_merge([
				'class' => 'ct-panel-content',
			]),
			$content
		);

		if (! $args['has_container']) {
			return $without_container;
		}

		return blocksy_html_tag(
			'div',
			array_merge(
				[
					'id' => 'woo-cart-panel',
					'class' => $class,
					'data-behaviour' => $behavior
				],
				$position_output
			),

			'<section>
				<div class="ct-panel-actions">
					<h6>' . __('Shopping Cart', 'blocksy') . '</h6>

					<span class="ct-close-button">
						<svg class="ct-icon" width="12" height="12" viewBox="0 0 15 15">
							<path d="M1 15a1 1 0 01-.71-.29 1 1 0 010-1.41l5.8-5.8-5.8-5.8A1 1 0 011.7.29l5.8 5.8 5.8-5.8a1 1 0 011.41 1.41l-5.8 5.8 5.8 5.8a1 1 0 01-1.41 1.41l-5.8-5.8-5.8 5.8A1 1 0 011 15z"/>
						</svg>
					</span>
				</div>
			'
			. $without_container .

			'</section>'
		);
	}
}
