<?php
/**
 * Static page.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--a entry--page' ); ?>>
		<header class="entry-header">
			<div class="container container--entry">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="entry-thumb container">
				<?php echo ze_get_thumbnail( get_post(), 'ze-wide', array( 'eager' => true, 'main' => true, 'sizes' => '(min-width: 1104px) 1040px, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</figure>
		<?php endif; ?>

		<div class="container entry-layout">
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
				<?php edit_post_link( __( 'Sunting', 'zona-ekonomi' ), '<p class="edit-link">', '</p>' ); ?>
			</div>
		</div>
	</article>
	<?php
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile;

get_footer();
