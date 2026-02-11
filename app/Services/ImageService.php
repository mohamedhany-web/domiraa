<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\StorageHelper;

class ImageService
{
    /**
     * Maximum width for images
     */
    protected int $maxWidth = 1920;
    
    /**
     * Maximum height for images
     */
    protected int $maxHeight = 1080;
    
    /**
     * JPEG quality (1-100)
     */
    protected int $quality = 80;
    
    /**
     * Thumbnail width
     */
    protected int $thumbWidth = 400;
    
    /**
     * Thumbnail height
     */
    protected int $thumbHeight = 300;

    /**
     * Upload and optimize an image
     */
    public function upload(UploadedFile $file, string $folder = 'images', bool $createThumbnail = true): array
    {
        $filename = $this->generateFilename($file);
        $path = "{$folder}/{$filename}";
        $thumbPath = null;
        
        // Get image info
        $imageInfo = getimagesize($file->getPathname());
        if (!$imageInfo) {
            // Not an image, just store it
            $storedPath = $file->store($folder, 'public');
            return [
                'path' => $storedPath,
                'thumbnail' => null,
                'url' => StorageHelper::url($storedPath),
                'thumbnail_url' => null,
            ];
        }
        
        // Create optimized image
        $optimizedImage = $this->optimizeImage($file, $imageInfo);
        
        // Store the optimized image
        Storage::disk('public')->put($path, $optimizedImage);
        
        // Create thumbnail if requested
        if ($createThumbnail) {
            $thumbFilename = 'thumb_' . $filename;
            $thumbPath = "{$folder}/thumbnails/{$thumbFilename}";
            $thumbnail = $this->createThumbnail($file, $imageInfo);
            Storage::disk('public')->put($thumbPath, $thumbnail);
        }
        
        return [
            'path' => $path,
            'thumbnail' => $thumbPath,
            'url' => StorageHelper::url($path),
            'thumbnail_url' => $thumbPath ? StorageHelper::url($thumbPath) : null,
        ];
    }

