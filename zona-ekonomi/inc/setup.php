<?php
/**
 * Theme setup: supports, menus, image sizes, widget areas.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports, menus and image sizes.
 */
function ze_setup() {
	load_theme_textdomain( 'zona-ekonomi', ZE_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Hitam', 'zona-ekonomi' ),
				'slug'  => 'ze-black',
				'color' => '#000000',
			),
			array(
				'name'  => __( 'Putih', 'zona-ekonomi' ),
				'slug'  => 'ze-white',
				'color' => '#ffffff',
			),
			array(
				'name'  => __( 'Abu-abu', 'zona-ekonomi' ),
				'slug'  => 'ze-gray',
				'color' => '#666666',
			),
			array(
				'name'  => __( 'Aksen', 'zona-ekonomi' ),
				'slug'  => 'ze-accent',
				'color' => ze_get_color( 'color_accent' ),
			),
		)
	);

	add_editor_style( 'assets/css/editor.css' );

	register_nav_menus(
		array(
			'primary'   => __( 'Navigasi utama', 'zona-ekonomi' ),
			'secondary' => __( 'Navigasi sekunder (di bawah header)', 'zona-ekonomi' ),
			'footer'    => __( 'Navigasi footer', 'zona-ekonomi' ),
		)
	);

	// Ukuran gambar. Rasio 3:2 atau 16:9 agar srcset konsisten.
	add_image_size( 'ze-thumb', 240, 160, true );
	add_image_size( 'ze-card', 720, 480, true );
	add_image_size( 'ze-feature', 1200, 800, true );
	add_image_size( 'ze-wide-sm', 800, 450, true );
	add_image_size( 'ze-wide', 1280, 720, true );
}
add_action( 'after_setup_theme', 'ze_setup' );

/**
 * Content width for embeds.
 */
function ze_content_width() {
	$GLOBALS['content_width'] = 720;
}
add_action( 'after_setup_theme', 'ze_content_width', 0 );

/**
 * Register the article sidebar.
 */
function ze_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar artikel', 'zona-ekonomi' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Tampil pada layout artikel B (Editorial) jika sidebar diaktifkan. Jika kosong, tema menampilkan daftar artikel terbaru.', 'zona-ekonomi' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'ze_widgets_init' );
