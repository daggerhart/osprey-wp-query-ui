<?php

namespace Osprey\Query;

use Osprey\Entity\Term;

/**
 * Class Terms
 *
 * @package Osprey\Query
 */
class Terms extends AbstractQuery {

	/**
	 * {@inheritdoc}
	 */
	public function execute( $callback = NULL ) {
		$this->query = new \WP_Term_Query( $this->arguments );

		foreach ( $this->query->get_terms() as $term ) {
			$item = new Term( $term );
			$this->results[ $item->id() ] = $item;

			if ( is_callable( $callback ) ) {
				call_user_func( $callback, $term );
			}
		}

		return $this->results;
	}

	function schema() {
		return array_replace(parent::schema(), [
			'taxonomy'  => [
				'type' => 'list',
				'title' =>__('Taxonomies'),
				'allowed_callback' => ['', ''],
			],
			'name'  => [
				'type' => 'list',
				'title' =>__('Term Names'),
				'allowed_callback' => ['', ''],
			],
			'slug'  => [
				'type' => 'list',
				'title' =>__('Term Slugs'),
				'allowed_callback' => ['', ''],
			],
			'include'  => [
				'type' => 'list',
				'title' =>__('Include term IDs'),
			],
			'exclude'  => [
				'type' => 'list',
				'title' =>__('Exclude term IDs'),
			],
			'exclude_tree'  => [
				'type' => 'list',
				'title' =>__('Exclude term IDs and descendants'),
			],
			'orderby'  => [
				'type' => 'string',
				'title' =>__('Order by'),
				'allowed' => [
					'name' => __('Name'),
					'slug' => __('Slug'),
					'term_group' => __('Term group'),
					'term_id' => __('Term ID'),
					//'id' => __(''),
					'description' => __('Term description'),
					'parent' => __('Parent ID'),
					'count' => __('Term usage count'),
					'include' => __('Included order'),
					'slug__in' => __('Slug order'),
					'meta_value' => __('Meta value'),
					'meta_value_num' => __('Meta value number'),
					'none' => __('None'),
				],
				'default' => 'name',
			],
			'hide_empty'  => [
				'type' => 'boolean',
				'title' =>__('Hide empty'),
				'default' => true,
			],
			'count'  => [
				'type' => 'number',
				'title' =>__('Number of results'),
				'default' => false,
			],
			'term_taxonomy_id'  => [
				'type' => 'list',
				'title' =>__('Term taxonomy IDs'),
			],
			'hierarchical'  => [
				'type' => 'boolean',
				'title' =>__('Hierarchical'),
				'default' => true,
			],
			'search'  => [
				'type' => 'string',
				'title' =>__('Search'),
			],
			'name__like'  => [
				'type' => 'string',
				'title' =>__('Name like'),
			],
			'description__like'  => [
				'type' => 'string',
				'title' =>__('Description like'),
			],
			'pad_counts'  => [
				'type' => 'boolean',
				'title' =>__('Pad count'),
			],
			'child_of'  => [
				'type' => 'number',
				'title' =>__('Child of'),
				'default' => 0,
			],
			'parent'  => [
				'type' => 'number',
				'title' =>__('Parent term ID'),
			],
			'childless'  => [
				'type' => 'boolean',
				'title' =>__('Terms without descendants'),
				'default' => false,
			],
			'cache_domain'  => [
				'type' => 'string',
				'title' =>__('Cache domain'),
				'default' => 'core',
			],
			'update_term_meta_cache'  => [
				'type' => 'boolean',
				'title' =>__('Update term meta cache'),
				'default' => true,
			],
		]);
	}

}
