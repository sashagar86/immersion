<?php

namespace App\Controllers;

use claviska\SimpleImage;

class Image
{
    const ALLOWED_EXTENSION = ['jpg', 'jpeg', 'png'];
    const UPLOADS_DIR = 'uploads/';

    public function uploadImage()
    {
        $filename = '';

        $dir = self::UPLOADS_DIR;

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        if (!empty($_FILES['image']['tmp_name'])) {
            $imageObject = new SimpleImage();

            $name = $_FILES['image']['name'];
            $name = explode('.', $name);
            $extension = end($name);

            if (!in_array($extension, self::ALLOWED_EXTENSION)) {
                flash()->error('The file extension not allow');
                return;
            }

            $filename = uniqid() . '.' . $extension;

            $imageObject->fromFile($_FILES['image']['tmp_name'])
                ->toFile($dir . $filename);
        }

        return $filename;
    }

    public static function getImage($image)
    {
        $file = self::UPLOADS_DIR . ($image ?: 'avatar-m.png');

        if (!file_exists($file)) {
            $image = self::UPLOADS_DIR . 'avatar-m.png';
        }

        return '/' . self::UPLOADS_DIR . ($image ?: 'avatar-m.png');
    }
}