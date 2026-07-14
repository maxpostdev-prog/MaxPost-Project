<?php
/**
 * Software metadata registration.
 *
 * @package MaxPostCore
 */

defined( 'ABSPATH' ) || exit;

function maxpost_core_register_meta(): void {
	$fields = [
		'_maxpost_version'               => 'string',
		'_maxpost_file_size'             => 'string',
		'_maxpost_download_url'          => 'string',
		'_maxpost_portable_download_url' => 'string',
		'_maxpost_icon_id'               => 'integer',
		'_maxpost_card_image_id'         => 'integer',
		'_maxpost_screenshot_ids'        => 'integer_array',
		'_maxpost_supported_windows'     => 'string_array',
		'_maxpost_supported_languages'   => 'string_array',
		'_maxpost_featured'              => 'boolean',
	];

	foreach ( $fields as $key => $field_type ) {
		$type = match ( $field_type ) {
			'integer_array', 'string_array' => 'array',
			default                         => $field_type,
		};

		$args = [
			'single'            => true,
			'type'              => $type,
			'show_in_rest'      => true,
			'auth_callback'     => static fn(): bool => current_user_can( 'edit_posts' ),
			'sanitize_callback' => 'maxpost_core_sanitize_meta',
		];

		if ( 'integer_array' === $field_type || 'string_array' === $field_type ) {
			$args['show_in_rest'] = [
				'schema' => [
					'type'  => 'array',
					'items' => [
						'type' => 'integer_array' === $field_type ? 'integer' : 'string',
					],
				],
			];
		}

		register_post_meta( 'software', $key, $args );
	}
}
add_action( 'init', 'maxpost_core_register_meta' );

function maxpost_core_sanitize_meta( mixed $value, string $meta_key ): mixed {
	if ( in_array( $meta_key, [ '_maxpost_icon_id', '_maxpost_card_image_id' ], true ) ) {
		return absint( $value );
	}

	if ( '_maxpost_screenshot_ids' === $meta_key ) {
		$ids = array_map( 'absint', (array) $value );
		return array_values( array_unique( array_filter( $ids ) ) );
	}

	if ( '_maxpost_featured' === $meta_key ) {
		return rest_sanitize_boolean( $value );
	}

	if ( in_array( $meta_key, [ '_maxpost_supported_windows', '_maxpost_supported_languages' ], true ) ) {
		return array_values( array_unique( array_filter( array_map( 'sanitize_text_field', (array) $value ) ) ) );
	}

	if ( str_contains( $meta_key, '_url' ) ) {
		return esc_url_raw( (string) $value );
	}

	return sanitize_text_field( (string) $value );
}