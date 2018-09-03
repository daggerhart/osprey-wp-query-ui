<?php

namespace Osprey\Entity;

/**
 * Class AbstractNormalObject
 *
 * @package Osprey\Entity
 */
abstract class AbstractEntity implements EntityInterface {

	/**
	 * Stored object that is being normalized.
	 *
	 * @var object
	 */
	protected $object;

	/**
	 * AbstractNormalObject constructor.
	 *
	 * @param $object
	 *   The object being normalized.
	 */
	public function __construct( $object ) {
		$this->object = $object;
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function meta( $name ) {
		return get_metadata( $this->type(), $this->id(), $name, true );
	}

}
