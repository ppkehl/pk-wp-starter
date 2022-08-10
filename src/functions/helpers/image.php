<?php
/*
################################################################################################################
##
##     IMAGE HANDELING FUNCTIONS
##
################################################################################################################
*/

//------------------------------------------------------------------------------------------
// Retrieves the attachment ID from the file URL
//------------------------------------------------------------------------------------------

function get_image_id_from_url($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
    return $attachment[0]; 
}

//------------------------------------------------------------------------------------------
// Get image by ID
//------------------------------------------------------------------------------------------

function get_image_by_id($id, $img_size = "medium") {

    $img = wp_get_attachment_image_src($id, $img_size);
    $post_img = $img[0];
    
return $post_img;
}

//------------------------------------------------------------------------------------------
// Get First Image of post
//------------------------------------------------------------------------------------------

function get_first_post_image() {
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    $first_img = $matches[1][0];

    if (!empty($first_img)) {
        return $first_img;
    }
}

//------------------------------------------------------------------------------------------
// Responsive container wrapper
//------------------------------------------------------------------------------------------

function get_ratio_img_wrapper($id, $img_size = "medium", $lazy = false, $classes = false) {

    $img = wp_get_attachment_image_src($id, $img_size);
    //16:9 = 56.25% = calc(9 / 16 * 100%)
    if($img){
        $img_ratio = $img[2]/$img[1] * 100;

        $post_img = '<div class="ratio-container '.$classes.'" style="padding-bottom:'.$img_ratio.'%">';
        if($lazy==true){
            $post_img.= '<img class="lazyload" data-src="'.$img[0].'">';
            $post_img.= '<noscript>';
            $post_img.= '<img src="'.$img[0].'" />';
            $post_img.= '</noscript>';
        }else{
            $post_img.= '<img src="'.$img[0].'">';
        }
        $post_img.= '</div>';
    }else{
        $post_img = 'Image not found';
    }
    
return $post_img;
}

//------------------------------------------------------------------------------------------
// Render SVG
//------------------------------------------------------------------------------------------
function render_svg($icon) {
    $fullsize_path = get_template_directory_uri() . '/public/imgs/' . $icon . '.svg';
    if ($fullsize_path) {
        echo file_get_contents($fullsize_path);
    }
}