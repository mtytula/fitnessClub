<?php

namespace App\FileUpload\Provider;

use App\FileUpload\Exception\ProviderException;
use App\FileUpload\ProviderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Cloudinary implements ProviderInterface
{
    const NAME = 'cloudinary';

    /**
     * @var UploadProxy
     */
    private $uploader;

    /**
     * Cloudinary constructor.
     * @param UploadProxy $uploader
     */
    public function __construct(UploadProxy $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param UploadedFile $file
     * @return string
     * @throws ProviderException
     */
    public function upload(UploadedFile $file): string
    {
        /**
         * For test purposes change to getClientOriginalName()
         */

        $fileName = $file->getFilename();
        try {
            $upload = $this->uploader->upload($file, ["public_id" => $fileName]);
        } catch (ProviderException $e) {
            throw new ProviderException("Upload provider have failed, during uploading a file!");
        }

        return $upload["url"];
    }

    /**
     * @param string $file
     * @return bool
     * @throws ProviderException
     */
    public function delete(string $file): bool
    {
        $pathinfo = pathinfo($file);
        $fileName = $pathinfo['filename'];
        $result = $this->uploader->destroy($fileName);

        if (in_array("not found", $result)) {
            throw new ProviderException("File not found!");
        }

        return true;
    }
}
