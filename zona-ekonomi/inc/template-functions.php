<?php
/**
 * Template helpers: queries, data for the homepage, content filters.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Run a lightweight post query (no pagination count by default).
 *
 * @param array $args WP_Query arguments.
 * @return WP_Query
 */
function ze_query_posts( $args = array() ) {
	$defaults = array(
		'post_type'           => 'post',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	return new WP_Query( wp_parse_args( $args, $defaults ) );
}

/**
 * Number of grid columns on desktop (2, 3 or 4).
 *
 * @return int
 */
function ze_get_columns() {
	$columns = (int) ze_option( 'home_columns' );

	return in_array( $columns, array( 2, 3, 4 ), true ) ? $columns : 3;
}

/**
 * Chosen single-article layout.
 *
 * @return string "a" or "b".
 */
function ze_get_single_layout() {
	return 'b' === ze_option( 'single_layout' ) ? 'b' : 'a';
}

/**
 * Responsive "sizes" attribute for a card image.
 *
 * @param int $columns Grid columns on desktop.
 * @return string
 */
function ze_card_sizes( $columns = 3 ) {
	$widths = array(
		2 => 590,
		3 => 380,
		4 => 285,
	);
	$width  = isset( $widths[ $columns ] ) ? $widths[ $columns ] : 380;

	return '(min-width: 1024px) ' . $width . 'px, (min-width: 640px) 50vw, 100vw';
}

/* -------------------------------------------------------------------------
 * Categories
 * ---------------------------------------------------------------------- */

/**
 * IDs of the default "Uncategorized" category, which is never shown as a label.
 *
 * @return int[]
 */
function ze_hidden_category_ids() {
	static $ids = null;

	if ( null === $ids ) {
		$ids     = array();
		$default = get_term( (int) get_option( 'default_category' ), 'category' );

		if ( $default instanceof WP_Term && in_array( $default->slug, array( 'uncategorized', 'tak-berkategori' ), true ) ) {
			$ids[] = (int) $default->term_id;
		}
	}

	return $ids;
}

/**
 * Main category of a post: Rank Math primary category, else the first real one.
 *
 * @param WP_Post|int|null $post Post.
 * @return WP_Term|null
 */
function ze_get_primary_category( $post = null ) {
	static $cache = array();

	$post = get_post( $post );

	if ( ! $post ) {
		return null;
	}

	if ( array_key_exists( $post->ID, $cache ) ) {
		return $cache[ $post->ID ];
	}

	$term       = null;
	$primary_id = ze_rank_math_primary_term_id( $post->ID );

	if ( $primary_id ) {
		$candidate = get_term( $primary_id, 'category' );

		if ( $candidate instanceof WP_Term && has_term( $candidate->term_id, 'category', $post ) ) {
			$term = $candidate;
		}
	}

	if ( ! $term ) {
		$terms = get_the_terms( $post, 'category' );

		if ( is_array( $terms ) ) {
			$hidden = ze_hidden_category_ids();

			foreach ( $terms as $candidate ) {
				if ( ! in_array( (int) $candidate->term_id, $hidden, true ) ) {
					$term = $candidate;
					break;
				}
			}
		}
	}

	$cache[ $post->ID ] = $term;

	return $term;
}

/**
 * Categories for the horizontal category navigation and the footer. Existing
 * categories only: nothing is ever created.
 *
 * @return WP_Term[]
 */
function ze_get_nav_categories() {
	static $terms = null;

	if ( null !== $terms ) {
		return $terms;
	}

	$ids  = ze_parse_ids( ze_option( 'catnav_ids' ) );
	$args = array(
		'taxonomy'   => 'category',
		'hide_empty' => true,
	);

	if ( $ids ) {
		$args['include'] = $ids;
		$args['orderby'] = 'include';
	} else {
		$args['orderby'] = 'count';
		$args['order']   = 'DESC';
		$args['number']  = 8;
		$hidden          = ze_hidden_category_ids();

		if ( $hidden ) {
			$args['exclude'] = $hidden;
		}
	}

	$result = get_terms( $args );
	$terms  = is_array( $result ) ? $result : array();

	return $terms;
}

/* -------------------------------------------------------------------------
 * Homepage data
 * ---------------------------------------------------------------------- */

/**
 * How many posts the first homepage page consumes (hero + featured + latest).
 * The same number drives the offset of page 2+, so no post is skipped.
 *
 * @return int
 */
function ze_front_page_pool_size() {
	$size = 0;

	if ( ze_is_on( 'home_show_latest' ) ) {
		$size += max( 3, min( 12, (int) ze_option( 'home_latest_count' ) ) );
	}

	if ( ze_is_on( 'home_show_featured' ) ) {
		$size++;
	}

	if ( ze_is_on( 'home_show_hero' ) && 'manual' !== ze_option( 'home_hero_source' ) ) {
		$size++;
	}

	return $size;
}

/**
 * Posts page URL (static front page setup) or empty string.
 *
 * @return string
 */
function ze_get_posts_page_url() {
	$page_id = (int) get_option( 'page_for_posts' );

	if ( 'page' === get_option( 'show_on_front' ) && $page_id ) {
		return (string) get_permalink( $page_id );
	}

	return '';
}

/**
 * URL of the "all posts" listing used by the category navigation.
 *
 * @return string
 */
function ze_get_blog_url() {
	$url = ze_get_posts_page_url();

	return $url ? $url : home_url( '/' );
}

/**
 * URL for "see all articles" on the homepage, or empty if there is nothing more.
 *
 * @return string
 */
function ze_get_all_posts_url() {
	$url = ze_get_posts_page_url();

	if ( $url ) {
		return $url;
	}

	if ( 'posts' === get_option( 'show_on_front' ) ) {
		$counts = wp_count_posts( 'post' );

		if ( isset( $counts->publish ) && (int) $counts->publish > ze_front_page_pool_size() ) {
			return get_pagenum_link( 2 );
		}
	}

	return '';
}

/**
 * Manually chosen hero post, if valid.
 *
 * @return WP_Post|null
 */
function ze_get_manual_hero() {
	$post_id = (int) ze_option( 'home_hero_post' );

	if ( ! $post_id ) {
		return null;
	}

	$post = get_post( $post_id );

	if ( $post && 'post' === $post->post_type && 'publish' === $post->post_status && '' === $post->post_password ) {
		return $post;
	}

	return null;
}

/**
 * All data needed by the homepage, fetched once per request.
 *
 * @return array
 */
function ze_get_home_data() {
	static $data = null;

	if ( null !== $data ) {
		return $data;
	}

	$data = array(
		'hero'       => null,
		'featured'   => null,
		'latest'     => array(),
		'popular'    => array(),
		'editors'    => array(),
		'categories' => array(),
	);

	$size = ze_front_page_pool_size();
	$pool = array();

	if ( $size > 0 ) {
		$query = ze_query_posts( array( 'posts_per_page' => $size ) );
		$pool  = $query->posts;
	}

	$used = array();

	if ( ze_is_on( 'home_show_hero' ) ) {
		$hero = 'manual' === ze_option( 'home_hero_source' ) ? ze_get_manual_hero() : null;

		if ( ! $hero && $pool ) {
			$hero = $pool[0];
		}

		if ( ! $hero ) {
			$fallback = ze_query_posts( array( 'posts_per_page' => 1 ) );
			$hero     = $fallback->posts ? $fallback->posts[0] : null;
		}

		if ( $hero ) {
			$data['hero'] = $hero;
			$used[]       = $hero->ID;
		}
	}

	$remaining = array();
	foreach ( $pool as $post ) {
		if ( ! in_array( $post->ID, $used, true ) ) {
			$remaining[] = $post;
		}
	}

	if ( ze_is_on( 'home_show_featured' ) && $remaining ) {
		$data['featured'] = array_shift( $remaining );
	}

	if ( ze_is_on( 'home_show_latest' ) ) {
		$data['latest'] = $remaining;
	}

	if ( ze_is_on( 'home_show_popular' ) ) {
		$query = ze_query_posts(
			array(
				'posts_per_page' => 5,
				'orderby'        => 'comment_count',
				'order'          => 'DESC',
			)
		);

		foreach ( $query->posts as $post ) {
			if ( (int) $post->comment_count > 0 ) {
				$data['popular'][] = $post;
			}
		}
	}

	if ( ze_is_on( 'home_show_editors' ) ) {
		$sticky = get_option( 'sticky_posts' );

		if ( ! empty( $sticky ) && is_array( $sticky ) ) {
			$query = ze_query_posts(
				array(
					'posts_per_page' => 3,
					'post__in'       => $sticky,
				)
			);

			$data['editors'] = $query->posts;
		}
	}

	if ( ze_is_on( 'home_show_categories' ) ) {
		$ids = array_slice( ze_parse_ids( ze_option( 'catsection_ids' ) ), 0, 4 );

		foreach ( $ids as $term_id ) {
			$term = get_term( $term_id, 'category' );

			if ( ! ( $term instanceof WP_Term ) ) {
				continue;
			}

			$query = ze_query_posts(
				array(
					'cat'            => $term_id,
					'posts_per_page' => 4,
				)
			);

			if ( $query->posts ) {
				$data['categories'][] = array(
					'term'  => $term,
					'posts' => $query->posts,
				);
			}
		}
	}

	return $data;
}

/**
 * Homepage in "latest posts" mode: the main query is not used to build page 1,
 * so keep it tiny. Page 2+ continues exactly after the posts shown on page 1.
 *
 * @param WP_Query $query Query.
 */
function ze_pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() || ! $query->is_front_page() ) {
		return;
	}

	$paged = max( 1, (int) $query->get( 'paged' ) );

	$query->set( 'ignore_sticky_posts', true );

	if ( 1 === $paged ) {
		$query->set( 'posts_per_page', 1 );
		$query->set( 'no_found_rows', true );
		return;
	}

	$pool     = ze_front_page_pool_size();
	$per_page = max( 1, (int) get_option( 'posts_per_page' ) );

	$query->set( 'posts_per_page', $per_page );
	$query->set( 'offset', $pool + ( $paged - 2 ) * $per_page );
	$query->set( 'ze_front_pool', $pool );
	$query->set( 'ze_front_paged', 1 );
}
add_action( 'pre_get_posts', 'ze_pre_get_posts' );

