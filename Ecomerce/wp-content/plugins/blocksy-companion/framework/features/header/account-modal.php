<?php

$users_can_register = get_option('users_can_register');

if (get_option('woocommerce_enable_myaccount_registration') === 'yes') {
	$users_can_register = true;
}

$form_views = [
	'login' => '',
	'register' => '',
	'lostpassword' => ''
];

foreach ($form_views as $form_key => $value) {
	$form_views[$form_key] = apply_filters(
		'blocksy:header:account-modal:views:' . $form_key . '-form',
		blocksy_render_view(
			dirname(__FILE__) . '/modal/' . $form_key . '.php',
			[]
		)
	);
}

?>

<div id="account-modal" class="ct-panel" data-behaviour="modal">
	<div class="ct-panel-actions">
		<span class="ct-close-button">
			<svg class="ct-icon" width="12" height="12" viewBox="0 0 15 15">
				<path d="M1 15a1 1 0 01-.71-.29 1 1 0 010-1.41l5.8-5.8-5.8-5.8A1 1 0 011.7.29l5.8 5.8 5.8-5.8a1 1 0 011.41 1.41l-5.8 5.8 5.8 5.8a1 1 0 01-1.41 1.41l-5.8-5.8-5.8 5.8A1 1 0 011 15z"/>
			</svg>
		</span>
	</div>

	<div class="ct-panel-content">
		<div class="ct-account-form">
			<?php if ($users_can_register) { ?>
				<ul>
					<li class="active ct-login">
						<?php echo __('Login', 'blc') ?>
					</li>

					<li class="ct-register">
						<?php echo __('Sign Up', 'blc') ?>
					</li>
				</ul>
			<?php } ?>

			<section class="ct-login-form active">
				<?php echo $form_views['login'] ?>
			</section>

			<?php if ($users_can_register) { ?>
				<section class="ct-register-form">
					<?php echo $form_views['register'] ?>
				</section>
			<?php } ?>

			<section class="ct-forgot-password-form">
				<?php echo $form_views['lostpassword'] ?>

				<a href="<?php echo wp_login_url() ?>" class="ct-back-to-login ct-login">
					← <?php echo __('Back to login', 'blc') ?>
				</a>
			</section>
		</div>
	</div>
</div>

