<?php
/**
 * Site header: logo, primary navigation, search and mobile menu toggle.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<header id="masthead" class="site-header">
	<div class="container site-header__bar">
		<div class="site-branding">
			<?php ze_the_logo(); ?>
		</div>

		<nav id="primary-navigation" class="primary-nav" aria-label="<?php esc_attr_e( 'Navigasi utama', 'zona-ekonomi' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'menu',
					'container'      => false,
					'depth'          => 2,
					'fallback_cb'    => 'ze_primary_menu_fallback',
				)
			);
			?>
		</nav>

		<div class="site-header__tools">
			<?php if ( ze_is_on( 'header_search' ) ) : ?>
				<button type="button" class="icon-btn search-toggle" aria-expanded="false" aria-controls="search-panel">
					<svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M21 21l-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z"/></svg>
					<span class="screen-reader-text"><?php esc_html_e( 'Cari', 'zona-ekonomi' ); ?></span>
				</button>
			<?php endif; ?>
			<button type="button" class="icon-btn menu-toggle" aria-expanded="false" aria-controls="primary-navigation">
				<span class="menu-toggle__bar" aria-hidden="true"></span>
				<span class="menu-toggle__bar" aria-hidden="true"></span>
				<span class="menu-toggle__bar" aria-hidden="true"></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'zona-ekonomi' ); ?></span>
			</button>
		</div>
	</div>

	<?php if ( has_nav_menu( 'secondary' ) ) : ?>
		<nav class="secondary-nav" aria-label="<?php esc_attr_e( 'Navigasi sekunder', 'zona-ekonomi' ); ?>">
			<div class="container">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'secondary',
						'menu_class'     => 'menu',
						'container'      => false,
						'depth'          => 1,
					)
				);
				?>
			</div>
		</nav>
	<?php endif; ?>

	<?php if ( ze_is_on( 'header_search' ) ) : ?>
		<div id="search-panel" class="search-panel">
			<div class="container"><?php get_search_form(); ?></div>
		</div>
	<?php endif; ?>
</header>
