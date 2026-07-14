<?php
/**
 * Software archive.
 *
 * @package MaxPost
 */

get_header();
?>
<main id="main" class="section">
	<div class="mp-container">
		<header class="archive-header">
			<p class="eyebrow"><?php esc_html_e( 'Software catalogue', 'maxpost' ); ?></p>
			<h1><?php post_type_archive_title(); ?></h1>
			<p><?php esc_html_e( 'Browse focused Windows utilities from MaxPost.', 'maxpost' ); ?></p>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="software-grid">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template-parts/software-card' ); ?>
				<?php endwhile; ?>
			</div>
			<div class="pagination"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p class="empty-state"><?php esc_html_e( 'No software has been published yet.', 'maxpost' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
