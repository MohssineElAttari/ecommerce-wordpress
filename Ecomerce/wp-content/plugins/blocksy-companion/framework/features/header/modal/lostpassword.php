<form name="lostpasswordform" id="lostpasswordform" action="<?php echo wp_lostpassword_url() ?>" method="post">
	<?php do_action('blocksy:account:modal:lostpassword:start'); ?>

	<p>
		<label for="user_login_forgot"><?php echo __('Username or Email Address', 'blc')?></label>
		<input type="text" name="user_login" id="user_login_forgot" class="input" value="" size="20" autocapitalize="off" required>
	</p>

	<p>
		<button name="wp-submit" class="ct-button">
			<?php echo __('Get New Password', 'blc') ?>
		</button>

		<!-- <input type="hidden" name="redirect_to" value="<?php echo blocksy_current_url() ?>"> -->
	</p>

	<?php do_action('blocksy:account:modal:lostpassword:end'); ?>
</form>

