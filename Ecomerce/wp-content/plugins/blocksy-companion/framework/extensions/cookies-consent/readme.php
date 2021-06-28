<h2><?php echo __('Instructions', 'blc'); ?></h2>

<p>
	<?php echo __('After installing and activating the Cookies Consent extension you will be able to configure it from this location:', 'blc') ?>
</p>

<ul class="ct-modal-list">
	<li>
		<h4><?php echo __('Customizer', 'blc') ?></h4>
		<i>
		<?php
			echo sprintf(
				__('Navigate to %s and customize the notification to meet your needs.', 'blc'),
				sprintf(
					'<code>%s</code>',
					__('Customizer ➝ Cookie Consent', 'blc')
				)
			);
		?>
		</i>
	</li>
</ul>