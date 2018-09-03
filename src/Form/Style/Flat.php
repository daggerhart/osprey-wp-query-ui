<?php

namespace Osprey\Form\Style;

class Flat extends AbstractStyle {

	public function id() {
		return 'flat';
	}

	/**
	 * Flat field style
	 *
	 * @param $field
	 * @param $html
	 *
	 * @return string
	 */
	public function wrapField($field, $html) {
	    ob_start();
		?>
		<div id="<?php echo esc_attr( $field['id'] ) ;?>--wrapper"
		     class="field-wrapper field-wrapper-flat field-type-<?php echo esc_attr( $field['type'] ) ;?>">

			<?php if ( !empty( $field['title'] ) && $field['label_first']) : ?>
				<label for="<?php echo esc_attr( $field['id'] ); ?>" class="field-label">
					<?php echo $field['title']; ?>
				</label>
			<?php endif; ?>

			<?php if ( !empty( $field['description'] ) ) : ?>
				<p class="description"><?php echo $field['description']; ?></p>
			<?php endif; ?>

			<?php echo $html; ?>

			<?php if ( !empty( $field['title'] ) && !$field['label_first']) : ?>
				<label for="<?php echo esc_attr( $field['id'] ); ?>" class="field-label">
					<?php echo $field['title']; ?>
				</label>
			<?php endif; ?>

			<?php if ( !empty($field['help']) ) : ?>
				<p class="description"><?php echo $field['help']; ?></p>
			<?php endif; ?>
		</div>
		<?php
        return ob_get_clean();
	}
}
