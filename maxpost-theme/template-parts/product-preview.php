<?php
/**
 * Product-specific interface preview used when real screenshots are unavailable.
 *
 * @package MaxPost
 */

$preview_slug = sanitize_html_class( get_post_field( 'post_name', get_the_ID() ) ?: 'utility' );
$preview_name = get_the_title();
$mode = 'folders';
if ( str_contains( $preview_slug, 'image' ) ) {
	$mode = 'images';
} elseif ( str_contains( $preview_slug, 'rename' ) ) {
	$mode = 'rename';
} elseif ( str_contains( $preview_slug, 'duplicate' ) ) {
	$mode = 'duplicates';
} elseif ( str_contains( $preview_slug, 'text' ) ) {
	$mode = 'text';
} elseif ( str_contains( $preview_slug, 'network' ) ) {
	$mode = 'network';
}
?>
<div class="product-preview product-preview--<?php echo esc_attr( $mode ); ?>" aria-hidden="true">
	<div class="product-preview__titlebar">
		<span class="product-preview__appmark">MP</span>
		<strong><?php echo esc_html( $preview_name ); ?></strong>
		<span class="product-preview__controls">— □ ×</span>
	</div>
	<div class="product-preview__layout">
		<div class="product-preview__sidebar"><i class="is-active"></i><i></i><i></i><i></i></div>
		<div class="product-preview__workspace">
			<?php if ( 'images' === $mode ) : ?>
				<div class="preview-split"><div class="preview-photo preview-photo--before"></div><div class="preview-photo preview-photo--after"></div></div>
				<div class="preview-toolbar"><span>WebP</span><span>90%</span><b>Convert</b></div>
			<?php elseif ( 'rename' === $mode ) : ?>
				<div class="preview-table"><span>summer_001.jpg</span><b>holiday-001.jpg</b><span>summer_002.jpg</span><b>holiday-002.jpg</b><span>summer_003.jpg</span><b>holiday-003.jpg</b></div>
				<div class="preview-action">Rename 24 files</div>
			<?php elseif ( 'duplicates' === $mode ) : ?>
				<div class="preview-duplicate"><span></span><div><b>IMG_1048.jpg</b><small>4.8 MB · identical</small></div><em>2 copies</em></div>
				<div class="preview-duplicate"><span></span><div><b>archive.zip</b><small>18.2 MB · identical</small></div><em>3 copies</em></div>
				<div class="preview-action">Review duplicates</div>
			<?php elseif ( 'text' === $mode ) : ?>
				<div class="preview-editor"><span>The quick brown fox...</span><span>THE QUICK BROWN FOX...</span><span>the-quick-brown-fox</span></div>
				<div class="preview-pills"><i>Trim</i><i>Case</i><i>Slug</i></div>
			<?php elseif ( 'network' === $mode ) : ?>
				<div class="preview-gauge"><span>18</span><small>ms ping</small></div>
				<div class="preview-bars"><i></i><i></i><i></i><i></i><i></i></div>
				<div class="preview-status">Connection looks good</div>
			<?php else : ?>
				<label>1. Select location</label><div class="preview-input"><span>C:\Users\Max\Documents</span><b>Browse</b></div>
				<label>2. Folder names</label><div class="preview-list"><span>Project_001</span><span>Project_002</span><span>Project_003</span></div>
				<div class="preview-action">Create folders</div>
			<?php endif; ?>
		</div>
	</div>
</div>
