<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250120165057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ALTER niveau_client TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE cours DROP CONSTRAINT FK_FDCA8C9CA234A5D3');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CA234A5D3 FOREIGN KEY (moniteur_id) REFERENCES moniteur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE forfait ALTER type_forfait TYPE VARCHAR(255)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C26F74BB12A5F6CC ON user_club (email_user)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE forfait ALTER type_forfait TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE client ALTER niveau_client TYPE VARCHAR(255)');
        $this->addSql('DROP INDEX UNIQ_C26F74BB12A5F6CC');
        $this->addSql('ALTER TABLE cours DROP CONSTRAINT fk_fdca8c9ca234a5d3');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT fk_fdca8c9ca234a5d3 FOREIGN KEY (moniteur_id) REFERENCES moniteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
