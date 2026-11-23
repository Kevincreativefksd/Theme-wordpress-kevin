<?php
/**
 * Homepage. Page 2+ of a "latest posts" homepage falls back to the plain listing.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( is_paged() ) {
	get_template_part( 'template-parts/content/listing' );
	get_footer();
	return;
}

$ze_home    = ze_get_home_data();
$ze_tagline = get_bloginfo( 'description' );
$ze_title   = get_bloginfo( 'name' ) . ( '' !== $ze_tagline ? ' – ' . $ze_tagline : '' );
$ze_any     = $ze_home['hero'] || $ze_home['featured'] || $ze_home['latest'] || $ze_home['popular'] || $ze_home['editors'] || $ze_home['categories'];
?>
<h1 class="screen-reader-text"><?php echo esc_html( $ze_title ); ?></h1>
<?php
if ( ! $ze_any ) {
	echo '<div class="section"><div class="container">';
	get_template_part( 'template-parts/content/none' );
	echo '</div></div>';
	get_footer();
	return;
}

if ( $ze_home['hero'] ) {
	get_template_part( 'template-parts/homepage/hero', null, array( 'post' => $ze_home['hero'] ) );
}

if ( ze_is_on( 'home_show_catnav' ) ) {
	get_template_part( 'template-parts/navigation/category-nav' );
}

if ( $ze_home['featured'] ) {
	get_template_part( 'template-parts/homepage/featured', null, array( 'post' => $ze_home['featured'] ) );
}

if ( $ze_home['latest'] ) {
	get_template_part( 'template-parts/homepage/latest', null, array( 'posts' => $ze_home['latest'] ) );
}

if ( $ze_home['popular'] ) {
	get_template_part( 'template-parts/homepage/popular', null, array( 'posts' => $ze_home['popular'] ) );
}

if ( $ze_home['editors'] ) {
	get_template_part( 'template-parts/homepage/editors-pick', null, array( 'posts' => $ze_home['editors'] ) );
}

foreach ( $ze_home['categories'] as $ze_section ) {
	get_template_part( 'template-parts/homepage/category-section', null, $ze_section );
}

get_footer();
