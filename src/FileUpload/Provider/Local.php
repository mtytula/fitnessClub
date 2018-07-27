<?php

namespace App\FileUpload\Provider;

use App\FileUpload\Exception\ProviderException;
use App\FileUpload\ProviderInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Local implements ProviderInterface
{
    const NAME = 'local';

    /**
     * @param $targetDirectory
     */
    private $targetDirectory;

    /**
     * Local constructor.
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
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
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (ProviderException $e) {
            throw new ProviderException("Upload provider have failed, during uploading a file!");
        }

        return 'uploads/'.$fileName;
    }

    /**
     * @param string $file
     * @return bool
     * @throws ProviderException
     */
    public function delete(string $file): bool
    {
        $fileSystem = new FileSystem();
        if (!$fileSystem->exists($file)) {
            throw new ProviderException("File not found!");
        };

        $fileSystem->remove($file);

        return true;
    }
}
