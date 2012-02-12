<?php

$wpcx_cxOptions = get_option('cxOptions');

// Set Content Width

$content_width = 600;

//Load Styles

add_action('wp_print_styles', 'wpcx_add_my_stylesheet');

function wpcx_add_my_stylesheet() {
	
	if ( !is_admin() ) {
		global $wpcx_cxOptions;
		
		if(!empty($wpcx_cxOptions['colorpicker'])) {
			$wpcx_colour = $wpcx_cxOptions['colorpicker'];
		} else {
			$wpcx_colour = false;
		}
		
		$wpcx_main_style = get_template_directory_uri() . '/style.css';
		wp_register_style('mainStyle', $wpcx_main_style);
		wp_enqueue_style('mainStyle');
		
		if($wpcx_colour) {
			$wpcx_sub_style = get_template_directory_uri() . '/style.php?color=' . $wpcx_colour;
		} else {
			$wpcx_sub_style = get_template_directory_uri() . '/style.php';
		}
		wp_register_style('wpcx_sub_style', $wpcx_sub_style);
		wp_enqueue_style('wpcx_sub_style');
	}
	
}

add_action('admin_init', 'wpcx_add_to_admin_style');

function wpcx_add_to_admin_style() {
	
	$wpcx_admin_style = get_template_directory_uri() . '/admin.css';
	wp_register_style('wpcx_admin_style', $wpcx_admin_style);
	wp_enqueue_style('wpcx_admin_style');
	
}

function wpcx_add_margin_header() {
	
	global $wpcx_cxOptions;
		
	if(!empty($wpcx_cxOptions["logo_margin"])) {

		echo "<style type='text/css'>#header { margin-top: " . $wpcx_cxOptions["logo_margin"] . "px;}</style>";

	} 
	
}

add_action('wp_head', 'wpcx_add_margin_header');

//Load JS

add_action('init', 'wpcx_add_my_scripts');

function wpcx_add_my_scripts() {
	
	if ( !is_admin() ) {
	    
		wp_register_script('wpcx_jquery.cycle', get_template_directory_uri() . '/scripts/jquery.cycle.all.js', array('jquery'), '1.0' );
		wp_enqueue_script('wpcx_jquery.cycle');
		wp_register_script('wpcx_jquery.superfish', get_template_directory_uri() . '/scripts/jquery.superfish.js', array('jquery'), '1.0' );
		wp_enqueue_script('wpcx_jquery.superfish');
		wp_register_script('wpcx_jquery.fredsel', get_template_directory_uri() . '/scripts/jquery.carouFredSel-2.3.1.js', array('jquery'), '1.0' );
		wp_enqueue_script('wpcx_jquery.fredsel');
		wp_register_script('wpcx_jquery.fancybox', get_template_directory_uri() . '/scripts/jquery.fancybox-1.3.4.pack.js', array('jquery'), '1.0' );
		wp_enqueue_script('wpcx_jquery.fancybox');
		wp_register_script('wpcx_custom', get_template_directory_uri() . '/scripts/custom.js', array('jquery'), '1.0' );
		wp_enqueue_script('wpcx_custom');
	    
	} else {
	    
		wp_register_script('wpcx_jquery.picker', get_template_directory_uri() . '/scripts/jquery.picker.js', array('jquery'), '1.0' );
		wp_enqueue_script('wpcx_jquery.picker');
		wp_register_script('wpcx_admin_custom', get_template_directory_uri() . '/scripts/admin_custom.js', array('jquery'), '1.0' );
		wp_enqueue_script('wpcx_admin_custom');
	    
	}
}

// Custom Logo Support

define( 'HEADER_IMAGE', '%s/images/logo.png' ); 

define( 'HEADER_IMAGE_WIDTH', apply_filters( '', 520 ) );

define( 'HEADER_IMAGE_HEIGHT', apply_filters( '', 155 ) );

