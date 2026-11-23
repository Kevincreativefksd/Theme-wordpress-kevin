<?php
/**
 * Assets: styles, scripts and the dynamic CSS variables from the Customizer.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a validated hex colour option.
 *
 * @param string $key Option key.
 * @return string
 */
function ze_get_color( $key ) {
	$defaults = ze_default_options();
	$value    = sanitize_hex_color( ze_option( $key ) );

	return $value ? $value : $defaults[ $key ];
}

/**
 * Hex to RGB.
 *
 * @param string $hex Hex colour (#rgb or #rrggbb).
 * @return int[]
 */
function ze_hex_to_rgb( $hex ) {
	$hex = ltrim( $hex, '#' );

	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}

	return array(
		hexdec( substr( $hex, 0, 2 ) ),
		hexdec( substr( $hex, 2, 2 ) ),
		hexdec( substr( $hex, 4, 2 ) ),
	);
}

/**
 * Mix two hex colours.
 *
 * @param string $from   Base colour.
 * @param string $to     Colour mixed in.
 * @param float  $weight Share of $to, 0 to 1.
 * @return string
 */
function ze_mix_hex( $from, $to, $weight ) {
	$a   = ze_hex_to_rgb( $from );
	$b   = ze_hex_to_rgb( $to );
	$out = '#';

	for ( $i = 0; $i < 3; $i++ ) {
		$out .= str_pad( dechex( (int) round( $a[ $i ] * ( 1 - $weight ) + $b[ $i ] * $weight ) ), 2, '0', STR_PAD_LEFT );
	}

	return $out;
}

/**
 * Readable text colour (black or white) for a background.
 *
 * @param string $hex Background colour.
 * @return string
 */
function ze_contrast_text( $hex ) {
	$rgb = ze_hex_to_rgb( $hex );
	$yiq = ( $rgb[0] * 299 + $rgb[1] * 587 + $rgb[2] * 114 ) / 1000;

	return $yiq >= 150 ? '#111111' : '#ffffff';
}

/**
 * System font stacks. No web fonts are loaded.
 *
 * @return array
 */
function ze_font_stacks() {
	return array(
		'sans'  => 'system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif',
		'serif' => 'Charter,"Bitstream Charter","Sitka Text",Cambria,Georgia,"Times New Roman",serif',
	);
}

/**
 * CSS variables generated from Customizer values. Every value is validated.
 *
 * @return string
 */
function ze_custom_css() {
	$fonts      = ze_font_stacks();
	$heading    = isset( $fonts[ ze_option( 'font_heading' ) ] ) ? $fonts[ ze_option( 'font_heading' ) ] : $fonts['sans'];
	$body       = isset( $fonts[ ze_option( 'font_body' ) ] ) ? $fonts[ ze_option( 'font_body' ) ] : $fonts['sans'];
	$accent     = ze_get_color( 'color_accent' );
	$accent_inv = ze_mix_hex( $accent, '#ffffff', 0.5 );
	$header     = ze_get_color( 'color_header' );
	$font_size  = max( 15, min( 20, (int) ze_option( 'font_size' ) ) );
	$logo_width = max( 80, min( 320, (int) ze_option( 'logo_width' ) ) );
	$mode       = ze_option( 'color_mode' );

	$css  = ':root{';
	$css .= '--ze-bg:' . ze_get_color( 'color_bg' ) . ';';
	$css .= '--ze-text:' . ze_get_color( 'color_text' ) . ';';
	$css .= '--ze-header-bg:' . $header . ';';
	$css .= '--ze-header-text:' . ze_contrast_text( $header ) . ';';
	$css .= '--ze-accent:' . $accent . ';';
	$css .= '--ze-accent-inv:' . $accent_inv . ';';
	$css .= '--ze-font-head:' . $heading . ';';
	$css .= '--ze-font-body:' . $body . ';';
	$css .= '--ze-fs:' . $font_size . 'px;';
	$css .= '--ze-logo-w:' . $logo_width . 'px;';
	$css .= '}';

	$dark = '--ze-bg:#0e0e0e;--ze-text:#ededed;--ze-accent:' . $accent_inv . ';color-scheme:dark;';

	if ( 'dark' === $mode ) {
		$css .= ':root{' . $dark . '}';
	} elseif ( 'auto' === $mode ) {
		$css .= '@media (prefers-color-scheme:dark){:root{' . $dark . '}}';
	}

	return $css;
}

/**
 * Enqueue front-end assets. Single-article CSS and the progress script are
 * only loaded on pages that use them.
 */
function ze_enqueue_assets() {
	$uri  = ZE_URI . '/assets/';
	$deps = array( 'ze-main', 'ze-components' );

	wp_enqueue_style( 'ze-main', $uri . 'css/main.css', array(), ZE_VERSION );
	wp_enqueue_style( 'ze-components', $uri . 'css/components.css', array( 'ze-main' ), ZE_VERSION );

	if ( is_singular() ) {
		wp_enqueue_style( 'ze-single', $uri . 'css/single.css', array( 'ze-main' ), ZE_VERSION );
		$deps[] = 'ze-single';
	}

	wp_enqueue_style( 'ze-responsive', $uri . 'css/responsive.css', $deps, ZE_VERSION );
	wp_add_inline_style( 'ze-main', ze_custom_css() );

	$script_args = array(
		'in_footer' => true,
		'strategy'  => 'defer',
	);

	wp_enqueue_script( 'ze-navigation', $uri . 'js/navigation.js', array(), ZE_VERSION, $script_args );

	if ( is_singular( 'post' ) && ze_is_on( 'single_progress' ) ) {
		wp_enqueue_script( 'ze-progress', $uri . 'js/progress.js', array(), ZE_VERSION, $script_args );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ze_enqueue_assets' );

/**
 * Flag JavaScript support as early as possible so layout does not shift.
 */
function ze_js_detect() {
	echo "<script>document.documentElement.className=document.documentElement.className.replace('no-js','js');</script>\n";
}
add_action( 'wp_head', 'ze_js_detect', 0 );
