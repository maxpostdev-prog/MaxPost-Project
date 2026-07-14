<?php
/**
 * MaxPost Hub data model and helpers.
 *
 * @package MaxPostCore
 */

defined( 'ABSPATH' ) || exit;

function maxpost_hub_register_content_types(): void {
	register_post_type(
		'maxpost_hub_card',
		[
			'labels' => [
				'name'          => __( 'Hub cards', 'maxpost-core' ),
				'singular_name' => __( 'Hub card', 'maxpost-core' ),
				'add_new_item'  => __( 'Add Hub card', 'maxpost-core' ),
				'edit_item'     => __( 'Edit Hub card', 'maxpost-core' ),
			],
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => 'maxpost-hub',
			'supports'        => [ 'title', 'editor', 'thumbnail', 'page-attributes' ],
			'capability_type' => 'post',
			'map_meta_cap'    => true,
		]
	);

	register_post_type(
		'maxpost_update',
		[
			'labels' => [
				'name'          => __( 'App updates', 'maxpost-core' ),
				'singular_name' => __( 'App update', 'maxpost-core' ),
				'add_new_item'  => __( 'Add app update', 'maxpost-core' ),
			],
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => 'maxpost-hub',
			'supports'     => [ 'title', 'editor' ],
		]
	);
}
add_action( 'init', 'maxpost_hub_register_content_types' );

function maxpost_hub_sanitize_string_list( mixed $value ): array {
	return array_values( array_unique( array_filter( array_map( 'sanitize_key', (array) $value ) ) ) );
}

function maxpost_hub_register_meta(): void {
	$common = [ 'single' => true, 'show_in_rest' => true, 'auth_callback' => static fn(): bool => current_user_can( 'manage_options' ) ];
	$fields = [
		'_maxpost_hub_type'         => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_key' ],
		'_maxpost_hub_subtitle'     => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
		'_maxpost_hub_button_label' => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
		'_maxpost_hub_url'          => [ 'type' => 'string', 'sanitize_callback' => 'esc_url_raw' ],
		'_maxpost_hub_priority'     => [ 'type' => 'integer', 'sanitize_callback' => 'absint' ],
		'_maxpost_hub_enabled'      => [ 'type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean' ],
		'_maxpost_hub_locale'       => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
		'_maxpost_hub_starts_at'    => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
		'_maxpost_hub_ends_at'      => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
		'_maxpost_hub_app_targets'  => [ 'type' => 'array', 'sanitize_callback' => 'maxpost_hub_sanitize_string_list', 'show_in_rest' => [ 'schema' => [ 'type' => 'array', 'items' => [ 'type' => 'string' ] ] ] ],
		'_maxpost_update_app'       => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_key' ],
		'_maxpost_update_version'   => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
		'_maxpost_update_channel'   => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_key' ],
		'_maxpost_update_url'       => [ 'type' => 'string', 'sanitize_callback' => 'esc_url_raw' ],
		'_maxpost_update_sha256'    => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
		'_maxpost_update_required'  => [ 'type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean' ],
	];
	foreach ( $fields as $key => $args ) {
		$post_type = str_starts_with( $key, '_maxpost_update_' ) ? 'maxpost_update' : 'maxpost_hub_card';
		register_post_meta( $post_type, $key, array_merge( $common, $args ) );
	}
}
add_action( 'init', 'maxpost_hub_register_meta' );

function maxpost_hub_get_flags(): array {
	$flags = get_option( 'maxpost_hub_flags', [] );
	return is_array( $flags ) ? $flags : [];
}

function maxpost_hub_get_localized_messages( string $locale ): array {
	$all = get_option( 'maxpost_hub_messages', [] );
	if ( ! is_array( $all ) ) {
		return [];
	}
	return array_merge( $all['default'] ?? [], $all[ $locale ] ?? [] );
}

function maxpost_hub_card_is_active( WP_Post $post, string $app, string $locale ): bool {
	if ( ! get_post_meta( $post->ID, '_maxpost_hub_enabled', true ) ) {
		return false;
	}
	$targets = (array) get_post_meta( $post->ID, '_maxpost_hub_app_targets', true );
	if ( $targets && ! in_array( $app, $targets, true ) ) {
		return false;
	}
	$card_locale = (string) get_post_meta( $post->ID, '_maxpost_hub_locale', true );
	if ( $card_locale && $card_locale !== $locale ) {
		return false;
	}
	$now = time();
	$start = strtotime( (string) get_post_meta( $post->ID, '_maxpost_hub_starts_at', true ) );
	$end = strtotime( (string) get_post_meta( $post->ID, '_maxpost_hub_ends_at', true ) );
	return ( ! $start || $start <= $now ) && ( ! $end || $end >= $now );
}
