<?php

// customize the excerpt
// limit the characters eliminating
// the read more button
function wace_custom_excerpt($text) {

    $length = 200;
    if(strlen($text)<$length+10) return $text;//don't cut if too short

    $break_pos = strpos($text, ' ', $length);//find next space after desired length
    $visible = substr($text, 0, $break_pos);
    return balanceTags($visible) . "...";

} // /customize the excerpt

// add responsive image class
// to images in the post content
// https://stackoverflow.com/questions/20473004/how-to-add-automatic-class-in-image-for-wordpress-post
//
function wace_add_responsive_class($content){

    $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
    $document = new DOMDocument();
    libxml_use_internal_errors(true);
    $document->loadHTML(utf8_decode($content));

    $imgs = $document->getElementsByTagName('img');
    foreach ($imgs as $img) {
       $img->setAttribute('class','img-responsive');
    }

    $html = $document->saveHTML();
    return $html;

}
