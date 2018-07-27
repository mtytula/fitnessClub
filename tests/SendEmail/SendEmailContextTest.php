<?php

namespace App\Tests\SendEmail;

use App\SendEmail\Provider\SwiftMailer;
use App\SendEmail\Provider\SendGrid;
use App\SendEmail\SendEmailContext;
use PHPUnit\Framework\TestCase;

class SendEmailContextTest extends TestCase
{
    public function testThisShouldBeAnInstanceOfSendEmailContext()
    {
        $swiftMailer = $this->prophesize(SwiftMailer::class);

        $context = new SendEmailContext($swiftMailer->reveal());

        $this->assertInstanceOf('App\SendEmail\SendEmailContext', $context);
    }

    public function testThisShouldSetSwiftMailerAsProvider()
    {
        $swiftMailer = $this->prophesize(SwiftMailer::class);

        $context = new SendEmailContext($swiftMailer->reveal());

        $this->assertInstanceOf('App\SendEmail\Provider\SwiftMailer', $context->getProvider());
    }

    public function testThisShouldSetSendGridAsProvider()
    {
        $sendGrid = $this->prophesize(SendGrid::class);

        $context = new SendEmailContext($sendGrid->reveal());

        $this->assertInstanceOf('App\SendEmail\Provider\SendGrid', $context->getProvider());
    }
}
