<?php
/**
 * Site footer.
 *
 * @package MaxPost
 */
?>
<footer class="site-footer">
	<div class="mp-container">
		<div class="site-footer__grid">
			<div>
				<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="brand__mark" aria-hidden="true">MP</span><span class="brand__copy"><strong>MaxPost</strong><small><?php esc_html_e( 'Windows utilities', 'maxpost' ); ?></small></span></a>
				<p><?php esc_html_e( 'Small, focused software for everyday Windows work.', 'maxpost' ); ?></p>
			</div>
			<div><h4><?php esc_html_e( 'Product', 'maxpost' ); ?></h4><ul><li><a href="<?php echo esc_url( get_post_type_archive_link( 'software' ) ?: home_url( '/software/' ) ); ?>"><?php esc_html_e( 'All software', 'maxpost' ); ?></a></li><li><a href="<?php echo esc_url( home_url( '/updates/' ) ); ?>"><?php esc_html_e( 'Updates', 'maxpost' ); ?></a></li><li><a href="<?php echo esc_url( home_url( '/guides/' ) ); ?>"><?php esc_html_e( 'Guides', 'maxpost' ); ?></a></li></ul></div>
			<div><h4><?php esc_html_e( 'Company', 'maxpost' ); ?></h4><ul><li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'maxpost' ); ?></a></li><li><a href="<?php echo esc_url( home_url( '/support/' ) ); ?>"><?php esc_html_e( 'Support', 'maxpost' ); ?></a></li><li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'maxpost' ); ?></a></li></ul></div>
			<div><h4><?php esc_html_e( 'Legal', 'maxpost' ); ?></h4><ul><li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy', 'maxpost' ); ?></a></li><li><a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Terms', 'maxpost' ); ?></a></li></ul></div>
		</div>
		<div class="site-footer__bottom"><span>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> MaxPost</span><span><?php esc_html_e( 'One tool. One task. Done.', 'maxpost' ); ?></span></div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
