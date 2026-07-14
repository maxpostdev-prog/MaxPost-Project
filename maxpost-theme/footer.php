<?php
/**
 * Site footer.
 *
 * @package MaxPost
 */
?>
<footer class="site-footer">
	<div class="mp-container site-footer__inner">
		<div>
			<strong>MaxPost</strong>
			<p><?php esc_html_e( 'Simple software that saves your time.', 'maxpost' ); ?></p>
		</div>
		<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> MaxPost</p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
