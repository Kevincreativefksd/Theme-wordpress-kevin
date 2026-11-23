<?php
/**
 * Comments. Avatars are disabled to avoid third-party requests.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments-area container container--entry">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$ze_comment_count = (int) get_comments_number();
			echo esc_html(
				/* translators: %s: number of comments. */
				sprintf( _n( '%s komentar', '%s komentar', $ze_comment_count, 'zona-ekonomi' ), number_format_i18n( $ze_comment_count ) )
			);
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 0,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Komentar ditutup.', 'zona-ekonomi' ); ?></p>
	<?php endif; ?>

	<?php comment_form( array( 'title_reply' => __( 'Tinggalkan komentar', 'zona-ekonomi' ) ) ); ?>
</section>
