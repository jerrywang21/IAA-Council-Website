<?php get_header(); ?>


<div id="sub-column">

        <div id="sub-top">
                <?php get_template_part('scripts/breadcrumb.php'); ?>
        </div>
        
        <div id="sub-content">
                
                <div class="content">
                      
                        <h1>Search Results</h1>
                      
                        <?php if (have_posts()) : while (have_posts()) : the_post();
                        
                        $thumb = wpcx_get_wp_generated_thumb("reference");
                        
                        ?>
			 	
                                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                        <h1><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></h1>
                                        <div class="post_meta">
                                                <span class="category">
                                                        <?php
                                                                $category = get_the_category();
                                                                
                                                                if($category) {
                                                                        $name = $category[0]->cat_name;
                                                                        $id = $category[0]->cat_ID;
                                                                        $link = get_category_link($id);
                                                                        echo "<a href='$link' title='$name'>$name</a>";
                                                                }
                                                        ?>
                                                </span>
                                                <span class="date">Published <?php the_time(get_option('date_format')); ?> at <?php the_time(get_option('time_format'));?></span>
                                                <span class="comment_meta"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></span>
                                                <span class="social">
							
							<?php
							
							if(!empty($cxOptions["social_fb_like"])) {
								
								if($cxOptions["social_fb_like"] == "yes") {
			
								?>
								
									<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;layout=button_count&amp;show_faces=false&amp;width=75&amp;action=like&amp;font&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:21px;" allowTransparency="true"></iframe>
								
								<?php
								
								}
								
							}
							
							?>
							
							<?php
							
							if(!empty($cxOptions["social_google_like"])) {
								
								if($cxOptions["social_google_like"] == "yes") {
			
								?>
								
									<span class="google"><g:plusone size="medium"></g:plusone></span>
									
								<?php
								
								}
								
							}
							
							?>
							
						</span>
                                        </div>
                                        
                                        <?php
                                         
                                        if(!empty($thumb)) {
                                                 
                                                 ?>
                                                 <div class="with_image">
                                                 
                                                 <img src="<?php echo $thumb;?>" />
                                                 
                                                 <?php
                                                 
                                        } else {
                                                 
                                                 echo "<div class='no_image'>";
                                                 
                                        }
                                         
                                        ?>
                                         
                                        <?php the_excerpt();?>
                                        
                                                </div>
                                </div>
                                
                        <?php endwhile; else: ?>

                                <div class="post">
                                        <h1>Error 404 - Page not found </h1>
                                        <p>The page you are looking for is not available, please type in a correct link. You may try another search:</p>
                                        <?php get_search_form(true);?>
                                </div>
                                
                        <?php endif; ?>
                        
                        <?php
        
                        get_template_part('scripts/wp-pagenavi');
                        
                        if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
                        
                        ?>
                                
                </div>
                
<?php get_sidebar(); ?>            
   
<?php get_footer(); ?>