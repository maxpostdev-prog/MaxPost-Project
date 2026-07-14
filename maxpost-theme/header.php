<?php
/**
 * Site header.
 *
 * @package MaxPost
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'maxpost' ); ?></a>
<header class="site-header">
	<div class="mp-container site-header__inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'MaxPost home', 'maxpost' ); ?>">
			<span class="brand__mark" aria-hidden="true">MP</span>
			<span class="brand__name">MaxPost</span>
		</a>
		<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'maxpost' ); ?>">
			<?php wp_nav_menu( [ 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => false ] ); ?>
		</nav>
	</div>
</header>
