<?php

namespace Osprey\Form\Field;

class Checkbox implements FieldInterface {

	public function render( $field ) {
		$input = new Input();

		$hidden = array_replace( $field, [
			'type' => 'hidden',
			'value' => 0,
			'id' => $field['id'] . '--hidden',
			'attributes' => [],
			'class' => 'field-hidden',
		]);

		ob_start();
		print $input->render( $hidden );

		if ( !empty( $field['value'] ) ) {
			$field['attributes']['checked'] = 'checked';
		}

		$field['value'] = 'on';
		print $input->render( $field );

		return ob_get_clean();
	}

}
