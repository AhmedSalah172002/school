<?php

namespace App\Service;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

class ImageService
{

    public function uploadImage($image, $folder = 'products', $oldImage = null)
    {
        if (is_string($image)) {
            return $image;
        }

        if ($oldImage !== null) {
            $this->deleteOldImage($oldImage);
        }

        return Cloudinary::upload($image->getRealPath(), [
            'folder' => "{$folder}/" . date("Y") . "/" . date("M"),
        ])->getSecurePath();
    }

    private function deleteOldImage($oldImage)
    {
        $publicId = $this->getPublicIdFromUrl($oldImage);
        if ($publicId) {
            Cloudinary::destroy($publicId);
        }
    }

    private function getPublicIdFromUrl(string $url): ?string
    {
        $parts = explode('/', $url);
        return explode('.', join('/', array_slice($parts, 7)))[0];
    }
}
