<?php
/**
 * Single article body. Layout A (minimalist) and B (editorial) share this file;
 * the differences are the excerpt, the optional sidebar and the CSS modifier.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ze_layout  = ze_get_single_layout();
$ze_sidebar = ( 'b' === $ze_layout && ze_is_on( 'single_sidebar' ) );
$ze_summary = 'a' === $ze_layout ? ze_get_summary( get_post(), false ) : '';
$ze_caption = has_post_thumbnail() ? get_the_post_thumbnail_caption() : '';
$ze_meta    = array(
	'date'        => ze_is_on( 'meta_date' ),
	'author'      => ze_is_on( 'meta_author' ),
	'modified'    => ze_is_on( 'meta_modified' ),
	'reading'     => ze_is_on( 'meta_reading' ),
	'author_link' => true,
);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--' . $ze_layout ); ?>>
	<header class="entry-header">
		<div class="container container--entry">
			<?php ze_breadcrumbs(); ?>
			<?php ze_the_category_label(); ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php if ( '' !== $ze_summary ) : ?>
				<p class="entry-summary"><?php echo esc_html( $ze_summary ); ?></p>
			<?php endif; ?>
			<?php ze_the_meta( get_post(), $ze_meta ); ?>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="entry-thumb container">
			<?php
			echo ze_get_thumbnail( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				get_post(),
				'ze-wide',
				array(
					'eager' => true,
					'main'  => true,
					'sizes' => 'a' === $ze_layout ? '(min-width: 1104px) 1040px, 100vw' : '(min-width: 1264px) 1200px, 100vw',
				)
			);
			?>
			<?php if ( '' !== $ze_caption ) : ?>
				<figcaption><?php echo esc_html( $ze_caption ); ?></figcaption>
			<?php endif; ?>
		</figure>
	<?php endif; ?>

	<div class="container entry-layout<?php echo $ze_sidebar ? ' entry-layout--sidebar' : ''; ?>">
		<div class="entry-main">
			<div class="entry-content">
				<?php
				the_content();
				wp_link_pages(
					array(
						'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Halaman', 'zona-ekonomi' ) . '">',
						'after'  => '</nav>',
					)
				);
				?>
			</div>

			<footer class="entry-footer">
				<?php the_tags( '<p class="entry-tags">', ' ', '</p>' ); ?>
				<?php edit_post_link( __( 'Sunting', 'zona-ekonomi' ), '<p class="edit-link">', '</p>' ); ?>
			</footer>
		</div>

		<?php if ( $ze_sidebar ) : ?>
			<aside class="entry-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'zona-ekonomi' ); ?>">
				<?php get_sidebar(); ?>
			</aside>
		<?php endif; ?>
	</div>
</article>
