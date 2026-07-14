<?php
/**
 * Compact software catalogue table.
 *
 * @package MaxPost
 */

$table_query = new WP_Query(
	[
		'post_type'      => 'software',
		'post_status'    => 'publish',
		'posts_per_page' => 50,
		'orderby'        => 'modified',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	]
);
?>
<div class="software-table-wrap" data-software-table>
	<div class="software-table-toolbar">
		<label><span class="screen-reader-text"><?php esc_html_e( 'Search software', 'maxpost' ); ?></span><input type="search" placeholder="<?php esc_attr_e( 'Search software…', 'maxpost' ); ?>" data-table-search></label>
		<select data-table-category aria-label="<?php esc_attr_e( 'Filter by category', 'maxpost' ); ?>"><option value=""><?php esc_html_e( 'All categories', 'maxpost' ); ?></option><?php foreach ( get_terms( [ 'taxonomy' => 'software_category', 'hide_empty' => true ] ) as $term ) : ?><option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option><?php endforeach; ?></select>
	</div>
	<div class="software-table" role="table">
		<div class="software-table__head" role="row"><span><?php esc_html_e( 'Software', 'maxpost' ); ?></span><span><?php esc_html_e( 'Category', 'maxpost' ); ?></span><span><?php esc_html_e( 'Version', 'maxpost' ); ?></span><span><?php esc_html_e( 'Size', 'maxpost' ); ?></span><span><?php esc_html_e( 'Updated', 'maxpost' ); ?></span><span></span></div>
		<?php while ( $table_query->have_posts() ) : $table_query->the_post(); $item = maxpost_get_software( get_the_ID() ); $category = $item['categories'][0] ?? [ 'name' => __( 'Utility', 'maxpost' ), 'slug' => 'utility' ]; ?>
			<div class="software-table__row" role="row" data-name="<?php echo esc_attr( strtolower( $item['name'] ) ); ?>" data-category="<?php echo esc_attr( $category['slug'] ); ?>">
				<span class="software-table__product"><b class="software-table__icon">MP</b><span><strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong><small><?php echo esc_html( wp_trim_words( $item['description'], 8 ) ); ?></small></span></span>
				<span><?php echo esc_html( $category['name'] ); ?></span><span><?php echo esc_html( $item['version'] ?: '—' ); ?></span><span><?php echo esc_html( $item['file_size'] ?: '—' ); ?></span><span><?php echo esc_html( get_the_modified_date() ); ?></span>
				<span><?php if ( $item['download_url'] ) : ?><a class="table-download" href="<?php echo esc_url( $item['download_url'] ); ?>"><?php esc_html_e( 'Download', 'maxpost' ); ?></a><?php endif; ?></span>
			</div>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
	<p class="software-table-empty" hidden data-table-empty><?php esc_html_e( 'No software matches your filters.', 'maxpost' ); ?></p>
</div>
