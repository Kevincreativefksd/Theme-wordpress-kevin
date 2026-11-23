<?php
/**
 * Compact card for lists (popular, sidebar, category sections).
 *
 * Args: post (WP_Post), thumb (bool), comments (bool).
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

$ze_image = ! empty( $args['thumb'] ) ? ze_get_thumbnail( $ze_post, 'ze-thumb', array( 'sizes' => '120px' ) ) : '';
?>
<article class="card-list<?php echo $ze_image ? ' card-list--thumb' : ''; ?>">
	<?php if ( $ze_image ) : ?>
		<a class="card-list__media" href="<?php echo esc_url( get_permalink( $ze_post ) ); ?>" tabindex="-1" aria-hidden="true"><?php echo $ze_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	<?php endif; ?>
	<div class="card-list__body">
		<?php ze_the_category_label( $ze_post ); ?>
		<h3 class="card-list__title"><a href="<?php echo esc_url( get_permalink( $ze_post ) ); ?>"><?php echo esc_html( get_the_title( $ze_post ) ); ?></a></h3>
		<?php
		ze_the_meta(
			$ze_post,
			array(
				'author'   => false,
				'comments' => ! empty( $args['comments'] ),
			)
		);
		?>
	</div>
</article>
