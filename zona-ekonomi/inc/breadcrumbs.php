<?php
/**
 * Breadcrumbs: uses Rank Math when its breadcrumb feature is on, otherwise a
 * plain HTML fallback (no schema markup, so nothing can collide with Rank Math).
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Items for the fallback breadcrumb.
 *
 * @return array[] Each item has "label" and "url".
 */
function ze_get_breadcrumb_items() {
	$items = array(
		array(
			'label' => __( 'Beranda', 'zona-ekonomi' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( is_singular( 'post' ) ) {
		$term = ze_get_primary_category();

		if ( $term ) {
			$chain = array_reverse( get_ancestors( $term->term_id, 'category', 'taxonomy' ) );
			$chain[] = $term->term_id;

			foreach ( $chain as $term_id ) {
				$item = get_term( $term_id, 'category' );
				$link = $item instanceof WP_Term ? get_term_link( $item ) : '';

				if ( $item instanceof WP_Term && ! is_wp_error( $link ) ) {
					$items[] = array(
						'label' => $item->name,
						'url'   => $link,
					);
				}
			}
		}
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor_id ) {
			$items[] = array(
				'label' => get_the_title( $ancestor_id ),
				'url'   => get_permalink( $ancestor_id ),
			);
		}
	}

	$items[] = array(
		'label' => get_the_title(),
		'url'   => '',
	);

	return $items;
}

/**
 * Print the breadcrumb.
 */
function ze_breadcrumbs() {
	if ( ze_rank_math_breadcrumbs_enabled() ) {
		echo '<div class="breadcrumb breadcrumb--rank-math">';
		rank_math_the_breadcrumbs();
		echo '</div>';
		return;
	}

	$items = ze_get_breadcrumb_items();

	if ( count( $items ) < 2 ) {
		return;
	}

	$last = count( $items ) - 1;

	echo '<nav class="breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'zona-ekonomi' ) . '"><ol class="breadcrumb__list">';

	foreach ( $items as $index => $item ) {
		echo '<li class="breadcrumb__item">';

		if ( $index === $last || empty( $item['url'] ) ) {
			echo '<span aria-current="page">' . esc_html( $item['label'] ) . '</span>';
		} else {
			echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a>';
		}

		echo '</li>';
	}

	echo '</ol></nav>';
}
