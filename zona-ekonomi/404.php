<?php
/**
 * 404 page.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$ze_latest = ze_query_posts( array( 'posts_per_page' => 3 ) );
?>
<div class="section">
	<div class="container">
		<header class="archive-header archive-header--plain">
			<h1 class="archive-header__title"><?php esc_html_e( 'Halaman tidak ditemukan', 'zona-ekonomi' ); ?></h1>
			<p class="archive-header__desc"><?php esc_html_e( 'Alamat yang Anda tuju tidak tersedia atau sudah dipindahkan. Coba cari artikel yang Anda butuhkan.', 'zona-ekonomi' ); ?></p>
		</header>
		<div class="not-found__search"><?php get_search_form(); ?></div>

		<?php if ( $ze_latest->posts ) : ?>
			<div class="section__head">
				<h2 class="section__title"><?php esc_html_e( 'Artikel Terbaru', 'zona-ekonomi' ); ?></h2>
			</div>
			<div class="ze-grid ze-grid--cols-3">
				<?php
				foreach ( $ze_latest->posts as $ze_item ) {
					get_template_part( 'template-parts/cards/card', null, array( 'post' => $ze_item ) );
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php
get_footer();
