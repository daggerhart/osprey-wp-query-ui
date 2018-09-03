<?php

namespace Osprey;

class Services {

	public static function types() {
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
