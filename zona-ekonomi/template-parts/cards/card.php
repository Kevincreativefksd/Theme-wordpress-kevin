<?php
/**
 * Article card: image, category, title, date, author.
 *
 * Args: post (WP_Post), eager (bool), size (image size), sizes (attribute), lead (bool).
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ze_post = isset( $args['post'] ) ? get_post( $args['post'] ) : get_post();

if ( ! $ze_post ) {
	return;
}

$ze_size  = ! empty( $args['size'] ) ? $args['size'] : 'ze-card';
$ze_sizes = ! empty( $args['sizes'] ) ? $args['sizes'] : ze_card_sizes( ze_get_columns() );
$ze_image = ze_get_thumbnail(
	$ze_post,
	$ze_size,
	array(
		'eager' => ! empty( $args['eager'] ),
		'sizes' => $ze_sizes,
	)
);
$ze_class = 'card' . ( $ze_image ? '' : ' card--noimg' ) . ( ! empty( $args['lead'] ) ? ' card--lead' : '' );
?>
<article class="<?php echo esc_attr( $ze_class ); ?>">
	<?php if ( $ze_image ) : ?>
		<a class="card__media" href="<?php echo esc_url( get_permalink( $ze_post ) ); ?>" tabindex="-1" aria-hidden="true"><?php echo $ze_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	<?php endif; ?>
	<div class="card__body">
		<?php ze_the_category_label( $ze_post ); ?>
		<h3 class="card__title"><a href="<?php echo esc_url( get_permalink( $ze_post ) ); ?>"><?php echo esc_html( get_the_title( $ze_post ) ); ?></a></h3>
		<?php ze_the_meta( $ze_post ); ?>
	</div>
</article>
