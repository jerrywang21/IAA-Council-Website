<?php $wpcx_cxOptions = get_option('cxOptions'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?> 

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>			

<div id="wrapper">
	
	<div id="topmenu">
		
		<?php
		
		if(!empty($wpcx_cxOptions["social_facebook"])) {
			
			?>
			
			<a href="<?php echo $wpcx_cxOptions["social_facebook"];?>" title="facebook" target="_blank"><img src="<?php echo get_template_directory_uri();?>/images/icon_facebook.png" /></a>
			
			<?php
			
		}
		
		if(!empty($wpcx_cxOptions["social_linkedin"])) {
			
			?>
			
			<a href="https://www.linkedin.com/e/fpf/<?php echo $wpcx_cxOptions["social_linkedin"]; ?>" title="linkedin" target="_blank"><img src="<?php echo get_template_directory_uri();?>/images/icon_linked.png" /></a>
			
			<?php
			
		}
		
		if(!empty($wpcx_cxOptions["social_twitter"])) {
			
			?>
			
			<a href="http://www.twitter.com/#!/<?php echo $wpcx_cxOptions["social_twitter"]; ?>" title="twitter" target="_blank"><img src="<?php echo get_template_directory_uri();?>/images/icon_twitter.png" /></a>
			
			<?php
			
		}
		
		if(!empty($wpcx_cxOptions["social_rss"])) {
			
			if($wpcx_cxOptions["social_rss"] == "yes") {
			
				?>
				
				<a href="<?php bloginfo('rss2_url'); ?>" title="rss" target="_blank"><img src="<?php echo get_template_directory_uri();?>/images/icon_rss.png" /></a>
				
				<?php
			
			}
			
		}
		
		?>
	
	</div>
	
	<div id="header">
			
		<div id="logoname">
			
			<?php
			
			if(!empty($wpcx_cxOptions["logo_name"])) {
			
				$wpcx_logo_name = $wpcx_cxOptions["logo_name"];
			
			} else{
				
				$wpcx_logo_name = "<div class='logo_text'>" . wpcx_cut_text(get_bloginfo('name'), 13, "") . "</div>";
				
			}
			
			if(!empty($wpcx_cxOptions["logo_file"])) {
				
				$wpcx_logo_name = "<img src='".get_template_directory_uri()."/images/logos/".$wpcx_cxOptions["logo_file"]."'/>";
				
			}
			
			?>
			
			<a href="<?php echo home_url();?>" title=""><?php echo $wpcx_logo_name; ?></a>
			
		</div>
		
		<div class="navigation">	
			<ul id="navbar">
				<?php wp_nav_menu(array('theme_location'  => 'nav_menu', 'container' => false, 'fallback_cb' => 'wpcx_default_menu')); ?>
			</ul>
			
			<div id="search">
				<form id="searchform" method="get" action="/">
				<input type="text" value="" name="s" id="searchbox" />
				<input type="submit" id="searchbutton" value="" />
				</form>	
			</div>
		</div>
	</div>