define( 'HEADER_TEXTCOLOR', '' );

define( 'NO_HEADER_TEXT', true );

add_custom_image_header( '', 'wpcx_admin_header_style' ); 

//Add custom post type "Portfolio"

function wpcx_post_type_myportfolio() {
	
register_post_type(
	'myportfolio',
	array('label' => 'Portfolio',
		'singular_label' => 'Portfolio',
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array("slug" => "portfolio-item", "with_front" => false),
		'supports' => array('title','editor','custom-fields','revisions','thumbnail'),
		'menu_position' => 4
	)
);
	register_taxonomy( 'portfolio', 'myportfolio', array( 'hierarchical' => true, 'label' => 'Portfolio Categories' ) );
	
}

add_action('init','wpcx_post_type_myportfolio');

if (!function_exists( 'wpcx_admin_header_style' )) {
	
	function wpcx_admin_header_style() { ?>

		<style type="text/css">
		
		#headimg {
			height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
			width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
		}
		
		#headimg h1, #headimg #desc {
			display: none;
		}
		
		</style>
	<?php }
}

//WP 3.0 Menu

add_action('init', 'wpcx_register_custom_menu');
 
function wpcx_register_custom_menu() {
	register_nav_menu('footer_menu', 'Footer Menu');
	register_nav_menu('nav_menu', 'Navigation Menu');
}

//Remove wp_nav wrapping

function wpcx_my_nav_unlister( $menu ){
	return preg_replace( array( '#^<ul[^>]*>#', '#</ul>$#' ), '', $menu );
}

add_filter( 'wp_nav_menu', 'wpcx_my_nav_unlister' );

// Fallback Nav Menus

function wpcx_default_menu() {
	
	echo "<li><a href='" . home_url() . "/'>Home</a></li>";

}

// Cut some text

function wpcx_cut_text($text, $chars, $points = "...") {
	$length = strlen($text);
	if($length <= $chars) {
		return $text;
	} else {
		return substr($text, 0, $chars)." ".$points;
	}
}

// Get WP Generated thumb

function wpcx_get_wp_generated_thumb($position) {
	global $post_id;
	$thumb = get_the_post_thumbnail($post_id, $position);
	$thumb = explode("\"", $thumb);
	if(!empty($thumb[5])) {
		return $thumb[5];
	}
}

if (function_exists('add_image_size')) { 
	add_image_size( 'slideshow', 480, 210, true ); 
	add_image_size( 'slideshow_thumb', 100, 75, true );
	add_image_size( 'reference', 250, 130, true );
	add_image_size( 'feat_thumb', 295, 150, true ); 
}

// Styling & Script Functions

function wpcx_script() {
	
	global $wpcx_cxOptions;
	
	if(!empty($wpcx_cxOptions["slide_effect"])) {
		
		$wpcx_slide_effect = $wpcx_cxOptions["slide_effect"];
		
	} else {
		
		$wpcx_slide_effect = "scrollLeft";
		
	}
	
	if(!empty($wpcx_cxOptions["slide_speed"])) {
		
		$wpcx_slide_speed = $wpcx_cxOptions["slide_speed"];
		
	} else {
		
		$wpcx_slide_speed = 300;
		
	}
	
	if(!empty($wpcx_cxOptions["slide_duration"])) {
		
		$wpcx_slide_duration = $wpcx_cxOptions["slide_duration"];
		
	} else {
		
		$wpcx_slide_duration = 8000;
		
	}
	
	
	
	$wpcx_script = '<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
	<script type="text/javascript">
			jQuery(document).ready(function($) {
				jQuery("ul#slideshow").cycle({
					fx:		"'.$wpcx_slide_effect.'",
					speed:		'.$wpcx_slide_speed.',
					timeout: 	'.$wpcx_slide_duration.',
					pager:  	"ul#slide_navigation", 
			pagerAnchorBuilder: function(idx, slide) { 
			    // return selector string for existing anchor 
			    return "#slide_navigation li:eq(" + idx + ") a"; 
			} 
		});
	
	});

	</script>';
	
	echo $wpcx_script;
	
}

