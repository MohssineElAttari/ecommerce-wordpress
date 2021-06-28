<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blocksy
 */

if ( post_password_required() ) {
	return;
}

$entity_singular = 'comment';
$entity_plural = 'comments';

$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );

$prefix = blocksy_manager()->screen->get_prefix();

$has_website_field = get_theme_mod($prefix . '_has_comments_website', 'yes');

$website_field_class = '';
$website_field_output = '';

if ($has_website_field === 'yes') {
	$website_field_class = 'has-website-field';

	$website_field_output =
		'<p class="comment-form-input-url">
			<label for="url">' . __( 'Website', 'blocksy' ) . '</label>
			<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />
		</p>';
}

ob_start();
do_action('blocksy:comments:title:before');
$title_before = ob_get_clean();

ob_start();
do_action('blocksy:comments:title:after');
$title_after = ob_get_clean();

$form_options = [
	'format' => 'xhtml',
	'class_form' => 'comment-form ' . $website_field_class,
	'title_reply' => __('Leave a Reply', 'blocksy'),
	'cancel_reply_link' => __('Cancel Reply', 'blocksy'),

	// Title reply
	'title_reply_before' => $title_before . '<h2 id="reply-title" class="comment-reply-title">',
	'title_reply_after' => '</h2>' . $title_after,

	// Cancel reply
	'cancel_reply_before' => '<span class="ct-cancel-reply">',
	'cancel_reply_after'  => '</span>',

	// Logged in as
	'logged_in_as' => '',

	// Comment notes
	'comment_notes_before' => '',

	// Input fields
	'fields' => [
		// Author input
		'author' =>
			'<p class="comment-form-input-author">
				<label for="author">' . __( 'Name', 'blocksy' ) . ' <b class="required">&nbsp;*</b></label>
				<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />
			</p>',

		// Email input
		'email' =>
			'<p class="comment-form-input-email">
				<label for="email">' . __( 'Email', 'blocksy' ) . ' <b class="required">&nbsp;*</b></label>
				<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />
			</p>',

		'url' => $website_field_output,

		'cookies' => ''
	],

	// Textarea
	'comment_field' =>
		'<p class="comment-form-textarea">
			<label for="comment">' . __( 'Add Comment', 'blocksy' ) . '</label>
			<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' . '</textarea>
		</p>',

	// submit button
	'submit_button' => '<button type="submit" name="%1$s" id="%2$s" class="%3$s" value="%4$s">%4$s</button>',
];

if (
	has_action( 'set_comment_cookies', 'wp_set_comment_cookies' )
	&&
	get_option( 'show_comments_cookies_opt_in' )
) {
	$consent = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';

	$form_options['comment_field'] .= '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
		'<label for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time I comment.', 'blocksy') . '</label></p>';
}

if (function_exists('blocksy_ext_cookies_checkbox')) {
	$form_options['comment_field'] .= blocksy_ext_cookies_checkbox('comment');
}

do_action('blocksy:comments:before');

?>


<div class="ct-comments" id="comments">
	<?php do_action('blocksy:comments:top'); ?>

	<?php if ( have_comments() ) : ?>
		<h3 class="ct-comments-title">
			<?php comments_number( esc_html__( 'No comments yet', 'blocksy' ), __( 'One comment', 'blocksy' ), __( '% Comments', 'blocksy' ) ); ?>
		</h3>

		<ol class="ct-comment-list">
			<?php
				wp_list_comments(
					[
						'short_ping'  => true,
						'avatar_size' => 100,
						'callback' => 'blocksy_custom_comment_template',
						'end-callback' => function () {
							echo '</li>';
						}
					]
				);
			?>
		</ol>

		<?php
		// Are there comments to navigate through?
		if (get_comment_pages_count() > 1 && get_option('page_comments')) :
			?>
			<nav class="ct-comment-navigation-container">
				<h4 class="screen-reader-text section-heading">
				<?php esc_html_e( 'Comment navigation', 'blocksy' ); ?>
				</h4>

				<div class="ct-comments-navigation">
					<span class="prev">
					<?php previous_comments_link( __( '&larr; Older Comments', 'blocksy' ) ); ?>
					</span>

					<span class="next">
					<?php next_comments_link( __( 'Newer Comments &rarr;', 'blocksy' ) ); ?>
					</span>
				</div>
			</nav>
		<?php endif; // Check for comment navigation ?>

		<?php if (! comments_open() && get_comments_number()) : ?>
			<p class="no-comments">
				<?php esc_html_e( 'Comments are closed.', 'blocksy' ); ?>
			</p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php comment_form($form_options); ?>

	<?php do_action('blocksy:comments:bottom'); ?>

</div>

<?php do_action('blocksy:comments:after'); ?>
