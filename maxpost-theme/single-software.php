<?php
/**
 * Single software page.
 *
 * @package MaxPost
 */

get_header();
?>
<main id="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php $software = function_exists( 'maxpost_get_software' ) ? maxpost_get_software( get_the_ID() ) : null; ?>
		<?php if ( $software ) : ?>
			<section class="product-hero section">
				<div class="mp-container product-hero__grid">
					<div>
						<p class="eyebrow"><?php echo esc_html( $software['categories'][0]['name'] ?? __( 'Windows utility', 'maxpost' ) ); ?></p>
						<h1><?php the_title(); ?></h1>
						<p class="product-hero__description"><?php echo esc_html( $software['description'] ); ?></p>
						<ul class="product-meta">
							<?php if ( $software['version'] ) : ?><li><?php echo esc_html( sprintf( __( 'Version %s', 'maxpost' ), $software['version'] ) ); ?></li><?php endif; ?>
							<?php if ( $software['file_size'] ) : ?><li><?php echo esc_html( $software['file_size'] ); ?></li><?php endif; ?>
						</ul>
						<?php if ( $software['download_url'] ) : ?>
							<a class="mp-button mp-button--primary" href="<?php echo esc_url( $software['download_url'] ); ?>"><?php esc_html_e( 'Download', 'maxpost' ); ?></a>
						<?php endif; ?>
					</div>
					<div class="product-hero__media">
						<?php
						$image_id = $software['card_image_id'] ?: ( $software['screenshot_ids'][0] ?? 0 );
						if ( $image_id ) {
							echo wp_kses_post( wp_get_attachment_image( $image_id, 'maxpost-hero', false, [ 'class' => 'product-hero__image' ] ) );
						} elseif ( has_post_thumbnail() ) {
							the_post_thumbnail( 'maxpost-hero', [ 'class' => 'product-hero__image' ] );
						}
						?>
					</div>
				</div>
			</section>

			<?php if ( $software['screenshot_ids'] ) : ?>
				<section class="section">
					<div class="mp-container">
						<h2><?php esc_html_e( 'Screenshots', 'maxpost' ); ?></h2>
						<div class="screenshot-grid">
							<?php foreach ( $software['screenshot_ids'] as $screenshot_id ) : ?>
								<?php echo wp_kses_post( wp_get_attachment_image( $screenshot_id, 'large', false, [ 'class' => 'screenshot-grid__image', 'loading' => 'lazy' ] ) ); ?>
							<?php endforeach; ?>
						</div>
					</div>
				</section>
			<?php endif; ?>

			<section class="section section--surface">
				<div class="mp-container prose">
					<h2><?php esc_html_e( 'Overview', 'maxpost' ); ?></h2>
					<?php the_content(); ?>
				</div>
			</section>
		<?php endif; ?>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
