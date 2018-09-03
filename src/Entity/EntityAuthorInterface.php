<?php

namespace Osprey\Entity;

/**
 * Interface AuthorInterface
 *
 * @package Osprey
 */
interface EntityAuthorInterface {

	/**
	 * @return \Osprey\Entity\User
	 */
	public function author();
}
