<?php
/**
 * Reversible demo content generator.
 *
 * @package MaxPostCore
 */

defined( 'ABSPATH' ) || exit;

function maxpost_core_register_demo_page(): void {
	add_submenu_page(
		'edit.php?post_type=software',
		__( 'Demo content', 'maxpost-core' ),
		__( 'Demo content', 'maxpost-core' ),
		'manage_options',
		'maxpost-demo-content',
		'maxpost_core_render_demo_page'
	);
}
add_action( 'admin_menu', 'maxpost_core_register_demo_page' );

function maxpost_core_render_demo_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$created = get_option( 'maxpost_demo_post_ids', [] );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'MaxPost demo content', 'maxpost-core' ); ?></h1>
		<p><?php esc_html_e( 'Create realistic sample software entries for design and installation testing. Demo entries are tagged internally and can be removed safely.', 'maxpost-core' ); ?></p>
		<?php if ( $created ) : ?>
			<p><strong><?php echo esc_html( sprintf( __( '%d demo entries are installed.', 'maxpost-core' ), count( $created ) ) ); ?></strong></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="maxpost_remove_demo_content">
				<?php wp_nonce_field( 'maxpost_remove_demo_content' ); ?>
				<?php submit_button( __( 'Remove demo content', 'maxpost-core' ), 'delete' ); ?>
			</form>
		<?php else : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="maxpost_create_demo_content">
				<?php wp_nonce_field( 'maxpost_create_demo_content' ); ?>
				<?php submit_button( __( 'Create demo content', 'maxpost-core' ), 'primary' ); ?>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

function maxpost_core_demo_dataset(): array {
	return [
		[
			'title'       => 'MP Folder Creator',
			'slug'        => 'mp-folder-creator',
			'excerpt'     => 'Create hundreds of folders in seconds from names, numbers or reusable templates.',
			'content'     => '<h2>Fast folder creation without repetitive work</h2><p>Build structured folder sets from lists, ranges and templates. Preview the result before anything is written to disk.</p><ul><li>Template-based naming</li><li>Number and date sequences</li><li>Duplicate detection</li><li>Portable workflow</li></ul>',
			'category'    => 'Files',
			'version'     => '1.0.0',
			'file_size'   => '8.4 MB',
			'featured'    => true,
			'download'    => '/downloads/mp-folder-creator.exe',
		],
		[
			'title'       => 'MP Image Converter',
			'slug'        => 'mp-image-converter',
			'excerpt'     => 'Convert, resize and optimize image batches with predictable presets.',
			'content'     => '<h2>Batch image processing that stays simple</h2><p>Convert common formats, resize large image collections and keep output settings consistent across projects.</p><ul><li>Batch conversion</li><li>Resize presets</li><li>Quality control</li><li>Metadata options</li></ul>',
			'category'    => 'Images',
			'version'     => '0.9.2',
			'file_size'   => '11.7 MB',
			'featured'    => true,
			'download'    => '/downloads/mp-image-converter.exe',
		],
		[
			'title'       => 'MP Bulk Rename',
			'slug'        => 'mp-bulk-rename',
			'excerpt'     => 'Rename large file collections with tokens, counters and a safe live preview.',
			'content'     => '<h2>Rename with confidence</h2><p>Build clear rename rules, inspect every resulting filename and apply the operation only when the preview is correct.</p><ul><li>Live preview</li><li>Find and replace</li><li>Counters and tokens</li><li>Undo history</li></ul>',
			'category'    => 'Files',
			'version'     => '1.1.0',
			'file_size'   => '7.9 MB',
			'featured'    => true,
			'download'    => '/downloads/mp-bulk-rename.exe',
		],
		[
			'title'       => 'MP Duplicate Finder',
			'slug'        => 'mp-duplicate-finder',
			'excerpt'     => 'Find duplicate files by content and review results before cleanup.',
			'content'     => '<h2>Recover space without risky automation</h2><p>Scan selected locations, group matching files and decide exactly what should be removed.</p><ul><li>Content hashing</li><li>Folder exclusions</li><li>Safe review mode</li><li>Exportable reports</li></ul>',
			'category'    => 'System',
			'version'     => '0.8.5',
			'file_size'   => '9.6 MB',
			'featured'    => false,
			'download'    => '/downloads/mp-duplicate-finder.exe',
		],
		[
			'title'       => 'MP Text Toolkit',
			'slug'        => 'mp-text-toolkit',
			'excerpt'     => 'Clean, transform and compare text without sending data to an online service.',
			'content'     => '<h2>Private text utilities in one focused workspace</h2><p>Normalize spacing, change case, remove duplicates and compare text locally.</p><ul><li>Case conversion</li><li>Whitespace cleanup</li><li>Duplicate-line removal</li><li>Text comparison</li></ul>',
			'category'    => 'Text',
			'version'     => '1.0.3',
			'file_size'   => '6.1 MB',
			'featured'    => false,
			'download'    => '/downloads/mp-text-toolkit.exe',
		],
		[
			'title'       => 'MP Network Check',
			'slug'        => 'mp-network-check',
			'excerpt'     => 'Run common connectivity checks from one clean Windows interface.',
			'content'     => '<h2>Useful network diagnostics without a terminal</h2><p>Check reachability, DNS resolution and common ports with readable results.</p><ul><li>Ping and DNS checks</li><li>Port testing</li><li>Result history</li><li>Copyable reports</li></ul>',
			'category'    => 'Network',
			'version'     => '0.7.0',
			'file_size'   => '5.8 MB',
			'featured'    => false,
			'download'    => '/downloads/mp-network-check.exe',
		],
	];
}

