<?php

namespace Osprey;

class Services extends Registry {

	/**
	 * @return Registry
	 */
	public static function formFields() {
		static $registry;

		if (is_null($registry)) {
			$registry = new Registry([
				'text' => [
					'class' => '\Osprey\Form\Field\Input'
				],
				'hidden' => [
					'class' => '\Osprey\Form\Field\Input'
				],
				'number' => [
					'class' => '\Osprey\Form\Field\Input'
				],
				'email' => [
					'class' => '\Osprey\Form\Field\Input'
				],
				'submit' => [
					'class' => '\Osprey\Form\Field\Input'
				],
				'button' => [
					'class' => '\Osprey\Form\Field\Input'
				],
				'textarea' => [
					'class' => '\Osprey\Form\Field\Textarea'
				],
				'checkbox' => [
					'class' => '\Osprey\Form\Field\Checkbox'
				],
				'checkboxes' => [
					'class' => '\Osprey\Form\Field\Checkboxes'
				],
				'select' => [
					'class' => '\Osprey\Form\Field\Select'
				],
				'item_list' => [
					'class' => '\Osprey\Form\Field\ItemList'
				],
				'markup' => [
					'class' => '\Osprey\Form\Field\Markup'
				],
			]);
		}

		return $registry;
	}

	/**
	 * @return Registry
	 */
	public static function formStyles() {
		static $registry;

		if (is_null($registry)) {
			$registry = new Registry([
				'default' => [
					'class' => '\Osprey\Form\Style\Flat'
				],
				'flat' => [
					'class' => '\Osprey\Form\Style\Flat',
				],
				'box' => [
					'class' => '\Osprey\Form\Style\Box',
				],
				'inline' => [
					'class' => '\Osprey\Form\Style\Inline',
				],
				'table' => [
					'class' => '\Osprey\Form\Style\Table',
				],
			]);
		}

		return $registry;
	}

	/**
	 * @return Registry
	 */
	public static function queryTypes() {
		static $registry;

		if (is_null($registry)) {
			$registry = new Registry([
				'post' => [
					'title' => __('Post'),
					'query_class' => '\Osprey\Query\Posts',
					'entity_class' => '\Osprey\Entity\Post',
				],
				'user' => [
					'title' => __('User'),
					'query_class' => '\Osprey\Query\Users',
					'entity_class' => '\Osprey\Entity\User',
				],
				'term' => [
					'title' => __('Term'),
					'query_class' => '\Osprey\Query\Terms',
					'entity_class' => '\Osprey\Entity\Term',
				],
				'comment' => [
					'title' => __('Comment'),
					'query_class' => '\Osprey\Query\Comments',
					'entity_class' => '\Osprey\Entity\Comment',
				],
			]);
		}

		return $registry;
	}
}
