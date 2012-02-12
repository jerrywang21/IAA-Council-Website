<?php get_header(); ?>
<div id="sub-column">

	<div id="sub-top">
		<?php get_template_part('scripts/breadcrumb'); ?>
	</div>
	
	<div id="column-content">
	
		<div id="portfolio">
			
			<div class="content">
				
			<h1 class="first"><?php $wpcx_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); echo $wpcx_term->name; ?></h1>
				
			<?php
			$wpcx_post_count = 0;
			
			if ( have_posts() ) : while ( have_posts() ) : the_post();
			
			$wpcx_post_count ++;
			
			$wpcx_custom = get_post_custom($post->ID);
			
			$wpcx_img = wpcx_get_wp_generated_thumb("reference");
			
			?>
			
			<div class="port-pic">
				<h3><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></h3>
				<span class="info">filed under <?php echo $wpcx_cat_list = get_the_term_list( $post->ID, 'portfolio', '', ', ', '' );?></span>
				<div class="image">
					
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
							$wpcx_image_count = 0;
							foreach($wpcx_images as $wpcx_image) {
								$wpcx_image_url = wp_get_attachment_image_src($wpcx_image->ID, $size='attached-image');
								$wpcx_thumb_url = wp_get_attachment_image_src($wpcx_image->ID, 'reference');
								$wpcx_image_count ++;
								?>
								<a href="<?php echo $wpcx_image_url[0];?>" rel="full_view_<?php echo $wpcx_post_count;?>" <?php if($wpcx_image_count != 1) { echo "class='noshow'"; } ?>><img src="<?php echo $wpcx_thumb_url[0];?>" /></a>
						<?php }
						
						}
					?>
				</div>
				<div id="description">
					<?php the_excerpt();?>
				</div>
			</div>
			<?php endwhile; endif;?>
				
			<?php
			get_template_part('scripts/wp-pagenavi');
			if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
			?>
			
			</div>

		</div>
	</div>
<?php get_footer(); ?>