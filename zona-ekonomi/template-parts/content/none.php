<?php
/**
 * Message shown when there is nothing to list.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="no-results">
	<?php if ( is_search() ) : ?>
		<p><?php esc_html_e( 'Tidak ada artikel yang cocok dengan kata kunci tersebut. Coba kata kunci lain yang lebih umum.', 'zona-ekonomi' ); ?></p>
	<?php elseif ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
		<p>
			<?php
			printf(
				/* translators: %s: link to the new-post screen. */
				wp_kses( __( 'Belum ada artikel yang dipublikasikan. <a href="%s">Tulis artikel pertama Anda</a>.', 'zona-ekonomi' ), array( 'a' => array( 'href' => array() ) ) ),
				esc_url( admin_url( 'post-new.php' ) )
			);
			?>
		</p>
	<?php else : ?>
		<p><?php esc_html_e( 'Belum ada artikel di sini.', 'zona-ekonomi' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</div>
