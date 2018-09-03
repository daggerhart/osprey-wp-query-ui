<?php

namespace Osprey\PostType;

use Osprey\Services;

class Query {

	/**
	 * Post type slug.
	 */
	const TYPE = 'osprey_query';

	/**
	 * Register this post type with WP.
	 */
	public static function register() {
		$labels = array(
			'name'               => _x( 'Queries', 'post type general name' ),
			'singular_name'      => _x( 'Query', 'post type singular name' ),
			'menu_name'          => _x( 'Queries', 'admin menu' ),
			'name_admin_bar'     => _x( 'Query', 'add new on admin bar' ),
			'add_new'            => _x( 'Add New', 'Query' ),
			'add_new_item'       => __( 'Add New Query' ),
			'new_item'           => __( 'New Query' ),
			'edit_item'          => __( 'Edit Query' ),
			'view_item'          => __( 'View Query' ),
			'all_items'          => __( 'All Queries' ),
			'search_items'       => __( 'Search Queries' ),
			'parent_item_colon'  => __( 'Parent Queries:' ),
			'not_found'          => __( 'No Queries found.' ),
			'not_found_in_trash' => __( 'No Queries found in Trash.' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'osprey_query' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array(
				'title',
				'custom-fields',
			),
		);

		register_post_type( self::TYPE, $args );
		add_action('add_meta_boxes_osprey_query', ['\Osprey\PostType\Query', 'metaBoxes']);
		add_action('save_post', ['\Osprey\PostType\Query', 'saveMetaBoxes']);
	}

	/**
	 * Render meta boxes.
	 *
	 * @param $post
	 */
	static public function metaBoxes($post) {
		$query_type = get_post_meta( $post->ID, '_osprey_query_type', TRUE );
		$registry = Services::types();
		$type = $registry->get( $query_type );

		$wizard = new MetaBoxWizard();

		if (empty($type)) {
			add_meta_box(
				$wizard->id(),
				$wizard->title(),
				[ $wizard, 'embed' ],
				self::TYPE,
				'normal',
				'default'
			);
			return;
		}

		$preview    = new MetaBoxPreview();
		$query_form = new MetaBoxQueryForm();

		add_meta_box(
			$query_form->id(),
			$query_form->title(),
			[ $query_form, 'embed' ],
			self::TYPE,
			'normal',
			'default'
		);

		add_meta_box(
			$preview->id(),
			$preview->title(),
			[ $preview, 'embed' ],
			self::TYPE,
			'normal',
			'default'
		);
	}

	/**
	 * Save meta boxes.
	 *
	 * @param $post_id
	 */
	static public function saveMetaBoxes( $post_id ) {
		$wizard = new MetaBoxWizard();
		$wizard->save($post_id);

		$query_form = new MetaBoxQueryForm();
		$query_form->save($post_id);
	}
}
