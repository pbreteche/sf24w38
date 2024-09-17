<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240917122121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE ingredient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE grumpy_pizza_ingredient (grumpy_pizza_id INT NOT NULL, ingredient_id INT NOT NULL, PRIMARY KEY(grumpy_pizza_id, ingredient_id))');
        $this->addSql('CREATE INDEX IDX_BC86F972FC612CAA ON grumpy_pizza_ingredient (grumpy_pizza_id)');
        $this->addSql('CREATE INDEX IDX_BC86F972933FE08C ON grumpy_pizza_ingredient (ingredient_id)');
        $this->addSql('CREATE TABLE ingredient (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE grumpy_pizza_ingredient ADD CONSTRAINT FK_BC86F972FC612CAA FOREIGN KEY (grumpy_pizza_id) REFERENCES grumpy_pizza (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grumpy_pizza_ingredient ADD CONSTRAINT FK_BC86F972933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE ingredient_id_seq CASCADE');
        $this->addSql('ALTER TABLE grumpy_pizza_ingredient DROP CONSTRAINT FK_BC86F972FC612CAA');
        $this->addSql('ALTER TABLE grumpy_pizza_ingredient DROP CONSTRAINT FK_BC86F972933FE08C');
        $this->addSql('DROP TABLE grumpy_pizza_ingredient');
        $this->addSql('DROP TABLE ingredient');
    }
}