/**
 * Keep the page count right when page 1 is the custom homepage.
 *
 * @param int      $found Total found posts.
 * @param WP_Query $query Query.
 * @return int
 */
function ze_found_posts( $found, $query ) {
	if ( ! $query->get( 'ze_front_paged' ) ) {
		return $found;
	}

	$per_page = max( 1, (int) $query->get( 'posts_per_page' ) );

	return max( 0, (int) $found - (int) $query->get( 'ze_front_pool' ) ) + $per_page;
}
add_filter( 'found_posts', 'ze_found_posts', 10, 2 );

/* -------------------------------------------------------------------------
 * Single article helpers
 * ---------------------------------------------------------------------- */

/**
 * Short description of a post.
 *
 * @param WP_Post|int|null $post Post.
 * @param bool             $auto Allow an automatic excerpt as last resort.
 * @return string Plain text.
 */
function ze_get_summary( $post = null, $auto = true ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return '';
	}

	if ( has_excerpt( $post ) ) {
		return wp_strip_all_tags( get_the_excerpt( $post ) );
	}

	$description = ze_rank_math_description( $post->ID );

	if ( '' !== $description ) {
		return $description;
	}

	return $auto ? wp_strip_all_tags( get_the_excerpt( $post ) ) : '';
}

/**
 * Estimated reading time in minutes.
 *
 * @param WP_Post|int|null $post Post.
 * @return int
 */
