<?php

namespace Osprey\Form\Field;

interface FieldInterface {

	/**
	 * @param $field
	 *
	 * @return string
	 */
	public function render( $field );
}
