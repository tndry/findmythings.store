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
            if (str_starts_with($this->image, 'catalog/')) {
                $storagePath = storage_path('app/public/' . $this->image);
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
        if (str_starts_with($this->image, 'catalog/') && 
            str_starts_with($this->imagePath, storage_path('app/public/'))) {
            return asset('storage/' . $this->image);
        }
        
        return asset($this->image);
    }
}
