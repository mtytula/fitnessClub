<?php

namespace App\SendEmail;

use App\SendEmail\Exception\ProviderException;
use App\SendEmail\Provider\SendGrid;
use App\SendEmail\Provider\SwiftMailer;

class StrategyFactory
{
    const DEFAULT_STRATEGY = SendGrid::NAME;

    /**
     * @var SwiftMailer $swiftMailer
     */
    private $swiftMailer;
    /**
     * @var SendGrid $sendGrid
     */
    private $sendGrid;

    /**
     * StrategyFactory constructor.
     * @param SwiftMailer $swiftMailer
     * @param SendGrid $sendGrid
     */
    public function __construct(SwiftMailer $swiftMailer, SendGrid $sendGrid)
    {
        $this->swiftMailer = $swiftMailer;
        $this->sendGrid = $sendGrid;
    }

    /**
     * @param string $name
     * @return ProviderInterface
     */
    public function getStrategy(string $name = self::DEFAULT_STRATEGY): ProviderInterface
    {
        switch (strtolower($name)) {
            case SwiftMailer::NAME:
                return $this->swiftMailer;
            case SendGrid::NAME:
                return $this->sendGrid;
            default:
                throw new ProviderException(sprintf('Provider: %s not found!', $name));
        }
    }
}
