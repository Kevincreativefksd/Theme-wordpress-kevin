<?php
/**
 * Article grid with pagination. The first image is eager-loaded (likely LCP).
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! have_posts() ) {
	get_template_part( 'template-parts/content/none' );
	return;
}

$ze_index = 0;
?>
<div class="ze-grid ze-grid--cols-<?php echo (int) ze_get_columns(); ?>">
	<?php
	while ( have_posts() ) {
		the_post();
		get_template_part(
			'template-parts/cards/card',
			null,
			array(
				'post'  => get_post(),
				'eager' => 0 === $ze_index && ! is_paged(),
			)
		);
		$ze_index++;
	}
	?>
</div>
<?php
the_posts_pagination(
	array(
		'mid_size'  => 1,
		'prev_text' => esc_html__( 'Sebelumnya', 'zona-ekonomi' ),
		'next_text' => esc_html__( 'Berikutnya', 'zona-ekonomi' ),
		'aria_label' => esc_attr__( 'Navigasi halaman artikel', 'zona-ekonomi' ),
	)
);
