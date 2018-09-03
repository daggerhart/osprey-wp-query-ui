<?php

namespace Osprey;

interface RegistryInterface {

	/**
	 * Return all items in this registry.
	 *
	 * @return array
	 */
	public function items();

	/**
	 * Get a specific item in the registry.
	 *
	 * @param $key
	 *   The name of the item in the registry.
	 *
	 * @return mixed
	 */
	public function get( $key );

	/**
	 * Set a specific item in the registry.
	 *
	 * @param $key
	 *   The name of the item in the registry.
	 * @param $data
	 *   The new class that should represent the item in the registry.
	 *
	 * @void
	 */
	public function set( $key, $data );

}
