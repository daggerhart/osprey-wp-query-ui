<?php

namespace Osprey\PostType;

interface MetaBoxInterface {

	/**
	 * The unique ID for this meta box.
	 *
	 * @return string
	 */
	public function id();

	/**
	 * The human title for this meta box.
	 *
	 * @return string
	 */
	public function title();

	/**
	 * Output the meta box html.
	 *
	 * @param \WP_Post $post
	 * @param array $box
	 *
	 * @void
	 */
	public function embed($post, $box);

	/**
	 * Save the meta box data.
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public function save($post_id);
}
