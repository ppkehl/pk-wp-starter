<?php
/*
################################################################################################################
##
##     TEXT HANDELING FUNCTIONS
##     Excerpts, Reading time, etc
##
################################################################################################################
*/

//------------------------------------------------------------------------------------------
// Custom Excerpts
//------------------------------------------------------------------------------------------
//    Usage Example:
//    
//    custom_excerpt( "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sed vestibulum magna.", 5, '...');
//
//    output: "Lorem..." 

function custom_excerpt($text, $maxchar, $end = '...') {

    $text = strip_tags($text);
    $text = trim($text);
    $text = str_replace('&nbsp;', '', $text);

    if (strlen($text) > $maxchar || $text == '') {
        $words = preg_split('/\s/', $text);
        $output = '';
        $i = 0;
        while (1) {
            $length = strlen($output) + strlen($words[$i]);
            if ($length > $maxchar) {
                break;
            } else {
                $output .= " " . $words[$i];
                ++$i;
            }
        }
        $output .= $end;
    } else {
        $output = $text;
    }
    return $output;
}

//------------------------------------------------------------------------------------------
// Reading time
//------------------------------------------------------------------------------------------
//    Usage Example:
//    
//    readingTime( "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sed vestibulum magna.");
//
//    output: "x" 

function get_reading_time($text) {
    $word = str_word_count(strip_tags($text));
    $m = floor($word / 300);
    $s = floor($word % 200 / (200 / 60));
    
    if ($m < 1) {
        $est = -1;
    } else if ($m == 1) {
        $est = 1;
    } else if ($m > 1) {
        $est = $m;
    }
    return $est;
}

//------------------------------------------------------------------------------------------
// Words Count
//------------------------------------------------------------------------------------------

function word_count($text) {
    $word = str_word_count(strip_tags($text));
    return $word;
}

//------------------------------------------------------------------------------------------
// Convert twitter handles to clickable links
//------------------------------------------------------------------------------------------
//    Usage Example:
//    
//    twitterHandleLink("@test");
//
//    output: <a href="http://www.twitter.com/test">@test</a>

function get_twitter_handle_link($handle) {
    $handle = preg_replace('/(?<=^|\s)@([a-z0-9_]+)/i', '<a href="http://www.twitter.com/$1">@$1</a>', $handle);
    return $handle;
}
