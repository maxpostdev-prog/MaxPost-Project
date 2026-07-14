<?php
/**
 * Software administration fields.
 *
 * @package MaxPostCore
 */

defined( 'ABSPATH' ) || exit;

function maxpost_core_add_meta_boxes(): void {
	add_meta_box(
		'maxpost-software-details',
		__( 'Software details', 'maxpost-core' ),
		'maxpost_core_render_software_details',
		'software',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'maxpost_core_add_meta_boxes' );

function maxpost_core_admin_assets( string $hook_suffix ): void {
	$screen = get_current_screen();
	if ( ! $screen || 'software' !== $screen->post_type || ! in_array( $hook_suffix, [ 'post.php', 'post-new.php' ], true ) ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_style(
		'maxpost-core-admin',
		plugins_url( 'assets/css/admin.css', MAXPOST_CORE_FILE ),
		[],
		MAXPOST_CORE_VERSION
	);
	wp_enqueue_script(
		'maxpost-core-media',
		plugins_url( 'assets/js/media-admin.js', MAXPOST_CORE_FILE ),
		[ 'jquery' ],
		MAXPOST_CORE_VERSION,
		true
	);
	wp_localize_script(
		'maxpost-core-media',
		'MaxPostMedia',
		[
			'iconTitle'       => __( 'Select application icon', 'maxpost-core' ),
			'cardTitle'       => __( 'Select card image', 'maxpost-core' ),
			'screensTitle'    => __( 'Select screenshots', 'maxpost-core' ),
			'useImage'        => __( 'Use image', 'maxpost-core' ),
			'useScreenshots'  => __( 'Use screenshots', 'maxpost-core' ),
			'remove'          => __( 'Remove', 'maxpost-core' ),
		],
	);
}
add_action( 'admin_enqueue_scripts', 'maxpost_core_admin_assets' );

function maxpost_core_render_media_field( int $post_id, string $meta_key, string $label, bool $multiple = false ): void {
	$value = get_post_meta( $post_id, $meta_key, true );
	$ids   = $multiple ? array_values( array_filter( array_map( 'absint', (array) $value ) ) ) : [ absint( $value ) ];
	$ids   = array_values( array_filter( $ids ) );
	?>
	<div class="maxpost-media-field" data-multiple="<?php echo $multiple ? '1' : '0'; ?>">
		<p><strong><?php echo esc_html( $label ); ?></strong></p>
		<input type="hidden" class="maxpost-media-value" name="<?php echo esc_attr( $meta_key ); ?>" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>">
		<div class="maxpost-media-preview" aria-live="polite">
			<?php foreach ( $ids as $attachment_id ) : ?>
				<div class="maxpost-media-item" data-id="<?php echo esc_attr( (string) $attachment_id ); ?>">
					<?php echo wp_kses_post( wp_get_attachment_image( $attachment_id, 'thumbnail' ) ); ?>
					<button type="button" class="button-link-delete maxpost-media-remove"><?php esc_html_e( 'Remove', 'maxpost-core' ); ?></button>
				</div>
			<?php endforeach; ?>
		</div>
		<p>
			<button type="button" class="button maxpost-media-select" data-title="<?php echo esc_attr( $label ); ?>">
				<?php echo esc_html( $multiple ? __( 'Select screenshots', 'maxpost-core' ) : __( 'Select image', 'maxpost-core' ) ); ?>
			</button>
		</p>
	</div>
	<?php
}

function maxpost_core_render_software_details( WP_Post $post ): void {
	wp_nonce_field( 'maxpost_save_software_details', 'maxpost_software_nonce' );

	$fields = [
		'_maxpost_version'      => __( 'Version', 'maxpost-core' ),
		'_maxpost_file_size'    => __( 'File size', 'maxpost-core' ),
		'_maxpost_download_url' => __( 'Download URL', 'maxpost-core' ),
	];

	foreach ( $fields as $key => $label ) {
		$value = (string) get_post_meta( $post->ID, $key, true );
		$type  = str_contains( $key, '_url' ) ? 'url' : 'text';
		?>
		<p>
			<label for="<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label><br>
			<input class="widefat" type="<?php echo esc_attr( $type ); ?>" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>">
		</p>
		<?php
	}

	maxpost_core_render_media_field( $post->ID, '_maxpost_icon_id', __( 'Application icon', 'maxpost-core' ) );
	maxpost_core_render_media_field( $post->ID, '_maxpost_card_image_id', __( 'Card image', 'maxpost-core' ) );
	maxpost_core_render_media_field( $post->ID, '_maxpost_screenshot_ids', __( 'Screenshots', 'maxpost-core' ), true );

	$featured = (bool) get_post_meta( $post->ID, '_maxpost_featured', true );
	?>
	<p>
		<label>
			<input type="checkbox" name="_maxpost_featured" value="1" <?php checked( $featured ); ?>>
			<?php esc_html_e( 'Featured software', 'maxpost-core' ); ?>
		</label>
	</p>
	<?php
}

function maxpost_core_validate_image_id( int $attachment_id ): int {
	return $attachment_id > 0 && wp_attachment_is_image( $attachment_id ) ? $attachment_id : 0;
}

function maxpost_core_save_software_details( int $post_id ): void {
	if ( ! isset( $_POST['maxpost_software_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['maxpost_software_nonce'] ) ), 'maxpost_save_software_details' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) || 'software' !== get_post_type( $post_id ) ) {
		return;
	}

	$text_fields = [ '_maxpost_version', '_maxpost_file_size' ];
	foreach ( $text_fields as $key ) {
		$value = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
		$value ? update_post_meta( $post_id, $key, $value ) : delete_post_meta( $post_id, $key );
	}

	$download_url = isset( $_POST['_maxpost_download_url'] ) ? esc_url_raw( wp_unslash( $_POST['_maxpost_download_url'] ) ) : '';
	$download_url ? update_post_meta( $post_id, '_maxpost_download_url', $download_url ) : delete_post_meta( $post_id, '_maxpost_download_url' );

	foreach ( [ '_maxpost_icon_id', '_maxpost_card_image_id' ] as $key ) {
		$attachment_id = isset( $_POST[ $key ] ) ? maxpost_core_validate_image_id( absint( $_POST[ $key ] ) ) : 0;
		$attachment_id ? update_post_meta( $post_id, $key, $attachment_id ) : delete_post_meta( $post_id, $key );
	}

	$raw_screenshots = isset( $_POST['_maxpost_screenshot_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['_maxpost_screenshot_ids'] ) ) : '';
	$screenshot_ids  = array_values( array_unique( array_filter( array_map( 'maxpost_core_validate_image_id', array_map( 'absint', explode( ',', $raw_screenshots ) ) ) ) ) );
	$screenshot_ids ? update_post_meta( $post_id, '_maxpost_screenshot_ids', $screenshot_ids ) : delete_post_meta( $post_id, '_maxpost_screenshot_ids' );

	update_post_meta( $post_id, '_maxpost_featured', isset( $_POST['_maxpost_featured'] ) ? '1' : '0' );
}
add_action( 'save_post_software', 'maxpost_core_save_software_details' );
