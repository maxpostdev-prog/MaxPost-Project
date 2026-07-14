<?php
/**
 * Front page.
 *
 * @package MaxPost
 */

get_header();
$featured = function_exists( 'maxpost_get_featured_software' ) ? maxpost_get_featured_software( 3 ) : [];
$latest   = new WP_Query(
	[
		'post_type'      => 'software',
		'post_status'    => 'publish',
		'posts_per_page' => 6,
		'no_found_rows'  => true,
	]
);
$archive_url = get_post_type_archive_link( 'software' ) ?: home_url( '/software/' );
?>
<main id="main">
	<section class="hero">
		<div class="hero__glow hero__glow--one"></div>
		<div class="hero__glow hero__glow--two"></div>
		<div class="mp-container hero__grid">
			<div class="hero__content">
				<p class="eyebrow"><span></span><?php esc_html_e( 'Independent Windows software', 'maxpost' ); ?></p>
				<h1><?php esc_html_e( 'Small tools.', 'maxpost' ); ?><br><em><?php esc_html_e( 'Serious time savings.', 'maxpost' ); ?></em></h1>
				<p class="hero__lead"><?php esc_html_e( 'Focused utilities for files, folders, images and everyday Windows tasks. No accounts. No bloat. Just useful software.', 'maxpost' ); ?></p>
				<div class="hero__actions">
					<a class="mp-button mp-button--primary" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Explore software', 'maxpost' ); ?><span aria-hidden="true">→</span></a>
					<a class="mp-button mp-button--secondary" href="#featured"><?php esc_html_e( 'See featured tools', 'maxpost' ); ?></a>
				</div>
				<div class="trust-row" aria-label="<?php esc_attr_e( 'Product qualities', 'maxpost' ); ?>">
					<span><b>✓</b><?php esc_html_e( 'Free to use', 'maxpost' ); ?></span>
					<span><b>✓</b><?php esc_html_e( 'Lightweight', 'maxpost' ); ?></span>
					<span><b>✓</b><?php esc_html_e( 'Multilingual', 'maxpost' ); ?></span>
				</div>
			</div>
			<div class="hero__visual" aria-label="<?php esc_attr_e( 'Preview of a MaxPost application', 'maxpost' ); ?>">
				<div class="visual-orbit visual-orbit--one"></div>
				<div class="visual-orbit visual-orbit--two"></div>
				<div class="app-mockup">
					<div class="app-mockup__bar">
						<div class="app-mockup__brand"><span>MP</span><strong>Folder Creator</strong></div>
						<div class="window-controls"><i></i><i></i><i></i></div>
					</div>
					<div class="app-mockup__body">
						<div class="mock-sidebar"><span class="is-active"></span><span></span><span></span><span></span></div>
						<div class="mock-workspace">
							<div class="mock-kicker"></div>
							<div class="mock-title"></div>
							<div class="mock-field"><span></span></div>
							<div class="mock-options"><i></i><i></i><i></i></div>
							<div class="mock-action"></div>
						</div>
					</div>
				</div>
				<div class="floating-card floating-card--top"><span>✓</span><div><strong><?php esc_html_e( 'Task complete', 'maxpost' ); ?></strong><small><?php esc_html_e( '250 folders created', 'maxpost' ); ?></small></div></div>
				<div class="floating-card floating-card--bottom"><span>4.8</span><small><?php esc_html_e( 'MB download', 'maxpost' ); ?></small></div>
			</div>
		</div>
	</section>

	<section class="proof-strip">
		<div class="mp-container proof-strip__inner">
			<p><?php esc_html_e( 'Built around one rule:', 'maxpost' ); ?></p>
			<strong><?php esc_html_e( 'One tool. One task. Done.', 'maxpost' ); ?></strong>
			<div class="proof-metrics"><span><b>0</b><?php esc_html_e( 'accounts', 'maxpost' ); ?></span><span><b>0</b><?php esc_html_e( 'bundled apps', 'maxpost' ); ?></span><span><b>1</b><?php esc_html_e( 'clear purpose', 'maxpost' ); ?></span></div>
		</div>
	</section>

	<section id="featured" class="section section--featured">
		<div class="mp-container">
			<div class="section-heading section-heading--split">
				<div><p class="eyebrow"><span></span><?php esc_html_e( 'Featured software', 'maxpost' ); ?></p><h2><?php esc_html_e( 'Useful from the first click.', 'maxpost' ); ?></h2></div>
				<p><?php esc_html_e( 'A small collection of focused utilities designed to remove repetitive work from your day.', 'maxpost' ); ?></p>
			</div>
			<?php if ( $featured ) : ?>
				<div class="software-grid">
					<?php foreach ( $featured as $item ) : $GLOBALS['post'] = get_post( $item['id'] ); setup_postdata( $GLOBALS['post'] ); get_template_part( 'template-parts/software-card' ); endforeach; wp_reset_postdata(); ?>
				</div>
			<?php else : ?>
				<div class="empty-state"><strong><?php esc_html_e( 'Featured software is ready for content.', 'maxpost' ); ?></strong><p><?php esc_html_e( 'Publish a Software entry and mark it as Featured in MaxPost Core.', 'maxpost' ); ?></p></div>
			<?php endif; ?>
		</div>
	</section>

	<section class="section section--surface">
		<div class="mp-container">
			<div class="section-heading"><p class="eyebrow"><span></span><?php esc_html_e( 'Browse by task', 'maxpost' ); ?></p><h2><?php esc_html_e( 'Start with what you need to do.', 'maxpost' ); ?></h2></div>
			<div class="category-grid">
				<?php foreach ( [ [ 'Files & folders', 'Create, rename and organize faster.', '⌘' ], [ 'Images', 'Convert and prepare visual assets.', '◫' ], [ 'Archives', 'Work with compressed files safely.', '▣' ], [ 'Text', 'Clean, transform and automate text.', 'T' ], [ 'System', 'Small tools for everyday Windows tasks.', '⚙' ], [ 'Developer', 'Focused helpers for technical work.', '</>' ] ] as $category ) : ?>
					<a class="category-card" href="<?php echo esc_url( $archive_url ); ?>"><span><?php echo esc_html( $category[2] ); ?></span><div><h3><?php echo esc_html( $category[0] ); ?></h3><p><?php echo esc_html( $category[1] ); ?></p></div><b aria-hidden="true">↗</b></a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="mp-container">
			<div class="section-heading section-heading--split"><div><p class="eyebrow"><span></span><?php esc_html_e( 'All software', 'maxpost' ); ?></p><h2><?php esc_html_e( 'The MaxPost toolbox.', 'maxpost' ); ?></h2></div><a class="text-link" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'View complete catalog', 'maxpost' ); ?> →</a></div>
			<?php if ( $latest->have_posts() ) : ?><div class="software-grid"><?php while ( $latest->have_posts() ) : $latest->the_post(); get_template_part( 'template-parts/software-card' ); endwhile; wp_reset_postdata(); ?></div><?php else : ?><div class="catalog-preview"><div><span>MP</span></div><div><h3><?php esc_html_e( 'Your software catalog starts here.', 'maxpost' ); ?></h3><p><?php esc_html_e( 'The layout, cards and empty states are ready. Add the first utility in WordPress to populate this section automatically.', 'maxpost' ); ?></p></div></div><?php endif; ?>
		</div>
	</section>

	<section class="section section--cta"><div class="mp-container"><div class="cta-panel"><div><p class="eyebrow"><span></span><?php esc_html_e( 'Built independently', 'maxpost' ); ?></p><h2><?php esc_html_e( 'Software should solve problems, not create new ones.', 'maxpost' ); ?></h2></div><a class="mp-button mp-button--primary" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Browse MaxPost tools', 'maxpost' ); ?><span>→</span></a></div></div></section>
</main>
<?php get_footer(); ?>
