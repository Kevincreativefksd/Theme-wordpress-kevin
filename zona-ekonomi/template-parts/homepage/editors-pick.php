<?php
/**
 * Editor's picks: posts marked "Stick to the top of the blog".
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
?>
<section class="section section--invert section--editors" aria-labelledby="editors-title">
	<div class="container">
		<div class="section__head">
			<h2 id="editors-title" class="section__title"><?php esc_html_e( 'Pilihan Editor', 'zona-ekonomi' ); ?></h2>
		</div>
		<div class="ze-grid ze-grid--cols-3">
			<?php
			foreach ( $ze_posts as $ze_item ) {
				get_template_part( 'template-parts/cards/card', null, array( 'post' => $ze_item, 'sizes' => ze_card_sizes( 3 ) ) );
			}
			?>
		</div>
	</div>
</section>
