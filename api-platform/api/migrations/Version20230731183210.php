<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731183210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exams (uuid UUID NOT NULL, owner UUID NOT NULL, external_id VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_69311328CF60E67C ON exams (owner)');
        $this->addSql('COMMENT ON COLUMN exams.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN exams.owner IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN exams.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE exams ADD CONSTRAINT FK_69311328CF60E67C FOREIGN KEY (owner) REFERENCES users (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exams DROP CONSTRAINT FK_69311328CF60E67C');
        $this->addSql('DROP TABLE exams');
    }
}
