<?php

function blc_output_newsletter_subscribe_form_cache() {
	if (! is_customize_preview()) return;

	blocksy_add_customizer_preview_cache(
		blocksy_html_tag(
			'div',
			[ 'data-id' => 'blocksy-mailchimp-subscribe' ],
			blc_ext_newsletter_subscribe_form(true)
		)
	);
}

function blc_ext_newsletter_subscribe_form($forced = false) {
	if (! $forced) {
		blc_output_newsletter_subscribe_form_cache();
	}

	if (get_theme_mod('newsletter_subscribe_single_post_enabled', 'yes') !== 'yes') {
		if (! $forced) {
			return '';
		}
	}

	if (
		blocksy_default_akg(
			'disable_subscribe_form',
			blc_call_fn([
				'fn' => 'blocksy_get_post_options',
				'default' => 'array'
			]),
			'no'
		) === 'yes'
	) {
		return '';
	}

	$args = [
		'title' => get_theme_mod(
			'newsletter_subscribe_title',
			__('Newsletter Updates', 'blc')
		),

		'description' => get_theme_mod('newsletter_subscribe_text', __(
			'Enter your email address below to subscribe to our newsletter',
			'blc'
		)),

		'button_text' => get_theme_mod(
			'newsletter_subscribe_button_text',
			__('Subscribe', 'blc')
		),
		'has_name' => get_theme_mod('has_newsletter_subscribe_name', 'no'),
		'name_label' => get_theme_mod(
			'newsletter_subscribe_name_label',
			__('Your name', 'blc')
		),
		'email_label' => get_theme_mod(
			'newsletter_subscribe_mail_label',
			__('Your email', 'blc')
		)
	];

	if ($forced) {
		$args['has_name'] = 'yes';
	}

	$list_id = null;

	if (get_theme_mod(
		'newsletter_subscribe_list_id_source',
		'default'
	) === 'custom') {
		$args['list_id'] = get_theme_mod('newsletter_subscribe_list_id', '');
	}


	$args['class'] = 'ct-newsletter-subscribe-block ' . blc_call_fn(
		['fn' => 'blocksy_visibility_classes'],
		get_theme_mod('newsletter_subscribe_subscribe_visibility', [
			'desktop' => true,
			'tablet' => true,
			'mobile' => false,
		])
	);

	return blc_ext_newsletter_subscribe_output_form($args);
}

function blc_ext_newsletter_subscribe_output_form($args = []) {
	$args = wp_parse_args($args, [
		'has_title' => true,
		'has_description' => true,

		'title' => __('Newsletter Updates', 'blc'),
		'description' => __(
			'Enter your email address below to subscribe to our newsletter',
			'blc'
		),
		'button_text' => __(
			'Subscribe', 'blc'
		),

		// no | yes
		'has_name' => 'no',

		'name_label' => __('Your name', 'blc'),
		'email_label' => __('Your email', 'blc'),
		'list_id' => '',
		'class' => ''
	]);

	$has_name = $args['has_name'] === 'yes';

	$manager = BlocksyNewsletterManager::get_for_settings();
	$provider_data = $manager->get_form_url_and_gdpr_for($args['list_id']);

	if (! $provider_data) {
		return '';
	}

	if ($provider_data['provider'] === 'mailerlite') {
		$settings = $manager->get_settings();
		$provider_data['provider'] .= ':' . $settings['list_id'];
	}

	$form_url = $provider_data['form_url'];
	$has_gdpr_fields = $provider_data['has_gdpr_fields'];

	$skip_submit_output = '';

	if ($has_gdpr_fields) {
		$skip_submit_output = 'data-skip-submit';
	}

	$fields_number = '1';

	if ($has_name) {
		$fields_number = '2';
	}

	ob_start();

	?>

	<div class="<?php echo esc_attr(trim($args['class'])) ?>">
		<?php if ($args['has_title']) { ?>
			<h3><?php echo esc_html($args['title']) ?></h3>
		<?php } ?>

		<?php if ($args['has_description']) { ?>
			<p class="ct-newsletter-subscribe-description">
				<?php echo $args['description'] ?>
			</p>
		<?php } ?>

		<form target="_blank" action="<?php echo esc_attr($form_url) ?>" method="post"
			data-provider="<?php echo $provider_data['provider'] ?>"
			class="ct-newsletter-subscribe-block-form" <?php echo $skip_submit_output ?>>
			<section data-fields="<?php echo $fields_number ?>">
				<?php if ($has_name) { ?>
					<input type="text" name="FNAME" placeholder="<?php esc_attr_e($args['name_label'], 'blc'); ?>" title="<?php echo __('Name', 'blc') ?>" />
				<?php } ?>

				<input type="email" name="EMAIL" placeholder="<?php esc_attr_e($args['email_label'], 'blc'); ?> *" title="<?php echo __('Email', 'blc') ?>" required />

				<button class="button">
					<?php echo esc_html($args['button_text']) ?>
				</button>
			</section>

			<div class="ct-newsletter-subscribe-message"></div>

			<?php
				if (function_exists('blocksy_ext_cookies_checkbox')) {
					echo blocksy_ext_cookies_checkbox('subscribe');
				}
			?>
		</form>

	</div>

	<?php

	return ob_get_clean();
}
