<?php

namespace App\Tests\SendEmail;

use App\SendEmail\Provider\SendGrid;
use App\SendEmail\Provider\SwiftMailer;
use App\SendEmail\StrategyFactory;
use PHPUnit\Framework\TestCase;

class StrategyFactoryTest extends TestCase
{
    public function testThisShouldBeAnInstanceOfStrategyFactory()
    {
        $sendGrid = $this->prophesize(SendGrid::class);
        $swiftMailer = $this->prophesize(SwiftMailer::class);

        $strategyFactory = new StrategyFactory($swiftMailer->reveal(), $sendGrid->reveal());

        $this->assertInstanceOf('App\SendEmail\StrategyFactory', $strategyFactory);
    }
}