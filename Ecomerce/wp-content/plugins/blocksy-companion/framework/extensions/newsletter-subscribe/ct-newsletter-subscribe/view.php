<?php
/**
 * Newsletter Subscribe widget
 *
 * @copyright 2019-present Creative Themes
 * @license   http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package Blocksy
 */


// Widget title
$title = blocksy_default_akg( 'title', $atts, __( 'Newsletter', 'blc' ) );


// Message
$message = blocksy_default_akg( 'newsletter_subscribe_text', $atts, __( 'Enter your email address below to subscribe to our newsletter', 'blc' ) );

// Button text
$button_text = blocksy_default_akg( 'newsletter_subscribe_button_text', $atts, __( 'Subscribe', 'blc' ) );

// Form name
$has_name = blocksy_default_akg( 'has_newsletter_subscribe_name', $atts, 'no' ) === 'yes';

$list_id = null;

if (blocksy_default_akg(
	'newsletter_subscribe_list_id_source',
	$atts,
	'default'
) === 'custom') {
	$list_id = blocksy_default_akg('newsletter_subscribe_list_id', $atts, '');
}

$manager = BlocksyNewsletterManager::get_for_settings();

// Button value
$provider_data = $manager->get_form_url_and_gdpr_for($list_id);

if (! $provider_data) {
	return;
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

// container type
$container = blocksy_default_akg('newsletter_subscribe_container', $atts, 'default');

$data_container = '';

if ( $container !== 'default' ) {
	$data_container = ' data-container=' . $container;
}

// Content alignment
$alignment = blocksy_default_akg('newsletter_subscribe_alignment', $atts, 'left');

$name_label = blocksy_default_akg('newsletter_subscribe_name_label', $atts, __('Your name', 'blc'));
$email_label = blocksy_default_akg('newsletter_subscribe_mail_label', $atts, __('Your email', 'blc'));

$data_alignment = '';

if ($alignment !== 'left') {
	$data_alignment = ' data-alignment=' . $alignment;
}

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $before_widget;

echo '<div class="ct-widget-inner"' . $data_alignment . '' . $data_container . '>';

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $before_title . wp_kses_post($title) . $after_title;


?>

	<form
		action="<?php echo esc_attr($form_url) ?>"
		method="post"
		class="ct-newsletter-subscribe-widget-form"
		target="_blank"
		data-provider="<?php echo $provider_data['provider'] ?>"
		<?php echo $skip_submit_output ?>>

	<?php if (! empty($message)) { ?>
		<div class="ct-newsletter-subscribe-description">
			<?php echo wp_kses_post($message) ?>
		</div>
	<?php } ?>

	<?php if ($has_name) { ?>
		<input type="text" name="FNAME" placeholder="<?php esc_attr_e($name_label, 'blc'); ?>" title="<?php echo __('Name', 'blocksy') ?>" />
	<?php } ?>

	<input type="email" name="EMAIL" placeholder="<?php esc_attr_e($email_label, 'blc'); ?> *" title="<?php echo __('Email', 'blocksy') ?>" required />

	<button class="button">
		<?php echo esc_html($button_text) ?>
	</button>

	<div class="ct-newsletter-subscribe-message"></div>

	<?php
		if (function_exists('blocksy_ext_cookies_checkbox')) {
			echo blocksy_ext_cookies_checkbox('newsletter-subscribe');
		}
	?>
</form>

</div>

<?php echo wp_kses_post($after_widget); ?>
