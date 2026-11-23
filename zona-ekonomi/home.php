<?php
/**
 * Blog index (posts page).
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
get_template_part( 'template-parts/content/listing' );
get_footer();
