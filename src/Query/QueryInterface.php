<?php

namespace Osprey\Query;

interface QueryInterface {

	/**
	 * Execute a query and return normalized results.
	 *
	 * @param string $entity_class
	 * @param null|callable $callback
	 *
	 * @return array
	 */
	public function execute( $entity_class, $callback = null );

	/**
	 * @return array
	 */
	public function schema();
}
