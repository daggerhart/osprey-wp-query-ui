<?php
/*
 * Plugin Name: Osprey
 * Description: Query UI
 * Author: Jonathan Daggerhart
 * Version: 0.1.0
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

	$output = osprey_render_query( $attributes['query'] );

	return $output;
}

function osprey_render_query( $query_id ){
	$output = '';
	$args = get_post_meta( $query_id, 'osprey_query_args_string', true );
	$template_id = get_post_meta( $query_id, 'osprey_query_template_id', true );
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
	$token_map = osprey_get_token_map();

	ob_start();
	if ( isset( $token_map[ $token ] ) ){
		$callback = $token_map[ $token ];
		call_user_func( $callback );
	}
	$output = ob_get_clean();

	return $output;
}

function osprey_get_token_map(){
	$token_map = array(
		'title' => 'the_title',
		'content' => 'the_content',
		'featured_image_url' => 'the_post_thumbnail_url'
	);

	return $token_map;
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

add_action( 'add_meta_boxes_osprey_template', 'osprey_template_register_meta_boxes' );

function osprey_template_register_meta_boxes( $post ){
	add_meta_box(
		'osprey-template-row',
		__('Template Row'),
		'osprey_template_render_template_row_meta_box',
		$post->post_type,
		'normal',
		'high'
	);
	add_meta_box(
		'osprey-template-tokens',
		__('Template Tokens'),
		'osprey_template_render_template_tokens_meta_box',
		$post->post_type,
		'normal',
		'high'
	);
}

function osprey_template_render_template_row_meta_box( $post ){
	$template_row = get_post_meta( $post->ID, 'osprey_template_row', true );
	?><textarea name="osprey_template_row"
	            style="width:100%; height: 400px;"><?php print esc_textarea($template_row);?></textarea>
	<?php
}

add_action( 'save_post', 'osprey_template_save_meta_boxes' );

function osprey_template_save_meta_boxes( $post_id ){
	if ( isset( $_POST['osprey_template_row'] ) ){
		$value = wp_kses_post( $_POST['osprey_template_row'] );
		update_post_meta( $post_id, 'osprey_template_row', $value );
	}
}

function osprey_template_render_template_tokens_meta_box( $post ){
	$token_map = osprey_get_token_map();
	$tokens = array_keys( $token_map );
	?>
	<ul>
		<?php foreach( $tokens as $token ) { ?>
			<li>{{ <?php print $token; ?> }}</li>
		<?php } ?>
	</ul>
	<?php
}

add_action( 'add_meta_boxes_osprey_query', 'osprey_query_register_meta_boxes' );

function osprey_query_register_meta_boxes( $post ){
	add_meta_box(
		'osprey-query-settings',
		__('Query Settings'),
		'osprey_query_render_settings_meta_box',
		$post->post_type,
		'normal',
		'high'
	);
	add_meta_box(
		'osprey-query-preview',
		__('Query Preview'),
		'osprey_query_render_preview_meta_box',
		$post->post_type,
		'normal',
		'high'
	);
}

function osprey_query_render_settings_meta_box( $post ){
	$query_args_string = get_post_meta( $post->ID, 'osprey_query_args_string', true );
	$template_id = get_post_meta( $post->ID, 'osprey_query_template_id', true );

	?>
	<label><?php _e('Query Args String'); ?></label>
	<input type="text"
           name="osprey_query_args_string"
           style="width: 100%;"
           value="<?php print esc_attr( $query_args_string ); ?>">
	<?php

	$all_templates = get_posts(array(
		'post_type' => 'osprey_template',
		'posts_per_page' => -1
	));
	?>
	<label><?php _e('Template'); ?></label>
	<select name="osprey_query_template_id">
		<option>-- SELECT --</option>
		<?php foreach( $all_templates as $template ) { ?>
			<option value="<?php print esc_attr($template->ID) ?>"
				<?php selected( $template_id, $template->ID ); ?>>
				<?php print esc_attr( $template->post_title); ?>
			</option>
		<?php } ?>
	</select>
	<?php
}

add_action( 'save_post', 'osprey_query_save_meta_boxes' );

function osprey_query_save_meta_boxes( $post_id ){
	if ( isset( $_POST['osprey_query_args_string'] ) ){
		$value = sanitize_text_field( $_POST['osprey_query_args_string'] );
		update_post_meta( $post_id, 'osprey_query_args_string', $value );
	}

	if ( isset( $_POST['osprey_query_template_id'] ) ){
		$value = intval( $_POST['osprey_query_template_id'] );
		update_post_meta( $post_id, 'osprey_query_template_id', $value );
	}
}

function osprey_query_render_preview_meta_box( $post ){
	print osprey_render_query( $post->ID );
}









