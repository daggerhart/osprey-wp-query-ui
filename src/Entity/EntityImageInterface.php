<?php

namespace Osprey\Entity;

/**
 * Interface ImageInterface
 *
 * @package Osprey
 */
interface EntityImageInterface {

	/**
	 * HTML image for the object.
	 *
	 * @param null $size
	 *
	 * @return string
	 */
	public function image( $size = null );
}
