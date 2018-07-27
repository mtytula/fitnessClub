<?php

namespace App\Facade;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserFacade
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoder $userPasswordEncoder
     */
    private $userPasswordEncoder;

    /**
     * ParticipantFacade constructor
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoder $userPasswordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoder $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $email
     * @return User
     */
    public function register(string $username, string $password, string $email): User
    {
        $user = new User();
        $user
            ->setUsername($username)
            ->setPassword(
                $this->userPasswordEncoder->encodePassword($user, $password)
            )
            ->setEmail($email)
            ->setEmailCanonical(strtolower($email))
            ->setUsernameCanonical(strtolower($username))
            ->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
