<?php

namespace App\Tests\EmailValidation;

use App\EmailValidation\Provider\QuickEmailValidation;
use App\EmailValidation\Provider\Trumail;
use App\EmailValidation\StrategyFactory;
use App\EmailValidation\Exception\ProviderException;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use QuickEmailVerification\Client as QuickEmailClient;

class StrategyFactoryTest extends TestCase
{
    public function testThisShouldBeAnInstanceOfStrategyFactory()
    {
        $quickEmailClient = $this->prophesize(QuickEmailClient::class)
            ->willBeConstructedWith([QuickEmailValidation::API_KEY]);
        $quickEmailValidation = $this->prophesize(QuickEmailValidation::class)
            ->willBeConstructedWith([$quickEmailClient->reveal()]);
        $guzzleClient = $this->prophesize(Client::class);
        $trumail = $this->prophesize(Trumail::class)
            ->willBeConstructedWith([$guzzleClient->reveal()]);

        $strategyFactory = new StrategyFactory($trumail->reveal(), $quickEmailValidation->reveal());

        $this->assertInstanceOf('App\EmailValidation\StrategyFactory', $strategyFactory);
    }

    public function testShouldThrowExceptionOnBadProviderName()
    {
        $guzzleClient = $this->prophesize(Client::class);
        $trumail = $this->prophesize(Trumail::class)
            ->willBeConstructedWith([$guzzleClient->reveal()]);
        $quickEmailClient = $this->prophesize(QuickEmailClient::class)
            ->willBeConstructedWith([QuickEmailValidation::API_KEY]);
        $quickEmailValidation = $this->prophesize(QuickEmailValidation::class)
            ->willBeConstructedWith([$quickEmailClient->reveal()]);

        $strategyFactory = new StrategyFactory($trumail->reveal(), $quickEmailValidation->reveal());

        $this->expectException(ProviderException::class);
        $strategyFactory->getStrategy('sdfs');
    }

    public function testShouldSetStrategyToTrumail()
    {
        $guzzleClient = $this->prophesize(Client::class);
        $trumail = $this->prophesize(Trumail::class)
            ->willBeConstructedWith([$guzzleClient->reveal()]);
        $quickEmailClient = $this->prophesize(QuickEmailClient::class)
            ->willBeConstructedWith([QuickEmailValidation::API_KEY]);
        $quickEmailValidation = $this->prophesize(QuickEmailValidation::class)
            ->willBeConstructedWith([$quickEmailClient->reveal()]);
        $strategyFactory = new StrategyFactory($trumail->reveal(), $quickEmailValidation->reveal());

        $strategy = $strategyFactory->getStrategy(Trumail::NAME);

        $this->assertInstanceOf(Trumail::class, $strategy);
    }

    public function testShouldSetStrategyToQuickEmailValidation()
    {
        $guzzleClient = $this->prophesize(Client::class);
        $trumail = $this->prophesize(Trumail::class)
            ->willBeConstructedWith([$guzzleClient->reveal()]);
        $quickEmailClient = $this->prophesize(QuickEmailClient::class)
            ->willBeConstructedWith([QuickEmailValidation::API_KEY]);
        $quickEmailValidation = $this->prophesize(QuickEmailValidation::class)
            ->willBeConstructedWith([$quickEmailClient->reveal()]);
        $strategyFactory = new StrategyFactory($trumail->reveal(), $quickEmailValidation->reveal());

        $strategy = $strategyFactory->getStrategy(QuickEmailValidation::NAME);

        $this->assertInstanceOf(QuickEmailValidation::class, $strategy);
    }
}
