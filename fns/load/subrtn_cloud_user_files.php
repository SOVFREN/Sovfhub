<?php
$location = 'assets/files/storage/'.$user_id.'/files/';

include('fns/cloud_storage/load.php');


if (!empty($data["search"])) {
    $data['search'] = stripslashes(str_replace(array('/', '*'), array('', ''), $data['search']));
}

$parameters = [
    'load_folder' => $location,
    'search' => $data["search"],
    'content_type' => $data["filter"]
];
$user_files = cloud_storage_module($parameters);

if (!empty($user_files)) {
    $user_files = array_slice($user_files, $data["offset"], Registry::load('settings')->records_per_call);
}

$files = array();
$array_index = 1;

foreach ($user_files as $file) {

    $basenameWithoutExtension = pathinfo($file['file_path'], PATHINFO_FILENAME);

    if (!empty($basenameWithoutExtension)) {
        $files[$array_index]['file_basename'] = $output['loaded']->offset = $file_name = basename($file['file_path']);
        $file_name = explode('-gr-', $file_name, 2);

        if (isset($file_name[1])) {
            $files[$array_index]['file_name'] = $file_name[1];
        } else {
            $files[$array_index]['file_name'] = basename($file['file_path']);
        }

        $files[$array_index]['file_size'] = files('getsize', ['convert_size' => $file['file_size']]);

        $files[$array_index]['file_type'] = $file_type = $file['content_type'];
        $files[$array_index]['file_path'] = Registry::load('settings')->cloud_storage_public_url.$file['file_path'];

        $file_extension_img = "assets/files/file_extensions/".pathinfo($file['file_path'], PATHINFO_EXTENSION).".png";
        $thumbnail = null;
        $files[$array_index]['file_format'] = 'others';

        if (in_array($file_type, $video_file_formats)) {
            $thumbnail = 'assets/files/storage/'.$user_id.'/thumbnails/'.pathinfo($file['file_path'], PATHINFO_FILENAME).'.jpg';
            $files[$array_index]['file_format'] = 'video';
        } else if (in_array($file_type, $image_file_formats)) {
            $thumbnail = 'assets/files/storage/'.$user_id.'/thumbnails/'.basename($file['file_path']);
            $files[$array_index]['file_format'] = 'image';
        } else if (in_array($file_type, $audio_file_formats)) {
            $files[$array_index]['file_format'] = 'audio';
        } else if (in_array($file_type, $pdf_file_formats)) {
            $files[$array_index]['file_format'] = 'pdf_file';
        }

        if (!empty($thumbnail)) {
            $files[$array_index]['thumbnail'] = Registry::load('settings')->cloud_storage_public_url.$thumbnail;
        } else if (file_exists($file_extension_img)) {
            $files[$array_index]['thumbnail'] = Registry::load('config')->site_url.$file_extension_img;
        } else {
            $files[$array_index]['thumbnail'] = $unknown_file_extension;
        }


        $array_index++;
    }
}
?>