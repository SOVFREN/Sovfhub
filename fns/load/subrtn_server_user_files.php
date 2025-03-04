<?php
$location = 'assets/files/storage/'.$user_id.'/files/*';

if (!empty($data["search"])) {
    $data['search'] = rangeof_chars(stripslashes(str_replace(array('/', '*'), array('', ''), $data['search'])));
    $location = $location.$data['search'].'*';
}

if ($data["filter"] === 'images') {
    $extensions = rangeof_chars('jpg,png,gif,jpeg,bmp,webp');
    $location = $location.'.{'.$extensions.'}';
} else if ($data["filter"] === 'videos') {
    $extensions = rangeof_chars('mp4,mpeg,ogg,webm,mov');
    $location = $location.'.{'.$extensions.'}';
} else if ($data["filter"] === 'audio') {
    $extensions = rangeof_chars('oga,mp3,wav');
    $location = $location.'.{'.$extensions.'}';
} else if ($data["filter"] === 'others') {
    $allfiles = glob($location);
    $extensions = rangeof_chars('mp4,mpeg,ogg,webm,mov,jpg,png,gif,jpeg,bmp,oga,mp3,wav,webp');
    $location = $location.'.{'.$extensions.'}';
    $imgvideos = glob($location, GLOB_BRACE);
}

if (empty($data["filter"])) {
    $user_files = glob($location);
} else if ($data["filter"] === 'others') {
    $user_files = array_diff($allfiles, $imgvideos);
} else {
    $user_files = glob($location, GLOB_BRACE);
}

usort($user_files, function($file1, $file2) {
    return filemtime($file2) <=> filemtime($file1);
});

$user_files = array_slice($user_files, $data["offset"], Registry::load('settings')->records_per_call);

$files = array();
$array_index = 1;

foreach ($user_files as $file) {

    $file_name = $files[$array_index]['file_basename'] = basename($file);
    $file_name = explode('-gr-', $file_name, 2);

    if (isset($file_name[1])) {
        $files[$array_index]['file_name'] = $file_name[1];
    } else {
        $files[$array_index]['file_name'] = basename($file);
    }

    $files[$array_index]['file_size'] = files('getsize', ['getsize_of' => $file, 'real_path' => true]);


    $files[$array_index]['file_type'] = $file_type = mime_content_type($file);
    $files[$array_index]['file_path'] = Registry::load('config')->site_url.$file;

    $file_extension_img = "assets/files/file_extensions/".pathinfo($file, PATHINFO_EXTENSION).".png";
    $thumbnail = null;
    $files[$array_index]['file_format'] = 'others';

    if (in_array($file_type, $video_file_formats)) {
        $thumbnail = 'assets/files/storage/'.$user_id.'/thumbnails/'.pathinfo($file, PATHINFO_FILENAME).'.jpg';
        $files[$array_index]['file_format'] = 'video';
    } else if (in_array($file_type, $image_file_formats)) {
        $thumbnail = 'assets/files/storage/'.$user_id.'/thumbnails/'.basename($file);
        $files[$array_index]['file_format'] = 'image';
    } else if (in_array($file_type, $audio_file_formats)) {
        $files[$array_index]['file_format'] = 'audio';
    } else if (in_array($file_type, $pdf_file_formats)) {
        $files[$array_index]['file_format'] = 'pdf_file';
    }

    if (!empty($thumbnail) && file_exists($thumbnail)) {
        $files[$array_index]['thumbnail'] = Registry::load('config')->site_url.$thumbnail;
    } else if (file_exists($file_extension_img)) {
        $files[$array_index]['thumbnail'] = Registry::load('config')->site_url.$file_extension_img;
    } else {
        $files[$array_index]['thumbnail'] = $unknown_file_extension;
    }


    $array_index++;
}
?>