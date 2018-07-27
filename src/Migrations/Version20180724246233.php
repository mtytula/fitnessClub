<?php

namespace DoctrineMigrations;

use App\Entity\Setting;
use App\Model\Setting as SettingModel;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Version20180724246233 extends AbstractMigration implements ContainerAwareInterface
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
        $emailNotificationProvider = new Setting();
        $emailNotificationProvider
            ->setName(SettingModel::SETTING_EMAIL_NOTIFICATION_PROVIDER)
            ->setValue('SendGrid')
            ->setOptions(['Switftmailer', 'SendGrid']);

        $emailVerificationProvider = new Setting();
        $emailVerificationProvider
            ->setName(SettingModel::SETTING_EMAIL_VERIFICATION_PROVIDER)
            ->setValue('Trumail')
            ->setOptions(['Trumail', 'QuickEmailValidation']);

        $uploadProvider = new Setting();
        $uploadProvider
            ->setName(SettingModel::SETTING_UPLOAD_PROVIDER)
            ->setValue('Cloudinary')
            ->setOptions(['Cloudinary', 'Local']);


        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $entityManager->persist($uploadProvider);
        $entityManager->persist($emailNotificationProvider);
        $entityManager->persist($emailVerificationProvider);
        $entityManager->flush();
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
