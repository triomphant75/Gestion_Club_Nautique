<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110090530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement ADD forfait_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paiement ADD location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paiement ADD facture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E906D5F2C FOREIGN KEY (forfait_id) REFERENCES forfait (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E64D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1DC7A1E906D5F2C ON paiement (forfait_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1DC7A1E64D218E ON paiement (location_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1DC7A1E7F2DEE08 ON paiement (facture_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT FK_B1DC7A1E906D5F2C');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT FK_B1DC7A1E64D218E');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT FK_B1DC7A1E7F2DEE08');
        $this->addSql('DROP INDEX UNIQ_B1DC7A1E906D5F2C');
        $this->addSql('DROP INDEX UNIQ_B1DC7A1E64D218E');
        $this->addSql('DROP INDEX UNIQ_B1DC7A1E7F2DEE08');
        $this->addSql('ALTER TABLE paiement DROP forfait_id');
        $this->addSql('ALTER TABLE paiement DROP location_id');
        $this->addSql('ALTER TABLE paiement DROP facture_id');
    }
}
