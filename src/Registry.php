<?php

namespace Osprey;

/**
 * Class Registry.
 *
 * This serves as a container for various types of registered normalizations.
 *
 * @package Osprey
 */
class Registry implements RegistryInterface {

	/**
	 * @var array
	 */
	protected $items = array();

	/**
	 * Set the entire array of items with a new array.
	 *
	 * @param $items
	 */
	public function __construct( $items ) {
		$this->items = $items;
	}

	/**
	 * {@inheritdoc}
	 */
	public function items() {
		return $this->items;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get( $key ) {
		return isset( $this->items[ $key ] ) ? $this->items[ $key ] : null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set( $key, $class ) {
		$this->items[ $key ] = $class;
	}

}
