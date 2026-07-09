<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    public static function uploadAndConvertToWebp(UploadedFile $file, string $path, string $disk = 'public', int $quality = 80): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $filename = uniqid() . '.webp';
        $filePath = $path . '/' . $filename;

        // If already webp, just store it
        if (strtolower($file->getClientOriginalExtension()) === 'webp') {
            return $file->store($path, $disk);
        }

        try {
            if (extension_loaded('imagick')) {
                $imagick = new \Imagick($file->getPathname());
                $imagick->setImageFormat('WEBP');
                $imagick->setImageCompressionQuality($quality);
                $imagick->stripImage();
                $webpContent = $imagick->getImageBlob();
                if (Storage::disk($disk)->put($filePath, $webpContent)) {
                    $imagick->destroy();
                    return $filePath;
                }
                $imagick->destroy();
            }
        } catch (\Exception $e) {
        }

        if (extension_loaded('gd')) {
            $imageType = exif_imagetype($file->getPathname());
            $image = false;

            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $image = @imagecreatefromjpeg($file->getPathname());
                    break;
                case IMAGETYPE_PNG:
                    $image = @imagecreatefrompng($file->getPathname());
                    if ($image) {
                        imagealphablending($image, false);
                        imagesavealpha($image, true);
                    }
                    break;
                case IMAGETYPE_GIF:
                    $image = @imagecreatefromgif($file->getPathname());
                    break;
            }

            if ($image) {
                $tempWebpPath = tempnam(sys_get_temp_dir(), 'webp_upload');
                if (@imagewebp($image, $tempWebpPath, $quality)) {
                    $webpContent = file_get_contents($tempWebpPath);
                    imagedestroy($image);
                    unlink($tempWebpPath);
                    if (Storage::disk($disk)->put($filePath, $webpContent)) {
                        return $filePath;
                    }
                } else {
                    imagedestroy($image);
                    if (file_exists($tempWebpPath)) unlink($tempWebpPath);
                }
            }
        }

        return $file->store($path, $disk);
    }

    public static function deleteImage($filePath, $disk = 'public'): bool
    {
        if ($filePath && Storage::disk($disk)->exists($filePath)) {
            Storage::disk($disk)->delete($filePath);
            return true;
        }
        return false;
    }
}
