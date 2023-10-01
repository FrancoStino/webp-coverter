<?php

require ('vendor/autoload.php'); // Includi il file di autoloading per le dipendenze

use WebPConvert\WebPConvert; // Importa la classe WebPConvert dalla libreria

add_filter('wp_handle_upload', 'compress_and_convert_images_to_webp'); // Aggiungi un filtro per l'upload di WordPress

function compress_and_convert_images_to_webp($file) {
    // Controlla se il tipo di file è supportato
    $supported_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $supported_types)) {
        return $file; // Se il tipo di file non è supportato, restituisci il file originale
    }

    // Ottieni il percorso alla directory di upload di WordPress
    $wp_upload_dir = wp_upload_dir();

    // Imposta i percorsi dei file
    $old_file_path = $file['file']; // Percorso del file originale
    $file_name = basename($file['file']); // Nome del file
    $webp_file_path = $wp_upload_dir['path'] . '/' . pathinfo($file_name, PATHINFO_FILENAME) . '.webp'; // Percorso del file WebP

    // Controlla se il file è già un'immagine WebP
    if (pathinfo($old_file_path, PATHINFO_EXTENSION) === 'webp') {
        return $file; // Se è già un'immagine WebP, restituisci il file originale
    }

    // Controlla se esiste un'immagine con lo stesso nome
    if (file_exists($webp_file_path)) {
        // Se esiste, ottieni l'ID dell'allegato per il file WebP esistente
        $existing_attachment_id = attachment_url_to_postid($wp_upload_dir['url'] . '/' . basename($webp_file_path));

        if ($existing_attachment_id) {
            // Elimina il vecchio file WebP
            unlink($webp_file_path);

            // Elimina l'entrata nel database per il vecchio file WebP
            wp_delete_attachment($existing_attachment_id, true); // Imposta il secondo parametro su true per eliminare definitivamente l'allegato
        }
    }

    // Imposta le opzioni per la conversione
    $options = [
        'quality' => 90, // Regola questo valore per controllare il livello di compressione
        //'converters' => ['cwebp', 'gd', 'imagick'], // Convertitori da utilizzare
    ];

    // Esegui la conversione in formato WebP
    WebPConvert::convert($old_file_path, $webp_file_path, $options);

    // Verifica se la conversione è stata completata con successo
    if (file_exists($webp_file_path)) {
        // Elimina il vecchio file immagine
        unlink($old_file_path);

        // Restituisci le informazioni aggiornate sul file
        return [
            'file' => $webp_file_path,
            'url' => $wp_upload_dir['url'] . '/' . basename($webp_file_path),
            'type' => 'image/webp',
        ];
    }
}