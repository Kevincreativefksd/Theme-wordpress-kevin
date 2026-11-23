<?php
/**
 * Site footer: brand, page menu, category menu, social links, copyright.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ze_description = (string) ze_option( 'brand_description' );
$ze_socials     = ze_get_social_links();
$ze_categories  = ze_get_nav_categories();
?>
<footer id="colophon" class="site-footer">
	<div class="container site-footer__grid">
		<div class="site-footer__brand">
			<?php ze_the_logo(); ?>
			<?php if ( '' !== $ze_description ) : ?>
				<p class="site-footer__desc"><?php echo esc_html( $ze_description ); ?></p>
			<?php endif; ?>
			<?php if ( $ze_socials ) : ?>
				<ul class="social-list" aria-label="<?php esc_attr_e( 'Media sosial', 'zona-ekonomi' ); ?>">
					<?php foreach ( $ze_socials as $ze_social ) : ?>
						<li><a href="<?php echo esc_url( $ze_social['url'] ); ?>" rel="noopener" target="_blank"><?php echo esc_html( $ze_social['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<nav class="site-footer__nav" aria-labelledby="footer-pages-title">
			<h2 id="footer-pages-title" class="footer-title"><?php esc_html_e( 'Halaman', 'zona-ekonomi' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'menu_class'     => 'footer-menu',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => 'ze_footer_menu_fallback',
				)
			);
			?>
		</nav>

		<?php if ( $ze_categories ) : ?>
			<nav class="site-footer__nav" aria-labelledby="footer-cats-title">
				<h2 id="footer-cats-title" class="footer-title"><?php esc_html_e( 'Kategori', 'zona-ekonomi' ); ?></h2>
				<ul class="footer-menu">
					<?php foreach ( $ze_categories as $ze_term ) : ?>
						<li><a href="<?php echo esc_url( get_term_link( $ze_term ) ); ?>"><?php echo esc_html( $ze_term->name ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>
	</div>

	<div class="container site-footer__bottom">
		<p class="site-info"><?php echo ze_get_copyright(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized with wp_kses_post. ?></p>
	</div>
</footer>
