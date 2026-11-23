<?php
/**
 * Performance: remove unneeded front-end weight.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load core block CSS only for blocks that are actually on the page.
 */
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * Remove the emoji detection script and styles (system emoji are used).
 */
function ze_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'ze_disable_emojis' );

/**
 * The header logo is above the fold: never lazy-load it.
 *
 * @param array $attr Image attributes.
 * @return array
 */
function ze_logo_attributes( $attr ) {
	$attr['loading']  = 'eager';
	$attr['decoding'] = 'async';

	return $attr;
}
add_filter( 'get_custom_logo_image_attributes', 'ze_logo_attributes' );
