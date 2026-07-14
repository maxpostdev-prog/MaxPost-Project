<?php
/**
 * Software card.
 *
 * @package MaxPost
 */

$software = function_exists( 'maxpost_get_software' ) ? maxpost_get_software( get_the_ID() ) : null;
if ( ! $software ) {
	return;
}

$image_id = $software['card_image_id'] ?: ( $software['screenshot_ids'][0] ?? 0 );
?>
<article class="software-card">
	<a class="software-card__media" href="<?php the_permalink(); ?>">
		<?php
		if ( $image_id ) {
			echo wp_kses_post( wp_get_attachment_image( $image_id, 'maxpost-card', false, [ 'class' => 'software-card__image', 'loading' => 'lazy' ] ) );
		} elseif ( has_post_thumbnail() ) {
			the_post_thumbnail( 'maxpost-card', [ 'class' => 'software-card__image', 'loading' => 'lazy' ] );
		} else {
			echo '<span class="software-card__placeholder" aria-hidden="true">MP</span>';
		}
		?>
	</a>
	<div class="software-card__body">
		<p class="software-card__eyebrow"><?php echo esc_html( $software['categories'][0]['name'] ?? __( 'Utility', 'maxpost' ) ); ?></p>
		<h3 class="software-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo esc_html( $software['description'] ); ?></p>
		<div class="software-card__actions">
			<?php if ( $software['download_url'] ) : ?>
				<a class="mp-button mp-button--primary" href="<?php echo esc_url( $software['download_url'] ); ?>"><?php esc_html_e( 'Download', 'maxpost' ); ?></a>
			<?php endif; ?>
			<a class="mp-button mp-button--ghost" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Learn more', 'maxpost' ); ?></a>
		</div>
	</div>
</article>
