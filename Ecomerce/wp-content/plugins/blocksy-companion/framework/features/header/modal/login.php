<?php

// wp_login_form([]);

$redirect_to_url = apply_filters(
	'blocksy:account:modal:login:redirect_to',
	blocksy_current_url()
);

?>

<form name="loginform" id="loginform" action="<?php echo wp_login_url() ?>" method="post">
	<?php do_action('woocommerce_login_form_start'); ?>
	<?php do_action('blocksy:account:modal:login:start'); ?>

	<p class="login-username">
		<label for="user_login"><?php echo __('Email Address', 'blc') ?></label>
		<input type="text" name="log" id="user_login" class="input" value="" size="20">
	</p>

	<p class="login-password">
		<label for="user_pass"><?php echo __('Password', 'blc') ?></label>
		<input type="password" name="pwd" id="user_pass" class="input" value="" size="20">
	</p>

	<p class="login-remember col-2">
		<label>
			<input name="rememberme" type="checkbox" id="rememberme" value="forever">
			<?php echo __('Remember Me', 'blc') ?>
		</label>

		<a href="<?php echo wp_lostpassword_url() ?>" class="ct-forgot-password">
			<?php echo __('Forgot Password?', 'blc') ?>
		</a>
	</p>

	<?php do_action('login_form') ?>

	<p class="login-submit">
		<button name="wp-submit" class="ct-button">
			<?php echo __('Log In', 'blc') ?>
		</button>

		<input type="hidden" name="redirect_to" value="<?php echo $redirect_to_url ?>">
	</p>

	<?php do_action('blocksy:account:modal:login:end'); ?>
	<?php do_action('woocommerce_login_form_end'); ?>
</form>

