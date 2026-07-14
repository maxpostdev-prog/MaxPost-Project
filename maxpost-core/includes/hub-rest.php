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

function maxpost_hub_get_cards( string $app, string $locale ): array {
	$cards = [];
	foreach ( get_posts( [ 'post_type' => 'maxpost_hub_card', 'post_status' => 'publish', 'posts_per_page' => 50, 'orderby' => [ 'menu_order' => 'ASC', 'date' => 'DESC' ] ] ) as $post ) {
		if ( maxpost_hub_card_is_active( $post, $app, $locale ) ) {
			$cards[] = maxpost_hub_serialize_card( $post );
		}
	}
	usort( $cards, static fn( array $a, array $b ): int => $b['priority'] <=> $a['priority'] );
	return $cards;
}

function maxpost_hub_get_notices( string $app, string $locale ): array {
	$items = [];
	foreach ( get_posts( [ 'post_type' => 'maxpost_notice', 'post_status' => 'publish', 'posts_per_page' => 30, 'orderby' => 'date', 'order' => 'DESC' ] ) as $post ) {
		if ( ! get_post_meta( $post->ID, '_maxpost_notice_enabled', true ) ) {
			continue;
		}
		$targets = (array) get_post_meta( $post->ID, '_maxpost_notice_app_targets', true );
		$item_locale = (string) get_post_meta( $post->ID, '_maxpost_notice_locale', true );
		if ( ! maxpost_hub_targets_match( $targets, $app ) || ( $item_locale && $item_locale !== $locale ) ) {
			continue;
		}
		if ( ! maxpost_hub_window_is_active( (string) get_post_meta( $post->ID, '_maxpost_notice_starts_at', true ), (string) get_post_meta( $post->ID, '_maxpost_notice_ends_at', true ) ) ) {
			continue;
		}
		$items[] = [
			'id' => $post->ID,
			'level' => (string) get_post_meta( $post->ID, '_maxpost_notice_level', true ) ?: 'info',
			'title' => get_the_title( $post ),
			'body' => wp_strip_all_tags( $post->post_content ),
		];
	}
	return $items;
}

function maxpost_hub_get_flags( string $app = '' ): array {
	$flags = [];
	foreach ( get_posts( [ 'post_type' => 'maxpost_flag', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC' ] ) as $post ) {
		if ( ! get_post_meta( $post->ID, '_maxpost_flag_enabled', true ) ) {
			continue;
		}
		$targets = (array) get_post_meta( $post->ID, '_maxpost_flag_app_targets', true );
		if ( ! maxpost_hub_targets_match( $targets, $app ) ) {
			continue;
		}
		$key = (string) get_post_meta( $post->ID, '_maxpost_flag_key', true );
		if ( ! $key ) {
			continue;
		}
		$value = (string) get_post_meta( $post->ID, '_maxpost_flag_value', true );
		if ( 'true' === strtolower( $value ) || 'false' === strtolower( $value ) ) {
			$flags[ $key ] = 'true' === strtolower( $value );
		} elseif ( is_numeric( $value ) ) {
			$flags[ $key ] = 0 + $value;
		} else {
			$flags[ $key ] = $value;
		}
	}
	return $flags;
}

function maxpost_hub_get_localized_messages( string $locale ): array {
	$messages = [];
	$posts = get_posts( [ 'post_type' => 'maxpost_message', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'ASC' ] );
	foreach ( [ '', 'default', $locale ] as $wanted_locale ) {
		foreach ( $posts as $post ) {
			if ( ! get_post_meta( $post->ID, '_maxpost_message_enabled', true ) ) {
				continue;
			}
			$item_locale = (string) get_post_meta( $post->ID, '_maxpost_message_locale', true );
			if ( $item_locale !== $wanted_locale ) {
				continue;
			}
			$key = (string) get_post_meta( $post->ID, '_maxpost_message_key', true );
			if ( $key ) {
				$messages[ $key ] = wp_strip_all_tags( $post->post_content );
			}
		}
	}
	return $messages;
}

function maxpost_hub_get_update( string $app, string $channel, string $current_version ): ?array {
	foreach ( get_posts( [ 'post_type' => 'maxpost_update', 'post_status' => 'publish', 'posts_per_page' => 50, 'orderby' => 'date', 'order' => 'DESC' ] ) as $post ) {
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
	$cards = maxpost_hub_get_cards( $app, $locale );
	$response = rest_ensure_response( [
		'api_version' => 'v1',
		'generated_at' => gmdate( DATE_ATOM ),
		'cache_ttl' => 21600,
		'app' => $app,
		'cards' => $cards,
		'whats_new' => array_values( array_filter( $cards, static fn( array $card ): bool => 'news' === $card['type'] ) ),
		'notifications' => maxpost_hub_get_notices( $app, $locale ),
		'feature_flags' => maxpost_hub_get_flags( $app ),
		'messages' => maxpost_hub_get_localized_messages( $locale ),
		'update' => maxpost_hub_get_update( $app, (string) $request['channel'], (string) $request['version'] ),
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
