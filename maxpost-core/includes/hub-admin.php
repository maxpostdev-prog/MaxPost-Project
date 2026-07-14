<?php
/**
 * MaxPost Hub administration.
 *
 * @package MaxPostCore
 */

defined( 'ABSPATH' ) || exit;

function maxpost_hub_admin_menu(): void {
	add_menu_page( __( 'MaxPost Hub', 'maxpost-core' ), __( 'MaxPost Hub', 'maxpost-core' ), 'manage_options', 'maxpost-hub', 'maxpost_hub_render_dashboard', 'dashicons-networking', 26 );
	add_submenu_page( 'maxpost-hub', __( 'Dashboard', 'maxpost-core' ), __( 'Dashboard', 'maxpost-core' ), 'manage_options', 'maxpost-hub', 'maxpost_hub_render_dashboard' );
	add_submenu_page( 'maxpost-hub', __( 'Configuration', 'maxpost-core' ), __( 'Configuration', 'maxpost-core' ), 'manage_options', 'maxpost-hub-config', 'maxpost_hub_render_config' );
}
add_action( 'admin_menu', 'maxpost_hub_admin_menu' );

function maxpost_hub_add_meta_boxes(): void {
	add_meta_box( 'maxpost-hub-card-settings', __( 'Delivery settings', 'maxpost-core' ), 'maxpost_hub_render_card_fields', 'maxpost_hub_card', 'normal', 'high' );
	add_meta_box( 'maxpost-update-settings', __( 'Update package', 'maxpost-core' ), 'maxpost_hub_render_update_fields', 'maxpost_update', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'maxpost_hub_add_meta_boxes' );

function maxpost_hub_render_card_fields( WP_Post $post ): void {
	wp_nonce_field( 'maxpost_hub_save_card', 'maxpost_hub_nonce' );
	$fields = [
		'_maxpost_hub_subtitle' => [ __( 'Subtitle', 'maxpost-core' ), 'text' ],
		'_maxpost_hub_button_label' => [ __( 'Button label', 'maxpost-core' ), 'text' ],
		'_maxpost_hub_url' => [ __( 'Destination URL', 'maxpost-core' ), 'url' ],
		'_maxpost_hub_priority' => [ __( 'Priority', 'maxpost-core' ), 'number' ],
		'_maxpost_hub_locale' => [ __( 'Locale (blank = all)', 'maxpost-core' ), 'text' ],
		'_maxpost_hub_starts_at' => [ __( 'Starts at (YYYY-MM-DD HH:MM)', 'maxpost-core' ), 'text' ],
		'_maxpost_hub_ends_at' => [ __( 'Ends at (YYYY-MM-DD HH:MM)', 'maxpost-core' ), 'text' ],
	];
	$type = (string) get_post_meta( $post->ID, '_maxpost_hub_type', true ) ?: 'tool';
	$targets = implode( ', ', (array) get_post_meta( $post->ID, '_maxpost_hub_app_targets', true ) );
	$enabled = (bool) get_post_meta( $post->ID, '_maxpost_hub_enabled', true );
	?>
	<p><label><strong><?php esc_html_e( 'Card type', 'maxpost-core' ); ?></strong><br><select name="_maxpost_hub_type">
	<?php foreach ( [ 'tool', 'news', 'notification', 'support', 'partner' ] as $option ) : ?><option value="<?php echo esc_attr( $option ); ?>" <?php selected( $type, $option ); ?>><?php echo esc_html( ucfirst( $option ) ); ?></option><?php endforeach; ?>
	</select></label></p>
	<?php foreach ( $fields as $key => $field ) : ?>
	<p><label for="<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $field[0] ); ?></strong></label><br><input class="widefat" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" type="<?php echo esc_attr( $field[1] ); ?>" value="<?php echo esc_attr( (string) get_post_meta( $post->ID, $key, true ) ); ?>"></p>
	<?php endforeach; ?>
	<p><label><strong><?php esc_html_e( 'Target apps', 'maxpost-core' ); ?></strong><br><input class="widefat" name="_maxpost_hub_app_targets" value="<?php echo esc_attr( $targets ); ?>" placeholder="mp-folder-creator, mp-image-converter"></label></p>
	<p><label><input type="checkbox" name="_maxpost_hub_enabled" value="1" <?php checked( $enabled ); ?>> <?php esc_html_e( 'Enabled', 'maxpost-core' ); ?></label></p>
	<?php
}

function maxpost_hub_render_update_fields( WP_Post $post ): void {
	wp_nonce_field( 'maxpost_hub_save_update', 'maxpost_update_nonce' );
	$fields = [
		'_maxpost_update_app' => [ __( 'Application ID', 'maxpost-core' ), 'text' ],
		'_maxpost_update_version' => [ __( 'Version', 'maxpost-core' ), 'text' ],
		'_maxpost_update_channel' => [ __( 'Channel', 'maxpost-core' ), 'text' ],
		'_maxpost_update_url' => [ __( 'Download URL', 'maxpost-core' ), 'url' ],
		'_maxpost_update_sha256' => [ __( 'SHA-256', 'maxpost-core' ), 'text' ],
	];
	foreach ( $fields as $key => $field ) : ?>
	<p><label for="<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $field[0] ); ?></strong></label><br><input class="widefat" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" type="<?php echo esc_attr( $field[1] ); ?>" value="<?php echo esc_attr( (string) get_post_meta( $post->ID, $key, true ) ); ?>"></p>
	<?php endforeach; ?>
	<p><label><input type="checkbox" name="_maxpost_update_required" value="1" <?php checked( (bool) get_post_meta( $post->ID, '_maxpost_update_required', true ) ); ?>> <?php esc_html_e( 'Required update', 'maxpost-core' ); ?></label></p>
	<?php
}

function maxpost_hub_save_post( int $post_id, WP_Post $post ): void {
	if ( wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( 'maxpost_hub_card' === $post->post_type ) {
		if ( ! isset( $_POST['maxpost_hub_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['maxpost_hub_nonce'] ) ), 'maxpost_hub_save_card' ) ) { return; }
		$text = [ '_maxpost_hub_type', '_maxpost_hub_subtitle', '_maxpost_hub_button_label', '_maxpost_hub_priority', '_maxpost_hub_locale', '_maxpost_hub_starts_at', '_maxpost_hub_ends_at' ];
		foreach ( $text as $key ) { update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ?? '' ) ) ); }
		update_post_meta( $post_id, '_maxpost_hub_url', esc_url_raw( wp_unslash( $_POST['_maxpost_hub_url'] ?? '' ) ) );
		$targets = array_filter( array_map( 'sanitize_key', preg_split( '/\s*,\s*/', (string) wp_unslash( $_POST['_maxpost_hub_app_targets'] ?? '' ) ) ) );
		update_post_meta( $post_id, '_maxpost_hub_app_targets', array_values( $targets ) );
		update_post_meta( $post_id, '_maxpost_hub_enabled', isset( $_POST['_maxpost_hub_enabled'] ) ? '1' : '0' );
	}
	if ( 'maxpost_update' === $post->post_type ) {
		if ( ! isset( $_POST['maxpost_update_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['maxpost_update_nonce'] ) ), 'maxpost_hub_save_update' ) ) { return; }
		foreach ( [ '_maxpost_update_app', '_maxpost_update_version', '_maxpost_update_channel', '_maxpost_update_sha256' ] as $key ) { update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ?? '' ) ) ); }
		update_post_meta( $post_id, '_maxpost_update_url', esc_url_raw( wp_unslash( $_POST['_maxpost_update_url'] ?? '' ) ) );
		update_post_meta( $post_id, '_maxpost_update_required', isset( $_POST['_maxpost_update_required'] ) ? '1' : '0' );
	}
}
add_action( 'save_post', 'maxpost_hub_save_post', 10, 2 );

function maxpost_hub_render_dashboard(): void {
	$stats = get_option( 'maxpost_hub_stats_' . gmdate( 'Ym' ), [] );
	?>
	<div class="wrap"><h1><?php esc_html_e( 'MaxPost Hub', 'maxpost-core' ); ?></h1><p><?php esc_html_e( 'Central control plane for MaxPost applications.', 'maxpost-core' ); ?></p>
	<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;max-width:980px">
	<?php foreach ( [ [ 'Hub cards', 'edit.php?post_type=maxpost_hub_card' ], [ 'App updates', 'edit.php?post_type=maxpost_update' ], [ 'Configuration', 'admin.php?page=maxpost-hub-config' ] ] as $item ) : ?><a href="<?php echo esc_url( admin_url( $item[1] ) ); ?>" style="padding:20px;background:#fff;border:1px solid #dcdcde;border-radius:8px;text-decoration:none"><strong><?php echo esc_html( $item[0] ); ?></strong></a><?php endforeach; ?>
	</div><h2><?php esc_html_e( 'This month', 'maxpost-core' ); ?></h2><table class="widefat striped" style="max-width:980px"><thead><tr><th>Metric</th><th>Count</th></tr></thead><tbody><?php if ( $stats ) : foreach ( $stats as $metric => $count ) : ?><tr><td><?php echo esc_html( $metric ); ?></td><td><?php echo esc_html( (string) $count ); ?></td></tr><?php endforeach; else : ?><tr><td colspan="2">No events yet.</td></tr><?php endif; ?></tbody></table></div>
	<?php
}

function maxpost_hub_render_config(): void {
	if ( isset( $_POST['maxpost_hub_config_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['maxpost_hub_config_nonce'] ) ), 'maxpost_hub_save_config' ) && current_user_can( 'manage_options' ) ) {
		$flags = json_decode( (string) wp_unslash( $_POST['maxpost_hub_flags'] ?? '{}' ), true );
		$messages = json_decode( (string) wp_unslash( $_POST['maxpost_hub_messages'] ?? '{}' ), true );
		if ( is_array( $flags ) ) { update_option( 'maxpost_hub_flags', $flags, false ); }
		if ( is_array( $messages ) ) { update_option( 'maxpost_hub_messages', $messages, false ); }
		echo '<div class="notice notice-success"><p>Saved.</p></div>';
	}
	$flags = wp_json_encode( maxpost_hub_get_flags(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	$messages = wp_json_encode( get_option( 'maxpost_hub_messages', [ 'default' => [] ] ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	?>
	<div class="wrap"><h1><?php esc_html_e( 'Hub configuration', 'maxpost-core' ); ?></h1><form method="post"><?php wp_nonce_field( 'maxpost_hub_save_config', 'maxpost_hub_config_nonce' ); ?><h2>Feature flags</h2><textarea class="large-text code" rows="12" name="maxpost_hub_flags"><?php echo esc_textarea( $flags ); ?></textarea><h2>Localized messages</h2><textarea class="large-text code" rows="16" name="maxpost_hub_messages"><?php echo esc_textarea( $messages ); ?></textarea><?php submit_button(); ?></form></div>
	<?php
}
