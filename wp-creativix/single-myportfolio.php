<?php get_header(); ?>

<div id="sub-column">

	<div id="sub-top">
		<?php get_template_part('scripts/breadcrumb'); ?>
	</div>

	<div id="sub-content">
		<div class="content">
			<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
			<div class="post">
				<h1 class="first"><?php the_title();?></h1>
				<div class="post_meta">
					<span class="date">Published <?php the_time(get_option('date_format')); ?> at <?php the_time(get_option('time_format'));?></span>
					<span class="social">
							
						<?php
						
						if(!empty($wpcx_cxOptions["social_fb_like"])) {
							
							if($wpcx_cxOptions["social_fb_like"] == "yes") {
		
							?>
							
								<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;layout=button_count&amp;show_faces=false&amp;width=75&amp;action=like&amp;font&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:21px;" allowTransparency="true"></iframe>
							
							<?php
							
							}
							
						}
						
						?>
						
						<?php
						
						if(!empty($wpcx_cxOptions["social_google_like"])) {
							
							if($wpcx_cxOptions["social_google_like"] == "yes") {
		
							?>
							
								<span class="google"><g:plusone size="medium"></g:plusone></span>
								
							<?php
							
							}
							
						}
						
						?>
						
					</span>
				</div>
				<?php the_content();?>
				<?php the_tags( '<p class="meta">Tags: ', ', ', '</p>'); ?>
			</div>
			<?php wp_link_pages(); ?>	
			<?php edit_post_link('Edit Post'); ?>
			
			<div class="gallery">
			
				<?php 
					global $post;
					
					$wpcx_post_id = $post->ID;
					
					$args = array(
						'post_type' => 'attachment',
						'post_mime_type' => 'image',
						'numberposts' => -1,
						'orderby' => 'menu_order',
						'order' => 'ASC',
						'post_parent' => $wpcx_post_id
					);
					    
					$wpcx_images = get_posts($args);
					
					if ($wpcx_images) {
						
						?>
						
						<h2>Gallery</h2>
						
						<?php
						
						$wpcx_image_count = 0;
						foreach($wpcx_images as $wpcx_image) {
							$wpcx_image_url = wp_get_attachment_image_src($wpcx_image->ID, $size='attached-image');
							$wpcx_thumb_url = wp_get_attachment_image_src($wpcx_image->ID, 'reference');
							$wpcx_image_count ++;
							?>
							<a href="<?php echo $wpcx_image_url[0];?>" rel="full_view"><img src="<?php echo $wpcx_thumb_url[0];?>" /></a>
					<?php }
					
					}
				?>
				
			</div>
			
			<?php endwhile; endif;?>
			
		</div>
		
<?php get_sidebar(); ?>

<?php get_footer(); ?>
