<?php
/**
 * Related articles (same main category, else same tags).
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ze_related = ze_get_related_posts( get_post(), 3 );

if ( empty( $ze_related ) ) {
	return;
}
?>
<section class="related container" aria-labelledby="related-title">
	<div class="section__head">
		<h2 id="related-title" class="section__title"><?php esc_html_e( 'Artikel Terkait', 'zona-ekonomi' ); ?></h2>
	</div>
	<div class="ze-grid ze-grid--cols-3">
		<?php
		foreach ( $ze_related as $ze_item ) {
			get_template_part( 'template-parts/cards/card', null, array( 'post' => $ze_item, 'sizes' => ze_card_sizes( 3 ) ) );
		}
		?>
	</div>
</section>
