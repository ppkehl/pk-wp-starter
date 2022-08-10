<?php

/*
  ################################################################################################################
  ##
  ##     MISCELANEOUS FUNCTIONS
  ##     Helper functions that don't belong to any other category
  ##
  ################################################################################################################
 */

//------------------------------------------------------------------------------------------
// Get the Slug
//------------------------------------------------------------------------------------------
//    Usage Example:
//    
//    get_slug();
//
//    output: the page slug 

function get_slug() {
    $post_data = get_post($post->ID, ARRAY_A);
    $slug = $post_data['post_name'];
    return $slug;
}

function get_slug_by_ID($myID) {
    $post_data = get_post($myID, ARRAY_A);
    $slug = $post_data['post_name'];
    return $slug;
}

//------------------------------------------------------------------------------------------
// Get Post Categories
//------------------------------------------------------------------------------------------

function get_this_post_categories($separator = ", ", $include_link = false, $caps = false) {

    $post_categories = wp_get_post_categories(get_the_ID());
    $sel_cats = array();

    foreach ($post_categories as $c) {

        $cats = '';
        $cat = get_category($c);
        $cat_name = $cat->name;
        $cat_link = get_category_link($cat->term_id);

        if ($include_link == true) {
            $cats.= '<a href="' . $cat_link . '">';
        };

        $cats.= $cat_name;

        if ($include_link == true) {
            $cats.= '</a>';
        };

        $sel_cats[] = $cats;
    }

    if ($caps != false) {
        $sel_cats = array_slice($sel_cats, 0, $caps);
    }
    return implode($separator, $sel_cats);
}

//------------------------------------------------------------------------------------------
// Get Terms
//------------------------------------------------------------------------------------------

function the_post_terms($term_name, $format = 'formated') {
    $terms = get_the_terms($post->ID, $term_name);
    if ($terms && !is_wp_error($terms)) {
        $namesArray = array();
        foreach ($terms as $term) {
            $namesArray[]= $term->name;  
            if ($format == 'formated') {
                echo '<span class="term ' . $term->slug . '">' . $term->name . '</span>';
            }
        }
        if ($format != 'formated') {
            echo implode(',',$namesArray);
        }
    } else {
        return false;
    }
}

//------------------------------------------------------------------------------------------
// Get Page type
//------------------------------------------------------------------------------------------
function get_page_type() {
    global $wp_query;
    $loop = 'notfound';
    if ($wp_query->is_page) {
        $loop = is_front_page() ? 'front' : 'page';
    } elseif ($wp_query->is_home) {
        $loop = 'home';
    } elseif ($wp_query->is_single) {
        $loop = ( $wp_query->is_attachment ) ? 'attachment' : 'single';
    } elseif ($wp_query->is_category) {
        $loop = 'category';
    } elseif ($wp_query->is_post_type_archive) {
        $loop = 'post type archive';
    } elseif ($wp_query->is_tag) {
        $loop = 'tag';
    } elseif ($wp_query->is_tax) {
        $loop = 'tax';
    } elseif ($wp_query->is_archive) {
        if ($wp_query->is_day) {
            $loop = 'day';
        } elseif ($wp_query->is_month) {
            $loop = 'month';
        } elseif ($wp_query->is_year) {
            $loop = 'year';
        } elseif ($wp_query->is_author) {
            $loop = 'author';
        } else {
            $loop = 'archive';
        }
    } elseif ($wp_query->is_search) {
        $loop = 'search';
    } elseif ($wp_query->is_404) {
        $loop = 'notfound';
    }
    return $loop;
}
