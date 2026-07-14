<?php
/**
 * Default template.
 *
 * @package MaxPost
 */

get_header();
?>
<main id="main" class="section">
	<div class="mp-container prose">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					<?php the_content(); ?>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing found.', 'maxpost' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
