<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250111090522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forfait DROP CONSTRAINT FK_BBB5C48219EB6921');
        $this->addSql('ALTER TABLE forfait ADD CONSTRAINT FK_BBB5C48219EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CB19EB6921');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participation DROP CONSTRAINT FK_AB55E24F19EB6921');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT fk_5e9e89cb19eb6921');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT fk_5e9e89cb19eb6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE forfait DROP CONSTRAINT fk_bbb5c48219eb6921');
        $this->addSql('ALTER TABLE forfait ADD CONSTRAINT fk_bbb5c48219eb6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participation DROP CONSTRAINT fk_ab55e24f19eb6921');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT fk_ab55e24f19eb6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
