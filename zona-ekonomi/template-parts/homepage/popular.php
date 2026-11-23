<?php
/**
 * Popular articles, ranked by comment count (a mechanism that needs no extra
 * database writes).
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
<section class="section section--popular" aria-labelledby="popular-title">
	<div class="container">
		<div class="section__head">
			<h2 id="popular-title" class="section__title"><?php esc_html_e( 'Artikel Populer', 'zona-ekonomi' ); ?></h2>
		</div>
		<ol class="rank-list">
			<?php foreach ( $ze_posts as $ze_item ) : ?>
				<li>
					<?php
					get_template_part(
						'template-parts/cards/card-list',
						null,
						array(
							'post'     => $ze_item,
							'comments' => true,
						)
					);
					?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
