<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231124132122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notation ADD formation_id INT DEFAULT NULL, DROP nom_formation');
        $this->addSql('ALTER TABLE notation ADD CONSTRAINT FK_268BC955200282E FOREIGN KEY (formation_id) REFERENCES tbl_formation (id)');
        $this->addSql('CREATE INDEX IDX_268BC955200282E ON notation (formation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notation DROP FOREIGN KEY FK_268BC955200282E');
        $this->addSql('DROP INDEX IDX_268BC955200282E ON notation');
        $this->addSql('ALTER TABLE notation ADD nom_formation VARCHAR(255) NOT NULL, DROP formation_id');
    }
}
