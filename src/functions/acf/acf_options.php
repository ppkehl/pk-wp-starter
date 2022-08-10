<?php
/*
################################################################################################################
##
##     ADVANCED CUSTOM FIELDS CONFIGURATIONS
##     (require Advanced Custom Fields enabled)
##
################################################################################################################
*/

//===============================================================================================================
// Register options page
//===============================================================================================================
if (function_exists("register_options_page")) {
    register_options_page('Globais');
};

//===============================================================================================================
// Register json save path
//===============================================================================================================
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
 function my_acf_json_save_point( $path ) {
    $path = get_stylesheet_directory() . '/functions/acf';
    return $path;
}

//===============================================================================================================
// Load json save path
//===============================================================================================================
add_filter('acf/settings/load_json', 'my_acf_json_load_point');
function my_acf_json_load_point( $paths ) {
    unset($paths[0]);
    $paths[] = get_stylesheet_directory() . '/functions/acf';
    return $paths;
}