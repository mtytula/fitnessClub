<?php

namespace App\Tests\EmailValidation;

use App\EmailValidation\EmailValidatorContext;
use App\EmailValidation\Provider\QuickEmailValidation;
use App\EmailValidation\Provider\Trumail;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use QuickEmailVerification\Client as QuickEmailClient;

class EmailValidatorContextTest extends TestCase
{
    public function testThisShouldSetTrumailAsProvider()
    {
        $guzzleClient = $this->prophesize(Client::class);
        $trumail = $this->prophesize(Trumail::class)
            ->willBeConstructedWith([$guzzleClient->reveal()]);
        $context = new EmailValidatorContext();

        $context->setProvider($trumail->reveal());

        $this->assertInstanceOf('App\EmailValidation\Provider\Trumail', $context->getProvider());
    }

    public function testThisShouldSetQuickEmailVerificationAsProvider()
    {
        $quickEmailClient = $this->prophesize(QuickEmailClient::class)
            ->willBeConstructedWith([QuickEmailValidation::API_KEY]);
        $quickEmailValidation = $this->prophesize(QuickEmailValidation::class)
            ->willBeConstructedWith([$quickEmailClient->reveal()]);
        $context = new EmailValidatorContext();

        $context->setProvider($quickEmailValidation->reveal());

        $this->assertInstanceOf('App\EmailValidation\Provider\QuickEmailValidation', $context->getProvider());
    }

    public function testThisShouldBeAnInstanceOfEmailValidatorContext()
    {
        $context = new EmailValidatorContext();

        $this->assertInstanceOf('App\EmailValidation\EmailValidatorContext', $context);
    }
}