add_action('wp_head', 'wpcx_script');

function wpcx_style() {
	
	$wpcx_style = "";
	
	global $wpcx_cxOptions;
	
	if(!empty($wpcx_cxOptions["logo_size"])) {
		
		$wpcx_logo_size = $wpcx_cxOptions["logo_size"];
		
		$style = '<style type="text/css">
		#logoname a {
		font-size: '.$wpcx_logo_size.'px !important;
		}
		</style>
		';
		
	} 

	if(empty($wpcx_cxOptions["logo_file"])) {
		
		
		$wpcx_style .= '<style type="text/css">
		#logoname {
		margin-top: 12px;
		}
		</style>
		';

	}
	
	if(!empty($wpcx_style)) {
		
		echo $wpcx_style;
	
	}
	
}

add_action('wp_head', 'wpcx_style');

function wpcx_list_pages() {
	
	echo "<li><a href='".get_bloginfo('url')."'>Home</a>";
	wp_list_pages('title_li=');
	
}

// Featured Options

add_action("admin_init", "wpcx_admin_init");
add_action('save_post', 'wpcx_save_feat');

function wpcx_admin_init(){
	add_meta_box("page_feat", "Creativix Options", "wpcx_feat_options", "page", "normal", "high");
	add_meta_box("post_feat", "Creativix Options", "wpcx_feat_options", "post", "normal", "high");
}

function wpcx_feat_options(){
	
	global $post;
	$wpcx_custom = get_post_custom($post->ID);
	if(!empty($wpcx_custom)) {
		if(!empty($wpcx_custom["feat_slideshow"][0])) {
			$wpcx_featured = $wpcx_custom["feat_slideshow"][0];
		}
		if(!empty($wpcx_custom["feat_front"][0])) {
			$wpcx_feat_front = $wpcx_custom["feat_front"][0];
		}
	}
?>
	<div class="inside">
		<table class="form-table">
			<tr>
				<th><label for="featured">Feature in Slideshow?</label></th>
				<td><input type="checkbox" name="featured" value="1" <?php if(isset($wpcx_featured) && $wpcx_featured == 1) { echo "checked='checked'";} ?></td>
			</tr>
			<tr>
				<th><label for="featured">Feature on frontpage?</label></th>
				<td><input type="checkbox" name="feat_front" value="1" <?php if(isset($wpcx_feat_front) && $wpcx_feat_front == 1) { echo "checked='checked'";} ?></td>
			</tr>
		</table>
	</div>
<?php
}

function wpcx_save_feat(){
	
	global $post;
	
	if($post->post_type == "post" OR $post->post_type == "page") {
	
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;
		
		
		if(isset($_POST["featured"])) {
			
			update_post_meta($post->ID, "feat_slideshow", $_POST["featured"]);
			
		} else {
			
			delete_post_meta($post->ID, "feat_slideshow", '');
			
		}
		
		if(isset($_POST["feat_front"])) {
			
			update_post_meta($post->ID, "feat_front", $_POST["feat_front"]);
			
		} else {
			
			delete_post_meta($post->ID, "feat_front", '');
			
		}
	
	}
	
} 


//Check for Post Thumbnail Support

if ( function_exists( 'add_theme_support' ) )
add_theme_support( 'post-thumbnails' );

// Register Sidebar

if ( function_exists('register_sidebar') )
    register_sidebar();

// Add RSS

add_theme_support('automatic-feed-links');

// Custom BG

add_custom_background();

// Editor Style

add_editor_style();

//Loading Theme Options Framework

$wpcx_themename = "WP-Creativix";
$wpcx_shortname = "Creativix";

