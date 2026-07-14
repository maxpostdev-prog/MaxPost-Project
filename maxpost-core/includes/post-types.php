<?php
/**
 * Post types and taxonomies.
 *
 * @package MaxPostCore
 */

defined( 'ABSPATH' ) || exit;

function maxpost_core_register_post_types(): void {
	register_post_type(
		'software',
		[
			'labels' => [
				'name'          => __( 'Software', 'maxpost-core' ),
				'singular_name' => __( 'Software', 'maxpost-core' ),
				'add_new_item'  => __( 'Add software', 'maxpost-core' ),
				'edit_item'     => __( 'Edit software', 'maxpost-core' ),
			],
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-desktop',
			'has_archive'  => true,
			'rewrite'      => [ 'slug' => 'software' ],
			'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ],
		]
	);

	register_taxonomy(
		'software_category',
		[ 'software' ],
		[
			'labels' => [
				'name'          => __( 'Software categories', 'maxpost-core' ),
				'singular_name' => __( 'Software category', 'maxpost-core' ),
			],
			'public'       => true,
			'show_in_rest' => true,
			'hierarchical' => true,
			'rewrite'      => [ 'slug' => 'software-category' ],
		]
	);
}
add_action( 'init', 'maxpost_core_register_post_types' );
