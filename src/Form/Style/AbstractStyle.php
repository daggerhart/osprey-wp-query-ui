<?php

namespace Osprey\Form\Style;

abstract class AbstractStyle implements StyleInterface {

	/**
	 * {@inheritdoc}
	 */
	public function wrapForm( $form ) {
		return "<div class='form form-wrapper-{$this->id()}'>{$form}</div>";
	}
}
