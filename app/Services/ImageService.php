<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * Delete an image from the storage
     *
     * @param string $filename
     * @param string $path
     * @return void
     */
    public function deleteImage(string $filename, string $path): void
    {
        $filePath = $path . $filename;
        // Delete the image file from the storage
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }
}
