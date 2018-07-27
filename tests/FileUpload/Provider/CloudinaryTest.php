<?php

namespace App\Tests\FileUpload\Provider;

use App\FileUpload\Exception\ProviderException;
use App\FileUpload\Provider\UploadProxy;
use PHPUnit\Framework\TestCase;
use Speicher210\CloudinaryBundle\Cloudinary\Cloudinary;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CloudinaryTest extends TestCase
{
    private $fileName;
    public function testUploadMethodShouldReturnStringOnSuccessUpload()
    {
        $goodFile = new UploadedFile(__DIR__ . '/../test2.png', 'test2.png', 'image/png', null, true);
        $this->fileName = $goodFile->getFilename();
        $config = [];
        $cloudinary = $this->prophesize(Cloudinary::class);
        $cloudinary->willBeConstructedWith($config);

        $uploaderProxy = $this->prophesize(UploadProxy::class);
        $uploaderProxy->upload($goodFile, ["public_id" => $this->fileName])->willReturn([
            "public_id" => $this->fileName,
            "url" => '/image/upload/'
        ]);
        $cloudinary = new \App\FileUpload\Provider\Cloudinary($uploaderProxy->reveal());

        $this->assertContains('/image/upload/', $cloudinary->upload($goodFile));
    }

    public function testUploadMethodShouldThrowProviderExceptionOnFailUpload()
    {
        $goodFile = new UploadedFile(__DIR__ . '/../test2.png', 'test2.png', 'image/png', null, true);
        $this->fileName = $goodFile->getFilename();
        $config = [];
        $cloudinary = $this->prophesize(Cloudinary::class);
        $cloudinary->willBeConstructedWith($config);

        $uploaderProxy = $this->prophesize(UploadProxy::class);
        $uploaderProxy->upload(
            $goodFile,
            ["public_id" => $this->fileName]
        )->willThrow(
            new ProviderException("Upload provider have failed, during uploading a file!")
        );
        $cloudinary = new \App\FileUpload\Provider\Cloudinary($uploaderProxy->reveal());

        $this->expectException(ProviderException::class);
        $this->expectExceptionMessage('Upload provider have failed, during uploading a file!');
        $this->getExpectedException();
        $this->assertEquals('Upload provider have failed, during uploading a file!', $cloudinary->upload($goodFile));
    }

    public function testDeleteMethodShouldReturnTrueOnSuccessDestroy()
    {
        $file = __DIR__ . '/../test2.png';
        $pathinfo = pathinfo($file);
        $fileName = $pathinfo['filename'];
        $config = [];
        $cloudinary = $this->prophesize(Cloudinary::class);
        $cloudinary->willBeConstructedWith($config);

        $uploaderProxy = $this->prophesize(UploadProxy::class);
        $uploaderProxy->destroy($fileName)->willReturn([
            "result" => 'true'
        ]);
        $cloudinary = new \App\FileUpload\Provider\Cloudinary($uploaderProxy->reveal());

        $this->assertEquals(true, $cloudinary->delete($fileName));
    }

    public function testDeleteMethodShouldThrowProviderExceptionOnFailedDestroy()
    {
        $file = __DIR__ . '/../test2.png';
        $pathinfo = pathinfo($file);
        $fileName = $pathinfo['filename'];
        $config = [];
        $cloudinary = $this->prophesize(Cloudinary::class);
        $cloudinary->willBeConstructedWith($config);

        $uploaderProxy = $this->prophesize(UploadProxy::class);
        $uploaderProxy->destroy($fileName)->willThrow(new ProviderException("File not found!"));
        $cloudinary = new \App\FileUpload\Provider\Cloudinary($uploaderProxy->reveal());

        $this->expectException(ProviderException::class);
        $this->expectExceptionMessage("File not found!");
        $this->getExpectedException();
        $this->assertEquals("File not found!", $cloudinary->delete($fileName));
    }
}
