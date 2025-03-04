<?php

if (isset($link_meta_data['title']) && isset($link_meta_data['html'])) {
    $result['title'] = $link_meta_data['title'];
    $result['image'] = $link_meta_data['thumbnail_url'];

    if (isset($link_meta_data['description'])) {
        $result['description'] = $link_meta_data['description'];
    } elseif (isset($link_meta_data['author_name'])) {
        $result['description'] = $link_meta_data['author_name'];
    }
    $sound_cloud_embed_url = $link_meta_data['html'];

    preg_match('/src="([^"]+)"/', $sound_cloud_embed_url, $sound_cloud_embed_url_match);

    if (isset($sound_cloud_embed_url_match[1])) {
        $sound_cloud_embed_url = $sound_cloud_embed_url_match[1];

        $result['iframe_embed'] = $sound_cloud_embed_url;
        $result['iframe_relative_height'] = '100px';
        $result['iframe_class'] = 'w-100 h-100';
    }
}