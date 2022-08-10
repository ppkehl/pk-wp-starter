<?php
/*
################################################################################################################
##
##     WORDPRESS STARTUP
##     Set Wordpress startup
##
################################################################################################################
*/

//===============================================================================================================
// Clean WP Head 
//===============================================================================================================

add_action('after_setup_theme', 'custom_cleanup', 16);

function custom_cleanup()
{
	// launching operation cleanup
	add_action('init', 'custom_head_cleanup');
	// remove WP version from RSS
	add_filter('the_generator', 'custom_rss_version');
}

function custom_head_cleanup()
{
	// category feeds
	remove_action('wp_head', 'feed_links_extra', 3);
	// post and comment feeds
	remove_action('wp_head', 'feed_links', 2);
	// EditURI link
	remove_action('wp_head', 'rsd_link');
	// windows live writer
	remove_action('wp_head', 'wlwmanifest_link');
	// index link
	remove_action('wp_head', 'index_rel_link');
	// previous link
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	// start link
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	// links for adjacent posts
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	// WP version
	remove_action('wp_head', 'wp_generator');
	// remove WP version from css
	add_filter('style_loader_src', 'custom_remove_wp_ver_css_js', 9999);
	// remove Wp version from scripts
	add_filter('script_loader_src', 'custom_remove_wp_ver_css_js', 9999);
	// remove Wp feed links
	remove_action('wp_head', 'feed_links', 2);
	// remove Wp emojis
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');
}

//===============================================================================================================
// Remove WP version
//===============================================================================================================
//------------------------------------------------------------------------------------------
// Remove WP version from RSS
//------------------------------------------------------------------------------------------

function custom_rss_version()
{
	return '';
}

//------------------------------------------------------------------------------------------
// Remove WP version from scripts
//------------------------------------------------------------------------------------------

function custom_remove_wp_ver_css_js($src)
{
	if (strpos($src, 'ver='))
		$src = remove_query_arg('ver', $src);
	return $src;
}

//===============================================================================================================
// Admin Cleanup
//===============================================================================================================
//------------------------------------------------------------------------------------------
// Remove Menu Itens for Non-admins
//------------------------------------------------------------------------------------------

if (!function_exists('my_remove_menu_pages')) {
	add_action('admin_menu', 'my_remove_menu_pages');

	function my_remove_menu_pages()
	{
		if (!current_user_can('update_core')) {
			//Menu item Posts
			remove_menu_page('edit.php');
			//Menu item Links
			remove_menu_page('link-manager.php');
			//Menu item Tools
			remove_menu_page('tools.php');
			//Menu item Users
			remove_menu_page('users.php');
			//Menu item Media
			remove_menu_page('upload.php');
			//Menu item Comments
			remove_menu_page('edit-comments.php');
			//Menu item Theme
			remove_menu_page('themes.php');
			//Menu item Plugins
			remove_menu_page('plugins.php');
			//Menu item Options
			remove_menu_page('options-general.php');
		}
	}
}

//------------------------------------------------------------------------------------------
// Disallow File Edit From Admin
//------------------------------------------------------------------------------------------
if (!current_user_can('update_core')) {
	define('DISALLOW_FILE_EDIT', true);
}

//------------------------------------------------------------------------------------------
// Remove Admin Bar
//------------------------------------------------------------------------------------------ 
show_admin_bar(false);

//===============================================================================================================
// Clean Default Shenanigans
//===============================================================================================================

//------------------------------------------------------------------------------------------ 
// Deregister Embed Widget
//------------------------------------------------------------------------------------------
function deregister_wpembed()
{
	wp_deregister_script('wp-embed');
}

add_action('wp_footer', 'deregister_wpembed');

//------------------------------------------------------------------------------------------ 
// Remove Gutenberg Block Library CSS from loading on the frontend
//------------------------------------------------------------------------------------------ 
function smartwp_remove_wp_block_library_css()
{
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-theme');
}

add_action('wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css');
