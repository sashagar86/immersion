<?php

namespace App\Controllers;

use claviska\SimpleImage;

class ImageController
{
    const ALLOWED_EXTENSION = ['jpg', 'jpeg', 'png'];
    const UPLOADS_DIR = 'uploads/';

    public function uploadImage()
    {
        $dir = $this->createDirectory();

        return $this->processFile($dir, $_FILES);
    }

    public static function getImage($image)
    {
        $file = self::UPLOADS_DIR . ($image ?: 'avatar-m.png');

        if (!file_exists($file)) {
            $image = self::UPLOADS_DIR . 'avatar-m.png';
        }

        return '/' . self::UPLOADS_DIR . ($image ?: 'avatar-m.png');
    }

    protected function createDirectory($dir = self::UPLOADS_DIR)
    {
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        return $dir;
    }

    protected function processFile($toDir, $image)
    {
        $filename = '';

        if (!empty($image['image']['tmp_name'])) {
            $imageObject = new SimpleImage();

            $name = $image['image']['name'];
            $name = explode('.', $name);
            $extension = end($name);

            if (!in_array($extension, self::ALLOWED_EXTENSION)) {
                flash()->error('The file extension not allow');
                return;
            }

            $filename = uniqid() . '.' . $extension;

            $imageObject->fromFile($image['image']['tmp_name'])
                ->toFile($toDir . $filename);
        }

        return $filename;
    }
}