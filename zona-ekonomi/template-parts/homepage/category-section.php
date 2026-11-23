<?php
/**
 * One category section: a lead article plus a compact list.
 *
 * Args: term (WP_Term), posts (WP_Post[]).
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ze_term  = isset( $args['term'] ) ? $args['term'] : null;
$ze_posts = isset( $args['posts'] ) ? $args['posts'] : array();

if ( ! ( $ze_term instanceof WP_Term ) || empty( $ze_posts ) ) {
	return;
}

$ze_link = get_term_link( $ze_term );

if ( is_wp_error( $ze_link ) ) {
	return;
}

$ze_lead = array_shift( $ze_posts );
?>
<section class="section section--category" aria-labelledby="cat-<?php echo (int) $ze_term->term_id; ?>">
	<div class="container">
		<div class="section__head">
			<h2 id="cat-<?php echo (int) $ze_term->term_id; ?>" class="section__title"><a href="<?php echo esc_url( $ze_link ); ?>"><?php echo esc_html( $ze_term->name ); ?></a></h2>
			<a class="section__link" href="<?php echo esc_url( $ze_link ); ?>"><?php esc_html_e( 'Lihat semua', 'zona-ekonomi' ); ?></a>
		</div>
		<div class="catsec">
			<?php
			get_template_part(
				'template-parts/cards/card',
				null,
				array(
					'post'  => $ze_lead,
					'size'  => 'ze-feature',
					'sizes' => '(min-width: 900px) 590px, 100vw',
					'lead'  => true,
				)
			);
			?>
			<?php if ( $ze_posts ) : ?>
				<div class="catsec__list">
					<?php
					foreach ( $ze_posts as $ze_item ) {
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
			<?php endif; ?>
		</div>
	</div>
</section>
