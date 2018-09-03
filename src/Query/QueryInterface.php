<?php

namespace Osprey\Query;

interface QueryInterface {

	/**
	 * Execute a query and return normalized results.
	 *
	 * @param null|callable $callback
	 *
	 * @return array
	 */
	public function execute( $callback = null );

	/**
	 * @return array
	 */
	public function schema();
}
