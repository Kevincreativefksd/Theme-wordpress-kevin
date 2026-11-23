<?php
/**
 * Heading of a listing: title, description and article count.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

$ze_description = ze_get_archive_description();
$ze_count       = isset( $wp_query->found_posts ) ? (int) $wp_query->found_posts : 0;
?>
<header class="archive-header">
	<div class="container">
		<h1 class="archive-header__title"><?php echo esc_html( ze_get_archive_title() ); ?></h1>
		<?php if ( '' !== trim( wp_strip_all_tags( $ze_description ) ) ) : ?>
			<div class="archive-header__desc"><?php echo wp_kses_post( $ze_description ); ?></div>
		<?php endif; ?>
		<?php if ( ! is_front_page() ) : ?>
			<p class="archive-header__count">
				<?php
				echo esc_html(
					/* translators: %s: number of articles. */
					sprintf( _n( '%s artikel', '%s artikel', $ze_count, 'zona-ekonomi' ), number_format_i18n( $ze_count ) )
				);
				?>
			</p>
		<?php endif; ?>
	</div>
</header>
