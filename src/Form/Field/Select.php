<?php

namespace Osprey\Form\Field;

use Osprey\Form\Form;

class Select implements FieldInterface {

	/**
	 * Select box
	 *  - expects an array of options as $field['options']
	 *
	 * @param $field
	 *
	 * @return string
	 */
	function render( $field ) {
		ob_start();
		?>
		<select name="<?php echo esc_attr( $field['form_name'] ); ?>"
		        id="<?php echo esc_attr( $field['id'] ); ?>"
		        class="<?php echo esc_attr( $field['classes'] ); ?>"
			<?php echo Form::attributes( $field['attributes'] ); ?> >
			<?php foreach( $field['options'] as $value => $option ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $field['value'] ); ?>><?php echo esc_html( $option ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
		return ob_get_clean();
	}

}
