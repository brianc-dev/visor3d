<?php

$image = imagecreatefrompng($_POST['imageUrl']);
$croppedImage = imagecropauto($image, IMG_CROP_BLACK);

$imageFile = tempnam(sys_get_temp_dir(), 'tmp');
imagepng($croppedImage, $imageFile);

$imageInfo = getimagesize($imageFile);
$width = $imageInfo[0];
$height = $imageInfo[1];

$newImage = imagecreatetruecolor(350, 300);
$color = imagecolorallocate($newImage, 0, 0, 0);
imagefill($newImage, 0, 0, $color);

if ($width > $height) {
    $scaledImage = imagescale($croppedImage, 350);
    $imageFile2 = tempnam(sys_get_temp_dir(), 'tmp');
    imagepng($scaledImage, $imageFile2);
    $imageInfo = getimagesize($imageFile2);
    $newWidth = $imageInfo[0];
    $newHeight = $imageInfo[1];
    imagecopy($newImage, $scaledImage, 0, 300 / 2 - $newHeight / 2, 0, 0, $newWidth, $newHeight);
} else {
    $rotatedImage = imagerotate($croppedImage, 90, 0);
    $scaledImage = imagescale($rotatedImage, 300);
    imagedestroy($rotatedImage);
    $rotatedImage = imagerotate($scaledImage, 360-90, 0);
    imagedestroy($scaledImage);
    
    $imageFile2 = tempnam(sys_get_temp_dir(), 'tmp');
    imagepng($rotatedImage, $imageFile2);

    $imageInfo = getimagesize($imageFile2);
    $newWidth = $imageInfo[0];
    $newHeight = $imageInfo[1];
    imagecopy($newImage, $rotatedImage, 350 / 2 - $newWidth / 2, 0, 0, 0, $newWidth, $newHeight);
}

// $file = tempnam(__DIR__ . '/../uploads/modelos_thumbnail', 'image_');
imagepng($newImage, __DIR__ . '/../uploads/modelos_thumbnail/'. uniqid() . '.png') or die();
// rename($file, $file . '.png');

imagedestroy($newImage);
