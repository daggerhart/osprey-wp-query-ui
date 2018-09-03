<?php

namespace Osprey\PostType;

use Osprey\Renderer;

class MetaBoxPreview extends AbstractMetaBox {

	/**
	 * {@inheritdoc}
	 */
	public function id() {
		return 'osprey-preview';
	}

	/**
	 * {@inheritdoc}
	 */
	public function title() {
		return __('Preview');
	}

	/**
	 * {@inheritdoc}
	 */
	public function embed($post, $box) {
		// Render the query
		$renderer = new Renderer($post);
		//$query_display = $post->meta('_osprey_query_display');

		print $renderer->render();
	}

	/**
	 * {@inheritdoc}
	 */
	public function save($post_id) {
		return;
	}
}
