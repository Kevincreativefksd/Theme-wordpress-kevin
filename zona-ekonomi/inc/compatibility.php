<?php
/**
 * Plugin compatibility helpers (Rank Math SEO).
 *
 * The theme never prints meta description, canonical, Open Graph, Twitter Card
 * or schema markup. These helpers only READ Rank Math data when it exists.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether Rank Math SEO is active.
 *
 * @return bool
 */
function ze_is_rank_math_active() {
	return defined( 'RANK_MATH_VERSION' );
}

/**
 * Whether Rank Math's breadcrumb feature is available and switched on.
 *
 * @return bool
 */
function ze_rank_math_breadcrumbs_enabled() {
	if ( ! ze_is_rank_math_active() || ! function_exists( 'rank_math_the_breadcrumbs' ) ) {
		return false;
	}

	if ( class_exists( '\RankMath\Helper' ) && method_exists( '\RankMath\Helper', 'get_settings' ) ) {
		return (bool) \RankMath\Helper::get_settings( 'general.breadcrumbs' );
	}

	return false;
}

/**
 * Primary category chosen in Rank Math for a post.
 *
 * @param int $post_id Post ID.
 * @return int Term ID or 0.
 */
function ze_rank_math_primary_term_id( $post_id ) {
	if ( ! ze_is_rank_math_active() ) {
		return 0;
	}

	return absint( get_post_meta( $post_id, 'rank_math_primary_category', true ) );
}

/**
 * Plain-text SEO description written in Rank Math, used only as a summary
 * fallback. Descriptions that still contain %variables% are ignored.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function ze_rank_math_description( $post_id ) {
	if ( ! ze_is_rank_math_active() ) {
		return '';
	}

	$description = trim( wp_strip_all_tags( (string) get_post_meta( $post_id, 'rank_math_description', true ) ) );

	if ( false !== strpos( $description, '%' ) ) {
		return '';
	}

	return $description;
}