function ze_get_reading_time( $post = null ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return 0;
	}

	$text  = trim( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ) );
	$words = '' === $text ? array() : preg_split( '/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY );

	return max( 1, (int) ceil( count( (array) $words ) / 200 ) );
}

/**
 * Related posts: same main category, else same tags.
 *
 * @param WP_Post|int|null $post  Post.
 * @param int              $count Number of posts.
 * @return WP_Post[]
 */
function ze_get_related_posts( $post = null, $count = 3 ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return array();
	}

	$args = array(
		'posts_per_page' => $count,
		'post__not_in'   => array( $post->ID ),
	);
	$term = ze_get_primary_category( $post );

	if ( $term ) {
		$args['cat'] = $term->term_id;
	} else {
		$tag_ids = wp_get_post_tags( $post->ID, array( 'fields' => 'ids' ) );

		if ( empty( $tag_ids ) || is_wp_error( $tag_ids ) ) {
			return array();
		}

		$args['tag__in'] = $tag_ids;
	}

	$query = ze_query_posts( $args );

	return $query->posts;
}

/**
 * Add anchors to H2/H3 headings and prepend a table of contents.
 *
 * @param string $content Post content.
 * @return string
 */
function ze_content_toc( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() || get_the_ID() !== get_queried_object_id() ) {
		return $content;
	}

	if ( ! ze_is_on( 'single_toc' ) ) {
		return $content;
	}

	$items = array();
	$used  = array();

	$content = preg_replace_callback(
		'/<h([23])([^>]*)>(.*?)<\/h\1>/is',
		function ( $match ) use ( &$items, &$used ) {
			$text = trim( wp_strip_all_tags( $match[3] ) );

			if ( '' === $text ) {
				return $match[0];
			}

			$result = $match[0];

			if ( preg_match( '/\sid=(["\'])([^"\']+)\1/i', $match[2], $id_match ) ) {
				$anchor = $id_match[2];
			} else {
				$base   = sanitize_title( $text );
				$base   = '' === $base ? 'bagian' : $base;
				$anchor = $base;
				$n      = 1;

				while ( isset( $used[ $anchor ] ) ) {
					$n++;
					$anchor = $base . '-' . $n;
				}

				$result = '<h' . $match[1] . $match[2] . ' id="' . esc_attr( $anchor ) . '">' . $match[3] . '</h' . $match[1] . '>';
			}

			$used[ $anchor ] = true;
			$items[]         = array(
				'level'  => (int) $match[1],
				'anchor' => $anchor,
				'text'   => $text,
			);

			return $result;
		},
		$content
	);

	if ( count( $items ) < 2 ) {
		return $content;
	}

	$html     = '<nav class="ze-toc" aria-label="' . esc_attr__( 'Daftar isi', 'zona-ekonomi' ) . '"><details open><summary>' . esc_html__( 'Daftar isi', 'zona-ekonomi' ) . '</summary><ol class="ze-toc__list">';
	$li_open  = false;
	$sub_open = false;

	foreach ( $items as $item ) {
		$link = '<a href="#' . esc_attr( $item['anchor'] ) . '">' . esc_html( $item['text'] ) . '</a>';

		if ( 2 === $item['level'] ) {
			if ( $sub_open ) {
				$html    .= '</ol>';
				$sub_open = false;
			}
			if ( $li_open ) {
				$html .= '</li>';
			}
			$html   .= '<li>' . $link;
			$li_open = true;
		} else {
			if ( ! $li_open ) {
				$html   .= '<li>';
				$li_open = true;
			}
			if ( ! $sub_open ) {
				$html    .= '<ol>';
				$sub_open = true;
			}
			$html .= '<li>' . $link . '</li>';
		}
	}

	if ( $sub_open ) {
		$html .= '</ol>';
	}
	if ( $li_open ) {
		$html .= '</li>';
	}

	$html .= '</ol></details></nav>';

	return $html . $content;
}
add_filter( 'the_content', 'ze_content_toc', 12 );

