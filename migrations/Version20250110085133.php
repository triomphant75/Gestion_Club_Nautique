<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110085133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT fk_fe8664102a4c4478');
        $this->addSql('DROP INDEX uniq_fe8664102a4c4478');
        $this->addSql('ALTER TABLE facture DROP paiement_id');
        $this->addSql('ALTER TABLE forfait DROP CONSTRAINT fk_bbb5c4822a4c4478');
        $this->addSql('DROP INDEX uniq_bbb5c4822a4c4478');
        $this->addSql('ALTER TABLE forfait DROP paiement_id');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT fk_5e9e89cb2a4c4478');
        $this->addSql('DROP INDEX uniq_5e9e89cb2a4c4478');
        $this->addSql('ALTER TABLE location DROP paiement_id');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT fk_b1dc7a1e64d218e');
        $this->addSql('DROP INDEX uniq_b1dc7a1e64d218e');
        $this->addSql('ALTER TABLE paiement DROP location_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE forfait ADD paiement_id INT NOT NULL');
        $this->addSql('ALTER TABLE forfait ADD CONSTRAINT fk_bbb5c4822a4c4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_bbb5c4822a4c4478 ON forfait (paiement_id)');
        $this->addSql('ALTER TABLE paiement ADD location_id INT NOT NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT fk_b1dc7a1e64d218e FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_b1dc7a1e64d218e ON paiement (location_id)');
        $this->addSql('ALTER TABLE location ADD paiement_id INT NOT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT fk_5e9e89cb2a4c4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_5e9e89cb2a4c4478 ON location (paiement_id)');
        $this->addSql('ALTER TABLE facture ADD paiement_id INT NOT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT fk_fe8664102a4c4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_fe8664102a4c4478 ON facture (paiement_id)');
    }
}
