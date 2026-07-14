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

$image_id     = $software['card_image_id'] ?: ( $software['screenshot_ids'][0] ?? 0 );
$category     = $software['categories'][0]['name'] ?? __( 'Utility', 'maxpost' );
$category_key = sanitize_html_class( strtolower( $software['categories'][0]['slug'] ?? 'utility' ) );
$days_old     = (int) floor( ( time() - get_post_modified_time( 'U', true ) ) / DAY_IN_SECONDS );
$badge        = $days_old <= 14 ? __( 'Updated', 'maxpost' ) : ( $software['featured'] ? __( 'Featured', 'maxpost' ) : __( 'Free', 'maxpost' ) );
?>
<article class="software-card software-card--<?php echo esc_attr( $category_key ); ?>">
	<a class="software-card__media" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View %s', 'maxpost' ), $software['name'] ) ); ?>">
		<span class="software-card__badge"><?php echo esc_html( $badge ); ?></span>
		<?php if ( $image_id ) : ?>
			<?php echo wp_kses_post( wp_get_attachment_image( $image_id, 'maxpost-card', false, [ 'class' => 'software-card__image', 'loading' => 'lazy' ] ) ); ?>
		<?php elseif ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'maxpost-card', [ 'class' => 'software-card__image', 'loading' => 'lazy' ] ); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/product-preview' ); ?>
		<?php endif; ?>
	</a>
	<div class="software-card__body">
		<div class="software-card__topline"><p class="software-card__eyebrow"><?php echo esc_html( $category ); ?></p><?php if ( $software['version'] ) : ?><span class="software-card__version">v<?php echo esc_html( $software['version'] ); ?></span><?php endif; ?></div>
		<h3 class="software-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="software-card__description"><?php echo esc_html( $software['description'] ); ?></p>
		<div class="software-card__rating"><span aria-hidden="true">★★★★★</span><small><?php esc_html_e( 'Fast, focused utility', 'maxpost' ); ?></small></div>
		<div class="software-card__meta"><span><?php esc_html_e( 'Free', 'maxpost' ); ?></span><?php if ( $software['file_size'] ) : ?><span><?php echo esc_html( $software['file_size'] ); ?></span><?php endif; ?><span><?php esc_html_e( 'Windows 10/11', 'maxpost' ); ?></span></div>
		<div class="software-card__actions"><?php if ( $software['download_url'] ) : ?><a class="mp-button mp-button--primary mp-button--compact" href="<?php echo esc_url( $software['download_url'] ); ?>"><?php esc_html_e( 'Download', 'maxpost' ); ?></a><?php endif; ?><a class="mp-button mp-button--ghost mp-button--compact" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Details', 'maxpost' ); ?> <span aria-hidden="true">→</span></a></div>
	</div>
</article>
