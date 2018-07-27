<?php

namespace App\FileUpload;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ProviderInterface
{
    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string;

    /**
     * @param string $file
     * @return bool
     */
    public function delete(string $file): bool;
}
