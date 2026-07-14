<?php
/**
 * Single software landing page.
 *
 * @package MaxPost
 */

get_header();
?>
<main id="main">
	<?php while ( have_posts() ) : the_post(); $software = function_exists( 'maxpost_get_software' ) ? maxpost_get_software( get_the_ID() ) : null; ?>
		<?php if ( $software ) : ?>
			<section class="product-landing-hero">
				<div class="mp-container product-landing-hero__grid">
					<div>
						<div class="product-logo">MP</div>
						<p class="eyebrow"><span></span><?php echo esc_html( $software['categories'][0]['name'] ?? __( 'Windows utility', 'maxpost' ) ); ?></p>
						<h1><?php the_title(); ?></h1>
						<p class="product-hero__description"><?php echo esc_html( $software['description'] ); ?></p>
						<div class="product-proof"><span>✓ <?php esc_html_e( 'Free', 'maxpost' ); ?></span><span>✓ <?php esc_html_e( 'No account', 'maxpost' ); ?></span><span>✓ <?php esc_html_e( 'Windows 10/11', 'maxpost' ); ?></span></div>
						<ul class="product-meta"><?php if ( $software['version'] ) : ?><li><?php echo esc_html( sprintf( __( 'Version %s', 'maxpost' ), $software['version'] ) ); ?></li><?php endif; ?><?php if ( $software['file_size'] ) : ?><li><?php echo esc_html( $software['file_size'] ); ?></li><?php endif; ?><li><?php echo esc_html( get_the_modified_date() ); ?></li></ul>
						<?php if ( $software['download_url'] ) : ?><a class="mp-button mp-button--primary" href="<?php echo esc_url( $software['download_url'] ); ?>"><?php esc_html_e( 'Download for Windows', 'maxpost' ); ?> <span>↓</span></a><?php endif; ?>
					</div>
					<div class="product-hero__media">
						<?php $image_id = $software['card_image_id'] ?: ( $software['screenshot_ids'][0] ?? 0 ); if ( $image_id ) : echo wp_kses_post( wp_get_attachment_image( $image_id, 'maxpost-hero', false, [ 'class' => 'product-hero__image' ] ) ); elseif ( has_post_thumbnail() ) : the_post_thumbnail( 'maxpost-hero', [ 'class' => 'product-hero__image' ] ); else : ?><div class="product-preview--large"><?php get_template_part( 'template-parts/product-preview' ); ?></div><?php endif; ?>
					</div>
				</div>
			</section>

			<section class="product-section section--surface"><div class="mp-container"><div class="section-heading"><p class="eyebrow"><span></span><?php esc_html_e( 'Why it helps', 'maxpost' ); ?></p><h2><?php esc_html_e( 'Built to finish one task extremely well.', 'maxpost' ); ?></h2></div><div class="product-feature-grid"><article class="product-feature"><span>01</span><h3><?php esc_html_e( 'Focused workflow', 'maxpost' ); ?></h3><p><?php esc_html_e( 'No menus full of unrelated features. Open the app and get straight to the job.', 'maxpost' ); ?></p></article><article class="product-feature"><span>02</span><h3><?php esc_html_e( 'Fast and lightweight', 'maxpost' ); ?></h3><p><?php esc_html_e( 'Compact downloads, quick startup and a small footprint on your system.', 'maxpost' ); ?></p></article><article class="product-feature"><span>03</span><h3><?php esc_html_e( 'Private by default', 'maxpost' ); ?></h3><p><?php esc_html_e( 'Your work stays on your computer. No account is required for the core workflow.', 'maxpost' ); ?></p></article></div></div></section>

			<section class="product-section"><div class="mp-container prose"><p class="eyebrow"><span></span><?php esc_html_e( 'Overview', 'maxpost' ); ?></p><h2><?php esc_html_e( 'Everything you need. Nothing you do not.', 'maxpost' ); ?></h2><?php the_content(); ?></div></section>

			<?php if ( $software['screenshot_ids'] ) : ?><section class="product-section section--surface"><div class="mp-container"><div class="section-heading"><p class="eyebrow"><span></span><?php esc_html_e( 'Interface', 'maxpost' ); ?></p><h2><?php esc_html_e( 'See the workflow before you download.', 'maxpost' ); ?></h2></div><div class="product-gallery"><?php foreach ( $software['screenshot_ids'] as $screenshot_id ) : echo wp_kses_post( wp_get_attachment_image( $screenshot_id, 'large', false, [ 'loading' => 'lazy' ] ) ); endforeach; ?></div></div></section><?php endif; ?>

			<section class="product-section"><div class="mp-container"><div class="cta-panel"><div><p class="eyebrow"><span></span><?php esc_html_e( 'Ready when you are', 'maxpost' ); ?></p><h2><?php echo esc_html( sprintf( __( 'Download %s and get the task done.', 'maxpost' ), $software['name'] ) ); ?></h2></div><?php if ( $software['download_url'] ) : ?><a class="mp-button mp-button--primary" href="<?php echo esc_url( $software['download_url'] ); ?>"><?php esc_html_e( 'Download now', 'maxpost' ); ?></a><?php endif; ?></div></div></section>
		<?php endif; ?>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
