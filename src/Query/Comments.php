<?php

namespace Osprey\Query;

use Osprey\Entity\AbstractEntity;

/**
 * Class Comments
 *
 * @package Osprey\Query
 */
class Comments extends AbstractQuery {

	/**
	 * {@inheritdoc}
	 */
	public function execute( $entity_class = null, $callback = null ) {
		$this->query = new \WP_Comment_Query( $this->arguments );

		foreach ( $this->query->get_comments() as $comment ) {
			/** @var AbstractEntity $item */
			$item = is_a($entity_class, '\Osprey\Entity\AbstractEntity') ? new $entity_class( $comment ) : null;
			$this->results[ $item->id() ] = $item;

			if ( is_callable( $callback ) ) {
				call_user_func( $callback, $item, $comment );
			}
		}

		return $this->results;
	}

	public function schema() {
		return array_replace(parent::schema(), [
			//'karma' => '',
			//'cache_domain' => 'core',
			'author_email' => [
				'type' => 'string',
				'title' => __('Author Email'),
			],
			'author_url' => [
				'type' => 'string',
				'title' => __('Author URL'),
			],
			'author__in' => [
				'type' => 'list',
				'title' => __('Author ID in list'),
			],
			'author__not_in' => [
				'type' => 'list',
				'title' => __('Author ID not in list'),
			],
			'include_unapproved' => [
				'type' => 'boolean',
				'title' => __('Included unapproved'),
			],
			'ID' => [
				'type' => 'number',
				'title' => __('ID'),
			],
			'comment__in' => [
				'type' => 'list',
				'title' => __('Comment ID in list'),
			],
			'comment__not_in' => [
				'type' => 'list',
				'title' => __('Comment ID not in list'),
			],
			'date_query' => [
				'type' => 'date_query',
				'title' => __('Date Query'),
			],
			'no_found_rows' => [
				'type' => 'boolean',
				'title' => __('Number of rows found'),
			],
			'orderby' => [
				'type' => 'string',
				'title' => __('Order by'),
				'default' => 'comment_date_gmt',
				'options' => [
					'comment_ID' => __('Comment ID'),
					'comment_post_ID' => __('Post ID'),
					'comment_author' => __('Author'),
					'comment_author_email' => __('Author email'),
					'comment_author_url' => __('Author URL'),
					'comment_author_IP' => __('Author IP'),
					'comment_date' => __('Date'),
					'comment_date_gmt' => __('Date GMT'),
					'comment_content' => __('Content'),
					'comment_karma' => __('Karma'),
					'comment_approved' => __('Approved'),
					'comment_agent' => __('Agent'),
					'comment_type' => __('Type'),
					'comment_parent' => __('Parent comment ID'),
					'user_id' => __('User ID'),
				],
			],
			'parent' => [
				'type' => 'number',
				'title' => __('Parent ID'),
			],
			'parent__in' => [
				'type' => 'list',
				'title' => __('Parent ID in list'),
			],
			'parent__not_in' => [
				'type' => 'list',
				'title' => __('Parent ID not in list'),
			],
			'post_author' => [
				'type' => 'number',
				'title' => __('Post Author ID'),
			],
			'post_author__in' => [
				'type' => 'list',
				'title' => __('Post Author ID in list'),
			],
			'post_author__not_in' => [
				'type' => 'list',
				'title' => __('Post Author ID not in list'),
			],
			'post_id' => [
				'type' => 'number',
				'title' => __('Post ID'),
				'default' => 0,
			],
			'post__in' => [
				'type' => 'list',
				'title' => __('Post ID in list'),
			],
			'post__not_in' => [
				'type' => 'list',
				'title' => __('Post ID not in list'),
			],
			'post_name' => [
				'type' => 'string',
				'title' => __('Post Slug'),
			],
			'post_parent' => [
				'type' => 'number',
				'title' => __('Post Parent ID'),
			],
			'post_status' => [
				'type' => 'list',
				'title' => __('Post Status'),
			],
			'post_type' => [
				'type' => 'list',
				'title' => __('Post Type'),
			],
			'status' => [
				'type' => 'string',
				'title' => __('Comment Status'),
				'default' => 'all',
				'allowed' => [
					'all' => __('All'),
					'hold' => __('Hold'),
					'approve' => __('Approve'),
					'trash' => __('Trash'),
				],
			],
			'type' => [
				'type' => 'string',
				'title' => __('Comment Type'),
			],
			'type__in' => [
				'type' => 'list',
				'title' => __('Comment Type in list'),
				'allowed' => [
					'comment' => __('Comment'),
					'trackback' => __('Trackback'),
					'pingback' => __('Pingback'),
				],
			],
			'type__not_in' => [
				'type' => 'list',
				'title' => __('Comment Type not in list'),
				'allowed' => [
					'comment' => __('Comment'),
					'trackback' => __('Trackback'),
					'pingback' => __('Pingback'),
				],
			],
			'user_id' => [
				'type' => 'number',
				'title' => __('Comment User ID'),
			],
			'search' => [
				'type' => 'string',
				'title' => __('Search'),
			],
			'hierarchical' => [
				'type' => 'boolean',
				'title' => __('Hierarchical'),
			],
			'count' => [
				'type' => 'boolean',
				'title' => __('Comment Count only'),
			],
			'update_comment_meta_cache' => [
				'type' => 'boolean',
				'title' => __('Update Comment Meta cache'),
				'default' => true,
			],
			'update_comment_post_cache' => [
				'type' => 'boolean',
				'title' => __('Update Comment Post cache'),
			],
		]);
	}
}
