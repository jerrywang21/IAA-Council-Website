<div id="footer">

	<div class="wordpress-icon">
		<a href="http://www.wordpress.org/"><img src="<?php echo get_template_directory_uri(); ?>/images/wp-icon.png" alt="wp-icon" /></a> 
	</div>

	<p>
		COPYRIGHT BY <a href="<?php echo home_url();?>" title=" <?php bloginfo('name'); ?>" target="_blank"><?php bloginfo('name'); ?></a> | <span class="copyright">LAYOUT BY <a class="copyright" href="http://www.iwebix.de/" title="webdesign" target="_blank">IWEBIX Webdesign</a></span>
	</p>
	
	<div class="footer_right">
		<ul>
			<?php wp_nav_menu(array('theme_location'  => 'footer_menu', 'container' => false, 'depth' => 0, 'fallback_cb' => 'wpcx_default_menu')); ?>
		</ul>
	</div>
</div>

<p class="tagline"><?php bloginfo('description');?></p>

</div>
</div>

<?php wp_footer(); ?>

</body>
</html>
