<?php
/*
################################################################################################################
##
##     LOGIN STYLING
##
################################################################################################################
*/

function custom_login_styles() {
    foreach ($GLOBALS['manifest_data'] as  $key => $entry_point) {
        if (str_contains($entry_point, 'cms-')) {
            wp_register_style($key, get_template_directory_uri() . '/assets/' . $entry_point, array(), '1', 'all');
            wp_enqueue_style($key);
        }
    }
    echo '<script>document.addEventListener("DOMContentLoaded", () => {document.querySelector("#login > h1 > a").href = "'.get_home_url().'";});</script>';
}

add_action('login_head', 'custom_login_styles');