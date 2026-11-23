<?php
/**
 * Generic listing: heading, category navigation (home and categories), grid.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_template_part( 'template-parts/content/archive-header' );

if ( ( is_home() || is_category() ) && ze_is_on( 'home_show_catnav' ) ) {
	get_template_part( 'template-parts/navigation/category-nav' );
}
?>
<div class="section">
	<div class="container">
		<?php get_template_part( 'template-parts/content/loop' ); ?>
	</div>
</div>
