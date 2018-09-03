<?php

namespace Osprey\PostType;

use Osprey\Services;

class MetaBoxWizard extends AbstractMetaBox {

	/**
	 * {@inheritdoc}
	 */
	public function id() {
		return 'osprey-wizard';
	}

	/**
	 * {@inheritdoc}
	 */
	public function title() {
		return __('Wizard');
	}

	/**
	 * {@inheritdoc}
	 */
	public function embed($post, $box) {
		parent::embed($post, $box);

		$query_types = [];

		foreach (Services::queryTypes()->items() as $type => $item) {
			$query_types[$type] = $item['title'];
		}

		$form = $this->form();

		print $form->renderField([
			'title' => __('Query Type'),
			'type' => 'select',
			'name' => 'query_type',
			'options' => $query_types,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function save($post_id) {
		$continue = parent::save($post_id);

		if (!$continue) {
			return;
		}

		$form = $this->form();

		$query_type = $form->submittedValue('query_type');

		if ($query_type) {
			update_post_meta($post_id, '_osprey_query_type', sanitize_text_field($query_type));
		}
	}
}
