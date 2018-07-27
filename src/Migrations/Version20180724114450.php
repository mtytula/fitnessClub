<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724114450 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE activities (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, slots INT NOT NULL, created_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE coaches (id SERIAL NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, created_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE opinions (id SERIAL NOT NULL, activity_id INT NOT NULL, content VARCHAR(255) NOT NULL, created_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BEAF78D081C06096 ON opinions (activity_id)');
        $this->addSql('CREATE TABLE participants (id SERIAL NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, created_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE rooms (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, capacity INT NOT NULL, created_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE schedules (id SERIAL NOT NULL, room_id INT DEFAULT NULL, coach_id INT DEFAULT NULL, activity_id INT DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_313BDC8E54177093 ON schedules (room_id)');
        $this->addSql('CREATE INDEX IDX_313BDC8E3C105691 ON schedules (coach_id)');
        $this->addSql('CREATE INDEX IDX_313BDC8E81C06096 ON schedules (activity_id)');
        $this->addSql('CREATE TABLE schedule_participant (schedule_id INT NOT NULL, participant_id INT NOT NULL, PRIMARY KEY(schedule_id, participant_id))');
        $this->addSql('CREATE INDEX IDX_4F1D81DAA40BC2D5 ON schedule_participant (schedule_id)');
        $this->addSql('CREATE INDEX IDX_4F1D81DA9D1C3019 ON schedule_participant (participant_id)');
        $this->addSql('CREATE TABLE users (id SERIAL NOT NULL, username VARCHAR(25) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(64) NOT NULL, is_active BOOLEAN NOT NULL, username_canonical VARCHAR(25) NOT NULL, email_canonical VARCHAR(64) NOT NULL, roles TEXT NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E992FC23A8 ON users (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9A0D96FBF ON users (email_canonical)');
        $this->addSql('COMMENT ON COLUMN users.roles IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE opinions ADD CONSTRAINT FK_BEAF78D081C06096 FOREIGN KEY (activity_id) REFERENCES activities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedules ADD CONSTRAINT FK_313BDC8E54177093 FOREIGN KEY (room_id) REFERENCES rooms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedules ADD CONSTRAINT FK_313BDC8E3C105691 FOREIGN KEY (coach_id) REFERENCES coaches (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedules ADD CONSTRAINT FK_313BDC8E81C06096 FOREIGN KEY (activity_id) REFERENCES activities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule_participant ADD CONSTRAINT FK_4F1D81DAA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedules (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule_participant ADD CONSTRAINT FK_4F1D81DA9D1C3019 FOREIGN KEY (participant_id) REFERENCES participants (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE opinions DROP CONSTRAINT FK_BEAF78D081C06096');
        $this->addSql('ALTER TABLE schedules DROP CONSTRAINT FK_313BDC8E81C06096');
        $this->addSql('ALTER TABLE schedules DROP CONSTRAINT FK_313BDC8E3C105691');
        $this->addSql('ALTER TABLE schedule_participant DROP CONSTRAINT FK_4F1D81DA9D1C3019');
        $this->addSql('ALTER TABLE schedules DROP CONSTRAINT FK_313BDC8E54177093');
        $this->addSql('ALTER TABLE schedule_participant DROP CONSTRAINT FK_4F1D81DAA40BC2D5');
        $this->addSql('DROP TABLE activities');
        $this->addSql('DROP TABLE coaches');
        $this->addSql('DROP TABLE opinions');
        $this->addSql('DROP TABLE participants');
        $this->addSql('DROP TABLE rooms');
        $this->addSql('DROP TABLE schedules');
        $this->addSql('DROP TABLE schedule_participant');
        $this->addSql('DROP TABLE users');
    }
}
