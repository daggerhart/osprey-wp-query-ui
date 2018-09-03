<?php

namespace Osprey\Form\Field;

class Markup implements FieldInterface {

	/**
	 * Generic output.
	 *
	 * @param $field
	 *
	 * @return string
	 */
	function render( $field ) {
		return $field['value'];
	}

}
