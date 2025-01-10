<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110044720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compagne_proprietaire (id SERIAL NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cours (id SERIAL NOT NULL, moniteur_id INT NOT NULL, proprietaire_id INT NOT NULL, date_cours DATE NOT NULL, heure_debut_cours TIME(0) WITHOUT TIME ZONE NOT NULL, heure_fin_cours TIME(0) WITHOUT TIME ZONE NOT NULL, etat_cours VARCHAR(255) NOT NULL, nombre_de_place INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FDCA8C9CA234A5D3 ON cours (moniteur_id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9C76C50E4A ON cours (proprietaire_id)');
        $this->addSql('CREATE TABLE cours_forfait (cours_id INT NOT NULL, forfait_id INT NOT NULL, PRIMARY KEY(cours_id, forfait_id))');
        $this->addSql('CREATE INDEX IDX_F8B3ADF97ECF78B0 ON cours_forfait (cours_id)');
        $this->addSql('CREATE INDEX IDX_F8B3ADF9906D5F2C ON cours_forfait (forfait_id)');
        $this->addSql('CREATE TABLE cours_materiel (cours_id INT NOT NULL, materiel_id INT NOT NULL, PRIMARY KEY(cours_id, materiel_id))');
        $this->addSql('CREATE INDEX IDX_DF461E4C7ECF78B0 ON cours_materiel (cours_id)');
        $this->addSql('CREATE INDEX IDX_DF461E4C16880AAF ON cours_materiel (materiel_id)');
        $this->addSql('CREATE TABLE facture (id SERIAL NOT NULL, paiement_id INT NOT NULL, num_facture VARCHAR(255) NOT NULL, montant_total DOUBLE PRECISION NOT NULL, adresse_etablissement VARCHAR(255) NOT NULL, date_facture DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE8664102A4C4478 ON facture (paiement_id)');
        $this->addSql('COMMENT ON COLUMN facture.date_facture IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE location (id SERIAL NOT NULL, client_id INT NOT NULL, paiement_id INT NOT NULL, materiel_id INT NOT NULL, duree_location TIME(0) WITHOUT TIME ZONE NOT NULL, prix_location DOUBLE PRECISION NOT NULL, prix_location_remise DOUBLE PRECISION NOT NULL, date_location DATE NOT NULL, etat_location VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E9E89CB19EB6921 ON location (client_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E9E89CB2A4C4478 ON location (paiement_id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB16880AAF ON location (materiel_id)');
        $this->addSql('CREATE TABLE materiel (id SERIAL NOT NULL, type_materiel VARCHAR(255) NOT NULL, caracteristique TEXT NOT NULL, num_serie VARCHAR(255) NOT NULL, date_mise_en_service DATE NOT NULL, etat_materiel VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moniteur (id INT NOT NULL, diplome VARCHAR(255) NOT NULL, specialite VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE paiement (id SERIAL NOT NULL, client_id INT NOT NULL, facture_id INT NOT NULL, location_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, date_paiement DATE NOT NULL, mode_paiement VARCHAR(255) NOT NULL, statut_paiement VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B1DC7A1E19EB6921 ON paiement (client_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1DC7A1E7F2DEE08 ON paiement (facture_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1DC7A1E64D218E ON paiement (location_id)');
        $this->addSql('COMMENT ON COLUMN paiement.date_paiement IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE panne (id SERIAL NOT NULL, materiel_id INT NOT NULL, description VARCHAR(255) NOT NULL, date_panne DATE NOT NULL, date_debut_reparation DATE NOT NULL, date_fin_reparation DATE DEFAULT NULL, etat_panne VARCHAR(255) NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3885B26016880AAF ON panne (materiel_id)');
        $this->addSql('CREATE TABLE participation (id SERIAL NOT NULL, client_id INT NOT NULL, cours_id INT NOT NULL, date_inscription_cours DATE NOT NULL, statut_participant VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AB55E24F19EB6921 ON participation (client_id)');
        $this->addSql('CREATE INDEX IDX_AB55E24F7ECF78B0 ON participation (cours_id)');
        $this->addSql('CREATE TABLE proprietaire (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_club (id SERIAL NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, prenom_user VARCHAR(180) NOT NULL, adresse_user VARCHAR(120) NOT NULL, email_user VARCHAR(255) NOT NULL, statut_user VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user_club (username)');
        $this->addSql('CREATE TABLE utilisation_forfait (id SERIAL NOT NULL, forfait_id INT NOT NULL, participation_id INT NOT NULL, date_utilisation DATE NOT NULL, statut_forfait VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B1DC0193906D5F2C ON utilisation_forfait (forfait_id)');
        $this->addSql('CREATE INDEX IDX_B1DC01936ACE3B73 ON utilisation_forfait (participation_id)');
        $this->addSql('COMMENT ON COLUMN utilisation_forfait.date_utilisation IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CA234A5D3 FOREIGN KEY (moniteur_id) REFERENCES moniteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES proprietaire (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cours_forfait ADD CONSTRAINT FK_F8B3ADF97ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cours_forfait ADD CONSTRAINT FK_F8B3ADF9906D5F2C FOREIGN KEY (forfait_id) REFERENCES forfait (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cours_materiel ADD CONSTRAINT FK_DF461E4C7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cours_materiel ADD CONSTRAINT FK_DF461E4C16880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664102A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB2A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB16880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE moniteur ADD CONSTRAINT FK_B3EC8EBABF396750 FOREIGN KEY (id) REFERENCES user_club (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E64D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE panne ADD CONSTRAINT FK_3885B26016880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE proprietaire ADD CONSTRAINT FK_69E399D6BF396750 FOREIGN KEY (id) REFERENCES user_club (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utilisation_forfait ADD CONSTRAINT FK_B1DC0193906D5F2C FOREIGN KEY (forfait_id) REFERENCES forfait (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utilisation_forfait ADD CONSTRAINT FK_B1DC01936ACE3B73 FOREIGN KEY (participation_id) REFERENCES participation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client ADD camping_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404553CC6385 FOREIGN KEY (camping_id) REFERENCES camping (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C74404553CC6385 ON client (camping_id)');
        $this->addSql('ALTER TABLE forfait ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE forfait ADD paiement_id INT NOT NULL');
        $this->addSql('ALTER TABLE forfait ADD CONSTRAINT FK_BBB5C48219EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE forfait ADD CONSTRAINT FK_BBB5C4822A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BBB5C48219EB6921 ON forfait (client_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BBB5C4822A4C4478 ON forfait (paiement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE forfait DROP CONSTRAINT FK_BBB5C4822A4C4478');
        $this->addSql('ALTER TABLE cours DROP CONSTRAINT FK_FDCA8C9CA234A5D3');
        $this->addSql('ALTER TABLE cours DROP CONSTRAINT FK_FDCA8C9C76C50E4A');
        $this->addSql('ALTER TABLE cours_forfait DROP CONSTRAINT FK_F8B3ADF97ECF78B0');
        $this->addSql('ALTER TABLE cours_forfait DROP CONSTRAINT FK_F8B3ADF9906D5F2C');
        $this->addSql('ALTER TABLE cours_materiel DROP CONSTRAINT FK_DF461E4C7ECF78B0');
        $this->addSql('ALTER TABLE cours_materiel DROP CONSTRAINT FK_DF461E4C16880AAF');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE8664102A4C4478');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CB19EB6921');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CB2A4C4478');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CB16880AAF');
        $this->addSql('ALTER TABLE moniteur DROP CONSTRAINT FK_B3EC8EBABF396750');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT FK_B1DC7A1E19EB6921');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT FK_B1DC7A1E7F2DEE08');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT FK_B1DC7A1E64D218E');
        $this->addSql('ALTER TABLE panne DROP CONSTRAINT FK_3885B26016880AAF');
        $this->addSql('ALTER TABLE participation DROP CONSTRAINT FK_AB55E24F19EB6921');
        $this->addSql('ALTER TABLE participation DROP CONSTRAINT FK_AB55E24F7ECF78B0');
        $this->addSql('ALTER TABLE proprietaire DROP CONSTRAINT FK_69E399D6BF396750');
        $this->addSql('ALTER TABLE utilisation_forfait DROP CONSTRAINT FK_B1DC0193906D5F2C');
        $this->addSql('ALTER TABLE utilisation_forfait DROP CONSTRAINT FK_B1DC01936ACE3B73');
        $this->addSql('DROP TABLE compagne_proprietaire');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE cours_forfait');
        $this->addSql('DROP TABLE cours_materiel');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE moniteur');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE panne');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE proprietaire');
        $this->addSql('DROP TABLE user_club');
        $this->addSql('DROP TABLE utilisation_forfait');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C74404553CC6385');
        $this->addSql('DROP INDEX IDX_C74404553CC6385');
        $this->addSql('ALTER TABLE client DROP camping_id');
        $this->addSql('ALTER TABLE forfait DROP CONSTRAINT FK_BBB5C48219EB6921');
        $this->addSql('DROP INDEX IDX_BBB5C48219EB6921');
        $this->addSql('DROP INDEX UNIQ_BBB5C4822A4C4478');
        $this->addSql('ALTER TABLE forfait DROP client_id');
        $this->addSql('ALTER TABLE forfait DROP paiement_id');
    }
}
