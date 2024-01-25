<?php

require ('vendor/autoload.php');
use WebPConvert\WebPConvert;

class ImageConverter {

    public function __construct() {
        add_filter('wp_handle_upload', array($this, 'compressAndConvertToWebP'));
    }

    /**
     * Compresses and converts images to WebP format.
     *
     * @param array $file The file to be processed.
     * @return array|string The processed file in WebP format or the original file if it's not supported.
     */
    public function compressAndConvertToWebP(array $file): array|string {
        $supportedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

        if (!in_array($file['type'], $supportedTypes)) {
            return $file;
        }

        $wpUploadDir = wp_upload_dir();

        $oldFilePath = $file['file'];
        $fileName = basename($file['file']);

        // Generate a unique name for the WebP file using wp_unique_filename
        $webpFileName = wp_unique_filename($wpUploadDir['path'], pathinfo($fileName, PATHINFO_FILENAME) . '.webp');
        $webpFilePath = $wpUploadDir['path'] . '/' . $webpFileName;

        $options = [
            'quality' => 90,
        ];

        WebPConvert::convert($oldFilePath, $webpFilePath, $options);

        if (file_exists($webpFilePath)) {
            unlink($oldFilePath);

            return [
                'file' => $webpFilePath,
                //'url' => $wpUploadDir['url'] . '/' . $webpFileName,
                'type' => 'image/webp',
            ];
        }

        return $file;
    }
}

// Create an instance of the ImageConverter class to initialize the filter
$imageConverter = new ImageConverter();
