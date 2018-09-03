<?php

namespace Osprey;

use Osprey\Entity\Post;
use Osprey\Query\QueryInterface;

class Renderer {

	/**
	 * @var Post
	 */
	public $query_post;

	/**
	 * Renderer constructor.
	 *
	 * @param int $query - Osprey query post id.
	 */
	function __construct($query) {
		$this->query_post = new Post($query);
	}

	/**
	 * Render the query.
	 *
	 * @return string
	 */
	function render() {
		$type = Services::queryTypes()->get($this->query_post->meta('_osprey_query_type'));
		$query_args = $this->query_post->meta('_osprey_query_args');
		if (empty($query_args)) {
			$query_args = [];
		}

		/** @var QueryInterface $query */
		$query = new $type['query_class']($query_args);

		ob_start();

		$query->execute( function($entity) {
			/** @var Post $entity */
			print $entity->image();
			print $entity->title();
		});

		return ob_get_clean();
	}
}
