<?php
/*
################################################################################################################
##
##     FUNCTIONS
##     Particular modules can be turned on/of commneting the lines or simple removing the functions
##
################################################################################################################
*/

//===============================================================================================================
// BASE INCLUDES AND MODULES
//===============================================================================================================

//------------------------------------------------------------------------------------------
// Startup Cleanup - Clean WP Junk
//------------------------------------------------------------------------------------------
//
// - Remove from head: 
//      - feed links
//      - wlwmanifest
//      - index_rel_link
//      - parent_post_rel_link
//      - start_post_rel_link
//      - adjacent_posts_rel_link_wp_head
//      - custom_remove_wp_ver_css_js
//      - custom_remove_wp_ver_css_js
//      - feed_links
//      - print_emoji_detection_script
//      - print_emoji_styles
//      - RSS version
//      - CSS & JS version
//  - Remove from admin:     
//      - Options for nom admin users
//      - File edit for nom admin users
//      - Admin bar from frontend
//  - Remove from front:
//      - Embed Widget
//      - Gutenberg Block Library   
//      
//------------------------------------------------------------------------------------------

include(TEMPLATEPATH . "/functions/base/startup.php");

//------------------------------------------------------------------------------------------
// Smart Includes - Allows to template parts be inserted with variables
//------------------------------------------------------------------------------------------

include(TEMPLATEPATH . "/functions/base/includes.php");

//===============================================================================================================
// THEME INCLUDES AND MODULES
//===============================================================================================================

//------------------------------------------------------------------------------------------
// ACF - Fields
//------------------------------------------------------------------------------------------

include(TEMPLATEPATH . "/functions/acf/acf_options.php");

//------------------------------------------------------------------------------------------
// Login Styling
//------------------------------------------------------------------------------------------

include(TEMPLATEPATH . "/functions/theme/login_styling.php");

//===============================================================================================================
// HELPER INCLUDES AND MODULES
//===============================================================================================================

//------------------------------------------------------------------------------------------
// Image Helpers
//------------------------------------------------------------------------------------------
// 
// - Image functions: 
//      - Get image id from url: get_image_id_from_url($image_url)
//      - Get image by id and size: get_image_by_id($id, $img_size = "medium")
//      - Get first image of post: get_first_post_image()
//      - Get image wrapped on ratio div: get_ratio_img_wrapper($id, $img_size = "medium", $lazy = false, $classes = false)
//      
//------------------------------------------------------------------------------------------

include(TEMPLATEPATH . "/functions/helpers/image.php");

//------------------------------------------------------------------------------------------
// Text Helpers
//------------------------------------------------------------------------------------------
// 
// - Text functions: 
//      - Get custom text excerpt: custom_excerpt($text, $maxchar, $end = '...')
//      - Get text reading time: get_reading_time($text)
//      - Get word count: word_count($text)
//      - Convert twitter handles to clickable links: get_twitter_handle_link($handle)
//      
//------------------------------------------------------------------------------------------

include(TEMPLATEPATH . "/functions/helpers/text.php");

//------------------------------------------------------------------------------------------
// Video Helpers
//------------------------------------------------------------------------------------------
// 
// - Youtube/Vimeo functions: 
//      - Get Video id from url: getVideoID($videoUrl)
//      - Get video thumb from url: getVideoThumb($videoUrl)
//      - Get video embed from url: getVideoEmbed($videoUrl, $videoWidth="100%", $videoHeight="100%", $autoplay = true)
//      
//------------------------------------------------------------------------------------------

include(TEMPLATEPATH . "/functions/helpers/video.php");

//------------------------------------------------------------------------------------------
// Custom Post Types Register
//------------------------------------------------------------------------------------------

// if (!class_exists('CPTRegister')):
//     include(TEMPLATEPATH . "/functions/helpers/cpt_register.php");
//     $cpt = new CPTRegister;          
//     $cpt->add_CPT(array(
//         'slug'              => 'teste',   
//         'name'              => 'Teste CPT',              
//         'capabilities'      => false,                           
//         'has_archive'       => true,                           
//         'menu'              => true,                           
//         'menu_icon'         => true,                           
//         'public'            => true,                            
//         'searchable'        => true,                           
//         'syndication'       => true,                           
//         'custom_category'   => false,                                           
//         'custom_tag'        => false,                            
//         'show_in_rest'      => true,                            
//         TODO: // not working -> 'supports'          => array('title','editor','author') 
//     ));
// endif;

