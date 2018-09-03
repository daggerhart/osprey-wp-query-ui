<?php

namespace Osprey\Form\Field;

use Osprey\Form\Form;

class Textarea implements FieldInterface {

	public function render( $field ) {
		ob_start();
		?>
		<textarea name="<?php echo esc_attr( $field['form_name'] ); ?>"
		          id="<?php echo esc_attr( $field['id'] ); ?>"
		          class="<?php echo esc_attr( $field['classes'] ); ?>"
			<?php echo Form::attributes( $field['attributes'] ); ?>
		><?php echo $this->escape( $field['value'] ); ?></textarea>
		<?php
		return ob_get_clean();
	}

	function escape( $value ) {
		return stripcslashes( esc_textarea( str_replace( "\\", "", $value ) ) );
	}
}
