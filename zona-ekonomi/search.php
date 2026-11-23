<?php
/**
 * Search results.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
get_template_part( 'template-parts/content/archive-header' );
?>
<div class="container search-page__form">
	<?php get_search_form(); ?>
</div>
<div class="section">
	<div class="container">
		<?php get_template_part( 'template-parts/content/loop' ); ?>
	</div>
</div>
<?php
get_footer();
