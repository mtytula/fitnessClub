<?php

namespace App\Tests\EmailValidation\Provider;

use App\EmailValidation\Provider\Trumail;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class TrumailTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testValidateMethodShouldReturnFalseOnWrongEmailAddress(): void
    {
        $wrongEmail = 'wrong@assss.com';
        $guzzleClient = $this->prophesize(Client::class);
        $trumail = $this->prophesize(Trumail::class)
            ->willBeConstructedWith([$guzzleClient->reveal()]);

        $trumail->validate($wrongEmail)->willReturn(false);

        $this->assertFalse($trumail->reveal()->validate($wrongEmail));
    }

    /**
     * @throws \Exception
     */
    public function testValidateMethodShouldReturnTrueOnCorrectEmailAddress()
    {
        $correctEmail = 'mateusz.koziol15@gmail.com';
        $guzzleClient = $this->prophesize(Client::class);
        $trumail = $this->prophesize(Trumail::class)
            ->willBeConstructedWith([$guzzleClient->reveal()]);

        $trumail->validate($correctEmail)->willReturn(true);

        $this->assertTrue($trumail->reveal()->validate($correctEmail));
    }

    public function testReturnFalseOnExistingArrayKeyNotPassingValidationRule()
    {
        $guzzleClient = $this->prophesize(Client::class);
        $trumail = new Trumail($guzzleClient->reveal());
        $mockArray = [
            'key' => 'value',
            'deliverable' => 'false'
        ];

        $trumail->process($mockArray);

        $this->assertFalse($trumail->process($mockArray));
    }

    public function testReturnTrueOnExistingArrayKeyWhichMatchPassRule()
    {
        $guzzleClient = $this->prophesize(Client::class);
        $trumail = new Trumail($guzzleClient->reveal());
        $mockArray = [
            'key' => 'value',
            'deliverable' => 'true'
        ];

        $trumail->process($mockArray);

        $this->assertTrue($trumail->process($mockArray));
    }

    public function testReturnFalseOnWrongArrayGiven()
    {
        $guzzleClient = $this->prophesize(Client::class);
        $trumail = new Trumail($guzzleClient->reveal());
        $mockArray = [
            'reallybadkey' => 'reallybadvalue',
            'deliverable' => [
                'nestedThing' => 'nestedThingValue'
            ]
        ];

        $trumail->process($mockArray);

        $this->assertFalse($trumail->process($mockArray));
    }
}