    /**
     * Upload multiple images
     */
    public function uploadMultiple(array $files, string $folder = 'images', bool $createThumbnails = true): array
    {
        $results = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $results[] = $this->upload($file, $folder, $createThumbnails);
            }
        }
        return $results;
    }

    /**
     * Delete an image and its thumbnail
     */
    public function delete(string $path, ?string $thumbnailPath = null): bool
    {
        $deleted = false;
        
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            $deleted = true;
        }
        
        if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }
        
        return $deleted;
    }

    /**
     * Generate a unique filename
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        return Str::uuid() . '_' . time() . '.' . $extension;
    }

    /**
     * Optimize and resize image
     */
    protected function optimizeImage(UploadedFile $file, array $imageInfo): string
    {
        list($width, $height, $type) = $imageInfo;
        
        // Create image resource based on type
        $source = $this->createImageResource($file->getPathname(), $type);
        if (!$source) {
            return file_get_contents($file->getPathname());
        }
        
        // Calculate new dimensions
        $newDimensions = $this->calculateDimensions($width, $height, $this->maxWidth, $this->maxHeight);
        
        // Create new image with calculated dimensions
        $destination = imagecreatetruecolor($newDimensions['width'], $newDimensions['height']);
        
        // Preserve transparency for PNG
        if ($type === IMAGETYPE_PNG) {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
            imagefill($destination, 0, 0, $transparent);
        }
        
        // Resize
        imagecopyresampled(
            $destination, $source,
            0, 0, 0, 0,
            $newDimensions['width'], $newDimensions['height'],
            $width, $height
        );
        
        // Get output
        ob_start();
        if ($type === IMAGETYPE_PNG) {
            imagepng($destination, null, 8);
        } elseif ($type === IMAGETYPE_GIF) {
            imagegif($destination);
        } else {
            imagejpeg($destination, null, $this->quality);
        }
        $output = ob_get_clean();
        
        // Free memory
        imagedestroy($source);
        imagedestroy($destination);
        
        return $output;
    }

    /**
     * Create thumbnail
     */
    protected function createThumbnail(UploadedFile $file, array $imageInfo): string
    {
        list($width, $height, $type) = $imageInfo;
        
        $source = $this->createImageResource($file->getPathname(), $type);
        if (!$source) {
            return file_get_contents($file->getPathname());
        }
        
        // Calculate crop dimensions for thumbnail (center crop)
        $cropDimensions = $this->calculateCropDimensions($width, $height, $this->thumbWidth, $this->thumbHeight);
        
        // Create thumbnail
        $destination = imagecreatetruecolor($this->thumbWidth, $this->thumbHeight);
        
        // Preserve transparency for PNG
        if ($type === IMAGETYPE_PNG) {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
            imagefill($destination, 0, 0, $transparent);
        }
        
        // Crop and resize
        imagecopyresampled(
            $destination, $source,
            0, 0,
            $cropDimensions['x'], $cropDimensions['y'],
            $this->thumbWidth, $this->thumbHeight,
            $cropDimensions['width'], $cropDimensions['height']
        );
        
        // Get output
        ob_start();
        if ($type === IMAGETYPE_PNG) {
            imagepng($destination, null, 8);
        } elseif ($type === IMAGETYPE_GIF) {
            imagegif($destination);
        } else {
            imagejpeg($destination, null, $this->quality);
        }
        $output = ob_get_clean();
        
        imagedestroy($source);
        imagedestroy($destination);
        
        return $output;
    }

    /**
     * Create image resource from file
     */
    protected function createImageResource(string $path, int $type)
    {
        switch ($type) {
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($path);
            case IMAGETYPE_PNG:
                return imagecreatefrompng($path);
            case IMAGETYPE_GIF:
                return imagecreatefromgif($path);
            case IMAGETYPE_WEBP:
                return imagecreatefromwebp($path);
            default:
                return null;
        }
    }

    /**
     * Calculate new dimensions maintaining aspect ratio
     */
    protected function calculateDimensions(int $width, int $height, int $maxWidth, int $maxHeight): array
    {
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return ['width' => $width, 'height' => $height];
        }
        
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        
        return [
            'width' => (int) round($width * $ratio),
            'height' => (int) round($height * $ratio),
        ];
    }

    /**
     * Calculate crop dimensions for center crop
     */
    protected function calculateCropDimensions(int $srcWidth, int $srcHeight, int $destWidth, int $destHeight): array
    {
        $srcRatio = $srcWidth / $srcHeight;
        $destRatio = $destWidth / $destHeight;
        
        if ($srcRatio > $destRatio) {
            // Source is wider, crop width
            $cropWidth = (int) round($srcHeight * $destRatio);
            $cropHeight = $srcHeight;
            $x = (int) round(($srcWidth - $cropWidth) / 2);
            $y = 0;
        } else {
            // Source is taller, crop height
            $cropWidth = $srcWidth;
            $cropHeight = (int) round($srcWidth / $destRatio);
            $x = 0;
            $y = (int) round(($srcHeight - $cropHeight) / 2);
        }
        
        return [
            'x' => $x,
            'y' => $y,
            'width' => $cropWidth,
            'height' => $cropHeight,
        ];
    }

    /**
     * Get image URL with fallback
     * Always returns full domain URL
     */
    public static function getUrl(?string $path, string $default = '/images/placeholder.jpg'): string
    {
        if (!$path) {
            return StorageHelper::url($default);
        }
        
        // Use StorageHelper for consistent URL generation
        return StorageHelper::url($path);
    }

    /**
     * Get thumbnail URL with fallback
     * Always returns full domain URL
     */
    public static function getThumbnailUrl(?string $path, string $default = '/images/placeholder-thumb.jpg'): string
    {
        if (!$path) {
            return StorageHelper::url($default);
        }
        
        // Use StorageHelper for consistent URL generation
        return StorageHelper::thumbnailUrl($path);
    }
}

