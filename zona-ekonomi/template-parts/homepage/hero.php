<?php
/**
 * Homepage hero: the headline article. Its image is the LCP element, so it is
 * eager-loaded with high fetch priority.
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
$ze_title   = get_the_title( $ze_post );
$ze_summary = ze_get_summary( $ze_post );
$ze_image   = ze_get_thumbnail(
	$ze_post,
	'ze-wide',
	array(
		'eager' => true,
		'main'  => true,
		'sizes' => '(min-width: 1264px) 1200px, 100vw',
	)
);
?>
<section class="hero" aria-label="<?php esc_attr_e( 'Artikel utama', 'zona-ekonomi' ); ?>">
	<div class="container">
		<div class="hero__content">
			<?php ze_the_category_label( $ze_post ); ?>
			<h2 class="hero__title"><a href="<?php echo esc_url( $ze_link ); ?>"><?php echo esc_html( $ze_title ); ?></a></h2>
			<?php if ( '' !== $ze_summary ) : ?>
				<p class="hero__summary"><?php echo esc_html( $ze_summary ); ?></p>
			<?php endif; ?>
			<?php ze_the_meta( $ze_post ); ?>
			<a class="btn btn--light hero__more" href="<?php echo esc_url( $ze_link ); ?>">
				<?php esc_html_e( 'Baca artikel', 'zona-ekonomi' ); ?>
				<span class="screen-reader-text"><?php echo esc_html( ': ' . $ze_title ); ?></span>
			</a>
		</div>
		<?php if ( $ze_image ) : ?>
			<a class="hero__media" href="<?php echo esc_url( $ze_link ); ?>" tabindex="-1" aria-hidden="true"><?php echo $ze_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		<?php endif; ?>
	</div>
</section>
