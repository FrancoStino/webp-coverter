<?php

require ('vendor/autoload.php'); // Include the autoloading file for dependencies

use WebPConvert\WebPConvert; // Import the WebPConvert class from the library

add_filter('wp_handle_upload', 'compress_and_convert_images_to_webp'); // Add a filter for WordPress file uploads

/**
 * Compresses and converts images to WebP format.
 *
 * @param array $file The file to be processed.
 * @return array|string The processed file in WebP format or the original file if it's not supported.
 */
function compress_and_convert_images_to_webp(array $file): array|string {
    // Check if the file type is supported
    $supported_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $supported_types)) {
        return $file; // If the file type is not supported, return the original file
    }

    // Get the WordPress upload directory path
    $wp_upload_dir = wp_upload_dir();

    // Set the file paths
    $old_file_path = $file['file']; // Path of the original file
    $file_name = basename($file['file']); // File name
    $webp_file_path = $wp_upload_dir['path'] . '/' . pathinfo($file_name, PATHINFO_FILENAME) . '.webp'; // Path of the WebP file

    // Check if the file is already a WebP image
    if (pathinfo($old_file_path, PATHINFO_EXTENSION) === 'webp') {
        return $file; // If it's already a WebP image, return the original file
    }

    // Check if an image with the same name exists
    if (file_exists($webp_file_path)) {
        // If it exists, get the attachment ID for the existing WebP file
        $existing_attachment_id = attachment_url_to_postid($wp_upload_dir['url'] . '/' . basename($webp_file_path));

        if ($existing_attachment_id) {
            // Delete the old WebP file
            unlink($webp_file_path);

            // Delete the database entry for the old WebP file
            wp_delete_attachment($existing_attachment_id, true); // Set the second parameter to true to permanently delete the attachment
        }
    }

    // Set the options for the conversion
    $options = [
        'quality' => 90, // Adjust this value to control the compression level
        //'converters' => ['cwebp', 'gd', 'imagick'], // Converters to use
    ];

    // Perform the conversion to WebP format
    WebPConvert::convert($old_file_path, $webp_file_path, $options);

    // Check if the conversion was successful
    if (file_exists($webp_file_path)) {
        // Delete the old image file
        unlink($old_file_path);

        // Return the updated file information
        return [
            'file' => $webp_file_path,
            'url' => $wp_upload_dir['url'] . '/' . basename($webp_file_path),
            'type' => 'image/webp',
        ];
    }
}