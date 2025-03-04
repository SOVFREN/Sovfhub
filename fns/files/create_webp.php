<?php
$result = false;
if (isset($data['file']) && !empty($data['file'])) {

    if (!is_array($data['file'])) {
        $data['file'] = array($data['file']);
    }

    if (!isset($data['quality'])) {
        $data['quality'] = 85;
    }

    foreach ($data['file'] as $convert_img) {
        if (isset($data['real_path']) && $data['real_path']) {
            $image = $saveas = $convert_img;
            if (isset($data['saveas']) && !empty($data['saveas'])) {
                $saveas = $data['saveas'];
            }
        } else {
            $image = $saveas = 'assets/files/'.$convert_img;
            if (isset($data['saveas']) && !empty($data['saveas'])) {
                $saveas = 'assets/files/'.$data['saveas'];
            }

        }

        if (file_exists($image)) {
            try {

                $imageType = exif_imagetype($image);

                if ($imageType === false || !in_array($imageType, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_BMP])) {
                    throw new Exception('Unsupported image type.');
                }

                switch ($imageType) {
                    case IMAGETYPE_JPEG:
                        $img = imagecreatefromjpeg($image);
                        break;
                    case IMAGETYPE_PNG:
                        $img = imagecreatefrompng($image);
                        break;
                    case IMAGETYPE_GIF:
                        $img = imagecreatefromgif($image);
                        break;
                    case IMAGETYPE_BMP:
                        $img = @imagecreatefrombmp($image);
                        if ($img === false) {
                            $img = @imageCreateFromBmp($image);
                        }
                        break;
                    default:
                        throw new Exception('Failed to create image resource.');
                    }

                    if ($img === false) {
                        throw new Exception('Failed to create image resource.');
                    }

                    $width = imagesx($img);
                    $height = imagesy($img);

                    $new_image = imagecreatetruecolor($width, $height);

                    if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_GIF) {
                        imagealphablending($new_image, false);
                        imagesavealpha($new_image, true);
                        $transparentLayer = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
                        imagefilledrectangle($new_image, 0, 0, $width - 1, $height - 1, $transparentLayer);
                    }

                    imagecopy($new_image, $img, 0, 0, 0, 0, $width, $height);

                    $save_image_result = imagewebp($new_image, $saveas, $data['quality']);

                    if (!$save_image_result) {
                        throw new Exception('Failed to save WebP image.');
                    }

                    imagedestroy($img);
                    imagedestroy($new_image);

                    $result = true;
                } catch (Exception $e) {
                    $result = false;
                }
            }
        }
    }
    ?>