<?php
/**
 * Horizontal category navigation (existing categories only).
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ze_terms = ze_get_nav_categories();

if ( empty( $ze_terms ) ) {
	return;
}

$ze_all_active = is_home() || is_front_page();
?>
<nav class="catnav" aria-label="<?php esc_attr_e( 'Kategori', 'zona-ekonomi' ); ?>">
	<div class="container">
		<ul class="catnav__list">
			<li><a href="<?php echo esc_url( ze_get_blog_url() ); ?>"<?php echo $ze_all_active ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'Semua', 'zona-ekonomi' ); ?></a></li>
			<?php foreach ( $ze_terms as $ze_term ) : ?>
				<li><a href="<?php echo esc_url( get_term_link( $ze_term ) ); ?>"<?php echo is_category( $ze_term->term_id ) ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $ze_term->name ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</nav>
