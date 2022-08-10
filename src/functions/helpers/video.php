<?php
/*
################################################################################################################
##
##     VIDE HANDELING FUNCTIONS
##     Vimeo and Youtube functions
##
################################################################################################################
*/

//------------------------------------------------------------------------------------------
// Get video ID
//------------------------------------------------------------------------------------------

//    Usage Example:
//    
//    getVideoID( "https://www.youtube.com/watch?v=tn6gjoMUEY4");
//
//    output: "tn6gjoMUEY4" 

function getVideoID($videoUrl) {
    // Detect if a Youtube link
    $vars_array = array();
    if (strpos($videoUrl, 'youtube') !== false) {
        parse_str( parse_url( $videoUrl, PHP_URL_QUERY ), $vars_array );
        $videoID = $vars_array['v'];   
        $source = 'youtube';
    // Detect if a Vimeo link
    } else if (strpos($videoUrl, 'vimeo') !== false) {
        preg_match('/(\d+)/', $videoUrl, $matches);
        $videoID = $matches[1];
        $source = 'vimeo';
    } else {
        $videoID = 'unsuported';
        $source = 'unkown';
    }
    return array('videoId' => $videoID, 'videoSource' => $source);
}

//------------------------------------------------------------------------------------------
// Get video thumb
//------------------------------------------------------------------------------------------

//    Usage Example:
//    
//    getVideoThumb( "https://www.youtube.com/watch?v=tn6gjoMUEY4");
//
//    output: "http://img.youtube.com/vi/tn6gjoMUEY4/0.jpg" 

function getVideoThumb($videoUrl) {
    $videoID = getVideoID($videoUrl);
    if ($videoID['videoSource'] == 'youtube') {
        $videoThumb = 'http://img.youtube.com/vi/' . $videoID['videoId'] . '/0.jpg';
    } else if ($videoID['videoSource'] == 'vimeo') {
        $imgid = $videoID['videoId'];
        $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
        $videoThumb = $hash[0]['thumbnail_large'];
    } else {
        $videoThumb = 'unsuported';
    }
    return $videoThumb;
}

//------------------------------------------------------------------------------------------
// Get video embed
//------------------------------------------------------------------------------------------

//    Usage Example:
//    
//    getVideoEmbed( "https://www.youtube.com/watch?v=tn6gjoMUEY4", 400, 300, true );
//
//    output: "<iframe class="youtube" id="tn6gjoMUEY4" width="400" height="300" src="http://www.youtube.com/embed/tn6gjoMUEY4?rel=0&wmode=opaque&showinfo=0&enablejsapi=1&autoplay=true" frameborder="0" allowfullscreen></iframe>" 

function getVideoEmbed($videoUrl, $videoWidth="100%", $videoHeight="100%", $autoplay = true) {
    
    $playnow = ( $autoplay==true ? $playnow = 1 : $playnow = 0 );
    
    $videoID = getVideoID($videoUrl);
    if ($videoID['videoSource'] == 'youtube') {
        $videoLink = '<iframe class="youtube" id="' . $videoID['videoId'] . '" width="' . $videoWidth . '" height="' . $videoHeight . '" src="http://www.youtube.com/embed/' . $videoID['videoId'] . '?rel=0&wmode=opaque&showinfo=0&enablejsapi=1&autoplay=' . $playnow . '" frameborder="0" allowfullscreen></iframe>';
    } else if ($videoID['videoSource'] == 'vimeo') {
        $videoLink = '<iframe class="vimeo" id="' . $videoID['videoId'] . '" src="http://player.vimeo.com/video/' . $videoID['videoId'] . '?portrait=0&autoplay=' . $playnow . '&color=134D85&title=0&byline=0&api=1&player_id=' . $videoID['videoId'] . '" width="' . $videoWidth . '" height="' . $videoHeight . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    } else {
        $videoLink = 'unsuported';
    }
    return $videoLink;
}
