<?php

namespace Osprey\Query;

/**
 * Class AbstractNormalQuery
 *
 * @package Osprey\Query
 */
abstract class AbstractQuery implements QueryInterface {

	/**
	 * Arguments that augment the query results.
	 *
	 * @var array
	 */
	public $arguments = array();

	/**
	 * The last executed query object.
	 *
	 * @var object
	 */
	public $query;

	/**
	 * Results of the last query.
	 *
	 * @var array
	 */
	public $results = array();

	/**
	 * AbstractNormalQuery constructor.
	 *
	 * @param array $arguments
	 */
	function __construct( array $arguments ) {
		$this->arguments = $arguments;
	}

	/**
	 * @return array
	 */
	function schema() {
		return [
			'number' => [
				'type' => 'number',
				'title' => __('Number of results'),
				'default' => 10,
			],
			'offset' => [
				'type' => 'number',
				'title' => __('Offset'),
				'default' => 0,
			],
			'order' => [
				'type' => 'string',
				'title' => __('Order'),
				'default' => 'DESC',
				'allowed' => [
					'ASC' => __('ASC'),
					'DESC' => __('DESC'),
				],
			],
			'meta_query' => [
				'type' => 'meta_query',
				'title' => __('Meta Query'),
			],
			'meta_key' => [
				'type' => 'string',
				'title' => __('Meta key'),
			],
			'meta_value' => [
				'type' => 'string',
				'title' => __('Meta value'),
			],
			'meta_compare' => [
				'type' => 'string',
				'title' => __('Meta compare'),
				'options' => [
					'=' => __('= Equals'),
					'!=' => __('!= Not equals'),
					'>' => __('> Greater than'),
					'>=' => __('Greater than or equal to'),
					'<' => __('Less than'),
					'<=' => __('Less than or equal to'),
					'LIKE' => __('Like'),
					'NOT LIKE' => __('Not like'),
					'IN' => __('In'),
					'NOT IN' => __('Not in'),
					'BETWEEN' => __('Between'),
					'NOT BETWEEN' => __('Not between'),
					'EXISTS' => __('Exists'),
					'NOT EXISTS' => __('Not exists'),
					'REGEXP' => __('Regular expression'),
					'NOT REGEXP' => __('Not regular expression'),
					'RLIKE' => __('R Like'),
				],
			],
		];
	}

}
