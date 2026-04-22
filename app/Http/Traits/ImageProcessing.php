<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

trait ImageProcessing
{
    /**
     * Resize the uploaded image to 80x80 and save it.
     *
     * @param string $imagePath Path to the uploaded image
     * @param string $savePath Path where the resized image will be saved
     * @return bool
     */
    public function createGalleryImage($imagePath, $savePath, $height=80, $width=80)
    {
        // Load the image from the given path
        $image = Image::make($imagePath);

        // Resize the image to 80x80
        $image->resize($height, $width);

        // Save the resized image to the specified path
        $image->save(base_path('public/') . $savePath);

        return true;
    }
}
