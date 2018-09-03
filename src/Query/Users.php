<?php

namespace Osprey\Query;

use Osprey\Entity\User;

/**
 * Class Users
 *
 * @package Osprey\Query
 */
class Users extends AbstractQuery {

	/**
	 * {@inheritdoc}
	 */
	public function execute( $callback = NULL ) {
		$this->query = new \WP_User_Query( $this->arguments );

		foreach ( $this->query->get_results() as $user ) {
			$item = new User( $user );
			$this->results[ $item->id() ] = $item;

			if ( is_callable( $callback ) ) {
				call_user_func( $callback, $user );
			}
		}

		return $this->results;
	}

	public function schema() {
		return array_replace(parent::schema(), [
			'role' => [
				'type' => 'list',
				'title' => __('Roles users have all'),
			],
			'role__in' => [
				'type' => 'list',
				'title' => __('Roles users have at least one'),
				'default' => [],
			],
			'role__not_in' => [
				'type' => 'list',
				'title' => __('Roles users do not have'),
			],
			'include' => [
				'type' => 'list',
				'title' => __('Include users by ID'),
			],
			'exclude' => [
				'type' => 'list',
				'title' => __('Exclude users by ID'),
			],
			'blog_id' => [
				'type' => 'number',
				'title' => __('Blog ID'),
			],
			'search' => [
				'type' => 'string',
				'title' => __('Search users by table columns'),
				'requires' => [
					'search_columns',
				],
			],
			'date_query' => [
				'type' => 'date_query',
				'title' => __('Date Query'),
			],
			'search_columns' => [
				'type' => 'list',
				'title' => __('Search columns'),
				'mutiple' => true,
				'options' => [
					'ID' => __('User ID'),
					'user_login' => __('User login'),
					'user_nicename' => __('User nice name'),
					'user_email' => __('User email'),
					'user_url' => __('User url'),
				],
				'default' => [
					'ID',
					'user_login',
					'user_nicename',
					'user_email',
					'user_url',
				],
			],
			'orderby' => [
				'type' => 'string',
				'title' => __('Order by'),
				'options' => [
					'ID' => __('User id'),
					'name' => __('User name'),
					'login' => __('User login'),
					'display_name' => __('User display name'),
					'nicename' => __('User nicename'),
					'email' => __('User email'),
					'url' => __('User url'),
					'registered' => __('User registered date'),
					'post_count' => __('User post count'),
					'include' => __('Included list of user_ids (requires the include parameter)'),
					//'meta_value' - Note that a 'meta_key=keyname' must also be present in the query
					//'meta_value_num' - Note that a 'meta_key=keyname' must also be present in the query
				],
				'default' => 'login',
			],
			'has_published_posts' => [
				'type' => 'boolean',
				'title' => __('Has published posts'),
			],
			'count_total' => [
				'type' => 'boolean',
				'title' => __('Count total'),
			],
		]);
	}

}
