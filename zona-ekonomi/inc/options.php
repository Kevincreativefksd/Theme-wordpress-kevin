<?php
/**
 * Theme options: default values and accessors.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default value of every theme option (theme_mod key without the "ze_" prefix).
 *
 * @return array
 */
function ze_default_options() {
	static $defaults = null;

	if ( null === $defaults ) {
		$defaults = array(
			// Umum.
			'brand_description'    => __( 'Ruang literasi ekonomi: informasi, analisis, dan edukasi seputar ekonomi, bisnis, keuangan, dan investasi.', 'zona-ekonomi' ),
			'copyright_text'       => __( '© {year} {site}. Seluruh hak cipta dilindungi.', 'zona-ekonomi' ),

			// Header.
			'header_search'        => true,
			'header_sticky'        => false,
			'logo_width'           => 160,
			'mobile_nav_style'     => 'dropdown',

			// Beranda.
			'home_hero_source'     => 'latest',
			'home_hero_post'       => 0,
			'home_latest_count'    => 6,
			'home_columns'         => 3,
			'catnav_ids'           => '',
			'catsection_ids'       => '',
			'home_show_hero'       => true,
			'home_show_catnav'     => true,
			'home_show_featured'   => true,
			'home_show_latest'     => true,
			'home_show_popular'    => false,
			'home_show_editors'    => true,
			'home_show_categories' => true,

			// Artikel.
			'single_layout'        => 'a',
			'single_sidebar'       => true,
			'single_toc'           => true,
			'single_related'       => true,
			'single_progress'      => true,
			'meta_author'          => true,
			'meta_date'            => true,
			'meta_modified'        => false,
			'meta_reading'         => true,

			// Footer.
			'social_facebook'      => '',
			'social_x'             => '',
			'social_instagram'     => '',
			'social_youtube'       => '',
			'social_linkedin'      => '',
			'social_tiktok'        => '',

			// Tampilan.
			'color_mode'           => 'light',
			'color_bg'             => '#ffffff',
			'color_text'           => '#111111',
			'color_header'         => '#000000',
			'color_accent'         => '#0a4fd8',
			'font_heading'         => 'sans',
			'font_body'            => 'sans',
			'font_size'            => 17,
		);
	}

	return $defaults;
}

/**
 * Get a theme option.
 *
 * @param string $key Option key without prefix.
 * @return mixed
 */
function ze_option( $key ) {
	$defaults = ze_default_options();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	return get_theme_mod( 'ze_' . $key, $default );
}

/**
 * Whether a boolean option is enabled.
 *
 * @param string $key Option key without prefix.
 * @return bool
 */
function ze_is_on( $key ) {
	return (bool) ze_option( $key );
}

/**
 * Parse a comma separated list (or array) into unique positive integers.
 *
 * @param string|array $value Raw value.
 * @return int[]
 */
function ze_parse_ids( $value ) {
	$parts = is_array( $value ) ? $value : explode( ',', (string) $value );

	return array_values( array_unique( array_filter( array_map( 'absint', $parts ) ) ) );
}
