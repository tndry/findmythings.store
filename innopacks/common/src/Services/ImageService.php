<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Common\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Drivers\Gd\Driver;

use Intervention\Image\ImageManager;

class ImageService
{
    private string $originImage;

    private string $image;

    private string $imagePath;

    private string $placeholderImage;

    const PLACEHOLDER_IMAGE = 'images/placeholder.png';

    /**
     * @throws Exception
     */
    public function __construct($image)
    {
        $this->originImage      = $image;
        $this->placeholderImage = system_setting('placeholder', self::PLACEHOLDER_IMAGE);
        if (! is_file(public_path($this->placeholderImage))) {
            $this->placeholderImage = self::PLACEHOLDER_IMAGE;
        }
        $this->image     = $image ?: $this->placeholderImage;
        $this->imagePath = public_path($this->image);
        
        // Check if file exists in public path first
        if (! is_file($this->imagePath)) {
            // For catalog images, check storage path
            if (str_starts_with($this->image, 'catalog/') || str_starts_with($this->image, 'storage/catalog/')) {
                // Handle both formats: catalog/products/file.jpg and storage/catalog/products/file.jpg
                $relativePath = str_starts_with($this->image, 'storage/') 
                    ? substr($this->image, 8) // Remove 'storage/' prefix
                    : $this->image;
                    
                $storagePath = storage_path('app/public/' . $relativePath);
                if (is_file($storagePath)) {
                    $this->imagePath = $storagePath;
                    // Keep the image path as is for storage URL generation
                } else {
                    $this->image     = $this->placeholderImage;
                    $this->imagePath = public_path($this->placeholderImage);
                }
            } else {
                $this->image     = $this->placeholderImage;
                $this->imagePath = public_path($this->placeholderImage);
            }
        }
    }

    /**
     * @param  $image
     * @return static
     * @throws Exception
     */
    public static function getInstance($image): self
    {
        return new self($image);
    }

    /**
     * Set plugin directory name
     *
     * @param  $dirName
     * @return $this
     */
    public function setPluginDirName($dirName): static
    {
        $originImage     = $this->originImage;
        $this->imagePath = plugin_path("{$dirName}/Public").$originImage;
        if (file_exists($this->imagePath)) {
            $this->image = strtolower('plugins/'.$dirName.$originImage);
        } else {
            $this->image     = $this->placeholderImage;
            $this->imagePath = public_path($this->image);
        }

        return $this;
    }

    /**
     * Generate thumbnail image, three methods: resize, cover, contain
     *
     * @param  int  $width
     * @param  int  $height
     * @return string
     */
    public function resize(int $width = 100, int $height = 100): string
{
    try {
        // FIX: Menggunakan pathinfo untuk mendapatkan nama file & ekstensi yang bersih
        $filename  = pathinfo($this->image, PATHINFO_FILENAME);
        $extension = pathinfo($this->image, PATHINFO_EXTENSION);

        // FIX: Membangun path cache baru yang jauh lebih sederhana dan aman
        $newImage = 'cache/' . $filename . '-' . $width . 'x' . $height . '.' . $extension;

        $newImagePath = public_path($newImage);

        if (! is_file($newImagePath) || (filemtime($this->imagePath) > filemtime($newImagePath))) {
            // Pastikan direktori cache ada
            create_directories(dirname($newImagePath));

            // Buat dan simpan gambar yang di-resize
            $manager = new ImageManager(new Driver);
            $image   = $manager->read($this->imagePath);
            $image->cover($width, $height);
            $image->save($newImagePath);
        }

        return asset($newImage);
    } catch (Exception $e) {
        Log::error($e->getMessage());
        return $this->originUrl();
    }
}

    /**
     * Get original image url.
     *
     * @return string
     */
    public function originUrl(): string
    {
        // For catalog images that are stored in storage, use storage URL
        if ((str_starts_with($this->image, 'catalog/') || str_starts_with($this->image, 'storage/catalog/')) && 
            str_starts_with($this->imagePath, storage_path('app/public/'))) {
            
            // If image already starts with storage/, use as is
            if (str_starts_with($this->image, 'storage/')) {
                return asset($this->image);
            }
            
            // Otherwise, add storage/ prefix
            return asset('storage/' . $this->image);
        }
        
        return asset($this->image);
    }

    /**
     * Compress and optimize uploaded image for storage
     * Reduces file size by ~80% while maintaining quality
     *
     * @param string $uploadedFilePath The temporary uploaded file path
     * @param string $targetDirectory Target directory (e.g., 'submissions')
     * @param int $maxWidth Maximum width (default: 1200px)
     * @param int $maxHeight Maximum height (default: 1200px)
     * @param int $quality JPEG quality (default: 85)
     * @return string The optimized file path
     * @throws Exception
     */
    public static function compressUpload(
        string $uploadedFilePath, 
        string $targetDirectory, 
        int $maxWidth = 1200, 
        int $maxHeight = 1200, 
        int $quality = 85
    ): string {
        try {
            // Initialize image manager
            $manager = new ImageManager(new Driver());
            
            // Read the uploaded image
            $image = $manager->read($uploadedFilePath);
            
            // Get original dimensions
            $originalWidth = $image->width();
            $originalHeight = $image->height();
            
            // Calculate new dimensions while maintaining aspect ratio
            $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
            
            // Only resize if image is larger than max dimensions
            if ($ratio < 1) {
                $newWidth = (int) ($originalWidth * $ratio);
                $newHeight = (int) ($originalHeight * $ratio);
                $image = $image->resize($newWidth, $newHeight);
            }
            
            // Generate unique filename
            $extension = pathinfo($uploadedFilePath, PATHINFO_EXTENSION);
            $filename = uniqid() . '_compressed.' . strtolower($extension);
            $relativePath = $targetDirectory . '/' . $filename;
            $fullPath = storage_path('app/public/' . $relativePath);
            
            // Ensure directory exists
            $directory = dirname($fullPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Save compressed image with quality setting
            if (strtolower($extension) === 'jpg' || strtolower($extension) === 'jpeg') {
                $image->toJpeg($quality)->save($fullPath);
            } elseif (strtolower($extension) === 'png') {
                $image->toPng()->save($fullPath);
            } else {
                // Default to JPEG for other formats
                $image->toJpeg($quality)->save($fullPath);
            }
            
            // Log compression info
            $originalSize = filesize($uploadedFilePath);
            $compressedSize = filesize($fullPath);
            $savedPercentage = round((($originalSize - $compressedSize) / $originalSize) * 100, 1);
            
            Log::info("Image compressed: {$originalSize} bytes → {$compressedSize} bytes (saved {$savedPercentage}%)");
            
            return $relativePath;
            
        } catch (Exception $e) {
            Log::error("Image compression failed: " . $e->getMessage());
            throw new Exception("Failed to compress image: " . $e->getMessage());
        }
    }
}
