<?php

namespace Osprey\Form\Style;

interface StyleInterface {

	/**
	 * Wrapper unique ID.
	 *
	 * @return string
	 */
	public function id();

	/**
	 * Style form wrapper.
	 *
	 * @param $form
	 *
	 * @return string
	 */
	public function wrapForm($form);

	/**
	 * Style field wrapper.
	 *
	 * @param $field
	 * @param $html
	 *
	 * @return string
	 */
	public function wrapField($field, $html);
}
