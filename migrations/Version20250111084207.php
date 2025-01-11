<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250111084207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE compagne_proprietaire_id_seq CASCADE');
        $this->addSql('ALTER TABLE compagne_proprietaire ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE compagne_proprietaire ADD CONSTRAINT FK_3463D9A6BF396750 FOREIGN KEY (id) REFERENCES user_club (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE forfait ADD is_confirmed BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE compagne_proprietaire_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE forfait DROP is_confirmed');
        $this->addSql('ALTER TABLE compagne_proprietaire DROP CONSTRAINT FK_3463D9A6BF396750');
        $this->addSql('CREATE SEQUENCE compagne_proprietaire_id_seq');
        $this->addSql('SELECT setval(\'compagne_proprietaire_id_seq\', (SELECT MAX(id) FROM compagne_proprietaire))');
        $this->addSql('ALTER TABLE compagne_proprietaire ALTER id SET DEFAULT nextval(\'compagne_proprietaire_id_seq\')');
    }
}
