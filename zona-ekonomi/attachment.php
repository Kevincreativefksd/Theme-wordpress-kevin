<?php
/**
 * Attachment page.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$ze_parent_id = wp_get_post_parent_id( get_the_ID() );
	$ze_parent    = $ze_parent_id ? get_post( $ze_parent_id ) : null;
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--a entry--attachment' ); ?>>
		<header class="entry-header">
			<div class="container container--entry">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php if ( $ze_parent && 'publish' === $ze_parent->post_status ) : ?>
					<p class="meta"><a href="<?php echo esc_url( get_permalink( $ze_parent ) ); ?>">
						<?php
						/* translators: %s: title of the parent post. */
						echo esc_html( sprintf( __( 'Kembali ke “%s”', 'zona-ekonomi' ), get_the_title( $ze_parent ) ) );
						?>
					</a></p>
				<?php endif; ?>
			</div>
		</header>

		<div class="container entry-layout">
			<div class="entry-main">
				<?php if ( wp_attachment_is_image() ) : ?>
					<figure class="attachment-figure">
						<?php echo wp_get_attachment_image( get_the_ID(), 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php if ( has_excerpt() ) : ?>
							<figcaption><?php echo esc_html( get_the_excerpt() ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php else : ?>
					<p><a class="btn" href="<?php echo esc_url( wp_get_attachment_url() ); ?>"><?php esc_html_e( 'Unduh berkas', 'zona-ekonomi' ); ?></a></p>
				<?php endif; ?>

				<div class="entry-content">
					<?php
					remove_filter( 'the_content', 'prepend_attachment' );
					the_content();
					add_filter( 'the_content', 'prepend_attachment' );
					?>
				</div>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
