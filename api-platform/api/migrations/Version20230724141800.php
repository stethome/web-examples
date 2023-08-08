<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230724141800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add SecurityUser and UserData';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE security_users (uuid UUID NOT NULL, user_data_uuid UUID NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F83F4643E7927C74 ON security_users (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F83F46436AD016A0 ON security_users (user_data_uuid)');
        $this->addSql('COMMENT ON COLUMN security_users.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN security_users.user_data_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN security_users.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE users (uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('COMMENT ON COLUMN users.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE security_users ADD CONSTRAINT FK_F83F46436AD016A0 FOREIGN KEY (user_data_uuid) REFERENCES users (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE security_users DROP CONSTRAINT FK_F83F46436AD016A0');
        $this->addSql('DROP TABLE security_users');
        $this->addSql('DROP TABLE users');
    }
}
