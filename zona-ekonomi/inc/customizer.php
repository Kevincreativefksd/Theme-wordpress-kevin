<?php
/**
 * Customizer: all theme settings live in the "Zona Ekonomi" panel.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize a checkbox.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function ze_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Sanitize a select/radio value against the control choices.
 *
 * @param mixed                $value   Raw value.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return mixed
 */
function ze_sanitize_choice( $value, $setting ) {
	$control = $setting->manager->get_control( $setting->id );

	if ( $control && isset( $control->choices[ $value ] ) ) {
		return $value;
	}

	return $setting->default;
}

/**
 * Sanitize a number against the min/max of the control.
 *
 * @param mixed                $value   Raw value.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return int
 */
function ze_sanitize_range( $value, $setting ) {
	$control = $setting->manager->get_control( $setting->id );
	$min     = 0;
	$max     = 9999;

	if ( $control && isset( $control->input_attrs['min'], $control->input_attrs['max'] ) ) {
		$min = (int) $control->input_attrs['min'];
		$max = (int) $control->input_attrs['max'];
	}

	return max( $min, min( $max, (int) $value ) );
}

/**
 * Sanitize a list of IDs into "1,2,3".
 *
 * @param mixed $value Raw value.
 * @return string
 */
function ze_sanitize_id_list( $value ) {
	return implode( ',', ze_parse_ids( $value ) );
}

/**
 * Sanitize a post ID.
 *
 * @param mixed $value Raw value.
 * @return int
 */
function ze_sanitize_post_id( $value ) {
	return absint( $value );
}

/**
 * Latest published posts for the "manual hero" dropdown (Customizer only).
 *
 * @return array
 */
function ze_get_post_choices() {
	$choices = array( 0 => __( '— Pilih artikel —', 'zona-ekonomi' ) );
	$posts   = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	);

	foreach ( $posts as $post ) {
		$choices[ $post->ID ] = wp_strip_all_tags( get_the_title( $post ) );
	}

	return $choices;
}

/**
 * Active callback: show the manual hero picker only when "manual" is chosen.
 *
 * @param WP_Customize_Control $control Control instance.
 * @return bool
 */
function ze_cb_hero_manual( $control ) {
	return 'manual' === $control->manager->get_setting( 'ze_home_hero_source' )->value();
}

