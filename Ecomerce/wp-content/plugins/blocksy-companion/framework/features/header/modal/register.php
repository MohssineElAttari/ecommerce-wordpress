<form name="registerform" id="registerform" action="<?php echo wp_registration_url() ?>" method="post" novalidate="novalidate">
	<?php do_action('woocommerce_register_form_start') ?>
	<?php do_action('blocksy:account:modal:register:start'); ?>

	<p>
		<label for="user_login_register"><?php echo __('Username', 'blc') ?></label>
		<input type="text" name="user_login" id="user_login_register" class="input" value="" size="20" autocapitalize="off">
	</p>

	<p>
		<label for="user_email"><?php echo __('Email', 'blc') ?></label>
		<input type="email" name="user_email" id="user_email" class="input" value="" size="25">
	</p>

	<?php do_action('register_form') ?>

	<p id="reg_passmail">
		<?php echo __('Registration confirmation will be emailed to you', 'blc') ?>
	</p>

	<p>
		<button name="wp-submit" class="ct-button">
			<?php echo __('Register', 'blc') ?>
		</button>

		<!-- <input type="hidden" name="redirect_to" value="<?php echo blocksy_current_url() ?>"> -->
	</p>

	<?php do_action('blocksy:account:modal:register:end'); ?>
	<?php do_action('woocommerce_register_form_end') ?>
</form>

