<?php
/*
 * Plugin Name: Osprey
 * Description: Query UI
 * Author: Jonathan Daggerhart
 * Version: 0.0.1
 */

add_shortcode('osprey', 'osprey_render_query_shortcode');


function osprey_render_query_shortcode( $attributes, $content = '' ){
	$attributes = wp_parse_args( $attributes, array( 'query' => null ) );
	$output = '';

	if ( !$attributes['query'] ){
		return $output;
	}

	if ( get_post_type( $attributes['query'] ) != 'osprey_query' ) {
		return $output;
	}

	$args = get_post_meta( $attributes['query'], 'osprey_query_args_string', true );
	$template_id = get_post_meta( $attributes['query'], 'osprey_query_template_id', true );
	$template_row = get_post_meta( $template_id, 'osprey_template_row', true );

	$query = new WP_Query( $args );

	while( $query->have_posts() ){
		$query->the_post();

		$output .= preg_replace_callback('({{ (\w+) }})', 'osprey_replace_template_tokens', $template_row);
	}
	wp_reset_query();

	return $output;
}

function osprey_replace_template_tokens( $matches ){
	$output = '';
	$token = $matches[1];

	$token_map = array(
		'title' => 'the_title',
		'content' => 'the_content',
		'featured_image_url' => 'the_post_thumbnail_url'
	);

	ob_start();
	if ( isset( $token_map[ $token ] ) ){
		$callback = $token_map[ $token ];
		call_user_func( $callback );
	}
	$output = ob_get_clean();

	return $output;
}

add_action( 'init', 'osprey_register_post_types' );

function osprey_register_post_types(){
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
			'custom-fields'
		)
	);
	
	register_post_type( 'osprey_query', $args );

	$labels = array(
		'name'               => _x( 'Templates', 'post type general name' ),
		'singular_name'      => _x( 'Template', 'post type singular name' ),
		'menu_name'          => _x( 'Templates', 'admin menu' ),
		'name_admin_bar'     => _x( 'Template', 'add new on admin bar' ),
		'add_new'            => _x( 'Add New', 'Template' ),
		'add_new_item'       => __( 'Add New Template' ),
		'new_item'           => __( 'New Template' ),
		'edit_item'          => __( 'Edit Template' ),
		'view_item'          => __( 'View Template' ),
		'all_items'          => __( 'All Templates' ),
		'search_items'       => __( 'Search Templates' ),
		'parent_item_colon'  => __( 'Parent Templates:' ),
		'not_found'          => __( 'No Templates found.' ),
		'not_found_in_trash' => __( 'No Templates found in Trash.' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'edit.php?post_type=osprey_query',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'osprey_template' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array(
			'title',
			'custom-fields'
		)
	);

	register_post_type( 'osprey_template', $args );
}