<?php
/**
 * Single article. Layout A or B is chosen in the Customizer.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/content/single' );

	if ( ze_is_on( 'single_related' ) ) {
		get_template_part( 'template-parts/content/related' );
	}

	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile;

get_footer();
