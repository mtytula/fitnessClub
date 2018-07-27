<?php

namespace App\FileUpload;

use App\FileUpload\Exception\ProviderException;
use App\FileUpload\Provider\Cloudinary;
use App\FileUpload\Provider\Local;

class StrategyFactory
{
    const DEFAULT_STRATEGY = Cloudinary::NAME;

    /**
     * @var Local $local
     */
    private $local;

    /**
     * @var Cloudinary $cloudinary
     */
    private $cloudinary;

    /**
     * StrategyFactory constructor.
     * @param Local $local
     * @param Cloudinary $cloudinary
     */
    public function __construct(Local $local, Cloudinary $cloudinary)
    {
        $this->local = $local;
        $this->cloudinary = $cloudinary;
    }

    /**
     * @param string $name
     * @return ProviderInterface
     * @throws ProviderException
     */
    public function getStrategy(string $name = self::DEFAULT_STRATEGY): ProviderInterface
    {
        switch (strtolower($name)) {
            case Local::NAME:
                return $this->local;
            case Cloudinary::NAME:
                return $this->cloudinary;
            default:
                throw new ProviderException('You have entered not existing upload provider!');
        }
    }
}
