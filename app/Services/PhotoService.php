<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PhotoService
{
    public function processAndStore(UploadedFile $file, string $citizenId): string
    {
        // Validate file
        if (!$file->isValid()) {
            throw new \Exception('Invalid file upload');
        }

        // Check file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw new \Exception('Invalid file type. Only JPEG and PNG are allowed.');
        }

        // Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $filename = 'citizen_' . $citizenId . '_' . time() . '.' . $extension;
        
        // Store original file without processing (since GD is not available)
        $path = 'photos/' . $filename;
        
        // Move the uploaded file to storage
        $file->storeAs('photos', $filename, 'public');
        
        return $path;
    }

    public function deletePhoto(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}