$wpcx_options = array (
	
array( "type" => "open",
"title" => "Frontpage Settings"
),

array( "name" => "Activate Slideshow on Frontpage?",
       "desc" => "Decide if you want to show the big Slideshow on Frontpage. By default the Slideshow will be shown.",
       "id" => "slide_show",
       "type" => "selectnormal",
       "options" => array("yes", "no"),
       "std" => "yes"),

array( "name" => "Limit Posts/Pages in Slideshow",
       "desc" => "Set maximum amount of posts/pages to be shown in the slideshow. Leave empty if you don't want to limit them.",
       "id" => "slide_max",
       "type" => "text",
       "std" => ""),

array( "name" => "Sort Posts/Pages in Slideshow",
       "desc" => "Choose how you want to sort your posts/pages in the Slideshow. Default sort order is by date",
       "id" => "slide_sort",
       "type" => "selectnormal",
       "options" => array("post_date", "rand", "title"),
       "std" => "post_date"),

array( "name" => "Order Posts/Pages in Slideshow",
       "desc" => "Choose how you want to order your posts/pages in the Slideshow. Default order is DESC",
       "id" => "slide_order",
       "type" => "selectnormal",
       "options" => array("DESC", "ASC"),
       "std" => "DESC"),

array( "name" => "Choose Sliding Effect",
       "desc" => "Choose an effect that will be applied for the Slideshow. Defaul ist scrollRight.",
       "id" => "slide_effect",
       "type" => "selectnormal",
       "options" => array("scrollRight", "fade", 'scrollUp', 'scrollDown', 'blindZ', 'fadeZoom'),
       "std" => "scrollRight"),

array( "name" => "Slideshow Transition Speed",
       "desc" => "Set the Speed for the slideshow's transition. Default ist set to 500 (ms)",
       "id" => "slide_speed",
       "type" => "text",
       "std" => "500"),

array( "name" => "Slideshow Duration",
       "desc" => "Set duration for each single Slide Item. Default is set to 3000 (ms)",
       "id" => "slide_duration",
       "type" => "text",
       "std" => "3000"),

array( "name" => "Sort Posts/Pages for Featured Posts on Frontage",
       "desc" => "Choose how you want to sort your posts/pages in the Featured Area on Frontage. Default sort order is by date",
       "id" => "featured_sort",
       "type" => "selectnormal",
       "options" => array("post_date", "rand", "title"),
       "std" => "post_date"),

array( "name" => "Order Posts/Pages for Featured Posts on Frontpage",
       "desc" => "Choose how you want to order your posts/pages in the Featured Area. Default order is DESC",
       "id" => "featured_order",
       "type" => "selectnormal",
       "options" => array("DESC", "ASC"),
       "std" => "DESC"),

array( "name" => "Sort Posts for Latest Articles",
       "desc" => "Choose how you want to sort your latest Articles displayed next to featured Articles. Default sort order is by date",
       "id" => "articles_sort",
       "type" => "selectnormal",
       "options" => array("date", "rand", "title"),
       "std" => "date"),

array( "name" => "Order Posts for Latest Articles",
       "desc" => "Choose how you want to order your latest Articles displayed next to featured Articles. Default order is DESC",
       "id" => "articles_order",
       "type" => "selectnormal",
       "options" => array("DESC", "ASC"),
       "std" => "DESC"),

array("type" => "close"),

array( "type" => "open",
	"title" => "Styling Settings"
	),

array( "name" => "Logo Font Size",
       "desc" => "Choose a font size for the Logo. Default font size is set to 26px.",
       "id" => "logo_size",
       "type" => "text",
       "std" => "26"),

array( "name" => "Logo Text",
       "desc" => "Choose text for the logo. Leave this empty to use your Wordpress Blog Title instead.",
       "id" => "logo_name",
       "type" => "text",
       "std" => ""),

array( "name" => "Logo/Nav Margin",
       "desc" => "Choose how many space should be between Social Menu and Navigation/Logo in px.",
       "id" => "logo_margin",
       "type" => "text",
       "std" => ""),

array( "name" => "Logo Upload",
       "desc" => "Upload your own Logo. The Logo will not be cropped/resized. You must apply correct width/height before uploading. (Folder wp-creativix/images/logos/ must be writeable)",
       "id" => "logo_file",
       "type" => "upload",
       "std" => ""),

array( "name" => "Choose an highlight Colour",
       "desc" => "Click on the input field and choose a highlight colour with the help of the colour-picker.",
       "id" => "colorpicker",
       "type" => "text",
       "std" => "939393"),

array("type" => "close"),

array( "type" => "open",
	"title" => "Social Media Settings"
	),

array( "name" => "Facebook Profile URL",
       "desc" => "If you want to show the icon, enter the URL (http://..) that points to your Facebook profile. (Leave empty to not show the icon)",
       "id" => "social_facebook",
       "type" => "text",
       "std" => ""),

array( "name" => "Linkedin Profile ID",
       "desc" => "Insert your Linkedin Profile ID to show the Linkedin Icon in the Header. (Leave empty to not show the icon)",
       "id" => "social_linkedin",
       "type" => "text",
       "std" => ""),

array( "name" => "Twitter Username",
       "desc" => "Insert your Twitter Username to show the Twitter Icon in the Header. (Leave empty to not show the icon)",
       "id" => "social_twitter",
       "type" => "text",
       "std" => ""),

array( "name" => "Show RSS Icon in Header?",
       "desc" => "Choose if you want to display the RSS Icon in the Header. Default is set to yes.",
       "id" => "social_rss",
       "type" => "selectnormal",
       "options" => array("yes", "no"),
       "std" => "yes"),

array( "name" => "Let Users like posts on Facebook",
       "desc" => "Choose if you want to display the facebook-like-button next to your Post. Default is set to yes.",
       "id" => "social_fb_like",
       "type" => "selectnormal",
       "options" => array("yes", "no"),
       "std" => "yes"),

array( "name" => "Let Users +1 your Posts",
       "desc" => "Choose if you want to display the Google +1 Button next to your Post. Default is set to yes.",
       "id" => "social_google_like",
       "type" => "selectnormal",
       "options" => array("yes", "no"),
       "std" => "yes"),

array("type" => "close"),

array( "type" => "open",
	"title" => "Portfolio Settings"
	),

array( "name" => "Sort Portfolio Items by",
       "desc" => "Choose how you want to sort your Portfolio items on your Portfolio Page. Default sort order is by date",
       "id" => "portfolio_sort",
       "type" => "selectnormal",
       "options" => array("date", "rand", "title"),
       "std" => "date"),

array( "name" => "Order Portfolio Items",
       "desc" => "Choose how you want to order your Portfolio items on your Portfolio Page. Default order is DESC",
       "id" => "portfolio_order",
       "type" => "selectnormal",
       "options" => array("DESC", "ASC"),
       "std" => "DESC"),

array( "name" => "Items per Page?",
       "desc" => "How many Portfolio Items would you like to display per Page? Default is set to 9",
       "id" => "portfolio_page",
       "type" => "text",
       "std" => "9"),

array("type" => "close"),

array( "type" => "open",
	"title" => "Blog Settings"
	),

array( "name" => "Sort Blog Items by",
       "desc" => "Choose how you want to sort your Blog items on your Blog Page. Default sort order is by date",
       "id" => "blog_sort",
       "type" => "selectnormal",
       "options" => array("date", "rand", "title"),
       "std" => "date"),

array( "name" => "Order Blog Items",
       "desc" => "Choose how you want to order your Blog items on your Blog Page. Default order is DESC",
       "id" => "blog_order",
       "type" => "selectnormal",
       "options" => array("DESC", "ASC"),
       "std" => "DESC"),

array( "name" => "Items per Page?",
       "desc" => "How many Blog Items would you like to display per Page? Default is set to 10",
       "id" => "blog_page",
       "type" => "text",
       "std" => "10"),

array("type" => "close")

);

