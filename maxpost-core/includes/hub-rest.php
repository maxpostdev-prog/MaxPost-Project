<?php
/**
 * MaxPost Hub REST API.
 *
 * @package MaxPostCore
 */

defined( 'ABSPATH' ) || exit;

function maxpost_hub_register_rest_routes(): void {
	register_rest_route( 'maxpost/v1', '/hub', [
		'methods' => WP_REST_Server::READABLE,
		'permission_callback' => '__return_true',
		'callback' => 'maxpost_hub_rest_config',
		'args' => [
			'app' => [ 'required' => true, 'sanitize_callback' => 'sanitize_key' ],
			'locale' => [ 'default' => 'en-US', 'sanitize_callback' => 'sanitize_text_field' ],
			'channel' => [ 'default' => 'stable', 'sanitize_callback' => 'sanitize_key' ],
			'version' => [ 'default' => '0.0.0', 'sanitize_callback' => 'sanitize_text_field' ],
		],
	] );

	register_rest_route( 'maxpost/v1', '/hub/event', [
		'methods' => WP_REST_Server::CREATABLE,
		'permission_callback' => '__return_true',
		'callback' => 'maxpost_hub_rest_event',
		'args' => [
			'app' => [ 'required' => true, 'sanitize_callback' => 'sanitize_key' ],
			'event' => [ 'required' => true, 'sanitize_callback' => 'sanitize_key' ],
			'item' => [ 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ],
		],
	] );
}
add_action( 'rest_api_init', 'maxpost_hub_register_rest_routes' );

function maxpost_hub_serialize_card( WP_Post $post ): array {
	$image_id = get_post_thumbnail_id( $post );
	return [
		'id' => $post->ID,
		'type' => (string) get_post_meta( $post->ID, '_maxpost_hub_type', true ) ?: 'tool',
		'title' => get_the_title( $post ),
		'subtitle' => (string) get_post_meta( $post->ID, '_maxpost_hub_subtitle', true ),
		'body' => wp_strip_all_tags( $post->post_content ),
		'image' => $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '',
		'button' => (string) get_post_meta( $post->ID, '_maxpost_hub_button_label', true ),
		'url' => (string) get_post_meta( $post->ID, '_maxpost_hub_url', true ),
		'priority' => (int) get_post_meta( $post->ID, '_maxpost_hub_priority', true ),
	];
}

function maxpost_hub_get_update( string $app, string $channel, string $current_version ): ?array {
	$posts = get_posts( [
		'post_type' => 'maxpost_update',
		'post_status' => 'publish',
		'posts_per_page' => 20,
		'orderby' => 'date',
		'order' => 'DESC',
	] );
	foreach ( $posts as $post ) {
		if ( $app !== get_post_meta( $post->ID, '_maxpost_update_app', true ) ) {
			continue;
		}
		$post_channel = (string) get_post_meta( $post->ID, '_maxpost_update_channel', true ) ?: 'stable';
		if ( $channel !== $post_channel ) {
			continue;
		}
		$version = (string) get_post_meta( $post->ID, '_maxpost_update_version', true );
		return [
			'available' => version_compare( $version, $current_version, '>' ),
			'version' => $version,
			'title' => get_the_title( $post ),
			'notes' => wp_strip_all_tags( $post->post_content ),
			'download_url' => (string) get_post_meta( $post->ID, '_maxpost_update_url', true ),
			'sha256' => (string) get_post_meta( $post->ID, '_maxpost_update_sha256', true ),
			'required' => (bool) get_post_meta( $post->ID, '_maxpost_update_required', true ),
		];
	}
	return null;
}

function maxpost_hub_rest_config( WP_REST_Request $request ): WP_REST_Response {
	$app = (string) $request['app'];
	$locale = (string) $request['locale'];
	$channel = (string) $request['channel'];
	$version = (string) $request['version'];
	$posts = get_posts( [
		'post_type' => 'maxpost_hub_card',
		'post_status' => 'publish',
		'posts_per_page' => 30,
		'orderby' => [ 'menu_order' => 'ASC', 'date' => 'DESC' ],
	] );
	$cards = [];
	foreach ( $posts as $post ) {
		if ( maxpost_hub_card_is_active( $post, $app, $locale ) ) {
			$cards[] = maxpost_hub_serialize_card( $post );
		}
	}
	usort( $cards, static fn( array $a, array $b ): int => $b['priority'] <=> $a['priority'] );
	$response = rest_ensure_response( [
		'api_version' => 'v1',
		'generated_at' => gmdate( DATE_ATOM ),
		'cache_ttl' => 21600,
		'app' => $app,
		'cards' => $cards,
		'whats_new' => array_values( array_filter( $cards, static fn( array $card ): bool => 'news' === $card['type'] ) ),
		'notifications' => array_values( array_filter( $cards, static fn( array $card ): bool => 'notification' === $card['type'] ) ),
		'feature_flags' => maxpost_hub_get_flags(),
		'messages' => maxpost_hub_get_localized_messages( $locale ),
		'update' => maxpost_hub_get_update( $app, $channel, $version ),
	] );
	$response->header( 'Cache-Control', 'public, max-age=300, s-maxage=21600' );
	return $response;
}

function maxpost_hub_rest_event( WP_REST_Request $request ): WP_REST_Response {
	$app = (string) $request['app'];
	$event = (string) $request['event'];
	$allowed = [ 'app_open', 'download_click', 'card_click', 'update_seen', 'update_installed' ];
	if ( ! in_array( $event, $allowed, true ) ) {
		return new WP_REST_Response( [ 'error' => 'unsupported_event' ], 400 );
	}
	$key = 'maxpost_hub_stats_' . gmdate( 'Ym' );
	$stats = get_option( $key, [] );
	$stats = is_array( $stats ) ? $stats : [];
	$bucket = $app . ':' . $event;
	$stats[ $bucket ] = (int) ( $stats[ $bucket ] ?? 0 ) + 1;
	update_option( $key, $stats, false );
	return rest_ensure_response( [ 'accepted' => true ] );
}
