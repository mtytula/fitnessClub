<?php

namespace App\Tests\EmailValidation\Provider;

use App\EmailValidation\Provider\QuickEmailValidation;
use PHPUnit\Framework\TestCase;
use QuickEmailVerification\Client as QuickEmailClient;

class QuickEmailValidationTest extends TestCase
{
    public function testValidateMethodShouldReturnFalseOnWrongEmailAddress(): void
    {
        $wrongEmail = 'wrong@assss.com';
        $quickEmailClient = $this->prophesize(QuickEmailClient::class)
            ->willBeConstructedWith([QuickEmailValidation::API_KEY]);
        $quickEmailValidation = $this->prophesize(QuickEmailValidation::class)
            ->willBeConstructedWith([$quickEmailClient->reveal()]);

        $quickEmailValidation->validate($wrongEmail)->willReturn(false);

        $this->assertFalse($quickEmailValidation->reveal()->validate($wrongEmail));
    }

    public function testValidateMethodShouldReturnTrueOnCorrectEmailAddress(): void
    {
        $correctEmail = 'mateusz.koziol15@gmail.com';
        $quickEmailClient = $this->prophesize(QuickEmailClient::class)
            ->willBeConstructedWith([QuickEmailValidation::API_KEY]);
        $quickEmailValidation = $this->prophesize(QuickEmailValidation::class)
            ->willBeConstructedWith([$quickEmailClient->reveal()]);

        $quickEmailValidation->validate($correctEmail)->willReturn(true);

        $this->assertTrue($quickEmailValidation->reveal()->validate($correctEmail));
    }

    public function testReturnFalseOnNotExistingArrayKeyNotPassingValidationRule()
    {
        $quickEmailClient = $this->prophesize(QuickEmailClient::class)
            ->willBeConstructedWith([QuickEmailValidation::API_KEY]);
        $quickEmailValidation = new QuickEmailValidation($quickEmailClient->reveal());
        $mockArray = [
            'key' => 'value',
            'result' => 'invalid'
        ];

        $quickEmailValidation->process($mockArray);

        $this->assertFalse($quickEmailValidation->process($mockArray));
    }

    public function testReturnTrueOnExistingArrayKeyWhichMatchPassRule()
    {
        $quickEmailClient = $this->prophesize(QuickEmailClient::class)
            ->willBeConstructedWith([QuickEmailValidation::API_KEY]);
        $quickEmailValidation = new QuickEmailValidation($quickEmailClient->reveal());
        $mockArray = [
            'key' => 'value',
            'result' => 'valid'
        ];

        $quickEmailValidation->process($mockArray);

        $this->assertTrue($quickEmailValidation->process($mockArray));
    }

    public function testReturnFalseOnWrongArrayGiven()
    {
        $quickEmailClient = $this->prophesize(QuickEmailClient::class)
            ->willBeConstructedWith([QuickEmailValidation::API_KEY]);
        $quickEmailValidation = new QuickEmailValidation($quickEmailClient->reveal());
        $mockArray = [
            'reallybadkey' => 'reallybadvalue',
            'deliverable' => [
                'nestedThing' => 'nestedThingValue'
            ]
        ];

        $quickEmailValidation->process($mockArray);

        $this->assertFalse($quickEmailValidation->process($mockArray));
    }
}
