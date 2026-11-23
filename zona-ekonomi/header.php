<?php
/**
 * Site header: document head, skip link, site header.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Lewati ke konten', 'zona-ekonomi' ); ?></a>
<?php if ( is_singular( 'post' ) && ze_is_on( 'single_progress' ) ) : ?>
	<div class="ze-progress" aria-hidden="true"><span></span></div>
<?php endif; ?>
<?php get_template_part( 'template-parts/header/site-header' ); ?>
<main id="content" class="site-main" tabindex="-1">
