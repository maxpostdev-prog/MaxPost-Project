<?php
/**
 * MaxPost theme bootstrap.
 *
 * @package MaxPost
 */

defined( 'ABSPATH' ) || exit;

function maxpost_theme_setup(): void {
	load_theme_textdomain( 'maxpost', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'html5', [ 'search-form', 'gallery', 'caption', 'style', 'script' ] );
	register_nav_menus( [ 'primary' => __( 'Primary navigation', 'maxpost' ) ] );
	add_image_size( 'maxpost-card', 720, 450, true );
	add_image_size( 'maxpost-hero', 1400, 900, false );
}
add_action( 'after_setup_theme', 'maxpost_theme_setup' );

function maxpost_theme_assets(): void {
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'maxpost-style', get_stylesheet_uri(), [], $version );
	wp_enqueue_style( 'maxpost-main', get_template_directory_uri() . '/assets/css/main.css', [ 'maxpost-style' ], $version );
	wp_enqueue_style( 'maxpost-catalogue', get_template_directory_uri() . '/assets/css/catalogue.css', [ 'maxpost-main' ], $version );
	wp_enqueue_script( 'maxpost-navigation', get_template_directory_uri() . '/assets/js/navigation.js', [], $version, true );
}
add_action( 'wp_enqueue_scripts', 'maxpost_theme_assets' );

function maxpost_theme_fallback_menu(): void {
	$items = [
		__( 'Software', 'maxpost' ) => get_post_type_archive_link( 'software' ) ?: home_url( '/software/' ),
		__( 'Categories', 'maxpost' ) => home_url( '/software-category/' ),
		__( 'Updates', 'maxpost' ) => home_url( '/updates/' ),
		__( 'About', 'maxpost' ) => home_url( '/about/' ),
	];
	echo '<ul>';
	foreach ( $items as $label => $url ) {
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}

function maxpost_theme_core_notice(): void {
	if ( current_user_can( 'activate_plugins' ) && ! function_exists( 'maxpost_get_software' ) ) {
		echo '<div class="notice notice-warning"><p>' . esc_html__( 'MaxPost theme requires the MaxPost Core plugin for software catalogue features.', 'maxpost' ) . '</p></div>';
	}
}
add_action( 'admin_notices', 'maxpost_theme_core_notice' );
