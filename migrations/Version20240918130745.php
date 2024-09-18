<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240918130745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE pizza_export_config_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE pizza_export_config (id INT NOT NULL, fields TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN pizza_export_config.fields IS \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE api_user ADD pizza_export_config_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE api_user ADD CONSTRAINT FK_AC64A0BAC66853AB FOREIGN KEY (pizza_export_config_id) REFERENCES pizza_export_config (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AC64A0BAC66853AB ON api_user (pizza_export_config_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE api_user DROP CONSTRAINT FK_AC64A0BAC66853AB');
        $this->addSql('DROP SEQUENCE pizza_export_config_id_seq CASCADE');
        $this->addSql('DROP TABLE pizza_export_config');
        $this->addSql('DROP INDEX IDX_AC64A0BAC66853AB');
        $this->addSql('ALTER TABLE api_user DROP pizza_export_config_id');
    }
}
