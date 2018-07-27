<?php declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724114810 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }

    public function postUp(Schema $schema)
    {
        $user = new User();
        $user
            ->setEmail('admin@gmail.com')
            ->setCreatedDate(new \DateTime())
            ->setIsActive(true)
            ->setPassword(
                $this->container
                    ->get('Symfony\Component\Security\Core\Encoder\UserPasswordEncoder')
                    ->encodePassword($user, 'admin')
            )
            ->setUsername('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setEmailCanonical(strtolower('admin@gmail.com'))
            ->setUsernameCanonical(strtolower('admin'));

        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $entityManager->persist($user);
        $entityManager->flush();
    }
}
