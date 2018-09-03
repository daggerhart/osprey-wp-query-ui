<?php

namespace Osprey\Entity;

/**
 * Interface BundleInterface
 *
 * @package Osprey
 */
interface EntityBundleInterface {

	/**
	 * Returns the bundle name/id.
	 *
	 * @return string
	 */
	public function bundle();

	/**
	 * Returns what information it can about the bundle.
	 *
	 * @return array
	 */
	public function bundleInfo();
}