/**
 * Register panel, sections, settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function ze_customize_register( $wp_customize ) {
	require_once ZE_DIR . '/inc/customizer-controls.php';

	$defaults = ze_default_options();

	$wp_customize->add_panel(
		'ze_panel',
		array(
			'title'       => __( 'Zona Ekonomi', 'zona-ekonomi' ),
			'description' => __( 'Pengaturan tema Zona Ekonomi. Logo dan ikon situs diatur di "Identitas Situs"; menu diatur di "Menu".', 'zona-ekonomi' ),
			'priority'    => 30,
		)
	);

	$sections = array(
		'ze_general'    => __( 'Umum', 'zona-ekonomi' ),
		'ze_header'     => __( 'Header', 'zona-ekonomi' ),
		'ze_home'       => __( 'Beranda', 'zona-ekonomi' ),
		'ze_single'     => __( 'Artikel', 'zona-ekonomi' ),
		'ze_footer'     => __( 'Footer', 'zona-ekonomi' ),
		'ze_appearance' => __( 'Tampilan: mode, warna, tipografi', 'zona-ekonomi' ),
	);

	$priority = 10;
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section(
			$id,
			array(
				'title'    => $title,
				'panel'    => 'ze_panel',
				'priority' => $priority,
			)
		);
		$priority += 10;
	}

	/**
	 * Add one setting with its control.
	 */
	$add = function ( $key, $section, $label, $type, $sanitize, $extra = array() ) use ( $wp_customize, $defaults ) {
		$wp_customize->add_setting(
			'ze_' . $key,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => $sanitize,
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control(
			'ze_' . $key,
			array_merge(
				array(
					'section' => $section,
					'label'   => $label,
					'type'    => $type,
				),
				$extra
			)
		);
	};

	// Umum.
	$add(
		'brand_description',
		'ze_general',
		__( 'Deskripsi brand', 'zona-ekonomi' ),
		'textarea',
		'sanitize_textarea_field',
		array( 'description' => __( 'Ditampilkan di footer.', 'zona-ekonomi' ) )
	);
	$add(
		'copyright_text',
		'ze_general',
		__( 'Teks copyright', 'zona-ekonomi' ),
		'textarea',
		'wp_kses_post',
		array( 'description' => __( 'Gunakan {year} untuk tahun berjalan dan {site} untuk nama situs.', 'zona-ekonomi' ) )
	);

	// Header.
	$add( 'header_search', 'ze_header', __( 'Tampilkan tombol pencarian', 'zona-ekonomi' ), 'checkbox', 'ze_sanitize_checkbox' );
	$add( 'header_sticky', 'ze_header', __( 'Header menempel saat halaman digulir', 'zona-ekonomi' ), 'checkbox', 'ze_sanitize_checkbox' );
	$add(
		'logo_width',
		'ze_header',
		__( 'Lebar logo (px)', 'zona-ekonomi' ),
		'range',
		'ze_sanitize_range',
		array(
			'input_attrs' => array(
				'min'  => 80,
				'max'  => 320,
				'step' => 10,
			),
		)
	);
	$add(
		'mobile_nav_style',
		'ze_header',
		__( 'Gaya navigasi mobile', 'zona-ekonomi' ),
		'select',
		'ze_sanitize_choice',
		array(
			'choices' => array(
				'dropdown'   => __( 'Dropdown di bawah header', 'zona-ekonomi' ),
				'fullscreen' => __( 'Layar penuh', 'zona-ekonomi' ),
			),
		)
	);

	// Beranda.
	$add(
		'home_hero_source',
		'ze_home',
		__( 'Sumber artikel unggulan (hero)', 'zona-ekonomi' ),
		'select',
		'ze_sanitize_choice',
		array(
			'choices' => array(
				'latest' => __( 'Artikel terbaru', 'zona-ekonomi' ),
				'manual' => __( 'Pilih manual', 'zona-ekonomi' ),
			),
		)
	);
	$add(
		'home_hero_post',
		'ze_home',
		__( 'Artikel unggulan', 'zona-ekonomi' ),
		'select',
		'ze_sanitize_post_id',
		array(
			'choices'         => ze_get_post_choices(),
			'active_callback' => 'ze_cb_hero_manual',
			'description'     => __( 'Jika artikel dihapus atau tidak dipublikasikan, tema memakai artikel terbaru.', 'zona-ekonomi' ),
		)
	);
	$add(
		'home_latest_count',
		'ze_home',
		__( 'Jumlah artikel pada grid "Artikel Terbaru"', 'zona-ekonomi' ),
		'range',
		'ze_sanitize_range',
		array(
			'input_attrs' => array(
				'min'  => 3,
				'max'  => 12,
				'step' => 1,
			),
		)
	);
	$add(
		'home_columns',
		'ze_home',
		__( 'Jumlah kolom grid (desktop)', 'zona-ekonomi' ),
		'select',
		'ze_sanitize_choice',
		array(
			'description' => __( 'Berlaku juga untuk halaman arsip. Tablet selalu 2 kolom dan mobile 1 kolom.', 'zona-ekonomi' ),
			'choices'     => array(
				'2' => __( '2 kolom', 'zona-ekonomi' ),
				'3' => __( '3 kolom', 'zona-ekonomi' ),
				'4' => __( '4 kolom', 'zona-ekonomi' ),
			),
		)
	);

	$wp_customize->add_setting(
		'ze_catnav_ids',
		array(
			'default'           => $defaults['catnav_ids'],
			'sanitize_callback' => 'ze_sanitize_id_list',
			'capability'        => 'edit_theme_options',
		)
	);
	$wp_customize->add_control(
		new ZE_Customize_Category_Control(
			$wp_customize,
			'ze_catnav_ids',
			array(
				'section'     => 'ze_home',
				'label'       => __( 'Kategori pada navigasi kategori', 'zona-ekonomi' ),
				'description' => __( 'Tidak ada yang dicentang: tema menampilkan 8 kategori dengan artikel terbanyak.', 'zona-ekonomi' ),
			)
		)
	);

	$wp_customize->add_setting(
		'ze_catsection_ids',
		array(
			'default'           => $defaults['catsection_ids'],
			'sanitize_callback' => 'ze_sanitize_id_list',
			'capability'        => 'edit_theme_options',
		)
	);
	$wp_customize->add_control(
		new ZE_Customize_Category_Control(
			$wp_customize,
			'ze_catsection_ids',
			array(
				'section'     => 'ze_home',
				'label'       => __( 'Kategori yang tampil sebagai section sendiri', 'zona-ekonomi' ),
				'description' => __( 'Maksimal 4 kategori. Tidak ada yang dicentang: section tidak tampil.', 'zona-ekonomi' ),
			)
		)
	);

	$home_toggles = array(
		'home_show_hero'       => __( 'Section: Hero (artikel unggulan)', 'zona-ekonomi' ),
		'home_show_catnav'     => __( 'Section: Navigasi kategori', 'zona-ekonomi' ),
		'home_show_featured'   => __( 'Section: Artikel unggulan dua kolom', 'zona-ekonomi' ),
		'home_show_latest'     => __( 'Section: Artikel terbaru', 'zona-ekonomi' ),
		'home_show_popular'    => __( 'Section: Artikel populer (berdasarkan jumlah komentar)', 'zona-ekonomi' ),
		'home_show_editors'    => __( 'Section: Pilihan editor (artikel yang di-"sticky")', 'zona-ekonomi' ),
		'home_show_categories' => __( 'Section: Artikel per kategori', 'zona-ekonomi' ),
	);
	foreach ( $home_toggles as $key => $label ) {
		$add( $key, 'ze_home', $label, 'checkbox', 'ze_sanitize_checkbox' );
	}

	// Artikel.
	$add(
		'single_layout',
		'ze_single',
		__( 'Layout artikel', 'zona-ekonomi' ),
		'select',
		'ze_sanitize_choice',
		array(
			'choices' => array(
				'a' => __( 'A: Minimalist (satu kolom)', 'zona-ekonomi' ),
				'b' => __( 'B: Editorial (judul besar, sidebar opsional)', 'zona-ekonomi' ),
			),
		)
	);
	$add(
		'single_sidebar',
		'ze_single',
		__( 'Aktifkan sidebar (khusus layout B)', 'zona-ekonomi' ),
		'checkbox',
		'ze_sanitize_checkbox'
	);
	$add( 'single_toc', 'ze_single', __( 'Daftar isi otomatis (heading H2 dan H3)', 'zona-ekonomi' ), 'checkbox', 'ze_sanitize_checkbox' );
	$add( 'single_related', 'ze_single', __( 'Artikel terkait', 'zona-ekonomi' ), 'checkbox', 'ze_sanitize_checkbox' );
	$add( 'single_progress', 'ze_single', __( 'Reading progress bar', 'zona-ekonomi' ), 'checkbox', 'ze_sanitize_checkbox' );
	$add( 'meta_author', 'ze_single', __( 'Metadata: nama penulis', 'zona-ekonomi' ), 'checkbox', 'ze_sanitize_checkbox' );
	$add( 'meta_date', 'ze_single', __( 'Metadata: tanggal terbit', 'zona-ekonomi' ), 'checkbox', 'ze_sanitize_checkbox' );
	$add( 'meta_modified', 'ze_single', __( 'Metadata: tanggal diperbarui', 'zona-ekonomi' ), 'checkbox', 'ze_sanitize_checkbox' );
	$add( 'meta_reading', 'ze_single', __( 'Metadata: estimasi waktu baca', 'zona-ekonomi' ), 'checkbox', 'ze_sanitize_checkbox' );

	// Footer.
	$socials = array(
		'social_facebook'  => 'Facebook',
		'social_x'         => 'X',
		'social_instagram' => 'Instagram',
		'social_youtube'   => 'YouTube',
		'social_linkedin'  => 'LinkedIn',
		'social_tiktok'    => 'TikTok',
	);
	foreach ( $socials as $key => $label ) {
		$add(
			$key,
			'ze_footer',
			/* translators: %s: social network name. */
			sprintf( __( 'URL %s', 'zona-ekonomi' ), $label ),
			'url',
			'esc_url_raw'
		);
	}

	// Tampilan.
	$add(
		'color_mode',
		'ze_appearance',
		__( 'Mode warna', 'zona-ekonomi' ),
		'select',
		'ze_sanitize_choice',
		array(
			'choices' => array(
				'light' => __( 'Terang', 'zona-ekonomi' ),
				'dark'  => __( 'Gelap', 'zona-ekonomi' ),
				'auto'  => __( 'Ikuti pengaturan perangkat pengunjung', 'zona-ekonomi' ),
			),
		)
	);

	$colors = array(
		'color_bg'     => __( 'Warna latar (mode terang)', 'zona-ekonomi' ),
		'color_text'   => __( 'Warna teks (mode terang)', 'zona-ekonomi' ),
		'color_header' => __( 'Warna header, footer, dan hero', 'zona-ekonomi' ),
		'color_accent' => __( 'Warna aksen (tautan dan kategori)', 'zona-ekonomi' ),
	);
	foreach ( $colors as $key => $label ) {
		$wp_customize->add_setting(
			'ze_' . $key,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => 'sanitize_hex_color',
				'capability'        => 'edit_theme_options',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ze_' . $key,
				array(
					'section' => 'ze_appearance',
					'label'   => $label,
				)
			)
		);
	}

	$font_choices = array(
		'sans'  => __( 'Sans-serif sistem', 'zona-ekonomi' ),
		'serif' => __( 'Serif sistem', 'zona-ekonomi' ),
	);
	$add( 'font_heading', 'ze_appearance', __( 'Font judul', 'zona-ekonomi' ), 'select', 'ze_sanitize_choice', array( 'choices' => $font_choices ) );
	$add( 'font_body', 'ze_appearance', __( 'Font isi', 'zona-ekonomi' ), 'select', 'ze_sanitize_choice', array( 'choices' => $font_choices ) );
	$add(
		'font_size',
		'ze_appearance',
		__( 'Ukuran teks dasar (px)', 'zona-ekonomi' ),
		'range',
		'ze_sanitize_range',
		array(
			'input_attrs' => array(
				'min'  => 15,
				'max'  => 20,
				'step' => 1,
			),
		)
	);
}
add_action( 'customize_register', 'ze_customize_register' );

/**
 * Script for the category checklist control (Customizer only).
 */
function ze_customize_controls_assets() {
	wp_enqueue_script(
		'ze-customizer-controls',
		ZE_URI . '/assets/js/customizer-controls.js',
		array( 'customize-controls' ),
		ZE_VERSION,
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'ze_customize_controls_assets' );
