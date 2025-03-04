<?php

$result = array();
$result['success'] = false;
$result['error_key'] = 'something_went_wrong';

include_once('fns/cloud_storage/libraries/amazon_s3/autoload.php');

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

if (isset($data['validate']) && !empty($data['validate'])) {
    Registry::load('settings')->cloud_storage_api_key = $data['validate']['cloud_storage_api_key'];
    Registry::load('settings')->cloud_storage_secret_key = $data['validate']['cloud_storage_secret_key'];
    Registry::load('settings')->cloud_storage_region = $data['validate']['cloud_storage_region'];
    Registry::load('settings')->cloud_storage_bucket_name = $data['validate']['cloud_storage_bucket_name'];
    Registry::load('settings')->cloud_storage_endpoint = $data['validate']['cloud_storage_endpoint'];
}

$storage_info = [
    'api_key' => Registry::load('settings')->cloud_storage_api_key,
    'secret_key' => Registry::load('settings')->cloud_storage_secret_key,
    'region' => Registry::load('settings')->cloud_storage_region,
    'bucket_name' => Registry::load('settings')->cloud_storage_bucket_name,
];


$storage_info['endpoint'] = Registry::load('settings')->cloud_storage_endpoint;

$s3 = new S3Client(array(
    'version' => 'latest',
    'use_path_style_endpoint' => true,
    'region' => $storage_info['region'],
    'credentials' => array(
        'key' => $storage_info['api_key'],
        'secret' => $storage_info['secret_key']
    ),
    'endpoint' => $storage_info['endpoint']
));

