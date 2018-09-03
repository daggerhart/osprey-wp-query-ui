<?php

namespace Osprey\PostType;

use Osprey\Query\QueryInterface;
use Osprey\Services;

class MetaBoxQueryForm extends AbstractMetaBox {

	/**
	 * {@inheritdoc}
	 */
	public function id() {
		return 'osprey-query-form';
	}

	/**
	 * {@inheritdoc}
	 */
	public function title() {
		return __('Query Form');
	}

	/**
	 * {@inheritdoc}
	 */
	public function embed($post, $box) {
		parent::embed($post, $box);
		$query_type = get_post_meta($post->ID, '_osprey_query_type', TRUE);
		$query_args = get_post_meta($post->ID, '_osprey_query_args', TRUE);
		$query_display = get_post_meta($post->ID, '_osprey_query_display', TRUE);

		$type = Services::queryTypes()->get($query_type);

		if (empty($type)) {
			return 'query type not recognized';
		}
		else {
			print $type['title'].'<br>'.$type['query_class'].'<br>'.$type['entity_class'];
		}

		$form = $this->form();

		/** @var QueryInterface $query */
		$query = new $type['query_class']([]);
		$schema = $query->schema();

		foreach ($schema as $key => $item) {
			$field = [
				'title' => $item['title'],
				'name' => $key,
				'name_prefix' => '[args]',
				'value' => isset($query_args[$key]) ? $query_args[$key] : ( isset($item['default']) ? $item['default'] : null),
			];

			switch ($item['type']) {
				case 'string':
					$field['type'] = 'text';

					if (!empty($item['allowed'])) {
						$field['type'] = 'select';
						$field['options'] = $item['allowed'];

						if (!empty($item['multiple'])) {
							$field['type'] = 'checkboxes';
						}
					}
					if (!empty($item['allowed_callback']) && is_callable($item['allowed_callback'])) {
						$field['type'] = 'select';
						$field['options'] = call_user_func($item['allowed_callback']);

						if (!empty($item['multiple'])) {
							$field['type'] = 'checkboxes';
						}
					}
					break;

				case 'number':
					$field['type'] = 'number';
					break;

				case 'boolean':
					$field['type'] = 'checkbox';
					break;

				default:
					continue;
					break;
			}

			print $form->renderField($field);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function save($post_id) {
		$continue = parent::save($post_id);

		if (!$continue) {
			return;
		}

		$query_type = get_post_meta($post_id, '_osprey_query_type', TRUE);
		$query_args = get_post_meta($post_id, '_osprey_query_args', TRUE);
		$query_display = get_post_meta($post_id, '_osprey_query_display', TRUE);

		$type = Services::queryTypes()->get($query_type);
		$form = $this->form();
		/** @var QueryInterface $query */
		$query = new $type['query_class']([]);
		$schema = $query->schema();

		$submitted_args = $form->submittedValue('args');

		$args = [];

		foreach ($schema as $key => $item) {
			if (!isset($submitted_args[$key])) {
				continue;
			}

			// If the value submitted is empty, and the default value for the
			// schema item is null, don't save this value at all. Allow the
			// query class to use its internal default value.
			if (isset($item['default']) && $submitted_args[$key] != $item['default']) {
				$args[$key] = $submitted_args[$key];
			}
		}

		update_post_meta($post_id, '_osprey_query_args', $args);
	}
}
