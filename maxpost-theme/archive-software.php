<?php
/**
 * Software archive.
 *
 * @package MaxPost
 */

get_header();
$categories = get_terms(
	[
		'taxonomy'   => 'software_category',
		'hide_empty' => true,
	]
);
$total = (int) wp_count_posts( 'software' )->publish;
?>
<main id="main" class="catalogue-page">
	<section class="catalogue-hero">
		<div class="mp-container catalogue-hero__grid">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'MaxPost software', 'maxpost' ); ?></p>
				<h1><?php esc_html_e( 'Small tools. Serious attention to detail.', 'maxpost' ); ?></h1>
				<p class="catalogue-hero__copy"><?php esc_html_e( 'A growing collection of focused Windows utilities designed to remove repetitive work without adding complexity.', 'maxpost' ); ?></p>
			</div>
			<div class="catalogue-summary" aria-label="<?php esc_attr_e( 'Catalogue summary', 'maxpost' ); ?>">
				<div><strong><?php echo esc_html( number_format_i18n( $total ) ); ?></strong><span><?php esc_html_e( 'Utilities', 'maxpost' ); ?></span></div>
				<div><strong>100%</strong><span><?php esc_html_e( 'Focused', 'maxpost' ); ?></span></div>
				<div><strong>0</strong><span><?php esc_html_e( 'Dark patterns', 'maxpost' ); ?></span></div>
			</div>
		</div>
	</section>

	<section class="catalogue-content section">
		<div class="mp-container">
			<div class="catalogue-toolbar">
				<form class="catalogue-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="screen-reader-text" for="catalogue-search"><?php esc_html_e( 'Search software', 'maxpost' ); ?></label>
					<input id="catalogue-search" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search utilities…', 'maxpost' ); ?>">
					<input type="hidden" name="post_type" value="software">
					<button type="submit"><?php esc_html_e( 'Search', 'maxpost' ); ?></button>
				</form>
				<?php if ( ! is_wp_error( $categories ) && $categories ) : ?>
					<nav class="category-pills" aria-label="<?php esc_attr_e( 'Software categories', 'maxpost' ); ?>">
						<a class="is-active" href="<?php echo esc_url( get_post_type_archive_link( 'software' ) ); ?>"><?php esc_html_e( 'All tools', 'maxpost' ); ?></a>
						<?php foreach ( $categories as $category ) : ?>
							<a href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?><span><?php echo esc_html( $category->count ); ?></span></a>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>
			</div>

			<div class="catalogue-heading">
				<div>
					<p class="eyebrow"><?php esc_html_e( 'Browse the collection', 'maxpost' ); ?></p>
					<h2><?php esc_html_e( 'Built for one job at a time', 'maxpost' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'No bundles, launchers or overloaded dashboards. Pick the utility that solves the task.', 'maxpost' ); ?></p>
			</div>

			<?php if ( have_posts() ) : ?>
				<div class="software-grid software-grid--catalogue">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'template-parts/software-card' ); ?>
					<?php endwhile; ?>
				</div>
				<div class="pagination"><?php the_posts_pagination(); ?></div>
			<?php else : ?>
				<div class="empty-state empty-state--large">
					<strong><?php esc_html_e( 'No software has been published yet.', 'maxpost' ); ?></strong>
					<p><?php esc_html_e( 'Create demo content from Software → Demo content, or publish your first utility.', 'maxpost' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>
