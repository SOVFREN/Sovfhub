<?php


if (isset($link_meta_data['author_name'])) {
    $result['title'] = $link_meta_data['author_name'];
    $result['image'] = Registry::load('config')->site_url.'assets/files/defaults/twitter.png';
    $image_generate = true;

    if (isset($link_meta_data['html'])) {
        $result['description'] = strip_tags($link_meta_data['html']);
    }

    if (empty($result['description'])) {
        if (isset($link_meta_data['description'])) {
            $result['description'] = $link_meta_data['description'];
        } else if (isset($link_meta_data['author_name'])) {
            $result['description'] = $link_meta_data['author_name'];
        }
    }


}