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

	$featured = (bool) get_post_meta( $post->ID, '_maxpost_featured', true );
	?>
	<p>
		<label>
			<input type="checkbox" name="_maxpost_featured" value="1" <?php checked( $featured ); ?>>
			<?php esc_html_e( 'Featured software', 'maxpost-core' ); ?>
		</label>
	</p>
	<p class="description">
		<?php esc_html_e( 'Use the Featured Image as a fallback card image. Dedicated icon, card image and screenshot controls will be added in the next milestone.', 'maxpost-core' ); ?>
	</p>
	<?php
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

	update_post_meta( $post_id, '_maxpost_featured', isset( $_POST['_maxpost_featured'] ) ? '1' : '0' );
}
add_action( 'save_post_software', 'maxpost_core_save_software_details' );
