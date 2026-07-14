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
}
add_action( 'admin_menu', 'maxpost_hub_admin_menu' );

function maxpost_hub_add_meta_boxes(): void {
	foreach ( array_keys( maxpost_hub_content_types() ) as $post_type ) {
		add_meta_box( 'maxpost-hub-settings', __( 'Delivery settings', 'maxpost-core' ), 'maxpost_hub_render_fields', $post_type, 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'maxpost_hub_add_meta_boxes' );

function maxpost_hub_field( string $key, string $label, string $type = 'text' ): void {
	$value = get_post_meta( get_the_ID(), $key, true );
	?>
	<p><label for="<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label><br>
	<input class="widefat" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" type="<?php echo esc_attr( $type ); ?>" value="<?php echo esc_attr( is_array( $value ) ? implode( ', ', $value ) : (string) $value ); ?>"></p>
	<?php
}

function maxpost_hub_checkbox( string $key, string $label ): void {
	?><p><label><input type="checkbox" name="<?php echo esc_attr( $key ); ?>" value="1" <?php checked( (bool) get_post_meta( get_the_ID(), $key, true ) ); ?>> <?php echo esc_html( $label ); ?></label></p><?php
}

function maxpost_hub_render_fields( WP_Post $post ): void {
	wp_nonce_field( 'maxpost_hub_save_entity', 'maxpost_hub_nonce' );
	switch ( $post->post_type ) {
		case 'maxpost_hub_card':
			$type = (string) get_post_meta( $post->ID, '_maxpost_hub_type', true ) ?: 'tool';
			?><p><label><strong><?php esc_html_e( 'Card type', 'maxpost-core' ); ?></strong><br><select name="_maxpost_hub_type"><?php foreach ( [ 'tool', 'news', 'support', 'partner' ] as $option ) : ?><option value="<?php echo esc_attr( $option ); ?>" <?php selected( $type, $option ); ?>><?php echo esc_html( ucfirst( $option ) ); ?></option><?php endforeach; ?></select></label></p><?php
			maxpost_hub_field( '_maxpost_hub_subtitle', __( 'Subtitle', 'maxpost-core' ) );
			maxpost_hub_field( '_maxpost_hub_button_label', __( 'Button label', 'maxpost-core' ) );
			maxpost_hub_field( '_maxpost_hub_url', __( 'Destination URL', 'maxpost-core' ), 'url' );
			maxpost_hub_field( '_maxpost_hub_priority', __( 'Priority', 'maxpost-core' ), 'number' );
			maxpost_hub_field( '_maxpost_hub_locale', __( 'Locale (blank = all)', 'maxpost-core' ) );
			maxpost_hub_field( '_maxpost_hub_starts_at', __( 'Starts at', 'maxpost-core' ), 'datetime-local' );
			maxpost_hub_field( '_maxpost_hub_ends_at', __( 'Ends at', 'maxpost-core' ), 'datetime-local' );
			maxpost_hub_field( '_maxpost_hub_app_targets', __( 'Target apps, comma-separated', 'maxpost-core' ) );
			maxpost_hub_checkbox( '_maxpost_hub_enabled', __( 'Enabled', 'maxpost-core' ) );
			break;
		case 'maxpost_update':
			maxpost_hub_field( '_maxpost_update_app', __( 'Application ID', 'maxpost-core' ) );
			maxpost_hub_field( '_maxpost_update_version', __( 'Version', 'maxpost-core' ) );
			maxpost_hub_field( '_maxpost_update_channel', __( 'Channel', 'maxpost-core' ) );
			maxpost_hub_field( '_maxpost_update_url', __( 'Download URL', 'maxpost-core' ), 'url' );
			maxpost_hub_field( '_maxpost_update_sha256', __( 'SHA-256', 'maxpost-core' ) );
			maxpost_hub_checkbox( '_maxpost_update_required', __( 'Required update', 'maxpost-core' ) );
			break;
		case 'maxpost_notice':
			maxpost_hub_field( '_maxpost_notice_level', __( 'Level: info, warning, critical', 'maxpost-core' ) );
			maxpost_hub_field( '_maxpost_notice_locale', __( 'Locale (blank = all)', 'maxpost-core' ) );
			maxpost_hub_field( '_maxpost_notice_starts_at', __( 'Starts at', 'maxpost-core' ), 'datetime-local' );
			maxpost_hub_field( '_maxpost_notice_ends_at', __( 'Ends at', 'maxpost-core' ), 'datetime-local' );
			maxpost_hub_field( '_maxpost_notice_app_targets', __( 'Target apps, comma-separated', 'maxpost-core' ) );
			maxpost_hub_checkbox( '_maxpost_notice_enabled', __( 'Enabled', 'maxpost-core' ) );
			break;
		case 'maxpost_flag':
			maxpost_hub_field( '_maxpost_flag_key', __( 'Flag key', 'maxpost-core' ) );
			maxpost_hub_field( '_maxpost_flag_value', __( 'Value', 'maxpost-core' ) );
			maxpost_hub_field( '_maxpost_flag_app_targets', __( 'Target apps, comma-separated', 'maxpost-core' ) );
			maxpost_hub_checkbox( '_maxpost_flag_enabled', __( 'Enabled', 'maxpost-core' ) );
			break;
		case 'maxpost_message':
			maxpost_hub_field( '_maxpost_message_key', __( 'Message key', 'maxpost-core' ) );
			maxpost_hub_field( '_maxpost_message_locale', __( 'Locale (blank/default = fallback)', 'maxpost-core' ) );
			maxpost_hub_checkbox( '_maxpost_message_enabled', __( 'Enabled', 'maxpost-core' ) );
			break;
	}
}

function maxpost_hub_save_post( int $post_id, WP_Post $post ): void {
	if ( ! array_key_exists( $post->post_type, maxpost_hub_content_types() ) || wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( ! isset( $_POST['maxpost_hub_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['maxpost_hub_nonce'] ) ), 'maxpost_hub_save_entity' ) ) {
		return;
	}
	$lists = [ '_maxpost_hub_app_targets', '_maxpost_notice_app_targets', '_maxpost_flag_app_targets' ];
	$urls = [ '_maxpost_hub_url', '_maxpost_update_url' ];
	$booleans = [ '_maxpost_hub_enabled', '_maxpost_update_required', '_maxpost_notice_enabled', '_maxpost_flag_enabled', '_maxpost_message_enabled' ];
	$allowed = [
		'maxpost_hub_card' => [ '_maxpost_hub_type', '_maxpost_hub_subtitle', '_maxpost_hub_button_label', '_maxpost_hub_url', '_maxpost_hub_priority', '_maxpost_hub_locale', '_maxpost_hub_starts_at', '_maxpost_hub_ends_at', '_maxpost_hub_app_targets', '_maxpost_hub_enabled' ],
		'maxpost_update' => [ '_maxpost_update_app', '_maxpost_update_version', '_maxpost_update_channel', '_maxpost_update_url', '_maxpost_update_sha256', '_maxpost_update_required' ],
		'maxpost_notice' => [ '_maxpost_notice_level', '_maxpost_notice_locale', '_maxpost_notice_starts_at', '_maxpost_notice_ends_at', '_maxpost_notice_app_targets', '_maxpost_notice_enabled' ],
		'maxpost_flag' => [ '_maxpost_flag_key', '_maxpost_flag_value', '_maxpost_flag_app_targets', '_maxpost_flag_enabled' ],
		'maxpost_message' => [ '_maxpost_message_key', '_maxpost_message_locale', '_maxpost_message_enabled' ],
	];
	foreach ( $allowed[ $post->post_type ] as $key ) {
		if ( in_array( $key, $booleans, true ) ) {
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? '1' : '0' );
		} elseif ( in_array( $key, $lists, true ) ) {
			$values = preg_split( '/\s*,\s*/', (string) wp_unslash( $_POST[ $key ] ?? '' ) );
			update_post_meta( $post_id, $key, maxpost_hub_sanitize_string_list( $values ) );
		} elseif ( in_array( $key, $urls, true ) ) {
			update_post_meta( $post_id, $key, esc_url_raw( wp_unslash( $_POST[ $key ] ?? '' ) ) );
		} else {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ?? '' ) ) );
		}
	}
}
add_action( 'save_post', 'maxpost_hub_save_post', 10, 2 );

function maxpost_hub_render_dashboard(): void {
	$stats = get_option( 'maxpost_hub_stats_' . gmdate( 'Ym' ), [] );
	$links = [
		[ 'Hub cards', 'maxpost_hub_card' ], [ 'App updates', 'maxpost_update' ], [ 'Notifications', 'maxpost_notice' ], [ 'Feature flags', 'maxpost_flag' ], [ 'Localized messages', 'maxpost_message' ],
	];
	?>
	<div class="wrap"><h1><?php esc_html_e( 'MaxPost Hub', 'maxpost-core' ); ?></h1><p><?php esc_html_e( 'Central control plane for MaxPost applications.', 'maxpost-core' ); ?></p>
	<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:16px;max-width:1100px"><?php foreach ( $links as $item ) : ?><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . $item[1] ) ); ?>" style="padding:20px;background:#fff;border:1px solid #dcdcde;border-radius:8px;text-decoration:none"><strong><?php echo esc_html( $item[0] ); ?></strong></a><?php endforeach; ?></div>
	<h2><?php esc_html_e( 'This month', 'maxpost-core' ); ?></h2><table class="widefat striped" style="max-width:1100px"><thead><tr><th>Metric</th><th>Count</th></tr></thead><tbody><?php if ( $stats ) : foreach ( $stats as $metric => $count ) : ?><tr><td><?php echo esc_html( $metric ); ?></td><td><?php echo esc_html( (string) $count ); ?></td></tr><?php endforeach; else : ?><tr><td colspan="2">No events yet.</td></tr><?php endif; ?></tbody></table></div>
	<?php
}