/* -------------------------------------------------------------------------
 * Archive headings
 * ---------------------------------------------------------------------- */

/**
 * Plain-text title for the current listing.
 *
 * @return string
 */
function ze_get_archive_title() {
	if ( is_home() ) {
		return is_front_page() ? __( 'Artikel Terbaru', 'zona-ekonomi' ) : single_post_title( '', false );
	}

	if ( is_search() ) {
		/* translators: %s: search query. */
		return sprintf( __( 'Hasil pencarian: %s', 'zona-ekonomi' ), get_search_query( false ) );
	}

	if ( is_author() ) {
		$author = get_queried_object();

		return $author instanceof WP_User ? $author->display_name : '';
	}

	return trim( wp_strip_all_tags( get_the_archive_title() ) );
}

/**
 * Description (HTML) for the current listing.
 *
 * @return string
 */
function ze_get_archive_description() {
	if ( is_author() ) {
		return wpautop( esc_html( (string) get_the_author_meta( 'description', get_queried_object_id() ) ) );
	}

	if ( is_home() || is_search() ) {
		return '';
	}

	return (string) get_the_archive_description();
}

/* -------------------------------------------------------------------------
 * Misc filters
 * ---------------------------------------------------------------------- */

add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

/**
 * Excerpt length.
 *
 * @return int
 */
function ze_excerpt_length() {
	return 28;
}
add_filter( 'excerpt_length', 'ze_excerpt_length' );

/**
 * Excerpt ending.
 *
 * @return string
 */
function ze_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'ze_excerpt_more' );

/**
 * Body classes driven by the Customizer.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function ze_body_classes( $classes ) {
	if ( ze_is_on( 'header_sticky' ) ) {
		$classes[] = 'ze-sticky-header';
	}

	$classes[] = 'ze-mobile-nav--' . ( 'fullscreen' === ze_option( 'mobile_nav_style' ) ? 'fullscreen' : 'dropdown' );

	if ( is_singular( 'post' ) ) {
		$classes[] = 'ze-single--' . ze_get_single_layout();
	}

	return $classes;
}
add_filter( 'body_class', 'ze_body_classes' );
