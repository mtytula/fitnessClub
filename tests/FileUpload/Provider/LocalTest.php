<?php

namespace App\Tests\FileUpload\Provider;

use App\FileUpload\Exception\ProviderException;
use App\FileUpload\Provider\Local;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LocalTest extends TestCase
{
    public function testUploadMethodShouldReturnStringOnSuccessUpload()
    {
        $targetDirectory = 'public/uploads/';
        $goodFile = new UploadedFile(__DIR__.'/../test2.png', 'test2.png', 'image/png', null, true);

        $local = new Local($targetDirectory);

        $this->assertContains('uploads/test2.png', $local->upload($goodFile));
    }

    public function testDeleteMethodShouldReturnTrueOnSuccessDelete()
    {
        $fileSystem = $this->prophesize(Filesystem::class);
        $fileSystem->willBeConstructedWith();
        $file = __DIR__.'/../test2.png';

        $fileSystem->remove($file)->willReturn(true);

        $this->assertEquals(true, $fileSystem->reveal()->remove($file));
    }

    public function testDeleteMethodShouldThrowFileNotFoundExceptionOnFailedDelete()
    {
        $file = 'public/uploads/not_existing.png';
        $fileSystem = $this->prophesize(Filesystem::class);
        $fileSystem->willBeConstructedWith();

        $fileSystem->remove($file)->willThrow(new ProviderException("File not found!"));

        $this->expectException(ProviderException::class);
        $this->expectExceptionMessage("File not found!");
        $this->getExpectedException();
        $this->assertEquals('File not found!', $fileSystem->reveal()->remove($file));
    }
}
