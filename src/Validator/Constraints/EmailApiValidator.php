<?php

namespace App\Validator\Constraints;

use App\EmailValidation\EmailValidatorContext;
use App\EmailValidation\Exception\ProviderException;
use App\EmailValidation\StrategyFactory;
use App\Model\Setting;
use App\Property\SettingProperty as Property;
use App\Repository\SettingRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EmailApiValidator extends ConstraintValidator
{
    /**
     * @var EmailValidatorContext $emailValidator
     */
    private $emailValidatorContext;

    /**
     * @var StrategyFactory $strategyFactory
     */
    private $strategyFactory;

    /**
     * @var SettingRepository
     */
    private $settingRepository;

    /**
     * EmailApiValidator constructor.
     * @param EmailValidatorContext $emailValidatorContext
     * @param StrategyFactory $strategyFactory
     * @param SettingRepository $settingRepository
     */
    public function __construct(EmailValidatorContext $emailValidatorContext, StrategyFactory $strategyFactory, SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
        $this->emailValidatorContext = $emailValidatorContext;
        $this->strategyFactory = $strategyFactory;
    }

    /**
     * @param string $value
     * @param Constraint $constraint The constraint for the validation
     * @throws ProviderException
     */
    public function validate($value, Constraint $constraint): void
    {
        $setting = $this->settingRepository->findOneByName(Setting::SETTING_EMAIL_VERIFICATION_PROVIDER);
        Property::set($setting->getName(), $setting->getValue());

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $emailVerification = Property::get(Setting::SETTING_EMAIL_VERIFICATION_PROVIDER);
        $strategy = $this->strategyFactory->getStrategy($emailVerification);
        $this->emailValidatorContext->setProvider($strategy);

        if (!$this->emailValidatorContext->validate($value)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