function maxpost_core_create_demo_content(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to perform this action.', 'maxpost-core' ) );
	}
	check_admin_referer( 'maxpost_create_demo_content' );

	$existing = get_option( 'maxpost_demo_post_ids', [] );
	if ( $existing ) {
		wp_safe_redirect( admin_url( 'edit.php?post_type=software&page=maxpost-demo-content' ) );
		exit;
	}

	$post_ids = [];
	foreach ( maxpost_core_demo_dataset() as $item ) {
		$term = term_exists( $item['category'], 'software_category' );
		if ( ! $term ) {
			$term = wp_insert_term( $item['category'], 'software_category' );
		}

		$post_id = wp_insert_post(
			[
				'post_type'    => 'software',
				'post_status'  => 'publish',
				'post_title'   => $item['title'],
				'post_name'    => $item['slug'],
				'post_excerpt' => $item['excerpt'],
				'post_content' => $item['content'],
				'meta_input'   => [
					'_maxpost_version'      => $item['version'],
					'_maxpost_file_size'    => $item['file_size'],
					'_maxpost_download_url' => home_url( $item['download'] ),
					'_maxpost_featured'     => $item['featured'] ? '1' : '0',
					'_maxpost_demo'         => '1',
				],
			],
			true
		);

		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		$term_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
		if ( $term_id ) {
			wp_set_post_terms( $post_id, [ $term_id ], 'software_category' );
		}
		$post_ids[] = $post_id;
	}

	update_option( 'maxpost_demo_post_ids', $post_ids, false );
	wp_safe_redirect( admin_url( 'edit.php?post_type=software&page=maxpost-demo-content' ) );
	exit;
}
add_action( 'admin_post_maxpost_create_demo_content', 'maxpost_core_create_demo_content' );

function maxpost_core_remove_demo_content(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to perform this action.', 'maxpost-core' ) );
	}
	check_admin_referer( 'maxpost_remove_demo_content' );

	foreach ( (array) get_option( 'maxpost_demo_post_ids', [] ) as $post_id ) {
		if ( '1' === get_post_meta( (int) $post_id, '_maxpost_demo', true ) ) {
			wp_delete_post( (int) $post_id, true );
		}
	}
	delete_option( 'maxpost_demo_post_ids' );

	wp_safe_redirect( admin_url( 'edit.php?post_type=software&page=maxpost-demo-content' ) );
	exit;
}
add_action( 'admin_post_maxpost_remove_demo_content', 'maxpost_core_remove_demo_content' );
