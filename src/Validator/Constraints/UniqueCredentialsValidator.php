<?php

namespace App\Validator\Constraints;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueCredentialsValidator extends ConstraintValidator
{
    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    /**
     * UniqueCredentialsValidator constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if ($this->userRepository->findOneByUsername($value) ||
            $this->userRepository->findOneByUsernameCanonical(strtolower($value))||
            $this->userRepository->findOneByEmail($value) ||
            $this->userRepository->findOneByEmailCanonical(strtolower($value))
        ) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
