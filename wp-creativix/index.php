<?php get_header(); ?>
	
	<?php
	
	global $post;
	
	if(!empty($wpcx_cxOptions["slide_max"])) {
		
		$wpcx_slide_max = $wpcx_cxOptions["slide_max"];
		
	} else {
		
		$wpcx_slide_max = 0;
		
	}
	
	if(!empty($wpcx_cxOptions["slide_sort"])) {
		
		$wpcx_slide_sort = $wpcx_cxOptions["slide_sort"];
		
	} else {
		
		$wpcx_slide_sort = "date";
		
	}
	
	if(!empty($wpcx_cxOptions["slide_order"])) {
		
		$wpcx_slide_order = $wpcx_cxOptions["slide_order"];
		
	} else {
		
		$wpcx_slide_order = "DESC";
		
	}
	
	if(!empty($wpcx_cxOptions["slide_show"])) {
		
		$wpcx_slide_show = $wpcx_cxOptions["slide_show"];
		
	} else {
		
		$wpcx_slide_show = "yes";
		
	}
	
	if($wpcx_slide_show != "no") {

		$wpcx_args = array( 'meta_key' => 'feat_slideshow', 'meta_value'=> '1', 'suppress_filters' => 0, 'post_type' => array('post', 'page'), 'post_status' => 'publish', 'numberposts' => $wpcx_slide_max, 'orderby' => $wpcx_slide_sort, 'order' => $wpcx_slide_order);
		
		$wpcx_myposts = get_posts($wpcx_args);
		
		if(!$wpcx_myposts) {
			
			$wpcx_args = array('suppress_filters' => 0, 'post_type' => array('post', 'page'), 'post_status' => 'publish', 'numberposts' => $wpcx_slide_max, 'orderby' => $wpcx_slide_sort, 'order' => $wpcx_slide_order);
		
			$wpcx_myposts = get_posts($wpcx_args);
	
		}
	
		if($wpcx_myposts) {
		
		?>
		
			<div id="slide_wrapper">
				
				<ul id="slideshow">
					
					<?php
					
						foreach( $wpcx_myposts as $post ) :	setup_postdata($post);
							
							$wpcx_slideshow_title = get_the_title();
							
							$wpcx_slideshow_text = wpcx_cut_text(get_the_excerpt(), 290);
							
							$wpcx_thumb_big = wpcx_get_wp_generated_thumb("slideshow");
							
							if(empty($wpcx_thumb_big)) {
								
								$wpcx_thumb_big = get_template_directory_uri()."/images/default_slideshow.jpg";
								
							}
							
							?>
							
								<li><img src="<?php echo $wpcx_thumb_big;?>" alt="<?php the_title();?>" /><div class="slide_text"><h1><a href="<?php the_permalink();?>"><?php the_title();?></a></h1><span class="date">Published <?php the_time('F j, Y'); ?> - <?php comments_number('No Comments','One Comment','% Comments'); ?></span><p><?php echo $wpcx_slideshow_text;?></p><a href="<?php the_permalink();?>">read more</a></div></li>
							
							<?php 
							
						endforeach;
					
					
					?>
					
				</ul>
				
				<div class="slide_nav">
				
					<ul id="slide_navigation">
					
						<?php
					
						
							foreach( $wpcx_myposts as $post ) :	setup_postdata($post);
								
								$wpcx_thumb = wpcx_get_wp_generated_thumb("slideshow_thumb");
								
								if(empty($wpcx_thumb)) {
								
									$wpcx_thumb = get_template_directory_uri()."/images/default_slideshow_small.jpg";
								
								}
								
								?>
							
							
									<li><a href="#"><img src="<?php echo $wpcx_thumb;?>" alt="<?php the_title();?>" /></a></li>
							
							
								<?php 
								
							endforeach;
						
						
						?>
					
					</ul>
					
					<a id="arrowleft" href=""></a>
					<a id="arrowright" href=""></a>
					
				</div>
			</div>
			
		<?php
		
		}
		
	}
		
		?>

<div id="big-column">
	
        <div id="column-top">
        </div>
	
        <div id="column-content">
                
		<?php
				
		global $post;
		
		if(!empty($cxOptions["featured_sort"])) {
		
			$feat_sort = $cxOptions["featured_sort"];
			
		} else {
			
			$feat_sort = "date";
			
		}
		
		if(!empty($cxOptions["featured_order"])) {
		
			$feat_order = $cxOptions["featured_order"];
			
		} else {
			
			$feat_order = "DESC";
			
		}
	
		$args = array( 'meta_key' => 'feat_front', 'meta_value'=> '1', 'suppress_filters' => 0, 'post_type' => array('post', 'page'), 'post_status' => 'publish', 'numberposts' => 2, 'orderby' => $feat_sort, 'order' => $feat_order);
		
		$myposts = get_posts( $args );
		
		if(!$myposts) {
			
			$args = array('suppress_filters' => 0, 'post_type' => array('post'), 'post_status' => 'publish', 'numberposts' => 2, 'orderby' => $feat_sort, 'order' => $feat_order);
		
			$myposts = get_posts( $args );
			
		}
		
		
		foreach( $myposts as $post ) :	setup_postdata($post);
		
			$thumb = wpcx_get_wp_generated_thumb("feat_thumb");
				
			?>
			<div class="feat-post" id="feat-post-<?php the_ID();?>">
				
					<h2><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></h2>
					<h3>Published <?php the_time(get_option('date_format')); ?> at <?php the_time(get_option('time_format'));?> - <?php comments_number('No Comments','1 Comment','% Comments'); ?></h3>
					<?php the_excerpt();?>
					<a href="<?php the_permalink();?>"><?php if($thumb) {?><img src="<?php echo $thumb;?>" alt="" /><?php } ?></a>
					
			</div>

                <?php endforeach; ?>

        <div class="latest-posts">
                <h2>Latest Articles</h2>
                <h3>Latest Articles from the Blog</h3>
                <ul class="latest-posts">
                        <?php
                        
                        wp_reset_query();
                        
                        query_posts("posts_per_page=8&orderby=date&order=DESC");
                        
                        while (have_posts()) : the_post();
                        
                        ?>
			
				<li id="latest-post-<?php the_ID();?>"><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a><span class="date">Published <?php the_time(get_option('date_format')); ?> at <?php the_time(get_option('time_format'));?></span></li>
			
                        <?php endwhile;?>
                </ul>
        </div>

</div>

<?php get_footer(); ?>
