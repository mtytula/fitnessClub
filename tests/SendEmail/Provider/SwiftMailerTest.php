<?php

namespace App\Tests\SendEmail\Provider;

use PHPUnit\Framework\TestCase;

class SwiftMailerTest extends TestCase
{
    public function testCheckIfCallSendMethod()
    {
        $messageMock = $this
            ->prophesize(\Swift_Message::class);

        $swiftMailer = $this
            ->getMockBuilder(\Swift_Mailer::class)
            ->setMethods(['send'])
            ->disableOriginalConstructor()
            ->getMock();

        $swiftMailer->expects($this->once())
            ->method('send');

        $swiftMailer->send($messageMock->reveal());
    }
}
