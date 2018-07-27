<?php

namespace App\FileUpload\Provider;

use Speicher210\CloudinaryBundle\Cloudinary\Uploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadProxy
{
    /**
     * @param UploadedFile $file
     * @param array $options
     * @return mixed
     */
    public function upload(UploadedFile $file, $options = [])
    {
        return Uploader::upload($file, $options);
    }

    /**
     * @param string $publicId
     * @param array $options
     * @return mixed
     */
    public function destroy(string $publicId, $options = [])
    {
        return Uploader::destroy($publicId, $options);
    }
}
