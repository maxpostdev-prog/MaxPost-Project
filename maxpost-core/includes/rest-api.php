<?php
/**
 * Public REST API.
 *
 * @package MaxPostCore
 */

defined( 'ABSPATH' ) || exit;

function maxpost_core_register_rest_routes(): void {
	register_rest_route(
		'maxpost/v1',
		'/software',
		[
			'methods'             => WP_REST_Server::READABLE,
			'permission_callback' => '__return_true',
			'callback'            => 'maxpost_core_rest_software',
			'args'                => [
				'per_page' => [
					'default'           => 12,
					'sanitize_callback' => 'absint',
					'validate_callback' => static fn( mixed $value ): bool => (int) $value >= 1 && (int) $value <= 100,
				],
			],
		]
	);

	register_rest_route(
		'maxpost/v1',
		'/software/(?P<slug>[a-z0-9-]+)',
		[
			'methods'             => WP_REST_Server::READABLE,
			'permission_callback' => '__return_true',
			'callback'            => 'maxpost_core_rest_software_item',
		]
	);
}
add_action( 'rest_api_init', 'maxpost_core_register_rest_routes' );

function maxpost_core_rest_software( WP_REST_Request $request ): WP_REST_Response {
	$query = new WP_Query(
		[
			'post_type'      => 'software',
			'post_status'    => 'publish',
			'posts_per_page' => (int) $request->get_param( 'per_page' ),
			'orderby'        => 'modified',
			'order'          => 'DESC',
		]
	);

	$items = array_values( array_filter( array_map( static fn( WP_Post $post ) => maxpost_get_software( $post->ID ), $query->posts ) ) );

	return rest_ensure_response(
		[
			'api_version' => 'v1',
			'count'       => count( $items ),
			'items'       => $items,
		]
	);
}

function maxpost_core_rest_software_item( WP_REST_Request $request ): WP_REST_Response|WP_Error {
	$post = get_page_by_path( sanitize_title( (string) $request['slug'] ), OBJECT, 'software' );
	if ( ! $post || 'publish' !== $post->post_status ) {
		return new WP_Error( 'maxpost_not_found', __( 'Software not found.', 'maxpost-core' ), [ 'status' => 404 ] );
	}

	return rest_ensure_response( maxpost_get_software( $post->ID ) );
}
