<?php

namespace App\EmailValidation;

use App\EmailValidation\Provider\QuickEmailValidation;
use App\EmailValidation\Provider\Trumail;
use App\EmailValidation\Exception\ProviderException;

class StrategyFactory
{
    const DEFAULT_STRATEGY = QuickEmailValidation::NAME;

    /** @var Trumail $trumail */
    private $trumail;

    /** @var QuickEmailValidation $quickEmailValidation */
    private $quickEmailValidation;

    /**
     * StrategyFactory constructor.
     * @param Trumail $trumail
     * @param QuickEmailValidation $quickEmailValidation
     */
    public function __construct(Trumail $trumail, QuickEmailValidation $quickEmailValidation)
    {
        $this->trumail = $trumail;
        $this->quickEmailValidation = $quickEmailValidation;
    }

    /**
     * @param string $name
     * @return ProviderInterface
     * @throws ProviderException
     */
    public function getStrategy(string $name = self::DEFAULT_STRATEGY): ProviderInterface
    {
        switch (strtolower($name)) {
            case Trumail::NAME:
                return $this->trumail;
            case QuickEmailValidation::NAME:
                return $this->quickEmailValidation;
            default:
                throw new ProviderException("Provider".$name." not found!");
        }
    }
}
