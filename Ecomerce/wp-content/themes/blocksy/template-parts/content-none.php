<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blocksy
 */

?>

<div class="entry-content">
	<?php

	if ( is_home() && current_user_can( 'publish_posts' ) ) {
		printf(
			'<p>' . wp_kses(
				/* translators: 1: link to WP admin new post page. */
				__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'blocksy' ),
				array(
					'a' => array(
						'href' => array(),
					),
				)
			) . '</p>',
			esc_url( admin_url( 'post-new.php' ) )
		);

	} elseif ( is_search() ) {
		get_search_form();
	} else {
		get_search_form();
	} ?>
</div>
