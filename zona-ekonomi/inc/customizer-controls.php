<?php
/**
 * Custom Customizer controls. Loaded only inside the Customizer.
 *
 * @package zona-ekonomi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'ZE_Customize_Category_Control' ) ) {

	/**
	 * Checklist of existing categories. Stores the selected IDs as "1,2,3".
	 */
	class ZE_Customize_Category_Control extends WP_Customize_Control {

		/**
		 * Control type.
		 *
		 * @var string
		 */
		public $type = 'ze-category-checklist';

		/**
		 * Render the control.
		 */
		public function render_content() {
			$categories = get_categories(
				array(
					'hide_empty' => false,
					'orderby'    => 'name',
				)
			);
			$selected   = ze_parse_ids( $this->value() );
			?>
			<div class="ze-category-checklist">
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>

				<?php if ( empty( $categories ) ) : ?>
					<p><?php esc_html_e( 'Belum ada kategori.', 'zona-ekonomi' ); ?></p>
				<?php else : ?>
					<ul style="max-height:200px;overflow:auto;margin:8px 0;padding:0;list-style:none;">
						<?php foreach ( $categories as $category ) : ?>
							<li>
								<label>
									<input type="checkbox" class="ze-category-checklist__box" value="<?php echo esc_attr( $category->term_id ); ?>" <?php checked( in_array( (int) $category->term_id, $selected, true ) ); ?> />
									<?php echo esc_html( $category->name ); ?>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<input type="hidden" value="<?php echo esc_attr( implode( ',', $selected ) ); ?>" <?php $this->link(); ?> />
			</div>
			<?php
		}
	}
}
