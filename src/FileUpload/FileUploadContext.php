<?php

namespace App\FileUpload;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadContext
{
    /**
     * @var ProviderInterface $provider
     */
    private $provider;

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        return $this->provider->upload($file);
    }

    /**
     * @param string $file
     * @return bool
     */
    public function delete(string $file): bool
    {
        return $this->provider->delete($file);
    }

    /**
     * FileUploadContext constructor.
     * @param ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider= $provider;
    }

    public function getProvider(): ProviderInterface
    {
        return $this->provider;
    }
}
