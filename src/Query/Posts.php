<?php

namespace Osprey\Query;

use Osprey\Entity\Post;

/**
 * Class Posts
 *
 * @package Osprey\Query
 */
class Posts extends AbstractQuery {

	/**
	 * Adjust the arguments so that it this query type accepts the same common
	 * arguments as the other query types.
	 *
	 * @param $arguments array
	 *
	 * @return array
	 */
	public function alterArguments( $arguments ) {
		$map = array(
			'number' => 'posts_per_page',
			'include' => 'posts__in',
			'exclude' => 'posts__not_in',
			'search' => 's',
		);

		foreach ( $map as $from => $to ) {
			if ( isset( $arguments[ $from ] ) && empty( $arguments[ $to ]) ) {
				$arguments[ $to ] = $arguments[ $from ];
				unset( $arguments[ $from ] );
			}
		}

		return $arguments;
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute( $callback = null ) {
		$arguments = $this->alterArguments( $this->arguments );
		$this->query = new \WP_Query( $arguments );

		if ( $this->query->have_posts() ) {
			while ( $this->query->have_posts() ) {
				$this->query->the_post();
				$item = new Post( get_post() );
				$this->results[ $item->id() ] = $item;

				if ( is_callable( $callback ) ) {
					call_user_func( $callback, $item );
				}
			}
			wp_reset_query();
		}

		return $this->results;
	}

	/**
	 * @link https://www.billerickson.net/code/wp_query-arguments/
	 *
	 * @return array
	 */
	public function schema() {
		return array_replace(parent::schema(), [
			'author_name' => [
				'type' => 'string',
				'title' => __('Author nice name'),
			],
			'author__in' => [
				'type' => 'list',
				'title' => __('Author in'),
			],
			'author__not_in' => [
				'type' => 'list',
				'title' => __('Author not in'),
			],
			'category_name' => [
				'type' => 'string',
				'title' => __('Category name'),
			],
			'category__and' => [
				'type' => 'list',
				'title' => __('Category IDs required'),
			],
			'category__in' => [
				'type' => 'list',
				'title' => __('Category in'),
			],
			'category__not_in' => [
				'type' => 'list',
				'title' => __('Category not in'),
			],
			'tag__and' => [
				'type' => 'list',
				'title' => __('Tag IDs required'),
			],
			'tag__in' => [
				'type' => 'list',
				'title' => __('Tag in'),
			],
			'tag__not_in' => [
				'type' => 'list',
				'title' => __('Tag not in'),
			],
			'tag_slug__in' => [
				'type' => 'list',
				'title' => __('Tag slugs in'),
			],
			'tag_slug__and' => [
				'type' => 'list',
				'title' => __('Tag slugs required'),
			],
			'tax_query' => [
				'type' => 'tax_query',
				'title' => __('Taxonomy Query'),
			],
			'date_query' => [
				'type' => 'date_query',
				'title' => __('Date Query'),
			],
			'name' => [
				'type' => 'string',
				'title' => __('Post slug'),
			],
			'pagename' => [
				'type' => 'string',
				'title' => __('Page slug'),
			],
			'post_parent__in' => [
				'type' => 'list',
				'title' => __('Post parent in'),
			],
			'post_parent__not_in' => [
				'type' => 'list',
				'title' => __('Post parent not in'),
			],
			'post__in' => [
				'type' => 'list',
				'title' => __('Post in'),
			],
			'post__not_in' => [
				'type' => 'list',
				'title' => __('Post not in'),
			],
			'has_password' => [
				'type' => 'boolean',
				'title' => __('Has password'),
			],
			'post_password' => [
				'type' => 'string',
				'title' => __('Post password'),
			],
			'post_type' => [
				'type' => 'list',
				'title' => __('Post type'),
				'allowed_callback' => [],
			],
			'post_status' => [
				'type' => 'list',
				'title' => __('Post status'),
				'allowed' => [
					'publish' => __('Publish'),
					'pending' => __('Pending'),
					'draft' => __('Draft'),
					'auto-draft' => __('Auto-draft'),
					'future' => __('Scheduled'),
					'private' => __('Private'),
					'inherit' => __('Inherit'),
					'trash' => __('Trash'),
				]
			],
			'ignore_sticky_posts' => [
				'type' => 'boolean',
				'title' => __('Ignore sticky posts'),
				'default' => false,
			],
			'orderby' => [
				'type' => 'string',
				'title' => __('Order by'),
				'options' => [
					'none' => __('No order '),
					'ID' => __('Post ID'),
					'author' => __('Author.'),
					'title' => __('Title'),
					'name' => __('Slug'),
					'date' => __('Date'),
					'modified' => __('Last modified date'),
					'parent' => __('Parent ID'),
					'rand' => __('Random'),
					'comment_count' => __('Number of comments'),
					'menu_order' => __('Page order'),
					'meta_value' => __('meta_key value'),
					'meta_value_num' => __('Numeric meta value'),
					'title menu_order' => __('Both menu_order AND title'),
					'post__in' => __('Order given in the post__in'),
				],
			],
			'perm' => [
				'type' => 'string',
				'title' => __('Permission'),
				'options' => [
					'readable' => __('Readable'),
					'editable' => __('Editable'),
				],
			],
			'cache_results' => [
				'type' => 'boolean',
				'title' => __('Cache results'),
				'default' => true,
			],
			'update_post_term_cache' => [
				'type' => 'boolean',
				'title' => __('Update post term cache'),
				'default' => true,
			],
			'update_post_meta_cache' => [
				'type' => 'boolean',
				'title' => __('Update post meta cache'),
				'default' => true,
			],
			'no_found_rows' => [
				'type' => 'boolean',
				'title' => __('Do not count rows'),
			],
			'exact' => [
				'type' => 'boolean',
				'title' => __('Match whole exact search'),
			],
			'sentence' => [
				'type' => 'boolean',
				'title' => __('Match phrase search'),
			],
		]);
	}

}
