<?php
/**
 * Featured article: text on the left, image on the right.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ze_post = isset( $args['post'] ) ? get_post( $args['post'] ) : null;

if ( ! $ze_post ) {
	return;
}

$ze_link    = get_permalink( $ze_post );
$ze_summary = ze_get_summary( $ze_post );
$ze_image   = ze_get_thumbnail(
	$ze_post,
	'ze-feature',
	array(
		'main'  => true,
		'sizes' => '(min-width: 900px) 580px, 100vw',
	)
);
?>
<section class="featured" aria-label="<?php esc_attr_e( 'Artikel pilihan', 'zona-ekonomi' ); ?>">
	<div class="container featured__inner">
		<div class="featured__text">
			<?php ze_the_category_label( $ze_post ); ?>
			<h2 class="featured__title"><a href="<?php echo esc_url( $ze_link ); ?>"><?php echo esc_html( get_the_title( $ze_post ) ); ?></a></h2>
			<?php if ( '' !== $ze_summary ) : ?>
				<p class="featured__summary"><?php echo esc_html( $ze_summary ); ?></p>
			<?php endif; ?>
			<?php ze_the_meta( $ze_post ); ?>
		</div>
		<?php if ( $ze_image ) : ?>
			<a class="featured__media" href="<?php echo esc_url( $ze_link ); ?>" tabindex="-1" aria-hidden="true"><?php echo $ze_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		<?php endif; ?>
	</div>
</section>
