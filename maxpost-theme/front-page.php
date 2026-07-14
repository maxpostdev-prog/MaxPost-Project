<?php
/**
 * Front page.
 *
 * @package MaxPost
 */

get_header();
$featured = function_exists( 'maxpost_get_featured_software' ) ? maxpost_get_featured_software( 3 ) : [];
?>
<main id="main">
	<section class="hero">
		<div class="mp-container hero__grid">
			<div class="hero__content">
				<p class="eyebrow">MaxPost Utilities</p>
				<h1><?php esc_html_e( 'Free Windows utilities built for real productivity.', 'maxpost' ); ?></h1>
				<p><?php esc_html_e( 'Small, fast and focused tools that help you finish repetitive work with less effort.', 'maxpost' ); ?></p>
				<div class="hero__actions">
					<a class="mp-button mp-button--primary" href="<?php echo esc_url( get_post_type_archive_link( 'software' ) ?: home_url( '/software/' ) ); ?>"><?php esc_html_e( 'Explore software', 'maxpost' ); ?></a>
					<a class="mp-button mp-button--secondary" href="#featured"><?php esc_html_e( 'Featured tools', 'maxpost' ); ?></a>
				</div>
				<ul class="hero__badges" aria-label="<?php esc_attr_e( 'Product qualities', 'maxpost' ); ?>">
					<li><?php esc_html_e( 'Free', 'maxpost' ); ?></li>
					<li><?php esc_html_e( 'Lightweight', 'maxpost' ); ?></li>
					<li><?php esc_html_e( 'Safe', 'maxpost' ); ?></li>
					<li><?php esc_html_e( 'Multilingual', 'maxpost' ); ?></li>
				</ul>
			</div>
			<div class="hero__visual" aria-hidden="true">
				<div class="app-mockup">
					<div class="app-mockup__bar"><span></span><span></span><span></span></div>
					<div class="app-mockup__body">
						<strong>MP Folder Creator</strong>
						<div class="app-mockup__field"></div>
						<div class="app-mockup__field app-mockup__field--short"></div>
						<div class="app-mockup__button"></div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section id="featured" class="section">
		<div class="mp-container">
			<div class="section-heading">
				<p class="eyebrow"><?php esc_html_e( 'Featured software', 'maxpost' ); ?></p>
				<h2><?php esc_html_e( 'Focused tools for everyday work', 'maxpost' ); ?></h2>
			</div>
			<?php if ( $featured ) : ?>
				<div class="software-grid">
					<?php foreach ( $featured as $item ) : ?>
						<?php
						$post = get_post( $item['id'] );
						if ( $post ) {
							setup_postdata( $post );
							get_template_part( 'template-parts/software-card' );
						}
						?>
					<?php endforeach; wp_reset_postdata(); ?>
				</div>
			<?php else : ?>
				<p class="empty-state"><?php esc_html_e( 'Featured software will appear here after MaxPost Core is activated and software entries are published.', 'maxpost' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>
