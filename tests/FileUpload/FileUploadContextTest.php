<?php

namespace App\Tests\FileUpload;

use App\FileUpload\FileUploadContext;
use App\FileUpload\Provider\Cloudinary;
use App\FileUpload\Provider\Local;
use App\FileUpload\ProviderInterface;
use App\FileUpload\StrategyFactory;
use PHPUnit\Framework\TestCase;

class FileUploadContextTest extends TestCase
{
    public function testThisShouldSetLocalAsProvider()
    {
        $local = self::prophesize(Local::class);

        $context= new FileUploadContext($local->reveal());

        $this->assertInstanceOf('App\FileUpload\Provider\Local', $context->getProvider());
    }

    public function testThisShouldSetCloudinaryAsProvider()
    {
        $cloudinary = self::prophesize(Cloudinary::class);

        $context = new FileUploadContext($cloudinary->reveal());

        $this->assertInstanceOf('App\FileUpload\Provider\Cloudinary', $context->getProvider());
    }

    public function testThisShouldBeAnInstanceOfFileUploadContext()
    {
        $provider = $this->prophesize(ProviderInterface::class);

        $context = new FileUploadContext($provider->reveal());

        $this->assertInstanceOf('App\FileUpload\FileUploadContext', $context);
    }

    public function testThisShouldBeAnInstanceOfStrategyFactory()
    {
        $local = self::prophesize(Local::class);
        $cloudinary = self::prophesize(Cloudinary::class);

        $strategyFactory = new StrategyFactory($local->reveal(), $cloudinary->reveal());

        $this->assertInstanceOf('App\FileUpload\StrategyFactory', $strategyFactory);
    }
}
