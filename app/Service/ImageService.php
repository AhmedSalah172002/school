<?php

namespace App\Service;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ImageService
{

    public function uploadImage($image, $folder = 'products', $oldImage = null)
    {
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        if ($oldImage !== null) {
            $this->deleteOldImage($oldImage);
        }

        return Cloudinary::upload($image, [
            'folder' => "{$folder}/" . date("Y") . "/" . date("M"),
        ])->getSecurePath();
    }

    private function deleteOldImage($oldImage):void
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

    public function uploadQrcode($data, $folder_name, $oldImage = null)
    {

        if ($oldImage !== null) {
            $this->deleteOldImage($oldImage);
        }

        $directoryPath = storage_path("app/public/{$folder_name}");
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true); // Create the directory if it doesn't exist
        }
        $uuid = uniqid();
        $qrCode = QrCode::size(300)->format('png')->generate(json_encode($data));
        $tempFilePath = storage_path("app/public/{$folder_name}/qrcode_{$uuid}.png");
        File::put($tempFilePath, $qrCode);
        $qrCodeLink = $this->uploadImage($tempFilePath, "{$folder_name}/qrcode");
        File::delete($tempFilePath);

        return $qrCodeLink;
    }
}
