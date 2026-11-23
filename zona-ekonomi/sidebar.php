<?php
/**
 * Article sidebar: widget area, or the latest posts when the area is empty.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_active_sidebar( 'sidebar-1' ) ) {
	dynamic_sidebar( 'sidebar-1' );
	return;
}

$ze_latest = ze_query_posts(
	array(
		'posts_per_page' => 5,
		'post__not_in'   => array( get_the_ID() ),
	)
);

if ( $ze_latest->posts ) :
	?>
	<section class="widget">
		<h2 class="widget-title"><?php esc_html_e( 'Artikel Terbaru', 'zona-ekonomi' ); ?></h2>
		<div class="card-list-stack">
			<?php
			foreach ( $ze_latest->posts as $ze_item ) {
				get_template_part(
					'template-parts/cards/card-list',
					null,
					array(
						'post'  => $ze_item,
						'thumb' => true,
					)
				);
			}
			?>
		</div>
	</section>
	<?php
endif;
