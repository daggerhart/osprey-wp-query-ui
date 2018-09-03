<?php

namespace Osprey\Form\Field;

use Osprey\Form\Form;

class Input implements FieldInterface {

	public function render( $field ) {
		ob_start();
		?>
		<input type="<?php echo esc_attr( $field['type'] ) ?>"
		       name="<?php echo esc_attr( $field['form_name'] ); ?>"
		       id="<?php echo esc_attr( $field['id'] ); ?>"
		       class="<?php echo esc_attr( $field['classes'] ); ?>"
		       value="<?php echo esc_attr( $field['value'] ); ?>"
			<?php echo Form::attributes( $field['attributes'] ); ?>
		>
		<?php
		return ob_get_clean();
	}

}
