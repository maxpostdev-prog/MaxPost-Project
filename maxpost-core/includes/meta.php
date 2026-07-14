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
		'_maxpost_screenshot_ids'        => 'array',
		'_maxpost_supported_windows'     => 'array',
		'_maxpost_supported_languages'   => 'array',
		'_maxpost_featured'              => 'boolean',
	];

	foreach ( $fields as $key => $type ) {
		$args = [
			'single'            => true,
			'type'              => $type,
			'show_in_rest'      => true,
			'auth_callback'     => static fn(): bool => current_user_can( 'edit_posts' ),
			'sanitize_callback' => 'maxpost_core_sanitize_meta',
		];

		if ( 'array' === $type ) {
			$args['show_in_rest'] = [
				'schema' => [
					'type'  => 'array',
					'items' => [ 'type' => 'string' ],
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

	if ( '_maxpost_featured' === $meta_key ) {
		return rest_sanitize_boolean( $value );
	}

	if ( is_array( $value ) ) {
		return array_values( array_filter( array_map( 'sanitize_text_field', $value ) ) );
	}

	if ( str_contains( $meta_key, '_url' ) ) {
		return esc_url_raw( (string) $value );
	}

	return sanitize_text_field( (string) $value );
}
