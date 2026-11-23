<?php
/**
 * Latest articles grid.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ze_posts = isset( $args['posts'] ) ? $args['posts'] : array();

if ( empty( $ze_posts ) ) {
	return;
}

$ze_all = ze_get_all_posts_url();
?>
<section class="section section--latest" aria-labelledby="latest-title">
	<div class="container">
		<div class="section__head">
			<h2 id="latest-title" class="section__title"><?php esc_html_e( 'Artikel Terbaru', 'zona-ekonomi' ); ?></h2>
			<?php if ( '' !== $ze_all ) : ?>
				<a class="section__link" href="<?php echo esc_url( $ze_all ); ?>"><?php esc_html_e( 'Lihat semua', 'zona-ekonomi' ); ?></a>
			<?php endif; ?>
		</div>
		<div class="ze-grid ze-grid--cols-<?php echo (int) ze_get_columns(); ?>">
			<?php
			foreach ( $ze_posts as $ze_item ) {
				get_template_part( 'template-parts/cards/card', null, array( 'post' => $ze_item ) );
			}
			?>
		</div>
	</div>
</section>