// create the Options page on the admin side

function wpcx_add_admin() {

    global $wpcx_themename, $wpcx_shortname, $wpcx_options;

	// Saving and Updating the options
	
	if (!empty($_GET['page']) && $_GET['page'] == basename(__FILE__) && !empty($_POST) && check_admin_referer('wpcx_save_theme_options','wpcx_options_nonce')) { 
		
		if (!empty($_REQUEST['action']) && 'save' == $_REQUEST['action']) {
			
			$wpcx_cxOptions = array();
			
			// print_r($_REQUEST);
			
			foreach ($wpcx_options as $wpcx_value) {
				
				if (!empty($wpcx_value['id'])) {
					
					if(isset($_REQUEST[$wpcx_value['id']])) {
					
						$wpcx_cxOptions[$wpcx_value['id']] = esc_attr($_REQUEST[ $wpcx_value['id'] ]);
						
						if(strpos($wpcx_cxOptions[$wpcx_value['id']], "http") !== false) {
							
							$wpcx_cxOptions[$wpcx_value['id']] = esc_url($_REQUEST[ $wpcx_value['id'] ]);
							
						}
					
					}
				}
				
			}
			
			// Logo Upload
			
			if(!empty($_FILES["logo_file"])) {
			
				// Upload Logo
				
				$wpcx_dir = TEMPLATEPATH . "/images/logos/";
				
				if (is_writable($wpcx_dir)) {
					
					if ((($_FILES["logo_file"]["type"] == "image/gif") || ($_FILES["logo_file"]["type"] == "image/jpeg") || ($_FILES["logo_file"]["type"] == "image/png") || ($_FILES["logo_file"]["type"] == "image/pjpeg")) && ($_FILES["logo_file"]["size"] < 1048576)) {
						
						if ($_FILES["logo_file"]["error"] > 0){
							echo "Return Code: " . $_FILES["logo_file"]["error"] . "<br />";
						} else {
							$_FILES["logo_file"]["name"] = str_replace(' ', '_' , $_FILES["logo_file"]["name"]);
							if (file_exists($wpcx_dir . $_FILES["logo_file"]["name"])) {
								echo $_FILES["logo_file"]["name"] . " already exists. ";
							} else {
								switch($_FILES["logo_file"]["type"]) {
									case "image/jpeg" : $wpcx_end = ".jpg";
									break;
									case "image/png" : $wpcx_end = ".png";
									break;
									case "image/gif" : $wpcx_end = ".gif";
									break;
								}
								$wpcx_newname = time().$wpcx_end;
								
								if(move_uploaded_file($_FILES["logo_file"]["tmp_name"], $wpcx_dir . $wpcx_newname)) {
									
									$wpcx_cxOptions['logo_file'] = $wpcx_newname;
									
								}
								
							}
						}
					}
				}
			
			}
			
			if(!empty($_REQUEST['del_pic'])) {
				
				$wpcx_cxOptions['logo_file'] = "";
				
			}
			
			update_option('cxOptions', $wpcx_cxOptions);
		
			if (!empty($wpcx_value['id']) && isset($_REQUEST[ $wpcx_value['id']])) {
				
				update_option('cxOptions', $wpcx_cxOptions);
				
			} elseif(!empty($wpcx_value['id'])) {
				
				delete_option( $wpcx_value['id'] );
				
			}
			
			header("Location: themes.php?page=functions.php&saved=true");
			
		} elseif(!empty($_REQUEST['action']) && 'reset' == $_REQUEST['action']) {
			
			delete_option('cxOptions');
			
			header("Location: themes.php?page=functions.php&reset=true");
			
		}				
			
}
		

    // Add Options page to the admin menu
    
    add_theme_page($wpcx_themename." Options", "$wpcx_themename Options", 'edit_theme_options', basename(__FILE__), 'wpcx_admin');
    
}
 
