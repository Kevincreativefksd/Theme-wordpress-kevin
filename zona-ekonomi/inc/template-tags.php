<?php
/**
 * Template tags: functions that print markup.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print the logo (uploaded logo, or the site name as text).
 */
function ze_the_logo() {
	if ( has_custom_logo() ) {
		echo '<div class="site-logo">' . get_custom_logo() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core escapes the logo markup.
		return;
	}

	printf(
		'<a class="site-title" href="%1$s" rel="home">%2$s</a>',
		esc_url( home_url( '/' ) ),
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * Print the main category of a post as a link.
 *
 * @param WP_Post|int|null $post  Post.
 * @param string           $class CSS class.
 */
function ze_the_category_label( $post = null, $class = 'cat-label' ) {
	$term = ze_get_primary_category( $post );

	if ( ! $term ) {
		return;
	}

	$link = get_term_link( $term );

	if ( is_wp_error( $link ) ) {
		return;
	}

	printf(
		'<a class="%1$s" href="%2$s">%3$s</a>',
		esc_attr( $class ),
		esc_url( $link ),
		esc_html( $term->name )
	);
}

/**
 * Print post metadata (date, author, and optional extras).
 *
 * @param WP_Post|int|null $post Post.
 * @param array            $args Which items to show.
 */
function ze_the_meta( $post = null, $args = array() ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return;
	}

	$args = wp_parse_args(
		$args,
		array(
			'date'        => true,
			'author'      => true,
			'modified'    => false,
			'reading'     => false,
			'author_link' => false,
			'comments'    => false,
		)
	);

	$out = '';

	if ( $args['date'] ) {
		$out .= '<span class="meta__item"><time datetime="' . esc_attr( get_the_date( DATE_W3C, $post ) ) . '">' . esc_html( get_the_date( '', $post ) ) . '</time></span>';
	}

	if ( $args['author'] ) {
		$name = get_the_author_meta( 'display_name', $post->post_author );

		if ( '' !== $name ) {
			if ( $args['author_link'] ) {
				$name_html = '<a href="' . esc_url( get_author_posts_url( $post->post_author ) ) . '">' . esc_html( $name ) . '</a>';
			} else {
				$name_html = esc_html( $name );
			}

			$out .= '<span class="meta__item meta__author">' . $name_html . '</span>';
		}
	}

	if ( $args['modified'] && (int) get_the_modified_date( 'U', $post ) > (int) get_the_date( 'U', $post ) + DAY_IN_SECONDS ) {
		$out .= '<span class="meta__item">' . esc_html__( 'Diperbarui', 'zona-ekonomi' ) . ' <time datetime="' . esc_attr( get_the_modified_date( DATE_W3C, $post ) ) . '">' . esc_html( get_the_modified_date( '', $post ) ) . '</time></span>';
	}

	if ( $args['reading'] ) {
		$minutes = ze_get_reading_time( $post );
		$out    .= '<span class="meta__item">' . esc_html(
			/* translators: %s: number of minutes. */
			sprintf( _n( '%s menit baca', '%s menit baca', $minutes, 'zona-ekonomi' ), number_format_i18n( $minutes ) )
		) . '</span>';
	}

	if ( $args['comments'] ) {
		$count = (int) get_comments_number( $post );
		$out  .= '<span class="meta__item">' . esc_html(
			/* translators: %s: number of comments. */
			sprintf( _n( '%s komentar', '%s komentar', $count, 'zona-ekonomi' ), number_format_i18n( $count ) )
		) . '</span>';
	}

	if ( '' === $out ) {
		return;
	}

	echo '<p class="meta">' . $out . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Every part is escaped above.
}

/**
 * Get the featured image markup with the right loading attributes.
 *
 * @param WP_Post|int|null $post Post.
 * @param string           $size Image size.
 * @param array            $args eager, sizes, main (adds alt/title fallbacks).
 * @return string
 */
function ze_get_thumbnail( $post, $size = 'ze-card', $args = array() ) {
	$post = get_post( $post );

	if ( ! $post || ! has_post_thumbnail( $post ) ) {
		return '';
	}

	$args = wp_parse_args(
		$args,
		array(
			'eager' => false,
			'sizes' => '',
			'main'  => false,
		)
	);

	$attr = array(
		'decoding' => 'async',
		'loading'  => $args['eager'] ? 'eager' : 'lazy',
	);

	if ( $args['eager'] ) {
		$attr['fetchpriority'] = 'high';
	}

	if ( $args['sizes'] ) {
		$attr['sizes'] = $args['sizes'];
	}

	if ( $args['main'] ) {
		$thumb_id = get_post_thumbnail_id( $post );

		if ( '' === trim( (string) get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ) ) {
			$attr['alt'] = wp_strip_all_tags( get_the_title( $post ) );
		}

		// Use the media title only when it reads like text, not like a file name.
		$media_title = wp_strip_all_tags( get_the_title( $thumb_id ) );

		if ( false !== strpos( trim( $media_title ), ' ' ) ) {
			$attr['title'] = $media_title;
		}
	}

	return get_the_post_thumbnail( $post, $size, $attr );
}

/**
 * Social links set in the Customizer.
 *
 * @return array[] Each item has "label" and "url".
 */
function ze_get_social_links() {
	$networks = array(
		'facebook'  => 'Facebook',
		'x'         => 'X',
		'instagram' => 'Instagram',
		'youtube'   => 'YouTube',
		'linkedin'  => 'LinkedIn',
		'tiktok'    => 'TikTok',
	);
	$links    = array();

	foreach ( $networks as $key => $label ) {
		$url = esc_url( (string) ze_option( 'social_' . $key ) );

		if ( '' !== $url ) {
			$links[] = array(
				'label' => $label,
				'url'   => $url,
			);
		}
	}

	return $links;
}

/**
 * Copyright text with {year} and {site} replaced.
 *
 * @return string HTML.
 */
function ze_get_copyright() {
	$text = str_replace(
		array( '{year}', '{site}' ),
		array( wp_date( 'Y' ), get_bloginfo( 'name' ) ),
		(string) ze_option( 'copyright_text' )
	);

	return wp_kses_post( $text );
}

/**
 * Fallback for the primary menu when none is assigned.
 */
function ze_primary_menu_fallback() {
	echo '<ul id="primary-menu" class="menu">';
	printf(
		'<li><a href="%1$s">%2$s</a></li>',
		esc_url( home_url( '/' ) ),
		esc_html__( 'Beranda', 'zona-ekonomi' )
	);
	wp_list_pages(
		array(
			'title_li' => '',
			'depth'    => 1,
			'number'   => 5,
		)
	);
	echo '</ul>';
}

/**
 * Fallback for the footer menu when none is assigned.
 */
function ze_footer_menu_fallback() {
	echo '<ul class="footer-menu">';
	wp_list_pages(
		array(
			'title_li' => '',
			'depth'    => 1,
			'number'   => 8,
		)
	);
	echo '</ul>';
}
