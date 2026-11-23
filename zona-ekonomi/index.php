<?php
/**
 * Fallback template for any listing without a more specific template.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
get_template_part( 'template-parts/content/listing' );
get_footer();
