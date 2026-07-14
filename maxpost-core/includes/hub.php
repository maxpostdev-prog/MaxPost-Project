<?php
/**
 * MaxPost Hub content model.
 *
 * @package MaxPostCore
 */

defined( 'ABSPATH' ) || exit;

function maxpost_hub_content_types(): array {
	return [
		'maxpost_hub_card' => [ 'Hub cards', 'Hub card', [ 'title', 'editor', 'thumbnail', 'page-attributes', 'revisions' ] ],
		'maxpost_update' => [ 'App updates', 'App update', [ 'title', 'editor', 'revisions' ] ],
		'maxpost_notice' => [ 'Notifications', 'Notification', [ 'title', 'editor', 'revisions' ] ],
		'maxpost_flag' => [ 'Feature flags', 'Feature flag', [ 'title', 'revisions' ] ],
		'maxpost_message' => [ 'Localized messages', 'Localized message', [ 'title', 'editor', 'revisions' ] ],
	];
}

function maxpost_hub_register_content_types(): void {
	foreach ( maxpost_hub_content_types() as $post_type => $definition ) {
		register_post_type(
			$post_type,
			[
				'labels' => [
					'name'          => __( $definition[0], 'maxpost-core' ),
					'singular_name' => __( $definition[1], 'maxpost-core' ),
					'add_new_item'  => sprintf( __( 'Add %s', 'maxpost-core' ), $definition[1] ),
					'edit_item'     => sprintf( __( 'Edit %s', 'maxpost-core' ), $definition[1] ),
				],
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => 'maxpost-hub',
				'show_in_rest'    => false,
				'supports'        => $definition[2],
				'capability_type' => 'post',
				'map_meta_cap'    => true,
			]
		);
	}
}
add_action( 'init', 'maxpost_hub_register_content_types' );

function maxpost_hub_sanitize_string_list( mixed $value ): array {
	return array_values( array_unique( array_filter( array_map( 'sanitize_key', (array) $value ) ) ) );
}

function maxpost_hub_register_meta(): void {
	$common = [
		'single'        => true,
		'show_in_rest'  => false,
		'auth_callback' => static fn(): bool => current_user_can( 'manage_options' ),
	];
	$schema = [
		'maxpost_hub_card' => [
			'_maxpost_hub_type' => [ 'string', 'sanitize_key' ],
			'_maxpost_hub_subtitle' => [ 'string', 'sanitize_text_field' ],
			'_maxpost_hub_button_label' => [ 'string', 'sanitize_text_field' ],
			'_maxpost_hub_url' => [ 'string', 'esc_url_raw' ],
			'_maxpost_hub_priority' => [ 'integer', 'absint' ],
			'_maxpost_hub_enabled' => [ 'boolean', 'rest_sanitize_boolean' ],
			'_maxpost_hub_locale' => [ 'string', 'sanitize_text_field' ],
			'_maxpost_hub_starts_at' => [ 'string', 'sanitize_text_field' ],
			'_maxpost_hub_ends_at' => [ 'string', 'sanitize_text_field' ],
			'_maxpost_hub_app_targets' => [ 'array', 'maxpost_hub_sanitize_string_list' ],
		],
		'maxpost_update' => [
			'_maxpost_update_app' => [ 'string', 'sanitize_key' ],
			'_maxpost_update_version' => [ 'string', 'sanitize_text_field' ],
			'_maxpost_update_channel' => [ 'string', 'sanitize_key' ],
			'_maxpost_update_url' => [ 'string', 'esc_url_raw' ],
			'_maxpost_update_sha256' => [ 'string', 'sanitize_text_field' ],
			'_maxpost_update_required' => [ 'boolean', 'rest_sanitize_boolean' ],
		],
		'maxpost_notice' => [
			'_maxpost_notice_level' => [ 'string', 'sanitize_key' ],
			'_maxpost_notice_enabled' => [ 'boolean', 'rest_sanitize_boolean' ],
			'_maxpost_notice_locale' => [ 'string', 'sanitize_text_field' ],
			'_maxpost_notice_starts_at' => [ 'string', 'sanitize_text_field' ],
			'_maxpost_notice_ends_at' => [ 'string', 'sanitize_text_field' ],
			'_maxpost_notice_app_targets' => [ 'array', 'maxpost_hub_sanitize_string_list' ],
		],
		'maxpost_flag' => [
			'_maxpost_flag_key' => [ 'string', 'sanitize_key' ],
			'_maxpost_flag_value' => [ 'string', 'sanitize_text_field' ],
			'_maxpost_flag_enabled' => [ 'boolean', 'rest_sanitize_boolean' ],
			'_maxpost_flag_app_targets' => [ 'array', 'maxpost_hub_sanitize_string_list' ],
		],
		'maxpost_message' => [
			'_maxpost_message_key' => [ 'string', 'sanitize_key' ],
			'_maxpost_message_locale' => [ 'string', 'sanitize_text_field' ],
			'_maxpost_message_enabled' => [ 'boolean', 'rest_sanitize_boolean' ],
		],
	];

	foreach ( $schema as $post_type => $fields ) {
		foreach ( $fields as $key => $definition ) {
			$args = array_merge( $common, [ 'type' => $definition[0], 'sanitize_callback' => $definition[1] ] );
			if ( 'array' === $definition[0] ) {
				$args['show_in_rest'] = [ 'schema' => [ 'type' => 'array', 'items' => [ 'type' => 'string' ] ] ];
			}
			register_post_meta( $post_type, $key, $args );
		}
	}
}
add_action( 'init', 'maxpost_hub_register_meta' );

function maxpost_hub_targets_match( array $targets, string $app ): bool {
	return ! $targets || in_array( $app, $targets, true );
}

function maxpost_hub_window_is_active( string $starts_at, string $ends_at ): bool {
	$now   = time();
	$start = $starts_at ? strtotime( $starts_at ) : false;
	$end   = $ends_at ? strtotime( $ends_at ) : false;
	return ( ! $start || $start <= $now ) && ( ! $end || $end >= $now );
}

function maxpost_hub_card_is_active( WP_Post $post, string $app, string $locale ): bool {
	if ( ! get_post_meta( $post->ID, '_maxpost_hub_enabled', true ) ) {
		return false;
	}
	$targets = (array) get_post_meta( $post->ID, '_maxpost_hub_app_targets', true );
	$item_locale = (string) get_post_meta( $post->ID, '_maxpost_hub_locale', true );
	return maxpost_hub_targets_match( $targets, $app )
		&& ( ! $item_locale || $item_locale === $locale )
		&& maxpost_hub_window_is_active(
			(string) get_post_meta( $post->ID, '_maxpost_hub_starts_at', true ),
			(string) get_post_meta( $post->ID, '_maxpost_hub_ends_at', true )
		);
}