//===============================================================================================================
// MANIFEST FILE
//===============================================================================================================
$manifest_file = TEMPLATEPATH . "/manifest.json";
$manifest_data = json_decode(file_get_contents($manifest_file), true);

//===============================================================================================================
// ENQUEUE CSS
//===============================================================================================================

function enqueue_css()
{
	if (!is_admin()) :

		//------------------------------------------------------------------------------------------
		// CSS: Main
		//------------------------------------------------------------------------------------------

		foreach ($GLOBALS['manifest_data'] as  $key => $entry_point) {
			if (str_contains($entry_point, '.css') && !str_contains($entry_point, 'scripts') && !str_contains($entry_point, 'cms')) {
				wp_register_style($key, get_template_directory_uri() . '/assets/' . $entry_point, array(), '1', 'all');
				wp_enqueue_style($key);
			}
		}

	endif;
}
add_action('wp_enqueue_scripts', 'enqueue_css');

//===============================================================================================================
// ENQUEUE JS
//===============================================================================================================

function enqueue_js()
{
	if (!is_admin()) :

		//------------------------------------------------------------------------------------------
		// JS: Jquery
		//------------------------------------------------------------------------------------------

		wp_deregister_script('jquery');

		//------------------------------------------------------------------------------------------
		// JS: Theme
		//------------------------------------------------------------------------------------------

		foreach ($GLOBALS['manifest_data'] as  $key => $entry_point) {
			if (str_contains($entry_point, '.js') && !str_contains($entry_point, '.js.map')) {
				wp_enqueue_script($key, get_template_directory_uri() . '/assets/' . $entry_point, array(), '1', 'all');
			}
		}

	endif;
}
add_action('wp_enqueue_scripts', 'enqueue_js');

//===============================================================================================================
// IMAGE SIZES
//===============================================================================================================

// 1x images
add_image_size('img-extra-large', '1920', '4000', false);
add_image_size('img-large', '1024', '4000', false);
add_image_size('img-medium', '768', '4000', false);
add_image_size('img-small', '320', '4000', false);

// 2x images
add_image_size('img-extra-large-2x', '3840', '8000', false);
add_image_size('img-large-2x', '2048', '8000', false);
add_image_size('img-medium-2x', '1536', '8000', false);
add_image_size('img-small-2x', '640', '8000', false);

// Thumb images
add_image_size('thumbnail-small-proportional', '450', '100', false);
add_image_size('thumbnail-medium-proportional', '450', '150', false);
add_image_size('thumbnail-large-proportional', '450', '300', false);

// Thumb crops
add_image_size('thumbnail-small-cropped', '250', '250', true);
add_image_size('thumbnail-medium-cropped', '450', '450', true);
add_image_size('thumbnail-large-cropped', '600', '600', true);

//===============================================================================================================
// ADD THEME SUPPORT
//===============================================================================================================

//------------------------------------------------------------------------------------------
// Theme Support - Title Tag
//------------------------------------------------------------------------------------------

add_theme_support('title-tag');

//------------------------------------------------------------------------------------------
// MENU SUPPORT: Add Menu support (child theme overwritable)
//------------------------------------------------------------------------------------------

if (!function_exists('medula_register_menu')) :
	function medula_register_menu()
	{
		register_nav_menu('primary', __('Main menu', 'theme-medula'));
	}
	add_action('after_setup_theme', 'medula_register_menu');
endif;

//===============================================================================================================
// LOAD TEXT DOMAIN
//===============================================================================================================

function wpinternationlizationtheme_setup()
{
	$domain = 'teste';
	// wp-content/languages/wpinternationlizationtheme/de_DE.mo
	load_theme_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain);
	// wp-content/themes/wpinternationlizationtheme/languages/de_DE.mo
	load_theme_textdomain($domain, get_stylesheet_directory() . '/languages');
	// wp-content/themes/wpinternationlizationtheme/languages/de_DE.mo
	load_theme_textdomain($domain, get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'wpinternationlizationtheme_setup');
