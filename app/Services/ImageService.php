<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected string $disk;

    public function __construct(?string $disk)
    {
        $this->disk = $disk ?? config('filesystems.default');
    }

    /**
     * Upload an image and optionally delete the old one.
     *
     * @param UploadedFile|null $newImage
     * @param string|null $path
     * @param string|null $oldImagePath
     * @return string|null
     */
    public function upload(?UploadedFile $newImage, ?string $path = 'uploads', ?string $oldImagePath = null): ?string
    {
        if (!$newImage) {
            return $oldImagePath;
        }
       
        // Delete the old image if it exists
        if ($oldImagePath && Storage::disk($this->disk)->exists($oldImagePath)) {
            Storage::disk($this->disk)->delete($oldImagePath);
        }

        // Store the new image and return the path
        return $newImage->store($path, $this->disk);
    }

    /**
     * Delete an image from storage.
     *
     * @param string|null $imagePath
     * @return bool
     */
    public function delete(?string $imagePath): bool
    {
        if ($imagePath && Storage::disk($this->disk)->exists($imagePath)) {
            return Storage::disk($this->disk)->delete($imagePath);
        }

        return false;
    }
}
