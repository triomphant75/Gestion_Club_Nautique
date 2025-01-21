<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250121003648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ALTER niveau_client TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE cours DROP CONSTRAINT FK_FDCA8C9C76C50E4A');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES proprietaire (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE forfait ALTER type_forfait TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE client ALTER niveau_client TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE forfait ALTER type_forfait TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE cours DROP CONSTRAINT fk_fdca8c9c76c50e4a');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT fk_fdca8c9c76c50e4a FOREIGN KEY (proprietaire_id) REFERENCES proprietaire (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
