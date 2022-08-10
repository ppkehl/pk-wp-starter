<?php
/*
 ################################################################################################################
 ##
 ##     CUSTOM POST TYPES REGISTER
 ##     Version 0.9
 ##     Register post types
 ##
 ################################################################################################################
 */

class CPTRegister
{

    //------------------------------------------------------------------------------------------
    // Init Variables
    //------------------------------------------------------------------------------------------

    private $cpt_all_args;
    private $cpt_slug;
    private $cpt_name;
    private $cpt_cat;
    private $cpt_tag;
    private $cpt_caps;
    private $cpt_menu;
    private $cpt_archive;
    private $cpt_public;
    private $cpt_exclude_search;
    private $cpt_menu_icon;
    private $cpt_supports;
    private $cpt_rest;

    private $cpt_labels = array();
    private $cpt_args = array();

    public $registered_cpts = array();

    //------------------------------------------------------------------------------------------
    // Add Fields
    //------------------------------------------------------------------------------------------

    public function add_CPT($args)
    {

        $this->cpt_all_args = $args;

        // CPT Names
        $this->cpt_slug = $args['slug'];
        $this->cpt_name = $args['name'];

        // CPT Defaults
        $this->cpt_cat = $args['custom_category'];
        $this->cpt_tag = $args['custom_tag'];
        $this->cpt_caps = $args['capabilities'];
        $this->cpt_menu = $args['menu'] ? true : $args['menu'];
        $this->cpt_archive = $args['has_archive'] ? true : $args['has_archive'];
        $this->cpt_public = $args['public'] ? true : $args['public'];

        if ($args['show_in_rest']) {
            $this->cpt_rest = $args['show_in_rest'];
        } else {
            $this->cpt_rest = true;
        }
        if ($args['supports']) {
            $this->cpt_supports = $args['supports'];
        } else {
            $this->cpt_supports = array(
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'trackbacks',
                'custom-fields',
                'comments',
                'revisions',
                'page-attributes',
                'post-formats'
            );
        }
        if ($args['searchable'] == true) {
            $this->cpt_exclude_search = false;
        } else if ($args['searchable'] == false) {
            $this->cpt_exclude_search = true;
        } else {
            $this->cpt_exclude_search = false;
        }
        if ($args['menu_icon'] && $args['menu_icon'] != false) {
            $this->cpt_menu_icon = get_stylesheet_directory_uri() . $args['menu_icon'];
        } else {
            $this->cpt_menu_icon = false;
        }
        // Add Labels
        $this->add_CPT_Labels();
    }

    //------------------------------------------------------------------------------------------
    // CPT Labels
    //------------------------------------------------------------------------------------------

    private function add_CPT_Labels()
    {
        // CPT Labels
        $this->cpt_labels = array(
            'name'                  => __($this->cpt_name, 'theme_medula'),
            'singular_name'         => __($this->cpt_name, 'theme_medula'),
            'add_new'               => __('Add new item', 'theme_medula'),
            'add_new_item'          => __('Add new item', 'theme_medula'),
            'edit_item'             => __('Edit item', 'theme_medula'),
            'new_item'              => __('New item', 'theme_medula'),
            'view_item'             => __('View item', 'theme_medula'),
            'search_items'          => __('Search item', 'theme_medula'),
            'not_found'             => __('Nothing found', 'theme_medula'),
            'not_found_in_trash'    => __('Nothing found in trash', 'theme_medula'),
            'parent_item_colon'     => ''
        );
        // Add Args 
        $this->add_CPT_args();
    }

    //------------------------------------------------------------------------------------------
    // CPT Arguments
    //------------------------------------------------------------------------------------------

    private function add_CPT_args()
    {
        // CPT Arguments
        $this->cpt_args = array(
            'labels'                => $this->cpt_labels,
            'public'                => $this->cpt_public,
            'publicly_queryable'    => $this->cpt_public,
            'show_ui'               => $this->cpt_menu,
            'query_var'             => true,
            'menu_icon'             => $this->cpt_menu_icon,
            'rewrite'               => array(
                'slug'              => $this->cpt_slug,
                'with_front'        => false
            ),
            'hierarchical'          => false,
            'show_in_menu'          => true,
            'menu_position'         => 10,
            'supports'              => $this->cpt_supports,
            'has_archive'           => $this->cpt_archive,
            'exclude_from_search'   => $this->cpt_exclude_search,
            'show_in_rest'          => true,
            'rest_base'             => $this->cpt_slug,
        );

        if ($this->cpt_caps == true) {
            $this->add_CPT_capabilities();
        } else {
            $this->register_CPT();
        }
    }