if (isset($data['validate']) && !empty($data['validate'])) {
    try {
        $buckets = $s3->listBuckets([]);

        if (isset($buckets['Buckets'])) {
            foreach ($buckets['Buckets'] as $key => $obj) {

                $check_bucket_name = $buckets['Buckets'][$key]['Name'];
                $bucket_name = $storage_info['bucket_name'];

                if ($check_bucket_name === $bucket_name) {

                    if ($s3->doesObjectExist($storage_info['bucket_name'], 'assets/cache/settings.cache')) {
                        $result['error_key'] = 'found_cache_folder_cloud_storage';
                        break;
                    }

                    if ($s3->doesObjectExist($storage_info['bucket_name'], 'assets/files/logos/chat_page_logo.png')) {
                        $result = array();
                        $result['success'] = true;
                        break;
                    } else {
                        $result['error_key'] = 'assets_folder_missing';
                        break;
                    }
                } else {
                    $result['error_key'] = 'invalid_bucket_name';
                }
            }
        } else {
            $result['error_key'] = 'invalid_bucket_name';
        }

    } catch (Exception $e) {
        $result['error_key'] = 'invalid_credentials';
    }
} else if (isset($data['upload_file']) && !empty($data['upload_file'])) {
    if (file_exists($data['upload_file'])) {
        try {
            $s3->putObject(array(
                'Bucket' => $storage_info['bucket_name'],
                'Key' => $data['upload_file'],
                'Body' => fopen($data['upload_file'], 'r+'),
                'ACL' => 'public-read',
                'CacheControl' => 'max-age=3153600'
            ));

            $result = array();
            $result['success'] = true;

            if (file_exists('assets/cache/total_cloud_storage_size.cache')) {
                unlink('assets/cache/total_cloud_storage_size.cache');
            }

            if (isset($data['delete']) && $data['delete']) {
                @unlink($data['upload_file']);
            }
        } catch (Exception $e) {
            $result['error_key'] = 'upload_Failed';
            $result['error_message'] = $e->getMessage();
        }

    } else {
        $result['error_key'] = 'file_not_found';
    }
} else if (isset($data['upload_folder']) && !empty($data['upload_folder'])) {
    if (file_exists($data['upload_folder'])) {
        try {
            $s3->uploadDirectory($data['upload_folder'], $storage_info['bucket_name'], '');
            $result = array();
            $result['success'] = true;
        } catch (Exception $e) {
            $result['error_key'] = 'upload_Failed';
            $result['error_message'] = $e->getMessage();
        }

    } else {
        $result['error_key'] = 'file_not_found';
    }
} else if (isset($data['delete_file']) && !empty($data['delete_file'])) {
    try {
        $s3->deleteObject(array(
            'Bucket' => $storage_info['bucket_name'],
            'Key' => $data['delete_file']
        ));
        $result = array();
        $result['success'] = true;

        if (file_exists('assets/cache/total_cloud_storage_size.cache')) {
            unlink('assets/cache/total_cloud_storage_size.cache');
        }
    } catch (Exception $e) {
        $result['error_key'] = 'unable_to_delete';
    }
} else if (isset($data['delete_files']) && !empty($data['delete_files'])) {
    try {
        $objects = [];
        foreach ($data['delete_files'] as $fileKey) {
            $objects[] = ['Key' => $fileKey];
        }

        $s3->deleteObjects([
            'Bucket' => $storage_info['bucket_name'],
            'Delete' => [
                'Objects' => $objects,
                'Quiet' => false,
            ],
        ]);
        $result = array();
        $result['success'] = true;

        if (file_exists('assets/cache/total_cloud_storage_size.cache')) {
            unlink('assets/cache/total_cloud_storage_size.cache');
        }

    } catch (Exception $e) {
        $result['error_key'] = 'unable_to_delete';
    }
} else if (isset($data['file_exists']) && !empty($data['file_exists'])) {
    try {

        $file_exists = $s3->doesObjectExist($storage_info['bucket_name'], $data['file_exists']);

        if ($file_exists) {
            $result = array();
            $result['success'] = true;
        } else {
            $result['error_key'] = 'file_not_found';
        }
    } catch (Exception $e) {
        $result['error_key'] = 'file_not_found';
    }
} else if (isset($data['get_file_info']) && !empty($data['get_file_info'])) {
    try {

        $headParams = [
            'Bucket' => $storage_info['bucket_name'],
            'Key' => $data['get_file_info'],
        ];

        $headResult = $s3->headObject($headParams);

        if (isset($headResult['ContentType'])) {
            $result = array();
            $result['success'] = true;
            $result['file_type'] = $headResult['ContentType'];
            $result['file_size'] = $headResult['ContentLength'];
            $result['LastModified'] = $headResult['LastModified'];

        } else {
            $result['error_key'] = 'file_not_found';
        }
    } catch (Exception $e) {
        $result['error_key'] = 'file_not_found';
    }
} else if (isset($data['get_size']) && !empty($data['get_size'])) {
    try {

        $result = 0;

        $maxAgeInSeconds = 2 * 60 * 60;
        $load_from_cache = false;
        $total_storage_cache = false;
        $pattern = '/\/storage\/(\d+)/';

        if (preg_match($pattern, $data['get_size'], $matches)) {
            $user_id = $matches[1];
            $filePath = 'assets/cache/user_storage/'.$user_id.'.cache';
        } else {
            $filePath = 'assets/cache/total_cloud_storage_size.cache';
            $total_storage_cache = true;
        }

        if (isset($data['load_from_cache']) && $data['load_from_cache']) {
            $load_from_cache = true;
        }

        if ($load_from_cache && file_exists($filePath) && (time() - filemtime($filePath)) < $maxAgeInSeconds) {
            $result = file_get_contents($filePath);
            $result = (int)$result;
        } else {
            $objects = $s3->listObjectsV2([
                'Bucket' => $storage_info['bucket_name'],
                'Prefix' => $data['get_size'],
            ]);


            if (isset($objects['Contents'])) {

                $totalSize = 0;
                $user_storage = array();

                foreach ($objects['Contents'] as $object) {

                    if ($total_storage_cache) {
                        if (preg_match($pattern, $object['Key'], $matches)) {
                            $user_id = $matches[1];

                            if (!isset($user_storage[$user_id])) {
                                $user_storage[$user_id] = 0;
                            }

                            $user_storage[$user_id] += $object['Size'];
                            $user_filePath = 'assets/cache/user_storage/'.$user_id.'.cache';

                            file_put_contents($user_filePath, $user_storage[$user_id]);
                        }
                    }

                    $totalSize += $object['Size'];
                }

                $result = $totalSize;

            } else {
                $result = 0;
            }

            if ($load_from_cache) {
                file_put_contents($filePath, $result);
            }
        }
    } catch (Exception $e) {
        $result['error_key'] = 'file_not_found';
    }
} else if (isset($data['delete_folder']) && !empty($data['delete_folder'])) {
    try {
        $objects = $s3->listObjectsV2([
            'Bucket' => $storage_info['bucket_name'],
            'Prefix' => $data['delete_folder'],
        ]);

        if (isset($objects['Contents']) && !empty($objects['Contents'])) {
            $keys = array_column($objects['Contents'], 'Key');

            if (!empty($keys)) {
                $result = $s3->deleteObjects([
                    'Bucket' => $storage_info['bucket_name'],
                    'Delete' => [
                        'Objects' => array_map(function ($key) {
                            return ['Key' => $key];
                        }, $keys),
                    ],
                ]);

                if ($result['Deleted']) {
                    $result = array();
                    $result['success'] = true;

                    if (file_exists('assets/cache/total_cloud_storage_size.cache')) {
                        unlink('assets/cache/total_cloud_storage_size.cache');
                    }
                }
            }
        }
    } catch (S3Exception $e) {
        $result['error_key'] = 'unable_to_delete';
    }
} else if (isset($data['delete_older_than']) && !empty($data['delete_older_than'])) {
    if (isset($data['location']) && !empty($data['location'])) {
        try {
            $objects = $s3->listObjectsV2([
                'Bucket' => $storage_info['bucket_name'],
                'Prefix' => $data['location'],
            ]);

            $delete_older_than = (int)$data['delete_older_than'];
            $timeThreshold = time() - ($delete_older_than * 60);

            if (isset($objects['Contents']) && !empty($objects['Contents'])) {

                $keys = array();
                foreach ($objects['Contents'] as $object) {
                    $lastModifiedTimestamp = strtotime($object['LastModified']);
                    if ($lastModifiedTimestamp < $timeThreshold) {
                        $keys[] = ['Key' => $object['Key']];
                    }
                }

                if (!empty($keys)) {

                    $result = $s3->deleteObjects([
                        'Bucket' => $storage_info['bucket_name'],
                        'Delete' => [
                            'Objects' => $keys,
                        ],
                    ]);

                    if ($result['Deleted']) {
                        $result = array();
                        $result['success'] = true;
                        $result['deleted_files'] = $keys;
                    }
                }
            }
        } catch (S3Exception $e) {
            $result['error_key'] = 'unable_to_delete';
        }
    }
} else if (isset($data['download_file']) && !empty($data['download_file'])) {
    try {

        $expiration = '+2 hour';
        $download_as = basename($data['download_file']);

        if (isset($data['download_as']) && !empty($data['download_as'])) {
            $download_as = $data['download_as'];
        }

        if (isset($data['expiration'])) {
            $expiration = $data['expiration'];
        }

        $command = $s3->getCommand('GetObject', [
            'Bucket' => $storage_info['bucket_name'],
            'Key' => $data['download_file'],
            'ResponseContentType' => 'application/octet-stream',
            'ResponseContentDisposition' => 'attachment;filename=' . $download_as,
        ]);
        $request = $s3->createPresignedRequest($command, $expiration);
        $presignedUrl = (string) $request->getUri();



        if (isset($data['download_url']) && $data['download_url']) {
            $result = array();
            $result['success'] = true;
            $result['download_url'] = $presignedUrl;
        } else {
            header("Location:$presignedUrl");
            exit();
        }

    } catch (Exception $e) {
        $result['error_key'] = 'unable_to_create_download_link';
    }
} else if (isset($data['load_folder']) && !empty($data['load_folder'])) {
    try {

        $parameters = [
            'Bucket' => $storage_info['bucket_name'],
            'Prefix' => $data['load_folder'],
        ];

        if (isset($data['extra_parameters']) && !empty($data['extra_parameters'])) {
            $parameters = array_merge($parameters, $data['extra_parameters']);
        }

        $objects = $s3->listObjectsV2($parameters);

        if (!empty($objects['Contents'])) {
            $result = array();

            $objectsArray = $objects['Contents'];

            if (isset($data["search"]) && !empty($data["search"])) {
                $searchWord = strtolower($data["search"]);

                $filteredObjects = array_filter($objectsArray, function ($object) use ($searchWord) {
                    return strpos(strtolower($object['Key']), $searchWord) !== false;
                });

                $objectsArray = $filteredObjects;
            }

            usort($objectsArray, function ($a, $b) {
                $timeA = strtotime($a['LastModified']);
                $timeB = strtotime($b['LastModified']);

                if ($timeA === $timeB) {
                    return 0;
                }

                return ($timeA > $timeB) ? -1 : 1;
            });

            foreach ($objectsArray as $object_file) {
                $headParams = [
                    'Bucket' => $storage_info['bucket_name'],
                    'Key' => $object_file['Key'],
                ];

                $headResult = $s3->headObject($headParams);

                $skip_file = false;
                $contentType = $headResult['ContentType'];

                if (isset($data["content_type"]) && !empty($data["content_type"])) {
                    if ($data["content_type"] === 'images') {
                        if (strpos($contentType, 'image/') !== 0) {
                            $skip_file = true;
                        }
                    } else if ($data["content_type"] === 'videos') {
                        if (strpos($contentType, 'video/') !== 0) {
                            $skip_file = true;
                        }
                    } else if ($data["content_type"] === 'audio') {
                        if (strpos($contentType, 'audio/') !== 0) {
                            $skip_file = true;
                        }
                    } else if ($data["content_type"] === 'others') {
                        if (strpos($contentType, 'image/') === 0 || strpos($contentType, 'video/') === 0 || strpos($contentType, 'audio/') === 0) {
                            $skip_file = true;
                        }
                    }
                }

                if (!$skip_file) {
                    $result[] = [
                        'file_path' => $object_file['Key'],
                        'file_size' => $object_file['Size'],
                        'content_type' => $contentType
                    ];
                }
            }
        } else {
            $result = array();
        }

    } catch (S3Exception $e) {
        $result = array();
    }
}
?>