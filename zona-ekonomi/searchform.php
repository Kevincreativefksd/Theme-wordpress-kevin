<?php
/**
 * Search form.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ze_uid = wp_unique_id( 'search-form-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $ze_uid ); ?>"><?php esc_html_e( 'Cari artikel', 'zona-ekonomi' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $ze_uid ); ?>" class="search-form__input" placeholder="<?php echo esc_attr_x( 'Cari artikel…', 'placeholder', 'zona-ekonomi' ); ?>" value="<?php echo esc_attr( get_search_query( false ) ); ?>" name="s">
	<button type="submit" class="search-form__submit"><?php esc_html_e( 'Cari', 'zona-ekonomi' ); ?></button>
</form>