function wpcx_admin() {

	global $wpcx_themename, $wpcx_shortname, $wpcx_options;
    
	?>
	
	<div class="wrap">
		<div style="float: left; margin-top: 50px;">
			<h1><img src="<?php echo get_template_directory_uri();?>/images/logo.gif" /></h1>
		</div>
		
		<div style="float: left; margin-left: 150px;">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_donations">
				<input type="hidden" name="business" value="dennis.nissle@iwebix.de">
				<input type="hidden" name="lc" value="US">
				<input type="hidden" name="item_name" value="WP-Creativix Theme Donation">
				<input type="hidden" name="currency_code" value="USD">
				<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
				<input type="image" src="<?php echo get_template_directory_uri(); ?>/images/donate.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
		</div>
			    
		<h2 style="clear: both; margin-left: 5px; margin-bottom: 20px;">WP-Creativix Options Page</h2>
		
		<?php 
			if ( !empty($_REQUEST['saved']) && $_REQUEST['saved'] ) echo '<div id="message" style="float: left; width: 655px; margin-left: 10px;" class="updated fade"><p><strong>'.$wpcx_themename.' settings saved.</strong></p></div>';
			if ( !empty($_REQUEST['reset']) && $_REQUEST['reset'] ) echo '<div id="message" style="float: left; width: 655px; margin-left: 10px;" class="updated fade"><p><strong>'.$wpcx_themename.' settings reset.</strong></p></div>';
		?>
	    
		<form method="post" enctype="multipart/form-data">
		
		<?php
		
		//print_r($options);
		
		foreach ($wpcx_options as $wpcx_value) {
			
			$wpcx_cxOptions = get_option('cxOptions');
			
			switch ($wpcx_value['type']) {
			
				case "open": ?>
					<div class="container" style="clear: both;background-color: #e8e8e8; border: 1px solid #CCC; padding: 10px; font-size: 11px; width: 650px; margin: 10px; float: left; color: #3b3b3b;">
					<h3><?php echo $wpcx_value['title']; ?></h3>
				<?php break;
	     
				case "close": ?>
					</div>
				<?php break;
	      
				case 'text': ?>
					<div style="margin-top:15px; float: left; clear: both;">
						<?php echo $wpcx_value['name']; ?><br/>
						<input name="<?php echo $wpcx_value['id']; ?>" id="<?php echo $wpcx_value['id']; ?>" type="<?php echo $wpcx_value['type']; ?>" value="<?php if ( !empty($wpcx_cxOptions[$wpcx_value['id']])) { echo esc_attr($wpcx_cxOptions[$wpcx_value['id']]); } else { echo $wpcx_value['std']; } ?>" />
					</div>
					<div style="width: 350px; float: right; margin-right: 20px; margin-top: 15px; background-color: #fff0b5; border: 4px solid #FFF; padding: 5px; font-size: 10px;">
						<?php echo $wpcx_value['desc']; ?>
					</div>
				<?php break;
	     
				case 'textarea': ?>
				
					<div style="margin-top:15px;padding:0; float: left; clear: both;">
						<?php echo $wpcx_value['name']; ?><br/>
						<textarea style="width: 200px; height:70px; font-size: 10px; border: 1px solid #b6b6b6;" name="<?php echo $wpcx_value['id']; ?>" id="<?php echo $wpcx_value['id']; ?>" type="<?php echo $wpcx_value['type']; ?>" cols="" rows=""><?php if ( !empty($wpcx_cxOptions[$wpcx_value['id']])) { echo esc_attr($wpcx_cxOptions[$wpcx_value['id']]); } else { echo $wpcx_value['std']; } ?></textarea>
					</div>
					<div style="width: 350px; float: right; margin-right: 20px; margin-top: 25px; background-color: #fff0b5; border: 4px solid #FFF; padding: 5px; font-size: 10px;">
						<?php echo $wpcx_value['desc']; ?>
					</div>   
				<?php break;
				
				case 'upload': ?>
				
					<div style="margin-top:15px;padding:0; float: left; clear: both;">
						<?php echo $wpcx_value['name']; ?><br/>
						<input type="file" name="<?php echo $wpcx_value['id'];?>" id="<?php echo $wpcx_value['id'];?>" />
					</div>
					<div style="width: 350px; float: right; margin-right: 20px; margin-top: 25px; background-color: #fff0b5; border: 4px solid #FFF; padding: 5px; font-size: 10px;">
						<?php echo $wpcx_value['desc']; ?>
					</div>
					<?php if ( !empty($wpcx_cxOptions[$wpcx_value['id']])) {
						echo "<div class='logo' style='float: left; clear: both;'><img style='float: left; clear: both;' src='".get_template_directory_uri()."/images/logos/".$wpcx_cxOptions[$wpcx_value['id']]."'/><p style='float: left; clear: both;'><input type='checkbox' name='del_pic' value='del' />Delete Logo</p><input type='hidden' name='logo_file' value='".$wpcx_cxOptions[$wpcx_value['id']]."' /></div>";
					}
					?>
				<?php break;
	     
				case 'selectnormal': ?>
					<div style="margin-top:15px; padding:0; float: left; clear: both;">
						<?php echo $wpcx_value['name']; ?><br/>
						<select name="<?php echo $wpcx_value['id']; ?>" id="<?php echo $wpcx_value['id']; ?>"><?php foreach ($wpcx_value['options'] as $wpcx_option) { ?><option<?php if(!empty($wpcx_cxOptions[$wpcx_value['id']]) &&$wpcx_cxOptions[$wpcx_value['id']] == $wpcx_option) { echo ' selected="selected"'; } elseif ($wpcx_option == $wpcx_value['std']) { echo ' selected="selected"'; } ?>><?php echo $wpcx_option; ?></option><?php } ?></select>
					</div>
					<div style="width: 350px; float: right; margin-right: 20px; margin-top: 15px; background-color: #fff0b5; border: 4px solid #FFF; padding: 5px; font-size: 10px;">
						<?php echo $wpcx_value['desc']; ?>
					</div>
				 <?php break;
		    
				case "checkbox": ?>
					<div style="margin-top:15px; padding:0; float: left; clear: both;">
						<?php echo $wpcx_value['name']; ?><br/>
						<?php if(get_option($wpcx_value['id'])){ $wpcx_checked = "checked=\"checked\""; }else{ $wpcx_checked = "";} ?>
						<input type="checkbox" name="<?php echo $wpcx_value['id']; ?>" id="<?php echo $wpcx_value['id']; ?>" value="true" <?php echo $wpcx_checked; ?> />
					</div>
					<div style="width: 350px; float: right; margin-right: 20px; margin-top: 15px; background-color: #fff0b5; border: 4px solid #FFF; padding: 5px; font-size: 10px;">
						<?php echo $wpcx_value['desc']; ?>
					 </div>
			    <?php break; 
			}
		}
		
	?>
	
		<div class="container" style="clear: both; background-color: #e8e8e8; border: 1px solid #CCC; padding: 10px; font-size: 11px; width: 650px; margin: 10px; float: left; color: #3b3b3b;"> 
			<p class="submit" style="float: left; margin-right: 20px;">
				<input name="save" type="submit" value="Save changes" />
				<input type="hidden" name="action" value="save" />
			</p>
			<?php wp_nonce_field('wpcx_save_theme_options','wpcx_options_nonce'); ?>
		</form>
		<form method="post">
			<p class="submit">
				<input name="reset" type="submit" value="Reset" />
				<input type="hidden" name="action" value="reset" />
			</p>
		</form>
	</div>
	
	<?php

}

add_action('admin_menu', 'wpcx_add_admin');

?>