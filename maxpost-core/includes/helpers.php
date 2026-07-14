<?php
/**
 * Public helper functions for themes and integrations.
 *
 * @package MaxPostCore
 */

defined( 'ABSPATH' ) || exit;

function maxpost_get_software( int $post_id ): ?array {
	$post = get_post( $post_id );
	if ( ! $post || 'software' !== $post->post_type || 'publish' !== $post->post_status ) {
		return null;
	}

	$icon_id       = absint( get_post_meta( $post_id, '_maxpost_icon_id', true ) );
	$card_image_id = absint( get_post_meta( $post_id, '_maxpost_card_image_id', true ) );
	$screenshots   = array_values( array_filter( array_map( 'absint', (array) get_post_meta( $post_id, '_maxpost_screenshot_ids', true ) ) ) );
	$terms         = get_the_terms( $post_id, 'software_category' );

	return [
		'id'                    => $post_id,
		'slug'                  => $post->post_name,
		'name'                  => get_the_title( $post_id ),
		'description'           => get_the_excerpt( $post_id ),
		'content'               => apply_filters( 'the_content', $post->post_content ),
		'version'               => (string) get_post_meta( $post_id, '_maxpost_version', true ),
		'file_size'             => (string) get_post_meta( $post_id, '_maxpost_file_size', true ),
		'download_url'          => esc_url_raw( (string) get_post_meta( $post_id, '_maxpost_download_url', true ) ),
		'portable_download_url' => esc_url_raw( (string) get_post_meta( $post_id, '_maxpost_portable_download_url', true ) ),
		'icon_id'               => $icon_id,
		'icon_url'              => $icon_id ? wp_get_attachment_image_url( $icon_id, 'thumbnail' ) : false,
		'card_image_id'         => $card_image_id,
		'card_image_url'        => $card_image_id ? wp_get_attachment_image_url( $card_image_id, 'large' ) : false,
		'screenshot_ids'        => $screenshots,
		'screenshot_urls'       => array_values( array_filter( array_map( static fn( int $id ) => wp_get_attachment_image_url( $id, 'large' ), $screenshots ) ) ),
		'windows'               => (array) get_post_meta( $post_id, '_maxpost_supported_windows', true ),
		'languages'             => (array) get_post_meta( $post_id, '_maxpost_supported_languages', true ),
		'featured'              => (bool) get_post_meta( $post_id, '_maxpost_featured', true ),
		'categories'            => is_array( $terms ) ? array_map( static fn( WP_Term $term ) => [ 'name' => $term->name, 'slug' => $term->slug ], $terms ) : [],
		'page_url'              => get_permalink( $post_id ),
		'updated_at'            => get_post_modified_time( DATE_ATOM, true, $post_id ),
	];
}

function maxpost_get_featured_software( int $limit = 3 ): array {
	$query = new WP_Query(
		[
			'post_type'      => 'software',
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, $limit ),
			'meta_key'       => '_maxpost_featured',
			'meta_value'     => '1',
			'no_found_rows'  => true,
		]
	);

	return array_values( array_filter( array_map( static fn( WP_Post $post ) => maxpost_get_software( $post->ID ), $query->posts ) ) );
}