    //------------------------------------------------------------------------------------------
    // CPT Capabilities
    //------------------------------------------------------------------------------------------
    private function add_CPT_capabilities()
    {

        // CPT Capabilities
        $this->cpt_args[] = array(
            'capability_type'       => $this->cpt_slug,
            'capabilities'          => array(
                'read_post'             => 'read_' . $this->cpt_slug,                 // Level 1
                'edit_post'             => 'edit_' . $this->cpt_slug,                 // Level 1
                'edit_posts'            => 'edit_' . $this->cpt_slug . 's',           // Level 1 					
                'publish_posts'         => 'publish_' . $this->cpt_slug . 's',        // Level 2
                'delete_post'           => 'delete_' . $this->cpt_slug,               // Level 2
                'edit_others_posts'     => 'edit_others_' . $this->cpt_slug . 's',    // Level 3
                'delete_posts'          => 'delete_' . $this->cpt_slug . 's',         // Level 3
                'delete_others_posts'   => 'delete_others_' . $this->cpt_slug . 's',  // Level 3 	
                'read_private_posts'    => 'read_private_' . $this->cpt_slug . 's',   // Level 3
            )
        );
        $this->register_CPT();
    }

    //------------------------------------------------------------------------------------------
    // CPT Category
    //------------------------------------------------------------------------------------------

    public function add_CPT_category($slug)
    {

        $labels = array(
            'name'              => __('Categories', 'theme_medula'),
            'singular_name'     => __('Category', 'theme_medula'),
            'search_items'      => __('Search categories', 'theme_medula'),
            'all_items'         => __('All categories', 'theme_medula'),
            'parent_item'       => __('Parent category', 'theme_medula'),
            'parent_item_colon' => __('Parent category:', 'theme_medula'),
            'edit_item'         => __('Edit category', 'theme_medula'),
            'update_item'       => __('Update category', 'theme_medula'),
            'add_new_item'      => __('Add new category', 'theme_medula'),
            'new_item_name'     => __('New category', 'theme_medula'),
            'menu_name'         => __('Category', 'theme_medula'),
        );

        register_taxonomy('cat_' . $slug, array($slug), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'categoria'),
        ));
    }

    //------------------------------------------------------------------------------------------
    // CPT Tag
    //------------------------------------------------------------------------------------------

    public function add_CPT_tag($slug)
    {

        $labels = array(
            'name'              => __('Tags', 'theme_medula'),
            'singular_name'     => __('Tag', 'theme_medula'),
            'search_items'      => __('Search tags', 'theme_medula'),
            'all_items'         => __('All tags', 'theme_medula'),
            'edit_item'         => __('Edit tags', 'theme_medula'),
            'update_item'       => __('Update tag', 'theme_medula'),
            'add_new_item'      => __('Add new tag', 'theme_medula'),
            'new_item_name'     => __('New tag', 'theme_medula'),
            'menu_name'         => __('Tags', 'theme_medula'),
        );

        register_taxonomy('tag_' . $slug, array($slug), array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'tag'),
        ));
    }

    //------------------------------------------------------------------------------------------
    // Register CPT
    //------------------------------------------------------------------------------------------

    private function register_CPT()
    {

        $this->registered_cpts[] = $this->cpt_all_args;

        register_post_type($this->cpt_slug, $this->cpt_args);

        if ($this->cpt_cat == true) {
            $this->add_CPT_category($this->cpt_slug);
        }

        if ($this->cpt_tag == true) {
            $this->add_CPT_tag($this->cpt_slug);
        }
    }

    //------------------------------------------------------------------------------------------
    // Return Registered CPTs
    //------------------------------------------------------------------------------------------

    public function get_CPTs()
    {

        return $this->registered_cpts;
    }
}
